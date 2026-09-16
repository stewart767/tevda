<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrainingCourse extends Model
{
    use HasFactory;

    protected $fillable = [
        'programme_id',
        'title',
        'slug',
        'description',
        'duration_hours',
        'pass_mark_percentage',
        'certificate_template_id',
        'fee_amount',
        'is_active',
    ];

    protected $casts = [
        'pass_mark_percentage' => 'decimal:2',
        'fee_amount' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function programme()
    {
        return $this->belongsTo(TrainingProgramme::class, 'programme_id');
    }

    public function modules()
    {
        return $this->hasMany(TrainingModule::class, 'course_id')->orderBy('order_number');
    }

    public function sessions()
    {
        return $this->hasMany(TrainingSession::class, 'course_id');
    }

    public function template()
    {
        return $this->belongsTo(CertificateTemplate::class, 'certificate_template_id');
    }

    public function assessments()
    {
        return $this->hasMany(TrainingAssessment::class, 'course_id');
    }
}
