<?php

namespace Modules\Ai\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Laravel\Ai\Embeddings;
use Laravel\Ai\Files\Image;

class EmbeddingService
{
    public function forFile(string $storagePath): array
    {
        try {
            $absolutePath = Storage::disk('public')->path($storagePath);

            return Embeddings::for([Image::fromPath($absolutePath, $this->detectMime($absolutePath))])
                ->generate()
                ->first();
        } catch (\Throwable $e) {
            Log::warning('[EmbeddingService] File embedding failed', [
                'path'  => $storagePath,
                'error' => $e->getMessage(),
            ]);

            return [];
        }
    }

    public function forFilePath(string $absolutePath): array
    {
        try {
            return Embeddings::for([Image::fromPath($absolutePath, $this->detectMime($absolutePath))])
                ->generate()
                ->first();
        } catch (\Throwable $e) {
            Log::warning('[EmbeddingService] File path embedding failed', [
                'path'  => $absolutePath,
                'error' => $e->getMessage(),
            ]);

            return [];
        }
    }

    public function forQuery(string $query): array
    {
        return cache()->remember('ai.embedding.' . md5($query), 3600, function () use ($query) {
            try {
                return Embeddings::for([$query])->cache(3600)->generate()->first();
            } catch (\Throwable $e) {
                Log::warning('[EmbeddingService] Query embedding failed', [
                    'query' => $query,
                    'error' => $e->getMessage(),
                ]);

                return [];
            }
        });
    }

    public function cosineSimilarity(array $a, array $b): float
    {
        if (empty($a) || empty($b)) {
            return 0.0;
        }

        $dot = $normA = $normB = 0.0;
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

        return (new \finfo(FILEINFO_MIME_TYPE))->file($absolutePath) ?: 'application/octet-stream';
    }
}
