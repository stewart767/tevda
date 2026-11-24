import 'package:flutter/material.dart';
import 'package:flutter/services.dart';
import 'core/theme.dart';
import 'core/localization.dart';
import 'core/api_service.dart';
import 'features/auth/auth_screen.dart';
import 'features/onboarding/onboarding_screen.dart';
import 'features/tracking/tracking_screen.dart';
import 'features/home/home_screen.dart';
import 'features/smart_id/smart_id_screen.dart';
import 'features/academy/academy_screen.dart';
import 'features/explore/explore_screen.dart';
import 'features/profile/profile_screen.dart';

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

class TevdaApp extends StatelessWidget {
  const TevdaApp({super.key});

  @override
  Widget build(BuildContext context) {
    return AnimatedBuilder(
      animation: LanguageController.instance,
      builder: (context, _) {
        return MaterialApp(
          title: 'TEVDA - Tanzania Electric Vehicle Drivers Association',
          debugShowCheckedModeBanner: false,
          theme: TevdaTheme.lightTheme,
          darkTheme: TevdaTheme.darkTheme,
          themeMode: ThemeMode.light,
          home: const AppStartupGate(),
        );
      },
    );
  }
}

/// Authentication-First Root Gate: routes user based on real backend authentication & membership state
class AppStartupGate extends StatefulWidget {
  const AppStartupGate({super.key});

  @override
  State<AppStartupGate> createState() => _AppStartupGateState();
}

class _AppStartupGateState extends State<AppStartupGate> {
  bool _isCheckingSession = false;

  void _refreshState() {
    if (mounted) {
      setState(() {});
    }
  }

  void _handleLogout() {
    ApiService.instance.clearSession();
    _refreshState();
  }

  @override
  Widget build(BuildContext context) {
    // 1. If not authenticated, display Authentication Screen
    if (!ApiService.instance.isAuthenticated) {
      return AuthScreen(onAuthenticated: _refreshState);
    }

    // 2. If authenticated, determine screen based on backend routing state
    final routing = ApiService.instance.routingState;
    final state = routing != null ? routing['state'] as String? : null;

    if (state == null) {
      // Fetch user profile & routing state from backend
      return FutureBuilder<ApiResponse<dynamic>>(
        future: ApiService.instance.getMe(),
        builder: (context, snapshot) {
          if (snapshot.connectionState == ConnectionState.waiting) {
            return const Scaffold(
              backgroundColor: TevdaColors.background,
              body: Center(
                child: Column(
                  mainAxisAlignment: MainAxisAlignment.center,
                  children: [
                    CircularProgressIndicator(color: TevdaColors.forestGreen),
                    SizedBox(height: 16),
                    Text(
                      'Inathibitisha uanachama...',
                      style: TextStyle(fontWeight: FontWeight.w700, color: TevdaColors.slate600),
                    ),
                  ],
                ),
              ),
            );
          }

          if (snapshot.hasError || (snapshot.hasData && !snapshot.data!.success)) {
            // Token might be invalid or network down
            return AuthScreen(onAuthenticated: _refreshState);
          }

          // Reroute once getMe finishes
          final updatedState = ApiService.instance.routingState?['state'] as String? ?? 'active_dashboard';
          return _routeForState(updatedState);
        },
      );
    }

    return _routeForState(state);
  }

  Widget _routeForState(String state) {
    switch (state) {
      case 'start_onboarding':
      case 'resume_draft':
        return OnboardingScreen(
          onComplete: _refreshState,
          onLogout: _handleLogout,
        );
      case 'tracking':
      case 'under_review':
      case 'correction_required':
      case 'payment_pending':
      case 'rejected':
        return TrackingScreen(
          onLogout: _handleLogout,
          onEditApplication: () {
            setState(() {
              if (ApiService.instance.routingState != null) {
                ApiService.instance.routingState!['state'] = 'resume_draft';
              }
            });
          },
        );
      case 'active_dashboard':
      default:
        return MainNavigationScreen(onLogout: _handleLogout);
    }
  }
}

/// The 5 Primary Destinations Scaffold
class MainNavigationScreen extends StatefulWidget {
  final VoidCallback? onLogout;

  const MainNavigationScreen({super.key, this.onLogout});

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
    final lang = LanguageController.instance;

    final List<Widget> destinations = [
      HomeScreen(onNavigate: _navigateToIndex),
      const SmartIdScreen(),
      const AcademyScreen(),
      const ExploreScreen(),
      ProfileScreen(onNavigateTab: _navigateToIndex, onLogout: widget.onLogout),
    ];

    return Scaffold(
      body: IndexedStack(
        index: _currentIndex,
        children: destinations,
      ),
      bottomNavigationBar: Container(
        decoration: BoxDecoration(
          color: Colors.white,
          boxShadow: [
            BoxShadow(
              color: Colors.black.withValues(alpha: 0.06),
              blurRadius: 18,
              offset: const Offset(0, -3),
            ),
          ],
          border: const Border(
            top: BorderSide(color: Color(0xFFF1F5F9), width: 1.2),
          ),
        ),
        child: SafeArea(
          top: false,
          child: Padding(
            padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 6),
            child: Row(
              mainAxisAlignment: MainAxisAlignment.spaceAround,
              children: [
                _buildNavItem(0, Icons.home_rounded, lang.t('Mwanzo', 'Home')),
                _buildNavItem(1, Icons.badge_rounded, lang.t('Kitambulisho', 'My ID')),
                _buildNavItem(2, Icons.school_rounded, lang.t('Chuo', 'Academy')),
                _buildNavItem(3, Icons.explore_rounded, lang.t('Gundua', 'Explore')),
                _buildNavItem(4, Icons.person_rounded, lang.t('Wasifu', 'Profile')),
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
        padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 8),
        decoration: BoxDecoration(
          color: isSelected ? TevdaColors.primarySurface : Colors.transparent,
          borderRadius: BorderRadius.circular(14),
        ),
        child: Column(
          mainAxisSize: MainAxisSize.min,
          children: [
            Icon(
              icon,
              color: isSelected ? TevdaColors.forestGreen : TevdaColors.slate400,
              size: 24,
            ),
            const SizedBox(height: 3),
            Text(
              label,
              style: TextStyle(
                fontSize: 10.5,
                fontWeight: isSelected ? FontWeight.w900 : FontWeight.w600,
                color: isSelected ? TevdaColors.forestGreen : TevdaColors.slate500,
              ),
            ),
          ],
        ),
      ),
    );
  }
}
