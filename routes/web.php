<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Public\HomeController;
use App\Http\Controllers\Public\VerificationController;
use App\Http\Controllers\Member\PortalController;
use App\Http\Controllers\Member\ApplicationController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\MemberManagementController;
use App\Http\Controllers\Admin\TrainingManagementController;
use App\Http\Controllers\Admin\CertificateEngineController;
use App\Http\Controllers\Admin\IdCardAdminController;
use App\Http\Controllers\Admin\IdCardTemplateController;
use App\Http\Controllers\Admin\OpportunityAdminController;
use App\Http\Controllers\Admin\ProjectAdminController;
use App\Http\Controllers\Admin\FinanceAdminController;
use App\Http\Controllers\Admin\GovernanceAdminController;
use App\Http\Controllers\Admin\CmsAdminController;
use App\Http\Controllers\Admin\SliderAdminController;
use App\Http\Controllers\Admin\AuditReportController;
use App\Http\Controllers\LocaleController;
use App\Http\Controllers\DocumentDownloadController;

/*
|--------------------------------------------------------------------------
| 1. PUBLIC WEBSITE ROUTES & LOCALIZATION
|--------------------------------------------------------------------------
*/
Route::get('/lang/{locale}', [LocaleController::class, 'switch'])->name('locale.switch');
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('/leadership', [HomeController::class, 'leadership'])->name('leadership');
Route::get('/membership-info', [HomeController::class, 'membershipInfo'])->name('membership.info');
Route::get('/programmes', [HomeController::class, 'programmes'])->name('programmes');
Route::get('/projects', [HomeController::class, 'projects'])->name('projects');
Route::get('/projects/{slug}', [HomeController::class, 'showProject'])->name('projects.show');
Route::get('/opportunities', [HomeController::class, 'opportunities'])->name('opportunities');
Route::get('/opportunities/{slug}', [HomeController::class, 'showOpportunity'])->name('opportunities.show');
Route::get('/partners', [HomeController::class, 'partners'])->name('partners');
Route::post('/partners/enquiry', [HomeController::class, 'submitPartnerEnquiry'])->name('partners.enquiry.submit');
Route::get('/news', [HomeController::class, 'news'])->name('news');
Route::get('/news/{slug}', [HomeController::class, 'showNews'])->name('news.show');
Route::get('/resources', [HomeController::class, 'resources'])->name('resources');
Route::get('/resources/{slug}/download', [HomeController::class, 'downloadResource'])->name('resources.download');
Route::get('/contact', [HomeController::class, 'contact'])->name('contact');
Route::post('/contact', [HomeController::class, 'submitContact'])->name('contact.submit');
Route::get('/whistleblower', [HomeController::class, 'whistleblower'])->name('whistleblower');
Route::post('/whistleblower', [HomeController::class, 'submitWhistleblower'])->name('whistleblower.submit');
Route::get('/whistleblower/track', [HomeController::class, 'trackWhistleblower'])->name('whistleblower.track');

// Legal & SEO
Route::get('/privacy-policy', [HomeController::class, 'privacy'])->name('privacy');
Route::get('/terms-of-use', [HomeController::class, 'terms'])->name('terms');
Route::get('/cookies-policy', [HomeController::class, 'cookies'])->name('cookies');
Route::get('/code-of-conduct', [HomeController::class, 'codeOfConduct'])->name('code_of_conduct');
Route::get('/sitemap.xml', [HomeController::class, 'sitemap'])->name('sitemap');
Route::get('/robots.txt', [HomeController::class, 'robots'])->name('robots');

/*
|--------------------------------------------------------------------------
| 2. PUBLIC VERIFICATION & APPLICATION TRACKING CENTER
|--------------------------------------------------------------------------
*/
Route::get('/track', [VerificationController::class, 'trackApplication'])->name('track.application');
Route::get('/membership/track', [VerificationController::class, 'trackApplication'])->name('membership.track');
Route::post('/track/submit-proof/{invoiceId}', [VerificationController::class, 'submitPublicPaymentProof'])->name('track.submit_proof');
Route::get('/verify/membership/{number?}', [VerificationController::class, 'verifyMembership'])->name('verify.membership');
Route::get('/verify/certificate/{number?}', [VerificationController::class, 'verifyCertificate'])->name('verify.certificate');

