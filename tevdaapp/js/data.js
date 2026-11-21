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
  ]
};
