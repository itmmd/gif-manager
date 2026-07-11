<?php

namespace Modules\Core\Services\Storage;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Modules\Core\Contracts\MediaStorageInterface;

/**
 * Local disk implementation of MediaStorageInterface.
 *
 * این کلاس پیاده‌سازی پیش‌فرض برای ذخیره‌سازی روی دیسک local است.
 * برای جایگزینی با S3 کافیه یک S3MediaStorage بسازی و binding رو
 * در CoreServiceProvider عوض کنی — بقیه کد پروژه بدون تغییر کار می‌کنه.
 */
class LocalMediaStorage implements MediaStorageInterface
{
    public function __construct(
        private readonly string $diskName = 'local'
    ) {}

    public function store(UploadedFile $file, string $directory = ''): string
    {
        return $file->store($directory, $this->diskName) ?: '';
    }

    public function put(string $path, string $contents): bool
    {
        return Storage::disk($this->diskName)->put($path, $contents);
    }

    public function url(string $path): string
    {
        return Storage::disk($this->diskName)->url($path);
    }

    public function exists(string $path): bool
    {
        return Storage::disk($this->diskName)->exists($path);
    }

    public function delete(string $path): bool
    {
        if (! $this->exists($path)) {
            return false;
        }

        return Storage::disk($this->diskName)->delete($path);
    }

    public function path(string $path): string
    {
        return Storage::disk($this->diskName)->path($path);
    }

    public function disk(): string
    {
        return $this->diskName;
    }
}
