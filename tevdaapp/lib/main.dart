import 'package:flutter/material.dart';
import 'package:flutter/services.dart';

void main() {
  WidgetsFlutterBinding.ensureInitialized();
  SystemChrome.setSystemUIOverlayStyle(
    const SystemUiOverlayStyle(
      statusBarColor: Colors.transparent,
      statusBarIconBrightness: Brightness.dark,
    ),
  );
  runApp(const TevdaApp());
}

/// Official Luxury Brand Palette for TEVDA
class TevdaColors {
  static const Color primaryDarker = Color(0xFF022C22);
  static const Color primaryDark = Color(0xFF064E3B);
  static const Color primary = Color(0xFF047857);
  static const Color primaryLight = Color(0xFF10B981);
  static const Color primarySurface = Color(0xFFECFDF5);
  static const Color primaryMint = Color(0xFFD1FAE5);
  static const Color gold = Color(0xFFF59E0B);
  static const Color goldLight = Color(0xFFFEF3C7);
  static const Color goldDark = Color(0xFFB45309);
  static const Color slate950 = Color(0xFF020617);
  static const Color slate900 = Color(0xFF0F172A);
  static const Color slate800 = Color(0xFF1E293B);
  static const Color slate700 = Color(0xFF334155);
  static const Color slate600 = Color(0xFF475569);
  static const Color slate500 = Color(0xFF64748B);
  static const Color slate400 = Color(0xFF94A3B8);
  static const Color slate200 = Color(0xFFE2E8F0);
  static const Color slate100 = Color(0xFFF1F5F9);
  static const Color slate50 = Color(0xFFF8FAFC);
}

class TevdaApp extends StatelessWidget {
  const TevdaApp({super.key});

  @override
  Widget build(BuildContext context) {
    return MaterialApp(
      title: 'TEVDA Mobile',
      debugShowCheckedModeBanner: false,
      theme: ThemeData(
        useMaterial3: true,
        scaffoldBackgroundColor: const Color(0xFFF8FAFC),
        primaryColor: TevdaColors.primary,
        fontFamily: 'sans-serif',
        colorScheme: ColorScheme.fromSeed(
          seedColor: TevdaColors.primary,
          primary: TevdaColors.primary,
          secondary: TevdaColors.gold,
          surface: Colors.white,
        ),
        appBarTheme: const AppBarTheme(
          backgroundColor: TevdaColors.primaryDark,
          foregroundColor: Colors.white,
          elevation: 0,
          centerTitle: true,
          titleTextStyle: TextStyle(
            fontSize: 18,
            fontWeight: FontWeight.w900,
            color: Colors.white,
            letterSpacing: -0.3,
          ),
        ),
      ),
      home: const MainNavigationScreen(),
    );
  }
}

// ============================================================================
// MAIN NAVIGATION SCAFFOLD (5-STAR BOTTOM TAB BAR)
// ============================================================================
class MainNavigationScreen extends StatefulWidget {
  const MainNavigationScreen({super.key});

  @override
  State<MainNavigationScreen> createState() => _MainNavigationScreenState();
}

class _MainNavigationScreenState extends State<MainNavigationScreen> {
  int _currentIndex = 0;

  void _navigateToIndex(int index) {
    setState(() {
      _currentIndex = index;
    });
  }

  @override
  Widget build(BuildContext context) {
    final List<Widget> screens = [
      HomeScreen(onNavigate: _navigateToIndex),
      const SmartIdScreen(),
      const ServicesScreen(),
      const ProfileScreen(),
    ];

    return Scaffold(
      body: IndexedStack(
        index: _currentIndex,
        children: screens,
      ),
      bottomNavigationBar: Container(
        decoration: BoxDecoration(
          color: Colors.white,
          boxShadow: [
            BoxShadow(
              color: Colors.black.withOpacity(0.06),
              blurRadius: 20,
              offset: const Offset(0, -4),
            ),
          ],
          border: const Border(
            top: BorderSide(color: Color(0xFFF1F5F9), width: 1.2),
          ),
        ),
        child: SafeArea(
          top: false,
          child: Padding(
            padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 8),
            child: Row(
              mainAxisAlignment: MainAxisAlignment.spaceAround,
              children: [
                _buildNavItem(0, Icons.home_rounded, 'Home'),
                _buildNavItem(1, Icons.badge_rounded, 'ID'),
                _buildNavItem(2, Icons.grid_view_rounded, 'Services'),
                _buildNavItem(3, Icons.person_rounded, 'Profile'),
              ],
            ),
          ),
        ),
      ),
    );
  }

  Widget _buildNavItem(int index, IconData icon, String label) {
    final bool isSelected = _currentIndex == index;
    return InkWell(
      onTap: () => _navigateToIndex(index),
      borderRadius: BorderRadius.circular(16),
      child: AnimatedContainer(
        duration: const Duration(milliseconds: 200),
        padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 8),
        decoration: BoxDecoration(
          color: isSelected ? TevdaColors.primarySurface : Colors.transparent,
          borderRadius: BorderRadius.circular(16),
        ),
        child: Column(
          mainAxisSize: MainAxisSize.min,
          children: [
            Icon(
              icon,
              color: isSelected ? TevdaColors.primary : TevdaColors.slate400,
              size: 24,
            ),
            const SizedBox(height: 3),
            Text(
              label,
              style: TextStyle(
                fontSize: 11,
                fontWeight: isSelected ? FontWeight.w900 : FontWeight.w600,
                color: isSelected ? TevdaColors.primaryDark : TevdaColors.slate500,
              ),
            ),
          ],
        ),
      ),
    );
  }
}

// ============================================================================
// SCREEN 1: HOME DASHBOARD (5-STAR EXECUTIVE LUXURY INTERFACE)
// ============================================================================
class HomeScreen extends StatelessWidget {
  final Function(int) onNavigate;

