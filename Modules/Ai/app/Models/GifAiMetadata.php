<?php

namespace Modules\Ai\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Gif\Models\Gif;

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
        'suggested_tags' => 'array',
        'embedding'      => 'array',
        'analyzed_at'    => 'datetime',
    ];

    public function gif(): BelongsTo
    {
        return $this->belongsTo(Gif::class, 'gif_id');
    }

    public function isAnalyzed(): bool
    {
        return $this->analyzed_at !== null;
    }

    public function isApproved(): bool
    {
        return $this->moderation_status === 'approved';
    }

    public function isFlagged(): bool
    {
        return $this->moderation_status === 'flagged';
    }
}
