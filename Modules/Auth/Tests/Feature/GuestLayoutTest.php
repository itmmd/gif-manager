<?php

/**
 * Verifies that Auth pages (login, register, forgot-password) use the
 * Tailwind-based guest layout and do NOT reference admin-assets in any way.
 *
 * This protects against regressions where Auth views accidentally pull in
 * Gentelella CSS/JS (admin-assets/) intended only for the Admin module.
 */

beforeEach(function () {
    // Disable Vite so tests run without a built manifest.
    // withoutVite() stubs @vite() directives to empty strings, which lets
    // us assert on the rendered HTML without needing `npm run build`.
    $this->withoutVite();
});

it('login page does not contain admin-assets references', function () {
    $response = $this->get('/login');

    $response->assertStatus(200);
    $response->assertDontSee('admin-assets', escape: false);
});

it('register page does not contain admin-assets references', function () {
    $response = $this->get('/register');

    $response->assertStatus(200);
    $response->assertDontSee('admin-assets', escape: false);
});

it('forgot-password page does not contain admin-assets references', function () {
    $response = $this->get('/forgot-password');

    $response->assertStatus(200);
    $response->assertDontSee('admin-assets', escape: false);
});

it('login page does not load gentelella css', function () {
    $response = $this->get('/login');

    $response->assertStatus(200);
    $response->assertDontSee('main-v4', escape: false);
    $response->assertDontSee('admin-assets/css', escape: false);
});

it('login page does not load gentelella js modules', function () {
    $response = $this->get('/login');

    $response->assertStatus(200);
    $response->assertDontSee('admin-assets/js', escape: false);
    $response->assertDontSee('rolldown-runtime', escape: false);
});
