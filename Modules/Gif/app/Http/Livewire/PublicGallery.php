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

    /** Live search — value kept in URL as ?q= */
    #[Url(as: 'q', history: true)]
    public string $search = '';

    /** Whether the Ai module's GIF Genie route is available. */
    public bool $genieAvailable = false;

    public function mount(): void
    {
        $this->genieAvailable = \Illuminate\Support\Facades\Route::has('gifs.genie');
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        // Only show approved GIFs in the public gallery.
        // pending_review and flagged GIFs are hidden until admin action.
        $gifs = Gif::approved()
            ->when($this->search, fn ($q) =>
                $q->where('title', 'like', '%' . $this->search . '%')
            )
            ->latest()
            ->paginate(24);

        return view('gif::livewire.public.gallery', compact('gifs'));
    }
}