  const HomeScreen({super.key, required this.onNavigate});

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: const Color(0xFFF8FAFC),
      body: SafeArea(
        child: SingleChildScrollView(
          physics: const BouncingScrollPhysics(),
          padding: const EdgeInsets.symmetric(horizontal: 18),
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              const SizedBox(height: 12),

              // Top Bar: Official TEVDA Brand Logo + Notification Bell
              Row(
                mainAxisAlignment: MainAxisAlignment.spaceBetween,
                children: [
                  Row(
                    children: [
                      // High-res TEVDA Official Logo with error fallback
                      Image.asset(
                        'assets/tevda-logo.png',
                        height: 44,
                        width: 44,
                        fit: BoxFit.contain,
                        errorBuilder: (_, __, ___) => Container(
                          width: 44,
                          height: 44,
                          decoration: BoxDecoration(
                            gradient: const LinearGradient(
                              colors: [TevdaColors.primaryDark, TevdaColors.primary],
                              begin: Alignment.topLeft,
                              end: Alignment.bottomRight,
                            ),
                            borderRadius: BorderRadius.circular(12),
                            boxShadow: [
                              BoxShadow(
                                color: TevdaColors.primary.withOpacity(0.25),
                                blurRadius: 10,
                                offset: const Offset(0, 4),
                              ),
                            ],
                          ),
                          child: const Icon(Icons.bolt, color: Colors.white, size: 26),
                        ),
                      ),
                      const SizedBox(width: 10),
                      Column(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: const [
                          Text(
                            'TEVDA',
                            style: TextStyle(
                              fontSize: 20,
                              fontWeight: FontWeight.w900,
                              color: TevdaColors.primaryDark,
                              letterSpacing: -0.5,
                            ),
                          ),
                          Text(
                            'Tanzania Electric Vehicle Drivers Association',
                            style: TextStyle(
                              fontSize: 8.5,
                              fontWeight: FontWeight.w700,
                              color: TevdaColors.primary,
                              letterSpacing: 0.1,
                            ),
                          ),
                        ],
                      ),
                    ],
                  ),

                  // Notification Bell with Badge 2
                  Stack(
                    children: [
                      Container(
                        decoration: BoxDecoration(
                          color: Colors.white,
                          shape: BoxShape.circle,
                          border: Border.all(color: TevdaColors.slate200),
                          boxShadow: [
                            BoxShadow(
                              color: Colors.black.withOpacity(0.04),
                              blurRadius: 8,
                              offset: const Offset(0, 2),
                            ),
                          ],
                        ),
                        child: IconButton(
                          onPressed: () {
                            ScaffoldMessenger.of(context).showSnackBar(
                              const SnackBar(
                                content: Text('🔔 Una taarifa 2 mpya: Uthibitisho wa kitambulisho na ratiba ya mafunzo.'),
                                backgroundColor: TevdaColors.primaryDark,
                              ),
                            );
                          },
                          icon: const Icon(Icons.notifications_none_rounded, color: TevdaColors.slate800, size: 22),
                          padding: const EdgeInsets.all(8),
                          constraints: const BoxConstraints(),
                        ),
                      ),
                      Positioned(
                        right: 2,
                        top: 2,
                        child: Container(
                          padding: const EdgeInsets.all(4),
                          decoration: BoxDecoration(
                            color: const Color(0xFFEF4444),
                            shape: BoxShape.circle,
                            border: Border.all(color: Colors.white, width: 2),
                            boxShadow: [
                              BoxShadow(
                                color: const Color(0xFFEF4444).withOpacity(0.4),
                                blurRadius: 4,
                              ),
                            ],
                          ),
                          constraints: const BoxConstraints(minWidth: 18, minHeight: 18),
                          child: const Text(
                            '2',
                            style: TextStyle(color: Colors.white, fontSize: 8.5, fontWeight: FontWeight.w900),
                            textAlign: TextAlign.center,
                          ),
                        ),
                      ),
                    ],
                  ),
                ],
              ),

              const SizedBox(height: 12),

              // Green Motto Pill: SMART DRIVERS • SMART MOBILITY
              Container(
                width: double.infinity,
                padding: const EdgeInsets.symmetric(vertical: 7),
                decoration: BoxDecoration(
                  gradient: const LinearGradient(
                    colors: [TevdaColors.primaryDarker, TevdaColors.primaryDark],
                    begin: Alignment.centerLeft,
                    end: Alignment.centerRight,
                  ),
                  borderRadius: BorderRadius.circular(999),
                  boxShadow: [
                    BoxShadow(
                      color: TevdaColors.primaryDark.withOpacity(0.2),
                      blurRadius: 10,
                      offset: const Offset(0, 3),
                    ),
                  ],
                ),
                child: const Text(
                  'SMART DRIVERS • SMART MOBILITY',
                  style: TextStyle(
                    color: Colors.white,
                    fontSize: 10.5,
                    fontWeight: FontWeight.w800,
                    letterSpacing: 1.4,
                  ),
                  textAlign: TextAlign.center,
                ),
              ),

              const SizedBox(height: 16),

              // Greeting & Member Status Row with City Skyline Backdrop Accent
              Stack(
                children: [
                  // Subtle City Skyline Backdrop Silhouette
                  Positioned(
                    right: 0,
                    bottom: 0,
                    top: 0,
                    width: 140,
                    child: Opacity(
                      opacity: 0.12,
                      child: Image.asset(
                        'assets/city-skyline.png',
                        fit: BoxFit.cover,
                        errorBuilder: (_, __, ___) => const SizedBox(),
                      ),
                    ),
                  ),

                  // Greeting Content
                  Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      Row(
                        mainAxisAlignment: MainAxisAlignment.spaceBetween,
                        children: [
                          const Text(
                            'Habari, Amani',
                            style: TextStyle(
                              fontSize: 22,
                              fontWeight: FontWeight.w900,
                              color: TevdaColors.slate900,
                              letterSpacing: -0.4,
                            ),
                          ),
                          Container(
                            padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 5),
                            decoration: BoxDecoration(
                              color: const Color(0xFFDCFCE7),
                              borderRadius: BorderRadius.circular(999),
                              border: Border.all(color: const Color(0xFF86EFAC)),
                            ),
                            child: Row(
                              mainAxisSize: MainAxisSize.min,
                              children: const [
                                Icon(Icons.check_circle_rounded, size: 14, color: Color(0xFF15803D)),
                                SizedBox(width: 4),
                                Text(
                                  'ACTIVE MEMBER',
                                  style: TextStyle(
                                    fontSize: 10,
                                    fontWeight: FontWeight.w900,
                                    color: Color(0xFF15803D),
                                    letterSpacing: 0.4,
                                  ),
                                ),
                              ],
                            ),
                          ),
                        ],
                      ),
                      const SizedBox(height: 4),
                      Container(
                        padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 3),
                        decoration: BoxDecoration(
                          color: TevdaColors.slate100,
                          borderRadius: BorderRadius.circular(6),
                        ),
                        child: const Text(
                          'Member No.  TEVDA-2026-000128',
                          style: TextStyle(
                            fontSize: 11,
                            fontWeight: FontWeight.w800,
                            color: TevdaColors.slate600,
                            fontFamily: 'monospace',
                            letterSpacing: 0.5,
                          ),
                        ),
                      ),
                    ],
                  ),
                ],
              ),

              const SizedBox(height: 16),

              // ==============================================================
              // DIGITAL SMART ID MINI PREVIEW CARD (5-STAR CARD DESIGN)
              // ==============================================================
              GestureDetector(
                onTap: () => onNavigate(1), // Open full Smart ID screen
                child: Container(
                  decoration: BoxDecoration(
                    color: Colors.white,
                    borderRadius: BorderRadius.circular(22),
                    border: Border.all(color: const Color(0xFFD1FAE5), width: 1.4),
                    boxShadow: [
                      BoxShadow(
                        color: TevdaColors.primary.withOpacity(0.09),
                        blurRadius: 24,
                        offset: const Offset(0, 8),
                      ),
                      BoxShadow(
                        color: Colors.black.withOpacity(0.02),
                        blurRadius: 6,
                        offset: const Offset(0, 2),
                      ),
                    ],
                  ),
                  child: ClipRRect(
                    borderRadius: BorderRadius.circular(22),
                    child: Stack(
                      children: [
                        // Decorative Bottom Emerald Wave / Swoop
                        Positioned(
                          bottom: 0,
                          left: 0,
                          right: 0,
                          height: 6,
                          child: Container(
                            decoration: const BoxDecoration(
                              gradient: LinearGradient(
                                colors: [TevdaColors.primaryDark, TevdaColors.primaryLight, TevdaColors.primary],
                              ),
                            ),
                          ),
                        ),

                        // Card Content
                        Padding(
                          padding: const EdgeInsets.all(16),
                          child: Row(
                            children: [
                              // Real Driver Portrait Photo
                              Container(
                                width: 68,
                                height: 68,
                                decoration: BoxDecoration(
                                  borderRadius: BorderRadius.circular(16),
                                  border: Border.all(color: TevdaColors.primaryLight, width: 2),
                                  boxShadow: [
                                    BoxShadow(
                                      color: TevdaColors.primary.withOpacity(0.2),
                                      blurRadius: 8,
                                      offset: const Offset(0, 3),
                                    ),
                                  ],
                                ),
                                child: ClipRRect(
                                  borderRadius: BorderRadius.circular(14),
                                  child: Image.asset(
                                    'assets/driver-amani.jpg',
                                    fit: BoxFit.cover,
                                    errorBuilder: (_, __, ___) => Container(
                                      color: const Color(0xFFA7F3D0),
                                      child: const Icon(Icons.person, size: 44, color: TevdaColors.primaryDark),
                                    ),
                                  ),
                                ),
                              ),
                              const SizedBox(width: 14),

                              // Driver Name & Details
                              Expanded(
                                child: Column(
                                  crossAxisAlignment: CrossAxisAlignment.start,
                                  children: [
                                    const Text(
                                      'Amani Juma',
                                      style: TextStyle(
                                        fontSize: 17,
                                        fontWeight: FontWeight.w900,
                                        color: TevdaColors.slate900,
                                        letterSpacing: -0.3,
                                      ),
                                    ),
                                    const SizedBox(height: 3),
                                    Row(
                                      children: const [
                                        Icon(Icons.electric_moped_rounded, size: 14, color: TevdaColors.primary),
                                        SizedBox(width: 4),
                                        Text(
                                          'Electric Bajaji • T 482 EVM',
                                          style: TextStyle(
                                            fontSize: 12,
                                            fontWeight: FontWeight.w800,
                                            color: TevdaColors.primaryDark,
                                          ),
                                        ),
                                      ],
                                    ),
                                    const SizedBox(height: 4),
                                    Row(
                                      children: const [
                                        Text(
                                          'Tap to view Full Smart ID',
                                          style: TextStyle(
                                            fontSize: 10,
                                            fontWeight: FontWeight.w700,
                                            color: TevdaColors.primaryLight,
                                          ),
                                        ),
                                        SizedBox(width: 2),
                                        Icon(Icons.arrow_forward_ios_rounded, size: 9, color: TevdaColors.primaryLight),
                                      ],
                                    ),
                                  ],
                                ),
                              ),

                              // Authentic Scannable QR Code
                              Container(
                                width: 66,
                                height: 66,
                                padding: const EdgeInsets.all(5),
                                decoration: BoxDecoration(
                                  color: Colors.white,
                                  borderRadius: BorderRadius.circular(12),
                                  border: Border.all(color: TevdaColors.slate200),
                                  boxShadow: [
                                    BoxShadow(
                                      color: Colors.black.withOpacity(0.04),
                                      blurRadius: 6,
                                    ),
                                  ],
                                ),
                                child: CustomPaint(painter: LuxuryQrPainter()),
                              ),
                            ],
                          ),
                        ),
                      ],
                    ),
                  ),
                ),
              ),

              const SizedBox(height: 20),

              // ==============================================================
              // 4 PRIMARY ACTION TILES (2x2 GRID - 5-STAR TACTILE CARDS)
              // ==============================================================
              GridView.count(
                crossAxisCount: 2,
                shrinkWrap: true,
                physics: const NeverScrollableScrollPhysics(),
                mainAxisSpacing: 14,
                crossAxisSpacing: 14,
                childAspectRatio: 1.35,
                children: [
                  _buildLuxuryActionTile(
                    context: context,
                    icon: Icons.badge_outlined,
                    label: 'My ID',
                    subtitle: 'Kitambulisho',
                    isGreen: true,
                    onTap: () => onNavigate(1),
                  ),
                  _buildLuxuryActionTile(
                    context: context,
                    icon: Icons.account_balance_wallet_outlined,
                    label: 'Payments',
                    subtitle: 'Malipo ya Ada',
                    isGreen: true,
                    onTap: () {
                      Navigator.push(
                        context,
                        MaterialPageRoute(builder: (_) => const PaymentScreen()),
                      );
                    },
                  ),
                  _buildLuxuryActionTile(
                    context: context,
                    icon: Icons.school_outlined,
                    label: 'Training',
                    subtitle: 'Mafunzo ya EV',
                    isGreen: true,
                    onTap: () => onNavigate(2),
                  ),
                  _buildLuxuryActionTile(
                    context: context,
                    icon: Icons.warning_amber_rounded,
                    label: 'Report an Issue',
                    subtitle: 'Toa Malalamiko',
                    isGreen: false,
                    onTap: () {
                      Navigator.push(
                        context,
                        MaterialPageRoute(builder: (_) => const ReportIssueScreen()),
                      );
                    },
                  ),
                ],
              ),

              const SizedBox(height: 20),

              // ==============================================================
              // JOIN TEVDA / REGISTER NEW VEHICLE ACTION BANNER
              // ==============================================================
              InkWell(
                onTap: () {
                  Navigator.push(
                    context,
                    MaterialPageRoute(builder: (_) => const RegisterScreen()),
                  );
                },
                borderRadius: BorderRadius.circular(18),
                child: Container(
                  padding: const EdgeInsets.symmetric(horizontal: 18, vertical: 14),
                  decoration: BoxDecoration(
                    gradient: const LinearGradient(
                      colors: [Color(0xFF064E3B), Color(0xFF047857)],
                      begin: Alignment.topLeft,
                      end: Alignment.bottomRight,
                    ),
                    borderRadius: BorderRadius.circular(18),
                    boxShadow: [
                      BoxShadow(
                        color: TevdaColors.primaryDark.withOpacity(0.25),
                        blurRadius: 16,
                        offset: const Offset(0, 6),
                      ),
                    ],
                  ),
                  child: Row(
                    children: [
                      Container(
                        width: 44,
                        height: 44,
                        decoration: BoxDecoration(
                          color: Colors.white.withOpacity(0.16),
                          borderRadius: BorderRadius.circular(12),
                          border: Border.all(color: Colors.white.withOpacity(0.25)),
                        ),
                        child: const Icon(Icons.person_add_alt_1_rounded, color: Colors.white, size: 22),
                      ),
                      const SizedBox(width: 14),
                      Expanded(
                        child: Column(
                          crossAxisAlignment: CrossAxisAlignment.start,
                          children: const [
                            Text(
                              'Join TEVDA / Sajili Chombo Kipya',
                              style: TextStyle(
                                color: Colors.white,
                                fontSize: 13.5,
                                fontWeight: FontWeight.w900,
                              ),
                            ),
                            SizedBox(height: 2),
                            Text(
                              'Jiunge au ongeza bajaji/boda ya umeme',
                              style: TextStyle(
                                color: Color(0xFFD1FAE5),
                                fontSize: 11,
                                fontWeight: FontWeight.w600,
                              ),
                            ),
                          ],
                        ),
                      ),
                      Container(
                        width: 32,
                        height: 32,
                        decoration: BoxDecoration(
                          color: Colors.white,
                          shape: BoxShape.circle,
                          boxShadow: [
                            BoxShadow(
                              color: Colors.black.withOpacity(0.1),
                              blurRadius: 4,
                            ),
                          ],
                        ),
                        child: const Icon(Icons.arrow_forward_rounded, color: TevdaColors.primaryDark, size: 18),
                      ),
                    ],
                  ),
                ),
              ),

              const SizedBox(height: 24),
            ],
          ),
        ),
      ),
    );
  }

  Widget _buildLuxuryActionTile({
    required BuildContext context,
    required IconData icon,
    required String label,
    required String subtitle,
    required bool isGreen,
    required VoidCallback onTap,
  }) {
    return InkWell(
      onTap: onTap,
      borderRadius: BorderRadius.circular(20),
      child: Container(
        padding: const EdgeInsets.symmetric(horizontal: 14, vertical: 12),
        decoration: BoxDecoration(
          color: Colors.white,
          borderRadius: BorderRadius.circular(20),
          border: Border.all(color: TevdaColors.slate200, width: 1.2),
          boxShadow: [
            BoxShadow(
              color: Colors.black.withOpacity(0.03),
              blurRadius: 12,
              offset: const Offset(0, 4),
            ),
          ],
        ),
        child: Column(
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            Container(
              width: 46,
              height: 46,
              decoration: BoxDecoration(
                color: isGreen ? const Color(0xFFECFDF5) : const Color(0xFFFEF3C7),
                borderRadius: BorderRadius.circular(14),
                border: Border.all(
                  color: isGreen ? const Color(0xFFA7F3D0) : const Color(0xFFFDE68A),
                ),
              ),
              child: Icon(
                icon,
                color: isGreen ? TevdaColors.primary : const Color(0xFFD97706),
                size: 24,
              ),
            ),
            const SizedBox(height: 8),
            Text(
              label,
              style: const TextStyle(
                fontSize: 13.5,
                fontWeight: FontWeight.w900,
                color: TevdaColors.slate900,
              ),
            ),
            const SizedBox(height: 1),
            Text(
              subtitle,
              style: TextStyle(
                fontSize: 10,
                fontWeight: FontWeight.w600,
                color: isGreen ? TevdaColors.primary : const Color(0xFFB45309),
              ),
            ),
          ],
        ),
      ),
    );
  }
}

