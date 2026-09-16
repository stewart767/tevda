<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrainingAttendance extends Model
{
    use HasFactory;

    protected $table = 'training_attendance';

    protected $fillable = [
        'enrolment_id',
        'session_id',
        'member_id',
        'attendance_date',
        'status',
        'recorded_by',
        'method',
    ];

    protected $casts = [
        'attendance_date' => 'date',
    ];

    public function enrolment()
    {
        return $this->belongsTo(TrainingEnrolment::class, 'enrolment_id');
    }

    public function session()
    {
        return $this->belongsTo(TrainingSession::class, 'session_id');
    }

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function recorder()
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }
}
