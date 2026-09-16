<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use App\Models\CertificateTemplate;
use App\Models\Member;
use App\Models\AuditLog;
use App\Services\PdfService;
use App\Services\QrCodeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CertificateEngineController extends Controller
{
    public function index(Request $request)
    {
        $query = Certificate::with(['member.category', 'template']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('certificate_number', 'like', "%{$search}%")
                  ->orWhere('recipient_name', 'like', "%{$search}%")
                  ->orWhere('title', 'like', "%{$search}%");
            });
        }

        if ($request->filled('type')) {
            $query->where('certificate_type', $request->type);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $certificates = $query->latest('issue_date')->paginate(15);
        $templates = CertificateTemplate::all();

        return view('admin.certificates.index', compact('certificates', 'templates'));
    }

    public function show(int $id)
    {
        $certificate = Certificate::with(['member', 'template', 'verifications', 'revoker'])->findOrFail($id);
        $verifyUrl = url('/verify/certificate/' . $certificate->certificate_number);
        $qrSvg = QrCodeService::svg($verifyUrl, 180);

        return view('admin.certificates.show', compact('certificate', 'verifyUrl', 'qrSvg'));
    }

    public function downloadPdf(int $id)
    {
        $certificate = Certificate::findOrFail($id);
        $pdf = PdfService::generateCertificatePdf($certificate);
        return $pdf->download($certificate->certificate_number . '.pdf');
    }

    public function revoke(Request $request, int $id)
    {
        $request->validate([
            'reason' => 'required|string|max:1000',
        ]);

        $certificate = Certificate::findOrFail($id);
        $oldStatus = $certificate->status;

        $certificate->update([
            'status' => 'revoked',
            'revocation_reason' => $request->reason,
            'revoked_by' => Auth::id(),
            'revoked_at' => now(),
        ]);

        AuditLog::log('revoked_certificate', 'certificate', (string)$certificate->id, ['status' => $oldStatus], ['status' => 'revoked', 'reason' => $request->reason]);

        return back()->with('success', "Certificate {$certificate->certificate_number} has been REVOKED.");
    }

    public function reissue(Request $request, int $id)
    {
        $oldCert = Certificate::findOrFail($id);
        $oldCert->update(['status' => 'replaced', 'revocation_reason' => 'Replaced by newly reissued certificate.']);

        $newCertNumber = Certificate::generateCertificateNumber('REISSUE');
        $verifyUrl = url('/verify/certificate/' . $newCertNumber);

        $newCert = Certificate::create([
            'certificate_number' => $newCertNumber,
            'member_id' => $oldCert->member_id,
            'template_id' => $oldCert->template_id,
            'certificate_type' => $oldCert->certificate_type,
            'title' => $oldCert->title,
            'recipient_name' => $oldCert->recipient_name,
            'course_id' => $oldCert->course_id,
            'course_name' => $oldCert->course_name,
            'grade' => $oldCert->grade,
            'issue_date' => now(),
            'expiry_date' => $oldCert->expiry_date ? now()->addYear() : null,
            'authorized_person_name' => 'Dr. Charles Mwansasu',
            'authorized_person_title' => 'Founding Chairperson',
            'qr_code_path' => $verifyUrl,
            'status' => 'valid',
        ]);

        AuditLog::log('reissued_certificate', 'certificate', (string)$newCert->id, ['old_cert' => $oldCert->certificate_number], ['new_cert' => $newCertNumber]);

        return redirect()->route('admin.certificates.show', $newCert->id)->with('success', "Certificate reissued successfully. New Certificate Number: {$newCertNumber}");
    }

    public function templates()
    {
        $templates = CertificateTemplate::withCount('certificates')->latest()->get();
        return view('admin.certificates.templates', compact('templates'));
    }

    public function createTemplate()
    {
        return view('admin.certificates.create_template');
    }

    public function storeTemplate(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:100|unique:certificate_templates,code',
            'certificate_type' => 'required|in:membership,training_completion,participation,professional_certification,professional_development,recognition_appreciation,other',
            'orientation' => 'required|in:landscape,portrait',
            'background_image' => 'nullable|image|mimes:jpeg,png,jpg|max:10240',
        ]);

        $backgroundPath = null;
        if ($request->hasFile('background_image')) {
            $file = $request->file('background_image');
            $filename = 'cert_template_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $backgroundPath = $file->storeAs('certificate_templates', $filename, 'public');
        }

        $defaultConfig = CertificateTemplate::getDefaultPlaceholdersConfig($validated['orientation']);

        $template = CertificateTemplate::create([
            'name' => $validated['name'],
            'code' => strtoupper($validated['code']),
            'certificate_type' => $validated['certificate_type'],
            'orientation' => $validated['orientation'],
            'background_image_path' => $backgroundPath,
            'placeholders_config' => $defaultConfig,
            'is_active' => true,
        ]);

        AuditLog::log('created_certificate_template', 'certificate_template', (string)$template->id, null, ['name' => $template->name, 'code' => $template->code]);

        return redirect()->route('admin.certificates.templates.edit', $template->id)->with('success', "Template '{$template->name}' created successfully! You can now fine-tune the element positions and QR code placement.");
    }

    public function editTemplate(int $id)
    {
        $template = CertificateTemplate::findOrFail($id);

        if (empty($template->placeholders_config)) {
            $template->placeholders_config = CertificateTemplate::getDefaultPlaceholdersConfig($template->orientation ?? 'landscape');
            $template->save();
        }

        // Generate a sample QR code SVG for live previewing in the visual builder
        $sampleVerifyUrl = url('/verify/certificate/TEVDA-PREVIEW-001');
        $sampleQrSvg = QrCodeService::svg($sampleVerifyUrl, 150);

        return view('admin.certificates.edit_template', compact('template', 'sampleQrSvg'));
    }

    public function updateTemplate(Request $request, int $id)
    {
        $template = CertificateTemplate::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:100|unique:certificate_templates,code,' . $template->id,
            'certificate_type' => 'required|in:membership,training_completion,participation,professional_certification,professional_development,recognition_appreciation,other',
            'orientation' => 'required|in:landscape,portrait',
            'background_image' => 'nullable|image|mimes:jpeg,png,jpg|max:10240',
            'placeholders_config' => 'nullable|string',
            'is_active' => 'nullable|boolean',
        ]);

        $backgroundPath = $template->background_image_path;
        if ($request->hasFile('background_image')) {
            $file = $request->file('background_image');
            $filename = 'cert_template_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $backgroundPath = $file->storeAs('certificate_templates', $filename, 'public');
        }

        $config = $template->placeholders_config;
        if ($request->filled('placeholders_config')) {
            $decoded = json_decode($request->placeholders_config, true);
            if (is_array($decoded)) {
                $config = $decoded;
            }
        }

        $template->update([
            'name' => $validated['name'],
            'code' => strtoupper($validated['code']),
            'certificate_type' => $validated['certificate_type'],
            'orientation' => $validated['orientation'],
            'background_image_path' => $backgroundPath,
            'placeholders_config' => $config,
            'is_active' => $request->boolean('is_active', true),
        ]);

        AuditLog::log('updated_certificate_template', 'certificate_template', (string)$template->id, null, ['name' => $template->name]);

        return redirect()->route('admin.certificates.templates.edit', $template->id)->with('success', "Certificate template layout and positions updated successfully!");
    }

    public function deleteTemplate(int $id)
    {
        $template = CertificateTemplate::withCount('certificates')->findOrFail($id);

        if ($template->certificates_count > 0) {
            return back()->with('error', "Cannot delete template '{$template->name}' because {$template->certificates_count} issued certificate(s) are linked to it.");
        }

        $name = $template->name;
        $template->delete();

        AuditLog::log('deleted_certificate_template', 'certificate_template', (string)$id, null, ['name' => $name]);

        return redirect()->route('admin.certificates.templates')->with('success', "Template '{$name}' deleted successfully.");
    }

    public function previewTemplatePdf(int $id)
    {
        $template = CertificateTemplate::findOrFail($id);
        $pdf = PdfService::generateSampleTemplatePdf($template);

        return $pdf->stream('preview-' . strtolower($template->code) . '.pdf');
    }
}
