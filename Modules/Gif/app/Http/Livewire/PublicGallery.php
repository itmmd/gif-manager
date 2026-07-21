<?php

namespace Modules\Gif\Http\Livewire;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;
use Modules\Gif\Models\Gif;

#[Layout('landing::layouts.landing')]
#[Title('GIF Gallery')]
class PublicGallery extends Component
{
    use WithPagination;

    /** جستجوی live — مقدار در URL نگه داشته می‌شه */
    #[Url(as: 'q', history: true)]
    public string $search = '';

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $gifs = Gif::query()
            ->when($this->search, fn ($q) =>
                $q->where('title', 'like', '%' . $this->search . '%')
            )
            ->latest()
            ->paginate(24);

        return view('gif::livewire.public.gallery', compact('gifs'));
    }
}
