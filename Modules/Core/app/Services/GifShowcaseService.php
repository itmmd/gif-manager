<?php

namespace Modules\Core\Services;

use Illuminate\Support\Collection;
use Modules\Core\Contracts\GifShowcaseInterface;

class GifShowcaseService implements GifShowcaseInterface
{
    private const GIF_MODEL = \Modules\Gif\Models\Gif::class;

    public function latestGifs(int $limit = 8): Collection
    {
        try {
            $model = new (self::GIF_MODEL)();

            return $model->newQuery()
                ->latest()
                ->limit($limit)
                ->get()
                ->map(fn ($gif) => (object) [
                    'url'       => $gif->url,
                    'title'     => $gif->title,
                    'mime_type' => $gif->mime_type,
                    'show_url'  => route('gifs.show', $gif),
                ]);
        } catch (\Throwable) {
            return collect();
        }
    }
}
