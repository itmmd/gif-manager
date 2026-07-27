<?php

use Illuminate\Routing\Router;

beforeEach(fn () => $this->withoutVite());

it('gifs.show route exists', function () {
    $route = app(Router::class)->getRoutes()->getByName('gifs.show');

    expect($route)->not->toBeNull();
});

it('gifs.show route uses slug binding', function () {
    $model = new \Modules\Gif\Models\Gif();

    expect($model->getRouteKeyName())->toBe('slug');
});

it('visiting a non-existent gif slug returns 404', function () {
    $this->get('/gifs/this-slug-does-not-exist-xyz')
         ->assertStatus(404);
})->group('db');