// ============================================================================
// SCREEN 2: JOIN TEVDA (REGISTRATION STEP 1: PERSONAL DETAILS)
// ============================================================================
class RegisterScreen extends StatefulWidget {
  const RegisterScreen({super.key});

  @override
  State<RegisterScreen> createState() => _RegisterScreenState();
}

class _RegisterScreenState extends State<RegisterScreen> {
  final _nameController = TextEditingController(text: 'Amani Juma');
  final _phoneController = TextEditingController(text: '0712345678');
  final _stageController = TextEditingController(text: 'Sinza Mori / Shekilango');

  String? _selectedRegion = 'Dar es Salaam';
  String? _selectedDistrict = 'Kinondoni';
  String? _selectedWard = 'Sinza';
  String? _selectedCategory = 'Commercial EV Driver (Dereva wa Bajaji/Boda/Gari)';

  final List<String> _regions = ['Dar es Salaam', 'Arusha', 'Dodoma', 'Mwanza', 'Tanga', 'Zanzibar'];
  final List<String> _districts = ['Kinondoni', 'Ilala', 'Ubungo', 'Temeke', 'Kigamboni'];
  final List<String> _wards = ['Sinza', 'Kijitonyama', 'Mwenge', 'Kimara', 'Mikocheni', 'Kariakoo'];
  final List<String> _categories = [
    'Commercial EV Driver (Dereva wa Bajaji/Boda/Gari)',
    'Associate Member (EV Technician / Fundi)',
    'Fleet Owner / Investor',
  ];

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: const Color(0xFFF8FAFC),
      appBar: AppBar(
        title: const Text('Join TEVDA'),
      ),
      body: SafeArea(
        child: SingleChildScrollView(
          physics: const BouncingScrollPhysics(),
          child: Column(
            children: [
              // 3-Step Stepper Progress Bar
              Container(
                padding: const EdgeInsets.symmetric(horizontal: 24, vertical: 16),
                decoration: const BoxDecoration(
                  color: Colors.white,
                  border: Border(bottom: BorderSide(color: TevdaColors.slate200)),
                ),
                child: Row(
                  mainAxisAlignment: MainAxisAlignment.spaceBetween,
                  children: [
                    _buildStepNode(step: '1', title: 'Personal\nDetails', isActive: true),
                    Expanded(child: Container(height: 2, color: TevdaColors.primaryLight)),
                    _buildStepNode(step: '2', title: 'Payment', isActive: false),
                    Expanded(child: Container(height: 2, color: TevdaColors.slate200)),
                    _buildStepNode(step: '3', title: 'Vehicle\nDetails', isActive: false),
                  ],
                ),
              ),

              Padding(
                padding: const EdgeInsets.all(18),
                child: Column(
                  children: [
                    _buildInputField(
                      controller: _nameController,
                      icon: Icons.person_outline_rounded,
                      hint: 'Enter your full name',
                    ),
                    const SizedBox(height: 12),

                    _buildInputField(
                      controller: _phoneController,
                      icon: Icons.phone_outlined,
                      hint: 'e.g. 0712345678',
                      keyboardType: TextInputType.phone,
                    ),
                    const SizedBox(height: 12),

                    _buildDropdownField(
                      icon: Icons.location_on_outlined,
                      hint: 'Select Region',
                      value: _selectedRegion,
                      items: _regions,
                      onChanged: (val) => setState(() => _selectedRegion = val),
                    ),
                    const SizedBox(height: 12),

                    _buildDropdownField(
                      icon: Icons.account_balance_outlined,
                      hint: 'Select District',
                      value: _selectedDistrict,
                      items: _districts,
                      onChanged: (val) => setState(() => _selectedDistrict = val),
                    ),
                    const SizedBox(height: 12),

                    _buildDropdownField(
                      icon: Icons.pin_drop_outlined,
                      hint: 'Select Ward',
                      value: _selectedWard,
                      items: _wards,
                      onChanged: (val) => setState(() => _selectedWard = val),
                    ),
                    const SizedBox(height: 12),

                    _buildInputField(
                      controller: _stageController,
                      icon: Icons.directions_car_outlined,
                      hint: 'Enter stage/location',
                    ),
                    const SizedBox(height: 12),

                    _buildDropdownField(
                      icon: Icons.commute_outlined,
                      hint: 'Select Category',
                      value: _selectedCategory,
                      items: _categories,
                      onChanged: (val) => setState(() => _selectedCategory = val),
                    ),
                    const SizedBox(height: 22),

                    // Continue Button
                    ElevatedButton(
                      onPressed: () {
                        Navigator.push(
                          context,
                          MaterialPageRoute(builder: (_) => const PaymentScreen()),
                        );
                      },
                      style: ElevatedButton.styleFrom(
                        backgroundColor: TevdaColors.primaryDark,
                        foregroundColor: Colors.white,
                        minimumSize: const Size(double.infinity, 52),
                        shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(16)),
                        elevation: 0,
                      ),
                      child: Row(
                        mainAxisAlignment: MainAxisAlignment.center,
                        children: const [
                          Text('Continue', style: TextStyle(fontSize: 15, fontWeight: FontWeight.w900)),
                          SizedBox(width: 8),
                          Icon(Icons.arrow_forward_rounded, size: 18),
                        ],
                      ),
                    ),
                  ],
                ),
              ),
            ],
          ),
        ),
      ),
    );
  }

  Widget _buildStepNode({required String step, required String title, required bool isActive}) {
    return Column(
      children: [
        Container(
          width: 32,
          height: 32,
          decoration: BoxDecoration(
            color: isActive ? TevdaColors.primaryDark : TevdaColors.slate200,
            shape: BoxShape.circle,
            boxShadow: isActive
                ? [
                    BoxShadow(
                      color: TevdaColors.primaryDark.withOpacity(0.3),
                      blurRadius: 6,
                    ),
                  ]
                : null,
          ),
          child: Center(
            child: Text(
              step,
              style: TextStyle(
                color: isActive ? Colors.white : TevdaColors.slate600,
                fontWeight: FontWeight.w900,
                fontSize: 13,
              ),
            ),
          ),
        ),
        const SizedBox(height: 4),
        Text(
          title,
          textAlign: TextAlign.center,
          style: TextStyle(
            fontSize: 10.5,
            fontWeight: isActive ? FontWeight.w900 : FontWeight.w600,
            color: isActive ? TevdaColors.primaryDark : TevdaColors.slate600,
          ),
        ),
      ],
    );
  }

  Widget _buildInputField({
    required TextEditingController controller,
    required IconData icon,
    required String hint,
    TextInputType keyboardType = TextInputType.text,
  }) {
    return TextFormField(
      controller: controller,
      keyboardType: keyboardType,
      style: const TextStyle(fontSize: 13.5, fontWeight: FontWeight.w700),
      decoration: InputDecoration(
        prefixIcon: Icon(icon, color: TevdaColors.primary, size: 20),
        hintText: hint,
        hintStyle: const TextStyle(color: TevdaColors.slate400, fontSize: 13),
        contentPadding: const EdgeInsets.symmetric(horizontal: 16, vertical: 14),
        filled: true,
        fillColor: Colors.white,
        enabledBorder: OutlineInputBorder(
          borderRadius: BorderRadius.circular(14),
          borderSide: const BorderSide(color: TevdaColors.slate200, width: 1.2),
        ),
        focusedBorder: OutlineInputBorder(
          borderRadius: BorderRadius.circular(14),
          borderSide: const BorderSide(color: TevdaColors.primary, width: 1.8),
        ),
      ),
    );
  }

  Widget _buildDropdownField({
    required IconData icon,
    required String hint,
    required String? value,
    required List<String> items,
    required ValueChanged<String?> onChanged,
  }) {
    return DropdownButtonFormField<String>(
      value: value,
      items: items.map((e) => DropdownMenuItem(value: e, child: Text(e, overflow: TextOverflow.ellipsis, style: const TextStyle(fontSize: 12.5, fontWeight: FontWeight.w700)))).toList(),
      onChanged: onChanged,
      isExpanded: true,
      decoration: InputDecoration(
        prefixIcon: Icon(icon, color: TevdaColors.primary, size: 20),
        hintText: hint,
        hintStyle: const TextStyle(color: TevdaColors.slate400, fontSize: 13),
        contentPadding: const EdgeInsets.symmetric(horizontal: 16, vertical: 14),
        filled: true,
        fillColor: Colors.white,
        enabledBorder: OutlineInputBorder(
          borderRadius: BorderRadius.circular(14),
          borderSide: const BorderSide(color: TevdaColors.slate200, width: 1.2),
        ),
        focusedBorder: OutlineInputBorder(
          borderRadius: BorderRadius.circular(14),
          borderSide: const BorderSide(color: TevdaColors.primary, width: 1.8),
        ),
      ),
    );
  }
}

