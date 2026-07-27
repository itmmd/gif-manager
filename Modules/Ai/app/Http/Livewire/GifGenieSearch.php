<?php

namespace Modules\Ai\Http\Livewire;

use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;
use Modules\Ai\Services\SemanticSearchService;
use Modules\Gif\Models\Gif;

#[Layout('landing::layouts.landing')]
#[Title('GIF Genie — AI Search')]
class GifGenieSearch extends Component
{
    use WithPagination;

    #[Url(as: 'genie', history: true)]
    public string $query = '';

    public bool $usedSemanticSearch = false;

    public bool $semanticAvailable = false;

    public function mount(): void
    {
        $this->semanticAvailable = app()->bound(SemanticSearchService::class);
    }

    public function updatedQuery(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        [$gifs, $usedSemantic] = $this->fetchGifs();
        $this->usedSemanticSearch = $usedSemantic;

        return view('ai::livewire.gif-genie-search', compact('gifs'));
    }

    private function fetchGifs(): array
    {
        $query = trim($this->query);

        if ($query === '') {
            return [Gif::approved()->latest()->paginate(24), false];
        }

        if ($this->semanticAvailable) {
            try {
                $ids = app(SemanticSearchService::class)->search(
                    mb_substr(strip_tags($query), 0, 200),
                    limit: 48
                );

                if ($ids->isNotEmpty()) {
                    $gifs = Gif::approved()
                        ->whereIn('id', $ids->all())
                        ->orderByRaw(
                            'CASE id ' .
                            $ids->values()->map(fn ($id, $i) => "WHEN {$id} THEN {$i}")->implode(' ') .
                            ' END'
                        )
                        ->paginate(24);

                    return [$gifs, true];
                }
            } catch (\Throwable) {
                // fall through to LIKE
            }
        }

        return [
            Gif::approved()->where('title', 'like', '%' . $query . '%')->latest()->paginate(24),
            false,
        ];
    }
}
