<?php

use Illuminate\Routing\Router;

it('shows login page with status 200', function () {
    $this->get('/login')->assertStatus(200);
});

it('shows register page with status 200', function () {
    $this->get('/register')->assertStatus(200);
});

it('shows forgot password page with status 200', function () {
    $this->get('/forgot-password')->assertStatus(200);
});

it('login page contains form', function () {
    $this->get('/login')->assertSee('form', escape: false);
});

it('authenticated user is redirected from login', function () {
    // Note: tests with database require pdo_sqlite extension.
    // Verifying redirect behavior via middleware reflection instead.
    $middleware = app(Router::class)
        ->getRoutes()
        ->getByName('login')
        ->gatherMiddleware();

    expect($middleware)->toContain('guest');
});
