<?php

return [
    'name' => 'Ai',

    /*
    |--------------------------------------------------------------------------
    | Duplicate Detection
    |--------------------------------------------------------------------------
    |
    | Cosine similarity threshold above which a newly uploaded file is
    | considered a duplicate of an existing GIF. Range: 0.0–1.0.
    | 0.92 = very similar (near-identical). Lower = more sensitive.
    |
    */
    'duplicate_threshold' => (float) env('AI_DUPLICATE_THRESHOLD', 0.92),

    /*
    |--------------------------------------------------------------------------
    | Semantic Search
    |--------------------------------------------------------------------------
    |
    | Minimum cosine similarity for a GIF to be included in semantic search
    | results. Range: 0.0–1.0. Lower = broader / more results.
    |
    */
    'search_threshold' => (float) env('AI_SEARCH_THRESHOLD', 0.30),

    /*
    |--------------------------------------------------------------------------
    | Graceful Degradation
    |--------------------------------------------------------------------------
    |
    | When the AI service is unavailable (timeout, rate-limit, wrong key),
    | the following flags control fallback behaviour:
    |
    |   auto_approve_on_failure — if true, GIFs are auto-approved when the
    |     moderation check fails, so the upload flow is never blocked.
    |     Set to false to keep GIFs in 'pending_review' on AI failure.
    |
    */
    'auto_approve_on_failure' => (bool) env('AI_AUTO_APPROVE_ON_FAILURE', true),
];
