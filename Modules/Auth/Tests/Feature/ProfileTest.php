<?php

use Illuminate\Routing\Router;

beforeEach(function () {
    $this->withoutVite();
});

it('profile route exists with correct name', function () {
    $route = app(Router::class)->getRoutes()->getByName('profile');

    expect($route)->not->toBeNull();
});

it('profile route resolves to /profile', function () {
    expect(route('profile'))->toBe(url('/profile'));
});

it('profile route requires auth middleware', function () {
    $middleware = app(Router::class)
        ->getRoutes()
        ->getByName('profile')
        ->gatherMiddleware();

    expect($middleware)->toContain('auth');
});

it('guest is redirected to login when accessing profile', function () {
    $this->get('/profile')->assertRedirect('/login');
});
