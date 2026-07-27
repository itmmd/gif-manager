<?php

use Modules\Ai\Services\EmbeddingService;

it('cosine similarity is 1.0 for identical vectors', function () {
    $service = new EmbeddingService();
    $vector  = [0.5, 0.8, 0.3, 0.9];

    expect($service->cosineSimilarity($vector, $vector))->toEqualWithDelta(1.0, 0.0001);
});

it('cosine similarity is 0.0 for orthogonal vectors', function () {
    $service = new EmbeddingService();

    expect($service->cosineSimilarity([1.0, 0.0], [0.0, 1.0]))->toEqualWithDelta(0.0, 0.0001);
});

it('cosine similarity is 0.0 when either vector is empty', function () {
    $service = new EmbeddingService();

    expect($service->cosineSimilarity([], [0.1, 0.2]))->toBe(0.0);
    expect($service->cosineSimilarity([0.1, 0.2], []))->toBe(0.0);
    expect($service->cosineSimilarity([], []))->toBe(0.0);
});

it('cosine similarity is above 0.99 for nearly-identical vectors', function () {
    $service = new EmbeddingService();

    expect($service->cosineSimilarity([0.9, 0.1, 0.5, 0.8, 0.3], [0.9, 0.11, 0.5, 0.8, 0.3]))->toBeGreaterThan(0.99);
});

it('cosine similarity is well below 0.92 for dissimilar vectors', function () {
    $service = new EmbeddingService();

    expect($service->cosineSimilarity([0.9, 0.1, 0.5, 0.8, 0.3], [0.1, 0.9, 0.2, 0.3, 0.7]))->toBeLessThan(0.92);
});

it('ModerationResult::safe() is not flagged', function () {
    $result = \Modules\Core\Contracts\ModerationResult::safe();

    expect($result->isFlagged)->toBeFalse()
        ->and($result->reason)->toBeNull();
});

it('ModerationResult::flagged() carries the reason', function () {
    $result = \Modules\Core\Contracts\ModerationResult::flagged('Contains graphic violence.');

    expect($result->isFlagged)->toBeTrue()
        ->and($result->reason)->toBe('Contains graphic violence.');
});

it('MediaAnalysisResult::fromArray() maps all fields correctly', function () {
    $result = \Modules\Core\Contracts\MediaAnalysisResult::fromArray([
        'suggested_title' => 'Funny Cat',
        'suggested_tags'  => ['cat', 'funny', 'reaction'],
        'description'     => 'A cat reacting to something.',
    ]);

    expect($result->suggestedTitle)->toBe('Funny Cat')
        ->and($result->suggestedTags)->toBe(['cat', 'funny', 'reaction'])
        ->and($result->description)->toBe('A cat reacting to something.')
        ->and($result->hasData())->toBeTrue();
});

it('MediaAnalysisResult::hasData() is false for empty result', function () {
    expect((new \Modules\Core\Contracts\MediaAnalysisResult())->hasData())->toBeFalse();
});
