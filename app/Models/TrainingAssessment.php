<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrainingAssessment extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id',
        'session_id',
        'title',
        'total_marks',
        'passing_marks',
        'questions',
        'is_active',
    ];

    protected $casts = [
        'total_marks' => 'decimal:2',
        'passing_marks' => 'decimal:2',
        'questions' => 'array',
        'is_active' => 'boolean',
    ];

    public function course()
    {
        return $this->belongsTo(TrainingCourse::class, 'course_id');
    }

    public function session()
    {
        return $this->belongsTo(TrainingSession::class, 'session_id');
    }

    public function results()
    {
        return $this->hasMany(TrainingResult::class, 'assessment_id');
    }
}
