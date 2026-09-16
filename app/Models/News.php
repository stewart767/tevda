<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    use HasFactory;

    protected $table = 'news';

    protected $fillable = [
        'title',
        'slug',
        'category',
        'featured_image',
        'summary',
        'content',
        'author_name',
        'publication_date',
        'is_published',
        'seo_title',
        'seo_description',
    ];

    protected $casts = [
        'publication_date' => 'date',
        'is_published' => 'boolean',
    ];

    public function getFeaturedImageUrlAttribute(): ?string
    {
        return \App\Helpers\ImageHelper::getUrl($this->featured_image);
    }

    public function hasFeaturedImage(): bool
    {
        return \App\Helpers\ImageHelper::exists($this->featured_image);
    }
}
