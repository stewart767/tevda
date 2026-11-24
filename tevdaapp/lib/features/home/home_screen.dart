import 'package:flutter/material.dart';
import '../../core/theme.dart';
import '../../core/constants.dart';
import '../../core/localization.dart';
import '../../core/api_service.dart';
import '../notifications/notifications_screen.dart';
import '../vehicles/vehicles_screen.dart';
import '../payments/payment_screen.dart';
import '../complaints/complaint_screen.dart';
import '../registration/register_screen.dart';

class HomeScreen extends StatefulWidget {
  final Function(int) onNavigate;

  const HomeScreen({super.key, required this.onNavigate});

  @override
  State<HomeScreen> createState() => _HomeScreenState();
}

class _HomeScreenState extends State<HomeScreen> {
  String _memberName = 'Amani Juma';
  String _memberNumber = 'TEVDA-2026-000128';
  String _status = 'ACTIVE MEMBER';
  String _vehicleInfo = 'Electric Bajaji • T 482 EVM';

  @override
  void initState() {
    super.initState();
    _fetchProfile();
  }

  Future<void> _fetchProfile() async {
    try {
      final res = await ApiService.instance.getProfile();
      if (res.success && res.data != null && res.data is Map) {
        final d = res.data as Map;
        setState(() {
          if (d['name'] != null) _memberName = d['name'];
          if (d['member_number'] != null) _memberNumber = d['member_number'];
          if (d['status'] != null) _status = d['status'].toString().toUpperCase();
          if (d['vehicle'] != null) _vehicleInfo = d['vehicle'];
        });
      }
    } catch (_) {}
  }

