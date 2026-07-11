<?php

namespace Modules\Core\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

/**
 * Sample queue job for Core module.
 *
 * این Job نمونه‌ای از پردازش‌های سنگین (مثل پردازش GIF در آینده) است.
 * از queue driver پیکربندی‌شده (database) استفاده می‌کنه — یعنی
 * dispatch() آن را در جدول jobs ذخیره می‌کنه و worker جداگانه پردازش می‌کنه.
 *
 * استفاده:
 *   ProcessMediaJob::dispatch('path/to/file.gif');
 */
class ProcessMediaJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /**
     * Number of times the job may be attempted.
     */
    public int $tries = 3;

    /**
     * Maximum seconds the job should run.
     */
    public int $timeout = 120;

    public function __construct(
        public readonly string $filePath,
        public readonly array $options = []
    ) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Log::info('[ProcessMediaJob] Processing media file', [
            'file' => $this->filePath,
            'options' => $this->options,
        ]);

        // جای واقعی پردازش GIF در آینده اینجاست
        // مثلاً: resize, optimize, extract frames, ...
    }
}
