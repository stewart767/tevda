/**
 * TEVDA Mobile Application Data Store
 * Official Data for Tanzania Electric Vehicle Drivers Association
 */

window.TEVDA_DATA = {
  // Current active driver profile (matching the 5-star official design)
  currentMember: {
    fullName: "Amani Juma",
    greetingName: "Amani",
    memberNo: "TEVDA-2026-000128",
    status: "ACTIVE MEMBER",
    statusCode: "approved",
    phone: "+255 712 345 678",
    email: "amani.juma@gmail.com",
    gender: "male",
    category: "Full Member",
    vehicleType: "ELECTRIC BAJAJI",
    vehicleReg: "T 482 EVM",
    vehicleMake: "Ampersand E-Trike 2025",
    drivingLicence: "TZA-DL-749102",
    licenceClass: "C3",
    nidaNumber: "19940812-14102-00001-42",
    region: "Dar es Salaam",
    district: "Kinondoni",
    ward: "Sinza Mori",
    operatingStage: "Sinza Mori / Kituo cha Shekilango",
    validThrough: "VALID THROUGH 2027",
    joinedDate: "01 Jan 2026",
    photoUrl: "assets/driver-amani.jpg",
    qrPayload: "https://www.tevda.or.tz/verify-id/TEVDA-2026-000128"
  },

  // Authoritative Membership Categories matching database
  categories: [
    {
      id: 1,
      slug: "full-member",
      name: "Full Member",
      nameSw: "Mwanachama Kamili (Dereva wa Chombo cha Umeme)",
      description: "Madereva na wamiliki wa vyombo vya moto vya umeme (Bajaji, Bodaboda, Magari, Mabasi).",
      requiresLicense: true,
      requiresVehicle: true,
      docs: ["NIDA", "Leseni ya Udereva", "Picha ya Pasipoti", "Kadi ya Gari"]
    },
    {
      id: 2,
      slug: "associate-member",
      name: "Associate Member",
      nameSw: "Mwanachama Mshiriki (Fundi / Mdau wa EV)",
      description: "Mafundi wa EV, vituo vya kuchaji, na wataalamu. Haihitaji leseni ya udereva.",
      requiresLicense: false,
      requiresVehicle: false,
      docs: ["NIDA", "Picha ya Pasipoti", "Cheti cha Taaluma"]
    },
    {
      id: 3,
      slug: "honorary-member",
      name: "Honorary Member",
      nameSw: "Mwanachama wa Heshima",
      description: "Viongozi na wateule waliochangia sekta ya EV nchini. Hauruhusiwi ada.",
      requiresLicense: false,
      requiresVehicle: false,
      feeExempt: true
    },
    {
      id: 4,
      slug: "institutional-member",
      name: "Institutional Member",
      nameSw: "Mwanachama wa Kitaasisi (Fleets & Corporate)",
      description: "Makampuni, wamiliki wa meli za EV, vituo vya kuchaji na taasisi za kifedha.",
      requiresLicense: false,
      requiresVehicle: false,
      docs: ["TIN ya Kampuni", "Leseni ya Biashara", "Kitambulisho cha Mwakilishi"]
    }
  ],

  // Official Fee Structure (Dynamic Council Disclaimer)
  feePolicy: {
    note: "Ada rasmi inathibitishwa na Baraza Kuu la TEVDA. Malipo yote yanadhibitiwa na GePG Control Number.",
    currency: "TZS",
    registrationFee: 50000.0,
    annualFee: 30000.0
  },

  // Ongoing registration state
  registrationDraft: {
    fullName: "",
    phone: "",
    region: "Dar es Salaam",
    district: "Kinondoni",
    ward: "Sinza",
    categoryId: 1,
    amount: "TZS 50,000",
    controlNumber: "994821730561",
    paymentStatus: "awaiting_payment",
    drivingLicence: "",
    vehicleType: "Electric Bajaji",
    vehiclePlate: ""
  },

  // Tanzania Regions
  regions: [
    {
      id: "dar",
      name: "Dar es Salaam",
      districts: [
        {
          id: "kinondoni",
          name: "Kinondoni",
          wards: ["Sinza", "Kijitonyama", "Mikocheni", "Msasani", "Magomeni", "Kawe", "Tandale"]
        },
        {
          id: "ubungo",
          name: "Ubungo",
          wards: ["Ubungo", "Kimara", "Mwenge", "Mbezi", "Goba", "Mburahati"]
        },
        {
          id: "ilala",
          name: "Ilala",
          wards: ["Kariakoo", "Upanga Magharibi", "Upanga Mashariki", "Buguruni", "Vingunguti", "Tabata", "Segerea"]
        },
        {
          id: "temeke",
          name: "Temeke",
          wards: ["Chang'ombe", "Kurasini", "Mbagala", "Temeke", "Yombo Vituka", "Mtoni"]
        },
        {
          id: "kigamboni",
          name: "Kigamboni",
          wards: ["Kigamboni", "Kimbiji", "Mjimwema", "Vijibweni", "Somangila"]
        }
      ]
    },
    {
      id: "arusha",
      name: "Arusha",
      districts: [
        {
          id: "arusha_city",
          name: "Arusha City",
          wards: ["Sekei", "Themi", "Kaloleni", "Ngarenaro", "Unga Limited", "Sombetini"]
        }
      ]
    },
    {
      id: "dodoma",
      name: "Dodoma",
      districts: [
        {
          id: "dodoma_city",
          name: "Dodoma City",
          wards: ["Makole", "Tambukareli", "Kizota", "Madukani", "Nzuguni", "Kikuyu"]
        }
      ]
    },
    {
      id: "mwanza",
      name: "Mwanza",
      districts: [
        {
          id: "nyamagana",
          name: "Nyamagana",
          wards: ["Nyamagana", "Mabatini", "Pamba", "Mirongo", "Igoma"]
        }
      ]
    }
  ],

  // Official 6 Accredited Courses from Database
  courses: [
    {
      id: 1,
      title: "Core Electric Vehicle Operation & Swapping Mastery",
      titleSw: "Uendeshaji wa EV na Ubadilishaji Betri (Battery Swapping)",
      hours: 16,
      passMark: 75,
      modules: 6,
      category: "EV Operations",
      desc: "Kanuni za kimsingi za injini ya umeme, mifumo ya kubadilisha betri kwa usalama, na uokoaji dharura.",
      badge: "Accredited",
      progress: 100
    },
    {
      id: 2,
      title: "Defensive Driving & Urban EV Road Safety",
      titleSw: "Uendeshaji wa Kujihami na Usalama Barabarani",
      hours: 12,
      passMark: 80,
      modules: 5,
      category: "Road Safety",
      desc: "Kudhibiti breki za regen (regenerative braking), kuzuia ajali mijini, na sheria za LATRA.",
      badge: "Safety",
      progress: 65
    },
    {
      id: 3,
      title: "Financial Literacy & Micro-Fleet Business Planning",
      titleSw: "Elimu ya Fedha na Usimamizi wa Mapato ya Chombo",
      hours: 10,
      passMark: 70,
      modules: 4,
      category: "Financial Planning",
      desc: "Kupanga mapato ya kila siku, urejeshaji wa mikopo ya EV, bima ya chombo, na akiba.",
      badge: "Finance",
      progress: 30
    },
    {
      id: 4,
      title: "Preventive Maintenance & Mechanical Inspection",
      titleSw: "Matengenezo Kinga na Ukaguzi wa Kila Siku",
      hours: 8,
      passMark: 75,
      modules: 4,
      category: "Maintenance",
      desc: "Kukagua matairi, mfumo wa kupoza betri, nyaya za high voltage na taa.",
      badge: "Technical",
      progress: 0
    },
    {
      id: 5,
      title: "Digital Mobility Apps & Navigation Systems",
      titleSw: "Mifumo ya Kidijitali ya Safari na Ramani",
      hours: 6,
      passMark: 70,
      modules: 3,
      category: "Digital Tools",
      desc: "Kutumia programu za ride-hailing, kutafuta vituo vya kubadilishia betri na GPS.",
      badge: "Digital",
      progress: 0
    },
    {
      id: 6,
      title: "Pathway to Commercial EV Entrepreneurship",
      titleSw: "Ujasiriamali wa Vyombo vya Umeme na Uwekezaji",
      hours: 8,
      passMark: 70,
      modules: 4,
      category: "Empowerment",
      desc: "Kupanua umiliki kutoka dereva hadi mmiliki wa vyombo vingi kwa vijana na wanawake.",
      badge: "Empowerment",
      progress: 0
    }
  ],

  // Verified Opportunities from Database
  opportunities: [
    {
      id: 1,
      title: "Commercial EV Driver Fleet Recruitment",
      titleSw: "Nafasi za Madereva wa Bajaji za Umeme (Full-Time)",
      category: "Employment",
      provider: "EcoMobility Fleet Tanzania",
      deadline: "30 Desemba 2026",
      location: "Dar es Salaam (Kinondoni & Ilala)",
      eligibility: "Madereva wenye leseni daraja A/A2/B, kitambulisho cha TEVDA, uzoefu zaidi ya mwaka 1.",
      type: "Ajira Rasmi"
    },
    {
      id: 2,
      title: "Clean Energy Green Financing (Low-Interest Loan)",
      titleSw: "Mkopo wa Nafuu wa Kununua Bajaji au Pikipiki ya Umeme",
      category: "Vehicle Financing",
      provider: "CRDB & Tanzania E-Mobility Fund",
      deadline: "15 Januari 2027",
      location: "Nchi Nzima (Tanzania)",
      eligibility: "Mwanachama kamili wa TEVDA aliyehitimu kozi ya Financial Literacy.",
      type: "Ufadhili wa Chombo"
    },
    {
      id: 3,
      title: "Women & Youth EV Operator Empowerment Grant",
      titleSw: "Ruzuku ya Uwezeshaji Madereva Wanawake na Vijana",
      category: "Grants",
      provider: "UNDP & TEVDA National Council",
      deadline: "20 Desemba 2026",
      location: "Mwanza, Dodoma na Arusha",
      eligibility: "Wanawake na vijana chini ya miaka 30 waliojiunga na mafunzo ya TEVDA.",
      type: "Ruzuku Isiyorejeshwa"
    },
    {
      id: 4,
      title: "OEM Battery & Spare Parts Supply Partnership",
      titleSw: "Vipuri Halisi vya EV kwa Bei ya Punguzo ya Wanachama",
      category: "Spare Parts",
      provider: "Ampersand & Spiro Certified Hubs",
      deadline: "Inaendelea",
      location: "Dar es Salaam, Morogoro",
      eligibility: "Onyesha kadi ya kidijitali (Smart ID) ya TEVDA kupata punguzo la 15%.",
      type: "Punguzo la Vipuri"
    }
  ],

  // Payment Providers
  paymentProviders: [
    { id: "mpesa", name: "M-Pesa Lipa Namba", operator: "Paybill: 500200", color: "#e11d48", tag: "Vodacom" },
    { id: "tigopesa", name: "Tigo Pesa", operator: "Lipa kwa Control Number", color: "#2563eb", tag: "Mixx by Yas" },
    { id: "airtel", name: "Airtel Money", operator: "Control Number / Lipa", color: "#dc2626", tag: "Airtel" },
    { id: "halopesa", name: "HaloPesa", operator: "Control Number", color: "#f59e0b", tag: "Halotel" },
    { id: "banks", name: "CRDB / NMB Bank", operator: "A/C: 0150829104000 (TEVDA HQ)", color: "#0f766e", tag: "Bank Transfer" }
  ],

  // Whistleblower Incident Categories
  complaintCategories: [
    { id: "extortion", title: "Extortion / Rushwa", desc: "Kudaiwa fedha haramu vituoni au barabarani", icon: "shield" },
    { id: "police_harassment", title: "Police Harassment", desc: "Unyanyasaji au ukaguzi usio halali", icon: "police" },
    { id: "battery_theft", title: "Battery Theft", desc: "Wizi wa betri au udanganyifu wa swap", icon: "battery" },
    { id: "unsafe_charging", title: "Unsafe Charging", desc: "Miundombinu mibovu, shoti au hatari ya moto", icon: "plug" },
    { id: "vehicle_defect", title: "Vehicle Defects", desc: "Hitilafu za kiwanda na warranty", icon: "tool" },
    { id: "other", title: "Changamoto Nyingine", desc: "Mizozo au malalamiko mengine", icon: "alert" }
  ],

  // Member Registered Vehicles
  vehicles: [
    {
      id: 1,
      type: "Electric Bajaji",
      make: "Ampersand",
      model: "E-Trike 2025",
      plate: "T 482 EVM",
      batteryKwh: 12.0,
      charging: "Battery Swap",
      dailyKm: 85,
      ownership: "Driver-Operated (Lease-to-Own)",
      status: "LATRA Verified",
      isPrimary: true
    },
    {
      id: 2,
      type: "Electric Bodaboda",
      make: "Spiro",
      model: "Equator Max",
      plate: "MC 918 EVT",
      batteryKwh: 4.5,
      charging: "Battery Swap",
      dailyKm: 110,
      ownership: "Owned",
      status: "LATRA Verified",
      isPrimary: false
    }
  ],

  // System Notifications
  notifications: [
    {
      id: "notif-1",
      title: "Uthibitisho wa Kitambulisho cha Kidijitali",
      summary: "Kitambulisho chako cha kidijitali (TEVDA-2026-000128) kimeidhinishwa rasmi na kiko tayari kutumika.",
      body: "Habari Ndugu Amani Juma,\n\nTunapenda kukutaarifu kuwa uhakiki wa taarifa zako na usajili wa chombo chako cha moto (Electric Bajaji, Namba T 482 EVM) umekamilika rasmi na Ofisi Kuu ya TEVDA.\n\nKitambulisho chako cha Kidijitali (Digital Smart ID) sasa ni hai (Active) kikiwa na QR Code inayotambulika kisheria na Mamlaka ya Udhibiti Usafiri Ardhini (LATRA) pamoja na Jeshi la Polisi Kitengo cha Usalama Barabarani.\n\nUnaweza kuonyesha kitambulisho hiki moja kwa moja kwenye simu yako au kupakua cheti rasmi cha uanachama kupitia sehemu ya 'Digital Smart ID'.",
      category: "Kitambulisho",
      time: "Dakika 10 zilizopita",
      icon: "🪪",
      isImportant: true,
      isRead: false,
      actionLabel: "Tazama Kitambulisho Changu →",
      targetScreen: "smart_id"
    },
    {
      id: "notif-2",
      title: "Ratiba ya Mafunzo ya Usalama wa Betri",
      summary: "Kikao cha mafunzo ya vitendo ya usalama wa betri za EV kimepangwa kufanyika 28 Nov 2026 saa 3:00 asubuhi.",
      body: "Ndugu Dereva,\n\nTEVDA kwa kushirikiana na wadau wa nishati safi inakualika kwenye mafunzo maalum ya vitendo ya 'EV Battery Safety & High-Voltage Maintenance'.\n\n📅 Tarehe: 28 Novemba 2026\n⏰ Muda: Saa 3:00 Asubuhi - 6:00 Mchana\n📍 Eneo: Kituo cha TEVDA, Sinza Mori, Dar es Salaam\n\nMada Kuu zitakazofundishwa:\n1. Mbinu sahihi za kuchaji betri wakati wa mvua au unyevu mwingi.\n2. Kuzuia na kugundua mapema dalili za joto kali (Thermal Runaway).\n3. Hatua salama za kuchukua wakati wa dharura ya hitilafu ya umeme barabarani.\n\nWashiriki wote watapata cheti cha usalama (Safety Certificate).",
      category: "Mafunzo",
      time: "Saa 2 zilizopita",
      icon: "🎓",
      isImportant: true,
      isRead: false,
      actionLabel: "Fungua Moduli za Mafunzo →",
      targetScreen: "academy"
    },
    {
      id: "notif-3",
      title: "Kumbukumbu ya Namba ya Malipo (Control Number)",
      summary: "Control Number 994821730561 inasubiri uhakiki wa ada ya uanachama.",
      body: "Ndugu Mwanachama,\n\nMalipo yako kupitia namba ya malipo (Control Number) 994821730561 yanashughulikiwa na mfumo wa fedha.\n\nKumbuka kuwa ada rasmi ya uanachama inathibitishwa na Baraza Kuu la TEVDA. Pindi malipo yanapothibitishwa, utapokea risiti rasmi na kitambulisho chako kitahuishwa mara moja.",
      category: "Malipo",
      time: "Jana, 14:45",
      icon: "🧾",
      isImportant: false,
      isRead: true,
      actionLabel: "Tazama Sehemu ya Malipo →",
      targetScreen: "payment"
    },
    {
      id: "notif-4",
      title: "Ilani ya Usalama: Vituo vya Kuchaji Umeme",
      summary: "Ukaguzi wa vituo vya kuchaji unaendelea Kinondoni na Ilala. Ripoti changamoto yoyote.",
      body: "TAARIFA KWA MADEREVA WOTE:\n\nKamati ya Ulinzi na Usalama ya TEVDA inaendelea na zoezi la ukaguzi wa vituo vya kuchajia vyombo vya moto katika Wilaya za Kinondoni na Ilala.\n\nTafadhali tumia sehemu ya 'Ripoti Tukio' kuripoti mara moja.",
      category: "Tahadhari",
      time: "Siku 2 zilizopita",
      icon: "⚠️",
      isImportant: false,
      isRead: true,
      actionLabel: "Toa Ripoti ya Kituo →",
      targetScreen: "report_issue"
    }
  ]
};
