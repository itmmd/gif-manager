<?php

namespace Modules\Ai\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Modules\Ai\Models\GifAiMetadata;
use Modules\Ai\Services\EmbeddingService;
use Modules\Gif\Models\Gif;

/**
 * Async job that generates and persists a visual embedding vector for a GIF.
 *
 * Runs after AnalyzeGifJob — the gif_ai_metadata row must already exist.
 * Dispatched from HandleGifUploaded with a small delay to avoid hammering
 * the embedding API immediately after the vision analysis job.
 *
 * On failure: logs the error and leaves the embedding column null.
 * Semantic search gracefully falls back to LIKE query when no embedding exists.
 */
class GenerateEmbeddingJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public int $tries = 3;

    public function backoff(): array
    {
        return [30, 60, 120];
    }

    public int $timeout = 60;

    public function __construct(
        public readonly int $gifId,
        public readonly string $storagePath,
    ) {}

    public function handle(EmbeddingService $embeddingService): void
    {
        $gif = Gif::find($this->gifId);

        if (! $gif) {
            return; // GIF deleted — nothing to do
        }

        Log::info('[GenerateEmbeddingJob] Generating embedding', ['gif_id' => $this->gifId]);

        $embedding = $embeddingService->forFile($this->storagePath);

        if (empty($embedding)) {
            Log::warning('[GenerateEmbeddingJob] Empty embedding returned — skipping', ['gif_id' => $this->gifId]);
            return;
        }

        // Upsert — safe if AnalyzeGifJob ran first (it always should) or not.
        GifAiMetadata::updateOrCreate(
            ['gif_id' => $this->gifId],
            ['embedding' => $embedding]
        );

        Log::info('[GenerateEmbeddingJob] Embedding stored', [
            'gif_id'     => $this->gifId,
            'dimensions' => count($embedding),
        ]);
    }

    public function failed(\Throwable $exception): void
    {
        Log::error('[GenerateEmbeddingJob] Permanently failed', [
            'gif_id' => $this->gifId,
            'error'  => $exception->getMessage(),
        ]);
        // embedding remains null — semantic search falls back to LIKE query
    }
}
