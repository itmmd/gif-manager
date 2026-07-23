<?php

namespace Modules\Core\Contracts;

/**
 * Value object returned by MediaIntelligenceInterface::analyzeMedia().
 *
 * Kept in Core so that both the Gif module (consumer) and the Ai module
 * (producer) can reference it without a direct cross-module dependency.
 */
final class MediaAnalysisResult
{
    /**
     * @param  string|null  $suggestedTitle  AI-suggested short title
     * @param  string[]     $suggestedTags   3–5 descriptive tags
     * @param  string|null  $description     One-sentence description
     */
    public function __construct(
        public readonly ?string $suggestedTitle = null,
        public readonly array $suggestedTags = [],
        public readonly ?string $description = null,
    ) {}

    /**
     * Build from an associative array (e.g. parsed JSON from AI response).
     *
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            suggestedTitle: isset($data['suggested_title']) ? (string) $data['suggested_title'] : null,
            suggestedTags: isset($data['suggested_tags']) && is_array($data['suggested_tags'])
                ? array_values(array_map('strval', $data['suggested_tags']))
                : [],
            description: isset($data['description']) ? (string) $data['description'] : null,
        );
    }

    /** True when the AI returned at least a title or tags. */
    public function hasData(): bool
    {
        return $this->suggestedTitle !== null || $this->suggestedTags !== [];
    }
}
