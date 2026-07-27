<?php

use Illuminate\Routing\Router;

beforeEach(function () {
    $this->withoutVite();
});

it('gifs.index route exists', function () {
    $route = app(Router::class)->getRoutes()->getByName('gifs.index');

    expect($route)->not->toBeNull();
});

it('gallery route resolves to /gifs', function () {
    expect(route('gifs.index'))->toBe(url('/gifs'));
});

it('gifs.show route exists', function () {
    $route = app(Router::class)->getRoutes()->getByName('gifs.show');

    expect($route)->not->toBeNull();
});

it('landing page contains a link to the gallery', function () {
    $this->get('/')->assertSee(route('gifs.index'), escape: false);
});

it('landing page contains View all GIFs button', function () {
    $this->get('/')->assertSee('View all GIFs');
});

it('GifShowcaseInterface is bound in the container', function () {
    $instance = app(\Modules\Core\Contracts\GifShowcaseInterface::class);

    expect($instance)->toBeInstanceOf(\Modules\Core\Contracts\GifShowcaseInterface::class);
});

it('GifShowcaseService returns a collection when no GIFs exist', function () {
    $showcase = app(\Modules\Core\Contracts\GifShowcaseInterface::class);

    expect($showcase->latestGifs(8))->toBeInstanceOf(\Illuminate\Support\Collection::class);
});
