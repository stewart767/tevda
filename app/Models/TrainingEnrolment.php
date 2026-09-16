<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrainingEnrolment extends Model
{
    use HasFactory;

    protected $fillable = [
        'session_id',
        'member_id',
        'invoice_id',
        'status',
        'registered_at',
    ];

    protected $casts = [
        'registered_at' => 'datetime',
    ];

    public function session()
    {
        return $this->belongsTo(TrainingSession::class, 'session_id');
    }

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public function attendances()
    {
        return $this->hasMany(TrainingAttendance::class, 'enrolment_id');
    }

    public function result()
    {
        return $this->hasOne(TrainingResult::class, 'enrolment_id');
    }
}
