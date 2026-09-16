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
        'is_founding_member',
        'next_of_kin_name',
        'next_of_kin_relationship',
        'next_of_kin_phone',
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
        'is_founding_member' => 'boolean',
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

    public function activeCertificate()
    {
        return $this->hasOne(Certificate::class)->where('status', 'valid')->latestOfMany();
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

    /**
     * Guarantees that an approved member has an active membership card and valid certificate.
     */
    public function ensureCredentialsGenerated(): void
    {
        if ($this->status !== 'approved') {
            return;
        }

        // 1. Membership Number
        if (!$this->membership_number) {
            $this->membership_number = self::generateMembershipNumber();
            if (!$this->approved_at) {
                $this->approved_at = now();
            }
            if (!$this->expiry_date) {
                $this->expiry_date = now()->addYear();
            }
            $this->save();
        }

        // 2. Digital Membership Card
        if (!$this->card()->exists()) {
            $verifyMembershipUrl = url('/verify/membership/' . $this->membership_number);
            MembershipCard::updateOrCreate(
                ['member_id' => $this->id],
                [
                    'card_number' => 'CARD-' . $this->membership_number,
                    'issue_date' => $this->approved_at ?? now(),
                    'expiry_date' => $this->expiry_date ?: now()->addYear(),
                    'qr_code_path' => $verifyMembershipUrl,
                    'card_data' => [
                        'name' => $this->full_name,
                        'category' => $this->category?->name ?? 'Commercial EV Member',
                        'region' => $this->region?->name ?? 'Tanzania',
                        'phone' => $this->phone,
                        'theme' => 'emerald',
                        'motto' => 'SMART DRIVERS SMART MOBILITY',
                    ],
                    'is_active' => true,
                ]
            );
        }

        // 3. Official Certificate of Membership
        if (!$this->membershipCertificate()->exists()) {
            $membershipTemplate = CertificateTemplate::where('certificate_type', 'membership')->first();
            $certNumber = Certificate::generateCertificateNumber('MEM');
            $verifyCertUrl = url('/verify/certificate/' . $certNumber);

            Certificate::updateOrCreate(
                ['member_id' => $this->id, 'certificate_type' => 'membership'],
                [
                    'certificate_number' => $certNumber,
                    'template_id' => $membershipTemplate?->id,
                    'title' => 'Certificate of Membership',
                    'recipient_name' => $this->full_name,
                    'course_name' => $this->category?->name ?? 'Commercial EV Full Member',
                    'issue_date' => $this->approved_at ?? now(),
                    'expiry_date' => $this->expiry_date ?: now()->addYear(),
                    'authorized_person_name' => 'Dr. Charles Mwansasu',
                    'authorized_person_title' => 'Founding Chairperson',
                    'qr_code_path' => $verifyCertUrl,
                    'status' => 'valid',
                ]
            );
        }
    }
}
