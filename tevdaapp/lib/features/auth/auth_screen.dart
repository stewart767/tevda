import 'package:flutter/material.dart';
import '../../core/theme.dart';
import '../../core/localization.dart';
import '../../core/api_service.dart';

class AuthScreen extends StatefulWidget {
  final VoidCallback onAuthenticated;

  const AuthScreen({super.key, required this.onAuthenticated});

  @override
  State<AuthScreen> createState() => _AuthScreenState();
}

class _AuthScreenState extends State<AuthScreen> with SingleTickerProviderStateMixin {
  late TabController _tabController;

  // Sign In Controllers
  final _loginCtrl = TextEditingController();
  final _loginPassCtrl = TextEditingController();
  bool _obscureLoginPass = true;
  bool _isLoginLoading = false;

  // Create Account Controllers
  final _regNameCtrl = TextEditingController();
  final _regPhoneCtrl = TextEditingController();
  final _regEmailCtrl = TextEditingController();
  final _regPassCtrl = TextEditingController();
  final _regPassConfirmCtrl = TextEditingController();
  bool _obscureRegPass = true;
  bool _obscureRegConfirm = true;
  bool _termsAccepted = false;
  bool _isRegLoading = false;

  // OTP Verification Modal State
  final _otpCtrl = TextEditingController();
  bool _isVerifyingOtp = false;

  @override
  void initState() {
    super.initState();
    _tabController = TabController(length: 2, vsync: this);
  }

  @override
  void dispose() {
    _tabController.dispose();
    _loginCtrl.dispose();
    _loginPassCtrl.dispose();
    _regNameCtrl.dispose();
    _regPhoneCtrl.dispose();
    _regEmailCtrl.dispose();
    _regPassCtrl.dispose();
    _regPassConfirmCtrl.dispose();
    _otpCtrl.dispose();
    super.dispose();
  }

