<?php

namespace Modules\Ai\Listeners;

use Modules\Ai\Jobs\AnalyzeGifJob;
use Modules\Ai\Jobs\GenerateEmbeddingJob;
use Modules\Gif\Events\GifUploaded;

/**
 * Listens to GifUploaded and dispatches two async jobs:
 *
 *   1. AnalyzeGifJob    — vision analysis + content moderation  (immediate)
 *   2. GenerateEmbeddingJob — embedding vector generation       (30 s delay,
 *        so the embedding API is not hammered simultaneously with the vision call)
 *
 * This listener lives in the Ai module. If Ai is disabled, the event
 * has no listeners and the Gif module is completely unaffected.
 *
 * The listener is NOT queued itself — it dispatches queued jobs. This means
 * the HTTP request returns immediately; the heavy work happens in the worker.
 */
class HandleGifUploaded
{
    public function handle(GifUploaded $event): void
    {
        $gif = $event->gif;

        // Job 1: Vision analysis + moderation (no delay — start ASAP)
        AnalyzeGifJob::dispatch($gif->id, $gif->file_path);

        // Job 2: Embedding (30 s delay — spread API load)
        GenerateEmbeddingJob::dispatch($gif->id, $gif->file_path)
            ->delay(now()->addSeconds(30));
    }
}
