<?php

namespace Modules\Core\Contracts;

use Illuminate\Http\UploadedFile;

interface MediaStorageInterface
{
    public function store(UploadedFile $file, string $directory = ''): string;

    public function put(string $path, string $contents): bool;

    public function url(string $path): string;

    public function exists(string $path): bool;

    public function delete(string $path): bool;

    public function path(string $path): string;

    public function disk(): string;
}
