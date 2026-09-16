<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImageHelper
{
    /**
     * Resolve a bulletproof public URL for any image path.
     * Supports relative storage paths, full URLs, data URIs, and fallback placeholders.
     */
    public static function getUrl(?string $path, ?string $fallback = null): ?string
    {
        if (empty($path)) {
            return $fallback;
        }

        // If it's already an absolute URL or Data URI, return directly
        if (Str::startsWith($path, ['http://', 'https://', 'data:', '//'])) {
            return $path;
        }

        // Normalize slashes
        $cleanPath = ltrim(str_replace('\\', '/', $path), '/');

        // If path starts with 'storage/', strip it for disk check
        $diskPath = Str::startsWith($cleanPath, 'storage/') 
            ? Str::after($cleanPath, 'storage/') 
            : $cleanPath;

        // Check if file exists in storage/app/public disk
        if (Storage::disk('public')->exists($diskPath)) {
            return asset('storage/' . $diskPath);
        }

        // Check if file exists directly in public/storage
        if (file_exists(public_path('storage/' . $diskPath))) {
            return asset('storage/' . $diskPath);
        }

        // Check if file exists directly in public/
        if (file_exists(public_path($cleanPath))) {
            return asset($cleanPath);
        }

        // If not found physically but has a value, still construct public storage URL
        if (!empty($diskPath)) {
            return asset('storage/' . $diskPath);
        }

        return $fallback;
    }

    /**
     * Check if a valid image exists on disk for a given path.
     */
    public static function exists(?string $path): bool
    {
        if (empty($path)) {
            return false;
        }

        if (Str::startsWith($path, ['http://', 'https://', 'data:'])) {
            return true;
        }

        $cleanPath = ltrim(str_replace('\\', '/', $path), '/');
        $diskPath = Str::startsWith($cleanPath, 'storage/') 
            ? Str::after($cleanPath, 'storage/') 
            : $cleanPath;

        return Storage::disk('public')->exists($diskPath)
            || file_exists(public_path('storage/' . $diskPath))
            || file_exists(public_path($cleanPath));
    }
}
