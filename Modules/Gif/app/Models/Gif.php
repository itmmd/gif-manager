<?php

namespace Modules\Gif\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
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

    /**
     * Use slug as the route model binding key.
     * Routes become /gifs/funny-cat-a3f9b2 instead of /gifs/9.
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /**
     * Sanitise the title on write: remove all HTML tags AND their inner
     * content for dangerous elements (script, style, iframe, object, embed),
     * then strip any remaining tags, and collapse whitespace.
     *
     * strip_tags() alone leaves tag content behind:
     *   strip_tags('<script>alert(1)</script>Hi') → 'alert(1)Hi'  ← unsafe
     * We strip script/style bodies first, then strip remaining tags.
     */
    protected function title(): Attribute
    {
        return Attribute::make(
            set: function (string $value) {
                // 1. Nuke dangerous tag bodies (script, style, iframe, etc.)
                $clean = preg_replace(
                    '/<(script|style|iframe|object|embed|applet|form)[^>]*>.*?<\/\1>/is',
                    '',
                    $value
                ) ?? $value;

                // 2. Strip any remaining HTML tags (e.g. <b>, <img>, etc.)
                $clean = strip_tags($clean);

                // 3. Collapse & trim whitespace
                $clean = trim((string) preg_replace('/\s+/u', ' ', $clean));

                // Only generate a new slug when the record is new (no slug yet).
                // Updating the title on an existing GIF keeps the old slug so
                // existing URLs / links don't break.
                if (empty($this->slug)) {
                    $this->attributes['slug'] = Str::slug($clean) . '-' . Str::random(6);
                }

                return $clean;
            },
        );
    }

    /** URL قابل دسترس از web */
    public function getUrlAttribute(): string
    {
        return Storage::disk('public')->url($this->file_path);
    }

    /** نمایش حجم فایل به‌صورت خوانا */
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

    /** کاربری که GIF را آپلود کرده */
    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    /** متادیتای AI مرتبط */
    public function aiMetadata(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(\Modules\Ai\Models\GifAiMetadata::class, 'gif_id');
    }

    /** فقط GIF‌های تأیید شده (برای گالری عمومی) */
    public function scopeApproved(\Illuminate\Database\Eloquent\Builder $query): \Illuminate\Database\Eloquent\Builder
    {
        return $query->where('status', 'approved');
    }

    /** GIF‌های در انتظار بررسی */
    public function scopePendingReview(\Illuminate\Database\Eloquent\Builder $query): \Illuminate\Database\Eloquent\Builder
    {
        return $query->where('status', 'pending_review');
    }

    /** GIF‌های پرچم‌گذاری شده توسط AI */
    public function scopeFlagged(\Illuminate\Database\Eloquent\Builder $query): \Illuminate\Database\Eloquent\Builder
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
