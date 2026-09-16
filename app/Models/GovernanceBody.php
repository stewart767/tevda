<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GovernanceBody extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'level',
        'order_number',
    ];

    public function leaders()
    {
        return $this->hasMany(Leader::class, 'governance_body_id')->orderBy('order_number');
    }
}
