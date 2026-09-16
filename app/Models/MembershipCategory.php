<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MembershipCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'eligibility_criteria',
        'required_documents',
        'registration_fee',
        'annual_fee',
        'fee_status_note',
        'is_active',
        'order_number',
    ];

    protected $casts = [
        'required_documents' => 'array',
        'registration_fee' => 'decimal:2',
        'annual_fee' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function members()
    {
        return $this->hasMany(Member::class, 'category_id');
    }
}