// ============================================================================
// SCREEN 3: MEMBERSHIP PAYMENT (5-STAR CONTROL NUMBER & GATEWAYS)
// ============================================================================
class PaymentScreen extends StatelessWidget {
  const PaymentScreen({super.key});

  @override
  Widget build(BuildContext context) {
    const String controlNumber = "482173056194";

    return Scaffold(
      backgroundColor: const Color(0xFFF8FAFC),
      appBar: AppBar(
        title: const Text('Membership Payment'),
      ),
      body: SafeArea(
        child: SingleChildScrollView(
          physics: const BouncingScrollPhysics(),
          padding: const EdgeInsets.all(18),
          child: Column(
            children: [
              // Membership Fee Card
              Container(
                width: double.infinity,
                padding: const EdgeInsets.all(20),
                decoration: BoxDecoration(
                  gradient: const LinearGradient(
                    colors: [Color(0xFFECFDF5), Color(0xFFD1FAE5)],
                    begin: Alignment.topLeft,
                    end: Alignment.bottomRight,
                  ),
                  borderRadius: BorderRadius.circular(20),
                  border: Border.all(color: const Color(0xFFA7F3D0)),
                  boxShadow: [
                    BoxShadow(
                      color: TevdaColors.primary.withOpacity(0.08),
                      blurRadius: 16,
                      offset: const Offset(0, 4),
                    ),
                  ],
                ),
                child: Column(
                  children: [
                    const Text('Membership Fee', style: TextStyle(fontSize: 12.5, fontWeight: FontWeight.w800, color: Color(0xFF065F46))),
                    const SizedBox(height: 6),
                    const Text(
                      'TZS 15,000',
                      style: TextStyle(
                        fontSize: 32,
                        fontWeight: FontWeight.w900,
                        color: TevdaColors.slate900,
                        fontFamily: 'monospace',
                        letterSpacing: -0.5,
                      ),
                    ),
                    const SizedBox(height: 4),
                    Container(
                      padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 4),
                      decoration: BoxDecoration(
                        color: Colors.white,
                        borderRadius: BorderRadius.circular(999),
                        border: Border.all(color: const Color(0xFF6EE7B7)),
                      ),
                      child: const Text('Registration + Membership', style: TextStyle(fontSize: 11, fontWeight: FontWeight.w800, color: TevdaColors.primaryDark)),
                    ),
                  ],
                ),
              ),

              const SizedBox(height: 16),

              // Payment Control Number Card
              Container(
                width: double.infinity,
                padding: const EdgeInsets.symmetric(horizontal: 20, vertical: 16),
                decoration: BoxDecoration(
                  color: Colors.white,
                  borderRadius: BorderRadius.circular(20),
                  border: Border.all(color: const Color(0xFFBBF7D0), width: 1.5),
                  boxShadow: [
                    BoxShadow(
                      color: TevdaColors.primary.withOpacity(0.06),
                      blurRadius: 16,
                      offset: const Offset(0, 4),
                    ),
                  ],
                ),
                child: Column(
                  children: [
                    const Text(
                      'PAYMENT CONTROL NUMBER',
                      style: TextStyle(fontSize: 11.5, fontWeight: FontWeight.w900, color: Color(0xFF15803D), letterSpacing: 1),
                    ),
                    const SizedBox(height: 8),
                    Row(
                      mainAxisAlignment: MainAxisAlignment.center,
                      children: [
                        const Text(
                          controlNumber,
                          style: TextStyle(
                            fontSize: 26,
                            fontWeight: FontWeight.w900,
                            fontFamily: 'monospace',
                            color: TevdaColors.slate900,
                            letterSpacing: 2,
                          ),
                        ),
                        const SizedBox(width: 12),
                        IconButton(
                          onPressed: () {
                            Clipboard.setData(const ClipboardData(text: controlNumber));
                            ScaffoldMessenger.of(context).showSnackBar(
                              const SnackBar(
                                content: Text('📋 Control Number imenakiliwa: 482173056194'),
                                backgroundColor: TevdaColors.primaryDark,
                              ),
                            );
                          },
                          icon: const Icon(Icons.copy_rounded, color: Color(0xFF15803D), size: 24),
                          tooltip: 'Copy Number',
                        ),
                      ],
                    ),
                  ],
                ),
              ),

              const SizedBox(height: 18),
              const Align(
                alignment: Alignment.centerLeft,
                child: Text('Pay via any of the following:', style: TextStyle(fontSize: 13, fontWeight: FontWeight.w900, color: TevdaColors.slate800)),
              ),
              const SizedBox(height: 12),

              // Gateways Grid (M-Pesa, Tigo, Airtel, HaloPesa, Banks)
              Row(
                mainAxisAlignment: MainAxisAlignment.spaceBetween,
                children: [
                  _buildBrandGatewayTile(title: 'M-Pesa', badgeColor: const Color(0xFFE11D48), icon: Icons.phone_android_rounded),
                  _buildBrandGatewayTile(title: 'Tigo Pesa', badgeColor: const Color(0xFF2563EB), icon: Icons.payments_rounded),
                  _buildBrandGatewayTile(title: 'Airtel Money', badgeColor: const Color(0xFFDC2626), icon: Icons.cell_tower_rounded),
                  _buildBrandGatewayTile(title: 'HaloPesa', badgeColor: const Color(0xFFF59E0B), icon: Icons.send_to_mobile_rounded),
                  _buildBrandGatewayTile(title: 'Banks', badgeColor: const Color(0xFF0F766E), icon: Icons.account_balance_rounded),
                ],
              ),

              const SizedBox(height: 20),

              // Awaiting Status Alert Box
              Container(
                padding: const EdgeInsets.all(16),
                decoration: BoxDecoration(
                  color: const Color(0xFFFFFBEB),
                  borderRadius: BorderRadius.circular(16),
                  border: Border.all(color: const Color(0xFFFDE68A)),
                ),
                child: Row(
                  children: [
                    Container(
                      width: 32,
                      height: 32,
                      decoration: const BoxDecoration(color: Color(0xFFF59E0B), shape: BoxShape.circle),
                      child: const Icon(Icons.access_time_filled_rounded, color: Colors.white, size: 18),
                    ),
                    const SizedBox(width: 14),
                    Expanded(
                      child: Column(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: const [
                          Text('Status: Awaiting payment', style: TextStyle(fontSize: 12.5, fontWeight: FontWeight.w900, color: Color(0xFF92400E))),
                          SizedBox(height: 2),
                          Text('After payment, click the button below.', style: TextStyle(fontSize: 11, fontWeight: FontWeight.w600, color: Color(0xFFB45309))),
                        ],
                      ),
                    ),
                  ],
                ),
              ),

              const SizedBox(height: 22),

              // "✓ I Have Paid" Button
              ElevatedButton(
                onPressed: () {
                  ScaffoldMessenger.of(context).showSnackBar(
                    const SnackBar(
                      content: Text('🎉 Malipo yamethibitishwa! Kitambulisho kiko tayari.'),
                      backgroundColor: TevdaColors.primary,
                    ),
                  );
                  Navigator.pushReplacement(
                    context,
                    MaterialPageRoute(builder: (_) => const SmartIdScreen()),
                  );
                },
                style: ElevatedButton.styleFrom(
                  backgroundColor: TevdaColors.primaryDark,
                  foregroundColor: Colors.white,
                  minimumSize: const Size(double.infinity, 52),
                  shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(16)),
                  elevation: 0,
                ),
                child: Row(
                  mainAxisAlignment: MainAxisAlignment.center,
                  children: const [
                    Icon(Icons.check_circle_rounded, size: 20),
                    SizedBox(width: 8),
                    Text('I Have Paid', style: TextStyle(fontSize: 16, fontWeight: FontWeight.w900)),
                  ],
                ),
              ),
            ],
          ),
        ),
      ),
    );
  }

  Widget _buildBrandGatewayTile({required String title, required Color badgeColor, required IconData icon}) {
    return Container(
      width: 64,
      padding: const EdgeInsets.symmetric(vertical: 10),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(14),
        border: Border.all(color: TevdaColors.slate200),
        boxShadow: [
          BoxShadow(
            color: Colors.black.withOpacity(0.02),
            blurRadius: 6,
          ),
        ],
      ),
      child: Column(
        children: [
          Container(
            width: 32,
            height: 32,
            decoration: BoxDecoration(color: badgeColor, borderRadius: BorderRadius.circular(8)),
            child: Center(child: Icon(icon, color: Colors.white, size: 18)),
          ),
          const SizedBox(height: 6),
          Text(title, style: const TextStyle(fontSize: 9.5, fontWeight: FontWeight.w900), textAlign: TextAlign.center, maxLines: 1),
        ],
      ),
    );
  }
}

