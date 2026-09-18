<?php

namespace Database\Seeders;

use App\Models\Setting;
use App\Models\Faq;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            // General & Branding
            ['group' => 'site', 'key' => 'site_name', 'value' => 'Tanzania Electric Vehicle Drivers Association (TEVDA)', 'type' => 'text', 'description' => 'Official Association Name', 'is_public' => true],
            ['group' => 'site', 'key' => 'site_short_name', 'value' => 'TEVDA', 'type' => 'text', 'description' => 'Short Name / Acronym', 'is_public' => true],
            ['group' => 'site', 'key' => 'site_logo', 'value' => null, 'type' => 'file', 'description' => 'Official Association Logo', 'is_public' => true],
            ['group' => 'site', 'key' => 'site_motto', 'value' => 'SMART DRIVERS SMART MOBILITY', 'type' => 'text', 'description' => 'Official Association Motto', 'is_public' => true],
            ['group' => 'site', 'key' => 'site_tagline', 'value' => 'Empowering Tanzanians Through Electric Mobility', 'type' => 'text', 'description' => 'Homepage Hero Tagline', 'is_public' => true],
            ['group' => 'site', 'key' => 'hero_supporting_text', 'value' => 'TEVDA represents drivers and operators of commercially used electric vehicles. We connect members with training, employment, business opportunities, capital, grants, affordable loans and partnerships that help them participate in Tanzania’s clean-energy economy.', 'type' => 'textarea', 'description' => 'Hero Supporting Text', 'is_public' => true],

            // Contact Info (Official Sinza Mori)
            ['group' => 'contact', 'key' => 'contact_address', 'value' => 'Sinza Mori, P.O. Box 40015, Dar es Salaam, Tanzania', 'type' => 'text', 'description' => 'Physical & Postal Address', 'is_public' => true],
            ['group' => 'contact', 'key' => 'contact_phone', 'value' => '+255 757 700 401', 'type' => 'text', 'description' => 'Official Telephone Line', 'is_public' => true],
            ['group' => 'contact', 'key' => 'contact_email', 'value' => 'info@tevda.or.tz', 'type' => 'text', 'description' => 'Official Public Email', 'is_public' => true],
            ['group' => 'contact', 'key' => 'contact_website', 'value' => 'www.tevda.or.tz', 'type' => 'text', 'description' => 'Official Website URL', 'is_public' => true],
            ['group' => 'contact', 'key' => 'office_hours', 'value' => 'Monday – Friday, 8:00 a.m. – 5:00 p.m.', 'type' => 'text', 'description' => 'Secretariat Office Hours', 'is_public' => true],

            // About TEVDA & Chairman's Welcome Message
            ['group' => 'about', 'key' => 'about_section_badge', 'value' => 'Executive Welcome & About TEVDA', 'type' => 'text', 'description' => 'Homepage About Section Badge', 'is_public' => true],
            ['group' => 'about', 'key' => 'about_section_title', 'value' => 'Shaping the Future of Electric Mobility in Tanzania', 'type' => 'text', 'description' => 'Homepage About Section Title', 'is_public' => true],
            ['group' => 'about', 'key' => 'about_section_subtitle', 'value' => 'Connecting commercial EV drivers, transport operators, financiers, and clean-energy innovators into a unified, sustainable ecosystem.', 'type' => 'textarea', 'description' => 'Homepage About Section Subtitle', 'is_public' => true],
            ['group' => 'about', 'key' => 'chairman_message_title', 'value' => 'Chairman’s Welcome Message', 'type' => 'text', 'description' => 'Chairman Message Title', 'is_public' => true],
            ['group' => 'about', 'key' => 'chairman_name', 'value' => 'Dr. Charles Mwansasu', 'type' => 'text', 'description' => 'Chairman Full Name', 'is_public' => true],
            ['group' => 'about', 'key' => 'chairman_role', 'value' => 'Organization Chair Man', 'type' => 'text', 'description' => 'Chairman Official Title / Role', 'is_public' => true],
            ['group' => 'about', 'key' => 'chairman_organization', 'value' => 'Tanzania Electric Vehicles Drivers Association (TEVDA)', 'type' => 'text', 'description' => 'Chairman Organization Name', 'is_public' => true],
            ['group' => 'about', 'key' => 'chairman_tagline', 'value' => 'Welcome to TEVDA—Smart Driving, Greener Future.', 'type' => 'text', 'description' => 'Chairman Slogan / Welcome Quote', 'is_public' => true],
            ['group' => 'about', 'key' => 'chairman_photo', 'value' => 'images/chairman_dr_charles_mwansasu.jpg', 'type' => 'file', 'description' => 'Chairman Portrait Photo', 'is_public' => true],
            ['group' => 'site', 'key' => 'chairman_signature', 'value' => null, 'type' => 'file', 'description' => 'Official Chairman Signature', 'is_public' => true],
            ['group' => 'about', 'key' => 'chairman_message', 'value' => "On behalf of the Tanzania Electric Vehicles Drivers Association (TEVDA), I warmly welcome our members, partners and stakeholders to our official website.\n\nTEVDA’s vision is to ensure that every Tanzanian can benefit from the transition to electric mobility and clean energy. We represent drivers and operators of all commercial electric vehicles, including electric cars, buses, bajaji and motorcycles.\n\nOur mission is to create opportunities for our members through access to grants, affordable financing, training, technology, employment and investment. We place particular emphasis on empowering young people, women and low-income communities while promoting road safety, environmental protection and professional standards.\n\nWe invite government institutions, development partners, financial institutions, investors, manufacturers and clean-energy companies to work with us in building an inclusive and sustainable electric-mobility ecosystem in Tanzania.\n\nTogether, we can create jobs, reduce transport costs and ensure that no Tanzanian is left behind in this technological transformation.\n\nWelcome to TEVDA—Smart Driving, Greener Future.", 'type' => 'textarea', 'description' => 'Full Chairman Welcome Message', 'is_public' => true],

            // About TEVDA Core Content
            ['group' => 'about', 'key' => 'vision', 'value' => 'To be the leading national association fostering a prosperous, skilled, and safe commercial electric vehicle driver community driving Tanzania\'s clean-energy transport transition.', 'type' => 'textarea', 'description' => 'TEVDA Vision', 'is_public' => true],
            ['group' => 'about', 'key' => 'mission', 'value' => 'To represent, train, protect, and empower commercial electric vehicle drivers and operators across Tanzania by facilitating access to green finance, technical skills, digital tools, fair operating conditions, and strategic national partnerships.', 'type' => 'textarea', 'description' => 'TEVDA Mission', 'is_public' => true],
            ['group' => 'about', 'key' => 'values', 'value' => 'Integrity, Safety, Innovation, Inclusivity, Sustainability, and Accountability.', 'type' => 'text', 'description' => 'Core Values', 'is_public' => true],

            // Who We Are (Institutional Profile)
            ['group' => 'about', 'key' => 'who_we_are_badge', 'value' => 'Institutional Profile & Mandate', 'type' => 'text', 'description' => 'Who We Are Badge Label', 'is_public' => true],
            ['group' => 'about', 'key' => 'who_we_are_title', 'value' => 'Who We Are', 'type' => 'text', 'description' => 'Who We Are Main Title', 'is_public' => true],
            ['group' => 'about', 'key' => 'who_we_are_description', 'value' => 'The Tanzania Electric Vehicle Drivers Association (TEVDA) is the premier national apex body organizing, upskilling, and advocating for commercial electric vehicle operators, charging pioneers, and sustainable fleet entrepreneurs across Tanzania.', 'type' => 'textarea', 'description' => 'Who We Are Main Description', 'is_public' => true],
            ['group' => 'about', 'key' => 'who_we_are_subtext', 'value' => 'Empowering drivers of electric cars, tricycles (bajaji), motorcycles (bodaboda), and clean-mobility fleets across all regions of Tanzania with accredited safety certification, green financing, and statutory representation.', 'type' => 'textarea', 'description' => 'Who We Are Subtext', 'is_public' => true],

            // SMART Principles
            ['group' => 'about', 'key' => 'smart_principles_badge', 'value' => 'Operational Philosophy', 'type' => 'text', 'description' => 'SMART Principles Badge', 'is_public' => true],
            ['group' => 'about', 'key' => 'smart_principles_title', 'value' => 'Our SMART Principles', 'type' => 'text', 'description' => 'SMART Principles Title', 'is_public' => true],
            ['group' => 'about', 'key' => 'smart_principles_subtitle', 'value' => 'The foundational pillars guiding every TEVDA driver, operator, and operational standard', 'type' => 'textarea', 'description' => 'SMART Principles Subtitle', 'is_public' => true],
            ['group' => 'about', 'key' => 'smart_s_title', 'value' => 'Safety', 'type' => 'text', 'description' => 'SMART S Title', 'is_public' => true],
            ['group' => 'about', 'key' => 'smart_s_desc', 'value' => 'Zero preventable accidents through rigorous defensive driving, high-voltage battery operating standards, and pedestrian awareness in silent electric vehicles.', 'type' => 'textarea', 'description' => 'SMART S Description', 'is_public' => true],
            ['group' => 'about', 'key' => 'smart_m_title', 'value' => 'Modern Tech', 'type' => 'text', 'description' => 'SMART M Title', 'is_public' => true],
            ['group' => 'about', 'key' => 'smart_m_desc', 'value' => 'Embracing smart EV powertrains, telemetry, rapid battery swapping networks, smart charging infrastructure, and digital booking tools.', 'type' => 'textarea', 'description' => 'SMART M Description', 'is_public' => true],
            ['group' => 'about', 'key' => 'smart_a_title', 'value' => 'Accountability', 'type' => 'text', 'description' => 'SMART A Title', 'is_public' => true],
            ['group' => 'about', 'key' => 'smart_a_desc', 'value' => 'Financial transparency, strict regulatory compliance with transport authorities (LATRA), and ethical member representation.', 'type' => 'textarea', 'description' => 'SMART A Description', 'is_public' => true],
            ['group' => 'about', 'key' => 'smart_r_title', 'value' => 'Respect', 'type' => 'text', 'description' => 'SMART R Title', 'is_public' => true],
            ['group' => 'about', 'key' => 'smart_r_desc', 'value' => 'Passenger dignity, mutual driver solidarity, ethical business conduct, gender inclusivity, and professional road etiquette.', 'type' => 'textarea', 'description' => 'SMART R Description', 'is_public' => true],
            ['group' => 'about', 'key' => 'smart_t_title', 'value' => 'Teamwork', 'type' => 'text', 'description' => 'SMART T Title', 'is_public' => true],
            ['group' => 'about', 'key' => 'smart_t_desc', 'value' => 'Uniting drivers, technicians, energy companies, financiers, and government bodies to build a thriving clean mobility economy.', 'type' => 'textarea', 'description' => 'SMART T Description', 'is_public' => true],

            // Fraud & Security Warning
            ['group' => 'security', 'key' => 'fraud_warning', 'value' => 'IMPORTANT NOTICE: TEVDA does not request cash payments to private personal mobile accounts. All official payments for membership, training, or association services must be made through authorized TEVDA payment reference channels with an official receipt. TEVDA does not falsely guarantee automatic grant or vehicle allocations without official stakeholder review.', 'type' => 'textarea', 'description' => 'Safety and Anti-Fraud Notice', 'is_public' => true],
        ];

        foreach ($settings as $s) {
            Setting::updateOrCreate(['key' => $s['key']], $s);
        }

        // FAQs
        $faqs = [
            [
                'category' => 'Membership',
                'question' => 'Who is eligible to become a Full Member of TEVDA?',
                'answer' => 'Full membership is open to all Tanzanian commercial electric vehicle drivers operating electric three-wheelers (bajaj), electric motorcycles, electric passenger taxis, vans, or buses who hold a valid driving licence issued by the relevant authorities.',
                'order_number' => 1,
                'is_active' => true,
            ],
            [
                'category' => 'Membership',
                'question' => 'Can non-drivers join TEVDA?',
                'answer' => 'Yes. Technicians, engineers, battery/charging specialists, researchers, and clean-energy advocates can join as Associate Members. Corporate entities, EV assemblers, and logistics companies can join as Institutional Members.',
                'order_number' => 2,
                'is_active' => true,
            ],
            [
                'category' => 'Digital ID & Verification',
                'question' => 'How can the public or transport authorities verify my TEVDA credentials?',
                'answer' => 'Every active TEVDA member receives a Digital Membership Card and Certificate with a secure QR code. Anyone can scan the QR code or visit www.tevda.or.tz/verify/membership or www.tevda.or.tz/verify/certificate to verify active status instantly.',
                'order_number' => 3,
                'is_active' => true,
            ],
            [
                'category' => 'Projects & Opportunities',
                'question' => 'How does the 50 Electric Three-Wheeler Programme work?',
                'answer' => 'The 50 Electric Three-Wheeler Programme is a pilot project under development. Once official funding and asset deliveries are finalized, verified TEVDA members will be invited to apply through the online portal.',
                'order_number' => 4,
                'is_active' => true,
            ],
        ];

        foreach ($faqs as $faq) {
            Faq::updateOrCreate(['question' => $faq['question']], $faq);
        }
    }
}
