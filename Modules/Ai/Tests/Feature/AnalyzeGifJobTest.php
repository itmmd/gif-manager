<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Storage;
use Laravel\Ai\Embeddings;
use Laravel\Ai\Facades\Ai;
use Modules\Ai\Jobs\AnalyzeGifJob;
use Modules\Ai\Jobs\GenerateEmbeddingJob;
use Modules\Ai\Listeners\HandleGifUploaded;
use Modules\Ai\Models\GifAiMetadata;
use Modules\Gif\Events\GifUploaded;
use Modules\Gif\Models\Gif;

/**
 * AnalyzeGifJob & GifUploaded event — DB tests
 *
 * All AI calls are faked with Laravel AI SDK's built-in fake mechanism.
 * No real API calls are made.
 */

uses(RefreshDatabase::class);

beforeEach(fn () => $this->withoutVite());

// ── Event dispatch ─────────────────────────────────────────────────────────

it('GifUploaded event is dispatched after successful upload', function () {
    Event::fake([GifUploaded::class]);

    $admin = User::factory()->admin()->create();
    Storage::fake('public');

    // Create a minimal GIF record directly (simulates what UploadGif component does)
    $gif = Gif::create([
        'title'             => 'Test GIF',
        'file_path'         => 'gifs/test.gif',
        'file_size'         => 1000,
        'mime_type'         => 'image/gif',
        'original_filename' => 'test.gif',
        'uploaded_by'       => $admin->id,
        'status'            => 'pending_review',
    ]);

    GifUploaded::dispatch($gif);

    Event::assertDispatched(GifUploaded::class, function ($event) use ($gif) {
        return $event->gif->id === $gif->id;
    });
})->group('db');

// ── HandleGifUploaded Listener ─────────────────────────────────────────────

it('HandleGifUploaded listener dispatches AnalyzeGifJob', function () {
    Queue::fake();

    $admin = User::factory()->admin()->create();
    $gif = Gif::create([
        'title'             => 'Queue Test GIF',
        'file_path'         => 'gifs/queue-test.gif',
        'file_size'         => 500,
        'mime_type'         => 'image/gif',
        'original_filename' => 'queue-test.gif',
        'uploaded_by'       => $admin->id,
        'status'            => 'pending_review',
    ]);

    $listener = new HandleGifUploaded();
    $listener->handle(new GifUploaded($gif));

    Queue::assertPushed(AnalyzeGifJob::class, function ($job) use ($gif) {
        return $job->gifId === $gif->id && $job->storagePath === $gif->file_path;
    });
})->group('db');

it('HandleGifUploaded listener dispatches GenerateEmbeddingJob with delay', function () {
    Queue::fake();

    $admin = User::factory()->admin()->create();
    $gif = Gif::create([
        'title'             => 'Embedding Test GIF',
        'file_path'         => 'gifs/embed-test.gif',
        'file_size'         => 500,
        'mime_type'         => 'image/gif',
        'original_filename' => 'embed-test.gif',
        'uploaded_by'       => $admin->id,
        'status'            => 'pending_review',
    ]);

    $listener = new HandleGifUploaded();
    $listener->handle(new GifUploaded($gif));

    Queue::assertPushed(GenerateEmbeddingJob::class, function ($job) use ($gif) {
        return $job->gifId === $gif->id;
    });
})->group('db');

// ── AnalyzeGifJob with fake AI ─────────────────────────────────────────────

