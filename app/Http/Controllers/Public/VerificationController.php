<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Member;
use App\Models\MembershipCard;
use App\Models\Certificate;
use App\Models\CertificateVerification;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\AuditLog;
use App\Models\NotificationCustom;
use App\Services\DocumentService;
use App\Services\PdfService;
use Illuminate\Http\Request;

class VerificationController extends Controller
{
    /**
     * Public Application & Membership Status Tracking page & lookup.
     * Allows searching by Driving Licence Number, Phone Number, Membership Number, Control Number, or NIDA.
     */
    public function trackApplication(Request $request)
    {
        $searchQuery = trim($request->query('query', $request->query('number', '')));
        $member = null;
        $invoice = null;
        $searchPerformed = false;

        if (!empty($searchQuery)) {
            $searchPerformed = true;
            $member = $this->findMemberByAnyIdentifier($searchQuery);

            if ($member) {
                if ($member->status === 'approved') {
                    $member->ensureCredentialsGenerated();
                    $member->load(['membershipCertificate', 'card']);
                }
                $invoice = $member->invoices()->latest()->first();
            }
        }

        return view('public.track_application', compact('member', 'invoice', 'searchQuery', 'searchPerformed'));
    }

    /**
     * Public download of official Certificate PDF without requiring login.
     */
    public function downloadPublicCertificatePdf(Request $request, string $number)
    {
        $searchQuery = trim($number);

        // 1. Try finding certificate by Certificate Number directly
        $certificate = Certificate::with(['member.category', 'template'])
            ->where('certificate_number', $searchQuery)
            ->where('status', 'valid')
            ->first();

        // 2. If not found by cert number, check if search term is a Membership Number / Identifier of an approved member
        if (!$certificate) {
            $member = $this->findMemberByAnyIdentifier($searchQuery);
            if ($member && $member->status === 'approved') {
                $member->ensureCredentialsGenerated();
                $certificate = $member->membershipCertificate ?? $member->activeCertificate;
            }
        }

        if (!$certificate || $certificate->status !== 'valid') {
            return redirect()->route('verify.certificate', ['number' => $searchQuery])
                ->with('error', 'The requested certificate is either invalid, revoked, or not yet unlocked for this member.');
        }

        // Log public verification / download hit
        CertificateVerification::create([
            'certificate_id' => $certificate->id,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'verified_at' => now(),
        ]);

        $pdf = PdfService::generateCertificatePdf($certificate);

        if ($request->boolean('stream')) {
            return $pdf->stream($certificate->certificate_number . '.pdf');
        }

        return $pdf->download($certificate->certificate_number . '.pdf');
    }

    /**
     * Public download of Digital Membership Card PDF without requiring login.
     */
    public function downloadPublicCardPdf(Request $request, string $number)
    {
        $searchQuery = trim($number);

        // 1. Try finding member by membership number / identifier
        $member = $this->findMemberByAnyIdentifier($searchQuery);

        // 2. If not found, check if searching by Card Number (e.g. CARD-TEVDA-...)
        if (!$member) {
            $card = MembershipCard::with('member')->where('card_number', $searchQuery)->first();
            if ($card && $card->member) {
                $member = $card->member;
            }
        }

        if (!$member || $member->status !== 'approved') {
            return redirect()->route('track.application', ['query' => $searchQuery])
                ->with('error', 'Digital membership card is only available for approved active members.');
        }

        $member->ensureCredentialsGenerated();

        $format = $request->query('format', 'cr80'); // 'cr80' or 'a4'
        $theme = $request->query('theme', 'emerald');

        if ($format === 'a4') {
            $pdf = PdfService::generateMembershipCardA4SheetPdf($member, ['theme' => $theme]);
            $filename = 'TEVDA-Card-Sheet-' . $member->membership_number . '.pdf';
        } else {
            $pdf = PdfService::generateMembershipCardPdf($member, ['theme' => $theme, 'show_back' => true]);
            $filename = 'TEVDA-ID-Card-' . $member->membership_number . '.pdf';
        }

        if ($request->boolean('stream')) {
            return $pdf->stream($filename);
        }

        return $pdf->download($filename);
    }