// Public Certificate & Digital ID Card Downloads (No Login Required)
Route::get('/download/certificate/{number}', [VerificationController::class, 'downloadPublicCertificatePdf'])->name('public.certificate.download');
Route::get('/download/card/{number}', [VerificationController::class, 'downloadPublicCardPdf'])->name('public.card.download');
Route::get('/verify/certificate/{number}/download', [VerificationController::class, 'downloadPublicCertificatePdf'])->name('public.certificate.download_alias');
Route::get('/verify/membership/{number}/download', [VerificationController::class, 'downloadPublicCardPdf'])->name('public.card.download_alias');

// Fallback Redirects for Direct /public URLs
Route::any('/public', function () {
    return redirect('/', 301);
});
Route::any('/public/{any}', function ($any = '') {
    return redirect('/' . $any, 301);
})->where('any', '.*');

// System Setup & Migration Runner (Protected by key)
Route::get('/system/setup-database', function (\Illuminate\Http\Request $request) {
    if ($request->get('key') !== 'tevda2026') {
        return response("<div style='font-family:sans-serif;padding:30px;color:#dc2626;background:#fef2f2;border:1px solid #fecaca;border-radius:8px;'><h2>Unauthorized</h2><p>Access key is missing or invalid. Use <code>?key=tevda2026</code> to run.</p></div>", 403);
    }
    
    try {
        \Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
        $migrateOutput = \Illuminate\Support\Facades\Artisan::output();
        
        \Illuminate\Support\Facades\Artisan::call('db:seed', ['--force' => true]);
        $seedOutput = \Illuminate\Support\Facades\Artisan::output();
        
        \Illuminate\Support\Facades\Artisan::call('optimize:clear');
        $clearOutput = \Illuminate\Support\Facades\Artisan::output();
        
        return response("
            <div style='font-family:ui-monospace,SFMono-Regular,Menlo,Monaco,Consolas,monospace;background:#0f172a;color:#f8fafc;padding:30px;border-radius:8px;max-width:800px;margin:30px auto;box-shadow:0 10px 25px rgba(0,0,0,0.5);'>
                <h2 style='color:#10b981;margin-top:0;'>&#10004; TEVDA Database Migrations & Seeds Executed Successfully</h2>
                <h4 style='color:#38bdf8;border-bottom:1px solid #334155;padding-bottom:5px;'>1. Migration Output:</h4>
                <pre style='background:#1e293b;padding:15px;border-radius:6px;overflow-x:auto;color:#cbd5e1;font-size:13px;'>" . htmlspecialchars($migrateOutput) . "</pre>
                <h4 style='color:#38bdf8;border-bottom:1px solid #334155;padding-bottom:5px;'>2. Seeder Output:</h4>
                <pre style='background:#1e293b;padding:15px;border-radius:6px;overflow-x:auto;color:#cbd5e1;font-size:13px;'>" . htmlspecialchars($seedOutput) . "</pre>
                <h4 style='color:#38bdf8;border-bottom:1px solid #334155;padding-bottom:5px;'>3. Cache Clear:</h4>
                <pre style='background:#1e293b;padding:15px;border-radius:6px;overflow-x:auto;color:#cbd5e1;font-size:13px;'>" . htmlspecialchars($clearOutput) . "</pre>
                <div style='margin-top:25px;'>
                    <a href='/' style='display:inline-block;background:#0284c7;color:#fff;padding:12px 24px;border-radius:6px;text-decoration:none;font-weight:600;'>Go to TEVDA Homepage &rarr;</a>
                </div>
            </div>
        ");
    } catch (\Throwable $e) {
        return response("
            <div style='font-family:ui-monospace,SFMono-Regular,Menlo,Monaco,Consolas,monospace;background:#0f172a;color:#f8fafc;padding:30px;border-radius:8px;max-width:800px;margin:30px auto;'>
                <h2 style='color:#ef4444;margin-top:0;'>&#10008; Database Migration Failed</h2>
                <p style='color:#fca5a5;'><strong>Error:</strong> " . htmlspecialchars($e->getMessage()) . "</p>
                <pre style='background:#1e293b;padding:15px;border-radius:6px;overflow-x:auto;color:#94a3b8;font-size:12px;'>" . htmlspecialchars($e->getTraceAsString()) . "</pre>
            </div>
        ", 500);
    }
});

/*
|--------------------------------------------------------------------------
| 3. AUTHENTICATION ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.submit');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

/*
|--------------------------------------------------------------------------
| 4. GATED DOCUMENT DOWNLOAD ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/documents/membership/{id}', [DocumentDownloadController::class, 'downloadMembershipDocument'])->name('documents.membership.download');
    Route::get('/documents/complaint/{id}', [DocumentDownloadController::class, 'downloadComplaintEvidence'])->name('documents.complaint.download');
});

/*
|--------------------------------------------------------------------------
| 5. MEMBER PORTAL ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->prefix('portal')->name('portal.')->group(function () {
    Route::get('/', [PortalController::class, 'dashboard'])->name('dashboard');
    Route::get('/profile', [PortalController::class, 'profile'])->name('profile');
    Route::post('/profile', [PortalController::class, 'updateProfile'])->name('profile.update');
    Route::get('/card/download', [PortalController::class, 'downloadCardPdf'])->name('card.download');
    Route::get('/certificate/{id}/download', [PortalController::class, 'downloadCertificatePdf'])->name('certificate.download');
    
    // Training
    Route::get('/training', [PortalController::class, 'training'])->name('training');
    Route::post('/training/{sessionId}/enroll', [PortalController::class, 'enrollTraining'])->name('training.enroll');
    
    // Opportunities
    Route::get('/opportunities', [PortalController::class, 'opportunities'])->name('opportunities');
    Route::post('/opportunities/{opportunityId}/apply', [PortalController::class, 'applyOpportunity'])->name('opportunities.apply');
    
    // Projects
    Route::get('/projects', [PortalController::class, 'projects'])->name('projects');
    Route::post('/projects/{projectId}/apply', [PortalController::class, 'applyProject'])->name('projects.apply');
    
    // Payments
    Route::get('/payments', [PortalController::class, 'payments'])->name('payments');
    Route::post('/payments/{invoiceId}/submit-proof', [PortalController::class, 'submitPaymentProof'])->name('payments.submit_proof');
    
    // Notifications
    Route::get('/notifications/{id}/read', [PortalController::class, 'markNotificationAsRead'])->name('notifications.read');
});

// Member Online Registration
Route::middleware(['auth'])->group(function () {
    Route::get('/membership/apply', [ApplicationController::class, 'showForm'])->name('membership.apply');
    Route::post('/membership/apply', [ApplicationController::class, 'submitApplication'])->name('membership.apply.submit');
});

/*
|--------------------------------------------------------------------------
| 6. ADMIN & STAFF PLATFORM ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'staff'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Membership Management
    Route::get('/members', [MemberManagementController::class, 'index'])->name('members.index');
    Route::get('/members/create', [MemberManagementController::class, 'create'])->name('members.create');
    Route::post('/members', [MemberManagementController::class, 'store'])->name('members.store');
    Route::get('/members/{id}', [MemberManagementController::class, 'show'])->name('members.show');
    Route::get('/members/{id}/edit', [MemberManagementController::class, 'edit'])->name('members.edit');
    Route::put('/members/{id}', [MemberManagementController::class, 'update'])->name('members.update');
    Route::delete('/members/{id}', [MemberManagementController::class, 'destroy'])->name('members.destroy');
    Route::post('/members/{id}/approve', [MemberManagementController::class, 'approve'])->name('members.approve');
    Route::post('/members/{id}/reject', [MemberManagementController::class, 'reject'])->name('members.reject');
    Route::post('/members/{id}/mark-incomplete', [MemberManagementController::class, 'markIncomplete'])->name('members.mark_incomplete');
    Route::post('/members/documents/{documentId}/verify', [MemberManagementController::class, 'verifyDocument'])->name('members.documents.verify');
    Route::get('/members/{id}/card/download', [IdCardAdminController::class, 'downloadMemberCardPdf'])->name('members.card.download');
    Route::post('/members/{id}/card/generate', [IdCardAdminController::class, 'generateForMember'])->name('members.card.generate');

    // ID Card Management & Creator Studio
    Route::get('/cards', [IdCardAdminController::class, 'index'])->name('cards.index');
    Route::get('/cards/create', [IdCardAdminController::class, 'create'])->name('cards.create');
    Route::post('/cards', [IdCardAdminController::class, 'store'])->name('cards.store');
    Route::post('/cards/bulk-generate', [IdCardAdminController::class, 'bulkGenerate'])->name('cards.bulk_generate');
    Route::get('/cards/bulk-download', [IdCardAdminController::class, 'bulkDownloadPdf'])->name('cards.bulk_download');
    Route::get('/cards/templates', [IdCardTemplateController::class, 'index'])->name('cards.templates');
    Route::get('/cards/templates/create', [IdCardTemplateController::class, 'create'])->name('cards.templates.create');
    Route::post('/cards/templates', [IdCardTemplateController::class, 'store'])->name('cards.templates.store');
    Route::get('/cards/templates/{id}/edit', [IdCardTemplateController::class, 'edit'])->name('cards.templates.edit');
    Route::put('/cards/templates/{id}', [IdCardTemplateController::class, 'update'])->name('cards.templates.update');
    Route::delete('/cards/templates/{id}', [IdCardTemplateController::class, 'destroy'])->name('cards.templates.destroy');
    Route::post('/cards/templates/{id}/set-default', [IdCardTemplateController::class, 'setDefault'])->name('cards.templates.set_default');
    Route::get('/cards/templates/{id}/preview-pdf', [IdCardTemplateController::class, 'previewPdf'])->name('cards.templates.preview_pdf');
    Route::get('/cards/{id}', [IdCardAdminController::class, 'show'])->name('cards.show');
    Route::get('/cards/{id}/download', [IdCardAdminController::class, 'downloadPdf'])->name('cards.download');
    Route::get('/cards/{id}/print', [IdCardAdminController::class, 'printView'])->name('cards.print');
    Route::post('/cards/{id}/reissue', [IdCardAdminController::class, 'reissue'])->name('cards.reissue');
    Route::post('/cards/{id}/toggle-status', [IdCardAdminController::class, 'toggleStatus'])->name('cards.toggle_status');

    // Training Management
    Route::get('/training', [TrainingManagementController::class, 'index'])->name('training.index');
    Route::get('/training/sessions/create', [TrainingManagementController::class, 'createSession'])->name('training.session.create');
    Route::post('/training/sessions', [TrainingManagementController::class, 'storeSession'])->name('training.session.store');
    Route::get('/training/sessions/{id}', [TrainingManagementController::class, 'showSession'])->name('training.session.show');
    Route::post('/training/sessions/{id}/attendance', [TrainingManagementController::class, 'recordAttendance'])->name('training.session.attendance');
    Route::post('/training/sessions/{id}/results', [TrainingManagementController::class, 'recordAssessmentResult'])->name('training.session.results');

    // Certificate Engine
    Route::get('/certificates', [CertificateEngineController::class, 'index'])->name('certificates.index');
    Route::get('/certificates/templates', [CertificateEngineController::class, 'templates'])->name('certificates.templates');
    Route::get('/certificates/templates/create', [CertificateEngineController::class, 'createTemplate'])->name('certificates.templates.create');
    Route::post('/certificates/templates', [CertificateEngineController::class, 'storeTemplate'])->name('certificates.templates.store');
    Route::get('/certificates/templates/{id}/edit', [CertificateEngineController::class, 'editTemplate'])->name('certificates.templates.edit');
    Route::put('/certificates/templates/{id}', [CertificateEngineController::class, 'updateTemplate'])->name('certificates.templates.update');
    Route::delete('/certificates/templates/{id}', [CertificateEngineController::class, 'deleteTemplate'])->name('certificates.templates.destroy');
    Route::get('/certificates/templates/{id}/preview-pdf', [CertificateEngineController::class, 'previewTemplatePdf'])->name('certificates.templates.preview_pdf');
    Route::get('/certificates/{id}', [CertificateEngineController::class, 'show'])->name('certificates.show');
    Route::get('/certificates/{id}/download', [CertificateEngineController::class, 'downloadPdf'])->name('certificates.download');
    Route::post('/certificates/{id}/revoke', [CertificateEngineController::class, 'revoke'])->name('certificates.revoke');
    Route::post('/certificates/{id}/reissue', [CertificateEngineController::class, 'reissue'])->name('certificates.reissue');

    // Opportunities
    Route::get('/opportunities', [OpportunityAdminController::class, 'index'])->name('opportunities.index');
    Route::get('/opportunities/create', [OpportunityAdminController::class, 'create'])->name('opportunities.create');
    Route::post('/opportunities', [OpportunityAdminController::class, 'store'])->name('opportunities.store');
    Route::get('/opportunities/{id}', [OpportunityAdminController::class, 'show'])->name('opportunities.show');
    Route::post('/opportunities/applications/{appId}/status', [OpportunityAdminController::class, 'updateApplicationStatus'])->name('opportunities.applications.status');

    // Projects & Beneficiaries
    Route::get('/projects', [ProjectAdminController::class, 'index'])->name('projects.index');
    Route::get('/projects/create', [ProjectAdminController::class, 'create'])->name('projects.create');
    Route::post('/projects', [ProjectAdminController::class, 'store'])->name('projects.store');
    Route::get('/projects/{id}', [ProjectAdminController::class, 'show'])->name('projects.show');
    Route::post('/projects/{id}/status', [ProjectAdminController::class, 'updateStatus'])->name('projects.status');
    Route::post('/projects/applications/{appId}/shortlist', [ProjectAdminController::class, 'shortlistApplication'])->name('projects.applications.shortlist');
    Route::post('/projects/applications/{appId}/allocate', [ProjectAdminController::class, 'allocateBeneficiary'])->name('projects.applications.allocate');

    // Finance & Fees
    Route::get('/finance', [FinanceAdminController::class, 'index'])->name('finance.index');
    Route::get('/finance/invoices', [FinanceAdminController::class, 'invoices'])->name('finance.invoices');
    Route::post('/finance/payments/{id}/verify', [FinanceAdminController::class, 'verifyPayment'])->name('finance.payments.verify');
    Route::post('/finance/fees/{id}', [FinanceAdminController::class, 'updateFee'])->name('finance.fees.update');
    Route::get('/finance/receipts/{id}/download', [FinanceAdminController::class, 'downloadReceipt'])->name('finance.receipts.download');

    // Governance & Leadership
    Route::get('/governance', [GovernanceAdminController::class, 'index'])->name('governance.index');
    Route::get('/governance/leaders/create', [GovernanceAdminController::class, 'createLeader'])->name('governance.leaders.create');
    Route::post('/governance/leaders', [GovernanceAdminController::class, 'storeLeader'])->name('governance.leaders.store');
    Route::post('/governance/branches', [GovernanceAdminController::class, 'storeBranch'])->name('governance.branches.store');
    Route::post('/governance/partners', [GovernanceAdminController::class, 'storePartner'])->name('governance.partners.store');

    // CMS & Communication
    Route::get('/cms/sliders', [SliderAdminController::class, 'index'])->name('cms.sliders.index');
    Route::get('/cms/sliders/create', [SliderAdminController::class, 'create'])->name('cms.sliders.create');
    Route::post('/cms/sliders', [SliderAdminController::class, 'store'])->name('cms.sliders.store');
    Route::get('/cms/sliders/{id}/edit', [SliderAdminController::class, 'edit'])->name('cms.sliders.edit');
    Route::put('/cms/sliders/{id}', [SliderAdminController::class, 'update'])->name('cms.sliders.update');
    Route::delete('/cms/sliders/{id}', [SliderAdminController::class, 'destroy'])->name('cms.sliders.destroy');
    Route::post('/cms/sliders/{id}/toggle', [SliderAdminController::class, 'toggleStatus'])->name('cms.sliders.toggle');
    Route::get('/cms/news', [CmsAdminController::class, 'newsIndex'])->name('cms.news.index');
    Route::get('/cms/news/create', [CmsAdminController::class, 'createNews'])->name('cms.news.create');
    Route::post('/cms/news', [CmsAdminController::class, 'storeNews'])->name('cms.news.store');
    Route::get('/cms/resources', [CmsAdminController::class, 'resourcesIndex'])->name('cms.resources.index');
    Route::post('/cms/resources', [CmsAdminController::class, 'storeResource'])->name('cms.resources.store');
    Route::get('/cms/contacts', [CmsAdminController::class, 'contactsIndex'])->name('cms.contacts.index');
    Route::post('/cms/contacts/{id}/status', [CmsAdminController::class, 'updateContactStatus'])->name('cms.contacts.status');
    Route::get('/cms/complaints', [CmsAdminController::class, 'complaintsIndex'])->name('cms.complaints.index');
    Route::get('/cms/complaints/{id}', [CmsAdminController::class, 'showComplaint'])->name('cms.complaints.show');
    Route::post('/cms/complaints/{id}', [CmsAdminController::class, 'updateComplaint'])->name('cms.complaints.update');
    Route::get('/cms/settings', [CmsAdminController::class, 'settingsIndex'])->name('cms.settings.index');
    Route::post('/cms/settings', [CmsAdminController::class, 'updateSettings'])->name('cms.settings.update');

    // Audit Logs & Reports
    Route::get('/reports', [AuditReportController::class, 'reportsIndex'])->name('reports.index');
    Route::get('/audit-logs', [AuditReportController::class, 'auditLogs'])->name('audit.index');
});
