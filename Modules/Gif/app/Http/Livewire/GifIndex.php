<?php

namespace Modules\Gif\Http\Livewire;

use Illuminate\Support\Facades\DB;
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

    public ?int $confirmDeleteId = null;

    public ?int $aiSuggestionId = null;

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

        Storage::disk('public')->delete($gif->file_path);
        $gif->delete();

        $this->confirmDeleteId = null;

        if ($this->page > 1 && Gif::paginate(12)->isEmpty()) {
            $this->previousPage();
        }
    }

    public function applyAiTitle(int $id): void
    {
        $gif = Gif::with('aiMetadata')->find($id);

        if (! $gif || ! $gif->aiMetadata?->suggested_title) {
            return;
        }

        DB::table('gifs')->where('id', $id)->update([
            'title' => $gif->aiMetadata->suggested_title,
        ]);

        $this->aiSuggestionId = null;
    }

    public function showAiSuggestion(int $id): void
    {
        $this->aiSuggestionId = ($this->aiSuggestionId === $id) ? null : $id;
    }

    public function render()
    {
        return view('gif::livewire.gif-index', [
            'gifs' => Gif::with(['uploader', 'aiMetadata'])->latest()->paginate(12),
        ]);
    }
}
