<?php

use Illuminate\Routing\Router;

/**
 * Landing Page — Auth Flow Tests (No DB required)
 * -----------------------------------------------------------------------
 * These tests verify the auth flow for the Landing page without needing
 * a database. They check routing, middleware config, and guest HTML.
 *
 * DB-dependent tests (actingAs with factory users) live in
 * LandingAuthFlowDbTest.php and require pdo_sqlite to run.
 */

beforeEach(function () {
    $this->withoutVite();
});

// ── Guest experience ──────────────────────────────────────────────────────

it('landing page is accessible to guests', function () {
    $this->get('/')->assertStatus(200);
});

it('landing page shows Sign in link for guests', function () {
    $this->get('/')->assertSee('Sign in');
});

it('landing page shows Get Started link for guests', function () {
    $this->get('/')->assertSee('Get Started');
});

it('landing page does not show admin panel link for guests', function () {
    $this->get('/')->assertDontSee('Admin Panel');
});

it('landing page has csrf meta token', function () {
    $this->get('/')->assertSee('csrf-token', escape: false);
});

// ── Route configuration ───────────────────────────────────────────────────

it('login route exists with correct name', function () {
    $route = app(Router::class)->getRoutes()->getByName('login');

    expect($route)->not->toBeNull();
});

it('logout route is registered as POST', function () {
    $route = app(Router::class)->getRoutes()->getByName('logout');

    expect($route)->not->toBeNull()
        ->and($route->methods())->toContain('POST');
});

it('logout route requires auth middleware', function () {
    $middleware = app(Router::class)
        ->getRoutes()
        ->getByName('logout')
        ->gatherMiddleware();

    expect($middleware)->toContain('auth');
});

it('admin dashboard route requires auth middleware', function () {
    $middleware = app(Router::class)
        ->getRoutes()
        ->getByName('admin.dashboard')
        ->gatherMiddleware();

    expect($middleware)->toContain('auth');
});

it('admin dashboard route requires admin role middleware', function () {
    $middleware = app(Router::class)
        ->getRoutes()
        ->getByName('admin.dashboard')
        ->gatherMiddleware();

    expect($middleware)->toContain('admin');
});

it('guest is redirected to login when accessing admin panel', function () {
    $this->get('/admin')->assertRedirect('/login');
});

it('logout url resolves to /logout', function () {
    expect(route('logout'))->toBe(url('/logout'));
});