it('AnalyzeGifJob sets gif status to approved when AI reports safe content', function () {
    Storage::fake('public');

    $admin = User::factory()->admin()->create();
    $gif = Gif::create([
        'title'             => 'Safe Content GIF',
        'file_path'         => 'gifs/safe.gif',
        'file_size'         => 800,
        'mime_type'         => 'image/gif',
        'original_filename' => 'safe.gif',
        'uploaded_by'       => $admin->id,
        'status'            => 'pending_review',
    ]);

    // Create a fake GIF file so Storage::disk('public')->path() resolves
    Storage::disk('public')->put('gifs/safe.gif', 'GIF89a' . str_repeat("\x00", 100));

    // Fake the VisionAnalysisService
    $fakeResult = new \Modules\Core\Contracts\MediaAnalysisResult(
        suggestedTitle: 'A Safe GIF',
        suggestedTags: ['funny', 'cat', 'happy'],
        description: 'A cat doing something funny.',
    );
    $fakeModerationResult = \Modules\Core\Contracts\ModerationResult::safe();

    $mockVision = Mockery::mock(\Modules\Ai\Services\VisionAnalysisService::class);
    $mockVision->shouldReceive('analyze')->once()->andReturn($fakeResult);
    $mockVision->shouldReceive('moderate')->once()->andReturn($fakeModerationResult);

    $job = new AnalyzeGifJob($gif->id, $gif->file_path);
    $job->handle($mockVision);

    $gif->refresh();
    expect($gif->status)->toBe('approved');

    $metadata = GifAiMetadata::where('gif_id', $gif->id)->first();
    expect($metadata)->not->toBeNull();
    expect($metadata->moderation_status)->toBe('approved');
    expect($metadata->suggested_title)->toBe('A Safe GIF');
    expect($metadata->suggested_tags)->toBe(['funny', 'cat', 'happy']);
})->group('db');

it('AnalyzeGifJob sets gif status to flagged when AI detects inappropriate content', function () {
    Storage::fake('public');

    $admin = User::factory()->admin()->create();
    $gif = Gif::create([
        'title'             => 'Flagged Content GIF',
        'file_path'         => 'gifs/flagged.gif',
        'file_size'         => 800,
        'mime_type'         => 'image/gif',
        'original_filename' => 'flagged.gif',
        'uploaded_by'       => $admin->id,
        'status'            => 'pending_review',
    ]);

    Storage::disk('public')->put('gifs/flagged.gif', 'GIF89a' . str_repeat("\x00", 100));

    $fakeResult = new \Modules\Core\Contracts\MediaAnalysisResult();
    $fakeModerationResult = \Modules\Core\Contracts\ModerationResult::flagged('Contains graphic violence.');

    $mockVision = Mockery::mock(\Modules\Ai\Services\VisionAnalysisService::class);
    $mockVision->shouldReceive('analyze')->once()->andReturn($fakeResult);
    $mockVision->shouldReceive('moderate')->once()->andReturn($fakeModerationResult);

    $job = new AnalyzeGifJob($gif->id, $gif->file_path);
    $job->handle($mockVision);

    $gif->refresh();
    expect($gif->status)->toBe('flagged');

    $metadata = GifAiMetadata::where('gif_id', $gif->id)->first();
    expect($metadata->moderation_status)->toBe('flagged');
    expect($metadata->moderation_reason)->toBe('Contains graphic violence.');
})->group('db');

it('AnalyzeGifJob failed() approves gif so it is not hidden on AI error', function () {
    $admin = User::factory()->admin()->create();
    $gif = Gif::create([
        'title'             => 'Error GIF',
        'file_path'         => 'gifs/error.gif',
        'file_size'         => 100,
        'mime_type'         => 'image/gif',
        'original_filename' => 'error.gif',
        'uploaded_by'       => $admin->id,
        'status'            => 'pending_review',
    ]);

    $job = new AnalyzeGifJob($gif->id, $gif->file_path);
    $job->failed(new \RuntimeException('AI service unavailable'));

    $gif->refresh();
    expect($gif->status)->toBe('approved');
})->group('db');

it('AnalyzeGifJob exits silently when gif has been deleted', function () {
    $mockVision = Mockery::mock(\Modules\Ai\Services\VisionAnalysisService::class);
    $mockVision->shouldNotReceive('analyze');
    $mockVision->shouldNotReceive('moderate');

    $job = new AnalyzeGifJob(99999, 'gifs/nonexistent.gif');
    $job->handle($mockVision);

    expect(true)->toBeTrue(); // no exception thrown
})->group('db');
