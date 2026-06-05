<?php

use Illuminate\Support\Facades\Storage;

if (! function_exists('media_url')) {
    function media_url(?string $path): ?string
    {
        if (blank($path)) {
            return null;
        }

        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return $path;
        }

        if (str_starts_with($path, 'images/') || str_starts_with($path, 'documents/')) {
            return asset($path);
        }

        return Storage::disk('public')->url($path);
    }
}

if (! function_exists('media_exists')) {
    function media_exists(?string $path): bool
    {
        if (blank($path)) {
            return false;
        }

        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return true;
        }

        if (str_starts_with($path, 'images/') || str_starts_with($path, 'documents/')) {
            return file_exists(public_path($path));
        }

        return Storage::disk('public')->exists($path);
    }
}