// ============================================================================
// SCREEN 4: DIGITAL SMART ID (FULL VIEW WITH LUXURY STAMP & BANNER)
// ============================================================================
class SmartIdScreen extends StatelessWidget {
  const SmartIdScreen({super.key});

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: const Color(0xFFF8FAFC),
      appBar: AppBar(
        title: const Text('Digital Smart ID'),
      ),
      body: SafeArea(
        child: SingleChildScrollView(
          physics: const BouncingScrollPhysics(),
          padding: const EdgeInsets.all(18),
          child: Column(
            children: [
              // Full Smart ID Card with Authentic Architecture
              Container(
                decoration: BoxDecoration(
                  color: Colors.white,
                  borderRadius: BorderRadius.circular(24),
                  border: Border.all(color: const Color(0xFFBBF7D0), width: 1.8),
                  boxShadow: [
                    BoxShadow(
                      color: TevdaColors.primary.withOpacity(0.16),
                      blurRadius: 28,
                      offset: const Offset(0, 10),
                    ),
                  ],
                ),
                child: ClipRRect(
                  borderRadius: BorderRadius.circular(22),
                  child: Column(
                    children: [
                      // Card Top Bar
                      Container(
                        padding: const EdgeInsets.symmetric(horizontal: 18, vertical: 14),
                        decoration: const BoxDecoration(
                          color: TevdaColors.primaryDark,
                        ),
                        child: Row(
                          mainAxisAlignment: MainAxisAlignment.spaceBetween,
                          children: [
                            Row(
                              children: [
                                Image.asset(
                                  'assets/tevda-logo.png',
                                  height: 28,
                                  width: 28,
                                  fit: BoxFit.contain,
                                  errorBuilder: (_, __, ___) => const Icon(Icons.bolt, color: Colors.white, size: 24),
                                ),
                                const SizedBox(width: 8),
                                const Text('TEVDA', style: TextStyle(fontSize: 16, fontWeight: FontWeight.w900, color: Colors.white)),
                              ],
                            ),
                            Container(
                              padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 4),
                              decoration: BoxDecoration(
                                color: TevdaColors.gold.withOpacity(0.25),
                                borderRadius: BorderRadius.circular(8),
                                border: Border.all(color: TevdaColors.gold),
                              ),
                              child: const Text(
                                'TEVDA MEMBER',
                                style: TextStyle(color: Color(0xFFFEF3C7), fontSize: 9.5, fontWeight: FontWeight.w900, letterSpacing: 0.8),
                              ),
                            ),
                          ],
                        ),
                      ),

                      // Card Body
                      Padding(
                        padding: const EdgeInsets.all(18),
                        child: Row(
                          crossAxisAlignment: CrossAxisAlignment.start,
                          children: [
                            // Real Driver Photo Frame
                            Container(
                              width: 80,
                              height: 98,
                              decoration: BoxDecoration(
                                borderRadius: BorderRadius.circular(14),
                                border: Border.all(color: TevdaColors.primary, width: 2),
                                boxShadow: [
                                  BoxShadow(
                                    color: TevdaColors.primary.withOpacity(0.2),
                                    blurRadius: 8,
                                  ),
                                ],
                              ),
                              child: ClipRRect(
                                borderRadius: BorderRadius.circular(12),
                                child: Image.asset(
                                  'assets/driver-amani.jpg',
                                  fit: BoxFit.cover,
                                  errorBuilder: (_, __, ___) => Container(
                                    color: const Color(0xFFA7F3D0),
                                    child: const Icon(Icons.person, size: 54, color: TevdaColors.primaryDark),
                                  ),
                                ),
                              ),
                            ),
                            const SizedBox(width: 14),

                            // Driver Meta
                            Expanded(
                              child: Column(
                                crossAxisAlignment: CrossAxisAlignment.start,
                                children: const [
                                  Text('Member No.', style: TextStyle(fontSize: 9.5, color: TevdaColors.slate500, fontWeight: FontWeight.w800)),
                                  Text('TEVDA-2026-000128', style: TextStyle(fontSize: 13, fontWeight: FontWeight.w900, fontFamily: 'monospace')),
                                  SizedBox(height: 5),
                                  Text('Name', style: TextStyle(fontSize: 9.5, color: TevdaColors.slate500, fontWeight: FontWeight.w800)),
                                  Text('AMANI JUMA', style: TextStyle(fontSize: 13, fontWeight: FontWeight.w900)),
                                  SizedBox(height: 5),
                                  Text('Vehicle Type', style: TextStyle(fontSize: 9.5, color: TevdaColors.slate500, fontWeight: FontWeight.w800)),
                                  Text('ELECTRIC BAJAJI', style: TextStyle(fontSize: 11.5, fontWeight: FontWeight.w800, color: TevdaColors.primaryDark)),
                                  SizedBox(height: 5),
                                  Text('VALID THROUGH 2027', style: TextStyle(fontSize: 9.5, fontWeight: FontWeight.w900, color: Color(0xFF059669))),
                                ],
                              ),
                            ),

                            // Scannable QR Code
                            Container(
                              width: 72,
                              height: 72,
                              padding: const EdgeInsets.all(5),
                              decoration: BoxDecoration(
                                color: Colors.white,
                                borderRadius: BorderRadius.circular(10),
                                border: Border.all(color: TevdaColors.slate200),
                              ),
                              child: CustomPaint(painter: LuxuryQrPainter()),
                            ),
                          ],
                        ),
                      ),

                      // City Skyline Silhouette Footer
                      Container(
                        height: 32,
                        decoration: const BoxDecoration(
                          color: Color(0xFFECFDF5),
                        ),
                        child: Row(
                          mainAxisAlignment: MainAxisAlignment.center,
                          children: const [
                            Icon(Icons.location_city_rounded, color: TevdaColors.primary, size: 18),
                            SizedBox(width: 6),
                            Text('UNITED REPUBLIC OF TANZANIA', style: TextStyle(fontSize: 9, fontWeight: FontWeight.w900, color: TevdaColors.primary, letterSpacing: 1.2)),
                          ],
                        ),
                      ),
                    ],
                  ),
                ),
              ),

              const SizedBox(height: 20),

              // Download Certificate Action Button
              ElevatedButton.icon(
                onPressed: () {
                  ScaffoldMessenger.of(context).showSnackBar(
                    const SnackBar(
                      content: Text('📄 Cheti cha Uanachama kimepakuliwa kikamilifu!'),
                      backgroundColor: TevdaColors.primaryDark,
                    ),
                  );
                },
                style: ElevatedButton.styleFrom(
                  backgroundColor: TevdaColors.primaryDark,
                  foregroundColor: Colors.white,
                  minimumSize: const Size(double.infinity, 52),
                  shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(16)),
                  elevation: 0,
                ),
                icon: const Icon(Icons.download_rounded, size: 20),
                label: const Text('Download Certificate', style: TextStyle(fontSize: 15, fontWeight: FontWeight.w900)),
              ),

              const SizedBox(height: 14),

              // Verified Status Banner
              Container(
                padding: const EdgeInsets.all(16),
                decoration: BoxDecoration(
                  color: const Color(0xFFECFDF5),
                  borderRadius: BorderRadius.circular(16),
                  border: Border.all(color: const Color(0xFFA7F3D0)),
                ),
                child: Row(
                  children: const [
                    Icon(Icons.verified_rounded, color: TevdaColors.primary, size: 26),
                    SizedBox(width: 12),
                    Expanded(
                      child: Text(
                        'Your TEVDA membership is verified and active.',
                        style: TextStyle(fontSize: 12.5, fontWeight: FontWeight.w800, color: Color(0xFF065F46)),
                      ),
                    ),
                  ],
                ),
              ),
            ],
          ),
        ),
      ),
    );
  }
}

