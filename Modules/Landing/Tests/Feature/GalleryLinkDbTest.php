<?php

use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * Gallery Page Tests (DB required — pdo_sqlite)
 * Run with: vendor/bin/pest --group=db
 */

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->withoutVite();
});

it('public gallery page is accessible to guests', function () {
    $this->get('/gifs')->assertStatus(200);
})->group('db');

it('public gallery page shows search input', function () {
    $this->get('/gifs')->assertSee('Search GIFs', escape: false);
})->group('db');

it('public gallery shows empty state when no GIFs exist', function () {
    $this->get('/gifs')->assertSee('No GIFs found');
})->group('db');
