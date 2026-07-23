<?php

namespace Modules\Ai\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Laravel\Ai\Files\Image;
use Modules\Ai\Agents\VisionAnalysisAgent;
use Modules\Core\Contracts\MediaAnalysisResult;
use Modules\Core\Contracts\ModerationResult;

/**
 * Calls the AI vision model to analyse a GIF/MP4's first frame.
 *
 * For GIFs: reads the file as an image (first frame is sufficient for analysis).
 * For MP4s: treats the file as an image attachment; the AI provider extracts
 *           a representative frame automatically for vision models.
 *
 * Circuit-breaker behaviour: ALL exceptions are caught and empty / safe results
 * are returned so that a failing AI service never blocks an upload.
 */
class VisionAnalysisService
{
    /**
     * Analyse a stored media file and return suggested metadata.
     *
     * @param  string  $storagePath  Relative path on the public disk (e.g. "gifs/uuid.gif")
     */
    public function analyze(string $storagePath): MediaAnalysisResult
    {
        try {
            $absolutePath = Storage::disk('public')->path($storagePath);

            $mimeType = $this->detectMime($absolutePath);

            $response = VisionAnalysisAgent::make()
                ->prompt(
                    prompt: 'Analyse this image and return the required JSON.',
                    attachments: [Image::fromPath($absolutePath, $mimeType)],
                );

            /** @var array<string, mixed> $data */
            $data = $response->structured ?? [];

            return MediaAnalysisResult::fromArray($data);

        } catch (\Throwable $e) {
            Log::warning('[VisionAnalysisService] Analysis failed', [
                'path'  => $storagePath,
                'error' => $e->getMessage(),
            ]);

            return new MediaAnalysisResult(); // empty result — graceful degradation
        }
    }

    /**
     * Check whether the stored file is appropriate for public display.
     *
     * @param  string  $storagePath  Relative path on the public disk
     */
    public function moderate(string $storagePath): ModerationResult
    {
        try {
            $absolutePath = Storage::disk('public')->path($storagePath);
            $mimeType = $this->detectMime($absolutePath);

            $response = VisionAnalysisAgent::make()
                ->prompt(
                    prompt: 'Analyse this image and return the required JSON.',
                    attachments: [Image::fromPath($absolutePath, $mimeType)],
                );

            /** @var array<string, mixed> $data */
            $data = $response->structured ?? [];

            if (! empty($data['is_flagged'])) {
                $reason = isset($data['flag_reason']) ? (string) $data['flag_reason'] : 'Content flagged by AI moderation.';
                return ModerationResult::flagged($reason);
            }

            return ModerationResult::safe();

        } catch (\Throwable $e) {
            Log::warning('[VisionAnalysisService] Moderation check failed', [
                'path'  => $storagePath,
                'error' => $e->getMessage(),
            ]);

            // On failure: do NOT flag the content — let the admin review manually.
            return ModerationResult::safe();
        }
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

        // fallback: use PHP's finfo
        $finfo = new \finfo(FILEINFO_MIME_TYPE);
        return $finfo->file($absolutePath) ?: 'application/octet-stream';
    }
}
