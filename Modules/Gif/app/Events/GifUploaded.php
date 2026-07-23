<?php

namespace Modules\Gif\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Gif\Models\Gif;

/**
 * Fired after a GIF/MP4 has been persisted to disk AND database.
 *
 * The Ai module listens to this event and dispatches its async analysis
 * jobs. The Gif module never imports anything from Modules\Ai — if the
 * Ai module is disabled, the event simply has no listeners and is a no-op.
 *
 * Payload:
 *   $gif->id            — DB primary key
 *   $gif->file_path     — storage path on the public disk (e.g. "gifs/uuid.gif")
 *   $gif->mime_type     — "image/gif" | "video/mp4"
 *   $gif->title         — sanitised title
 */
class GifUploaded
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    public function __construct(
        public readonly Gif $gif,
    ) {}
}
