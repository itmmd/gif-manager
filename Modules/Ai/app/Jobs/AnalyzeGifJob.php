<?php

namespace Modules\Ai\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Modules\Ai\Models\GifAiMetadata;
use Modules\Ai\Services\VisionAnalysisService;
use Modules\Gif\Models\Gif;

class AnalyzeGifJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public int $tries = 3;

    public int $timeout = 90;

    public function __construct(
        public readonly int $gifId,
        public readonly string $storagePath,
    ) {}

    public function backoff(): array
    {
        return [60, 120, 240];
    }

    public function handle(VisionAnalysisService $vision): void
    {
        $gif = Gif::find($this->gifId);

        if (! $gif) {
            return;
        }

        Log::info('[AnalyzeGifJob] Starting analysis', ['gif_id' => $this->gifId]);

        $analysisResult   = $vision->analyze($this->storagePath);
        $moderationResult = $vision->moderate($this->storagePath);

        GifAiMetadata::updateOrCreate(
            ['gif_id' => $this->gifId],
            [
                'suggested_title'   => $analysisResult->suggestedTitle,
                'suggested_tags'    => $analysisResult->suggestedTags,
                'description'       => $analysisResult->description,
                'moderation_status' => $moderationResult->isFlagged ? 'flagged' : 'approved',
                'moderation_reason' => $moderationResult->reason,
                'analyzed_at'       => now(),
            ]
        );

        $newStatus = $moderationResult->isFlagged ? 'flagged' : 'approved';
        $gif->update(['status' => $newStatus]);

        Log::info('[AnalyzeGifJob] Analysis complete', [
            'gif_id' => $this->gifId,
            'status' => $newStatus,
        ]);
    }

    public function failed(\Throwable $exception): void
    {
        Log::error('[AnalyzeGifJob] Analysis permanently failed', [
            'gif_id' => $this->gifId,
            'error'  => $exception->getMessage(),
        ]);

        $autoApprove = (bool) config('ai.auto_approve_on_failure', true);

        GifAiMetadata::updateOrCreate(
            ['gif_id' => $this->gifId],
            [
                'moderation_status' => $autoApprove ? 'approved' : 'pending',
                'analyzed_at'       => now(),
            ]
        );

        if ($autoApprove) {
            Gif::where('id', $this->gifId)
               ->where('status', 'pending_review')
               ->update(['status' => 'approved']);
        }
    }
}
