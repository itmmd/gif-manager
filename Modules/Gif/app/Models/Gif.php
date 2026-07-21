<?php

namespace Modules\Gif\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class Gif extends Model
{
    protected $fillable = [
        'title',
        'file_path',
        'file_size',
        'mime_type',
        'original_filename',
        'uploaded_by',
    ];

    protected $casts = [
        'file_size' => 'integer',
    ];

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
}