    /**
     * Public Membership Verification page & lookup.
     */
    public function verifyMembership(Request $request, ?string $number = null)
    {
        $searchQuery = trim($number ?: $request->query('number', $request->query('query', '')));
        $member = null;
        $searchPerformed = false;

        if (!empty($searchQuery)) {
            $searchPerformed = true;
            $member = $this->findMemberByAnyIdentifier($searchQuery);

            if ($member && $member->status === 'approved') {
                $member->ensureCredentialsGenerated();
                $member->load(['membershipCertificate', 'card']);
            }
        }

        return view('public.verify_membership', compact('member', 'searchQuery', 'searchPerformed'));
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

    /**
     * Allow applicants to submit payment proof/reference directly from the public tracking page.
     */
    public function submitPublicPaymentProof(Request $request, int $invoiceId)
    {
        $invoice = Invoice::with('member')->findOrFail($invoiceId);
        $member = $invoice->member;

        $validated = $request->validate([
            'payment_method' => 'required|in:mobile_money,bank_transfer,cash,online_card,other',
            'transaction_reference' => 'required|string|max:100',
            'amount' => 'required|numeric|min:1',
            'proof_file' => 'nullable|file|mimes:pdf,jpg,png,jpeg|max:5120',
        ]);

        $proofPath = null;
        if ($request->hasFile('proof_file')) {
            $doc = DocumentService::storePrivate($request->file('proof_file'), 'payments');
            $proofPath = $doc['file_path'];
        }

        $payment = Payment::create([
            'payment_reference' => Payment::generatePaymentReference(),
            'invoice_id' => $invoice->id,
            'member_id' => $member?->id,
            'amount' => $validated['amount'],
            'currency' => $invoice->currency,
            'payment_method' => $validated['payment_method'],
            'transaction_reference' => $validated['transaction_reference'],
            'status' => 'pending',
            'proof_of_payment_path' => $proofPath,
        ]);

        if ($member && $member->status === 'payment_pending') {
            $member->update(['status' => 'payment_pending']);
        }

        AuditLog::log('submitted_public_payment_proof', 'payment', (string)$payment->id, null, [
            'transaction_reference' => $validated['transaction_reference'],
            'control_number' => $invoice->control_number,
        ]);

        if ($member?->user_id) {
            NotificationCustom::send(
                $member->user_id,
                'Payment Proof Submitted',
                "Payment reference {$payment->transaction_reference} for Control Number {$invoice->control_number} submitted to Secretariat for verification.",
                route('portal.payments'),
                'info'
            );
        }

        $lookupKey = $member?->driving_licence_number ?: ($member?->phone ?: $invoice->control_number);

        return redirect()->route('track.application', ['query' => $lookupKey])
            ->with('success', 'Payment proof submitted successfully! Your transaction reference is now undergoing Secretariat confirmation.');
    }

    /**
     * Helper to find a member by driving licence, phone, membership number, control number, or NIDA.
     */
    protected function findMemberByAnyIdentifier(string $rawQuery): ?Member
    {
        $query = trim($rawQuery);
        $cleanDigits = preg_replace('/[^0-9]/', '', $query);

        // 1. Direct search on Member model
        $member = Member::with(['category', 'region', 'district', 'primaryVehicle', 'documents', 'invoices.payments', 'membershipCertificate', 'card'])
            ->where(function ($q) use ($query, $cleanDigits) {
                $q->where('membership_number', $query)
                  ->orWhere('driving_licence_number', $query)
                  ->orWhere('driving_licence_number', 'like', "%{$query}%")
                  ->orWhere('nida_number', $query)
                  ->orWhere('phone', $query);

                if (!empty($cleanDigits) && strlen($cleanDigits) >= 6) {
                    // Match phone variations (e.g. 0757..., 255757..., +255757...)
                    $suffix = substr($cleanDigits, -9); // last 9 digits (e.g. 757700401)
                    $q->orWhere('phone', 'like', "%{$suffix}%")
                      ->orWhereHas('user', function ($uq) use ($suffix) {
                          $uq->where('phone', 'like', "%{$suffix}%");
                      });
                }
            })
            ->latest()
            ->first();

        if ($member) {
            return $member;
        }

        // 2. Search via Control Number on Invoices table
        if (!empty($cleanDigits) || !empty($query)) {
            $invoice = Invoice::with(['member.category', 'member.region', 'member.district', 'member.primaryVehicle', 'member.documents', 'member.invoices.payments', 'member.membershipCertificate', 'member.card'])
                ->where('control_number', $query)
                ->orWhere('control_number', $cleanDigits)
                ->orWhere('invoice_number', $query)
                ->first();

            if ($invoice && $invoice->member) {
                return $invoice->member;
            }
        }

        return null;
    }
}

