<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Complaint extends Model
{
    use HasFactory;

    protected $fillable = [
        'complaint_number',
        'category',
        'description',
        'evidence_file_path',
        'reporter_name',
        'reporter_phone',
        'reporter_email',
        'is_anonymous',
        'priority',
        'assigned_to',
        'status',
        'internal_notes',
        'resolution_summary',
        'closed_at',
    ];

    protected $casts = [
        'is_anonymous' => 'boolean',
        'closed_at' => 'datetime',
    ];

    public function assignedOfficer()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public static function generateComplaintNumber(): string
    {
        $year = date('Y');
        $count = self::whereYear('created_at', $year)->count() + 1;
        return sprintf('TEVDA-CMP-%s-%04d', $year, $count);
    }
}
