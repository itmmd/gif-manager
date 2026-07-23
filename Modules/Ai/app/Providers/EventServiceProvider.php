<?php

namespace Modules\Ai\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Modules\Ai\Listeners\HandleGifUploaded;
use Modules\Gif\Events\GifUploaded;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the Ai module.
     *
     * GifUploaded is fired by the Gif module — the Ai module listens here.
     * Removing this module removes the listener; the Gif module is unaffected.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        GifUploaded::class => [
            HandleGifUploaded::class,
        ],
    ];

    /**
     * Disable event auto-discovery for this module — we use explicit mapping above.
     */
    protected static $shouldDiscoverEvents = false;

    protected function configureEmailVerification(): void {}
}
