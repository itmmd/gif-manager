<?php

use Modules\Core\Contracts\MediaStorageInterface;
use Modules\Core\Services\Storage\LocalMediaStorage;

it('resolves MediaStorageInterface from the container', function () {
    expect(app(MediaStorageInterface::class))->toBeInstanceOf(MediaStorageInterface::class);
});

it('resolves to LocalMediaStorage by default', function () {
    expect(app(MediaStorageInterface::class))->toBeInstanceOf(LocalMediaStorage::class);
});

it('reports the correct disk name', function () {
    expect(app(MediaStorageInterface::class)->disk())->toBe(config('filesystems.default', 'local'));
});

it('can check existence of a non-existent file without error', function () {
    expect(app(MediaStorageInterface::class)->exists('non-existent-file.gif'))->toBeFalse();
});