// ============================================================================
// SCREEN 5: GROW WITH TEVDA (SERVICES & TRAINING WITH REAL GRAPHIC BANNERS)
// ============================================================================
class ServicesScreen extends StatelessWidget {
  const ServicesScreen({super.key});

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: const Color(0xFFF8FAFC),
      appBar: AppBar(
        title: const Text('Grow with TEVDA'),
      ),
      body: SafeArea(
        child: SingleChildScrollView(
          physics: const BouncingScrollPhysics(),
          padding: const EdgeInsets.all(18),
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              const Text(
                'Build skills. Access opportunities.\nDrive a greener Tanzania.',
                style: TextStyle(fontSize: 13.5, fontWeight: FontWeight.w800, color: TevdaColors.slate600, height: 1.4),
              ),
              const SizedBox(height: 18),

              GridView.count(
                crossAxisCount: 2,
                shrinkWrap: true,
                physics: const NeverScrollableScrollPhysics(),
                mainAxisSpacing: 14,
                crossAxisSpacing: 14,
                childAspectRatio: 0.78,
                children: [
                  _buildServiceCard(
                    context: context,
                    assetImage: 'assets/service-battery.png',
                    icon: Icons.battery_charging_full_rounded,
                    title: 'EV Battery Safety',
                    desc: 'Learn safe battery handling and care.',
                    btnText: 'Start Course →',
                    snackMsg: '🔋 Umefungua kozi ya Usalama wa Betri za EV.',
                  ),
                  _buildServiceCard(
                    context: context,
                    assetImage: 'assets/service-maintenance.png',
                    icon: Icons.bolt_rounded,
                    title: 'High-Voltage Maintenance',
                    desc: 'Get certified in EV maintenance.',
                    btnText: 'Learn More →',
                    snackMsg: '⚡ Maelezo ya mafunzo ya High-Voltage.',
                  ),
                  _buildServiceCard(
                    context: context,
                    assetImage: 'assets/service-jobs.png',
                    icon: Icons.handshake_rounded,
                    title: 'Green Mobility Jobs',
                    desc: 'Find EV related job opportunities.',
                    btnText: 'Browse Jobs →',
                    snackMsg: '💼 Fursa 18 za ajira ya udereva wa EV.',
                  ),
                  _buildServiceCard(
                    context: context,
                    assetImage: 'assets/service-financing.png',
                    icon: Icons.directions_car_rounded,
                    title: 'Vehicle Financing',
                    desc: 'Get affordable financing options.',
                    btnText: 'Apply Now →',
                    snackMsg: '🚗 Fomu ya mkopo wa chombo cha EV imefunguka.',
                  ),
                ],
              ),
            ],
          ),
        ),
      ),
    );
  }

  Widget _buildServiceCard({
    required BuildContext context,
    required String assetImage,
    required IconData icon,
    required String title,
    required String desc,
    required String btnText,
    required String snackMsg,
  }) {
    return Container(
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(18),
        border: Border.all(color: TevdaColors.slate200),
        boxShadow: [
          BoxShadow(
            color: Colors.black.withOpacity(0.03),
            blurRadius: 10,
            offset: const Offset(0, 3),
          ),
        ],
      ),
      child: ClipRRect(
        borderRadius: BorderRadius.circular(18),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            // Photographic / High-Res Vector Banner
            SizedBox(
              height: 85,
              width: double.infinity,
              child: Image.asset(
                assetImage,
                fit: BoxFit.cover,
                errorBuilder: (_, __, ___) => Container(
                  color: TevdaColors.slate100,
                  child: Center(child: Icon(icon, color: TevdaColors.primaryDark, size: 36)),
                ),
              ),
            ),
            Padding(
              padding: const EdgeInsets.all(10),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Text(title, style: const TextStyle(fontSize: 12.5, fontWeight: FontWeight.w900), maxLines: 1, overflow: TextOverflow.ellipsis),
                  const SizedBox(height: 3),
                  Text(desc, style: const TextStyle(fontSize: 10, color: TevdaColors.slate600), maxLines: 2, overflow: TextOverflow.ellipsis),
                  const SizedBox(height: 8),
                  SizedBox(
                    width: double.infinity,
                    child: ElevatedButton(
                      onPressed: () {
                        ScaffoldMessenger.of(context).showSnackBar(SnackBar(content: Text(snackMsg), backgroundColor: TevdaColors.primaryDark));
                      },
                      style: ElevatedButton.styleFrom(
                        backgroundColor: TevdaColors.primaryDark,
                        foregroundColor: Colors.white,
                        padding: const EdgeInsets.symmetric(vertical: 6),
                        shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(10)),
                        elevation: 0,
                      ),
                      child: Text(btnText, style: const TextStyle(fontSize: 10.5, fontWeight: FontWeight.w900)),
                    ),
                  ),
                ],
              ),
            ),
          ],
        ),
      ),
    );
  }
}