  @override
  Widget build(BuildContext context) {
    final lang = LanguageController.instance;

    return Scaffold(
      body: SafeArea(
        child: SingleChildScrollView(
          padding: const EdgeInsets.symmetric(horizontal: 20, vertical: 12),
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              // Top Bar: TEVDA Brand + Notification Bell with Badge
              Row(
                mainAxisAlignment: MainAxisAlignment.spaceBetween,
                children: [
                  Row(
                    children: [
                      ClipRRect(
                        borderRadius: BorderRadius.circular(10),
                        child: Image.asset(
                          'assets/tevda-logo.png',
                          width: 44,
                          height: 44,
                          fit: BoxFit.contain,
                        ),
                      ),
                      const SizedBox(width: 10),
                      Column(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: const [
                          Text(
                            'TEVDA',
                            style: TextStyle(
                              fontSize: 18,
                              fontWeight: FontWeight.w900,
                              color: TevdaColors.forestGreen,
                              letterSpacing: 0.8,
                            ),
                          ),
                          Text(
                            'Tanzania Electric Vehicle Drivers Association',
                            style: TextStyle(
                              fontSize: 9.5,
                              color: TevdaColors.slate500,
                              fontWeight: FontWeight.w600,
                            ),
                          ),
                        ],
                      ),
                    ],
                  ),

                  // Notification Bell with Interactive Read Counter
                  AnimatedBuilder(
                    animation: NotificationController.instance,
                    builder: (context, _) {
                      final count = NotificationController.instance.unreadCount;
                      return Stack(
                        clipBehavior: Clip.none,
                        children: [
                          IconButton(
                            icon: const Icon(Icons.notifications_outlined, size: 26, color: TevdaColors.slate800),
                            onPressed: () {
                              Navigator.push(
                                context,
                                MaterialPageRoute(
                                  builder: (_) => NotificationsScreen(onNavigateTab: widget.onNavigate),
                                ),
                              );
                            },
                          ),
                          if (count > 0)
                            Positioned(
                              top: 6,
                              right: 6,
                              child: Container(
                                padding: const EdgeInsets.all(4),
                                decoration: const BoxDecoration(
                                  color: TevdaColors.error,
                                  shape: BoxShape.circle,
                                ),
                                constraints: const BoxConstraints(minWidth: 18, minHeight: 18),
                                child: Text(
                                  '$count',
                                  textAlign: TextAlign.center,
                                  style: const TextStyle(
                                    color: Colors.white,
                                    fontSize: 10,
                                    fontWeight: FontWeight.w900,
                                  ),
                                ),
                              ),
                            ),
                        ],
                      );
                    },
                  ),
                ],
              ),
              const SizedBox(height: 12),

              // Slogan Tagline Banner
              Container(
                width: double.infinity,
                padding: const EdgeInsets.symmetric(vertical: 6),
                decoration: BoxDecoration(
                  color: TevdaColors.forestGreen,
                  borderRadius: BorderRadius.circular(8),
                ),
                child: const Text(
                  'SMART DRIVERS • SMART MOBILITY',
                  textAlign: TextAlign.center,
                  style: TextStyle(
                    color: Colors.white,
                    fontSize: 10,
                    fontWeight: FontWeight.w800,
                    letterSpacing: 1.5,
                  ),
                ),
              ),
              const SizedBox(height: 16),

              // Personalized Greeting & Membership Status
              Row(
                mainAxisAlignment: MainAxisAlignment.spaceBetween,
                crossAxisAlignment: CrossAxisAlignment.center,
                children: [
                  Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      Text(
                        'Habari, $_memberName',
                        style: const TextStyle(
                          fontSize: 22,
                          fontWeight: FontWeight.w900,
                          color: TevdaColors.slate900,
                        ),
                      ),
                      const SizedBox(height: 2),
                      Text(
                        'Member No. $_memberNumber',
                        style: const TextStyle(
                          fontSize: 12,
                          fontWeight: FontWeight.w700,
                          color: TevdaColors.slate500,
                        ),
                      ),
                    ],
                  ),
                  Container(
                    padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 5),
                    decoration: BoxDecoration(
                      color: TevdaColors.primarySurface,
                      borderRadius: BorderRadius.circular(20),
                      border: Border.all(color: TevdaColors.forestGreen.withValues(alpha: 0.3)),
                    ),
                    child: Row(
                      children: [
                        const Icon(Icons.check_circle_rounded, color: TevdaColors.forestGreen, size: 14),
                        const SizedBox(width: 4),
                        Text(
                          _status,
                          style: const TextStyle(
                            fontSize: 10,
                            fontWeight: FontWeight.w900,
                            color: TevdaColors.forestGreen,
                            letterSpacing: 0.3,
                          ),
                        ),
                      ],
                    ),
                  ),
                ],
              ),
              const SizedBox(height: 16),

              // Digital ID Preview Card (Clickable to My ID Tab)
              InkWell(
                onTap: () => widget.onNavigate(1), // Go to My ID tab
                borderRadius: BorderRadius.circular(20),
                child: Container(
                  padding: const EdgeInsets.all(16),
                  decoration: BoxDecoration(
                    color: Colors.white,
                    borderRadius: BorderRadius.circular(20),
                    border: Border.all(color: TevdaColors.forestGreen.withValues(alpha: 0.3), width: 1.5),
                    boxShadow: [
                      BoxShadow(
                        color: TevdaColors.forestGreen.withValues(alpha: 0.06),
                        blurRadius: 16,
                        offset: const Offset(0, 4),
                      ),
                    ],
                  ),
                  child: Row(
                    children: [
                      Container(
                        width: 58,
                        height: 58,
                        decoration: BoxDecoration(
                          color: TevdaColors.primarySurface,
                          borderRadius: BorderRadius.circular(14),
                          border: Border.all(color: TevdaColors.forestGreen, width: 1.5),
                        ),
                        child: const Icon(Icons.person, size: 40, color: TevdaColors.forestGreen),
                      ),
                      const SizedBox(width: 14),
                      Expanded(
                        child: Column(
                          crossAxisAlignment: CrossAxisAlignment.start,
                          children: [
                            Text(
                              _memberName,
                              style: const TextStyle(
                                fontSize: 16,
                                fontWeight: FontWeight.w900,
                                color: TevdaColors.slate900,
                              ),
                            ),
                            const SizedBox(height: 2),
                            Text(
                              _vehicleInfo,
                              style: const TextStyle(
                                fontSize: 12,
                                fontWeight: FontWeight.w600,
                                color: TevdaColors.slate600,
                              ),
                            ),
                            const SizedBox(height: 4),
                            Text(
                              '$_status • Valid thru 2027',
                              style: const TextStyle(
                                fontSize: 10.5,
                                color: TevdaColors.forestGreen,
                                fontWeight: FontWeight.w700,
                              ),
                            ),
                          ],
                        ),
                      ),
                      Container(
                        padding: const EdgeInsets.all(6),
                        decoration: BoxDecoration(
                          color: TevdaColors.slate50,
                          borderRadius: BorderRadius.circular(10),
                          border: Border.all(color: const Color(0xFFE2E8F0)),
                        ),
                        child: const Icon(Icons.qr_code_2_rounded, size: 42, color: TevdaColors.slate800),
                      ),
                    ],
                  ),
                ),
              ),
              const SizedBox(height: 20),

              // 4-Card Quick Actions Grid
              Row(
                children: [
                  Expanded(
                    child: _buildQuickActionCard(
                      icon: Icons.badge_rounded,
                      title: lang.t('Kitambulisho', 'My ID'),
                      onTap: () => widget.onNavigate(1),
                    ),
                  ),
                  const SizedBox(width: 12),
                  Expanded(
                    child: _buildQuickActionCard(
                      icon: Icons.account_balance_wallet_rounded,
                      title: lang.t('Malipo', 'Payments'),
                      onTap: () {
                        Navigator.push(context, MaterialPageRoute(builder: (_) => const PaymentScreen()));
                      },
                    ),
                  ),
                ],
              ),
              const SizedBox(height: 12),
              Row(
                children: [
                  Expanded(
                    child: _buildQuickActionCard(
                      icon: Icons.school_rounded,
                      title: lang.t('Mafunzo', 'Academy'),
                      onTap: () => widget.onNavigate(2),
                    ),
                  ),
                  const SizedBox(width: 12),
                  Expanded(
                    child: _buildQuickActionCard(
                      icon: Icons.warning_amber_rounded,
                      title: lang.t('Toa Ripoti', 'Report Issue'),
                      color: TevdaColors.goldDark,
                      onTap: () {
                        Navigator.push(context, MaterialPageRoute(builder: (_) => const ComplaintScreen()));
                      },
                    ),
                  ),
                ],
              ),
              const SizedBox(height: 14),

              // Join TEVDA / Register New Vehicle Button
              SizedBox(
                width: double.infinity,
                child: OutlinedButton.icon(
                  onPressed: () {
                    Navigator.push(context, MaterialPageRoute(builder: (_) => const RegisterScreen()));
                  },
                  icon: const Icon(Icons.person_add_alt_1_rounded, size: 18),
                  label: Text(lang.t('Jiunge na TEVDA / Sajili Chombo Kipya', 'Join TEVDA / Register EV')),
                  style: OutlinedButton.styleFrom(
                    foregroundColor: TevdaColors.forestGreen,
                    side: const BorderSide(color: TevdaColors.forestGreen, width: 1.5),
                    padding: const EdgeInsets.symmetric(vertical: 14),
                    shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(14)),
                  ),
                ),
              ),
              const SizedBox(height: 24),

