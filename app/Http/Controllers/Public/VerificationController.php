<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Member;
use App\Models\Certificate;
use App\Models\CertificateVerification;
use Illuminate\Http\Request;

class VerificationController extends Controller
{
    /**
     * Public Membership Verification page & lookup.
     */
    public function verifyMembership(Request $request, ?string $number = null)
    {
        $membershipNumber = $number ?: $request->query('number');
        $member = null;
        $searchPerformed = false;

        if ($membershipNumber) {
            $searchPerformed = true;
            $member = Member::with(['category', 'region'])
                ->where('membership_number', trim($membershipNumber))
                ->first();
        }

        return view('public.verify_membership', compact('member', 'membershipNumber', 'searchPerformed'));
    }

    /**
     * Public Certificate Verification page & lookup.
     */
    public function verifyCertificate(Request $request, ?string $number = null)
    {
        $certificateNumber = $number ?: $request->query('number');
        $certificate = null;
        $searchPerformed = false;

        if ($certificateNumber) {
            $searchPerformed = true;
            $certificate = Certificate::with(['member.category', 'course.programme'])
                ->where('certificate_number', trim($certificateNumber))
                ->first();

            if ($certificate) {
                // Log verification hit
                CertificateVerification::create([
                    'certificate_id' => $certificate->id,
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                    'verified_at' => now(),
                ]);
            }
        }

        return view('public.verify_certificate', compact('certificate', 'certificateNumber', 'searchPerformed'));
    }
}
