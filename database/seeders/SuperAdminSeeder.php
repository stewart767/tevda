<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    public function run(): void
    {
        $superAdminRole = Role::where('slug', 'super-admin')->first();

        $admin = User::updateOrCreate(
            ['email' => 'admin@tevda.or.tz'],
            [
                'name' => 'TEVDA Super Administrator',
                'phone' => '+255 757 700 401',
                'password' => Hash::make('Password123!'),
                'role_id' => $superAdminRole?->id,
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );
    }
}
