<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CertificateTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'certificate_type',
        'background_image_path',
        'orientation',
        'template_html',
        'placeholders_config',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function getPlaceholdersConfigAttribute($value)
    {
        $orientation = $this->attributes['orientation'] ?? 'landscape';
        $defaults = self::getDefaultPlaceholdersConfig($orientation);

        if (empty($value)) {
            return $defaults;
        }

        $decoded = is_string($value) ? json_decode($value, true) : $value;

        // If it's a legacy flat indexed array of strings ['{{member_name}}', ...] or invalid structure
        if (!is_array($decoded) || (isset($decoded[0]) && is_string($decoded[0]))) {
            return $defaults;
        }

        // Merge with defaults to guarantee all expected element keys exist
        return array_replace_recursive($defaults, $decoded);
    }

    public function setPlaceholdersConfigAttribute($value)
    {
        if (is_string($value)) {
            $decoded = json_decode($value, true);
            if (is_array($decoded)) {
                $value = $decoded;
            }
        }

        $this->attributes['placeholders_config'] = is_array($value) ? json_encode($value) : $value;
    }

    public function certificates()
    {
        return $this->hasMany(Certificate::class, 'template_id');
    }

    public function getBackgroundImageUrl(): ?string
    {
        if (!$this->background_image_path) {
            return null;
        }

        return asset('storage/' . $this->background_image_path);
    }

    public function getBackgroundImageDataUri(): ?string
    {
        if (!$this->background_image_path) {
            return null;
        }

        $fullPath = storage_path('app/public/' . $this->background_image_path);
        if (!file_exists($fullPath)) {
            $fullPath = public_path('storage/' . $this->background_image_path);
        }

        if (file_exists($fullPath)) {
            $mime = mime_content_type($fullPath) ?: 'image/png';
            return 'data:' . $mime . ';base64,' . base64_encode(file_get_contents($fullPath));
        }

        return null;
    }

    public static function getDefaultPlaceholdersConfig(string $orientation = 'landscape'): array
    {
        if ($orientation === 'portrait') {
            return [
                'header' => [
                    'enabled' => true,
                    'top' => 7.5,
                    'left' => 50.0,
                    'width' => 90.0,
                    'font_size' => 12.5,
                    'show_logo' => true,
                    'text_align' => 'center',
                ],
                'title' => [
                    'enabled' => true,
                    'top' => 21.0,
                    'left' => 50.0,
                    'width' => 90.0,
                    'font_size' => 30,
                    'font_weight' => 'bold',
                    'color' => '#0f2744',
                    'text_align' => 'center',
                ],
                'recipient_name' => [
                    'enabled' => true,
                    'top' => 32.5,
                    'left' => 50.0,
                    'width' => 90.0,
                    'font_size' => 34,
                    'font_weight' => 'bold',
                    'color' => '#065f46',
                    'text_align' => 'center',
                ],
                'body_text' => [
                    'enabled' => true,
                    'top' => 44.5,
                    'left' => 50.0,
                    'width' => 86.0,
                    'font_size' => 14.0,
                    'font_weight' => 'normal',
                    'color' => '#334155',
                    'text_align' => 'center',
                ],
                'signatory' => [
                    'enabled' => true,
                    'top' => 66.5,
                    'left' => 8.5,
                    'width' => 32.0,
                    'font_size' => 14.5,
                    'font_weight' => 'bold',
                    'color' => '#0f172a',
                    'text_align' => 'left',
                ],
                'qr_code' => [
                    'enabled' => true,
                    'top' => 64.0,
                    'left' => 50.0,
                    'size' => 110,
                    'bg_color' => '#ffffff',
                    'has_bg' => true,
                ],
                'issue_date' => [
                    'enabled' => true,
                    'top' => 66.5,
                    'left' => 61.5,
                    'width' => 32.0,
                    'font_size' => 11.5,
                    'font_weight' => 'normal',
                    'color' => '#64748b',
                    'text_align' => 'right',
                ],
                'certificate_number' => [
                    'enabled' => true,
                    'top' => 74.5,
                    'left' => 61.5,
                    'width' => 32.0,
                    'font_size' => 11.0,
                    'font_weight' => 'bold',
                    'color' => '#0f172a',
                    'text_align' => 'right',
                ],
            ];
        }

        // Standard Landscape: Perfectly aligned with TEVDA Official Template graphics
        return [
            'header' => [
                'enabled' => true,
                'top' => 5.2,
                'left' => 50.0,
                'width' => 84.0,
                'font_size' => 19.5,
                'show_logo' => true,
                'text_align' => 'center',
            ],
            'title' => [
                'enabled' => true,
                'top' => 32.2,
                'left' => 50.0,
                'width' => 70.0,
                'font_size' => 27.0,
                'font_weight' => 'bold',
                'color' => '#fef08a',
                'text_align' => 'center',
            ],
            'recipient_name' => [
                'enabled' => true,
                'top' => 45.2,
                'left' => 50.0,
                'width' => 80.0,
                'font_size' => 32.0,
                'font_weight' => 'bold',
                'color' => '#0f172a',
                'text_align' => 'center',
            ],
            'body_text' => [
                'enabled' => true,
                'top' => 54.5,
                'left' => 50.0,
                'width' => 72.0,
                'font_size' => 10.5,
                'font_weight' => 'normal',
                'color' => '#334155',
                'text_align' => 'center',
            ],
            'signatory' => [
                'enabled' => true,
                'top' => 65.5,
                'left' => 11.0,
                'width' => 22.0,
                'font_size' => 10.5,
                'font_weight' => 'bold',
                'color' => '#0f172a',
                'text_align' => 'center',
            ],
            'qr_code' => [
                'enabled' => true,
                'top' => 71.5,
                'left' => 81.0,
                'size' => 65,
                'bg_color' => '#ffffff',
                'has_bg' => true,
            ],
            'issue_date' => [
                'enabled' => true,
                'top' => 63.0,
                'left' => 69.0,
                'width' => 23.0,
                'font_size' => 9.0,
                'font_weight' => 'normal',
                'color' => '#64748b',
                'text_align' => 'right',
            ],
            'certificate_number' => [
                'enabled' => true,
                'top' => 63.0,
                'left' => 69.0,
                'width' => 23.0,
                'font_size' => 9.0,
                'font_weight' => 'bold',
                'color' => '#0f172a',
                'text_align' => 'right',
            ],
        ];
    }
}
