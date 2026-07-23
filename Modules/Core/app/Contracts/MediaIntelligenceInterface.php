<?php

namespace Modules\Core\Contracts;

/**
 * Contract between the Gif module and the Ai module.
 *
 * The Gif module depends on this interface (in Core) — it never imports
 * anything from Modules\Ai directly. The Ai module provides the concrete
 * implementation and registers the binding in its service provider.
 *
 * Dependency graph (compile-time):
 *   Gif → Core\Contracts\MediaIntelligenceInterface ← Ai (implementation)
 *
 * If the Ai module is disabled or removed, all callers must gracefully
 * handle a missing binding (e.g. app()->bound() check) and fall back to
 * returning nulls / empty collections.
 */
interface MediaIntelligenceInterface
{
    /**
     * Analyse the first frame of a GIF/MP4 and return suggested metadata.
     *
     * @param  string  $storagePath  Path on the public disk (e.g. "gifs/uuid.gif")
     * @return MediaAnalysisResult
     */
    public function analyzeMedia(string $storagePath): MediaAnalysisResult;

    /**
     * Generate a float[] embedding vector for the given file.
     *
     * Used for:
     *   - Semantic search (query ↔ GIF similarity)
     *   - Duplicate detection (GIF ↔ GIF similarity)
     *
     * @param  string  $storagePath  Path on the public disk
     * @return float[]
     */
    public function generateEmbedding(string $storagePath): array;

    /**
     * Check whether the file content is appropriate for public display.
     *
     * @param  string  $storagePath  Path on the public disk
     * @return ModerationResult
     */
    public function checkModeration(string $storagePath): ModerationResult;
}