              // Upcoming Training Banner
              Row(
                mainAxisAlignment: MainAxisAlignment.spaceBetween,
                children: [
                  Text(
                    lang.t('Mafunzo Yajayo ya EV', 'Upcoming EV Training'),
                    style: const TextStyle(fontSize: 15, fontWeight: FontWeight.w900, color: TevdaColors.slate900),
                  ),
                  InkWell(
                    onTap: () => widget.onNavigate(2),
                    child: Text(
                      lang.t('Orodha Yote →', 'View All →'),
                      style: const TextStyle(fontSize: 12, fontWeight: FontWeight.w800, color: TevdaColors.forestGreen),
                    ),
                  ),
                ],
              ),
              const SizedBox(height: 10),
              Container(
                padding: const EdgeInsets.all(16),
                decoration: BoxDecoration(
                  color: Colors.white,
                  borderRadius: BorderRadius.circular(16),
                  border: Border.all(color: const Color(0xFFE2E8F0)),
                ),
                child: Row(
                  children: [
                    Container(
                      width: 48,
                      height: 48,
                      decoration: BoxDecoration(
                        color: const Color(0xFFEFF6FF),
                        borderRadius: BorderRadius.circular(12),
                      ),
                      child: const Icon(Icons.event_note_rounded, color: Color(0xFF2563EB), size: 24),
                    ),
                    const SizedBox(width: 14),
                    Expanded(
                      child: Column(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: const [
                          Text(
                            'EV Battery Safety & High-Voltage Mastery',
                            style: TextStyle(fontWeight: FontWeight.w800, fontSize: 13, color: TevdaColors.slate900),
                          ),
                          SizedBox(height: 3),
                          Text(
                            '28 Nov 2026 • Sinza Mori, Dar es Salaam',
                            style: TextStyle(fontSize: 11, color: TevdaColors.slate500),
                          ),
                        ],
                      ),
                    ),
                  ],
                ),
              ),
              const SizedBox(height: 20),

