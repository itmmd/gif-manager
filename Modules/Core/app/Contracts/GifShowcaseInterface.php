<?php

namespace Modules\Core\Contracts;

use Illuminate\Support\Collection;

interface GifShowcaseInterface
{
    public function latestGifs(int $limit = 8): Collection;
}
