<?php

namespace Modules\Core\Contracts;

interface MediaIntelligenceInterface
{
    public function analyzeMedia(string $storagePath): MediaAnalysisResult;

    public function generateEmbedding(string $storagePath): array;

    public function checkModeration(string $storagePath): ModerationResult;
}
