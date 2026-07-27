<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(fn () => $this->withoutVite());

it('returns 200 for admin user', function () {
    $this->actingAs(User::factory()->admin()->create())
         ->get('/admin')
         ->assertStatus(200);
})->group('db');

it('renders the sidebar in admin layout', function () {
    $this->actingAs(User::factory()->admin()->create())
         ->get('/admin')
         ->assertSee('sidebar', escape: false);
})->group('db');

it('references gentelella css asset in admin layout', function () {
    $this->actingAs(User::factory()->admin()->create())
         ->get('/admin')
         ->assertSee('admin-assets/css', escape: false);
})->group('db');

it('returns 403 for regular user', function () {
    $this->actingAs(User::factory()->create())
         ->get('/admin')
         ->assertStatus(403);
})->group('db');
