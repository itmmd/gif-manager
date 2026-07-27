<?php

namespace Modules\Core\Contracts;

final class MediaAnalysisResult
{
    public function __construct(
        public readonly ?string $suggestedTitle = null,
        public readonly array $suggestedTags = [],
        public readonly ?string $description = null,
    ) {}

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

    public function hasData(): bool
    {
        return $this->suggestedTitle !== null || $this->suggestedTags !== [];
    }
}
