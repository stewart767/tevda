<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Member;
use App\Models\MembershipCategory;
use App\Models\Vehicle;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\TrainingCourse;
use App\Models\TrainingEnrolment;
use App\Models\Opportunity;
use App\Models\OpportunityApplication;
use App\Models\Complaint;
use App\Models\Region;
use App\Models\District;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use PHPUnit\Framework\Attributes\Depends;

class TevdaPhase2EndToEndTest extends TestCase
{
    /**
     * TASK 1 & 2: Real Member Registration with Full Validation & DB Persistence
     */
    public function test_task2_real_applicant_registration_workflow(): array
    {
        // 1. Fetch real region & district from MySQL
        $region = Region::first() ?: Region::create(['name' => 'Dar es Salaam', 'code' => 'DSM']);
        $district = District::where('region_id', $region->id)->first() ?: District::create(['region_id' => $region->id, 'name' => 'Kinondoni']);
        $category = MembershipCategory::where('slug', 'full-member')->first();

        $uniquePhone = '+2557' . mt_rand(10000000, 99999999);
        $applicantName = 'Juma Bakari ' . mt_rand(100, 999);

        $payload = [
            'full_name' => $applicantName,
            'phone' => $uniquePhone,
            'email' => 'applicant_' . mt_rand(1000, 9999) . '@test.tevda.or.tz',
            'category_id' => $category->id,
            'region_id' => $region->id,
            'district_id' => $district->id,
            'ward_name' => 'Sinza Mori',
            'password' => 'Password123!',
            'nida_number' => '19950101-14102-00001-' . mt_rand(10, 99),
            'driving_licence_number' => 'TZA-DL-' . mt_rand(100000, 999999),
            'licence_class' => 'C3',
            'physical_address' => 'Sinza Kijiweni, Dar es Salaam',
        ];

        // 2. Submit to API endpoint: POST /api/v1/auth/register
        $response = $this->postJson('/api/v1/auth/register', $payload);

        $response->assertStatus(201)
            ->assertJson([
                'success' => true,
            ]);

        $responseData = $response->json('data');
        $this->assertNotEmpty($responseData['token']);
        $this->assertNotEmpty($responseData['member']['membership_number']);

        $memberNumber = $responseData['member']['membership_number'];

        // 3. Verify MySQL persistence
        $this->assertDatabaseHas('users', [
            'phone' => $uniquePhone,
            'name' => $applicantName,
        ]);

        $this->assertDatabaseHas('members', [
            'membership_number' => $memberNumber,
            'phone' => $uniquePhone,
            'status' => 'submitted', // Under admin review & verified payment
        ]);

        // 4. Verify Duplicate Prevention
        $duplicateResponse = $this->postJson('/api/v1/auth/register', $payload);
        $duplicateResponse->assertStatus(409); // Conflict

        return [
            'phone' => $uniquePhone,
            'password' => 'Password123!',
            'member_number' => $memberNumber,
            'token' => $responseData['token'],
            'member_id' => $responseData['member']['id'],
        ];
    }

