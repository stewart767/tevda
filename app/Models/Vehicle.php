<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    use HasFactory;

    protected $fillable = [
        'member_id',
        'vehicle_type',
        'make',
        'model',
        'registration_number',
        'year_of_manufacture',
        'ownership_type',
        'battery_capacity_kwh',
        'charging_type',
        'daily_average_km',
    ];

    protected $casts = [
        'battery_capacity_kwh' => 'decimal:2',
        'daily_average_km' => 'integer',
    ];

    public function member()
    {
        return $this->belongsTo(Member::class);
    }
}
