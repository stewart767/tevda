<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'full_name',
        'phone',
        'email',
        'region',
        'district',
        'reason',
        'message',
        'consent_given',
        'status',
        'reply_notes',
        'handled_by',
        'handled_at',
    ];

    protected $casts = [
        'consent_given' => 'boolean',
        'handled_at' => 'datetime',
    ];

    public function handler()
    {
        return $this->belongsTo(User::class, 'handled_by');
    }
}
