<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Beneficiary extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'member_id',
        'application_id',
        'beneficiary_code',
        'asset_type',
        'asset_registration_or_serial',
        'allocation_date',
        'training_completed',
        'status',
        'monitoring_notes',
    ];

    protected $casts = [
        'allocation_date' => 'date',
        'training_completed' => 'boolean',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function application()
    {
        return $this->belongsTo(ProjectApplication::class, 'application_id');
    }

    public static function generateBeneficiaryCode(string $prefix = 'BEN'): string
    {
        $year = date('Y');
        $count = self::whereYear('created_at', $year)->count() + 1;
        return sprintf('%s-TEVDA-%s-%04d', $prefix, $year, $count);
    }
}
