<?php

namespace Modules\Gif\Providers;

use Nwidart\Modules\Support\ModuleServiceProvider;

class GifServiceProvider extends ModuleServiceProvider
{
    protected string $name = 'Gif';

    protected string $nameLower = 'gif';

    protected array $providers = [
        EventServiceProvider::class,
        RouteServiceProvider::class,
    ];
}
