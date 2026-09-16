<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OpportunityApplication extends Model
{
    use HasFactory;

    protected $fillable = [
        'opportunity_id',
        'member_id',
        'application_number',
        'cover_letter',
        'application_data',
        'resume_path',
        'supporting_documents',
        'status',
        'admin_feedback',
        'reviewed_by',
        'reviewed_at',
    ];

    protected $casts = [
        'application_data' => 'array',
        'supporting_documents' => 'array',
        'reviewed_at' => 'datetime',
    ];

    public function opportunity()
    {
        return $this->belongsTo(Opportunity::class);
    }

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public static function generateApplicationNumber(): string
    {
        $year = date('Y');
        $count = self::whereYear('created_at', $year)->count() + 1;
        return sprintf('APP-OPP-%s-%05d', $year, $count);
    }
}
