import 'package:flutter/material.dart';
import '../../core/theme.dart';
import '../../core/localization.dart';
import '../../core/api_service.dart';

class SmartIdScreen extends StatefulWidget {
  const SmartIdScreen({super.key});

  @override
  State<SmartIdScreen> createState() => _SmartIdScreenState();
}

class _SmartIdScreenState extends State<SmartIdScreen> {
  bool _isCardFlipped = false;
  String _cardState = 'active'; // 'active', 'expired', 'suspended'

  String _memberName = 'Amani Juma';
  String _memberNumber = 'TEVDA-2026-000128';
  String _category = 'Full Member';
  String _vehicleInfo = 'Electric Bajaji • T 482 EVM';
  String _validUntil = '31 Dec 2027';
  String _issuedDate = '01 Jan 2026';
  String _verificationUrl = 'https://www.tevda.or.tz/verify-id/TEVDA-2026-000128';

  @override
  void initState() {
    super.initState();
    _fetchLiveSmartId();
  }

  Future<void> _fetchLiveSmartId() async {
    try {
      final res = await ApiService.instance.getSmartId();
      if (res.success && res.data != null && res.data is Map<String, dynamic>) {
        final d = res.data as Map<String, dynamic>;
        setState(() {
          if (d['full_name'] != null) _memberName = d['full_name'];
          if (d['member_number'] != null) _memberNumber = d['member_number'];
          if (d['category'] != null) _category = d['category'];
          if (d['vehicle'] != null) _vehicleInfo = d['vehicle'];
          if (d['expires_at'] != null) _validUntil = d['expires_at'];
          if (d['issued_at'] != null) _issuedDate = d['issued_at'];
          if (d['verification_url'] != null) _verificationUrl = d['verification_url'];
          if (d['status'] != null) {
            final st = d['status'].toString().toLowerCase();
            if (st == 'suspended') {
              _cardState = 'suspended';
            } else if (st == 'expired') _cardState = 'expired';
            else _cardState = 'active';
          }
        });
      }
    } catch (_) {}
  }

