<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Slider extends Model
{
    use HasFactory;

    protected $table = 'sliders';

    protected $fillable = [
        'title',
        'subtitle',
        'image_path',
        'link_url',
        'order_number',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'order_number' => 'integer',
    ];

    /**
     * Scope for only active slides.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for ordering slides.
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('order_number', 'asc')->orderBy('id', 'asc');
    }

    /**
     * Get the accessible public URL for the slide image.
     */
    public function getImageUrlAttribute(): string
    {
        return \App\Helpers\ImageHelper::getUrl($this->image_path, asset('images/slider/slide-1.jpg'))
            ?: asset('images/slider/slide-1.jpg');
    }
}
