<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Member;
use App\Models\MembershipCategory;
use App\Models\Region;
use App\Models\Invoice;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class RegistrationValidationTest extends TestCase
{
    use DatabaseTransactions;

    public function test_registration_form_page_renders_cleanly()
    {
        $response = $this->get('/register');
        $response->assertStatus(200);
        $response->assertSee('Jiunge na Umoja wa Madereva (TEVDA)');
        $response->assertSee('Taarifa za Awali');
        $response->assertSee('TZS 15,000');
        $response->assertSee('Endelea na Malipo');
    }

    public function test_registration_requires_mandatory_fields()
    {
        $response = $this->post('/register', []);

        $response->assertSessionHasErrors([
            'category_id',
            'name',
            'email',
            'phone',
            'gender',
            'date_of_birth',
            'region_id',
            'ward_name',
            'physical_address',
            'password',
            'terms',
        ]);
    }

    public function test_registration_validates_email_format()
    {
        $response = $this->post('/register', [
            'email' => 'invalid-email-address',
        ]);

        $response->assertSessionHasErrors(['email']);
    }

    public function test_registration_validates_password_confirmation()
    {
        $category = MembershipCategory::first();
        $region = Region::first();

        $response = $this->post('/register', [
            'category_id' => $category->id,
            'name' => 'Validation Test Driver',
            'email' => 'driver.valtest@tevda.or.tz',
            'phone' => '+255799999991',
            'gender' => 'male',
            'date_of_birth' => '1995-01-01',
            'region_id' => $region->id,
            'ward_name' => 'Sinza',
            'physical_address' => 'Sinza Mori',
            'password' => 'Secret123456',
            'password_confirmation' => 'DifferentSecret123456',
            'terms' => 'on',
        ]);

        $response->assertSessionHasErrors(['password']);
    }

    public function test_registration_prevents_duplicate_email()
    {
        $existing = User::whereNotNull('email')->first();
        $this->assertNotNull($existing);

        $category = MembershipCategory::first();
        $region = Region::first();

        $response = $this->post('/register', [
            'category_id' => $category->id,
            'name' => 'Duplicate Email Tester',
            'email' => $existing->email,
            'phone' => '+255788888881',
            'gender' => 'male',
            'date_of_birth' => '1995-01-01',
            'region_id' => $region->id,
            'ward_name' => 'Sinza',
            'physical_address' => 'Sinza Mori',
            'password' => 'Secret123456',
            'password_confirmation' => 'Secret123456',
            'terms' => 'on',
        ]);

        $response->assertSessionHasErrors(['email']);
    }

    public function test_complete_profile_step_requires_licence_and_passport_photo()
    {
        $unique = uniqid();
        $email = "step3.val.{$unique}@tevda.or.tz";
        $phone = "+2557" . rand(10000000, 99999999);
        $category = MembershipCategory::first();
        $region = Region::first();

        // 1. Register
        $this->post('/register', [
            'category_id' => $category->id,
            'name' => 'Step3 Validation User',
            'email' => $email,
            'phone' => $phone,
            'gender' => 'female',
            'date_of_birth' => '1996-05-12',
            'region_id' => $region->id,
            'ward_name' => 'Mikocheni',
            'physical_address' => 'Mikocheni B',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
            'terms' => 'on',
        ]);

        $user = User::where('email', $email)->first();
        $this->actingAs($user);

        // 2. Pay
        $member = Member::where('user_id', $user->id)->first();
        $invoice = Invoice::where('member_id', $member->id)->first();
        $this->post(route('register.payment.process', $invoice->id), [
            'payment_method' => 'mobile_money',
            'payment_mode' => 'manual_ref',
            'transaction_reference' => 'TESTREF' . rand(1000, 9999),
        ]);

        // 3. Complete profile with empty required inputs
        $response = $this->post(route('register.complete_profile.submit'), [
            'driving_licence_number' => '',
            'vehicle_type' => '',
            'passport_photo' => null,
        ]);

        $response->assertSessionHasErrors([
            'driving_licence_number',
            'vehicle_type',
            'passport_photo',
        ]);
    }
}
