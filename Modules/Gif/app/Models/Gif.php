<?php

namespace Modules\Gif\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Gif extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'file_path',
        'file_size',
        'mime_type',
        'original_filename',
        'status',
        'uploaded_by',
    ];

    protected $casts = [
        'file_size' => 'integer',
    ];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    protected function title(): Attribute
    {
        return Attribute::make(
            set: function (string $value) {
                $clean = preg_replace(
                    '/<(script|style|iframe|object|embed|applet|form)[^>]*>.*?<\/\1>/is',
                    '',
                    $value
                ) ?? $value;

                $clean = strip_tags($clean);
                $clean = trim((string) preg_replace('/\s+/u', ' ', $clean));

                if (empty($this->slug)) {
                    $this->attributes['slug'] = Str::slug($clean) . '-' . Str::random(6);
                }

                return $clean;
            },
        );
    }

    public function getUrlAttribute(): string
    {
        return Storage::disk('public')->url($this->file_path);
    }

    public function getFormattedSizeAttribute(): string
    {
        $bytes = $this->file_size;

        if ($bytes < 1024) {
            return $bytes . ' B';
        }

        if ($bytes < 1048576) {
            return round($bytes / 1024, 1) . ' KB';
        }

        return round($bytes / 1048576, 2) . ' MB';
    }

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function aiMetadata(): HasOne
    {
        return $this->hasOne(\Modules\Ai\Models\GifAiMetadata::class, 'gif_id');
    }

    public function scopeApproved(Builder $query): Builder
    {
        return $query->where('status', 'approved');
    }

    public function scopePendingReview(Builder $query): Builder
    {
        return $query->where('status', 'pending_review');
    }

    public function scopeFlagged(Builder $query): Builder
    {
        return $query->where('status', 'flagged');
    }

    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    public function isPendingReview(): bool
    {
        return $this->status === 'pending_review';
    }

    public function isFlagged(): bool
    {
        return $this->status === 'flagged';
    }
}
