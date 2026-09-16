<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RoleAndPermissionSeeder::class,
            GeographySeeder::class,
            MembershipCategorySeeder::class,
            LeadershipSeeder::class,
            TrainingProgrammeSeeder::class,
            OpportunityAndProjectCategorySeeder::class,
            CertificateTemplateSeeder::class,
            SettingSeeder::class,
            SuperAdminSeeder::class,
        ]);
    }
}
