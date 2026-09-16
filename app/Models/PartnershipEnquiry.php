<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PartnershipEnquiry extends Model
{
    use HasFactory;

    protected $fillable = [
        'organisation_name',
        'category',
        'contact_person',
        'email',
        'phone',
        'collaboration_interests',
        'message',
        'status',
    ];
}
