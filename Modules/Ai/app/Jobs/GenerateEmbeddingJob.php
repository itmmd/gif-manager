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

class GenerateEmbeddingJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public int $tries = 3;

    public int $timeout = 60;

    public function __construct(
        public readonly int $gifId,
        public readonly string $storagePath,
    ) {}

    public function backoff(): array
    {
        return [30, 60, 120];
    }

    public function handle(EmbeddingService $embeddingService): void
    {
        if (! Gif::find($this->gifId)) {
            return;
        }

        Log::info('[GenerateEmbeddingJob] Generating embedding', ['gif_id' => $this->gifId]);

        $embedding = $embeddingService->forFile($this->storagePath);

        if (empty($embedding)) {
            Log::warning('[GenerateEmbeddingJob] Empty embedding returned', ['gif_id' => $this->gifId]);
            return;
        }

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
    }
}
