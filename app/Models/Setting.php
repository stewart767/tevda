<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'group',
        'key',
        'value',
        'type',
        'description',
        'is_public',
    ];

    protected $casts = [
        'is_public' => 'boolean',
    ];

    public static function get(string $key, $default = null)
    {
        $setting = self::where('key', $key)->first();
        return $setting ? $setting->value : $default;
    }

    public static function set(string $key, $value, string $group = 'general', string $type = 'text', ?string $description = null)
    {
        return self::updateOrCreate(
            ['key' => $key],
            [
                'value' => $value,
                'group' => $group,
                'type' => $type,
                'description' => $description,
            ]
        );
    }

    /**
     * Check if a custom system logo is uploaded and present.
     */
    public static function hasCustomLogo(): bool
    {
        $logo = self::get('site_logo');
        if (empty($logo)) {
            return false;
        }

        return \App\Helpers\ImageHelper::exists($logo);
    }

    /**
     * Get the web URL of the system logo.
     */
    public static function getLogoUrl(): ?string
    {
        $logo = self::get('site_logo');
        if (empty($logo)) {
            return null;
        }

        return \App\Helpers\ImageHelper::getUrl($logo);
    }

    /**
     * Get the absolute filesystem path of the system logo.
     */
    public static function getLogoPath(): ?string
    {
        $logo = self::get('site_logo');
        if (empty($logo)) {
            return null;
        }

        $cleanPath = ltrim(str_replace('\\', '/', $logo), '/');
        $diskPath = \Illuminate\Support\Str::startsWith($cleanPath, 'storage/') 
            ? \Illuminate\Support\Str::after($cleanPath, 'storage/') 
            : $cleanPath;

        $path = Storage::disk('public')->path($diskPath);
        if (file_exists($path)) {
            return $path;
        }

        $publicPath = public_path('storage/' . $diskPath);
        if (file_exists($publicPath)) {
            return $publicPath;
        }

        $directPath = public_path($cleanPath);
        if (file_exists($directPath)) {
            return $directPath;
        }

        return null;
    }

    /**
     * Get base64 Data URI of the system logo (ideal for DomPDF).
     */
    public static function getLogoDataUri(): ?string
    {
        $path = self::getLogoPath();
        if ($path && file_exists($path)) {
            $mime = mime_content_type($path);
            if (!$mime || $mime === 'text/plain') {
                $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
                $mime = match ($ext) {
                    'png' => 'image/png',
                    'jpg', 'jpeg' => 'image/jpeg',
                    'svg' => 'image/svg+xml',
                    'webp' => 'image/webp',
                    default => 'image/png',
                };
            }
            $data = file_get_contents($path);
            return 'data:' . $mime . ';base64,' . base64_encode($data);
        }

        return null;
    }

    /**
     * Check if a custom chairman photo is uploaded and present.
     */
    public static function hasChairmanPhoto(): bool
    {
        $photo = self::get('chairman_photo', 'images/chairman_dr_charles_mwansasu.jpg');
        if (empty($photo)) {
            return false;
        }

        return \App\Helpers\ImageHelper::exists($photo);
    }

    /**
     * Get the web URL of the chairman photo.
     */
    public static function getChairmanPhotoUrl(): ?string
    {
        $photo = self::get('chairman_photo', 'images/chairman_dr_charles_mwansasu.jpg');
        if (empty($photo)) {
            return null;
        }

        return \App\Helpers\ImageHelper::getUrl($photo, asset('images/chairman_dr_charles_mwansasu.jpg'));
    }
}

