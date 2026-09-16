<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fee extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'fee_type',
        'category_id',
        'amount',
        'currency',
        'description',
        'is_active',
        'is_configurable',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'is_active' => 'boolean',
        'is_configurable' => 'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(MembershipCategory::class, 'category_id');
    }
}