  @override
  Widget build(BuildContext context) {
    final lang = LanguageController.instance;

    return Scaffold(
      appBar: AppBar(
        title: Text(
          lang.t('Kitambulisho cha Kidijitali', 'Digital Smart ID'),
          style: const TextStyle(fontWeight: FontWeight.w800, fontSize: 18),
        ),
        actions: [
          IconButton(
            tooltip: lang.t('Fungua Skrini Kamili', 'Full Screen'),
            icon: const Icon(Icons.fullscreen_rounded, color: TevdaColors.forestGreen),
            onPressed: () => _openFullScreenId(context),
          ),
          IconButton(
            tooltip: lang.t('Pakua Cheti cha Uanachama', 'Download Certificate'),
            icon: const Icon(Icons.workspace_premium_rounded, color: TevdaColors.goldDark),
            onPressed: () => _openCertificateModal(context),
          ),
          const SizedBox(width: 8),
        ],
      ),
      body: SingleChildScrollView(
        padding: const EdgeInsets.symmetric(horizontal: 20, vertical: 16),
        child: Column(
          children: [
            // Status Switcher Bar (Auditor / Inspector Demo Tool)
            Container(
              padding: const EdgeInsets.symmetric(horizontal: 14, vertical: 8),
              decoration: BoxDecoration(
                color: Colors.white,
                borderRadius: BorderRadius.circular(12),
                border: Border.all(color: const Color(0xFFE2E8F0)),
              ),
              child: Row(
                children: [
                  Icon(Icons.shield_outlined, size: 18, color: TevdaColors.slate500),
                  const SizedBox(width: 8),
                  Text(
                    lang.t('Hali ya Kadi:', 'Card Status:'),
                    style: const TextStyle(fontSize: 12, fontWeight: FontWeight.w600, color: TevdaColors.slate600),
                  ),
                  const Spacer(),
                  _buildStatusChip('active', lang.t('Hai (Active)', 'Active'), TevdaColors.forestGreen),
                  const SizedBox(width: 6),
                  _buildStatusChip('expired', lang.t('Muda Umeisha', 'Expired'), TevdaColors.goldDark),
                  const SizedBox(width: 6),
                  _buildStatusChip('suspended', lang.t('Imesitishwa', 'Suspended'), TevdaColors.error),
                ],
              ),
            ),
            const SizedBox(height: 20),

            // Flip Card Interaction
            GestureDetector(
              onTap: () => setState(() => _isCardFlipped = !_isCardFlipped),
              child: AnimatedSwitcher(
                duration: const Duration(milliseconds: 300),
                child: _isCardFlipped ? _buildCardBack() : _buildCardFront(),
              ),
            ),
            const SizedBox(height: 12),
            Row(
              mainAxisAlignment: MainAxisAlignment.center,
              children: [
                Icon(Icons.touch_app_rounded, size: 16, color: TevdaColors.slate400),
                const SizedBox(width: 6),
                Text(
                  lang.t('Gusa kadi kugeuza (Mbele / Nyuma)', 'Tap card to flip (Front / Back)'),
                  style: const TextStyle(fontSize: 12, color: TevdaColors.slate500),
                ),
              ],
            ),
            const SizedBox(height: 24),

            // Quick Actions: Certificate & Share
            Row(
              children: [
                Expanded(
                  child: ElevatedButton.icon(
                    onPressed: () => _openCertificateModal(context),
                    icon: const Icon(Icons.verified_rounded, size: 18),
                    label: Text(lang.t('Cheti Rasmi (PDF)', 'Official PDF Cert')),
                    style: ElevatedButton.styleFrom(
                      backgroundColor: TevdaColors.forestGreen,
                      foregroundColor: Colors.white,
                      padding: const EdgeInsets.symmetric(vertical: 14),
                      shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(14)),
                    ),
                  ),
                ),
                const SizedBox(width: 12),
                Expanded(
                  child: OutlinedButton.icon(
                    onPressed: () => _openFullScreenId(context),
                    icon: const Icon(Icons.qr_code_scanner_rounded, size: 18),
                    label: Text(lang.t('Ukaguzi wa LATRA', 'LATRA Inspection')),
                    style: OutlinedButton.styleFrom(
                      foregroundColor: TevdaColors.forestGreen,
                      side: const BorderSide(color: TevdaColors.forestGreen, width: 1.5),
                      padding: const EdgeInsets.symmetric(vertical: 14),
                      shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(14)),
                    ),
                  ),
                ),
              ],
            ),
            const SizedBox(height: 24),

            // Security & Legal Verification Info Card
            Container(
              padding: const EdgeInsets.all(16),
              decoration: BoxDecoration(
                color: Colors.white,
                borderRadius: BorderRadius.circular(16),
                border: Border.all(color: const Color(0xFFE2E8F0)),
              ),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Row(
                    children: [
                      Container(
                        padding: const EdgeInsets.all(6),
                        decoration: BoxDecoration(
                          color: TevdaColors.primarySurface,
                          borderRadius: BorderRadius.circular(8),
                        ),
                        child: const Icon(Icons.lock_outline_rounded, color: TevdaColors.forestGreen, size: 18),
                      ),
                      const SizedBox(width: 10),
                      Text(
                        lang.t('Uthibitisho na Ulinzi wa Taarifa', 'Data Privacy & Verification'),
                        style: const TextStyle(fontSize: 14, fontWeight: FontWeight.w800, color: TevdaColors.slate900),
                      ),
                    ],
                  ),
                  const SizedBox(height: 10),
                  Text(
                    lang.t(
                      '• Kitambulisho hiki kinalindwa na Sheria ya Ulinzi wa Taarifa Binafsi (2022).\n• QR Code haionyeshi wala kuhifadhi namba ya NIDA ili kulinda usalama wako.\n• Inatambulika kisheria na Mamlaka ya Udhibiti Usafiri Ardhini (LATRA) na Jeshi la Polisi Usalama Barabarani.',
                      '• This ID is protected under the Personal Data Protection Act (2022).\n• QR Code verification does not expose your NIDA number.\n• Officially recognized by LATRA and Traffic Police.',
                    ),
                    style: const TextStyle(fontSize: 12, color: TevdaColors.slate600, height: 1.5),
                  ),
                ],
              ),
            ),
          ],
        ),
      ),
    );
  }

  Widget _buildStatusChip(String state, String label, Color color) {
    final isSelected = _cardState == state;
    return InkWell(
      onTap: () => setState(() => _cardState = state),
      child: Container(
        padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 4),
        decoration: BoxDecoration(
          color: isSelected ? color : Colors.transparent,
          borderRadius: BorderRadius.circular(6),
          border: Border.all(color: color, width: 1),
        ),
        child: Text(
          label,
          style: TextStyle(
            fontSize: 10,
            fontWeight: FontWeight.w700,
            color: isSelected ? Colors.white : color,
          ),
        ),
      ),
    );
  }

  Widget _buildCardFront() {
    return Container(
      key: const ValueKey('front'),
      width: double.infinity,
      height: 220,
      decoration: BoxDecoration(
        gradient: LinearGradient(
          colors: _cardState == 'active'
              ? [const Color(0xFF075B37), const Color(0xFF033821)]
              : _cardState == 'expired'
                  ? [const Color(0xFF78350F), const Color(0xFF451A03)]
                  : [const Color(0xFF991B1B), const Color(0xFF450A0A)],
          begin: Alignment.topLeft,
          end: Alignment.bottomRight,
        ),
        borderRadius: BorderRadius.circular(20),
        boxShadow: [
          BoxShadow(
            color: Colors.black.withValues(alpha: 0.25),
            blurRadius: 18,
            offset: const Offset(0, 8),
          ),
        ],
      ),
      child: Stack(
        children: [
          // Background Gold Trim & Wave Accent
          Positioned(
            right: -40,
            bottom: -40,
            child: Container(
              width: 180,
              height: 180,
              decoration: BoxDecoration(
                shape: BoxShape.circle,
                color: Colors.white.withValues(alpha: 0.04),
              ),
            ),
          ),

          Padding(
            padding: const EdgeInsets.all(18),
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                // Card Header
                Row(
                  mainAxisAlignment: MainAxisAlignment.spaceBetween,
                  children: [
                    Row(
                      children: [
                        Container(
                          width: 36,
                          height: 36,
                          decoration: BoxDecoration(
                            color: Colors.white,
                            borderRadius: BorderRadius.circular(8),
                          ),
                          padding: const EdgeInsets.all(2),
                          child: ClipRRect(
                            borderRadius: BorderRadius.circular(6),
                            child: Image.asset(
                              'assets/tevda-logo.png',
                              fit: BoxFit.contain,
                            ),
                          ),
                        ),
                        const SizedBox(width: 10),
                        Column(
                          crossAxisAlignment: CrossAxisAlignment.start,
                          children: const [
                            Text(
                              'TEVDA',
                              style: TextStyle(
                                color: Colors.white,
                                fontWeight: FontWeight.w900,
                                fontSize: 15,
                                letterSpacing: 1.2,
                              ),
                            ),
                            Text(
                              'SMART DRIVER IDENTITY',
                              style: TextStyle(
                                color: TevdaColors.gold,
                                fontWeight: FontWeight.w700,
                                fontSize: 8.5,
                                letterSpacing: 0.8,
                              ),
                            ),
                          ],
                        ),
                      ],
                    ),
                    Container(
                      padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 3),
                      decoration: BoxDecoration(
                        color: _cardState == 'active'
                            ? TevdaColors.emeraldGreen
                            : _cardState == 'expired'
                                ? TevdaColors.goldDark
                                : TevdaColors.error,
                        borderRadius: BorderRadius.circular(20),
                      ),
                      child: Text(
                        _cardState == 'active'
                            ? '● ACTIVE'
                            : _cardState == 'expired'
                                ? '● EXPIRED'
                                : '● SUSPENDED',
                        style: const TextStyle(
                          color: Colors.white,
                          fontSize: 9,
                          fontWeight: FontWeight.w900,
                          letterSpacing: 0.5,
                        ),
                      ),
                    ),
                  ],
                ),
                const Spacer(),

                // Middle Row: Photo + Details + QR
                Row(
                  children: [
                    // Member Photo
                    Container(
                      width: 62,
                      height: 62,
                      decoration: BoxDecoration(
                        color: Colors.white,
                        borderRadius: BorderRadius.circular(12),
                        border: Border.all(color: TevdaColors.gold, width: 2),
                      ),
                      child: ClipRRect(
                        borderRadius: BorderRadius.circular(10),
                        child: const Icon(Icons.person, size: 48, color: TevdaColors.forestGreen),
                      ),
                    ),
                    const SizedBox(width: 14),

                    // Details
                    Expanded(
                      child: Column(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          Text(
                            _memberName,
                            style: const TextStyle(
                              color: Colors.white,
                              fontSize: 16,
                              fontWeight: FontWeight.w900,
                            ),
                          ),
                          const SizedBox(height: 2),
                          Text(
                            _memberNumber,
                            style: const TextStyle(
                              color: TevdaColors.gold,
                              fontSize: 12,
                              fontWeight: FontWeight.w700,
                              letterSpacing: 0.5,
                            ),
                          ),
                          const SizedBox(height: 2),
                          Text(
                            _vehicleInfo,
                            style: TextStyle(
                              color: Colors.white.withValues(alpha: 0.85),
                              fontSize: 10.5,
                              fontWeight: FontWeight.w500,
                            ),
                          ),
                        ],
                      ),
                    ),

                    // QR Mini Widget
                    Container(
                      padding: const EdgeInsets.all(4),
                      decoration: BoxDecoration(
                        color: Colors.white,
                        borderRadius: BorderRadius.circular(8),
                      ),
                      child: const Icon(Icons.qr_code_2_rounded, size: 48, color: Color(0xFF0F172A)),
                    ),
                  ],
                ),
                const Spacer(),

                // Card Footer
                Row(
                  mainAxisAlignment: MainAxisAlignment.spaceBetween,
                  children: [
                    Text(
                      'CATEGORY: $_category',
                      style: TextStyle(
                        color: Colors.white.withValues(alpha: 0.7),
                        fontSize: 9.5,
                        fontWeight: FontWeight.w700,
                      ),
                    ),
                    Text(
                      'VALID THRU: $_validUntil',
                      style: const TextStyle(
                        color: TevdaColors.gold,
                        fontSize: 9.5,
                        fontWeight: FontWeight.w800,
                      ),
                    ),
                  ],
                ),
              ],
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildCardBack() {
    return Container(
      key: const ValueKey('back'),
      width: double.infinity,
      height: 220,
      decoration: BoxDecoration(
        color: const Color(0xFF1E293B),
        borderRadius: BorderRadius.circular(20),
        boxShadow: [
          BoxShadow(
            color: Colors.black.withValues(alpha: 0.2),
            blurRadius: 16,
            offset: const Offset(0, 6),
          ),
        ],
      ),
      child: Padding(
        padding: const EdgeInsets.all(18),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Row(
              mainAxisAlignment: MainAxisAlignment.spaceBetween,
              children: const [
                Text(
                  'TANZANIA ELECTRIC VEHICLE DRIVERS ASSOCIATION',
                  style: TextStyle(color: Colors.white70, fontSize: 8.5, fontWeight: FontWeight.w800),
                ),
                Text('P.O. BOX 1400 DSM', style: TextStyle(color: Colors.white38, fontSize: 8)),
              ],
            ),
            const SizedBox(height: 10),
            Container(
              height: 34,
              width: double.infinity,
              color: Colors.black45,
            ),
            const SizedBox(height: 12),
            const Text(
              'TERMS OF MEMBERSHIP & VERIFICATION:',
              style: TextStyle(color: TevdaColors.gold, fontSize: 9, fontWeight: FontWeight.w800),
            ),
            const SizedBox(height: 4),
            Text(
              '1. This digital smart credential confirms authorized membership with TEVDA.\n2. Law enforcement officers can scan the official QR code to inspect driver validation status.\n3. Found lost? Return to TEVDA National HQ or contact +255 750 000 000.',
              style: TextStyle(color: Colors.white.withValues(alpha: 0.7), fontSize: 8.5, height: 1.4),
            ),
            const Spacer(),
            Row(
              mainAxisAlignment: MainAxisAlignment.spaceBetween,
              children: [
                Text('ISSUED: $_issuedDate', style: const TextStyle(color: Colors.white38, fontSize: 8)),
                const Text('SIGNATURE: Authorized Secretary General', style: TextStyle(color: Colors.white54, fontSize: 8)),
              ],
            ),
          ],
        ),
      ),
    );
  }

  void _openFullScreenId(BuildContext context) {
    showDialog(
      context: context,
      builder: (ctx) {
        return Dialog(
          backgroundColor: Colors.transparent,
          insetPadding: const EdgeInsets.all(16),
          child: Container(
            padding: const EdgeInsets.all(24),
            decoration: BoxDecoration(
              color: Colors.white,
              borderRadius: BorderRadius.circular(24),
            ),
            child: Column(
              mainAxisSize: MainAxisSize.min,
              children: [
                Row(
                  mainAxisAlignment: MainAxisAlignment.spaceBetween,
                  children: [
                    const Text(
                      'LATRA & POLICE INSPECTION',
                      style: TextStyle(fontSize: 13, fontWeight: FontWeight.w900, color: TevdaColors.forestGreen),
                    ),
                    IconButton(
                      icon: const Icon(Icons.close),
                      onPressed: () => Navigator.pop(ctx),
                    ),
                  ],
                ),
                const SizedBox(height: 12),
                Container(
                  padding: const EdgeInsets.all(16),
                  decoration: BoxDecoration(
                    color: Colors.white,
                    borderRadius: BorderRadius.circular(16),
                    border: Border.all(color: const Color(0xFFE2E8F0), width: 2),
                  ),
                  child: Column(
                    children: [
                      const Icon(Icons.qr_code_2_rounded, size: 180, color: Color(0xFF0F172A)),
                      const SizedBox(height: 10),
                      Text(
                        _verificationUrl,
                        textAlign: TextAlign.center,
                        style: const TextStyle(fontSize: 10, color: TevdaColors.slate500),
                      ),
                    ],
                  ),
                ),
                const SizedBox(height: 16),
                Text(
                  _memberName,
                  style: const TextStyle(fontSize: 20, fontWeight: FontWeight.w900, color: TevdaColors.slate900),
                ),
                Text(
                  '$_memberNumber • $_category',
                  style: const TextStyle(fontSize: 13, fontWeight: FontWeight.w700, color: TevdaColors.forestGreen),
                ),
                const SizedBox(height: 6),
                Text(
                  _vehicleInfo,
                  style: const TextStyle(fontSize: 13, color: TevdaColors.slate600),
                ),
                const SizedBox(height: 16),
                Container(
                  padding: const EdgeInsets.symmetric(horizontal: 14, vertical: 8),
                  decoration: BoxDecoration(
                    color: TevdaColors.primarySurface,
                    borderRadius: BorderRadius.circular(10),
                  ),
                  child: Row(
                    mainAxisSize: MainAxisSize.min,
                    children: const [
                      Icon(Icons.check_circle, color: TevdaColors.forestGreen, size: 16),
                      SizedBox(width: 6),
                      Text(
                        'VERIFIED OFFICIAL CREDENTIAL',
                        style: TextStyle(color: TevdaColors.forestGreen, fontWeight: FontWeight.w800, fontSize: 11),
                      ),
                    ],
                  ),
                ),
              ],
            ),
          ),
        );
      },
    );
  }

  void _openCertificateModal(BuildContext context) {
    showModalBottomSheet(
      context: context,
      isScrollControlled: true,
      backgroundColor: Colors.transparent,
      builder: (ctx) {
        return Container(
          height: MediaQuery.of(ctx).size.height * 0.85,
          decoration: const BoxDecoration(
            color: Colors.white,
            borderRadius: BorderRadius.vertical(top: Radius.circular(24)),
          ),
          padding: const EdgeInsets.all(24),
          child: Column(
            children: [
              Center(
                child: Container(
                  width: 40,
                  height: 4,
                  decoration: BoxDecoration(
                    color: TevdaColors.slate200,
                    borderRadius: BorderRadius.circular(2),
                  ),
                ),
              ),
              const SizedBox(height: 16),
              Row(
                mainAxisAlignment: MainAxisAlignment.spaceBetween,
                children: [
                  const Text(
                    'CHETI RASMI CHA UANACHAMA',
                    style: TextStyle(fontWeight: FontWeight.w900, fontSize: 15, color: TevdaColors.forestGreen),
                  ),
                  IconButton(
                    icon: const Icon(Icons.close),
                    onPressed: () => Navigator.pop(ctx),
                  ),
                ],
              ),
              const Divider(),
              Expanded(
                child: SingleChildScrollView(
                  child: Container(
                    padding: const EdgeInsets.all(20),
                    decoration: BoxDecoration(
                      color: const Color(0xFFFDFBF7),
                      borderRadius: BorderRadius.circular(16),
                      border: Border.all(color: TevdaColors.gold, width: 2),
                    ),
                    child: Column(
                      children: [
                        const Icon(Icons.workspace_premium_rounded, size: 48, color: TevdaColors.goldDark),
                        const SizedBox(height: 8),
                        const Text(
                          'TANZANIA ELECTRIC VEHICLE DRIVERS ASSOCIATION',
                          textAlign: TextAlign.center,
                          style: TextStyle(fontWeight: FontWeight.w900, fontSize: 13, color: TevdaColors.forestGreen),
                        ),
                        const Text(
                          'CERTIFICATE OF OFFICIAL MEMBERSHIP',
                          textAlign: TextAlign.center,
                          style: TextStyle(fontWeight: FontWeight.w700, fontSize: 11, color: TevdaColors.goldDark),
                        ),
                        const SizedBox(height: 16),
                        const Text(
                          'Hii inathibitisha kuwa ndugu:',
                          style: TextStyle(fontSize: 12, color: TevdaColors.slate600, fontStyle: FontStyle.italic),
                        ),
                        const SizedBox(height: 6),
                        Text(
                          _memberName,
                          style: const TextStyle(fontSize: 22, fontWeight: FontWeight.w900, color: TevdaColors.slate900),
                        ),
                        const SizedBox(height: 6),
                        Text(
                          'Namba ya Uanachama: $_memberNumber',
                          style: const TextStyle(fontSize: 12, fontWeight: FontWeight.w700, color: TevdaColors.forestGreen),
                        ),
                        const SizedBox(height: 12),
                        const Text(
                          'Ametimiza masharti yote ya Katiba ya TEVDA na kusajiliwa rasmi kama:',
                          textAlign: TextAlign.center,
                          style: TextStyle(fontSize: 12, color: TevdaColors.slate700),
                        ),
                        const SizedBox(height: 6),
                        Container(
                          padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 6),
                          decoration: BoxDecoration(
                            color: TevdaColors.primarySurface,
                            borderRadius: BorderRadius.circular(20),
                          ),
                          child: Text(
                            _category.toUpperCase(),
                            style: const TextStyle(fontWeight: FontWeight.w900, color: TevdaColors.forestGreen, fontSize: 13),
                          ),
                        ),
                        const SizedBox(height: 16),
                        Row(
                          mainAxisAlignment: MainAxisAlignment.spaceBetween,
                          children: [
                            Column(
                              crossAxisAlignment: CrossAxisAlignment.start,
                              children: const [
                                Text('Tarehe ya Kuanza:', style: TextStyle(fontSize: 10, color: TevdaColors.slate500)),
                                Text('01 Januari 2026', style: TextStyle(fontSize: 11, fontWeight: FontWeight.w700)),
                              ],
                            ),
                            Column(
                              crossAxisAlignment: CrossAxisAlignment.end,
                              children: [
                                const Text('Mwisho wa Muda:', style: TextStyle(fontSize: 10, color: TevdaColors.slate500)),
                                Text(_validUntil, style: const TextStyle(fontSize: 11, fontWeight: FontWeight.w700)),
                              ],
                            ),
                          ],
                        ),
                        const SizedBox(height: 20),
                        Row(
                          mainAxisAlignment: MainAxisAlignment.spaceAround,
                          children: [
                            Column(
                              children: const [
                                Text('_____________________', style: TextStyle(color: TevdaColors.slate400)),
                                Text('Mwenyekiti wa Taifa', style: TextStyle(fontSize: 10, fontWeight: FontWeight.w700)),
                              ],
                            ),
                            Column(
                              children: const [
                                Text('_____________________', style: TextStyle(color: TevdaColors.slate400)),
                                Text('Katibu Mkuu', style: TextStyle(fontSize: 10, fontWeight: FontWeight.w700)),
                              ],
                            ),
                          ],
                        ),
                      ],
                    ),
                  ),
                ),
              ),
              const SizedBox(height: 16),
              Row(
                children: [
                  Expanded(
                    child: ElevatedButton.icon(
                      onPressed: () {
                        Navigator.pop(ctx);
                        ScaffoldMessenger.of(context).showSnackBar(
                          const SnackBar(
                            content: Text('Cheti kinapakuliwa (PDF imehifadhiwa)...'),
                            backgroundColor: TevdaColors.forestGreen,
                          ),
                        );
                      },
                      icon: const Icon(Icons.download_rounded, color: Colors.white),
                      label: const Text('Pakua Cheti (PDF)'),
                      style: ElevatedButton.styleFrom(
                        backgroundColor: TevdaColors.forestGreen,
                        padding: const EdgeInsets.symmetric(vertical: 14),
                      ),
                    ),
                  ),
                  const SizedBox(width: 12),
                  IconButton(
                    onPressed: () {
                      ScaffoldMessenger.of(context).showSnackBar(
                        const SnackBar(content: Text('Kiunganishi cha cheti kimenakiliwa!')),
                      );
                    },
                    icon: const Icon(Icons.share_rounded, color: TevdaColors.forestGreen),
                  ),
                ],
              ),
            ],
          ),
        );
      },
    );
  }
}
