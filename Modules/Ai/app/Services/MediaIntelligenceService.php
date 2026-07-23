<?php

namespace Modules\Ai\Services;

use Modules\Core\Contracts\MediaAnalysisResult;
use Modules\Core\Contracts\MediaIntelligenceInterface;
use Modules\Core\Contracts\ModerationResult;

/**
 * Concrete implementation of MediaIntelligenceInterface.
 *
 * Bound in AiServiceProvider — the Gif module calls this interface
 * without knowing about this class.
 *
 * Delegates to focused services:
 *   VisionAnalysisService  — AI vision (tags, title, moderation flag)
 *   EmbeddingService       — embedding vector generation
 */
class MediaIntelligenceService implements MediaIntelligenceInterface
{
    public function __construct(
        private readonly VisionAnalysisService $vision,
        private readonly EmbeddingService $embeddings,
    ) {}

    public function analyzeMedia(string $storagePath): MediaAnalysisResult
    {
        return $this->vision->analyze($storagePath);
    }

    /**
     * Generate an embedding for a file path.
     *
     * When called from UploadGif with a tmp path (not yet on the public disk),
     * the path is passed as-is. EmbeddingService reads it directly.
     *
     * @return float[]
     */
    public function generateEmbedding(string $filePath): array
    {
        // If the path is a tmp file (starts with /tmp or sys_get_temp_dir),
        // read it directly. Otherwise resolve from public disk.
        if (str_starts_with($filePath, '/') && file_exists($filePath)) {
            return $this->embeddings->forFilePath($filePath);
        }

        return $this->embeddings->forFile($filePath);
    }

    public function checkModeration(string $storagePath): ModerationResult
    {
        return $this->vision->moderate($storagePath);
    }
}
