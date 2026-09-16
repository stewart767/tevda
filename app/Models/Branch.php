<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    use HasFactory;

    protected $fillable = [
        'region_id',
        'district_id',
        'ward_id',
        'name',
        'code',
        'location_description',
        'contact_person',
        'contact_phone',
        'contact_email',
        'status',
        'established_date',
    ];

    protected $casts = [
        'established_date' => 'date',
    ];

    public function region()
    {
        return $this->belongsTo(Region::class);
    }

    public function district()
    {
        return $this->belongsTo(District::class);
    }

    public function ward()
    {
        return $this->belongsTo(Ward::class);
    }

    public function members()
    {
        return $this->hasMany(Member::class);
    }
}
