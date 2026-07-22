<?php

use Illuminate\Routing\Router;

/**
 * PublicShow — No-DB tests (always run)
 * -----------------------------------------------------------------------
 * Verifies that the show route is correctly configured.
 * DB-dependent tests (actually opening a GIF detail page) live in
 * PublicShowDbTest.php.
 */

beforeEach(fn () => $this->withoutVite());

// ── Route config ──────────────────────────────────────────────────────────

it('gifs.show route exists', function () {
    $route = app(Router::class)->getRoutes()->getByName('gifs.show');

    expect($route)->not->toBeNull();
});

it('gifs.show route uses slug binding', function () {
    $route = app(Router::class)->getRoutes()->getByName('gifs.show');

    // The route URI must contain {gif:slug} or at minimum bind on slug.
    // nWidart compiles it as /gifs/{gif}, but getRouteKeyName on Gif = 'slug'
    // confirms slug binding is in effect.
    $model = new \Modules\Gif\Models\Gif();
    expect($model->getRouteKeyName())->toBe('slug');
});

it('visiting a non-existent gif slug returns 404', function () {
    $this->get('/gifs/this-slug-does-not-exist-xyz')
         ->assertStatus(404);
})->group('db');
