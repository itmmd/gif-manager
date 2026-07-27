<?php

namespace Modules\Ai\Listeners;

use Modules\Ai\Jobs\AnalyzeGifJob;
use Modules\Ai\Jobs\GenerateEmbeddingJob;
use Modules\Gif\Events\GifUploaded;

class HandleGifUploaded
{
    public function handle(GifUploaded $event): void
    {
        $gif = $event->gif;

        AnalyzeGifJob::dispatch($gif->id, $gif->file_path);

        GenerateEmbeddingJob::dispatch($gif->id, $gif->file_path)
            ->delay(now()->addSeconds(30));
    }
}
