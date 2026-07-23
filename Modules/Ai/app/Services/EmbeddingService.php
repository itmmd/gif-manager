<?php

namespace Modules\Ai\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Laravel\Ai\Embeddings;
use Laravel\Ai\Files\Image;

/**
 * Generates embedding vectors for GIF/MP4 files and for text queries.
 *
 * Embeddings are stored as JSON float[] in gif_ai_metadata.embedding.
 * On PostgreSQL this could be upgraded to pgvector; for now JSON works
 * well enough for the expected dataset size.
 *
 * Circuit-breaker: all exceptions are caught — callers receive an empty
 * array, which disables semantic features gracefully.
 */
class EmbeddingService
{
    /**
     * Generate a visual embedding for a stored GIF/MP4.
     *
     * @param  string  $storagePath  Relative path on the public disk (e.g. "gifs/uuid.gif")
     * @return float[]  Empty array on failure.
     */
    public function forFile(string $storagePath): array
    {
        try {
            $absolutePath = Storage::disk('public')->path($storagePath);
            $mimeType = $this->detectMime($absolutePath);

            $response = Embeddings::for([Image::fromPath($absolutePath, $mimeType)])
                ->generate();

            return $response->first();

        } catch (\Throwable $e) {
            Log::warning('[EmbeddingService] File embedding failed', [
                'path'  => $storagePath,
                'error' => $e->getMessage(),
            ]);

            return [];
        }
    }

    /**
     * Generate an embedding for an absolute filesystem path (e.g. tmp upload files).
     *
     * @param  string  $absolutePath  Absolute path, must exist and be readable.
     * @return float[]  Empty array on failure.
     */
    public function forFilePath(string $absolutePath): array
    {
        try {
            $mimeType = $this->detectMime($absolutePath);

            $response = Embeddings::for([Image::fromPath($absolutePath, $mimeType)])
                ->generate();

            return $response->first();

        } catch (\Throwable $e) {
            Log::warning('[EmbeddingService] File path embedding failed', [
                'path'  => $absolutePath,
                'error' => $e->getMessage(),
            ]);

            return [];
        }
    }

    /**
     * Generate a text embedding for a search query.
     *
     * Results are cached for 1 hour to keep latency low for repeated queries.
     *
     * @param  string  $query  User search string (sanitised by the caller)
     * @return float[]  Empty array on failure.
     */
    public function forQuery(string $query): array
    {
        $cacheKey = 'ai.embedding.' . md5($query);

        return cache()->remember($cacheKey, 3600, function () use ($query) {
            try {
                $response = Embeddings::for([$query])
                    ->cache(3600)   // SDK-level caching as well
                    ->generate();

                return $response->first();

            } catch (\Throwable $e) {
                Log::warning('[EmbeddingService] Query embedding failed', [
                    'query' => $query,
                    'error' => $e->getMessage(),
                ]);

                return [];
            }
        });
    }

    /**
     * Cosine similarity between two float vectors (range: −1 to 1).
     * Returns 0.0 when either vector is empty or all-zeros.
     *
     * @param  float[]  $a
     * @param  float[]  $b
     */
    public function cosineSimilarity(array $a, array $b): float
    {
        if (empty($a) || empty($b)) {
            return 0.0;
        }

        $dot   = 0.0;
        $normA = 0.0;
        $normB = 0.0;

        $len = min(count($a), count($b));
        for ($i = 0; $i < $len; $i++) {
            $dot   += $a[$i] * $b[$i];
            $normA += $a[$i] ** 2;
            $normB += $b[$i] ** 2;
        }

        if ($normA === 0.0 || $normB === 0.0) {
            return 0.0;
        }

        return $dot / (sqrt($normA) * sqrt($normB));
    }

    private function detectMime(string $absolutePath): string
    {
        $handle = fopen($absolutePath, 'rb');
        $header = fread($handle, 12);
        fclose($handle);

        if (str_starts_with($header, 'GIF87a') || str_starts_with($header, 'GIF89a')) {
            return 'image/gif';
        }

        if (substr($header, 4, 4) === 'ftyp') {
            return 'video/mp4';
        }

        $finfo = new \finfo(FILEINFO_MIME_TYPE);
        return $finfo->file($absolutePath) ?: 'application/octet-stream';
    }
}
