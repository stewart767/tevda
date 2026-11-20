<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Role;
use App\Models\Member;
use App\Models\MembershipCategory;
use App\Models\MembershipCard;
use App\Models\Region;
use App\Models\TrainingCourse;
use App\Models\TrainingSession;
use App\Models\TrainingEnrolment;
use App\Models\Certificate;
use App\Models\Project;
use App\Models\ProjectApplication;
use App\Models\Beneficiary;
use App\Models\Complaint;
use App\Models\Invoice;
use App\Models\Payment;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use App\Services\PdfService;
use App\Services\QrCodeService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class TevdaPlatformTest extends TestCase
{
    use DatabaseTransactions;
    /**
     * Test 1: All Public Pages load with 200 OK and correct authority metadata
     */
    public function test_public_pages_load_successfully()
    {
        $publicRoutes = [
            '/',
            '/about',
            '/leadership',
            '/membership-info',
            '/programmes',
            '/projects',
            '/opportunities',
            '/partners',
            '/news',
            '/resources',
            '/contact',
            '/whistleblower',
            '/verify/membership',
            '/verify/certificate',
            '/privacy-policy',
            '/terms-of-use',
            '/cookies-policy',
            '/code-of-conduct',
        ];

        foreach ($publicRoutes as $url) {
            $response = $this->get($url);
            $response->assertStatus(200);
        }

        // Verify official authority metadata on homepage
        $home = $this->get('/');
        $home->assertSee('SMART DRIVERS SMART MOBILITY');
        $home->assertSee('Sinza Mori');
        $home->assertSee('+255 757 700 401');
        $home->assertSee('info@tevda.or.tz');
    }

    /**
     * Test 2: Member Registration & Multi-step Application Flow
     */
    public function test_driver_registration_and_application_submission()
    {
        $unique = uniqid();
        $email = "driver.{$unique}@tevda.or.tz";
        $phone = "+2557" . rand(10000000, 99999999);
        $category = MembershipCategory::first();
        $region = Region::first();

        // 1. Unified Registration Submission
        $response = $this->post('/register', [
            'category_id' => $category->id,
            'name' => 'Juma Bakari Test',
            'email' => $email,
            'phone' => $phone,
            'gender' => 'male',
            'date_of_birth' => '1992-04-10',
            'region_id' => $region->id,
            'ward_name' => 'Sinza',
            'physical_address' => 'Sinza Mori, Block 12, Dar es Salaam',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
            'terms' => 'on',
        ]);

        $this->assertDatabaseHas('users', ['email' => $email]);
        $user = User::where('email', $email)->first();
        $member = Member::where('user_id', $user->id)->first();
        $invoice = Invoice::where('member_id', $member->id)->first();

        $this->assertNotNull($invoice);
        $response->assertRedirect(route('register.payment', $invoice->id));

        // 2. Process Registration Payment
        $this->actingAs($user);
        $payRes = $this->post(route('register.payment.process', $invoice->id), [
            'payment_method' => 'mobile_money',
            'provider' => 'mpesa',
            'phone' => $phone,
            'payment_mode' => 'push_stk',
        ]);

        $payRes->assertRedirect(route('register.complete_profile'));
        $invoice->refresh();
        $this->assertEquals('paid', $invoice->status);

        // 3. Complete Profile Submission
        Storage::fake('public');
        $completeRes = $this->post(route('register.complete_profile.submit'), [
            'driving_licence_number' => 'TZA-DL-' . rand(100000, 999999),
            'licence_class' => 'C',
            'nida_number' => '19920410-12345-00001-' . rand(10, 99),
            'vehicle_type' => 'three_wheeler',
            'vehicle_make' => 'GreenRide',
            'vehicle_model' => 'Trike EV 2025',
            'vehicle_registration' => 'MC ' . rand(100, 999) . ' AKZ',
            'ownership_type' => 'owned',
            'charging_type' => 'battery_swap',
            'passport_photo' => UploadedFile::fake()->image('passport.jpg', 400, 400),
            'declaration' => '1',
        ]);

        $completeRes->assertRedirect(route('portal.dashboard'));
        $member->refresh();
        $this->assertEquals('approved', $member->status);
        $this->assertNotNull($member->membership_number);
        $this->assertNotNull($member->card);
        $this->assertNotNull($member->membershipCertificate);
    }

    /**
     * Test 3: Admin Review, Approval, Digital ID Card & Certificate Generation
     */
    public function test_admin_member_approval_and_credential_generation()
    {
        $superAdmin = User::where('email', 'admin@tevda.or.tz')->first();
        $this->actingAs($superAdmin);

        // Create a test member
        $category = MembershipCategory::first();
        $region = Region::first();
        $memberRole = Role::where('slug', 'member')->first();

        $unique = uniqid();
        $user = User::create([
            'name' => 'Amina Hassan Test',
            'email' => "amina.{$unique}@tevda.or.tz",
            'phone' => "+2557" . rand(10000000, 99999999),
            'password' => Hash::make('Password123!'),
            'role_id' => $memberRole?->id,
            'is_active' => true,
        ]);

        $member = Member::create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'full_name' => $user->name,
            'email' => $user->email,
            'phone' => $user->phone,
            'region_id' => $region->id,
            'status' => 'submitted',
        ]);

        // Approve member
        $approveResponse = $this->post(route('admin.members.approve', $member->id), [
            'notes' => 'Application thoroughly vetted and verified.',
        ]);

        $approveResponse->assertSessionHas('success');
        $member->refresh();

        $this->assertEquals('approved', $member->status);
        $this->assertNotNull($member->membership_number);
        $this->assertStringStartsWith('TEVDA-', $member->membership_number);

        // Verify digital membership card record
        $this->assertDatabaseHas('membership_cards', [
            'member_id' => $member->id,
            'is_active' => 1,
        ]);

        // Verify certificate record
        $this->assertDatabaseHas('certificates', [
            'member_id' => $member->id,
            'certificate_type' => 'membership',
            'status' => 'valid',
        ]);

        // Test Public Verification Center for this member
        $verifyMemberRes = $this->get('/verify/membership/' . $member->membership_number);
        $verifyMemberRes->assertStatus(200);
        $verifyMemberRes->assertSee('Amina Hassan Test');
        $verifyMemberRes->assertSee('VERIFIED AUTHENTIC & ACTIVE MEMBER');

        // Test Certificate Public Verification
        $cert = Certificate::where('member_id', $member->id)->where('certificate_type', 'membership')->first();
        $verifyCertRes = $this->get('/verify/certificate/' . $cert->certificate_number);
        $verifyCertRes->assertStatus(200);
        $verifyCertRes->assertSee('VERIFIED AUTHENTIC OFFICIAL CERTIFICATE');
    }

    /**
     * Test 4: Training Management, Attendance & Assessment Automatic Certification
     */
    public function test_training_session_attendance_grading_and_completion_certificate()
    {
        $superAdmin = User::where('email', 'admin@tevda.or.tz')->first();
        $this->actingAs($superAdmin);

        $course = TrainingCourse::first();
        $category = MembershipCategory::first();
        $region = Region::first();
        $member = Member::first() ?? Member::create([
            'user_id' => $superAdmin->id,
            'category_id' => $category->id,
            'full_name' => 'Training Candidate',
            'phone' => '+255755123456',
            'email' => 'candidate@tevda.test',
            'region_id' => $region->id,
            'status' => 'approved',
        ]);

        // Create a training session with valid status 'upcoming'
        $session = TrainingSession::create([
            'course_id' => $course->id,
            'session_code' => TrainingSession::generateSessionCode(),
            'trainer_name' => 'Eng. Juma Rashid',
            'location' => 'Dar es Salaam',
            'venue' => 'Sinza Mori TEVDA Center',
            'start_date' => now()->toDateString(),
            'capacity' => 25,
            'fee_amount' => 0.00,
            'status' => 'upcoming',
        ]);

        // Enroll member
        $enrolment = TrainingEnrolment::create([
            'session_id' => $session->id,
            'member_id' => $member->id,
            'status' => 'confirmed',
            'enrolment_date' => now(),
        ]);

        // Grade assessment with 88% (Distinction)
        $gradeResponse = $this->post(route('admin.training.session.results', $session->id), [
            'enrolment_id' => $enrolment->id,
            'score' => 88.0,
        ]);

        $gradeResponse->assertSessionHas('success');
        $enrolment->refresh();

        $this->assertEquals('completed', $enrolment->status);

        // Verify automatic training completion certificate
        $this->assertDatabaseHas('certificates', [
            'member_id' => $member->id,
            'certificate_type' => 'training_completion',
            'grade' => 'Distinction',
            'status' => 'valid',
        ]);
    }

    /**
     * Test 5: Project Management & Beneficiary Asset Allocation (50 Electric Three-Wheeler Programme)
     */
    public function test_project_shortlisting_and_beneficiary_asset_allocation()
    {
        $superAdmin = User::where('email', 'admin@tevda.or.tz')->first();
        $this->actingAs($superAdmin);

        $project = Project::first();
        $category = MembershipCategory::first();
        $region = Region::first();
        $memberRole = Role::where('slug', 'member')->first();

        $unique = uniqid();
        $user = User::create([
            'name' => 'Daudi Mussa Test',
            'email' => "daudi.{$unique}@tevda.or.tz",
            'phone' => "+2557" . rand(10000000, 99999999),
            'password' => Hash::make('Password123!'),
            'role_id' => $memberRole?->id,
            'is_active' => true,
        ]);

        $member = Member::create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'full_name' => $user->name,
            'email' => $user->email,
            'phone' => $user->phone,
            'region_id' => $region->id,
            'status' => 'approved',
            'membership_number' => 'TEVDA-2026-' . rand(10000, 99999),
        ]);

        // Submit project application with valid status 'applied'
        $app = ProjectApplication::create([
            'project_id' => $project->id,
            'member_id' => $member->id,
            'application_number' => ProjectApplication::generateApplicationNumber(),
            'status' => 'applied',
            'statement_of_need' => 'Committed commercial EV driver operating in Kinondoni.',
        ]);

        // Shortlist application
        $this->post(route('admin.projects.applications.shortlist', $app->id));
        $app->refresh();
        $this->assertEquals('shortlisted', $app->status);

        // Allocate Beneficiary Asset
        $allocateRes = $this->post(route('admin.projects.applications.allocate', $app->id), [
            'asset_type' => 'Electric Three-Wheeler (Bajaji)',
            'asset_registration_or_serial' => 'MC 450 DRZ',
            'allocation_date' => now()->toDateString(),
            'training_completed' => '1',
        ]);

        $allocateRes->assertSessionHas('success');
        $app->refresh();
        $this->assertEquals('asset_allocated', $app->status);

        $this->assertDatabaseHas('beneficiaries', [
            'project_id' => $project->id,
            'member_id' => $member->id,
            'asset_registration_or_serial' => 'MC 450 DRZ',
            'status' => 'active',
        ]);
    }

    /**
     * Test 6: Whistleblower Grievance Submission & Ticket Tracking
     */
    public function test_whistleblower_submission_and_public_tracking()
    {
        $submitRes = $this->post('/whistleblower', [
            'category' => 'unsafe_practices',
            'description' => 'Observed damaged cabling and lack of safety signage at commercial charging dock.',
            'is_anonymous' => '1',
        ]);

        $submitRes->assertSessionHas('success');
        $submitRes->assertRedirect();

        $complaint = Complaint::latest()->first();
        $this->assertNotNull($complaint);
        $this->assertEquals('unsafe_practices', $complaint->category);
        $this->assertTrue((bool)$complaint->is_anonymous);

        // Public Tracking
        $trackRes = $this->get('/whistleblower/track?ticket=' . $complaint->complaint_number);
        $trackRes->assertStatus(200);
        $trackRes->assertSee($complaint->complaint_number);
        $trackRes->assertSee('Report Ticket Reference');
    }

    /**
     * Test 7: Pure PHP Vector QR Code & PDF Services Rendering
     */
    public function test_vector_qr_code_and_pdf_generation()
    {
        $svg = QrCodeService::svg('https://www.tevda.or.tz/verify/membership/TEVDA-2026-00001', 150);
        $dataUri = QrCodeService::dataUri('https://www.tevda.or.tz', 120);
        $this->assertStringStartsWith('data:image/', $dataUri);

        $cert = Certificate::first();
        if ($cert) {
            $pdf = PdfService::generateCertificatePdf($cert);
            $this->assertNotNull($pdf->output());
        }

        $member = Member::first();
        if ($member) {
            $cardPdf = PdfService::generateMembershipCardPdf($member);
            $this->assertNotNull($cardPdf->output());
        }
    }

    /**
     * Test 8: Admin Direct Member Registration with Instant Approval
     */
    public function test_admin_direct_member_registration()
    {
        $superAdmin = User::where('email', 'admin@tevda.or.tz')->first();
        $category = \App\Models\MembershipCategory::first();
        $region = \App\Models\Region::first();

        $unique = uniqid();
        $response = $this->actingAs($superAdmin)->post(route('admin.members.store'), [
            'name' => 'Rashid Bakari Test',
            'email' => "rashid.{$unique}@example.com",
            'phone' => "+2557" . rand(10000000, 99999999),
            'category_id' => $category->id,
            'region_id' => $region->id,
            'gender' => 'male',
            'physical_address' => 'Morogoro Road Hub',
            'vehicle_type' => 'three_wheeler',
            'vehicle_registration' => 'T 999 ZZZ',
            'initial_status' => 'approved',
        ]);

        $this->assertDatabaseHas('users', ['email' => "rashid.{$unique}@example.com"]);
        $member = Member::where('email', "rashid.{$unique}@example.com")->first();
        $this->assertNotNull($member);
        $this->assertEquals('approved', $member->status);
        $this->assertNotNull($member->membership_number);

        // Check Digital ID Card & Certificate generated
        $this->assertDatabaseHas('membership_cards', ['member_id' => $member->id]);
        $this->assertDatabaseHas('certificates', ['member_id' => $member->id, 'certificate_type' => 'membership']);

        $response->assertRedirect(route('admin.members.show', $member->id));
    }

    /**
     * Test 9: ID Card Creator Studio and Card Management Operations
     */
    public function test_id_card_creator_and_management_studio()
    {
        $superAdmin = User::where('email', 'admin@tevda.or.tz')->first();
        $member = Member::where('status', 'approved')->first();

        if (!$member) {
            $category = \App\Models\MembershipCategory::first();
            $region = \App\Models\Region::first();
            $user = User::factory()->create();
            $member = Member::create([
                'user_id' => $user->id,
                'category_id' => $category->id,
                'region_id' => $region->id,
                'full_name' => 'Card Test User',
                'email' => 'card.test@example.com',
                'phone' => '+255711223344',
                'status' => 'approved',
                'membership_number' => Member::generateMembershipNumber(),
            ]);
        }

        // 1. View ID Cards Index
        $indexRes = $this->actingAs($superAdmin)->get(route('admin.cards.index'));
        $indexRes->assertStatus(200);
        $indexRes->assertSee('Member ID Cards');

        // 2. View ID Card Creator Studio
        $createRes = $this->actingAs($superAdmin)->get(route('admin.cards.create', ['member_id' => $member->id]));
        $createRes->assertStatus(200);
        $createRes->assertSee('ID Card Creator Studio');

        // 3. Issue / Store ID Card
        $uniqueCardNo = 'CARD-TEST-' . rand(1000, 9999);
        $storeRes = $this->actingAs($superAdmin)->post(route('admin.cards.store'), [
            'member_id' => $member->id,
            'card_number' => $uniqueCardNo,
            'issue_date' => now()->format('Y-m-d'),
            'expiry_date' => now()->addYear()->format('Y-m-d'),
            'theme' => 'emerald',
            'motto' => 'SMART MOBILITY FOR ALL',
        ]);

        $card = MembershipCard::where('card_number', $uniqueCardNo)->first();
        $this->assertNotNull($card);
        $this->assertEquals($member->id, $card->member_id);
        $this->assertEquals('SMART MOBILITY FOR ALL', $card->card_data['motto']);
        $storeRes->assertRedirect(route('admin.cards.show', $card->id));

        // 4. View ID Card Show Page
        $showRes = $this->actingAs($superAdmin)->get(route('admin.cards.show', $card->id));
        $showRes->assertStatus(200);
        $showRes->assertSee($uniqueCardNo);

        // 5. Download CR80 PDF
        $cr80Res = $this->actingAs($superAdmin)->get(route('admin.cards.download', ['id' => $card->id, 'format' => 'cr80']));
        $cr80Res->assertStatus(200);
        $this->assertEquals('application/pdf', $cr80Res->headers->get('content-type'));

        // 6. Download A4 Sheet PDF
        $a4Res = $this->actingAs($superAdmin)->get(route('admin.cards.download', ['id' => $card->id, 'format' => 'a4']));
        $a4Res->assertStatus(200);
        $this->assertEquals('application/pdf', $a4Res->headers->get('content-type'));

        // 7. Direct Print View
        $printRes = $this->actingAs($superAdmin)->get(route('admin.cards.print', $card->id));
        $printRes->assertStatus(200);
        $printRes->assertSee($member->full_name);

        // 8. Reissue Card
        $reissueRes = $this->actingAs($superAdmin)->post(route('admin.cards.reissue', $card->id));
        $reissueRes->assertSessionHas('success');

        // 9. Toggle Status
        $toggleRes = $this->actingAs($superAdmin)->post(route('admin.cards.toggle_status', $card->id));
        $toggleRes->assertSessionHas('success');
        $card->refresh();
        $this->assertFalse($card->is_active);

        // Reactivate card to test bulk download
        $this->actingAs($superAdmin)->post(route('admin.cards.toggle_status', $card->id));
        $card->refresh();
        $this->assertTrue($card->is_active);

        // 10. Bulk Download
        $bulkRes = $this->actingAs($superAdmin)->get(route('admin.cards.bulk_download'));
        $bulkRes->assertStatus(200);
    }

    /**
     * Test 10: Approved Member Public Certificate & ID Card Downloads Without Login
     */
    public function test_approved_member_public_downloads_without_login()
    {
        $category = MembershipCategory::first();
        $region = Region::first();
        $memberRole = Role::where('slug', 'member')->first();

        $unique = uniqid();
        $user = User::create([
            'name' => 'Kassim Said Test',
            'email' => "kassim.{$unique}@tevda.or.tz",
            'phone' => "+2557" . rand(10000000, 99999999),
            'password' => Hash::make('Password123!'),
            'role_id' => $memberRole?->id,
            'is_active' => true,
        ]);

        $member = Member::create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'region_id' => $region->id,
            'full_name' => $user->name,
            'email' => $user->email,
            'phone' => $user->phone,
            'driving_licence_number' => 'DL-KASSIM-' . rand(10000, 99999),
            'status' => 'approved',
            'membership_number' => Member::generateMembershipNumber(),
            'approved_at' => now(),
            'expiry_date' => now()->addYear(),
        ]);

        $member->ensureCredentialsGenerated();

        // 1. Download Certificate PDF as Guest (No Login)
        $cert = $member->membershipCertificate;
        $this->assertNotNull($cert);
        $certDownloadRes = $this->get(route('public.certificate.download', $cert->certificate_number));
        $certDownloadRes->assertStatus(200);
        $this->assertEquals('application/pdf', $certDownloadRes->headers->get('content-type'));

        // 2. Download ID Card CR80 PDF as Guest (No Login)
        $cardDownloadRes = $this->get(route('public.card.download', $member->membership_number));
        $cardDownloadRes->assertStatus(200);
        $this->assertEquals('application/pdf', $cardDownloadRes->headers->get('content-type'));

        // 3. Download ID Card A4 Sheet PDF as Guest (No Login)
        $sheetDownloadRes = $this->get(route('public.card.download', ['number' => $member->membership_number, 'format' => 'a4']));
        $sheetDownloadRes->assertStatus(200);
        $this->assertEquals('application/pdf', $sheetDownloadRes->headers->get('content-type'));

        // 4. View Tracking Page as Guest -> Confirm Download Links Present
        $trackRes = $this->get(route('track.application', ['query' => $member->driving_licence_number]));
        $trackRes->assertStatus(200);
        $trackRes->assertSee('Download Certificate (PDF)');
        $trackRes->assertSee('Download ID Card (PDF)');

        // 5. View Public Membership Verification Page -> Confirm Download Links Present
        $verifyMemberRes = $this->get(route('verify.membership', ['number' => $member->membership_number]));
        $verifyMemberRes->assertStatus(200);
        $verifyMemberRes->assertSee('Download ID Card (PDF)');
        $verifyMemberRes->assertSee('Download Certificate (PDF)');

        // 6. View Public Certificate Verification Page -> Confirm Download Link Present
        $verifyCertRes = $this->get(route('verify.certificate', ['number' => $cert->certificate_number]));
        $verifyCertRes->assertStatus(200);
        $verifyCertRes->assertSee('Download Official Certificate (PDF)');
    }
}
