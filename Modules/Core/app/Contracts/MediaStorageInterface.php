<?php

namespace Modules\Core\Contracts;

use Illuminate\Http\UploadedFile;

/**
 * Contract for media file storage.
 *
 * هر ماژول (مثلاً Gif) به‌جای وابستگی مستقیم به Storage facade،
 * از این interface استفاده می‌کنه تا بشه در آینده driver رو بدون
 * تغییر کد ماژول‌ها عوض کرد (مثلاً از local به S3).
 */
interface MediaStorageInterface
{
    /**
     * Store an uploaded file and return its storage path.
     */
    public function store(UploadedFile $file, string $directory = ''): string;

    /**
     * Store raw content (string) under the given path.
     */
    public function put(string $path, string $contents): bool;

    /**
     * Retrieve the full URL to access a stored file.
     */
    public function url(string $path): string;

    /**
     * Check whether a file exists at the given path.
     */
    public function exists(string $path): bool;

    /**
     * Delete a file at the given path.
     * Returns true on success, false if the file did not exist.
     */
    public function delete(string $path): bool;

    /**
     * Return the absolute filesystem path for a stored file.
     * Useful for local processing (e.g. GIF manipulation).
     */
    public function path(string $path): string;

    /**
     * Return the name of the underlying storage disk being used.
     */
    public function disk(): string;
}
