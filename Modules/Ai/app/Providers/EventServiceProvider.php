<?php

namespace Modules\Ai\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Modules\Ai\Listeners\HandleGifUploaded;
use Modules\Gif\Events\GifUploaded;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        GifUploaded::class => [
            HandleGifUploaded::class,
        ],
    ];

    protected static $shouldDiscoverEvents = false;

    protected function configureEmailVerification(): void {}
}
