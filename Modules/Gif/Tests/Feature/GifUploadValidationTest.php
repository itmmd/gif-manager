<?php

use Illuminate\Routing\Router;

/**
 * GIF Upload Validation — No-DB tests (always run)
 * -----------------------------------------------------------------------
 * Route config and middleware checks that don't touch the database.
 * DB-dependent upload/validation tests live in GifUploadValidationDbTest.php.
 */

beforeEach(fn () => $this->withoutVite());

// ── Route config ──────────────────────────────────────────────────────────

it('upload route exists with correct name', function () {
    $route = app(Router::class)->getRoutes()->getByName('admin.gifs.upload');

    expect($route)->not->toBeNull();
});

it('upload route requires auth middleware', function () {
    $middleware = app(Router::class)
        ->getRoutes()
        ->getByName('admin.gifs.upload')
        ->gatherMiddleware();

    expect($middleware)->toContain('auth');
});

it('upload route requires admin middleware', function () {
    $middleware = app(Router::class)
        ->getRoutes()
        ->getByName('admin.gifs.upload')
        ->gatherMiddleware();

    expect($middleware)->toContain('admin');
});

it('guest is redirected to login when accessing upload page', function () {
    $this->get('/admin/gifs/upload')->assertRedirect('/login');
});

// ── Gif model validation rules ────────────────────────────────────────────

it('Gif model strips HTML tags from title on set', function () {
    $gif = new \Modules\Gif\Models\Gif();
    $gif->title = '<script>alert("xss")</script>Hello';

    expect($gif->title)->toBe('Hello');
});

it('Gif model strips tags but keeps unicode characters', function () {
    $gif = new \Modules\Gif\Models\Gif();
    $gif->title = 'سلام دنیا 👋';

    expect($gif->title)->toBe('سلام دنیا 👋');
});

it('Gif model strips tags but keeps arabic characters', function () {
    $gif = new \Modules\Gif\Models\Gif();
    $gif->title = 'مرحبا بالعالم';

    expect($gif->title)->toBe('مرحبا بالعالم');
});

it('Gif model strips tags but keeps emoji', function () {
    $gif = new \Modules\Gif\Models\Gif();
    $gif->title = 'Party time 🎉🎊';

    expect($gif->title)->toBe('Party time 🎉🎊');
});

it('Gif model trims leading and trailing whitespace from title', function () {
    $gif = new \Modules\Gif\Models\Gif();
    $gif->title = '  some title  ';

    expect($gif->title)->toBe('some title');
});

it('Gif model strips mixed html and unicode title', function () {
    $gif = new \Modules\Gif\Models\Gif();
    $gif->title = '<b>عنوان</b> گیف <em>جدید</em>';

    expect($gif->title)->toBe('عنوان گیف جدید');
});
