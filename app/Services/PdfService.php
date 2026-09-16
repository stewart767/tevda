<?php

namespace App\Services;

use App\Models\Certificate;
use App\Models\Member;
use App\Models\Invoice;
use App\Models\Receipt;
use App\Models\Setting;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class PdfService
{
    /**
     * Generate PDF for a Certificate.
     */
    public static function generateCertificatePdf(Certificate $certificate)
    {
        $verifyUrl = url('/verify/certificate/' . $certificate->certificate_number);
        $qrCodeUri = QrCodeService::dataUri($verifyUrl, 140);
        $logoDataUri = Setting::getLogoDataUri();
        
        $template = $certificate->template;

        if ($template && ($template->background_image_path || !empty($template->placeholders_config))) {
            $backgroundDataUri = $template->getBackgroundImageDataUri();
            $orientation = $template->orientation ?? 'landscape';

            $pdf = Pdf::loadView('pdf.certificate_custom', [
                'certificate' => $certificate,
                'template' => $template,
                'verifyUrl' => $verifyUrl,
                'qrCodeUri' => $qrCodeUri,
                'backgroundDataUri' => $backgroundDataUri,
                'logoDataUri' => $logoDataUri,
            ])->setPaper('a4', $orientation);

            return $pdf;
        }

        $pdf = Pdf::loadView('pdf.certificate', [
            'certificate' => $certificate,
            'verifyUrl' => $verifyUrl,
            'qrCodeUri' => $qrCodeUri,
            'logoDataUri' => $logoDataUri,
        ])->setPaper('a4', 'landscape');

        return $pdf;
    }

    /**
     * Generate a Sample Preview PDF for a Certificate Template with dummy data.
     */
    public static function generateSampleTemplatePdf(\App\Models\CertificateTemplate $template)
    {
        $sampleNumber = 'TEVDA-SAMPLE-' . date('Y') . '-001';
        $verifyUrl = url('/verify/certificate/' . $sampleNumber);
        $qrCodeUri = QrCodeService::dataUri($verifyUrl, 140);
        $logoDataUri = Setting::getLogoDataUri();
        $backgroundDataUri = $template->getBackgroundImageDataUri();
        $orientation = $template->orientation ?? 'landscape';

        // Create a mock Certificate object with realistic sample data
        $dummyCert = new Certificate([
            'certificate_number' => $sampleNumber,
            'certificate_type' => $template->certificate_type ?? 'membership',
            'title' => $template->name ?? 'Certificate of Achievement',
            'recipient_name' => 'Juma Rashidi Athumani',
            'course_name' => $template->certificate_type === 'membership' ? 'Commercial EV Operator Full Member' : 'Advanced Commercial EV Diagnostic & Safety Operations',
            'grade' => 'Distinction (Grade A)',
            'issue_date' => now(),
            'expiry_date' => now()->addYear(),
            'authorized_person_name' => 'Dr. Charles Mwansasu',
            'authorized_person_title' => 'Founding & National Chairperson',
            'status' => 'valid',
        ]);

        if ($backgroundDataUri || !empty($template->placeholders_config)) {
            $pdf = Pdf::loadView('pdf.certificate_custom', [
                'certificate' => $dummyCert,
                'template' => $template,
                'verifyUrl' => $verifyUrl,
                'qrCodeUri' => $qrCodeUri,
                'backgroundDataUri' => $backgroundDataUri,
                'logoDataUri' => $logoDataUri,
            ])->setPaper('a4', $orientation);
        } else {
            $pdf = Pdf::loadView('pdf.certificate', [
                'certificate' => $dummyCert,
                'verifyUrl' => $verifyUrl,
                'qrCodeUri' => $qrCodeUri,
                'logoDataUri' => $logoDataUri,
            ])->setPaper('a4', $orientation);
        }

        return $pdf;
    }

    /**
     * Generate PDF for a Member ID Card (CR80 standard dual-sided or single-sided).
     */
    public static function generateMembershipCardPdf(Member $member, array $options = [])
    {
        $verifyUrl = url('/verify/membership/' . $member->membership_number);
        $qrCodeUri = QrCodeService::dataUri($verifyUrl, 140);
        $logoDataUri = Setting::getLogoDataUri();

        // Convert member passport photo to base64 Data URI for reliable offline DomPDF embedding
        $photoDataUri = null;
        if ($member->passport_photo_path) {
            $photoPath = Storage::disk('public')->path($member->passport_photo_path);
            if (!file_exists($photoPath)) {
                $photoPath = public_path('storage/' . $member->passport_photo_path);
            }
            if (file_exists($photoPath)) {
                $mime = mime_content_type($photoPath) ?: 'image/jpeg';
                $photoDataUri = 'data:' . $mime . ';base64,' . base64_encode(file_get_contents($photoPath));
            }
        }

        $theme = $options['theme'] ?? 'emerald';
        $showBack = $options['show_back'] ?? true;

        $pdf = Pdf::loadView('pdf.membership_card', [
            'member' => $member,
            'verifyUrl' => $verifyUrl,
            'qrCodeUri' => $qrCodeUri,
            'logoDataUri' => $logoDataUri,
            'photoDataUri' => $photoDataUri,
            'theme' => $theme,
            'showBack' => $showBack,
        ])->setPaper([0, 0, 242.64, 153.07], 'portrait'); // CR80 dimensions in pts: 85.6mm (242.64pt) x 53.98mm (153.07pt)

        return $pdf;
    }

    /**
     * Generate PDF for Member ID Cards formatted on an A4 sheet with cut guides and fold lines.
     * Can receive a single Member or a Collection of Members.
     */
    public static function generateMembershipCardA4SheetPdf($members, array $options = [])
    {
        if ($members instanceof Member) {
            $members = collect([$members]);
        }

        $logoDataUri = Setting::getLogoDataUri();
        $theme = $options['theme'] ?? 'emerald';

        // Prepare member data items with photo and QR code base64 URIs
        $preparedMembers = $members->map(function ($member) {
            $verifyUrl = url('/verify/membership/' . $member->membership_number);
            $qrCodeUri = QrCodeService::dataUri($verifyUrl, 120);
            
            $photoDataUri = null;
            if ($member->passport_photo_path) {
                $photoPath = Storage::disk('public')->path($member->passport_photo_path);
                if (!file_exists($photoPath)) {
                    $photoPath = public_path('storage/' . $member->passport_photo_path);
                }
                if (file_exists($photoPath)) {
                    $mime = mime_content_type($photoPath) ?: 'image/jpeg';
                    $photoDataUri = 'data:' . $mime . ';base64,' . base64_encode(file_get_contents($photoPath));
                }
            }

            return [
                'member' => $member,
                'verifyUrl' => $verifyUrl,
                'qrCodeUri' => $qrCodeUri,
                'photoDataUri' => $photoDataUri,
            ];
        });

        $pdf = Pdf::loadView('pdf.membership_card_a4', [
            'preparedMembers' => $preparedMembers,
            'logoDataUri' => $logoDataUri,
            'theme' => $theme,
        ])->setPaper('a4', 'portrait');

        return $pdf;
    }

    /**
     * Generate PDF for an Invoice.
     */
    public static function generateInvoicePdf(Invoice $invoice)
    {
        $logoDataUri = Setting::getLogoDataUri();

        $pdf = Pdf::loadView('pdf.invoice', [
            'invoice' => $invoice,
            'logoDataUri' => $logoDataUri,
        ])->setPaper('a4', 'portrait');

        return $pdf;
    }

    /**
     * Generate PDF for a Receipt.
     */
    public static function generateReceiptPdf(Receipt $receipt)
    {
        $logoDataUri = Setting::getLogoDataUri();

        $pdf = Pdf::loadView('pdf.receipt', [
            'receipt' => $receipt,
            'logoDataUri' => $logoDataUri,
        ])->setPaper('a4', 'portrait');

        return $pdf;
    }
}
