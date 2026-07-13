<?php

namespace Modules\Landing\Http\Livewire;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('landing::layouts.landing')]
#[Title('GIF Manager — Organize, Share & Discover GIFs')]
class Landing extends Component
{
    public function render()
    {
        return view('landing::livewire.landing');
    }
}
