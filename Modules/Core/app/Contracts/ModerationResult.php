<?php

namespace Modules\Core\Contracts;

/**
 * Value object returned by MediaIntelligenceInterface::checkModeration().
 *
 * Lives in Core so that both Gif (consumer) and Ai (producer) can
 * reference it without a direct cross-module dependency.
 */
final class ModerationResult
{
    /**
     * @param  bool         $isFlagged  True when content should NOT be auto-published
     * @param  string|null  $reason     Human-readable reason if flagged
     */
    public function __construct(
        public readonly bool $isFlagged = false,
        public readonly ?string $reason = null,
    ) {}

    /** Convenience factory: content is safe. */
    public static function safe(): self
    {
        return new self(isFlagged: false);
    }

    /** Convenience factory: content is flagged. */
    public static function flagged(string $reason): self
    {
        return new self(isFlagged: true, reason: $reason);
    }
}
