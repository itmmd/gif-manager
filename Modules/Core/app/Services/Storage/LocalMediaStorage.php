<?php

namespace Modules\Core\Services\Storage;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Modules\Core\Contracts\MediaStorageInterface;

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
