<?php

namespace Modules\Core\Services;

use Illuminate\Support\Collection;
use Modules\Core\Contracts\GifShowcaseInterface;

/**
 * Default implementation of GifShowcaseInterface.
 *
 * Uses the Gif Eloquent model via its fully-qualified class name so that
 * Core never imports the Gif module's namespace directly — the class is
 * resolved at runtime, keeping the compile-time dependency graph clean.
 *
 * If the gifs table does not exist yet (fresh install, no migrations run)
 * or the Gif module is disabled, the service returns an empty collection
 * instead of throwing — Landing page gracefully shows no preview items.
 */
class GifShowcaseService implements GifShowcaseInterface
{
    private const GIF_MODEL = \Modules\Gif\Models\Gif::class;

    public function latestGifs(int $limit = 8): Collection
    {
        try {
            /** @var \Illuminate\Database\Eloquent\Model $model */
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
            // Table missing, module disabled, or any other boot-time issue.
            return collect();
        }
    }
}
