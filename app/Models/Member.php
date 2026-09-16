<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Member extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'category_id',
        'region_id',
        'district_id',
        'ward_id',
        'branch_id',
        'membership_number',
        'full_name',
        'date_of_birth',
        'gender',
        'phone',
        'email',
        'physical_address',
        'nida_number',
        'driving_licence_number',
        'licence_class',
        'licence_expiry_date',
        'occupation',
        'ev_sector',
        'employer',
        'ev_experience_years',
        'tin_number',
        'passport_photo_path',
        'status',
        'reviewer_notes',
        'rejection_reason',
        'reviewed_by',
        'reviewed_at',
        'approved_by',
        'approved_at',
        'expiry_date',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'licence_expiry_date' => 'date',
        'reviewed_at' => 'datetime',
        'approved_at' => 'datetime',
        'expiry_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(MembershipCategory::class, 'category_id');
    }

    public function region()
    {
        return $this->belongsTo(Region::class);
    }

    public function district()
    {
        return $this->belongsTo(District::class);
    }

    public function ward()
    {
        return $this->belongsTo(Ward::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function documents()
    {
        return $this->hasMany(MembershipDocument::class);
    }

    public function card()
    {
        return $this->hasOne(MembershipCard::class)->where('is_active', true);
    }

    public function cards()
    {
        return $this->hasMany(MembershipCard::class);
    }

    public function vehicles()
    {
        return $this->hasMany(Vehicle::class);
    }

    public function primaryVehicle()
    {
        return $this->hasOne(Vehicle::class)->latestOfMany();
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function certificates()
    {
        return $this->hasMany(Certificate::class);
    }

    public function membershipCertificate()
    {
        return $this->hasOne(Certificate::class)->where('certificate_type', 'membership')->where('status', 'valid')->latestOfMany();
    }

    public function enrolments()
    {
        return $this->hasMany(TrainingEnrolment::class);
    }

    public function opportunityApplications()
    {
        return $this->hasMany(OpportunityApplication::class);
    }

    public function projectApplications()
    {
        return $this->hasMany(ProjectApplication::class);
    }

    public function isActive(): bool
    {
        return $this->status === 'approved';
    }

    public function isPending(): bool
    {
        return in_array($this->status, ['submitted', 'payment_pending', 'payment_confirmed', 'under_review']);
    }

    public static function generateMembershipNumber(): string
    {
        $year = date('Y');
        $count = self::whereNotNull('membership_number')->count() + 1;
        return sprintf('TEVDA-%s-%05d', $year, $count);
    }
}
