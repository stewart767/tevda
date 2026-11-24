<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\MembershipCategory;
use App\Models\TrainingCourse;

class ApiV1Test extends TestCase
{
    /**
     * Test dynamic fees endpoint
     */
    public function test_config_fees_returns_official_structure(): void
    {
        $response = $this->getJson('/api/v1/config/fees');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'currency',
                    'fee_policy_note',
                    'categories',
                    'payment_channels',
                ]
            ]);
    }

    /**
     * Test academy courses endpoint
     */
    public function test_academy_courses_returns_active_curriculum(): void
    {
        $response = $this->getJson('/api/v1/academy/courses');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'message',
                'data',
            ]);
    }

    /**
     * Test digital smart ID endpoint does not leak NIDA
     */
    public function test_smart_id_does_not_leak_nida(): void
    {
        $response = $this->getJson('/api/v1/member/smart-id');

        $response->assertStatus(200);
        $data = $response->json('data');

        // Verify NIDA is NOT in smart ID payload
        $this->assertArrayNotHasKey('nida_number', $data);
        $this->assertArrayHasKey('qr_verification_url', $data);
    }

    /**
     * Test anonymous complaint submission completely excludes reporter identity
     */
    public function test_anonymous_complaint_excludes_identity(): void
    {
        $payload = [
            'category' => 'police_harassment',
            'description' => 'Ukaguzi usio rasmi kituo cha Ubungo Maziwa.',
            'is_anonymous' => true,
        ];

        $response = $this->postJson('/api/v1/complaints', $payload);

        $response->assertStatus(201)
            ->assertJson([
                'success' => true,
                'data' => [
                    'is_anonymous' => true,
                ]
            ]);

        $this->assertDatabaseHas('complaints', [
            'category' => 'misconduct',
            'is_anonymous' => true,
            'reporter_name' => null,
            'reporter_phone' => null,
        ]);
    }
}
