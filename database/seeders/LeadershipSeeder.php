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
        $boardBody = GovernanceBody::where('slug', 'national-council')->first();
        $secretariatBody = GovernanceBody::where('slug', 'national-secretariat')->first();

        // 1. Confirmed Board & Founding Leadership
        $leaders = [
            [
                'governance_body_id' => $boardBody?->id ?? $necBody?->id,
                'name' => 'Dr. Charles Mwansasu',
                'position' => 'Mwenyekiti wa Baraza la Taifa (Board Chairperson)',
                'is_founding_leader' => true,
                'founding_position' => 'Mwenyekiti Mwanzilishi (Founding Chairperson)',
                'photo_path' => 'images/chairman_dr_charles_mwansasu.jpg',
                'biography' => 'Kiongozi mwanzilishi na mtetezi mkuu wa mapinduzi ya usafiri safi wa umeme nchini Tanzania. Anaongoza Baraza la Taifa kusimamia sera, mikakati na ushirikiano wa kitaifa na kimataifa.',
                'qualifications' => 'Uongozi wa kitaasisi, sera za usafiri safi, na utawala wa kimkakati.',
                'responsibilities' => 'Usimamizi wa kimkakati wa Baraza la Taifa, uhusiano na serikali, utetezi wa kisera na uelekeo mkuu wa chama.',
                'official_office_contact' => 'chairperson@tevda.or.tz',
                'term_period' => '2024 - Present',
                'is_active' => true,
                'order_number' => 1,
            ],
            [
                'governance_body_id' => $secretariatBody?->id ?? $necBody?->id,
                'name' => 'Sharifa Abdallah',
                'position' => 'Mkurugenzi Mtendaji (Executive Director)',
                'is_founding_leader' => false,
                'founding_position' => null,
                'photo_path' => null,
                'biography' => 'Mkurugenzi Mtendaji anayeongoza utekelezaji wa kila siku wa shughuli za TEVDA kote nchini, kusimamia idara za chama, na kuendeleza dira ya madereva makini na usafiri endelevu.',
                'qualifications' => 'Usimamizi wa miradi, uongozi wa kiutendaji, na uratibu wa kitaasisi.',
                'responsibilities' => 'Usimamizi mkuu wa Sekretarieti, utekelezaji wa maamuzi ya Baraza la Taifa, uratibu wa wakurugenzi wa idara, na usimamizi wa shughuli za kitaifa.',
                'official_office_contact' => 'ed@tevda.or.tz',
                'term_period' => '2024 - Present',
                'is_active' => true,
                'order_number' => 2,
            ],
            [
                'governance_body_id' => $secretariatBody?->id ?? $necBody?->id,
                'name' => 'Nafasi Wazi (Inateuliwa)',
                'position' => 'Mkurugenzi wa Fedha (Director of Finance)',
                'is_founding_leader' => false,
                'founding_position' => null,
                'photo_path' => null,
                'biography' => 'Usimamizi thabiti wa mifumo ya fedha, bajeti, udhibiti wa mapato na matumizi, na uwazi wa taarifa za fedha kwa mujibu wa viwango vya kimataifa.',
                'qualifications' => 'Usimamizi wa fedha, uhasibu na ukaguzi wa kitaasisi.',
                'responsibilities' => 'Usimamizi wa sera za fedha, ukusanyaji ada na ruzuku, bajeti za chama na ukaguzi wa fedha.',
                'official_office_contact' => 'finance@tevda.or.tz',
                'term_period' => '2024 - Present',
                'is_active' => true,
                'order_number' => 3,
            ],
            [
                'governance_body_id' => $secretariatBody?->id ?? $necBody?->id,
                'name' => 'Nafasi Wazi (Inateuliwa)',
                'position' => 'Mkurugenzi wa Utawala na Rasilimali Watu',
                'is_founding_leader' => false,
                'founding_position' => null,
                'photo_path' => null,
                'biography' => 'Uratibu wa utawala wa kila siku, usimamizi wa rasilimali watu, uajiri, nidhamu na mazingira salama ya utendaji kazi ndani ya TEVDA.',
                'qualifications' => 'Usimamizi wa rasilimali watu na utawala bora wa mashirika.',
                'responsibilities' => 'Usimamizi wa wafanyakazi, utawala wa ofisi kuu na kanda, utekelezaji wa sera za ajira na mafunzo ya ndani.',
                'official_office_contact' => 'admin@tevda.or.tz',
                'term_period' => '2024 - Present',
                'is_active' => true,
                'order_number' => 4,
            ],
            [
                'governance_body_id' => $secretariatBody?->id ?? $necBody?->id,
                'name' => 'Nafasi Wazi (Inateuliwa)',
                'position' => 'Mkurugenzi wa Miradi na Ufadhili',
                'is_founding_leader' => false,
                'founding_position' => null,
                'photo_path' => null,
                'biography' => 'Kubuni, kuandika na kusimamia miradi ya kimkakati ya EV, kutafuta fursa za ufadhili wa kimataifa na kitaifa, na kuunganisha madereva na mikopo na ruzuku.',
                'qualifications' => 'Usimamizi wa miradi, uandishi wa mapendekezo ya miradi na uhamasishaji rasilimali.',
                'responsibilities' => 'Kusimamia miradi ya vituo vya kuchaji, upatikanaji wa magari/pikipiki za umeme na ushirikiano na wafadhili.',
                'official_office_contact' => 'projects@tevda.or.tz',
                'term_period' => '2024 - Present',
                'is_active' => true,
                'order_number' => 5,
            ],
            [
                'governance_body_id' => $secretariatBody?->id ?? $necBody?->id,
                'name' => 'Nafasi Wazi (Inateuliwa)',
                'position' => 'Mkurugenzi wa Sera na Sheria',
                'is_founding_leader' => false,
                'founding_position' => null,
                'photo_path' => null,
                'biography' => 'Ushauri wa kisheria kwa chama, mapitio ya mikataba, utetezi wa madereva mbele ya mamlaka za kisheria (LATRA, Polisi, EWURA), na ushiriki wa mabadiliko ya kisera.',
                'qualifications' => 'Sheria za usafirishaji, sera za umma na usuluhishi wa kisheria.',
                'responsibilities' => 'Usimamizi wa masuala yote ya kisheria, mikataba ya ushirikiano, utetezi wa kisheria wa wanachama na sera za usafiri safi.',
                'official_office_contact' => 'legal@tevda.or.tz',
                'term_period' => '2024 - Present',
                'is_active' => true,
                'order_number' => 6,
            ],
            [
                'governance_body_id' => $secretariatBody?->id ?? $necBody?->id,
                'name' => 'Nafasi Wazi (Inateuliwa)',
                'position' => 'Mkurugenzi wa Teknolojia (ICT)',
                'is_founding_leader' => false,
                'founding_position' => null,
                'photo_path' => null,
                'biography' => 'Usimamizi wa mifumo ya kidijitali, kadi za kielektroniki zenye QR-Code, kanzidata ya wanachama, na uunganishaji wa teknolojia za EV na mifumo ya malipo.',
                'qualifications' => 'Mhandisi wa programu, usalama wa mifumo (Cybersecurity) na uchambuzi wa takwimu.',
                'responsibilities' => 'Kusimamia miundombinu ya teknolojia ya TEVDA, usalama wa data za wanachama, tovuti na huduma za mtandao.',
                'official_office_contact' => 'ict@tevda.or.tz',
                'term_period' => '2024 - Present',
                'is_active' => true,
                'order_number' => 7,
            ],
            [
                'governance_body_id' => $secretariatBody?->id ?? $necBody?->id,
                'name' => 'Nafasi Wazi (Inateuliwa)',
                'position' => 'Mkurugenzi wa Uanachama na Uhamasishaji',
                'is_founding_leader' => false,
                'founding_position' => null,
                'photo_path' => null,
                'biography' => 'Uhamasishaji wa madereva kujiunga na chama, uratibu wa matawi ya mikoa na wilaya, utatuzi wa changamoto za wanachama na ukuzaji wa mtandao wa madereva.',
                'qualifications' => 'Uhamasishaji wa jamii, mawasiliano na uongozi wa makundi ya kijamii.',
                'responsibilities' => 'Uandikishaji wanachama, usambazaji wa vitambulisho, uratibu wa mikutano ya wanachama na usimamizi wa matawi.',
                'official_office_contact' => 'membership@tevda.or.tz',
                'term_period' => '2024 - Present',
                'is_active' => true,
                'order_number' => 8,
            ],
            [
                'governance_body_id' => $secretariatBody?->id ?? $necBody?->id,
                'name' => 'Nafasi Wazi (Inateuliwa)',
                'position' => 'Mkurugenzi wa Ushirikiano na Mahusiano',
                'is_founding_leader' => false,
                'founding_position' => null,
                'photo_path' => null,
                'biography' => 'Kujenga mahusiano thabiti na wadau wa serikali, mashirika binafsi, makampuni ya vyombo vya umeme, benki na vyombo vya habari kwa manufaa ya chama.',
                'qualifications' => 'Mahusiano ya umma (PR), diplomasia na ushirikiano wa kibiashara.',
                'responsibilities' => 'Mahusiano ya umma, mawasiliano ya nje na vyombo vya habari, matukio rasmi na mikataba ya ushirikiano.',
                'official_office_contact' => 'partnerships@tevda.or.tz',
                'term_period' => '2024 - Present',
                'is_active' => true,
                'order_number' => 9,
            ],
            [
                'governance_body_id' => $secretariatBody?->id ?? $necBody?->id,
                'name' => 'Nafasi Wazi (Inateuliwa)',
                'position' => 'Mkurugenzi wa Ustawi wa Wanachama',
                'is_founding_leader' => false,
                'founding_position' => null,
                'photo_path' => null,
                'biography' => 'Kusimamia mipango ya hifadhi ya jamii, bima za afya na vyombo kwa madereva, mikopo nafuu ya dharura na usalama kazini kwa wanachama wote wa TEVDA.',
                'qualifications' => 'Ustawi wa jamii, mifumo ya bima na uwezeshaji wananchi kiuchumi.',
                'responsibilities' => 'Mipango ya ustawi, bima za wanachama, msaada wa kisheria na matibabu pale dharura inapotokea barabarani.',
                'official_office_contact' => 'welfare@tevda.or.tz',
                'term_period' => '2024 - Present',
                'is_active' => true,
                'order_number' => 10,
            ],
            [
                'governance_body_id' => $necBody?->id,
                'name' => 'Alex John Lupeja',
                'position' => 'Katibu Mwanzilishi (Founding Secretary)',
                'is_founding_leader' => true,
                'founding_position' => 'Founding Secretary',
                'photo_path' => null,
                'biography' => 'Katibu mwanzilishi aliyejitoa kuanzisha mifumo ya kiutawala, uhamasishaji madereva na uendeshaji thabiti wa chama nchini kote.',
                'qualifications' => 'Utawala wa shughuli, uratibu wa madereva na usimamizi wa taasisi.',
                'responsibilities' => 'Utawala wa Sekretarieti, barua na kumbukumbu rasmi, na uratibu wa uongozi.',
                'official_office_contact' => 'secretary@tevda.or.tz',
                'term_period' => 'Founding Term',
                'is_active' => true,
                'order_number' => 11,
            ],
            [
                'governance_body_id' => $necBody?->id,
                'name' => 'Sabrina Gulam',
                'position' => 'Mweka Hazina Mwanzilishi (Founding Treasurer)',
                'is_founding_leader' => true,
                'founding_position' => 'Founding Treasurer',
                'photo_path' => null,
                'biography' => 'Mweka hazina mwanzilishi anayezingatia uwajibikaji wa fedha, mifumo ya wazi ya mapato na programu za kuwawezesha waendeshaji wa EV kifedha.',
                'qualifications' => 'Usimamizi wa fedha, ujasiriamali na ukaguzi wa mapato.',
                'responsibilities' => 'Uzingatiaji wa kanuni za fedha, bajeti, akaunti za michango na ripoti za fedha.',
                'official_office_contact' => 'treasurer@tevda.or.tz',
                'term_period' => 'Founding Term',
                'is_active' => true,
                'order_number' => 12,
            ],
        ];

        foreach ($leaders as $lData) {
            Leader::updateOrCreate(
                ['position' => $lData['position']],
                $lData
            );
        }
    }
}
