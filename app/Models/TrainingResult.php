<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrainingResult extends Model
{
    use HasFactory;

    protected $fillable = [
        'enrolment_id',
        'assessment_id',
        'member_id',
        'score',
        'percentage',
        'status',
        'evaluated_by',
        'certificate_id',
        'evaluated_at',
    ];

    protected $casts = [
        'score' => 'decimal:2',
        'percentage' => 'decimal:2',
        'evaluated_at' => 'datetime',
    ];

    public function enrolment()
    {
        return $this->belongsTo(TrainingEnrolment::class, 'enrolment_id');
    }

    public function assessment()
    {
        return $this->belongsTo(TrainingAssessment::class, 'assessment_id');
    }

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function evaluator()
    {
        return $this->belongsTo(User::class, 'evaluated_by');
    }

    public function certificate()
    {
        return $this->belongsTo(Certificate::class);
    }
}
