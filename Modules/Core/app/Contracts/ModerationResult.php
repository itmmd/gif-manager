<?php

namespace Modules\Core\Contracts;

final class ModerationResult
{
    public function __construct(
        public readonly bool $isFlagged = false,
        public readonly ?string $reason = null,
    ) {}

    public static function safe(): self
    {
        return new self(isFlagged: false);
    }

    public static function flagged(string $reason): self
    {
        return new self(isFlagged: true, reason: $reason);
    }
}
