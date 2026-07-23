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

/**
 * "GIF Genie" — semantic search chat-box component.
 *
 * Sits on the public gallery page as a complementary search layer:
 *   1. User types a natural-language query (e.g. "scared cat running away").
 *   2. SemanticSearchService generates an embedding and returns ranked GIF IDs.
 *   3. If the Ai module is unavailable or returns no results, falls back to
 *      the standard LIKE query so the page never returns an empty state
 *      simply because the AI is down.
 *
 * The component is standalone — it can be embedded anywhere with
 *   <livewire:ai::gif-genie-search />
 */
#[Layout('landing::layouts.landing')]
#[Title('GIF Genie — AI Search')]
class GifGenieSearch extends Component
{
    use WithPagination;

    /** The natural-language query — synced to ?genie= in the URL. */
    #[Url(as: 'genie', history: true)]
    public string $query = '';

    /** Whether the last search used semantic (true) or LIKE fallback (false). */
    public bool $usedSemanticSearch = false;

    /** Whether semantic search is available (Ai module bound + embeddings exist). */
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

    /**
     * Fetch GIFs: try semantic search first, fall back to LIKE.
     *
     * @return array{0: LengthAwarePaginator, 1: bool}
     */
    private function fetchGifs(): array
    {
        $query = trim($this->query);

        // No query — show all approved GIFs (latest first)
        if ($query === '') {
            return [
                Gif::approved()->latest()->paginate(24),
                false,
            ];
        }

        // --- Attempt semantic search ---
        if ($this->semanticAvailable) {
            try {
                /** @var SemanticSearchService $semantic */
                $semantic = app(SemanticSearchService::class);

                // Sanitise: strip tags + limit length before embedding
                $sanitised = mb_substr(strip_tags($query), 0, 200);
                $ids = $semantic->search($sanitised, limit: 48);

                if ($ids->isNotEmpty()) {
                    // Preserve ranking order from semantic search
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
                // AI service down — fall through to LIKE search
            }
        }

        // --- Fallback: LIKE search ---
        $gifs = Gif::approved()
            ->where('title', 'like', '%' . $query . '%')
            ->latest()
            ->paginate(24);

        return [$gifs, false];
    }
}
