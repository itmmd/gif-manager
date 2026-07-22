<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Gif\Models\Gif;

/**
 * PublicShow — DB-dependent tests (require pdo_sqlite)
 * Run with: vendor/bin/pest --group=db
 */

uses(RefreshDatabase::class);

beforeEach(fn () => $this->withoutVite());

/**
 * Helper: create a Gif record without a real file.
 * file_path points to a non-existent file — fine for route/rendering tests
 * since the view just outputs the URL, not the actual file bytes.
 */
function makeGif(array $attrs = []): Gif
{
    $user = User::factory()->admin()->create();

    return Gif::create(array_merge([
        'title'             => 'Test GIF',
        'file_path'         => 'gifs/test-uuid.gif',
        'file_size'         => 12345,
        'mime_type'         => 'image/gif',
        'original_filename' => 'test.gif',
        'uploaded_by'       => $user->id,
    ], $attrs));
}

// ── Page loads ────────────────────────────────────────────────────────────

it('detail page returns 200 for a valid slug', function () {
    $gif = makeGif(['title' => 'My Test GIF']);

    $this->get(route('gifs.show', $gif))
         ->assertStatus(200);
})->group('db');

it('detail page title contains the gif title', function () {
    $gif = makeGif(['title' => 'Awesome Cat Reaction']);

    $this->get(route('gifs.show', $gif))
         ->assertSee('Awesome Cat Reaction');
})->group('db');

it('detail page shows download button', function () {
    $gif = makeGif();

    $this->get(route('gifs.show', $gif))
         ->assertSee('Download');
})->group('db');

it('route uses slug not numeric id in URL', function () {
    $gif = makeGif(['title' => 'Slug Test GIF']);

    $url = route('gifs.show', $gif);

    // URL must contain the slug, not /gifs/1 or /gifs/9
    expect($url)->toContain($gif->slug)
                ->not->toMatch('/\/gifs\/\d+$/');
})->group('db');

it('slug is auto-generated on create', function () {
    $gif = makeGif(['title' => 'Hello World']);

    expect($gif->slug)->not->toBeNull()
                      ->toStartWith('hello-world');
})->group('db');

it('slug survives title update (existing URL not broken)', function () {
    $gif  = makeGif(['title' => 'Original Title']);
    $originalSlug = $gif->slug;

    $gif->update(['title' => 'Updated Title']);

    expect($gif->fresh()->slug)->toBe($originalSlug);
})->group('db');
