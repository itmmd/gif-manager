<?php

namespace Modules\Gif\Http\Livewire;

use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;
use Modules\Gif\Models\Gif;

#[Layout('admin::layouts.admin')]
#[Title('GIF Library')]
class GifIndex extends Component
{
    use WithPagination;

    /** ID of the GIF currently pending delete confirmation */
    public ?int $confirmDeleteId = null;

    public function confirmDelete(int $id): void
    {
        $this->confirmDeleteId = $id;
    }

    public function cancelDelete(): void
    {
        $this->confirmDeleteId = null;
    }

    public function delete(int $id): void
    {
        $gif = Gif::find($id);

        if (! $gif) {
            $this->confirmDeleteId = null;
            return;
        }

        // Remove file from disk
        Storage::disk('public')->delete($gif->file_path);

        // Remove DB record
        $gif->delete();

        $this->confirmDeleteId = null;

        // If the current page is now empty, go back one page
        if ($this->page > 1 && Gif::paginate(12)->isEmpty()) {
            $this->previousPage();
        }
    }

    public function render()
    {
        return view('gif::livewire.gif-index', [
            'gifs' => Gif::with('uploader')
                         ->latest()
                         ->paginate(12),
        ]);
    }
}
