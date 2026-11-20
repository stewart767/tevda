<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Role;
use App\Models\Member;
use App\Models\MembershipCategory;
use App\Models\MembershipDocument;
use App\Models\Region;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class SecurityControlsTest extends TestCase
{
    use DatabaseTransactions;
    /**
     * Test 1: Path Traversal prevention on /storage/{path}
     */
    public function test_path_traversal_blocked_on_storage_route()
    {
        // 1. Direct directory traversal targeting .env
        $res1 = $this->get('/storage/../../../.env');
        $this->assertTrue(in_array($res1->status(), [403, 404]), 'Directory traversal ../../../.env must be blocked with 403 or 404');

        // 2. Traversal targeting system files
        $res2 = $this->get('/storage/../../bootstrap/app.php');
        $this->assertTrue(in_array($res2->status(), [403, 404]), 'Directory traversal targeting bootstrap must be blocked');

        // 3. Traversal with encoded backslash
        try {
            $res3 = $this->get('/storage/..%5C..%5C..%5C.env');
            $this->assertTrue(in_array($res3->status(), [400, 403, 404]), 'Backslash traversal must be blocked');
        } catch (\Symfony\Component\HttpFoundation\Exception\BadRequestException $e) {
            $this->assertTrue(true, 'Symfony immediately rejects malformed backslashes');
        }
    }

    /**
     * Test 2: Unauthenticated Database Setup route is strictly blocked without valid key
     */
    public function test_database_setup_route_strictly_guarded()
    {
        $res = $this->get('/system/setup-database');
        $this->assertEquals(403, $res->status());

        $resInvalidKey = $this->get('/system/setup-database?key=invalid_key_attempt');
        $this->assertEquals(403, $resInvalidKey->status());
    }

    /**
     * Test 3: Confidential member documents cannot be downloaded by unauthenticated guests
     */
    public function test_confidential_documents_require_authentication()
    {
        $res = $this->get('/documents/membership/1');
        // Unauthenticated guests should be redirected to login or receive 401
        $this->assertTrue(in_array($res->status(), [302, 401]));
    }

    /**
     * Test 4: Confidential member documents cannot be downloaded by unauthorized members (IDOR prevention)
     */
    public function test_idor_prevention_on_private_membership_documents()
    {
        $category = MembershipCategory::first();
        $region = Region::first();
        $memberRole = Role::where('slug', 'member')->first();

        // Member A (Owner)
        $userA = User::create([
            'name' => 'Owner Member A',
            'email' => 'owner.a.' . uniqid() . '@tevda.test',
            'phone' => '+2557' . rand(10000000, 99999999),
            'password' => Hash::make('Secret123!'),
            'role_id' => $memberRole?->id,
            'is_active' => true,
        ]);
        $memberA = Member::create([
            'user_id' => $userA->id,
            'category_id' => $category->id,
            'full_name' => $userA->name,
            'phone' => $userA->phone,
            'email' => $userA->email,
            'region_id' => $region->id,
            'status' => 'approved',
        ]);

        // Fake document for Member A
        $doc = MembershipDocument::create([
            'member_id' => $memberA->id,
            'document_type' => 'nida',
            'file_path' => 'private/documents/fake_nida_' . uniqid() . '.pdf',
            'file_name' => 'nida_card.pdf',
            'file_size' => 1024,
            'mime_type' => 'application/pdf',
            'verification_status' => 'verified',
        ]);

        // Member B (Attacker / Different User)
        $userB = User::create([
            'name' => 'Attacker Member B',
            'email' => 'attacker.b.' . uniqid() . '@tevda.test',
            'phone' => '+2557' . rand(10000000, 99999999),
            'password' => Hash::make('Secret123!'),
            'role_id' => $memberRole?->id,
            'is_active' => true,
        ]);

        $this->actingAs($userB);
        $res = $this->get('/documents/membership/' . $doc->id);
        $this->assertEquals(403, $res->status(), 'Non-owner member must be denied access with 403 Forbidden');
    }

    /**
     * Test 5: SQL Injection resistance in public tracking and verification inputs
     */
    public function test_sql_injection_resilience_in_public_search()
    {
        $payloads = [
            "' OR '1'='1",
            "1; DROP TABLE users; --",
            "' UNION SELECT 1,2,3,4,5,6,7,8,9,10 --",
            "admin' --",
        ];

        foreach ($payloads as $payload) {
            $trackRes = $this->get(route('track.application', ['query' => $payload]));
            $this->assertEquals(200, $trackRes->status());

            $verifyMemberRes = $this->get(route('verify.membership', ['number' => $payload]));
            $this->assertEquals(200, $verifyMemberRes->status());

            $verifyCertRes = $this->get(route('verify.certificate', ['number' => $payload]));
            $this->assertEquals(200, $verifyCertRes->status());
        }
    }
}
