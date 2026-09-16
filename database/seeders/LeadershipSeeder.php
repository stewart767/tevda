<?php

namespace Database\Seeders;

use App\Models\GovernanceBody;
use App\Models\Leader;
use Illuminate\Database\Seeder;

class LeadershipSeeder extends Seeder
{
    public function run(): void
    {
        $bodies = [
            ['name' => 'National General Meeting', 'slug' => 'national-general-meeting', 'level' => 'national', 'description' => 'The supreme governing and policy-making organ of the Tanzania Electric Vehicle Drivers Association comprising all eligible members.', 'order_number' => 1],
            ['name' => 'National Council', 'slug' => 'national-council', 'level' => 'national', 'description' => 'The principal policy, oversight, and strategic decision-making council between General Meetings.', 'order_number' => 2],
            ['name' => 'National Executive Committee', 'slug' => 'national-executive-committee', 'level' => 'national', 'description' => 'Executive organ responsible for operational governance, association management, and strategic programme execution.', 'order_number' => 3],
            ['name' => 'National Secretariat', 'slug' => 'national-secretariat', 'level' => 'national', 'description' => 'Administrative body headed by the Secretary General managing day-to-day operations, member services, and partner liaison.', 'order_number' => 4],
            ['name' => 'Standing Committees', 'slug' => 'standing-committees', 'level' => 'national', 'description' => 'Specialized advisory and oversight committees on Training, Safety, Finance, Legal, and Women/Youth Empowerment.', 'order_number' => 5],
            ['name' => 'Zonal & Regional Committees', 'slug' => 'zonal-regional-committees', 'level' => 'regional', 'description' => 'Coordinating bodies across Tanzania\'s administrative zones and regions facilitating grassroots mobility networks.', 'order_number' => 6],
            ['name' => 'District, Council, Ward & Branch Network', 'slug' => 'district-ward-branches', 'level' => 'branch', 'description' => 'Grassroots driver associations and local charging hub branch operations.', 'order_number' => 7],
        ];

        foreach ($bodies as $b) {
            GovernanceBody::updateOrCreate(['slug' => $b['slug']], $b);
        }

        $necBody = GovernanceBody::where('slug', 'national-executive-committee')->first();

        // Confirmed Founding Leadership
        $leaders = [
            [
                'governance_body_id' => $necBody?->id,
                'name' => 'Dr. Charles Mwansasu',
                'position' => 'Founding Chairperson',
                'is_founding_leader' => true,
                'founding_position' => 'Founding Chairperson',
                'photo_path' => 'images/chairman_dr_charles_mwansasu.jpg',
                'biography' => 'Founding leader and visionary advocate for clean transport transformation in Tanzania. Spearheading the organisation\'s strategic vision to empower commercial electric vehicle drivers through formal representation, technical training, and institutional partnerships.',
                'qualifications' => 'Institutional leadership, electric mobility advocacy, and green transport governance.',
                'responsibilities' => 'Strategic oversight, national stakeholder engagement, policy representation, and overall leadership of the Association.',
                'official_office_contact' => 'chairperson@tevda.or.tz',
                'term_period' => 'Founding Term',
                'is_active' => true,
                'order_number' => 1,
            ],
            [
                'governance_body_id' => $necBody?->id,
                'name' => 'Alex John Lupeja',
                'position' => 'Founding Secretary',
                'is_founding_leader' => true,
                'founding_position' => 'Founding Secretary',
                'biography' => 'Founding Secretary dedicated to establishing robust administrative systems, driver mobilisation frameworks, and operational governance for the association across Tanzania.',
                'qualifications' => 'Operations management, driver community coordination, and organizational administration.',
                'responsibilities' => 'Secretariat administration, member correspondence, official records, and executive coordination.',
                'official_office_contact' => 'secretary@tevda.or.tz',
                'term_period' => 'Founding Term',
                'is_active' => true,
                'order_number' => 2,
            ],
            [
                'governance_body_id' => $necBody?->id,
                'name' => 'Sabrina Gulam',
                'position' => 'Founding Treasurer',
                'is_founding_leader' => true,
                'founding_position' => 'Founding Treasurer',
                'biography' => 'Founding Treasurer focused on financial accountability, transparent fund management, and building financial inclusion programmes for EV operators.',
                'qualifications' => 'Financial stewardship, enterprise development, and resource management.',
                'responsibilities' => 'Financial compliance, budgeting oversight, subscription accounting, and financial reporting.',
                'official_office_contact' => 'treasurer@tevda.or.tz',
                'term_period' => 'Founding Term',
                'is_active' => true,
                'order_number' => 3,
            ],
        ];

        foreach ($leaders as $lData) {
            Leader::updateOrCreate(
                ['name' => $lData['name'], 'founding_position' => $lData['founding_position']],
                $lData
            );
        }
    }
}
