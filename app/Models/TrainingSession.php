<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrainingSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id',
        'session_code',
        'trainer_name',
        'location',
        'venue',
        'start_date',
        'end_date',
        'start_time',
        'end_time',
        'capacity',
        'fee_amount',
        'status',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'fee_amount' => 'decimal:2',
    ];

    public function course()
    {
        return $this->belongsTo(TrainingCourse::class, 'course_id');
    }

    public function enrolments()
    {
        return $this->hasMany(TrainingEnrolment::class, 'session_id');
    }

    public function attendances()
    {
        return $this->hasMany(TrainingAttendance::class, 'session_id');
    }

    public function assessment()
    {
        return $this->hasOne(TrainingAssessment::class, 'session_id');
    }

    public function getAvailableSeatsAttribute(): int
    {
        return max(0, $this->capacity - $this->enrolments()->whereIn('status', ['registered', 'confirmed', 'attended', 'completed'])->count());
    }

    public static function generateSessionCode(string $prefix = 'TRN'): string
    {
        $count = self::count() + 1;
        return sprintf('%s-%s-%04d', $prefix, date('Y'), $count);
    }
}
