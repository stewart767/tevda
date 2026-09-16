<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    use HasFactory;

    protected $fillable = ['region_id', 'name', 'is_active'];

    protected $casts = ['is_active' => 'boolean'];

    public function region()
    {
        return $this->belongsTo(Region::class);
    }

    public function councils()
    {
        return $this->hasMany(Council::class);
    }
}
