<?php

namespace Database\Seeders;

use App\Models\MembershipCategory;
use App\Models\Fee;
use Illuminate\Database\Seeder;

class MembershipCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Full Member',
                'slug' => 'full-member',
                'description' => 'Eligible commercial electric-vehicle drivers operating electric three-wheelers (bajaj), electric motorcycles, electric passenger vehicles, vans, or buses with a valid Tanzanian driving licence.',
                'eligibility_criteria' => 'Must be an active commercial electric vehicle driver or operator with a valid driving licence issued by the relevant Tanzanian authority, valid National Identification (NIDA), and vehicle details.',
                'required_documents' => [
                    ['key' => 'nida', 'label' => 'National ID (NIDA) or NIDA Number Slip', 'required' => true],
                    ['key' => 'driving_licence', 'label' => 'Valid Driving Licence', 'required' => true],
                    ['key' => 'passport_photo', 'label' => 'Passport Size Photograph', 'required' => true],
                    ['key' => 'vehicle_registration', 'label' => 'Vehicle Registration / Proof of Operation', 'required' => false],
                ],
                'registration_fee' => 0.00,
                'annual_fee' => 0.00,
                'fee_status_note' => 'Official fee subject to TEVDA National Council confirmation',
                'is_active' => true,
                'order_number' => 1,
            ],
            [
                'name' => 'Associate Member',
                'slug' => 'associate-member',
                'description' => 'EV technicians, engineers, battery specialists, charging infrastructure specialists, software professionals, researchers, environmental practitioners, and clean-energy advocates.',
                'eligibility_criteria' => 'Professionals and practitioners engaged in the electric vehicle ecosystem, technical maintenance, charging technology, or academic/environmental research supporting clean mobility.',
                'required_documents' => [
                    ['key' => 'nida', 'label' => 'National ID (NIDA)', 'required' => true],
                    ['key' => 'passport_photo', 'label' => 'Passport Size Photograph', 'required' => true],
                    ['key' => 'professional_qualification', 'label' => 'Curriculum Vitae / Professional Credentials', 'required' => false],
                ],
                'registration_fee' => 0.00,
                'annual_fee' => 0.00,
                'fee_status_note' => 'Official fee subject to TEVDA National Council confirmation',
                'is_active' => true,
                'order_number' => 2,
            ],
            [
                'name' => 'Honorary Member',
                'slug' => 'honorary-member',
                'description' => 'Conferred upon persons recognised for exceptional contribution to electric mobility, sustainable transport policy, environmental leadership, or association development.',
                'eligibility_criteria' => 'Nominated and approved by the National Council for distinguished service to clean transport and green mobility in Tanzania.',
                'required_documents' => [
                    ['key' => 'nida', 'label' => 'National ID (NIDA) / Passport', 'required' => false],
                    ['key' => 'passport_photo', 'label' => 'Photograph', 'required' => true],
                ],
                'registration_fee' => 0.00,
                'annual_fee' => 0.00,
                'fee_status_note' => 'Exempt from registration and annual fees by Council resolution',
                'is_active' => true,
                'order_number' => 3,
            ],
            [
                'name' => 'Institutional Member',
                'slug' => 'institutional-member',
                'description' => 'Electric vehicle manufacturers, assemblers, charging network operators, battery swapping companies, technical colleges, research institutions, NGOs, logistics companies, and corporate transport fleets.',
                'eligibility_criteria' => 'Registered corporate bodies, institutions, academic entities, and non-governmental organisations actively participating in Tanzania\'s clean transport value chain.',
                'required_documents' => [
                    ['key' => 'tin', 'label' => 'TIN / Certificate of Incorporation', 'required' => true],
                    ['key' => 'business_licence', 'label' => 'Valid Business Licence / Accreditation', 'required' => true],
                    ['key' => 'contact_person_id', 'label' => 'Designated Contact Person National ID', 'required' => true],
                ],
                'registration_fee' => 0.00,
                'annual_fee' => 0.00,
                'fee_status_note' => 'Official institutional fee subject to TEVDA National Council confirmation',
                'is_active' => true,
                'order_number' => 4,
            ],
        ];

        foreach ($categories as $catData) {
            $category = MembershipCategory::updateOrCreate(['slug' => $catData['slug']], $catData);

            // Create initial configurable fee records
            Fee::updateOrCreate(
                ['category_id' => $category->id, 'fee_type' => 'membership_registration'],
                [
                    'name' => $category->name . ' Registration Fee',
                    'amount' => $catData['registration_fee'],
                    'currency' => 'TZS',
                    'description' => $catData['fee_status_note'],
                    'is_active' => true,
                    'is_configurable' => true,
                ]
            );

            Fee::updateOrCreate(
                ['category_id' => $category->id, 'fee_type' => 'annual_subscription'],
                [
                    'name' => $category->name . ' Annual Subscription',
                    'amount' => $catData['annual_fee'],
                    'currency' => 'TZS',
                    'description' => $catData['fee_status_note'],
                    'is_active' => true,
                    'is_configurable' => true,
                ]
            );
        }
    }
}
