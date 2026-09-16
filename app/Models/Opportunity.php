<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Opportunity extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'title',
        'slug',
        'provider_name',
        'provider_logo',
        'summary',
        'description',
        'eligibility_criteria',
        'location',
        'deadline',
        'application_type',
        'external_url',
        'required_documents',
        'contact_info',
        'status',
        'is_verified',
        'is_featured',
        'published_by',
        'published_at',
    ];

    protected $casts = [
        'deadline' => 'date',
        'required_documents' => 'array',
        'is_verified' => 'boolean',
        'is_featured' => 'boolean',
        'published_at' => 'datetime',
    ];

    public function category()
    {
        return $this->belongsTo(OpportunityCategory::class, 'category_id');
    }

    public function applications()
    {
        return $this->hasMany(OpportunityApplication::class);
    }

    public function publisher()
    {
        return $this->belongsTo(User::class, 'published_by');
    }

    public function isExpired(): bool
    {
        return $this->deadline && $this->deadline->isPast();
    }

    public function getProviderLogoUrlAttribute(): ?string
    {
        return \App\Helpers\ImageHelper::getUrl($this->provider_logo);
    }

    public function hasProviderLogo(): bool
    {
        return \App\Helpers\ImageHelper::exists($this->provider_logo);
    }
}
