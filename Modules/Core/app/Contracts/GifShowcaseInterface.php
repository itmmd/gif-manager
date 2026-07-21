<?php

namespace Modules\Core\Contracts;

use Illuminate\Support\Collection;

/**
 * Contract for fetching a small preview set of GIFs for the landing page.
 *
 * Landing module depends on this interface (in Core), not on the Gif model
 * directly. This keeps the module dependency graph clean:
 *   Landing → Core (interface) ← Gif (implementation)
 *
 * Each item in the returned Collection is a plain object / array with:
 *   - url   (string) — publicly accessible URL to the GIF/MP4
 *   - title (string) — display title
 *   - mime_type (string) — 'image/gif' | 'video/mp4'
 *   - show_url (string) — URL of the public detail page for this GIF
 */
interface GifShowcaseInterface
{
    /**
     * Return the $limit most-recently-uploaded GIFs.
     *
     * @return Collection<int, object>
     */
    public function latestGifs(int $limit = 8): Collection;
}
