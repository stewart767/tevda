<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IdCardTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'card_type',
        'front_background_image_path',
        'back_background_image_path',
        'orientation',
        'placeholders_config',
        'is_active',
        'is_default',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_default' => 'boolean',
    ];

    public function cards()
    {
        return $this->hasMany(MembershipCard::class, 'template_id');
    }

    public function getPlaceholdersConfigAttribute($value)
    {
        $defaults = self::getDefaultPlaceholdersConfig();

        if (empty($value)) {
            return $defaults;
        }

        $decoded = is_string($value) ? json_decode($value, true) : $value;

        if (!is_array($decoded)) {
            return $defaults;
        }

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

    public function getFrontBackgroundImageUrl(): ?string
    {
        if (!$this->front_background_image_path) {
            return null;
        }

        return asset('storage/' . $this->front_background_image_path);
    }

    public function getBackBackgroundImageUrl(): ?string
    {
        if (!$this->back_background_image_path) {
            return null;
        }

        return asset('storage/' . $this->back_background_image_path);
    }

    public function getFrontBackgroundImageDataUri(): ?string
    {
        if (!$this->front_background_image_path) {
            return null;
        }

        $fullPath = storage_path('app/public/' . $this->front_background_image_path);
        if (!file_exists($fullPath)) {
            $fullPath = public_path('storage/' . $this->front_background_image_path);
        }

        if (file_exists($fullPath)) {
            $mime = mime_content_type($fullPath) ?: 'image/png';
            return 'data:' . $mime . ';base64,' . base64_encode(file_get_contents($fullPath));
        }

        return null;
    }

    public function getBackBackgroundImageDataUri(): ?string
    {
        if (!$this->back_background_image_path) {
            return null;
        }

        $fullPath = storage_path('app/public/' . $this->back_background_image_path);
        if (!file_exists($fullPath)) {
            $fullPath = public_path('storage/' . $this->back_background_image_path);
        }

        if (file_exists($fullPath)) {
            $mime = mime_content_type($fullPath) ?: 'image/png';
            return 'data:' . $mime . ';base64,' . base64_encode(file_get_contents($fullPath));
        }

        return null;
    }

    /**
     * Default CR80 ISO/IEC 7810 ID-1 standard layout coordinates.
     */
    public static function getDefaultPlaceholdersConfig(): array
    {
        return [
            // FRONT SIDE ELEMENTS
            'front' => [
                'header' => [
                    'enabled' => true,
                    'top' => 5.0,
                    'left' => 5.0,
                    'show_logo' => true,
                    'logo_height' => 16.0,
                    'title' => 'TEVDA',
                    'subtitle' => 'Tanzania Electric Vehicles Drivers Association',
                    'font_size' => 10.0,
                    'color' => '#ffffff',
                ],
                'category' => [
                    'enabled' => true,
                    'top' => 5.5,
                    'left' => 68.0,
                    'font_size' => 6.5,
                    'font_weight' => 'bold',
                    'color' => '#a7f3d0',
                    'bg_color' => '#065f46',
                    'border_color' => '#10b981',
                    'has_bg' => true,
                ],
                'photo' => [
                    'enabled' => true,
                    'top' => 22.0,
                    'left' => 5.5,
                    'width' => 21.0,
                    'height' => 42.0,
                    'border_radius' => 4.0,
                    'border_color' => '#10b981',
                    'border_width' => 1.5,
                ],
                'full_name' => [
                    'enabled' => true,
                    'top' => 22.0,
                    'left' => 29.5,
                    'width' => 45.0,
                    'font_size' => 9.0,
                    'font_weight' => 'bold',
                    'color' => '#ffffff',
                    'text_align' => 'left',
                    'text_transform' => 'uppercase',
                    'show_label' => true,
                    'label_text' => 'Member Name / Jina',
                ],
                'membership_number' => [
                    'enabled' => true,
                    'top' => 38.0,
                    'left' => 29.5,
                    'font_size' => 7.8,
                    'font_weight' => 'bold',
                    'color' => '#f59e0b',
                    'bg_color' => '#022c22',
                    'border_color' => '#f59e0b',
                    'has_bg' => true,
                    'show_label' => true,
                    'label_text' => 'Member ID / Namba',
                ],
                'region' => [
                    'enabled' => true,
                    'top' => 53.0,
                    'left' => 29.5,
                    'width' => 45.0,
                    'font_size' => 6.5,
                    'font_weight' => 'normal',
                    'color' => '#cbd5e1',
                    'show_label' => true,
                    'label_text' => 'Region & Territory',
                ],
                'expiry_date' => [
                    'enabled' => true,
                    'top' => 66.5,
                    'left' => 29.5,
                    'font_size' => 6.5,
                    'font_weight' => 'bold',
                    'color' => '#f59e0b',
                    'show_label' => true,
                    'label_text' => 'Validity / Hali',
                ],
                'qr_code' => [
                    'enabled' => true,
                    'top' => 22.0,
                    'left' => 77.0,
                    'size' => 40.0,
                    'bg_color' => '#ffffff',
                    'has_bg' => true,
                    'show_label' => true,
                    'label_text' => 'Scan to Verify',
                ],
                'motto' => [
                    'enabled' => true,
                    'top' => 88.5,
                    'left' => 50.0,
                    'width' => 90.0,
                    'font_size' => 5.2,
                    'font_weight' => 'bold',
                    'color' => '#f59e0b',
                    'text_align' => 'center',
                    'text' => 'SMART DRIVERS SMART MOBILITY • WWW.TEVDA.OR.TZ',
                ],
            ],

            // BACK SIDE ELEMENTS
            'back' => [
                'magnetic_stripe' => [
                    'enabled' => true,
                    'top' => 0.0,
                    'height' => 14.0,
                    'bg_color' => '#020617',
                    'show_card_number' => true,
                    'font_size' => 5.5,
                    'color' => '#cbd5e1',
                ],
                'terms' => [
                    'enabled' => true,
                    'top' => 20.0,
                    'left' => 6.0,
                    'width' => 88.0,
                    'font_size' => 4.5,
                    'color' => '#94a3b8',
                    'text' => 'This smart ID card certifies that the cardholder is a registered and compliant member of the Tanzania Electric Vehicles Drivers Association (TEVDA). Card is non-transferable and must be presented upon request during official operations.',
                ],
                'signatory' => [
                    'enabled' => true,
                    'top' => 48.0,
                    'left' => 6.0,
                    'width' => 44.0,
                    'font_size' => 5.5,
                    'color' => '#ffffff',
                    'name' => Setting::get('chairman_name', 'Dr. Charles Mwansasu'),
                    'title' => Setting::get('chairman_role', 'Founding Chairperson') . ' • TEVDA',
                ],
                'helpline' => [
                    'enabled' => true,
                    'top' => 48.0,
                    'left' => 52.0,
                    'width' => 42.0,
                    'font_size' => 4.5,
                    'color' => '#cbd5e1',
                ],
                'barcode' => [
                    'enabled' => true,
                    'top' => 77.0,
                    'left' => 50.0,
                    'width' => 60.0,
                    'font_size' => 6.5,
                    'color' => '#94a3b8',
                    'text_align' => 'center',
                ],
                'return_notice' => [
                    'enabled' => true,
                    'top' => 90.0,
                    'left' => 50.0,
                    'width' => 90.0,
                    'font_size' => 4.0,
                    'color' => '#94a3b8',
                    'text_align' => 'center',
                    'text' => 'Property of TEVDA. If found, please return to any TEVDA Regional Office or Police Station.',
                ],
            ],
        ];
    }

    /**
     * Get auto-detect / layout preset profiles.
     */
    public static function getPresetProfiles(): array
    {
        $default = self::getDefaultPlaceholdersConfig();

        // Preset 1: Official Standard
        $official = $default;

        // Preset 2: Pre-Printed Card Shell (Hides logo, motto, magnetic stripe bar since they are already printed on physical plastic)
        $preprinted = $default;
        $preprinted['front']['header']['show_logo'] = false;
        $preprinted['front']['header']['title'] = '';
        $preprinted['front']['header']['subtitle'] = '';
        $preprinted['front']['motto']['enabled'] = false;
        $preprinted['back']['magnetic_stripe']['bg_color'] = 'transparent';
        $preprinted['back']['terms']['enabled'] = false;

        // Preset 3: Right Photo Layout
        $rightPhoto = $default;
        $rightPhoto['front']['photo']['left'] = 73.5;
        $rightPhoto['front']['full_name']['left'] = 6.0;
        $rightPhoto['front']['membership_number']['left'] = 6.0;
        $rightPhoto['front']['region']['left'] = 6.0;
        $rightPhoto['front']['expiry_date']['left'] = 6.0;
        $rightPhoto['front']['qr_code']['left'] = 48.0;
        $rightPhoto['front']['category']['left'] = 6.0;

        return [
            'official_standard' => [
                'name' => 'Official TEVDA CR80 Layout',
                'description' => 'Left Photo, Center Info, Right QR Code & Top Header.',
                'config' => $official,
            ],
            'preprinted_shell' => [
                'name' => 'Pre-Printed Plastic Card Shell',
                'description' => 'Only injects Photo, Name, ID, QR into pre-printed template shells.',
                'config' => $preprinted,
            ],
            'right_photo' => [
                'name' => 'Right Photo & Left Details',
                'description' => 'Details on left with photo on right side.',
                'config' => $rightPhoto,
            ],
        ];
    }
}
