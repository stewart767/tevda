class AppConstants {
  static const String appName = 'TEVDA';
  static const String appTagline = 'SMART DRIVERS • SMART MOBILITY';
  static const String associationName = 'Tanzania Electric Vehicle Drivers Association';
  static const String officialWebsite = 'https://www.tevda.or.tz/';
  static const String supportHotline = '+255 750 000 000';
  static const String officialEmail = 'info@tevda.or.tz';
  static const String hqLocation = 'TEVDA National Secretariat, Dar es Salaam, Tanzania';

  // API Config
  static const String apiBaseUrl = 'http://localhost/tevda/public/api/v1';

  // Membership Categories
  static const List<Map<String, dynamic>> membershipCategories = [
    {
      'id': 1,
      'name': 'Full Member',
      'name_sw': 'Mwanachama Kamili',
      'slug': 'full-member',
      'description': 'Madereva na wamiliki wa vyombo vya moto vya umeme (Bajaji, Bodaboda, Magari, Mabasi).',
      'requires_license': true,
      'requires_vehicle': true,
      'required_docs': ['NIDA', 'Leseni ya Udereva', 'Picha ya Pasipoti', 'Kadi ya Gari/Chombo'],
    },
    {
      'id': 2,
      'name': 'Associate Member',
      'name_sw': 'Mwanachama Mshiriki',
      'slug': 'associate-member',
      'description': 'Mafundi wa EV, waendeshaji vituo vya kuchaji, wataalamu, na wadau wa nishati safi.',
      'requires_license': false, // NO driving license required!
      'requires_vehicle': false,
      'required_docs': ['NIDA', 'Picha ya Pasipoti', 'Cheti cha Taaluma/Ushiriki'],
    },
    {
      'id': 3,
      'name': 'Honorary Member',
      'name_sw': 'Mwanachama wa Heshima',
      'slug': 'honorary-member',
      'description': 'Wateule na viongozi mashuhuri waliochangia maendeleo ya uhamaji wa umeme nchini.',
      'requires_license': false,
      'requires_vehicle': false,
      'required_docs': ['Uteuzi Rasmi wa Baraza Kuu'],
      'fee_exempt': true,
    },
    {
      'id': 4,
      'name': 'Institutional Member',
      'name_sw': 'Mwanachama wa Kitaasisi',
      'slug': 'institutional-member',
      'description': 'Makampuni, wamiliki wa meli za EV (Fleets), na taasisi za kifedha na nishati.',
      'requires_license': false,
      'requires_vehicle': false,
      'required_docs': ['TIN ya Kampuni', 'Leseni ya Biashara', 'Kitambulisho cha Mwakilishi'],
    },
  ];

  // Official Accredited Training Courses from DB
  static const List<Map<String, dynamic>> officialCourses = [
    {
      'id': 1,
      'title': 'Core Electric Vehicle Operation & Swapping Mastery',
      'title_sw': 'Uendeshaji wa EV na Ubadilishaji Betri (Battery Swapping)',
      'duration_hours': 16,
      'pass_mark_percentage': 75.0,
      'modules_count': 6,
      'category': 'EV Operations',
      'description': 'Kanuni za kimsingi za injini ya umeme, mifumo ya kubadilisha betri kwa usalama, na uokoaji dharura.',
      'icon': 'electric_meter',
    },
    {
      'id': 2,
      'title': 'Defensive Driving & Urban EV Road Safety',
      'title_sw': 'Uendeshaji wa Kujihami na Usalama Barabarani',
      'duration_hours': 12,
      'pass_mark_percentage': 80.0,
      'modules_count': 5,
      'category': 'Road Safety',
      'description': 'Kudhibiti breki za regen (regenerative braking), kuzuia ajali mijini, na sheria za LATRA.',
      'icon': 'security',
    },
    {
      'id': 3,
      'title': 'Financial Literacy & Micro-Fleet Business Planning',
      'title_sw': 'Elimu ya Fedha na Usimamizi wa Mapato ya Chombo',
      'duration_hours': 10,
      'pass_mark_percentage': 70.0,
      'modules_count': 4,
      'category': 'Financial Planning',
      'description': 'Kupanga mapato ya kila siku, urejeshaji wa mikopo ya EV, bima ya chombo, na akiba.',
      'icon': 'account_balance_wallet',
    },
    {
      'id': 4,
      'title': 'Preventive Maintenance & Mechanical Inspection',
      'title_sw': 'Matengenezo Kinga na Ukaguzi wa Kila Siku',
      'duration_hours': 8,
      'pass_mark_percentage': 75.0,
      'modules_count': 4,
      'category': 'Maintenance',
      'description': 'Kukagua matairi, mfumo wa kupoza betri (cooling system), nyaya za high voltage na taa.',
      'icon': 'build',
    },
    {
      'id': 5,
      'title': 'Digital Mobility Apps & Navigation Systems',
      'title_sw': 'Mifumo ya Kidijitali ya Safari na Ramani',
      'duration_hours': 6,
      'pass_mark_percentage': 70.0,
      'modules_count': 3,
      'category': 'Digital Tools',
      'description': 'Kutumia programu za ride-hailing, kutafuta vituo vya kubadilishia betri na GPS.',
      'icon': 'navigation',
    },
    {
      'id': 6,
      'title': 'Pathway to Commercial EV Entrepreneurship',
      'title_sw': 'Ujasiriamali wa Vyombo vya Umeme na Uwekezaji',
      'duration_hours': 8,
      'pass_mark_percentage': 70.0,
      'modules_count': 4,
      'category': 'Empowerment',
      'description': 'Kupanua umiliki kutoka dereva hadi mmiliki wa vyombo vingi (fleet owner) kwa vijana na wanawake.',
      'icon': 'trending_up',
    },
  ];

  // Official Opportunities Directory from DB
  static const List<Map<String, dynamic>> verifiedOpportunities = [
    {
      'id': 1,
      'title': 'Commercial EV Driver Fleet Recruitment',
      'title_sw': 'Nafasi za Madereva wa Bajaji za Umeme (Full-Time)',
      'category': 'Employment',
      'provider': 'EcoMobility Fleet Tanzania',
      'deadline': '30 Desemba 2026',
      'location': 'Dar es Salaam (Kinondoni & Ilala)',
      'eligibility': 'Madereva wenye leseni daraja A/A2/B, kitambulisho cha TEVDA, uzoefu zaidi ya mwaka 1.',
      'type': 'Ajira Rasmi',
    },
    {
      'id': 2,
      'title': 'Clean Energy Green Financing (Low-Interest Loan)',
      'title_sw': 'Mkopo wa Nafuu wa Kununua Bajaji au Pikipiki ya Umeme',
      'category': 'Vehicle Financing',
      'provider': 'CRDB & Tanzania E-Mobility Fund',
      'deadline': '15 Januari 2027',
      'location': 'Nchi Nzima (Tanzania)',
      'eligibility': 'Mwanachama kamili wa TEVDA aliyehitimu kozi ya Financial Literacy na mwenye akiba hai.',
      'type': 'Ufadhili wa Chombo',
    },
    {
      'id': 3,
      'title': 'Women & Youth EV Operator Empowerment Grant',
      'title_sw': 'Ruzuku ya Uwezeshaji Madereva Wanawake na Vijana',
      'category': 'Grants',
      'provider': 'UNDP & TEVDA National Council',
      'deadline': '20 Desemba 2026',
      'location': 'Mwanza, Dodoma na Arusha',
      'eligibility': 'Wanawake na vijana chini ya miaka 30 waliojiunga na mafunzo ya ujasiriamali ya TEVDA.',
      'type': 'Ruzuku Isiyorejeshwa',
    },
    {
      'id': 4,
      'title': 'OEM Battery & Spare Parts Supply Partnership',
      'title_sw': 'Vipuri Halisi vya EV kwa Bei ya Punguzo ya Wanachama',
      'category': 'Spare Parts',
      'provider': 'Ampersand & Spiro Certified Hubs',
      'deadline': 'Inaendelea',
      'location': 'Dar es Salaam, Morogoro',
      'eligibility': 'Onyesha kadi ya kidijitali (Smart ID) ya TEVDA ili kupata punguzo la 15%.',
      'type': 'Punguzo la Vipuri',
    },
  ];

  // Tanzania Regions
  static const List<String> tanzaniaRegions = [
    'Dar es Salaam',
    'Arusha',
    'Dodoma',
    'Mwanza',
    'Kilimanjaro',
    'Mbeya',
    'Morogoro',
    'Tanga',
    'Zanzibar Mjini Magharibi',
    'Kagera',
    'Iringa',
    'Tabora',
    'Kigoma',
    'Mara',
    'Shinyanga',
    'Geita',
    'Manyara',
    'Singida',
    'Rukwa',
    'Ruvuma',
    'Songwe',
    'Lindi',
    'Mtwara',
    'Pwani',
    'Katavi',
    'Njombe',
    'Simiyu',
  ];
}
