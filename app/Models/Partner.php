<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Partner extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'category',
        'logo_path',
        'description',
        'website_url',
        'collaboration_area',
        'status',
        'start_date',
        'is_featured',
        'order_number',
    ];

    protected $casts = [
        'start_date' => 'date',
        'is_featured' => 'boolean',
    ];

    public function getLogoUrlAttribute(): ?string
    {
        return \App\Helpers\ImageHelper::getUrl($this->logo_path);
    }

    public function hasLogo(): bool
    {
        return \App\Helpers\ImageHelper::exists($this->logo_path);
    }
}
