<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DocumentService
{
    /**
     * Store an uploaded document securely in private storage.
     */
    public static function storePrivate(UploadedFile $file, string $directory = 'documents'): array
    {
        $extension = $file->getClientOriginalExtension();
        $safeName = Str::random(32) . '.' . $extension;
        $path = $file->storeAs('private/' . $directory, $safeName, 'local');

        return [
            'file_path' => $path,
            'file_name' => $file->getClientOriginalName(),
            'file_size' => $file->getSize(),
            'mime_type' => $file->getMimeType(),
        ];
    }

    /**
     * Store public upload (e.g. news photos, partner logos).
     */
    public static function storePublic(UploadedFile $file, string $directory = 'uploads'): string
    {
        $extension = $file->getClientOriginalExtension();
        $safeName = Str::random(24) . '.' . $extension;
        return $file->storeAs($directory, $safeName, 'public');
    }
}