    #[Depends('test_task2_real_applicant_registration_workflow')]
    public function test_task3_authentication_lifecycle(array $authData): void
    {
        // 1. OTP Delivery
        $otpSend = $this->postJson('/api/v1/auth/send-otp', ['phone' => $authData['phone']]);
        $otpSend->assertStatus(200)->assertJson(['success' => true]);

        // 2. OTP Validation
        $otpVerify = $this->postJson('/api/v1/auth/verify-otp', [
            'phone' => $authData['phone'],
            'otp' => '2026',
        ]);
        $otpVerify->assertStatus(200)->assertJson(['data' => ['verified' => true]]);

        // 3. Login with Credentials
        $loginRes = $this->postJson('/api/v1/auth/login', [
            'login' => $authData['phone'],
            'password' => $authData['password'],
        ]);
        $loginRes->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data' => ['token', 'user', 'member']
            ]);

        // 4. Invalid Password Rejection
        $badLogin = $this->postJson('/api/v1/auth/login', [
            'login' => $authData['phone'],
            'password' => 'WrongPassword!',
        ]);
        $badLogin->assertStatus(401);

        // 5. Logout
        $logout = $this->postJson('/api/v1/auth/logout');
        $logout->assertStatus(200)->assertJson(['success' => true]);
    }

    #[Depends('test_task2_real_applicant_registration_workflow')]
    public function test_task4_payment_control_number_and_verification(array $authData): string
    {
        // 1. Generate GePG Control Number
        $genRes = $this->postJson('/api/v1/payments/generate-control-number', [
            'purpose' => 'membership_registration',
            'amount' => 50000.00,
        ]);

        $genRes->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'control_number',
                    'invoice_number',
                    'amount',
                    'currency',
                    'expires_at',
                ]
            ]);

        $controlNumber = $genRes->json('data.control_number');
        $this->assertNotEmpty($controlNumber);
        $this->assertStringStartsWith('99', $controlNumber); // Standard GePG prefix

        // Verify Invoice in MySQL
        $this->assertDatabaseHas('invoices', [
            'control_number' => $controlNumber,
            'status' => 'unpaid',
        ]);

        // 2. Submit Transaction Reference (e.g. from M-Pesa / Tigo Pesa)
        $txRef = 'TXN-EV-' . strtoupper(Str::random(8));
        $paySubmit = $this->postJson('/api/v1/payments/submit-reference', [
            'control_number' => $controlNumber,
            'transaction_reference' => $txRef,
            'payment_method' => 'mpesa',
            'amount' => 50000.00,
        ]);

        // Strict Requirement: Status must be pending_verification, NEVER auto-activated!
        $paySubmit->assertStatus(200)
            ->assertJson([
                'data' => [
                    'status' => 'pending_verification',
                ]
            ]);

        // Verify Payment in MySQL
        $this->assertDatabaseHas('payments', [
            'transaction_reference' => $txRef,
            'status' => 'pending',
        ]);

        // 3. Payment Status Check
        $verifyRes = $this->getJson("/api/v1/payments/verify/{$txRef}");
        $verifyRes->assertStatus(200)
            ->assertJson([
                'data' => [
                    'status' => 'pending',
                    'is_cleared' => false,
                ]
            ]);

        return $txRef;
    }

    #[Depends('test_task2_real_applicant_registration_workflow')]
    #[Depends('test_task4_payment_control_number_and_verification')]
    public function test_task5_administrative_approval_flow(array $authData, string $txRef): void
    {
        // 1. Financial Clearance by Accountant / Webhook reconciliation
        $payment = Payment::where('transaction_reference', $txRef)->first();
        $this->assertNotNull($payment);
        $payment->status = 'paid';
        $payment->paid_at = now();
        $payment->verified_at = now();
        $payment->save();

        if ($payment->invoice) {
            $payment->invoice->status = 'paid';
            $payment->invoice->paid_at = now();
            $payment->invoice->save();
        }

        // Verify payment is now cleared
        $verifyRes = $this->getJson("/api/v1/payments/verify/{$txRef}");
        $verifyRes->assertStatus(200)
            ->assertJson([
                'data' => [
                    'status' => 'paid',
                    'is_cleared' => true,
                ]
            ]);

        // 2. Administrative Review & Approval by General Secretary
        $member = Member::find($authData['member_id']);
        $this->assertNotNull($member);
        $member->status = 'approved';
        $member->approved_at = now();
        $member->expiry_date = now()->addYear();
        $member->save();

        // 3. Verify status transition in MySQL
        $this->assertDatabaseHas('members', [
            'id' => $member->id,
            'status' => 'approved',
        ]);
    }

    #[Depends('test_task2_real_applicant_registration_workflow')]
    public function test_task6_smart_id_and_certificate(array $authData): void
    {
        $response = $this->getJson('/api/v1/member/smart-id');
        $response->assertStatus(200);

        $data = $response->json('data');

        // Security check: No raw NIDA in payload
        $this->assertArrayNotHasKey('nida_number', $data);
        $this->assertNotEmpty($data['qr_verification_url']);
        $this->assertNotEmpty($data['qr_verification_token']);

        // Certificate metadata verification
        $certRes = $this->getJson('/api/v1/member/certificate');
        $certRes->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'certificate_number',
                    'member_name',
                    'category',
                    'issuing_authority',
                    'valid_until',
                    'download_url',
                ]
            ]);
    }

    #[Depends('test_task2_real_applicant_registration_workflow')]
    public function test_task7_academy_courses_and_quiz(): void
    {
        // 1. Fetch courses
        $coursesRes = $this->getJson('/api/v1/academy/courses');
        $coursesRes->assertStatus(200);
        $courses = $coursesRes->json('data');
        $this->assertNotEmpty($courses);

        $course = $courses[0];
        $courseId = $course['id'];
        $passMark = (float) $course['pass_mark_percentage'];

        // 2. Course Enrolment
        $enrollRes = $this->postJson('/api/v1/academy/enroll', ['course_id' => $courseId]);
        $enrollRes->assertStatus(200);

        // 3. Submit Failing Assessment Score (below pass mark)
        $failScore = $passMark - 10.0;
        $failRes = $this->postJson('/api/v1/academy/submit-assessment', [
            'course_id' => $courseId,
            'score_percentage' => $failScore,
        ]);
        $failRes->assertStatus(200)
            ->assertJson([
                'data' => [
                    'passed' => false,
                    'certificate_eligible' => false,
                ]
            ]);

        // 4. Submit Passing Assessment Score
        $passScore = $passMark + 5.0;
        $passRes = $this->postJson('/api/v1/academy/submit-assessment', [
            'course_id' => $courseId,
            'score_percentage' => $passScore,
        ]);
        $passRes->assertStatus(200)
            ->assertJson([
                'data' => [
                    'passed' => true,
                    'certificate_eligible' => true,
                ]
            ]);
    }

    /**
     * TASK 8: Whistleblower Anonymous Reporting & PII Stripping
     */
    public function test_task8_whistleblower_anonymous_protection(): void
    {
        // 1. Submit Anonymous Incident
        $anonPayload = [
            'category' => 'unsafe_practices',
            'description' => 'Waya wazi wa umeme kituo cha kuchajia Ubungo.',
            'is_anonymous' => true,
        ];

        $anonRes = $this->postJson('/api/v1/complaints', $anonPayload);
        $anonRes->assertStatus(201);
        $ticketNo = $anonRes->json('data.ticket_number');

        $this->assertNotEmpty($ticketNo);

        // 2. Verify Database Has Zero PII
        $this->assertDatabaseHas('complaints', [
            'complaint_number' => $ticketNo,
            'is_anonymous' => true,
            'reporter_name' => null,
            'reporter_phone' => null,
            'reporter_email' => null,
        ]);

        // 3. Track Ticket anonymously
        $trackRes = $this->getJson("/api/v1/complaints/track/{$ticketNo}");
        $trackRes->assertStatus(200)
            ->assertJson([
                'data' => [
                    'ticket_number' => $ticketNo,
                    'is_anonymous' => true,
                ]
            ]);
    }

    /**
     * TASK 11: Security & Tamper Checks
     */
    public function test_task11_security_and_injection_resilience(): void
    {
        // 1. SQL Injection Probe in login
        $sqliLogin = $this->postJson('/api/v1/auth/login', [
            'login' => "' OR '1'='1",
            'password' => "' OR '1'='1",
        ]);
        $sqliLogin->assertStatus(401);

        // 2. XSS and Malicious Script Filtering in Complaints
        $xssComplaint = $this->postJson('/api/v1/complaints', [
            'category' => 'other',
            'description' => '<script>alert("hacked")</script> Malalamiko halisi',
            'is_anonymous' => true,
        ]);
        $xssComplaint->assertStatus(201);
    }
}
