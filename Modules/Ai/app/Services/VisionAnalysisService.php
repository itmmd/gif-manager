<?php

namespace Modules\Ai\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Laravel\Ai\Files\Image;
use Modules\Ai\Agents\VisionAnalysisAgent;
use Modules\Core\Contracts\MediaAnalysisResult;
use Modules\Core\Contracts\ModerationResult;

class VisionAnalysisService
{
    public function analyze(string $storagePath): MediaAnalysisResult
    {
        try {
            $absolutePath = Storage::disk('public')->path($storagePath);

            $response = VisionAnalysisAgent::make()->prompt(
                prompt:      'Analyse this image and return the required JSON.',
                attachments: [Image::fromPath($absolutePath, $this->detectMime($absolutePath))],
            );

            return MediaAnalysisResult::fromArray($response->structured ?? []);
        } catch (\Throwable $e) {
            Log::warning('[VisionAnalysisService] Analysis failed', [
                'path'  => $storagePath,
                'error' => $e->getMessage(),
            ]);

            return new MediaAnalysisResult();
        }
    }

    public function moderate(string $storagePath): ModerationResult
    {
        try {
            $absolutePath = Storage::disk('public')->path($storagePath);

            $response = VisionAnalysisAgent::make()->prompt(
                prompt:      'Analyse this image and return the required JSON.',
                attachments: [Image::fromPath($absolutePath, $this->detectMime($absolutePath))],
            );

            $data = $response->structured ?? [];

            if (! empty($data['is_flagged'])) {
                return ModerationResult::flagged(
                    isset($data['flag_reason']) ? (string) $data['flag_reason'] : 'Content flagged by AI moderation.'
                );
            }

            return ModerationResult::safe();
        } catch (\Throwable $e) {
            Log::warning('[VisionAnalysisService] Moderation check failed', [
                'path'  => $storagePath,
                'error' => $e->getMessage(),
            ]);

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

        return (new \finfo(FILEINFO_MIME_TYPE))->file($absolutePath) ?: 'application/octet-stream';
    }
}
