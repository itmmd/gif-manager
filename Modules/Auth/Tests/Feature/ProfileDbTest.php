<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * Profile Page Tests (DB required — pdo_sqlite)
 * Run with: vendor/bin/pest --group=db
 */

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->withoutVite();
});

it('authenticated user can access profile page', function () {
    $user = User::factory()->create();

    $this->actingAs($user)->get('/profile')->assertStatus(200);
})->group('db');

it('profile page shows user name', function () {
    $user = User::factory()->create(['name' => 'Hassan Rouhani']);

    $this->actingAs($user)->get('/profile')->assertSee('Hassan Rouhani');
})->group('db');

it('profile page shows user email', function () {
    $user = User::factory()->create(['email' => 'test@example.com']);

    $this->actingAs($user)->get('/profile')->assertSee('test@example.com');
})->group('db');

it('profile page contains password change form', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
         ->get('/profile')
         ->assertSee('current_password', escape: false)
         ->assertSee('Update password');
})->group('db');

it('admin user can also access profile page', function () {
    $admin = User::factory()->admin()->create();

    $this->actingAs($admin)->get('/profile')->assertStatus(200);
})->group('db');

it('landing navbar profile link points to /profile for authenticated user', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
         ->get('/')
         ->assertSee('href="' . route('profile') . '"', escape: false);
})->group('db');
