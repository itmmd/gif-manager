<?php

namespace Modules\Ai\Services;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SemanticSearchService
{
    public function __construct(
        private readonly EmbeddingService $embeddings,
    ) {}

    public function search(string $query, int $limit = 24, ?float $threshold = null): Collection
    {
        if (blank($query)) {
            return collect();
        }

        $threshold ??= (float) config('ai.search_threshold', 0.30);

        try {
            $queryEmbedding = $this->embeddings->forQuery($query);

            if (empty($queryEmbedding)) {
                return collect();
            }

            $rows = DB::table('gif_ai_metadata')
                ->join('gifs', 'gifs.id', '=', 'gif_ai_metadata.gif_id')
                ->where('gifs.status', 'approved')
                ->whereNotNull('gif_ai_metadata.embedding')
                ->select('gifs.id', 'gif_ai_metadata.embedding')
                ->get();

            if ($rows->isEmpty()) {
                return collect();
            }

            return $rows
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
                ->pluck('id')
                ->values();

        } catch (\Throwable $e) {
            Log::warning('[SemanticSearchService] Search failed', [
                'query' => $query,
                'error' => $e->getMessage(),
            ]);

            return collect();
        }
    }
}
