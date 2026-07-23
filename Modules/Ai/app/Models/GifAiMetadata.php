<?php

namespace Modules\Ai\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Gif\Models\Gif;

/**
 * Eloquent model for the gif_ai_metadata table.
 *
 * Owned by the Ai module — the Gif model has a soft reference (HasOne)
 * that resolves this class by fully-qualified name at runtime.
 */
class GifAiMetadata extends Model
{
    protected $table = 'gif_ai_metadata';

    protected $fillable = [
        'gif_id',
        'suggested_title',
        'suggested_tags',
        'description',
        'embedding',
        'moderation_status',
        'moderation_reason',
        'analyzed_at',
    ];

    protected $casts = [
        'suggested_tags'  => 'array',
        'embedding'       => 'array',   // float[] stored as JSON
        'analyzed_at'     => 'datetime',
    ];

    public function gif(): BelongsTo
    {
        return $this->belongsTo(Gif::class, 'gif_id');
    }

    /** True when AI analysis completed (regardless of result). */
    public function isAnalyzed(): bool
    {
        return $this->analyzed_at !== null;
    }

    /** True when content moderation cleared the GIF. */
    public function isApproved(): bool
    {
        return $this->moderation_status === 'approved';
    }

    /** True when AI flagged the content for manual review. */
    public function isFlagged(): bool
    {
        return $this->moderation_status === 'flagged';
    }
}
