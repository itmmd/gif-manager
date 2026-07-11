<?php

use Modules\Core\Contracts\MediaStorageInterface;
use Modules\Core\Services\Storage\LocalMediaStorage;

it('resolves MediaStorageInterface from the container', function () {
    $storage = app(MediaStorageInterface::class);

    expect($storage)->toBeInstanceOf(MediaStorageInterface::class);
});

it('resolves to LocalMediaStorage by default', function () {
    $storage = app(MediaStorageInterface::class);

    expect($storage)->toBeInstanceOf(LocalMediaStorage::class);
});

it('reports the correct disk name', function () {
    $storage = app(MediaStorageInterface::class);

    expect($storage->disk())->toBe(config('filesystems.default', 'local'));
});

it('can check existence of a non-existent file without error', function () {
    $storage = app(MediaStorageInterface::class);

    expect($storage->exists('non-existent-file.gif'))->toBeFalse();
});
