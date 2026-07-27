<?php

namespace Modules\Core\Http\Livewire;

use Illuminate\View\View;
use Livewire\Component;

class Ping extends Component
{
    public string $message = 'Livewire is working.';

    public int $clickCount = 0;

    public function increment(): void
    {
        $this->clickCount++;
    }

    public function render(): View
    {
        return view('core::livewire.ping');
    }
}
