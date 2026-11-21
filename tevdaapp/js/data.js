/**
 * TEVDA Mobile Application Data Store
 * Contains Tanzania geographic hierarchy, sample driver profile,
 * categories, services, courses, and payment providers.
 */

window.TEVDA_DATA = {
  // Current active driver profile (matching the UI design mockup exactly)
  currentMember: {
    fullName: "Amani Juma",
    greetingName: "Amani",
    memberNo: "TEVDA-2026-000128",
    status: "ACTIVE MEMBER",
    statusCode: "approved",
    phone: "+255 712 345 678",
    email: "amani.juma@gmail.com",
    gender: "male",
    vehicleType: "ELECTRIC BAJAJI",
    vehicleReg: "T 482 EVM",
    vehicleMake: "Taifa Electric Trike 2025",
    drivingLicence: "TZA-DL-749102",
    licenceClass: "C3",
    nidaNumber: "19940812-14102-00001-42",
    region: "Dar es Salaam",
    district: "Kinondoni",
    ward: "Sinza",
    operatingStage: "Sinza Mori / Kituo cha Shekilango",
    validThrough: "VALID THROUGH 2027",
    joinedDate: "15 Jan 2026",
    photoUrl: "https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=400&auto=format&fit=crop&q=80",
    qrPayload: "https://tevda.or.tz/verify/membership?query=TEVDA-2026-000128"
  },

  // Ongoing registration state (Step 1 -> Step 2 -> Step 3)
  registrationDraft: {
    fullName: "",
    phone: "",
    region: "",
    district: "",
    ward: "",
    operatingStage: "",
    category: "Full Member (Dereva wa Chombo)",
    amount: "TZS 15,000",
    controlNumber: "482173056194",
    paymentStatus: "awaiting_payment",
    drivingLicence: "",
    vehicleType: "three_wheeler",
    vehiclePlate: ""
  },

  // Tanzania Regions with Districts & Common Wards
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
        },
        {
          id: "arumeru",
          name: "Arumeru",
          wards: ["Usa River", "King'ori", "Majengo", "Kikwe"]
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
        },
        {
          id: "ilemela",
          name: "Ilemela",
          wards: ["Kirumba", "Nyamanoro", "Buswelu", "Kawekamo"]
        }
      ]
    },
    {
      id: "tanga",
      name: "Tanga",
      districts: [
        {
          id: "tanga_city",
          name: "Tanga City",
          wards: ["Majengo", "Chumbageni", "Ngamiani", "Usagara"]
        }
      ]
    },
    {
      id: "zanzibar",
      name: "Zanzibar (Mjini Magharibi)",
      districts: [
        {
          id: "mjini",
          name: "Mjini",
          wards: ["Malindi", "Shangani", "Mkunazini", "Kikwajuni"]
        }
      ]
    }
  ],

  // Membership Categories
  categories: [
    { id: "full", name: "Full Member (Commercial EV Driver)", fee: 15000 },
    { id: "associate", name: "Associate Member (EV Tech / Engineer)", fee: 15000 },
    { id: "fleet", name: "Fleet Owner / Operator", fee: 50000 },
    { id: "institutional", name: "Institutional & Partner", fee: 100000 }
  ],

  // Payment Providers
  paymentProviders: [
    { id: "mpesa", name: "M-Pesa", operator: "Vodacom / Safaricom", color: "#e11d48", tag: "Vodacom" },
    { id: "tigopesa", name: "Tigo Pesa", operator: "Mixx by Yas", color: "#2563eb", tag: "Tigo" },
    { id: "airtel", name: "Airtel Money", operator: "Airtel Tanzania", color: "#dc2626", tag: "Airtel" },
    { id: "halopesa", name: "HaloPesa", operator: "Halotel", color: "#f59e0b", tag: "Halotel" },
    { id: "banks", name: "Banks", operator: "CRDB, NMB, KCB, NBC", color: "#0f766e", tag: "Bank Transfer" }
  ],

  // Grow with TEVDA (Services & Training)
  services: [
    {
      id: "battery_safety",
      title: "EV Battery Safety",
      description: "Learn safe battery handling, thermal runaway prevention, and charging protocols.",
      badge: "Accredited",
      actionText: "Start Course →",
      instructor: "Eng. Hamisi Bakari (EV Certified)",
      duration: "4 Hours (Self-Paced / Workshop)",
      cert: "TEVDA Battery Handling Certificate"
    },
    {
      id: "high_voltage",
      title: "High-Voltage Maintenance",
      description: "Get certified in high-voltage wiring, inverter diagnostics, and motor troubleshooting.",
      badge: "Technical",
      actionText: "Learn More →",
      instructor: "Eng. Frank Mrosso",
      duration: "2 Weeks Intensive Cohort",
      cert: "Certified EV Technical Specialist"
    },
    {
      id: "green_jobs",
      title: "Green Mobility Jobs",
      description: "Find verified EV related job opportunities, charging hub gigs, and corporate fleets.",
      badge: "Opportunities",
      actionText: "Browse Jobs →",
      openings: "18 Active Fleet Openings in Dar es Salaam"
    },
    {
      id: "financing",
      title: "Vehicle Financing",
      description: "Access subsidized battery-as-a-service and clean asset financing with low interest.",
      badge: "Finance",
      actionText: "Apply Now →",
      partners: "CRDB Bank, NMB Bank, E-Mobility Green Fund"
    }
  ],

  // Grievance / Whistleblower Categories
  complaintCategories: [
    {
      id: "extortion",
      title: "Extortion",
      desc: "Illegal payment demands at stages or intersections",
      icon: "mask"
    },
    {
      id: "police_harassment",
      title: "Police Harassment",
      desc: "Unjustified impoundment or arbitrary harassment",
      icon: "police"
    },
    {
      id: "battery_theft",
      title: "Battery Theft",
      desc: "Stolen EV batteries, cells or swapping fraud",
      icon: "battery"
    },
    {
      id: "unsafe_charging",
      title: "Unsafe Charging",
      desc: "Faulty infrastructure, overvoltage or hazards",
      icon: "plug"
    }
  ],

  // System & Official Notifications
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
      targetScreen: "services"
    },
    {
      id: "notif-3",
      title: "Risiti ya Malipo ya Ada ya Uanachama",
      summary: "Malipo yako ya TZS 15,000 kupitia Control Number 482173056194 yamethibitishwa.",
      body: "Ndugu Mwanachama,\n\nMalipo yako ya Ada ya Usajili na Uanachama ya TZS 15,000/= kupitia namba ya malipo (Control Number) 482173056194 yamethibitishwa na kuingizwa kwenye mfumo mkuu wa TEVDA.\n\n• Kumbukumbu ya Muamala: TXN-EV-9402816\n• Kiasi: TZS 15,000\n• Njia ya Malipo: M-Pesa / Tigo Pesa Gateway\n• Hali: IMELIPWA KIKAMILIFU (PAID)\n\nAsante kwa kuchangia ukuaji wa umoja wa madereva wa vyombo vya umeme Tanzania!",
      category: "Malipo",
      time: "Jana, 14:45",
      icon: "💳",
      isImportant: false,
      isRead: true,
      actionLabel: "Tazama Sehemu ya Malipo →",
      targetScreen: "register_payment"
    },
    {
      id: "notif-4",
      title: "Ilani ya Usalama: Vituo vya Kuchaji Umeme",
      summary: "Ukaguzi wa vituo vya kuchaji unaendelea Kinondoni na Ilala. Ripoti changamoto yoyote.",
      body: "TAARIFA KWA MADEREVA WOTE:\n\nKamati ya Ulinzi na Usalama ya TEVDA inaendelea na zoezi la ukaguzi wa vituo vya kuchajia vyombo vya moto katika Wilaya za Kinondoni na Ilala.\n\nIwapo unakutana na:\n• Vituo vinavyotoza viwango holela vilivyo kinyume na makubaliano.\n• Waya zilizo wazi au plugs zenye cheche wakati wa kuchaji.\n• Unyanyasaji au vituo visivyo na vifaa vya kuzimia moto.\n\nTafadhali tumia sehemu ya 'Report an Issue' ndani ya mfumo huu kuripoti mara moja ili jopo la uokoaji lichukue hatua za haraka.",
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
