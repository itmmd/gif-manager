<?php

namespace Modules\Core\Providers;

use Modules\Core\Contracts\GifShowcaseInterface;
use Modules\Core\Contracts\MediaStorageInterface;
use Modules\Core\Services\GifShowcaseService;
use Modules\Core\Services\Storage\LocalMediaStorage;
use Nwidart\Modules\Support\ModuleServiceProvider;

class CoreServiceProvider extends ModuleServiceProvider
{
    protected string $name = 'Core';

    protected string $nameLower = 'core';

    protected array $providers = [
        EventServiceProvider::class,
        RouteServiceProvider::class,
    ];

    public function register(): void
    {
        parent::register();

        $this->app->bind(
            MediaStorageInterface::class,
            fn () => new LocalMediaStorage(
                diskName: config('filesystems.default', 'local')
            )
        );

        $this->app->bind(GifShowcaseInterface::class, GifShowcaseService::class);
    }

    public function boot(): void
    {
        parent::boot();
    }
}
