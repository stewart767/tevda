<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class PasswordResetTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Test 1: User can view forgot password page
     */
    public function test_forgot_password_page_loads()
    {
        $response = $this->get(route('password.request'));
        $response->assertStatus(200);
        $response->assertSee('Reset Your Password');
    }

    /**
     * Test 2: User can request password reset link
     */
    public function test_user_can_request_password_reset_token()
    {
        $role = Role::where('slug', 'member')->first();
        $email = 'reset.test.' . uniqid() . '@tevda.test';
        $user = User::create([
            'name' => 'Password Reset User',
            'email' => $email,
            'phone' => '+2557' . rand(10000000, 99999999),
            'password' => Hash::make('OldPassword123!'),
            'role_id' => $role?->id,
            'is_active' => true,
        ]);

        $response = $this->post(route('password.email'), [
            'email' => $email,
        ]);

        $response->assertSessionHas('status');
        $this->assertDatabaseHas('password_reset_tokens', [
            'email' => $email,
        ]);
    }

    /**
     * Test 3: User can reset password with valid token
     */
    public function test_user_can_reset_password_with_valid_token()
    {
        $role = Role::where('slug', 'member')->first();
        $email = 'reset.test.' . uniqid() . '@tevda.test';
        $user = User::create([
            'name' => 'Password Reset User',
            'email' => $email,
            'phone' => '+2557' . rand(10000000, 99999999),
            'password' => Hash::make('OldPassword123!'),
            'role_id' => $role?->id,
            'is_active' => true,
        ]);

        $plainToken = 'test-token-1234567890';
        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $email],
            [
                'token' => Hash::make($plainToken),
                'created_at' => now(),
            ]
        );

        // View reset form
        $viewRes = $this->get(route('password.reset', ['token' => $plainToken, 'email' => $email]));
        $viewRes->assertStatus(200);
        $viewRes->assertSee('Choose a New Password');

        // Submit new password
        $resetRes = $this->post(route('password.update'), [
            'token' => $plainToken,
            'email' => $email,
            'password' => 'NewSecurePassword123!',
            'password_confirmation' => 'NewSecurePassword123!',
        ]);

        $resetRes->assertRedirect(route('login'));
        $resetRes->assertSessionHas('success');

        // Verify user can log in with new password
        $user->refresh();
        $this->assertTrue(Hash::check('NewSecurePassword123!', $user->password));

        // Token should be removed
        $this->assertDatabaseMissing('password_reset_tokens', ['email' => $email]);
    }
}
