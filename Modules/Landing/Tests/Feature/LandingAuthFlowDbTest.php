<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * Landing Page — Auth Flow Tests (DB required)
 * -----------------------------------------------------------------------
 * These tests use User::factory() and require pdo_sqlite (or a real DB).
 *
 * Run with: vendor/bin/pest --group=db
 * Skip DB tests: vendor/bin/pest --exclude-group=db
 *
 * In CI (GitHub Actions), pdo_sqlite is listed in the workflow extensions,
 * so all tests including this file will run.
 */

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->withoutVite();
});

// ── isAdmin() helper ──────────────────────────────────────────────────────

it('User isAdmin returns true for admin role', function () {
    $admin = User::factory()->admin()->create();

    expect($admin->isAdmin())->toBeTrue();
})->group('db');

it('User isAdmin returns false for regular user role', function () {
    $user = User::factory()->create();

    expect($user->isAdmin())->toBeFalse();
})->group('db');

// ── Navbar auth state ─────────────────────────────────────────────────────

it('authenticated admin sees panel entry link in navbar', function () {
    $admin = User::factory()->admin()->create();

    $this->actingAs($admin)
         ->get('/')
         ->assertSee('Admin Panel');
})->group('db');

it('authenticated regular user does not see panel entry link', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
         ->get('/')
         ->assertDontSee('Admin Panel');
})->group('db');

it('authenticated user sees their name in navbar', function () {
    $user = User::factory()->create(['name' => 'Dariush Mehrjui']);

    $this->actingAs($user)
         ->get('/')
         ->assertSee('Dariush Mehrjui');
})->group('db');

it('authenticated user does not see guest Sign in link', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
         ->get('/')
         ->assertDontSee('href="' . route('login') . '"', escape: false);
})->group('db');

it('authenticated user sees logout form in navbar', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
         ->get('/')
         ->assertSee('action="' . route('logout') . '"', escape: false);
})->group('db');

// ── Logout ────────────────────────────────────────────────────────────────

it('logout POST redirects to landing page', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
         ->post('/logout')
         ->assertRedirect('/');
})->group('db');

it('user is unauthenticated after logout', function () {
    $user = User::factory()->create();

    $this->actingAs($user)->post('/logout');

    $this->assertGuest();
})->group('db');

// ── Admin panel access control ────────────────────────────────────────────

it('admin can access admin panel', function () {
    $admin = User::factory()->admin()->create();

    $this->actingAs($admin)
         ->get('/admin')
         ->assertStatus(200);
})->group('db');

it('regular user is blocked from admin panel with 403', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
         ->get('/admin')
         ->assertStatus(403);
})->group('db');

// ── Login ─────────────────────────────────────────────────────────────────

it('successful login via Fortify POST authenticates the user', function () {
    $admin = User::factory()->admin()->create([
        'password' => bcrypt('secret-pass'),
    ]);

    $this->post('/login', [
        'email'    => $admin->email,
        'password' => 'secret-pass',
    ]);

    $this->assertAuthenticated();
})->group('db');
