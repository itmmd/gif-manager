<?php

namespace Modules\Core\Providers;

use Illuminate\Console\Scheduling\Schedule;
use Modules\Core\Contracts\GifShowcaseInterface;
use Modules\Core\Contracts\MediaStorageInterface;
use Modules\Core\Services\GifShowcaseService;
use Modules\Core\Services\Storage\LocalMediaStorage;
use Nwidart\Modules\Support\ModuleServiceProvider;

class CoreServiceProvider extends ModuleServiceProvider
{
    /**
     * The name of the module.
     */
    protected string $name = 'Core';

    /**
     * The lowercase version of the module name.
     */
    protected string $nameLower = 'core';

    /**
     * Command classes to register.
     *
     * @var string[]
     */
    // protected array $commands = [];

    /**
     * Provider classes to register.
     *
     * @var string[]
     */
    protected array $providers = [
        EventServiceProvider::class,
        RouteServiceProvider::class,
    ];

    /**
     * Register module-level service bindings.
     *
     * برای تغییر از local به S3 کافیه binding رو اینجا عوض کنی:
     *   $this->app->bind(MediaStorageInterface::class, S3MediaStorage::class);
     */
    public function register(): void
    {
        parent::register();

        $this->app->bind(
            MediaStorageInterface::class,
            fn () => new LocalMediaStorage(
                diskName: config('filesystems.default', 'local')
            )
        );

        $this->app->bind(
            GifShowcaseInterface::class,
            GifShowcaseService::class
        );
    }

    /**
     * Boot the module services.
     */
    public function boot(): void
    {
        parent::boot();
    }

    /**
     * Define module schedules.
     *
     * @param  $schedule
     */
    // protected function configureSchedules(Schedule $schedule): void
    // {
    //     $schedule->command('inspire')->hourly();
    // }
}
