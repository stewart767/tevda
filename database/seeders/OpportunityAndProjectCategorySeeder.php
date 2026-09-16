<?php

namespace Database\Seeders;

use App\Models\OpportunityCategory;
use App\Models\ProjectCategory;
use App\Models\Project;
use App\Models\Partner;
use Illuminate\Database\Seeder;

class OpportunityAndProjectCategorySeeder extends Seeder
{
    public function run(): void
    {
        // 1. Opportunity Categories
        $oppCategories = [
            ['name' => 'Grants & Subsidies', 'slug' => 'grants', 'description' => 'Clean energy transition grants, development partner subsidies, and green mobility assistance funds.', 'icon' => 'gift'],
            ['name' => 'Asset Financing & Affordable Loans', 'slug' => 'finance', 'description' => 'Low-interest vehicle financing, lease-to-own arrangements, and battery financing facilities.', 'icon' => 'banknotes'],
            ['name' => 'Employment & Fleet Driver Openings', 'slug' => 'employment', 'description' => 'Verified corporate driver placements, delivery fleet contracts, and institutional transport jobs.', 'icon' => 'briefcase'],
            ['name' => 'Business & Franchise Contracts', 'slug' => 'business', 'description' => 'Commercial passenger routes, logistics delivery tenders, and charging hub franchise operations.', 'icon' => 'building-storefront'],
            ['name' => 'Sponsored Training & Skills Programmes', 'slug' => 'training', 'description' => 'Subsidized technical certifications, safety workshops, and entrepreneurship fellowships.', 'icon' => 'academic-cap'],
            ['name' => 'Procurement & Spare Parts Access', 'slug' => 'procurement', 'description' => 'Bulk purchasing agreements for certified batteries, tyres, spare parts, and workshop tools.', 'icon' => 'truck'],
        ];

        foreach ($oppCategories as $oc) {
            OpportunityCategory::updateOrCreate(['slug' => $oc['slug']], $oc);
        }

        // 2. Project Categories
        $prjCategories = [
            ['name' => 'Commercial Electric Vehicles', 'slug' => 'commercial-ev', 'description' => 'Deployment and lease-to-own programmes for electric three-wheelers (bajaj), motorcycles, and light commercial vehicles.', 'icon' => 'truck'],
            ['name' => 'Battery Swapping Infrastructure', 'slug' => 'battery-swapping', 'description' => 'Expansion of standardized, rapid battery swapping networks across urban corridors and driver hubs.', 'icon' => 'arrow-path'],
            ['name' => 'EV Charging Stations', 'slug' => 'ev-charging', 'description' => 'Public and depot AC/DC fast charging points powered by clean grid and solar integrations.', 'icon' => 'bolt'],
            ['name' => 'Workshops & Spare Parts Logistics', 'slug' => 'workshops-parts', 'description' => 'Accredited technical diagnostic centres, high-voltage maintenance bays, and reliable OEM parts distribution.', 'icon' => 'wrench'],
            ['name' => 'Training & Driver Certification', 'slug' => 'training-certification', 'description' => 'National standard curriculum, simulator labs, and continuous professional development for e-mobility operators.', 'icon' => 'academic-cap'],
            ['name' => 'Renewable Energy & Solar Microgrids', 'slug' => 'renewable-energy', 'description' => 'Direct solar-powered charging stations reducing operating costs and emissions footprint.', 'icon' => 'sun'],
            ['name' => 'Digital Transport Systems & Telematics', 'slug' => 'digital-transport', 'description' => 'Fleet telematics, battery state-of-health tracking, and passenger booking integration.', 'icon' => 'cpu-chip'],
        ];

        foreach ($prjCategories as $pc) {
            ProjectCategory::updateOrCreate(['slug' => $pc['slug']], $pc);
        }

        // 3. 50 Electric Three-Wheeler Programme
        $evCat = ProjectCategory::where('slug', 'commercial-ev')->first();
        if ($evCat) {
            Project::updateOrCreate(
                ['slug' => '50-electric-three-wheeler-programme'],
                [
                    'category_id' => $evCat->id,
                    'title' => '50 Electric Three-Wheeler (Bajaj) Pilot Deployment Programme',
                    'slug' => '50-electric-three-wheeler-programme',
                    'summary' => 'A strategic initiative aimed at deploying 50 commercial electric three-wheelers to verified Tanzanian drivers under structured lease-to-own and charging support.',
                    'description' => 'The 50 Electric Three-Wheeler Programme is a flagship demonstration initiative designed to transition existing commercial tricycle operators to zero-emission electric vehicles. This programme integrates comprehensive technical driver training, battery swapping network access, defensive road safety education, and affordable financing structures.

Please Note: This project is currently in the proposal and stakeholder engagement phase. Official applications will open upon confirmation of funding and asset delivery by TEVDA leadership and financing partners.',
                    'location' => 'Dar es Salaam & Coastal Region',
                    'target_regions' => ['Dar es Salaam', 'Pwani'],
                    'target_beneficiaries_count' => 50,
                    'funding_status' => 'proposal_under_development',
                    'project_status' => 'proposal_under_development',
                    'budget_amount' => null,
                    'currency' => 'TZS',
                    'partner_organisations' => 'Financial institutions, EV assemblers, and Clean Energy Funds (subject to formal partnership agreements)',
                    'is_featured' => true,
                ]
            );
        }

        // 4. Partner categories / initial confirmed structure
        $partners = [
            [
                'name' => 'Government & Transport Regulatory Authorities',
                'slug' => 'government-transport-authorities',
                'category' => 'government_and_authorities',
                'description' => 'Engagement with national transport, energy, and municipal authorities regulating clean commercial mobility.',
                'status' => 'active',
                'is_featured' => true,
                'order_number' => 1,
            ],
            [
                'name' => 'Clean Mobility Financial Institutions & Green Funds',
                'slug' => 'financial-institutions-green-funds',
                'category' => 'financial_institutions',
                'description' => 'Financial partners offering structured asset finance, loan guarantees, and credit facilities for licensed drivers.',
                'status' => 'active',
                'is_featured' => true,
                'order_number' => 2,
            ],
            [
                'name' => 'Electric Vehicle Manufacturers & Assemblers',
                'slug' => 'ev-manufacturers-assemblers',
                'category' => 'ev_companies',
                'description' => 'OEM partners supplying commercial electric tricycles, motorcycles, battery packs, and swapping systems.',
                'status' => 'active',
                'is_featured' => true,
                'order_number' => 3,
            ],
            [
                'name' => 'Technical Colleges & Vocational Research Institutions',
                'slug' => 'technical-colleges-research-institutions',
                'category' => 'colleges_and_research',
                'description' => 'Academic and technical institutions collaborating on driver training curriculum and maintenance accreditation.',
                'status' => 'active',
                'is_featured' => true,
                'order_number' => 4,
            ],
        ];

        foreach ($partners as $pData) {
            Partner::updateOrCreate(['slug' => $pData['slug']], $pData);
        }
    }
}
