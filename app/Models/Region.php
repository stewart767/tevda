<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Region extends Model
{
    use HasFactory;

    protected $fillable = ['zone_id', 'name', 'code', 'is_active'];

    protected $casts = ['is_active' => 'boolean'];

    public function zone()
    {
        return $this->belongsTo(Zone::class);
    }

    public function districts()
    {
        return $this->hasMany(District::class);
    }

    public function branches()
    {
        return $this->hasMany(Branch::class);
    }

    public function members()
    {
        return $this->hasMany(Member::class);
    }
}
