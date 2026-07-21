<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * Admin Dashboard — DB-dependent tests
 * Require pdo_sqlite. Run with: vendor/bin/pest --group=db
 */

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->withoutVite();
});

it('returns 200 for admin user', function () {
    $admin = User::factory()->admin()->create();

    $this->actingAs($admin)->get('/admin')->assertStatus(200);
})->group('db');

it('renders the sidebar in admin layout', function () {
    $admin = User::factory()->admin()->create();

    $this->actingAs($admin)->get('/admin')->assertSee('sidebar', escape: false);
})->group('db');

it('references gentelella css asset in admin layout', function () {
    $admin = User::factory()->admin()->create();

    $this->actingAs($admin)->get('/admin')->assertSee('admin-assets/css', escape: false);
})->group('db');

it('returns 403 for regular user', function () {
    $user = User::factory()->create();

    $this->actingAs($user)->get('/admin')->assertStatus(403);
})->group('db');