              // Vehicle Summary Shortcut
              Row(
                mainAxisAlignment: MainAxisAlignment.spaceBetween,
                children: [
                  Text(
                    lang.t('Chombo Chako Kikuu', 'Primary Electric Vehicle'),
                    style: const TextStyle(fontSize: 15, fontWeight: FontWeight.w900, color: TevdaColors.slate900),
                  ),
                  InkWell(
                    onTap: () {
                      Navigator.push(context, MaterialPageRoute(builder: (_) => const VehiclesScreen()));
                    },
                    child: Text(
                      lang.t('Vyombo Vyangu →', 'Fleet Manager →'),
                      style: const TextStyle(fontSize: 12, fontWeight: FontWeight.w800, color: TevdaColors.forestGreen),
                    ),
                  ),
                ],
              ),
              const SizedBox(height: 10),
              Container(
                padding: const EdgeInsets.all(16),
                decoration: BoxDecoration(
                  color: Colors.white,
                  borderRadius: BorderRadius.circular(16),
                  border: Border.all(color: const Color(0xFFE2E8F0)),
                ),
                child: Row(
                  children: [
                    Container(
                      padding: const EdgeInsets.all(10),
                      decoration: BoxDecoration(
                        color: TevdaColors.primarySurface,
                        borderRadius: BorderRadius.circular(10),
                      ),
                      child: const Icon(Icons.electric_moped_rounded, color: TevdaColors.forestGreen, size: 22),
                    ),
                    const SizedBox(width: 14),
                    Expanded(
                      child: Column(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: const [
                          Text(
                            'Ampersand E-Trike 2025',
                            style: TextStyle(fontWeight: FontWeight.w800, fontSize: 13.5, color: TevdaColors.slate900),
                          ),
                          SizedBox(height: 2),
                          Text(
                            'Namba ya Usajili: T 482 EVM • 12 kWh Battery',
                            style: TextStyle(fontSize: 11, color: TevdaColors.slate600),
                          ),
                        ],
                      ),
                    ),
                    Container(
                      padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 3),
                      decoration: BoxDecoration(
                        color: TevdaColors.primarySurface,
                        borderRadius: BorderRadius.circular(12),
                      ),
                      child: const Text('LATRA OK', style: TextStyle(fontSize: 9.5, fontWeight: FontWeight.w800, color: TevdaColors.forestGreen)),
                    ),
                  ],
                ),
              ),
              const SizedBox(height: 24),
            ],
          ),
        ),
      ),
    );
  }

  Widget _buildQuickActionCard({
    required IconData icon,
    required String title,
    required VoidCallback onTap,
    Color? color,
  }) {
    final iconColor = color ?? TevdaColors.forestGreen;
    return InkWell(
      onTap: onTap,
      borderRadius: BorderRadius.circular(16),
      child: Container(
        padding: const EdgeInsets.symmetric(vertical: 20),
        decoration: BoxDecoration(
          color: Colors.white,
          borderRadius: BorderRadius.circular(16),
          border: Border.all(color: const Color(0xFFE2E8F0)),
          boxShadow: [
            BoxShadow(
              color: Colors.black.withValues(alpha: 0.02),
              blurRadius: 8,
              offset: const Offset(0, 2),
            ),
          ],
        ),
        child: Column(
          children: [
            Container(
              padding: const EdgeInsets.all(10),
              decoration: BoxDecoration(
                color: iconColor.withValues(alpha: 0.08),
                shape: BoxShape.circle,
              ),
              child: Icon(icon, color: iconColor, size: 26),
            ),
            const SizedBox(height: 8),
            Text(
              title,
              style: const TextStyle(
                fontSize: 12.5,
                fontWeight: FontWeight.w800,
                color: TevdaColors.slate800,
              ),
            ),
          ],
        ),
      ),
    );
  }
}
