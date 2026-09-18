<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Certificate extends Model
{
    use HasFactory;

    protected $fillable = [
        'certificate_number',
        'member_id',
        'template_id',
        'certificate_type',
        'title',
        'recipient_name',
        'course_id',
        'course_name',
        'grade',
        'issue_date',
        'expiry_date',
        'authorized_person_name',
        'authorized_person_title',
        'signature_image_path',
        'qr_code_path',
        'pdf_path',
        'status',
        'revocation_reason',
        'revoked_by',
        'revoked_at',
    ];

    protected $casts = [
        'issue_date' => 'date',
        'expiry_date' => 'date',
        'revoked_at' => 'datetime',
    ];

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function template()
    {
        return $this->belongsTo(CertificateTemplate::class, 'template_id');
    }

    public function course()
    {
        return $this->belongsTo(TrainingCourse::class, 'course_id');
    }

    public function verifications()
    {
        return $this->hasMany(CertificateVerification::class);
    }

    public function revoker()
    {
        return $this->belongsTo(User::class, 'revoked_by');
    }

    public function isValid(): bool
    {
        return $this->status === 'valid' && (!$this->expiry_date || $this->expiry_date->isFuture());
    }

    /**
     * Resolve base64 data URI for certificate signature (specific signature or system chairman signature).
     */
    public function getSignatureDataUri(): ?string
    {
        if ($this->signature_image_path) {
            $cleanPath = ltrim(str_replace('\\', '/', $this->signature_image_path), '/');
            $diskPath = \Illuminate\Support\Str::startsWith($cleanPath, 'storage/') 
                ? \Illuminate\Support\Str::after($cleanPath, 'storage/') 
                : $cleanPath;

            $path = \Illuminate\Support\Facades\Storage::disk('public')->path($diskPath);
            if (!file_exists($path)) {
                $path = public_path('storage/' . $diskPath);
            }
            if (!file_exists($path)) {
                $path = public_path($cleanPath);
            }

            if (file_exists($path)) {
                $mime = mime_content_type($path) ?: 'image/png';
                return 'data:' . $mime . ';base64,' . base64_encode(file_get_contents($path));
            }
        }

        return Setting::getChairmanSignatureDataUri();
    }

    /**
     * Resolve public URL for certificate signature.
     */
    public function getSignatureUrl(): ?string
    {
        if ($this->signature_image_path) {
            return \App\Helpers\ImageHelper::getUrl($this->signature_image_path);
        }

        return Setting::getChairmanSignatureUrl();
    }

    public static function generateCertificateNumber(string $type = 'CERT'): string
    {
        $year = date('Y');
        $count = self::whereYear('issue_date', $year)->count() + 1;
        return sprintf('TEVDA-%s-%s-%06d', strtoupper($type), $year, $count);
    }
}

