import 'package:flutter/material.dart';

/// Official TEVDA Brand Palette
class TevdaColors {
  // Brand Primary & Accents
  static const Color forestGreen = Color(0xFF075B37); // Primary brand green
  static const Color emeraldGreen = Color(0xFF0B9B62); // Vibrant action green
  static const Color gold = Color(0xFFD6AD54); // Premium gold accent
  static const Color goldLight = Color(0xFFFEF3C7);
  static const Color goldDark = Color(0xFFB45309);
  
  // Backgrounds & Surfaces
  static const Color background = Color(0xFFF4F7F5); // Official light bg
  static const Color surface = Color(0xFFFFFFFF);
  static const Color surfaceMint = Color(0xFFE8F5E9);
  static const Color primarySurface = Color(0xFFECFDF5);
  
  // Slate / Neutrals
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
  
  // Semantic Status
  static const Color success = Color(0xFF10B981);
  static const Color warning = Color(0xFFF59E0B);
  static const Color error = Color(0xFFEF4444);
  static const Color info = Color(0xFF3B82F6);
}

class TevdaTheme {
  static ThemeData get lightTheme {
    return ThemeData(
      useMaterial3: true,
      colorScheme: ColorScheme.fromSeed(
        seedColor: TevdaColors.forestGreen,
        primary: TevdaColors.forestGreen,
        secondary: TevdaColors.emeraldGreen,
        tertiary: TevdaColors.gold,
        background: TevdaColors.background,
        surface: TevdaColors.surface,
        brightness: Brightness.light,
      ),
      scaffoldBackgroundColor: TevdaColors.background,
      fontFamily: 'Roboto',
      appBarTheme: const AppBarTheme(
        backgroundColor: Colors.white,
        foregroundColor: TevdaColors.slate900,
        elevation: 0,
        centerTitle: false,
        iconTheme: IconThemeData(color: TevdaColors.forestGreen),
      ),
      cardTheme: CardThemeData(
        color: Colors.white,
        elevation: 0,
        shape: RoundedRectangleBorder(
          borderRadius: BorderRadius.circular(16),
          side: const BorderSide(color: Color(0xFFE2E8F0), width: 1.0),
        ),
      ),
      elevatedButtonTheme: ElevatedButtonThemeData(
        style: ElevatedButton.styleFrom(
          backgroundColor: TevdaColors.forestGreen,
          foregroundColor: Colors.white,
          elevation: 0,
          padding: const EdgeInsets.symmetric(horizontal: 24, vertical: 14),
          shape: RoundedRectangleBorder(
            borderRadius: BorderRadius.circular(14),
          ),
          textStyle: const TextStyle(
            fontSize: 15,
            fontWeight: FontWeight.w700,
            letterSpacing: 0.3,
          ),
        ),
      ),
    );
  }

  static ThemeData get darkTheme {
    return ThemeData(
      useMaterial3: true,
      colorScheme: ColorScheme.fromSeed(
        seedColor: TevdaColors.forestGreen,
        primary: TevdaColors.emeraldGreen,
        secondary: TevdaColors.gold,
        tertiary: TevdaColors.goldLight,
        background: const Color(0xFF0A100D),
        surface: const Color(0xFF131D18),
        brightness: Brightness.dark,
      ),
      scaffoldBackgroundColor: const Color(0xFF0A100D),
      cardTheme: CardThemeData(
        color: const Color(0xFF131D18),
        elevation: 0,
        shape: RoundedRectangleBorder(
          borderRadius: BorderRadius.circular(16),
          side: const BorderSide(color: Color(0xFF1E3A2F), width: 1.0),
        ),
      ),
    );
  }
}