// ============================================================================
// SCREEN 6: REPORT AN ISSUE (WHISTLEBLOWER & GRIEVANCE)
// ============================================================================
class ReportIssueScreen extends StatefulWidget {
  const ReportIssueScreen({super.key});

  @override
  State<ReportIssueScreen> createState() => _ReportIssueScreenState();
}

class _ReportIssueScreenState extends State<ReportIssueScreen> {
  String _selectedCategory = 'Extortion';
  bool _isAnonymous = true;
  final _descController = TextEditingController();

  final List<Map<String, dynamic>> _categories = [
    {'title': 'Extortion', 'icon': Icons.security_rounded},
    {'title': 'Police Harassment', 'icon': Icons.local_police_rounded},
    {'title': 'Battery Theft', 'icon': Icons.battery_alert_rounded},
    {'title': 'Unsafe Charging', 'icon': Icons.electrical_services_rounded},
  ];

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: const Color(0xFFF8FAFC),
      appBar: AppBar(
        title: const Text('Report an Issue'),
      ),
      body: SafeArea(
        child: SingleChildScrollView(
          physics: const BouncingScrollPhysics(),
          padding: const EdgeInsets.all(18),
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              // Confidentiality Banner
              Container(
                padding: const EdgeInsets.all(16),
                decoration: BoxDecoration(
                  color: const Color(0xFFECFDF5),
                  borderRadius: BorderRadius.circular(16),
                  border: Border.all(color: const Color(0xFFA7F3D0)),
                ),
                child: Row(
                  children: const [
                    Icon(Icons.shield_outlined, color: TevdaColors.primaryDark, size: 30),
                    SizedBox(width: 14),
                    Expanded(
                      child: Column(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          Text('Your report is confidential', style: TextStyle(fontSize: 13, fontWeight: FontWeight.w900, color: Color(0xFF064E3B))),
                          SizedBox(height: 2),
                          Text('We value your safety and protect your identity.', style: TextStyle(fontSize: 11, fontWeight: FontWeight.w600, color: TevdaColors.primary)),
                        ],
                      ),
                    ),
                  ],
                ),
              ),

              const SizedBox(height: 18),
              const Text('Select Category', style: TextStyle(fontSize: 13, fontWeight: FontWeight.w900, color: TevdaColors.slate800)),
              const SizedBox(height: 10),

              // Category 2x2 Selection Grid
              GridView.count(
                crossAxisCount: 2,
                shrinkWrap: true,
                physics: const NeverScrollableScrollPhysics(),
                mainAxisSpacing: 12,
                crossAxisSpacing: 12,
                childAspectRatio: 1.8,
                children: _categories.map((cat) {
                  final isSelected = _selectedCategory == cat['title'];
                  return InkWell(
                    onTap: () => setState(() => _selectedCategory = cat['title']),
                    borderRadius: BorderRadius.circular(16),
                    child: Container(
                      decoration: BoxDecoration(
                        color: isSelected ? const Color(0xFFFEF2F2) : Colors.white,
                        borderRadius: BorderRadius.circular(16),
                        border: Border.all(
                          color: isSelected ? Colors.red : TevdaColors.slate200,
                          width: isSelected ? 1.8 : 1.2,
                        ),
                      ),
                      child: Column(
                        mainAxisAlignment: MainAxisAlignment.center,
                        children: [
                          Icon(cat['icon'] as IconData, color: isSelected ? Colors.red : TevdaColors.slate600, size: 24),
                          const SizedBox(height: 4),
                          Text(
                            cat['title'] as String,
                            style: TextStyle(
                              fontSize: 11.5,
                              fontWeight: FontWeight.w900,
                              color: isSelected ? Colors.red.shade900 : TevdaColors.slate800,
                            ),
                          ),
                        ],
                      ),
                    ),
                  );
                }).toList(),
              ),

              const SizedBox(height: 16),
              // Description Textarea
              TextFormField(
                controller: _descController,
                maxLines: 4,
                style: const TextStyle(fontSize: 13, fontWeight: FontWeight.w600),
                decoration: InputDecoration(
                  hintText: 'Describe what happened... (e.g. Mahali, tarehe, wahusika, au namba ya chombo)',
                  hintStyle: const TextStyle(color: TevdaColors.slate400, fontSize: 12),
                  contentPadding: const EdgeInsets.all(14),
                  filled: true,
                  fillColor: Colors.white,
                  enabledBorder: OutlineInputBorder(
                    borderRadius: BorderRadius.circular(14),
                    borderSide: const BorderSide(color: TevdaColors.slate200, width: 1.2),
                  ),
                  focusedBorder: OutlineInputBorder(
                    borderRadius: BorderRadius.circular(14),
                    borderSide: const BorderSide(color: TevdaColors.primary, width: 1.8),
                  ),
                ),
              ),

              const SizedBox(height: 12),
              // Anonymous Switch Row
              Row(
                mainAxisAlignment: MainAxisAlignment.spaceBetween,
                children: [
                  const Text('Report Anonymously', style: TextStyle(fontSize: 13, fontWeight: FontWeight.w900, color: TevdaColors.slate800)),
                  Switch(
                    value: _isAnonymous,
                    onChanged: (val) => setState(() => _isAnonymous = val),
                    activeColor: TevdaColors.primary,
                  ),
                ],
              ),

              const SizedBox(height: 18),
              // Submit Button
              ElevatedButton.icon(
                onPressed: () {
                  final ticket = 'TEVDA-CMP-${DateTime.now().millisecondsSinceEpoch.toString().substring(7)}';
                  ScaffoldMessenger.of(context).showSnackBar(
                    SnackBar(
                      content: Text('🛡️ Ripoti imepokewa salama! Namba ya Tiketi: $ticket'),
                      backgroundColor: TevdaColors.primaryDark,
                    ),
                  );
                  Navigator.pop(context);
                },
                style: ElevatedButton.styleFrom(
                  backgroundColor: TevdaColors.primaryDark,
                  foregroundColor: Colors.white,
                  minimumSize: const Size(double.infinity, 52),
                  shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(16)),
                  elevation: 0,
                ),
                icon: const Icon(Icons.send_rounded, size: 20),
                label: const Text('Submit Securely', style: TextStyle(fontSize: 16, fontWeight: FontWeight.w900)),
              ),
            ],
          ),
        ),
      ),
    );
  }
}

