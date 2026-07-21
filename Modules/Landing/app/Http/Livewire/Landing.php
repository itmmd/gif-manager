<?php

namespace Modules\Landing\Http\Livewire;

use Illuminate\Support\Collection;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Modules\Core\Contracts\GifShowcaseInterface;

#[Layout('landing::layouts.landing')]
#[Title('GIF Manager — Organize, Share & Discover GIFs')]
class Landing extends Component
{
    /** @var Collection<int, object> */
    public Collection $showcaseGifs;

    public function boot(GifShowcaseInterface $showcase): void
    {
        // Resolved via Core's container binding → GifShowcaseService.
        // Returns an empty collection when no GIFs exist yet, so the
        // Showcase section gracefully falls back to placeholder cards.
        $this->showcaseGifs = $showcase->latestGifs(8);
    }

    public function render()
    {
        return view('landing::livewire.landing', [
            'showcaseGifs' => $this->showcaseGifs,
        ]);
    }
}
