<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommitteeMember extends Model
{
    use HasFactory;

    protected $fillable = [
        'committee_id',
        'name',
        'position_in_committee',
        'leader_id',
        'member_id',
        'appointed_date',
        'is_active',
    ];

    protected $casts = [
        'appointed_date' => 'date',
        'is_active' => 'boolean',
    ];

    public function committee()
    {
        return $this->belongsTo(Committee::class);
    }

    public function leader()
    {
        return $this->belongsTo(Leader::class);
    }

    public function member()
    {
        return $this->belongsTo(Member::class);
    }
}
