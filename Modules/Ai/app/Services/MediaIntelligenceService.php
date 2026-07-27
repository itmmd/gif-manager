<?php

namespace Modules\Ai\Services;

use Modules\Core\Contracts\MediaAnalysisResult;
use Modules\Core\Contracts\MediaIntelligenceInterface;
use Modules\Core\Contracts\ModerationResult;

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

    public function generateEmbedding(string $filePath): array
    {
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
