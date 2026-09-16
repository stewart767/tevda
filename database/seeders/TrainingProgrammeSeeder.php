<?php

namespace Database\Seeders;

use App\Models\TrainingProgramme;
use App\Models\TrainingCourse;
use App\Models\TrainingModule;
use Illuminate\Database\Seeder;

class TrainingProgrammeSeeder extends Seeder
{
    public function run(): void
    {
        $programmes = [
            [
                'title' => 'Electric Vehicle Operation & Battery Technology',
                'slug' => 'ev-operation-battery-technology',
                'code' => 'EV-OPS',
                'description' => 'Comprehensive technical training on electric vehicle systems, lithium-ion battery care, optimal range management, AC/DC charging protocols, safe battery swapping procedures, warning indicators, and pre-trip inspections.',
                'objective' => 'Equip commercial drivers with foundational technical mastery to maximize EV efficiency, vehicle lifespan, and operating safety.',
                'icon' => 'battery-charging',
                'order_number' => 1,
                'courses' => [
                    [
                        'title' => 'Core Electric Vehicle Operation & Swapping Mastery',
                        'slug' => 'core-ev-operation-swapping-mastery',
                        'description' => 'Hands-on training covering powertrain operation, regenerative braking, thermal management, battery swap station protocols, and high-voltage safety.',
                        'duration_hours' => 16,
                        'pass_mark_percentage' => 75.00,
                        'fee_amount' => 0.00,
                        'modules' => [
                            ['title' => 'EV Powertrain & High-Voltage Fundamentals', 'description' => 'Understanding motors, controllers, inverters, and battery safety.', 'duration_hours' => 4],
                            ['title' => 'Battery Care, Swapping & Charging Best Practices', 'description' => 'Battery health preservation, swap hub operations, and fast charging.', 'duration_hours' => 4],
                            ['title' => 'Range Optimization & Energy Efficiency', 'description' => 'Eco-driving techniques, regenerative braking, and route planning.', 'duration_hours' => 4],
                            ['title' => 'Pre-Trip Inspection & Fault Diagnosis', 'description' => 'Daily inspection checklist, dashboard warning codes, and diagnostics.', 'duration_hours' => 4],
                        ],
                    ],
                ],
            ],
            [
                'title' => 'Road Safety & Defensive Driving for Electric Fleets',
                'slug' => 'road-safety-defensive-driving',
                'code' => 'RD-SFT',
                'description' => 'In-depth road safety education focusing on Tanzanian traffic laws, pedestrian safety in quiet electric vehicles, weight and passenger limits, defensive driving in urban traffic, and accident prevention.',
                'objective' => 'Achieve zero preventable accidents across commercial electric vehicle fleets through defensive driving and rapid emergency response.',
                'icon' => 'shield-check',
                'order_number' => 2,
                'courses' => [
                    [
                        'title' => 'Defensive Driving & Urban EV Road Safety',
                        'slug' => 'defensive-driving-urban-ev-road-safety',
                        'description' => 'Advanced road awareness, low-noise vehicle pedestrian management, night driving safety, and emergency response.',
                        'duration_hours' => 12,
                        'pass_mark_percentage' => 80.00,
                        'fee_amount' => 0.00,
                        'modules' => [
                            ['title' => 'Tanzanian Traffic Code & Commercial Vehicle Regulations', 'description' => 'Legal obligations, licence compliance, and passenger transit rights.', 'duration_hours' => 3],
                            ['title' => 'Acoustic Awareness & Quiet Vehicle Safety', 'description' => 'Pedestrian anticipation, blind-spot checks, and urban hazard management.', 'duration_hours' => 3],
                            ['title' => 'Defensive Tactics & Weather Hazards', 'description' => 'Wet road braking, speed management, and collision avoidance.', 'duration_hours' => 3],
                            ['title' => 'Emergency Response & Incident Reporting', 'description' => 'First aid, scene securing, post-incident reporting to TEVDA and police.', 'duration_hours' => 3],
                        ],
                    ],
                ],
            ],
            [
                'title' => 'Driver Enterprise & Financial Inclusion Skills',
                'slug' => 'driver-enterprise-financial-skills',
                'code' => 'ENT-FIN',
                'description' => 'Business management and financial literacy tailored for commercial EV operators: daily income/expense tracking, digital mobile money reconciliation, savings schemes, loan servicing, and customer service excellence.',
                'objective' => 'Transform drivers into sustainable micro-entrepreneurs who build savings, maintain creditworthiness, and grow wealth through clean mobility.',
                'icon' => 'chart-bar',
                'order_number' => 3,
                'courses' => [
                    [
                        'title' => 'Financial Literacy & Micro-Fleet Business Planning',
                        'slug' => 'financial-literacy-micro-fleet-business',
                        'description' => 'Cash flow tracking, saving for battery replacements, digital payments, and cooperative loan schemes.',
                        'duration_hours' => 10,
                        'pass_mark_percentage' => 70.00,
                        'fee_amount' => 0.00,
                        'modules' => [
                            ['title' => 'Daily Bookkeeping & Cost-per-Kilometer Calculation', 'description' => 'Comparing EV operating costs vs ICE vehicles and budgeting.', 'duration_hours' => 3],
                            ['title' => 'Digital Payments & Mobile Money Record-Keeping', 'description' => 'M-Pesa/Tigo Pesa integration, digital ticketing, and cashless accounting.', 'duration_hours' => 3],
                            ['title' => 'Customer Care & Professional Ethics', 'description' => 'Passenger relations, conflict de-escalation, and dispute resolution.', 'duration_hours' => 2],
                            ['title' => 'Credit Access & Loan Management for EV Assets', 'description' => 'Asset finance requirements, loan repayment discipline, and grants.', 'duration_hours' => 2],
                        ],
                    ],
                ],
            ],
            [
                'title' => 'Basic Maintenance, Electrical Diagnostics & Warranties',
                'slug' => 'basic-maintenance-electrical-diagnostics',
                'code' => 'MNT-DGN',
                'description' => 'Preventive maintenance skills covering tyre wear, brake pads, low-voltage wiring, suspension checks, vehicle cleaning standards, warranty protection, and accredited garage fault reporting.',
                'objective' => 'Maintain maximum vehicle uptime and safeguard manufacturer warranties through structured preventive maintenance routines.',
                'icon' => 'wrench-screwdriver',
                'order_number' => 4,
                'courses' => [
                    [
                        'title' => 'Preventive Maintenance & Mechanical Inspection',
                        'slug' => 'preventive-maintenance-mechanical-inspection',
                        'description' => 'Essential maintenance routines for 2-wheeler, 3-wheeler, and light commercial electric vehicles.',
                        'duration_hours' => 8,
                        'pass_mark_percentage' => 75.00,
                        'fee_amount' => 0.00,
                        'modules' => [
                            ['title' => 'Braking Systems & Regenerative Interaction', 'description' => 'Brake fluid, pad inspection, and hydraulic maintenance.', 'duration_hours' => 2],
                            ['title' => 'Tyre Pressure, Suspension & Alignment', 'description' => 'Weight distribution, tyre wear patterns, and chassis integrity.', 'duration_hours' => 2],
                            ['title' => 'Electrical Harness & Waterproofing Checks', 'description' => 'Connector sealing, monsoon protection, and auxiliary batteries.', 'duration_hours' => 2],
                            ['title' => 'Warranty Terms & Service Schedule Compliance', 'description' => 'Understanding OEM warranty exclusions and certified logbooks.', 'duration_hours' => 2],
                        ],
                    ],
                ],
            ],
            [
                'title' => 'Digital Transport Tools & Platform Literacy',
                'slug' => 'digital-transport-tools',
                'code' => 'DIG-TLS',
                'description' => 'Training on GPS navigation tools, e-hailing platforms, charging station locator apps, battery swap queue management tools, and digital TEVDA member tools.',
                'objective' => 'Empower drivers with digital tool proficiency for enhanced passenger bookings and seamless charging infrastructure navigation.',
                'icon' => 'device-phone-mobile',
                'order_number' => 5,
                'courses' => [
                    [
                        'title' => 'Digital Mobility Apps & Navigation Systems',
                        'slug' => 'digital-mobility-apps-navigation',
                        'description' => 'Smart transport tools, booking platforms, battery telemetry monitoring, and member portal utilities.',
                        'duration_hours' => 6,
                        'pass_mark_percentage' => 70.00,
                        'fee_amount' => 0.00,
                        'modules' => [
                            ['title' => 'Smart Navigation & Charging Station Finders', 'description' => 'Locating charging points, reservation apps, and route efficiency.', 'duration_hours' => 3],
                            ['title' => 'TEVDA Portal, Verification & Digital Credentials', 'description' => 'Using the digital ID card, accessing opportunity feeds, and tracking certs.', 'duration_hours' => 3],
                        ],
                    ],
                ],
            ],
            [
                'title' => 'Women & Youth Economic Empowerment in Clean Mobility',
                'slug' => 'women-youth-empowerment',
                'code' => 'EMP-WYE',
                'description' => 'Dedicated empowerment programme supporting women and youth to enter the commercial EV sector as licensed drivers, charging hub attendants, technicians, and fleet micro-entrepreneurs.',
                'objective' => 'Create accessible pathways, mentorship, and economic opportunities for underrepresented demographics in Tanzania\'s green transition.',
                'icon' => 'sparkles',
                'order_number' => 6,
                'courses' => [
                    [
                        'title' => 'Pathway to Commercial EV Entrepreneurship',
                        'slug' => 'pathway-commercial-ev-entrepreneurship',
                        'description' => 'Licence acquisition support, mentorship circles, safe driving networks, and cooperative ownership models.',
                        'duration_hours' => 8,
                        'pass_mark_percentage' => 70.00,
                        'fee_amount' => 0.00,
                        'modules' => [
                            ['title' => 'Overcoming Barriers in Commercial Transport', 'description' => 'Safe operations, community peer networks, and legal protections.', 'duration_hours' => 4],
                            ['title' => 'Cooperative Financing & Micro-Fleet Ownership', 'description' => 'Group savings (VICOBA/SACCOS) for vehicle deposits and operations.', 'duration_hours' => 4],
                        ],
                    ],
                ],
            ],
        ];

        foreach ($programmes as $progData) {
            $courses = $progData['courses'];
            unset($progData['courses']);

            $programme = TrainingProgramme::updateOrCreate(['slug' => $progData['slug']], $progData);

            foreach ($courses as $courseData) {
                $modules = $courseData['modules'];
                unset($courseData['modules']);
                $courseData['programme_id'] = $programme->id;

                $course = TrainingCourse::updateOrCreate(
                    ['slug' => $courseData['slug']],
                    $courseData
                );

                foreach ($modules as $modIndex => $modData) {
                    $modData['course_id'] = $course->id;
                    $modData['order_number'] = $modIndex + 1;
                    TrainingModule::updateOrCreate(
                        ['course_id' => $course->id, 'title' => $modData['title']],
                        $modData
                    );
                }
            }
        }
    }
}
