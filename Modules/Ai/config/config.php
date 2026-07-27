<?php

return [
    'name'                    => 'Ai',
    'duplicate_threshold'     => (float) env('AI_DUPLICATE_THRESHOLD', 0.92),
    'search_threshold'        => (float) env('AI_SEARCH_THRESHOLD', 0.30),
    'auto_approve_on_failure' => (bool) env('AI_AUTO_APPROVE_ON_FAILURE', true),
];
