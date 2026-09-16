<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'title',
        'slug',
        'summary',
        'description',
        'location',
        'target_regions',
        'target_beneficiaries_count',
        'funding_status',
        'project_status',
        'budget_amount',
        'currency',
        'start_date',
        'end_date',
        'application_open_date',
        'application_close_date',
        'featured_image',
        'partner_organisations',
        'is_featured',
    ];

    protected $casts = [
        'target_regions' => 'array',
        'budget_amount' => 'decimal:2',
        'start_date' => 'date',
        'end_date' => 'date',
        'application_open_date' => 'date',
        'application_close_date' => 'date',
        'is_featured' => 'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(ProjectCategory::class, 'category_id');
    }

    public function applications()
    {
        return $this->hasMany(ProjectApplication::class);
    }

    public function beneficiaries()
    {
        return $this->hasMany(Beneficiary::class);
    }

    public function isOpenForApplications(): bool
    {
        return $this->project_status === 'open_for_applications';
    }

    public function getFeaturedImageUrlAttribute(): ?string
    {
        return \App\Helpers\ImageHelper::getUrl($this->featured_image);
    }

    public function hasFeaturedImage(): bool
    {
        return \App\Helpers\ImageHelper::exists($this->featured_image);
    }
}
