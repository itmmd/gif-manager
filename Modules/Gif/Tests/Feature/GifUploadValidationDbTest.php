<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * GIF Upload Validation — DB-dependent tests (require pdo_sqlite)
 * Run with: vendor/bin/pest --group=db
 */

uses(RefreshDatabase::class);

beforeEach(fn () => $this->withoutVite());

// ── Upload page access ────────────────────────────────────────────────────

it('admin can access the upload page', function () {
    $admin = User::factory()->admin()->create();

    $this->actingAs($admin)
         ->get('/admin/gifs/upload')
         ->assertStatus(200);
})->group('db');

it('upload page contains title input', function () {
    $admin = User::factory()->admin()->create();

    $this->actingAs($admin)
         ->get('/admin/gifs/upload')
         ->assertSee('id="title"', escape: false);
})->group('db');

it('upload page contains dropzone', function () {
    $admin = User::factory()->admin()->create();

    $this->actingAs($admin)
         ->get('/admin/gifs/upload')
         ->assertSee('dropzone');
})->group('db');

it('upload page contains brand upload button', function () {
    $admin = User::factory()->admin()->create();

    $this->actingAs($admin)
         ->get('/admin/gifs/upload')
         ->assertSee('btn-brand', escape: false);
})->group('db');

it('regular user cannot access upload page', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
         ->get('/admin/gifs/upload')
         ->assertStatus(403);
})->group('db');

// ── Slug auto-generation on create ───────────────────────────────────────

it('gif created with persian title gets a slug', function () {
    $user  = User::factory()->admin()->create();
    $gif   = \Modules\Gif\Models\Gif::create([
        'title'             => 'واکنش خنده‌دار',
        'file_path'         => 'gifs/test.gif',
        'file_size'         => 1000,
        'mime_type'         => 'image/gif',
        'original_filename' => 'test.gif',
        'uploaded_by'       => $user->id,
    ]);

    // Slug must be generated (non-empty) even for non-ASCII title
    expect($gif->slug)->not->toBeEmpty();
})->group('db');

it('gif created with emoji title gets a slug', function () {
    $user  = User::factory()->admin()->create();
    $gif   = \Modules\Gif\Models\Gif::create([
        'title'             => '🎉 Party GIF',
        'file_path'         => 'gifs/party.gif',
        'file_size'         => 2000,
        'mime_type'         => 'image/gif',
        'original_filename' => 'party.gif',
        'uploaded_by'       => $user->id,
    ]);

    expect($gif->slug)->not->toBeEmpty();
})->group('db');

it('two gifs with the same title get different slugs', function () {
    $user = User::factory()->admin()->create();
    $base = [
        'file_path'         => 'gifs/x.gif',
        'file_size'         => 500,
        'mime_type'         => 'image/gif',
        'original_filename' => 'x.gif',
        'uploaded_by'       => $user->id,
    ];

    $gif1 = \Modules\Gif\Models\Gif::create(array_merge($base, ['title' => 'Same Title']));
    $gif2 = \Modules\Gif\Models\Gif::create(array_merge($base, ['title' => 'Same Title', 'file_path' => 'gifs/y.gif']));

    expect($gif1->slug)->not->toBe($gif2->slug);
})->group('db');
