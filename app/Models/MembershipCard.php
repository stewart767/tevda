<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MembershipCard extends Model
{
    use HasFactory;

    protected $fillable = [
        'member_id',
        'template_id',
        'card_number',
        'issue_date',
        'expiry_date',
        'qr_code_path',
        'card_data',
        'is_active',
    ];

    protected $casts = [
        'issue_date' => 'date',
        'expiry_date' => 'date',
        'card_data' => 'array',
        'is_active' => 'boolean',
    ];

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function template()
    {
        return $this->belongsTo(IdCardTemplate::class, 'template_id');
    }
}
