<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Role;
use App\Models\Member;
use App\Models\MembershipCategory;
use App\Models\Region;
use App\Models\Invoice;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class MembershipCategoriesTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Test all 4 active membership categories are operational
     */
    public function test_all_four_membership_categories_can_register()
    {
        $categories = MembershipCategory::where('is_active', true)->get();
        $this->assertCount(4, $categories);

        $region = Region::first();
        $expectedSlugs = ['full-member', 'associate-member', 'honorary-member', 'institutional-member'];

        foreach ($expectedSlugs as $slug) {
            $cat = $categories->firstWhere('slug', $slug);
            $this->assertNotNull($cat, "Category {$slug} must exist in the database");

            $unique = uniqid();
            $email = "cat.{$slug}.{$unique}@tevda.test";
            $phone = "+2557" . rand(10000000, 99999999);

            // Register driver under category
            $res = $this->post('/register', [
                'category_id' => $cat->id,
                'name' => "Member {$slug}",
                'email' => $email,
                'phone' => $phone,
                'gender' => 'male',
                'date_of_birth' => '1991-03-20',
                'region_id' => $region->id,
                'ward_name' => 'Sinza',
                'physical_address' => 'Dar es Salaam',
                'password' => 'Password123!',
                'password_confirmation' => 'Password123!',
                'terms' => 'on',
            ]);

            $this->assertDatabaseHas('users', ['email' => $email]);
            $user = User::where('email', $email)->first();
            $member = Member::where('user_id', $user->id)->first();

            $this->assertEquals($cat->id, $member->category_id);
            $this->assertEquals('payment_pending', $member->status);

            // Check Invoice generated
            $invoice = Invoice::where('member_id', $member->id)->first();
            $this->assertNotNull($invoice);
            $this->assertEquals(15000.00, (float)$invoice->amount);
            $this->assertNotEmpty($invoice->control_number);

            Auth::logout();
        }
    }
}