  // ==========================================================================
  // SIGN IN ACTION
  // ==========================================================================
  Future<void> _handleSignIn() async {
    final login = _loginCtrl.text.trim();
    final pass = _loginPassCtrl.text;

    if (login.isEmpty || pass.isEmpty) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(
          content: Text('Tafadhali ingiza namba ya simu au barua pepe na nenosiri lako.'),
          backgroundColor: TevdaColors.error,
        ),
      );
      return;
    }

    setState(() => _isLoginLoading = true);
    final res = await ApiService.instance.login(login, pass);
    if (!mounted) return;
    setState(() => _isLoginLoading = false);

    if (res.success) {
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(
          content: Text(res.message),
          backgroundColor: TevdaColors.forestGreen,
        ),
      );
      widget.onAuthenticated();
    } else {
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(
          content: Text(res.message),
          backgroundColor: TevdaColors.error,
        ),
      );
    }
  }

  // ==========================================================================
  // CREATE ACCOUNT & OTP FLOW
  // ==========================================================================
  Future<void> _handleCreateAccount() async {
    final name = _regNameCtrl.text.trim();
    final phone = _regPhoneCtrl.text.trim();
    final email = _regEmailCtrl.text.trim();
    final pass = _regPassCtrl.text;
    final confirm = _regPassConfirmCtrl.text;

    if (name.isEmpty || phone.isEmpty || pass.isEmpty) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(
          content: Text('Tafadhali jaza jina kamili, namba ya simu na nenosiri.'),
          backgroundColor: TevdaColors.error,
        ),
      );
      return;
    }

    if (pass.length < 6) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(
          content: Text('Nenosiri lazima liwe na angalau herufi au tarakimu 6.'),
          backgroundColor: TevdaColors.error,
        ),
      );
      return;
    }

    if (pass != confirm) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(
          content: Text('Nenosiri la uthibitisho halilingani.'),
          backgroundColor: TevdaColors.error,
        ),
      );
      return;
    }

    if (!_termsAccepted) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(
          content: Text('Tafadhali kubali Masharti na Sera ya Faragha ya TEVDA.'),
          backgroundColor: TevdaColors.error,
        ),
      );
      return;
    }

    // Step 1: Send OTP to verify phone
    setState(() => _isRegLoading = true);
    final otpRes = await ApiService.instance.sendOtp(phone);
    if (!mounted) return;
    setState(() => _isRegLoading = false);

    if (!otpRes.success) {
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(content: Text(otpRes.message), backgroundColor: TevdaColors.error),
      );
      return;
    }

    // Step 2: Open OTP Dialog
    _showOtpVerificationDialog(name, phone, email, pass, confirm);
  }

  void _showOtpVerificationDialog(String name, String phone, String email, String pass, String confirm) {
    _otpCtrl.text = '2026'; // Pre-filled for seamless testing

    showModalBottomSheet(
      context: context,
      isScrollControlled: true,
      backgroundColor: Colors.transparent,
      builder: (ctx) {
        return StatefulBuilder(
          builder: (dialogCtx, setModalState) {
            return Container(
              padding: EdgeInsets.only(
                top: 24,
                left: 24,
                right: 24,
                bottom: MediaQuery.of(ctx).viewInsets.bottom + 24,
              ),
              decoration: const BoxDecoration(
                color: Colors.white,
                borderRadius: BorderRadius.vertical(top: Radius.circular(24)),
              ),
              child: Column(
                mainAxisSize: MainAxisSize.min,
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Row(
                    mainAxisAlignment: MainAxisAlignment.spaceBetween,
                    children: [
                      Row(
                        children: const [
                          Icon(Icons.lock_clock_rounded, color: TevdaColors.forestGreen, size: 24),
                          SizedBox(width: 8),
                          Text('Uthibitisho wa Simu (OTP)', style: TextStyle(fontWeight: FontWeight.w900, fontSize: 16)),
                        ],
                      ),
                      IconButton(icon: const Icon(Icons.close), onPressed: () => Navigator.pop(ctx)),
                    ],
                  ),
                  const SizedBox(height: 8),
                  Text(
                    'Tumetuma nambari ya siri ya tarakimu 4 kwenye namba $phone ili kuthibitisha umiliki wa simu yako.',
                    style: const TextStyle(fontSize: 12.5, color: TevdaColors.slate600, height: 1.4),
                  ),
                  const SizedBox(height: 16),
                  TextField(
                    controller: _otpCtrl,
                    keyboardType: TextInputType.number,
                    maxLength: 6,
                    textAlign: TextAlign.center,
                    style: const TextStyle(fontSize: 22, fontWeight: FontWeight.w900, letterSpacing: 8),
                    decoration: InputDecoration(
                      hintText: '2026',
                      counterText: '',
                      filled: true,
                      fillColor: TevdaColors.slate50,
                      border: OutlineInputBorder(borderRadius: BorderRadius.circular(12)),
                    ),
                  ),
                  const SizedBox(height: 16),
                  SizedBox(
                    width: double.infinity,
                    child: ElevatedButton(
                      onPressed: _isVerifyingOtp
                          ? null
                          : () async {
                              final otp = _otpCtrl.text.trim();
                              if (otp.isEmpty) return;

                              setModalState(() => _isVerifyingOtp = true);
                              final verifyRes = await ApiService.instance.verifyOtp(phone, otp);

                              if (!verifyRes.success) {
                                setModalState(() => _isVerifyingOtp = false);
                                ScaffoldMessenger.of(context).showSnackBar(
                                  SnackBar(content: Text(verifyRes.message), backgroundColor: TevdaColors.error),
                                );
                                return;
                              }

                              // Step 3: Register User Account
                              final regRes = await ApiService.instance.registerAccount(
                                name: name,
                                phone: phone,
                                email: email.isNotEmpty ? email : null,
                                password: pass,
                                passwordConfirmation: confirm,
                                termsAccepted: true,
                              );

                              if (!mounted) return;
                              setModalState(() => _isVerifyingOtp = false);

                              if (regRes.success) {
                                Navigator.pop(ctx);
                                ScaffoldMessenger.of(context).showSnackBar(
                                  SnackBar(content: Text(regRes.message), backgroundColor: TevdaColors.forestGreen),
                                );
                                widget.onAuthenticated();
                              } else {
                                ScaffoldMessenger.of(context).showSnackBar(
                                  SnackBar(content: Text(regRes.message), backgroundColor: TevdaColors.error),
                                );
                              }
                            },
                      style: ElevatedButton.styleFrom(
                        backgroundColor: TevdaColors.forestGreen,
                        padding: const EdgeInsets.symmetric(vertical: 14),
                      ),
                      child: _isVerifyingOtp
                          ? const SizedBox(width: 20, height: 20, child: CircularProgressIndicator(color: Colors.white, strokeWidth: 2))
                          : const Text('Thibitisha na Ufungue Akaunti'),
                    ),
                  ),
                ],
              ),
            );
          },
        );
      },
    );
  }

  // ==========================================================================
  // FORGOT PASSWORD MODAL
  // ==========================================================================
  void _showForgotPasswordDialog() {
    final resetLoginCtrl = TextEditingController(text: _loginCtrl.text);
    final resetOtpCtrl = TextEditingController(text: '2026');
    final newPassCtrl = TextEditingController();
    final newPassConfirmCtrl = TextEditingController();
    int resetStep = 0; // 0: enter phone/email, 1: enter OTP & new pass
    bool isResetLoading = false;

    showModalBottomSheet(
      context: context,
      isScrollControlled: true,
      backgroundColor: Colors.transparent,
      builder: (ctx) {
        return StatefulBuilder(
          builder: (dialogCtx, setModalState) {
            return Container(
              padding: EdgeInsets.only(
                top: 24,
                left: 24,
                right: 24,
                bottom: MediaQuery.of(ctx).viewInsets.bottom + 24,
              ),
              decoration: const BoxDecoration(
                color: Colors.white,
                borderRadius: BorderRadius.vertical(top: Radius.circular(24)),
              ),
              child: Column(
                mainAxisSize: MainAxisSize.min,
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Row(
                    mainAxisAlignment: MainAxisAlignment.spaceBetween,
                    children: [
                      const Text('Kuweka Upya Nenosiri', style: TextStyle(fontWeight: FontWeight.w900, fontSize: 16)),
                      IconButton(icon: const Icon(Icons.close), onPressed: () => Navigator.pop(ctx)),
                    ],
                  ),
                  const SizedBox(height: 8),
                  if (resetStep == 0) ...[
                    const Text('Weka namba ya simu au barua pepe uliyosajili TEVDA ili kupokea namba ya siri (OTP).', style: TextStyle(fontSize: 12, color: TevdaColors.slate600)),
                    const SizedBox(height: 14),
                    TextField(
                      controller: resetLoginCtrl,
                      decoration: InputDecoration(
                        labelText: 'Namba ya Simu / Barua Pepe',
                        filled: true,
                        fillColor: TevdaColors.slate50,
                        border: OutlineInputBorder(borderRadius: BorderRadius.circular(12)),
                      ),
                    ),
                    const SizedBox(height: 16),
                    SizedBox(
                      width: double.infinity,
                      child: ElevatedButton(
                        onPressed: isResetLoading
                            ? null
                            : () async {
                                final log = resetLoginCtrl.text.trim();
                                if (log.isEmpty) return;
                                setModalState(() => isResetLoading = true);
                                final res = await ApiService.instance.forgotPassword(log);
                                setModalState(() => isResetLoading = false);
                                if (res.success) {
                                  setModalState(() => resetStep = 1);
                                } else {
                                  ScaffoldMessenger.of(context).showSnackBar(SnackBar(content: Text(res.message), backgroundColor: TevdaColors.error));
                                }
                              },
                        style: ElevatedButton.styleFrom(backgroundColor: TevdaColors.forestGreen, padding: const EdgeInsets.symmetric(vertical: 14)),
                        child: isResetLoading ? const CircularProgressIndicator(color: Colors.white) : const Text('Tuma OTP ya Uhakiki'),
                      ),
                    ),
                  ] else ...[
                    const Text('Weka OTP ya tarakimu 4 na nenosiri jipya unalotaka kutumia.', style: TextStyle(fontSize: 12, color: TevdaColors.slate600)),
                    const SizedBox(height: 12),
                    TextField(
                      controller: resetOtpCtrl,
                      decoration: InputDecoration(labelText: 'Nambari ya Siri (OTP)', filled: true, fillColor: TevdaColors.slate50, border: OutlineInputBorder(borderRadius: BorderRadius.circular(12))),
                    ),
                    const SizedBox(height: 10),
                    TextField(
                      controller: newPassCtrl,
                      obscureText: true,
                      decoration: InputDecoration(labelText: 'Nenosiri Jipya', filled: true, fillColor: TevdaColors.slate50, border: OutlineInputBorder(borderRadius: BorderRadius.circular(12))),
                    ),
                    const SizedBox(height: 10),
                    TextField(
                      controller: newPassConfirmCtrl,
                      obscureText: true,
                      decoration: InputDecoration(labelText: 'Thibitisha Nenosiri Jipya', filled: true, fillColor: TevdaColors.slate50, border: OutlineInputBorder(borderRadius: BorderRadius.circular(12))),
                    ),
                    const SizedBox(height: 16),
                    SizedBox(
                      width: double.infinity,
                      child: ElevatedButton(
                        onPressed: isResetLoading
                            ? null
                            : () async {
                                setModalState(() => isResetLoading = true);
                                final res = await ApiService.instance.resetPassword(
                                  login: resetLoginCtrl.text.trim(),
                                  otp: resetOtpCtrl.text.trim(),
                                  password: newPassCtrl.text,
                                  passwordConfirmation: newPassConfirmCtrl.text,
                                );
                                setModalState(() => isResetLoading = false);
                                if (res.success) {
                                  Navigator.pop(ctx);
                                  ScaffoldMessenger.of(context).showSnackBar(SnackBar(content: Text(res.message), backgroundColor: TevdaColors.forestGreen));
                                } else {
                                  ScaffoldMessenger.of(context).showSnackBar(SnackBar(content: Text(res.message), backgroundColor: TevdaColors.error));
                                }
                              },
                        style: ElevatedButton.styleFrom(backgroundColor: TevdaColors.forestGreen, padding: const EdgeInsets.symmetric(vertical: 14)),
                        child: isResetLoading ? const CircularProgressIndicator(color: Colors.white) : const Text('Hifadhi Nenosiri Jipya'),
                      ),
                    ),
                  ],
                ],
              ),
            );
          },
        );
      },
    );
  }

  @override
  Widget build(BuildContext context) {
    final lang = LanguageController.instance;

    return Scaffold(
      backgroundColor: TevdaColors.background,
      body: SafeArea(
        child: SingleChildScrollView(
          padding: const EdgeInsets.symmetric(horizontal: 24, vertical: 16),
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.center,
            children: [
              // Language Switcher & Top Row
              Row(
                mainAxisAlignment: MainAxisAlignment.end,
                children: [
                  InkWell(
                    onTap: () => lang.toggleLanguage(),
                    borderRadius: BorderRadius.circular(20),
                    child: Container(
                      padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 5),
                      decoration: BoxDecoration(
                        color: Colors.white,
                        borderRadius: BorderRadius.circular(20),
                        border: Border.all(color: const Color(0xFFE2E8F0)),
                      ),
                      child: Row(
                        children: [
                          const Icon(Icons.language_rounded, size: 16, color: TevdaColors.forestGreen),
                          const SizedBox(width: 4),
                          Text(
                            lang.isSwahili ? 'English' : 'Kiswahili',
                            style: const TextStyle(fontSize: 11.5, fontWeight: FontWeight.w700, color: TevdaColors.forestGreen),
                          ),
                        ],
                      ),
                    ),
                  ),
                ],
              ),
              const SizedBox(height: 10),

              // Official Logo Branding
              Center(
                child: Image.asset(
                  'assets/tevda-logo.png',
                  width: 90,
                  height: 90,
                  fit: BoxFit.contain,
                ),
              ),
              const SizedBox(height: 12),

              const Text(
                'TEVDA',
                style: TextStyle(
                  fontSize: 24,
                  fontWeight: FontWeight.w900,
                  color: TevdaColors.forestGreen,
                  letterSpacing: 1.2,
                ),
              ),
              const SizedBox(height: 2),
              const Text(
                'Tanzania Electric Vehicle Drivers Association',
                textAlign: TextAlign.center,
                style: TextStyle(
                  fontSize: 11,
                  fontWeight: FontWeight.w600,
                  color: TevdaColors.slate600,
                ),
              ),
              const SizedBox(height: 8),

              Container(
                padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 4),
                decoration: BoxDecoration(
                  color: TevdaColors.primarySurface,
                  borderRadius: BorderRadius.circular(20),
                ),
                child: const Text(
                  'SMART DRIVERS • SMART MOBILITY',
                  style: TextStyle(
                    fontSize: 9.5,
                    fontWeight: FontWeight.w800,
                    color: TevdaColors.forestGreen,
                    letterSpacing: 1.0,
                  ),
                ),
              ),
              const SizedBox(height: 24),

              // Tab Selector: Sign In vs Create Account
              Container(
                height: 48,
                decoration: BoxDecoration(
                  color: Colors.white,
                  borderRadius: BorderRadius.circular(14),
                  border: Border.all(color: const Color(0xFFE2E8F0)),
                ),
                child: TabBar(
                  controller: _tabController,
                  indicator: BoxDecoration(
                    color: TevdaColors.forestGreen,
                    borderRadius: BorderRadius.circular(12),
                  ),
                  labelColor: Colors.white,
                  unselectedLabelColor: TevdaColors.slate600,
                  labelStyle: const TextStyle(fontWeight: FontWeight.w800, fontSize: 13),
                  unselectedLabelStyle: const TextStyle(fontWeight: FontWeight.w600, fontSize: 13),
                  tabs: [
                    Tab(text: lang.t('Ingia (Sign In)', 'Sign In')),
                    Tab(text: lang.t('Fungua Akaunti', 'Create Account')),
                  ],
                ),
              ),
              const SizedBox(height: 20),

              // Form Container
              AnimatedBuilder(
                animation: _tabController,
                builder: (context, _) {
                  return Container(
                    padding: const EdgeInsets.all(20),
                    decoration: BoxDecoration(
                      color: Colors.white,
                      borderRadius: BorderRadius.circular(20),
                      border: Border.all(color: const Color(0xFFE2E8F0)),
                      boxShadow: [
                        BoxShadow(
                          color: Colors.black.withOpacity(0.03),
                          blurRadius: 12,
                          offset: const Offset(0, 4),
                        ),
                      ],
                    ),
                    child: _tabController.index == 0 ? _buildSignInForm() : _buildCreateAccountForm(),
                  );
                },
              ),
              const SizedBox(height: 16),

              // Bottom Security Footer
              Row(
                mainAxisAlignment: MainAxisAlignment.center,
                children: const [
                  Icon(Icons.shield_rounded, size: 14, color: TevdaColors.slate400),
                  SizedBox(width: 6),
                  Text(
                    'Mfumo Salama wa Uanachama • TEVDA National HQ',
                    style: TextStyle(fontSize: 11, color: TevdaColors.slate500, fontWeight: FontWeight.w600),
                  ),
                ],
              ),
            ],
          ),
        ),
      ),
    );
  }

  // ==========================================================================
  // SIGN IN TAB VIEW
  // ==========================================================================
  Widget _buildSignInForm() {
    final lang = LanguageController.instance;

    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Text(
          lang.t('Karibu Tena!', 'Welcome Back!'),
          style: const TextStyle(fontSize: 18, fontWeight: FontWeight.w900, color: TevdaColors.slate900),
        ),
        const SizedBox(height: 4),
        Text(
          lang.t('Weka namba ya simu au barua pepe na nenosiri lako.', 'Enter your phone/email and password to continue.'),
          style: const TextStyle(fontSize: 12, color: TevdaColors.slate600),
        ),
        const SizedBox(height: 16),

        TextField(
          controller: _loginCtrl,
          decoration: InputDecoration(
            labelText: lang.t('Namba ya Simu / Barua Pepe', 'Phone Number / Email'),
            hintText: 'Mfano: 0754123456 au TEVDA-2026-000128',
            prefixIcon: const Icon(Icons.person_outline_rounded, color: TevdaColors.forestGreen),
            filled: true,
            fillColor: TevdaColors.slate50,
            border: OutlineInputBorder(borderRadius: BorderRadius.circular(12)),
          ),
        ),
        const SizedBox(height: 14),

        TextField(
          controller: _loginPassCtrl,
          obscureText: _obscureLoginPass,
          decoration: InputDecoration(
            labelText: lang.t('Nenosiri (Password)', 'Password'),
            prefixIcon: const Icon(Icons.lock_outline_rounded, color: TevdaColors.forestGreen),
            suffixIcon: IconButton(
              icon: Icon(_obscureLoginPass ? Icons.visibility_off : Icons.visibility, color: TevdaColors.slate400),
              onPressed: () => setState(() => _obscureLoginPass = !_obscureLoginPass),
            ),
            filled: true,
            fillColor: TevdaColors.slate50,
            border: OutlineInputBorder(borderRadius: BorderRadius.circular(12)),
          ),
        ),
        const SizedBox(height: 10),

        Align(
          alignment: Alignment.centerRight,
          child: TextButton(
            onPressed: _showForgotPasswordDialog,
            child: Text(
              lang.t('Umesahau Nenosiri?', 'Forgot Password?'),
              style: const TextStyle(fontSize: 12, fontWeight: FontWeight.w700, color: TevdaColors.forestGreen),
            ),
          ),
        ),
        const SizedBox(height: 10),

        SizedBox(
          width: double.infinity,
          child: ElevatedButton(
            onPressed: _isLoginLoading ? null : _handleSignIn,
            style: ElevatedButton.styleFrom(
              backgroundColor: TevdaColors.forestGreen,
              padding: const EdgeInsets.symmetric(vertical: 14),
              shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
            ),
            child: _isLoginLoading
                ? const SizedBox(width: 20, height: 20, child: CircularProgressIndicator(color: Colors.white, strokeWidth: 2))
                : Text(lang.t('Ingia Kwenye Mfumo →', 'Sign In →'), style: const TextStyle(fontSize: 14, fontWeight: FontWeight.w800)),
          ),
        ),
      ],
    );
  }

  // ==========================================================================
  // CREATE ACCOUNT TAB VIEW
  // ==========================================================================
  Widget _buildCreateAccountForm() {
    final lang = LanguageController.instance;

    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Text(
          lang.t('Unda Akaunti Mpya', 'Create New Account'),
          style: const TextStyle(fontSize: 18, fontWeight: FontWeight.w900, color: TevdaColors.slate900),
        ),
        const SizedBox(height: 4),
        Text(
          lang.t('Anza kwa kuunda akaunti yako binafsi ya udereva.', 'Begin by creating your personal member account.'),
          style: const TextStyle(fontSize: 12, color: TevdaColors.slate600),
        ),
        const SizedBox(height: 14),

        TextField(
          controller: _regNameCtrl,
          decoration: InputDecoration(
            labelText: lang.t('Jina Kamili *', 'Full Legal Name *'),
            hintText: 'Kama lilivyo kwenye kitambulisho cha NIDA',
            prefixIcon: const Icon(Icons.person_rounded, color: TevdaColors.forestGreen),
            filled: true,
            fillColor: TevdaColors.slate50,
            border: OutlineInputBorder(borderRadius: BorderRadius.circular(12)),
          ),
        ),
        const SizedBox(height: 12),

        TextField(
          controller: _regPhoneCtrl,
          keyboardType: TextInputType.phone,
          decoration: InputDecoration(
            labelText: lang.t('Namba ya Simu (M-Pesa / Tigo) *', 'Phone Number (Mobile Money) *'),
            hintText: 'Mfano: 0754123456 au 0685000000',
            prefixIcon: const Icon(Icons.phone_rounded, color: TevdaColors.forestGreen),
            filled: true,
            fillColor: TevdaColors.slate50,
            border: OutlineInputBorder(borderRadius: BorderRadius.circular(12)),
          ),
        ),
        const SizedBox(height: 12),

        TextField(
          controller: _regEmailCtrl,
          keyboardType: TextInputType.emailAddress,
          decoration: InputDecoration(
            labelText: lang.t('Barua Pepe (Hiari)', 'Email Address (Optional)'),
            hintText: 'jina@mfano.or.tz',
            prefixIcon: const Icon(Icons.email_outlined, color: TevdaColors.forestGreen),
            filled: true,
            fillColor: TevdaColors.slate50,
            border: OutlineInputBorder(borderRadius: BorderRadius.circular(12)),
          ),
        ),
        const SizedBox(height: 12),

        TextField(
          controller: _regPassCtrl,
          obscureText: _obscureRegPass,
          decoration: InputDecoration(
            labelText: lang.t('Nenosiri (Angalau herufi 6) *', 'Password (Min 6 chars) *'),
            prefixIcon: const Icon(Icons.lock_rounded, color: TevdaColors.forestGreen),
            suffixIcon: IconButton(
              icon: Icon(_obscureRegPass ? Icons.visibility_off : Icons.visibility, color: TevdaColors.slate400),
              onPressed: () => setState(() => _obscureRegPass = !_obscureRegPass),
            ),
            filled: true,
            fillColor: TevdaColors.slate50,
            border: OutlineInputBorder(borderRadius: BorderRadius.circular(12)),
          ),
        ),
        const SizedBox(height: 12),

        TextField(
          controller: _regPassConfirmCtrl,
          obscureText: _obscureRegConfirm,
          decoration: InputDecoration(
            labelText: lang.t('Thibitisha Nenosiri *', 'Confirm Password *'),
            prefixIcon: const Icon(Icons.lock_outline_rounded, color: TevdaColors.forestGreen),
            suffixIcon: IconButton(
              icon: Icon(_obscureRegConfirm ? Icons.visibility_off : Icons.visibility, color: TevdaColors.slate400),
              onPressed: () => setState(() => _obscureRegConfirm = !_obscureRegConfirm),
            ),
            filled: true,
            fillColor: TevdaColors.slate50,
            border: OutlineInputBorder(borderRadius: BorderRadius.circular(12)),
          ),
        ),
        const SizedBox(height: 12),

        CheckboxListTile(
          value: _termsAccepted,
          activeColor: TevdaColors.forestGreen,
          title: Text(
            lang.t('Ninakubali Sera ya Faragha na Kanuni za TEVDA.', 'I accept TEVDA Terms of Service and Privacy Policy.'),
            style: const TextStyle(fontSize: 11.5, fontWeight: FontWeight.w700),
          ),
          onChanged: (v) => setState(() => _termsAccepted = v ?? false),
          controlAffinity: ListTileControlAffinity.leading,
          contentPadding: EdgeInsets.zero,
        ),
        const SizedBox(height: 12),

        SizedBox(
          width: double.infinity,
          child: ElevatedButton(
            onPressed: _isRegLoading ? null : _handleCreateAccount,
            style: ElevatedButton.styleFrom(
              backgroundColor: TevdaColors.forestGreen,
              padding: const EdgeInsets.symmetric(vertical: 14),
              shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
            ),
            child: _isRegLoading
                ? const SizedBox(width: 20, height: 20, child: CircularProgressIndicator(color: Colors.white, strokeWidth: 2))
                : Text(lang.t('Thibitisha Simu na Uunde Akaunti →', 'Verify Phone & Create Account →'), style: const TextStyle(fontSize: 13, fontWeight: FontWeight.w800)),
          ),
        ),
      ],
    );
  }
}
