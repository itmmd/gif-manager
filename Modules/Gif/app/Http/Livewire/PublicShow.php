<?php

namespace Modules\Gif\Http\Livewire;

use Livewire\Attributes\Layout;
use Livewire\Component;
use Modules\Gif\Models\Gif;

#[Layout('landing::layouts.landing')]
class PublicShow extends Component
{
    public Gif $gif;

    public function title(): string
    {
        return ($this->gif->title ?? 'View GIF') . ' — GIF Gallery';
    }

    public function render()
    {
        $related = Gif::where('id', '!=', $this->gif->id)
                      ->latest()
                      ->limit(8)
                      ->get();

        return view('gif::livewire.public.show', compact('related'));
    }
}
