<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Leader extends Model
{
    use HasFactory;

    protected $fillable = [
        'governance_body_id',
        'name',
        'position',
        'photo_path',
        'biography',
        'qualifications',
        'sector_experience',
        'responsibilities',
        'official_office_contact',
        'term_period',
        'is_founding_leader',
        'founding_position',
        'is_active',
        'order_number',
    ];

    protected $casts = [
        'is_founding_leader' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function governanceBody()
    {
        return $this->belongsTo(GovernanceBody::class, 'governance_body_id');
    }

    public function getPhotoUrlAttribute(): ?string
    {
        return \App\Helpers\ImageHelper::getUrl($this->photo_path);
    }

    public function hasPhoto(): bool
    {
        return \App\Helpers\ImageHelper::exists($this->photo_path);
    }
}
