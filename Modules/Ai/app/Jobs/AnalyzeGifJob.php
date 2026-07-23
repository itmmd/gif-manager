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

/**
 * Async job that:
 *   1. Calls VisionAnalysisService to get title/tags/description + moderation flag.
 *   2. Persists results to gif_ai_metadata.
 *   3. Updates gif.status based on moderation:
 *        - flagged   → gif.status = 'flagged'  (hidden from public gallery)
 *        - safe      → gif.status = 'approved' (visible in public gallery)
 *
 * The job is intentionally lenient:
 *   - It retries up to 3 times with exponential back-off (for rate limits).
 *   - On final failure, it marks the GIF as 'approved' (not 'flagged') so
 *     the admin can still see/delete it; content defaults to visible.
 *
 * If the Gif row has been deleted before this job runs, it exits silently.
 */
class AnalyzeGifJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /** Max attempts before the job is considered failed. */
    public int $tries = 3;

    /** Seconds between retry attempts (exponential back-off: 60, 120, 240). */
    public function backoff(): array
    {
        return [60, 120, 240];
    }

    /** Maximum execution time in seconds. */
    public int $timeout = 90;

    public function __construct(
        public readonly int $gifId,
        public readonly string $storagePath,
    ) {}

    public function handle(VisionAnalysisService $vision): void
    {
        $gif = Gif::find($this->gifId);

        if (! $gif) {
            // GIF deleted before analysis ran — nothing to do.
            return;
        }

        Log::info('[AnalyzeGifJob] Starting analysis', ['gif_id' => $this->gifId]);

        // --- Run vision analysis (includes moderation check in one API call) ---
        $analysisResult  = $vision->analyze($this->storagePath);
        $moderationResult = $vision->moderate($this->storagePath);

        // --- Persist to gif_ai_metadata (upsert — safe to re-run) ---
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

        // --- Update GIF publication status ---
        $newStatus = $moderationResult->isFlagged ? 'flagged' : 'approved';
        $gif->update(['status' => $newStatus]);

        Log::info('[AnalyzeGifJob] Analysis complete', [
            'gif_id' => $this->gifId,
            'status' => $newStatus,
        ]);
    }

    /**
     * Called when all retries are exhausted.
     * Gracefully marks the metadata row as analyzed (with empty data) and
     * approves the GIF so the admin can at least see and manage it.
     */
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
        // else: GIF stays in 'pending_review' — admin must manually approve
    }
}
