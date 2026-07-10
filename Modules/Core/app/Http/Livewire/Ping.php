<?php

namespace Modules\Core\Http\Livewire;

use Livewire\Component;

class Ping extends Component
{
    public string $message = 'Livewire 4 is working inside the Core module!';

    public int $clickCount = 0;

    public function increment(): void
    {
        $this->clickCount++;
    }

    public function render(): \Illuminate\View\View
    {
        return view('core::livewire.ping');
    }
}