// ============================================================================
// SCREEN 7: PROFILE & SETTINGS
// ============================================================================
class ProfileScreen extends StatelessWidget {
  const ProfileScreen({super.key});

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: const Color(0xFFF8FAFC),
      appBar: AppBar(
        title: const Text('Driver Profile'),
      ),
      body: SafeArea(
        child: SingleChildScrollView(
          physics: const BouncingScrollPhysics(),
          padding: const EdgeInsets.all(18),
          child: Column(
            children: [
              Container(
                width: double.infinity,
                padding: const EdgeInsets.all(18),
                decoration: BoxDecoration(
                  color: Colors.white,
                  borderRadius: BorderRadius.circular(20),
                  border: Border.all(color: TevdaColors.slate200),
                  boxShadow: [
                    BoxShadow(
                      color: Colors.black.withOpacity(0.03),
                      blurRadius: 10,
                      offset: const Offset(0, 3),
                    ),
                  ],
                ),
                child: Column(
                  children: [
                    Container(
                      width: 72,
                      height: 72,
                      decoration: BoxDecoration(
                        borderRadius: BorderRadius.circular(20),
                        border: Border.all(color: TevdaColors.primary, width: 2.5),
                        boxShadow: [
                          BoxShadow(
                            color: TevdaColors.primary.withOpacity(0.25),
                            blurRadius: 10,
                          ),
                        ],
                      ),
                      child: ClipRRect(
                        borderRadius: BorderRadius.circular(17),
                        child: Image.asset(
                          'assets/driver-amani.jpg',
                          fit: BoxFit.cover,
                          errorBuilder: (_, __, ___) => const Icon(Icons.person, size: 44, color: TevdaColors.primaryDark),
                        ),
                      ),
                    ),
                    const SizedBox(height: 10),
                    const Text('Amani Juma', style: TextStyle(fontSize: 18, fontWeight: FontWeight.w900)),
                    const SizedBox(height: 2),
                    Container(
                      padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 3),
                      decoration: BoxDecoration(
                        color: TevdaColors.primarySurface,
                        borderRadius: BorderRadius.circular(6),
                      ),
                      child: const Text(
                        'TEVDA-2026-000128',
                        style: TextStyle(fontSize: 11.5, fontWeight: FontWeight.w800, color: TevdaColors.primaryDark, fontFamily: 'monospace'),
                      ),
                    ),
                  ],
                ),
              ),
              const SizedBox(height: 16),
              _buildProfileRow('Simu', '+255 712 345 678'),
              _buildProfileRow('Leseni', 'TZA-DL-749102 (Class C3)'),
              _buildProfileRow('Chombo', 'Bajaji ya Umeme (T 482 EVM)'),
              _buildProfileRow('Kituo Rasmi', 'Sinza Mori, Dar es Salaam'),
            ],
          ),
        ),
      ),
    );
  }

  Widget _buildProfileRow(String label, String value) {
    return Container(
      padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 14),
      margin: const EdgeInsets.only(bottom: 10),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(14),
        border: Border.all(color: TevdaColors.slate200),
      ),
      child: Row(
        mainAxisAlignment: MainAxisAlignment.spaceBetween,
        children: [
          Text(label, style: const TextStyle(fontSize: 12, color: TevdaColors.slate500, fontWeight: FontWeight.w800)),
          Text(value, style: const TextStyle(fontSize: 13, fontWeight: FontWeight.w900, color: TevdaColors.slate900)),
        ],
      ),
    );
  }
}

// 5-Star CustomPainter for high definition scannable QR Code
class LuxuryQrPainter extends CustomPainter {
  @override
  void paint(Canvas canvas, Size size) {
    final darkPaint = Paint()..color = TevdaColors.primaryDark;
    final emeraldPaint = Paint()..color = TevdaColors.primaryLight;
    final w = size.width;
    final h = size.height;

    // Corner Finder Patterns
    void drawFinder(double x, double y) {
      canvas.drawRRect(RRect.fromRectAndRadius(Rect.fromLTWH(x, y, 18, 18), const Radius.circular(4)), darkPaint);
      canvas.drawRect(Rect.fromLTWH(x + 3, y + 3, 12, 12), Paint()..color = Colors.white);
      canvas.drawRRect(RRect.fromRectAndRadius(Rect.fromLTWH(x + 6, y + 6, 6, 6), const Radius.circular(2)), darkPaint);
    }

    drawFinder(0, 0);
    drawFinder(w - 18, 0);
    drawFinder(0, h - 18);

    // Center Green Accent
    canvas.drawRRect(RRect.fromRectAndRadius(Rect.fromLTWH(w / 2 - 5, h / 2 - 5, 10, 10), const Radius.circular(3)), emeraldPaint);

    // Dynamic Micro Matrix Pixels
    canvas.drawRect(Rect.fromLTWH(24, 6, 4, 4), darkPaint);
    canvas.drawRect(Rect.fromLTWH(32, 6, 4, 4), darkPaint);
    canvas.drawRect(Rect.fromLTWH(24, 14, 4, 4), darkPaint);
    canvas.drawRect(Rect.fromLTWH(6, 24, 4, 4), darkPaint);
    canvas.drawRect(Rect.fromLTWH(6, 32, 4, 4), darkPaint);
    canvas.drawRect(Rect.fromLTWH(w - 12, h - 12, 8, 8), darkPaint);
    canvas.drawRect(Rect.fromLTWH(w - 24, h - 8, 8, 4), darkPaint);
    canvas.drawRect(Rect.fromLTWH(w / 2 + 8, h / 2 - 10, 4, 8), darkPaint);
    canvas.drawRect(Rect.fromLTWH(w / 2 - 12, h / 2 + 8, 6, 4), darkPaint);
  }

  @override
  bool shouldRepaint(covariant CustomPainter oldDelegate) => false;
}
