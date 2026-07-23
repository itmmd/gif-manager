<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Ai\Services\EmbeddingService;
use Modules\Core\Contracts\MediaIntelligenceInterface;
use Modules\Gif\Models\Gif;

/**
 * Duplicate Detection — DB tests
 *
 * Tests the cosine similarity logic and the duplicate-check flow
 * in UploadGif without making real AI calls.
 */

uses(RefreshDatabase::class);

beforeEach(fn () => $this->withoutVite());

// ── Cosine similarity (unit-level, no DB) ─────────────────────────────────

it('EmbeddingService returns 1.0 for identical vectors', function () {
    $service = new EmbeddingService();
    $vector  = [0.5, 0.8, 0.3, 0.9];

    expect($service->cosineSimilarity($vector, $vector))->toBe(1.0);
});

it('EmbeddingService returns 0.0 for orthogonal vectors', function () {
    $service = new EmbeddingService();
    $a = [1.0, 0.0];
    $b = [0.0, 1.0];

    expect($service->cosineSimilarity($a, $b))->toEqualWithDelta(0.0, 0.0001);
});

it('EmbeddingService returns 0.0 for empty vectors', function () {
    $service = new EmbeddingService();

    expect($service->cosineSimilarity([], [0.1, 0.2]))->toBe(0.0);
    expect($service->cosineSimilarity([0.1, 0.2], []))->toBe(0.0);
    expect($service->cosineSimilarity([], []))->toBe(0.0);
});

it('EmbeddingService similarity is above 0.9 for nearly-identical vectors', function () {
    $service = new EmbeddingService();
    $base  = [0.9, 0.1, 0.5, 0.8, 0.3];
    $near  = [0.91, 0.11, 0.51, 0.79, 0.31]; // slightly perturbed

    expect($service->cosineSimilarity($base, $near))->toBeGreaterThan(0.99);
});

// ── Duplicate detection logic ─────────────────────────────────────────────

it('duplicate check returns null when no embeddings are stored', function () {
    $admin = User::factory()->admin()->create();

    // Bind a fake MediaIntelligenceInterface that returns a non-empty embedding
    $mockIntelligence = Mockery::mock(MediaIntelligenceInterface::class);
    $mockIntelligence->shouldReceive('generateEmbedding')
        ->once()
        ->andReturn([0.5, 0.8, 0.3]);

    app()->bind(MediaIntelligenceInterface::class, fn () => $mockIntelligence);

    // No gif_ai_metadata rows exist — should return null
    $reflector = new \ReflectionClass(\Modules\Gif\Http\Livewire\UploadGif::class);
    $component = $reflector->newInstanceWithoutConstructor();

    $method = $reflector->getMethod('checkDuplicate');
    $method->setAccessible(true);

    $result = $method->invoke($component, '/tmp/fake.gif');

    expect($result)->toBeNull();
})->group('db');

it('duplicate check returns gif title when similarity exceeds 92%', function () {
    $admin = User::factory()->admin()->create();

    $existingGif = Gif::create([
        'title'             => 'Existing Similar GIF',
        'file_path'         => 'gifs/existing.gif',
        'file_size'         => 1000,
        'mime_type'         => 'image/gif',
        'original_filename' => 'existing.gif',
        'uploaded_by'       => $admin->id,
        'status'            => 'approved',
    ]);

    // Store a known embedding for the existing GIF
    $storedVector = [0.9, 0.1, 0.5, 0.8, 0.3];
    \DB::table('gif_ai_metadata')->insert([
        'gif_id'            => $existingGif->id,
        'embedding'         => json_encode($storedVector),
        'moderation_status' => 'approved',
        'created_at'        => now(),
        'updated_at'        => now(),
    ]);

    // Query embedding is nearly identical → similarity > 0.92
    $nearlyIdenticalVector = [0.9, 0.11, 0.5, 0.8, 0.3];

    $mockIntelligence = Mockery::mock(MediaIntelligenceInterface::class);
    $mockIntelligence->shouldReceive('generateEmbedding')
        ->once()
        ->andReturn($nearlyIdenticalVector);

    app()->bind(MediaIntelligenceInterface::class, fn () => $mockIntelligence);

    $reflector = new \ReflectionClass(\Modules\Gif\Http\Livewire\UploadGif::class);
    $component = $reflector->newInstanceWithoutConstructor();

    $method = $reflector->getMethod('checkDuplicate');
    $method->setAccessible(true);

    $result = $method->invoke($component, '/tmp/new.gif');

    expect($result)->toBe('Existing Similar GIF');
})->group('db');

it('duplicate check returns null when similarity is below 92%', function () {
    $admin = User::factory()->admin()->create();

    $existingGif = Gif::create([
        'title'             => 'Different GIF',
        'file_path'         => 'gifs/different.gif',
        'file_size'         => 1000,
        'mime_type'         => 'image/gif',
        'original_filename' => 'different.gif',
        'uploaded_by'       => $admin->id,
        'status'            => 'approved',
    ]);

    $storedVector = [0.9, 0.1, 0.5, 0.8, 0.3];
    \DB::table('gif_ai_metadata')->insert([
        'gif_id'            => $existingGif->id,
        'embedding'         => json_encode($storedVector),
        'moderation_status' => 'approved',
        'created_at'        => now(),
        'updated_at'        => now(),
    ]);

    // Very different vector → low similarity
    $differentVector = [0.1, 0.9, 0.2, 0.3, 0.7];

    $mockIntelligence = Mockery::mock(MediaIntelligenceInterface::class);
    $mockIntelligence->shouldReceive('generateEmbedding')
        ->once()
        ->andReturn($differentVector);

    app()->bind(MediaIntelligenceInterface::class, fn () => $mockIntelligence);

    $reflector = new \ReflectionClass(\Modules\Gif\Http\Livewire\UploadGif::class);
    $component = $reflector->newInstanceWithoutConstructor();

    $method = $reflector->getMethod('checkDuplicate');
    $method->setAccessible(true);

    $result = $method->invoke($component, '/tmp/unique.gif');

    expect($result)->toBeNull();
})->group('db');

it('duplicate check returns null gracefully when AI module is not bound', function () {
    // Unbind the interface so app()->bound() returns false
    app()->forgetInstance(MediaIntelligenceInterface::class);

    $reflector = new \ReflectionClass(\Modules\Gif\Http\Livewire\UploadGif::class);
    $component = $reflector->newInstanceWithoutConstructor();

    $method = $reflector->getMethod('checkDuplicate');
    $method->setAccessible(true);

    $result = $method->invoke($component, '/tmp/test.gif');

    expect($result)->toBeNull();
});
