<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectApplication extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'member_id',
        'application_number',
        'statement_of_need',
        'preferred_vehicle_type',
        'operating_zone_or_route',
        'supporting_documents',
        'status',
        'reviewer_notes',
        'reviewed_by',
        'reviewed_at',
    ];

    protected $casts = [
        'supporting_documents' => 'array',
        'reviewed_at' => 'datetime',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function beneficiary()
    {
        return $this->hasOne(Beneficiary::class, 'application_id');
    }

    public static function generateApplicationNumber(): string
    {
        $year = date('Y');
        $count = self::whereYear('created_at', $year)->count() + 1;
        return sprintf('APP-PRJ-%s-%05d', $year, $count);
    }
}
