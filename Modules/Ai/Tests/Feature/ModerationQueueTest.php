<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Modules\Gif\Models\Gif;

uses(RefreshDatabase::class);

beforeEach(fn () => $this->withoutVite());

it('moderation route requires auth and admin middleware', function () {
    $this->get('/admin/ai/moderation')->assertRedirect('/login');
});

it('regular user cannot access moderation queue', function () {
    $this->actingAs(User::factory()->create())
         ->get('/admin/ai/moderation')
         ->assertStatus(403);
})->group('db');

it('admin can access moderation queue', function () {
    $this->actingAs(User::factory()->admin()->create())
         ->get('/admin/ai/moderation')
         ->assertStatus(200);
})->group('db');

it('Gif::flagged() scope returns only flagged gifs', function () {
    $user = User::factory()->admin()->create();
    $base = ['file_path' => 'gifs/x.gif', 'file_size' => 100, 'mime_type' => 'image/gif', 'original_filename' => 'x.gif', 'uploaded_by' => $user->id];

    Gif::create(array_merge($base, ['title' => 'Approved', 'status' => 'approved',       'file_path' => 'gifs/a.gif']));
    Gif::create(array_merge($base, ['title' => 'Pending',  'status' => 'pending_review', 'file_path' => 'gifs/b.gif']));
    Gif::create(array_merge($base, ['title' => 'Flagged',  'status' => 'flagged',        'file_path' => 'gifs/c.gif']));

    $flagged = Gif::flagged()->get();

    expect($flagged)->toHaveCount(1)
        ->and($flagged->first()->title)->toBe('Flagged');
})->group('db');

it('Gif::approved() scope filters out pending and flagged gifs', function () {
    $user = User::factory()->admin()->create();
    $base = ['file_size' => 100, 'mime_type' => 'image/gif', 'original_filename' => 'x.gif', 'uploaded_by' => $user->id];

    Gif::create(array_merge($base, ['title' => 'Approved', 'status' => 'approved',       'file_path' => 'gifs/a.gif']));
    Gif::create(array_merge($base, ['title' => 'Pending',  'status' => 'pending_review', 'file_path' => 'gifs/b.gif']));
    Gif::create(array_merge($base, ['title' => 'Flagged',  'status' => 'flagged',        'file_path' => 'gifs/c.gif']));

    expect(Gif::approved()->count())->toBe(1);
})->group('db');

it('admin can approve a flagged gif', function () {
    Storage::fake('public');
    $admin = User::factory()->admin()->create();

    $gif = Gif::create([
        'title'             => 'Flagged GIF',
        'file_path'         => 'gifs/flag.gif',
        'file_size'         => 500,
        'mime_type'         => 'image/gif',
        'original_filename' => 'flag.gif',
        'uploaded_by'       => $admin->id,
        'status'            => 'flagged',
    ]);

    $this->actingAs($admin);

    \Livewire\Livewire::test(\Modules\Ai\Http\Livewire\ModerationQueue::class)
        ->call('approve', $gif->id);

    expect($gif->fresh()->status)->toBe('approved');
})->group('db');

it('admin can reject (delete) a flagged gif', function () {
    Storage::fake('public');
    $admin = User::factory()->admin()->create();
    Storage::disk('public')->put('gifs/delete-me.gif', 'GIF89a');

    $gif = Gif::create([
        'title'             => 'Delete Me GIF',
        'file_path'         => 'gifs/delete-me.gif',
        'file_size'         => 100,
        'mime_type'         => 'image/gif',
        'original_filename' => 'delete-me.gif',
        'uploaded_by'       => $admin->id,
        'status'            => 'flagged',
    ]);

    $this->actingAs($admin);

    \Livewire\Livewire::test(\Modules\Ai\Http\Livewire\ModerationQueue::class)
        ->call('reject', $gif->id);

    expect(Gif::find($gif->id))->toBeNull();
    Storage::disk('public')->assertMissing('gifs/delete-me.gif');
})->group('db');

it('public gallery only shows approved gifs', function () {
    $user = User::factory()->admin()->create();
    $base = ['file_size' => 100, 'mime_type' => 'image/gif', 'original_filename' => 'x.gif', 'uploaded_by' => $user->id];

    Gif::create(array_merge($base, ['title' => 'Visible',        'status' => 'approved',       'file_path' => 'gifs/v.gif']));
    Gif::create(array_merge($base, ['title' => 'Hidden Pending', 'status' => 'pending_review', 'file_path' => 'gifs/p.gif']));
    Gif::create(array_merge($base, ['title' => 'Hidden Flagged', 'status' => 'flagged',        'file_path' => 'gifs/f.gif']));

    $this->get('/gifs')
         ->assertSee('Visible')
         ->assertDontSee('Hidden Pending')
         ->assertDontSee('Hidden Flagged');
})->group('db');
