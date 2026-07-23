<?php

namespace Modules\Ai\Services;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Semantic (vector) search over stored GIF embeddings.
 *
 * Algorithm:
 *   1. Generate an embedding for the user's text query.
 *   2. Load all stored embeddings from gif_ai_metadata (joined with gifs).
 *   3. Compute cosine similarity between the query vector and each stored vector.
 *   4. Return the top-N GIF IDs sorted by similarity (descending).
 *
 * Fallback: if the query embedding is empty (AI unavailable) or no embeddings
 * are stored, the method returns an empty collection — callers then fall back
 * to LIKE search.
 *
 * Caching: query embeddings are cached by EmbeddingService (1 hour).
 * For very large datasets, the in-process loop should be replaced with
 * pgvector or a dedicated vector store.
 */
class SemanticSearchService
{
    public function __construct(
        private readonly EmbeddingService $embeddings,
    ) {}

    /**
     * Return GIF IDs ranked by semantic similarity to $query.
     *
     * @param  string  $query    User search string (sanitised before calling)
     * @param  int     $limit    Maximum number of results
     * @param  float   $threshold Minimum similarity score (0–1) to include a result
     * @return Collection<int, int>  Collection of gif IDs (most similar first)
     */
    public function search(string $query, int $limit = 24, ?float $threshold = null): Collection
    {
        if (blank($query)) {
            return collect();
        }

        $threshold ??= (float) config('ai.search_threshold', 0.30);

        try {
            $queryEmbedding = $this->embeddings->forQuery($query);

            if (empty($queryEmbedding)) {
                return collect(); // AI unavailable — caller falls back to LIKE
            }

            // Load approved GIFs that have embeddings
            $rows = DB::table('gif_ai_metadata')
                ->join('gifs', 'gifs.id', '=', 'gif_ai_metadata.gif_id')
                ->where('gifs.status', 'approved')
                ->whereNotNull('gif_ai_metadata.embedding')
                ->select('gifs.id', 'gif_ai_metadata.embedding')
                ->get();

            if ($rows->isEmpty()) {
                return collect();
            }

            $scored = $rows
                ->map(function ($row) use ($queryEmbedding) {
                    $stored = json_decode($row->embedding, true);
                    if (! is_array($stored) || empty($stored)) {
                        return null;
                    }

                    return [
                        'id'         => $row->id,
                        'similarity' => $this->embeddings->cosineSimilarity($queryEmbedding, $stored),
                    ];
                })
                ->filter()
                ->filter(fn ($item) => $item['similarity'] >= $threshold)
                ->sortByDesc('similarity')
                ->take($limit)
                ->pluck('id');

            return $scored->values();

        } catch (\Throwable $e) {
            Log::warning('[SemanticSearchService] Search failed', [
                'query' => $query,
                'error' => $e->getMessage(),
            ]);

            return collect(); // graceful fallback
        }
    }
}
