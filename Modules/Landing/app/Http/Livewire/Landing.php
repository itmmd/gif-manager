<?php

namespace Modules\Landing\Http\Livewire;

use Illuminate\Support\Collection;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Modules\Core\Contracts\GifShowcaseInterface;

#[Layout('landing::layouts.landing')]
class Landing extends Component
{
    /** @var Collection<int, object> */
    public Collection $showcaseGifs;

    public function boot(GifShowcaseInterface $showcase): void
    {
        // Empty collection when there's nothing yet — showcase falls back to placeholders.
        $this->showcaseGifs = $showcase->latestGifs(config('landing.showcase.count', 8));
    }

    public function render()
    {
        return view('landing::livewire.landing', [
            'showcaseGifs' => $this->showcaseGifs,
        ]);
    }
}
