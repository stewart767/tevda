import 'package:flutter/material.dart';
import '../../core/theme.dart';
import '../../core/localization.dart';
import '../../core/api_service.dart';
import '../vehicles/vehicles_screen.dart';
import '../payments/payment_screen.dart';
import '../complaints/complaint_screen.dart';
import '../tracking/tracking_screen.dart';

class ProfileScreen extends StatefulWidget {
  final Function(int)? onNavigateTab;
  final VoidCallback? onLogout;

  const ProfileScreen({super.key, this.onNavigateTab, this.onLogout});

  @override
  State<ProfileScreen> createState() => _ProfileScreenState();
}

class _ProfileScreenState extends State<ProfileScreen> {
  bool _biometricsEnabled = true;
  String _name = 'Mwanachama wa TEVDA';
  String _memberNumber = 'TEVDA-2026-XXXX';
  String _category = 'Full Member • LATRA Certified';
  String _phone = '';
  String _status = 'active';

  @override
  void initState() {
    super.initState();
    _fetchProfile();
  }

  Future<void> _fetchProfile() async {
    try {
      final res = await ApiService.instance.getProfile();
      if (!mounted) return;
      if (res.success && res.data != null && res.data is Map) {
        final d = res.data as Map;
        setState(() {
          if (d['name'] != null) _name = d['name'];
          if (d['member_number'] != null) _memberNumber = d['member_number'];
          if (d['category'] != null) _category = '${d['category']} • LATRA Certified';
          if (d['phone'] != null) _phone = d['phone'];
          if (d['status'] != null) _status = d['status'];
        });
      } else if (ApiService.instance.currentUser != null) {
        final u = ApiService.instance.currentUser!;
        setState(() {
          _name = u['name'] ?? 'Mwanachama wa TEVDA';
          _phone = u['phone'] ?? '';
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
          lang.t('Wasifu na Mipangilio', 'Profile & Settings'),
          style: const TextStyle(fontWeight: FontWeight.w800, fontSize: 18),
        ),
      ),
      body: SingleChildScrollView(
        padding: const EdgeInsets.symmetric(horizontal: 20, vertical: 16),
        child: Column(
          children: [
            // Driver Profile Header Card
            Container(
              padding: const EdgeInsets.all(20),
              decoration: BoxDecoration(
                color: Colors.white,
                borderRadius: BorderRadius.circular(20),
                border: Border.all(color: const Color(0xFFE2E8F0)),
                boxShadow: [
                  BoxShadow(
                    color: Colors.black.withValues(alpha: 0.02),
                    blurRadius: 10,
                    offset: const Offset(0, 4),
                  ),
                ],
              ),
              child: Row(
                children: [
                  Container(
                    width: 64,
                    height: 64,
                    decoration: BoxDecoration(
                      color: TevdaColors.primarySurface,
                      shape: BoxShape.circle,
                      border: Border.all(color: TevdaColors.forestGreen, width: 2),
                    ),
                    child: const Icon(Icons.person_rounded, size: 40, color: TevdaColors.forestGreen),
                  ),
                  const SizedBox(width: 16),
                  Expanded(
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        Text(
                          _name,
                          style: const TextStyle(fontSize: 17, fontWeight: FontWeight.w900, color: TevdaColors.slate900),
                        ),
                        const SizedBox(height: 2),
                        Text(
                          _memberNumber,
                          style: const TextStyle(fontSize: 12, fontWeight: FontWeight.w700, color: TevdaColors.forestGreen),
                        ),
                        if (_phone.isNotEmpty) ...[
                          const SizedBox(height: 2),
                          Text(
                            _phone,
                            style: const TextStyle(fontSize: 11, color: TevdaColors.slate500, fontWeight: FontWeight.w600),
                          ),
                        ],
                        const SizedBox(height: 4),
                        Container(
                          padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 3),
                          decoration: BoxDecoration(
                            color: TevdaColors.primarySurface,
                            borderRadius: BorderRadius.circular(6),
                          ),
                          child: Text(
                            _category,
                            style: const TextStyle(fontSize: 10, fontWeight: FontWeight.w800, color: TevdaColors.forestGreen),
                          ),
                        ),
                      ],
                    ),
                  ),
                ],
              ),
            ),
            const SizedBox(height: 20),

            // Modules Quick Access
            _buildSectionHeader(lang.t('Usimamizi wa Uanachama', 'Membership Management')),

            _buildMenuItem(
              icon: Icons.track_changes_rounded,
              title: lang.t('Hali ya Maombi (Application Tracking)', 'Application Tracking'),
              subtitle: lang.t('Fuatilia maombi, nyaraka na idhini', 'Track review & approval stage'),
              onTap: () {
                Navigator.push(
                  context,
                  MaterialPageRoute(
                    builder: (_) => TrackingScreen(onLogout: widget.onLogout),
                  ),
                );
              },
            ),

            _buildMenuItem(
              icon: Icons.folder_shared_rounded,
              title: lang.t('Nyaraka Zangu (My Documents)', 'My Documents'),
              subtitle: lang.t('Vyeti, leseni, NIDA na bima', 'Certificates, licence, NIDA & insurance'),
              onTap: () => _showDocumentsSheet(context, lang),
            ),

            _buildMenuItem(
              icon: Icons.electric_moped_rounded,
              title: lang.t('Vyombo Vyangu vya Umeme (EVs)', 'My Electric Vehicles'),
              subtitle: lang.t('Orodha ya vyombo na usajili', 'Registered vehicles & specifications'),
              onTap: () {
                Navigator.push(context, MaterialPageRoute(builder: (_) => const VehiclesScreen()));
              },
            ),

            _buildMenuItem(
              icon: Icons.receipt_long_rounded,
              title: lang.t('Ada ya Uanachama na Risiti', 'Fees & Official Receipts'),
              subtitle: lang.t('Control Number & Historia', 'Control numbers & history'),
              onTap: () {
                Navigator.push(context, MaterialPageRoute(builder: (_) => const PaymentScreen()));
              },
            ),

            _buildMenuItem(
              icon: Icons.shield_rounded,
              title: lang.t('Ulinzi, Usalama na Malalamiko', 'Safety & Incident Reporting'),
              subtitle: lang.t('Ripoti kwa siri bila jina', 'Anonymous whistleblowing'),
              onTap: () {
                Navigator.push(context, MaterialPageRoute(builder: (_) => const ComplaintScreen()));
              },
            ),
            const SizedBox(height: 16),

            // Preferences
            _buildSectionHeader(lang.t('Mipangilio ya Mfumo', 'System Preferences')),
            Container(
              padding: const EdgeInsets.all(16),
              decoration: BoxDecoration(
                color: Colors.white,
                borderRadius: BorderRadius.circular(16),
                border: Border.all(color: const Color(0xFFE2E8F0)),
              ),
              child: Column(
                children: [
                  // Language Toggle
                  Row(
                    mainAxisAlignment: MainAxisAlignment.spaceBetween,
                    children: [
                      Row(
                        children: [
                          const Icon(Icons.language_rounded, color: TevdaColors.forestGreen, size: 22),
                          const SizedBox(width: 12),
                          Column(
                            crossAxisAlignment: CrossAxisAlignment.start,
                            children: [
                              Text(lang.t('Lugha ya Mfumo', 'App Language'), style: const TextStyle(fontWeight: FontWeight.w800, fontSize: 13)),
                              Text(lang.isSwahili ? 'Kiswahili (Tanzania)' : 'English', style: const TextStyle(fontSize: 11, color: TevdaColors.slate500)),
                            ],
                          ),
                        ],
                      ),
                      TextButton(
                        onPressed: () => setState(() => lang.toggleLanguage()),
                        child: Text(
                          lang.isSwahili ? 'Change to English' : 'Badili Kiswahili',
                          style: const TextStyle(fontWeight: FontWeight.w800, fontSize: 12, color: TevdaColors.forestGreen),
                        ),
                      ),
                    ],
                  ),
                  const Divider(),

                  // Biometrics Toggle
                  Row(
                    mainAxisAlignment: MainAxisAlignment.spaceBetween,
                    children: [
                      Row(
                        children: [
                          const Icon(Icons.fingerprint_rounded, color: TevdaColors.forestGreen, size: 22),
                          const SizedBox(width: 12),
                          Column(
                            crossAxisAlignment: CrossAxisAlignment.start,
                            children: [
                              Text(lang.t('Kufungua kwa Alama ya Kidole', 'Biometric Unlock'), style: const TextStyle(fontWeight: FontWeight.w800, fontSize: 13)),
                              Text(lang.t('Ulinzi wa haraka wa Smart ID', 'Quick biometric access'), style: const TextStyle(fontSize: 11, color: TevdaColors.slate500)),
                            ],
                          ),
                        ],
                      ),
                      Switch(
                        value: _biometricsEnabled,
                        activeColor: TevdaColors.forestGreen,
                        onChanged: (val) => setState(() => _biometricsEnabled = val),
                      ),
                    ],
                  ),
                ],
              ),
            ),
            const SizedBox(height: 16),

            // Support & Sign Out
            _buildSectionHeader(lang.t('Msaada na Huduma kwa Wateja', 'Support & Hotline')),
            _buildMenuItem(
              icon: Icons.support_agent_rounded,
              title: lang.t('Dawati la Dharura la TEVDA', 'TEVDA Emergency Dispatch'),
              subtitle: '+255 750 000 000 (Muda wote 24/7)',
              onTap: () {
                ScaffoldMessenger.of(context).showSnackBar(
                  const SnackBar(content: Text('Inapiga simu namba ya dharura ya TEVDA: +255 750 000 000')),
                );
              },
            ),
            const SizedBox(height: 10),

            SizedBox(
              width: double.infinity,
              child: TextButton.icon(
                onPressed: () => _confirmLogout(context),
                icon: const Icon(Icons.logout_rounded, color: TevdaColors.error),
                label: Text(
                  lang.t('Ondoka Kwenye Mfumo (Sign Out)', 'Sign Out'),
                  style: const TextStyle(color: TevdaColors.error, fontWeight: FontWeight.w800),
                ),
              ),
            ),
            const SizedBox(height: 20),
          ],
        ),
      ),
    );
  }

  Widget _buildSectionHeader(String title) {
    return Align(
      alignment: Alignment.centerLeft,
      child: Padding(
        padding: const EdgeInsets.only(left: 4, bottom: 8, top: 4),
        child: Text(
          title,
          style: const TextStyle(fontSize: 13, fontWeight: FontWeight.w800, color: TevdaColors.slate700),
        ),
      ),
    );
  }

  Widget _buildMenuItem({
    required IconData icon,
    required String title,
    required String subtitle,
    required VoidCallback onTap,
  }) {
    return Container(
      margin: const EdgeInsets.only(bottom: 8),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(14),
        border: Border.all(color: const Color(0xFFE2E8F0)),
      ),
      child: Material(
        color: Colors.transparent,
        borderRadius: BorderRadius.circular(14),
        child: ListTile(
          leading: Container(
            padding: const EdgeInsets.all(8),
            decoration: BoxDecoration(
              color: TevdaColors.primarySurface,
              borderRadius: BorderRadius.circular(8),
            ),
            child: Icon(icon, color: TevdaColors.forestGreen, size: 20),
          ),
          title: Text(title, style: const TextStyle(fontSize: 13.5, fontWeight: FontWeight.w800, color: TevdaColors.slate900)),
          subtitle: Text(subtitle, style: const TextStyle(fontSize: 11, color: TevdaColors.slate500)),
          trailing: const Icon(Icons.chevron_right_rounded, color: TevdaColors.slate400),
          onTap: onTap,
        ),
      ),
    );
  }

  void _showDocumentsSheet(BuildContext context, LanguageController lang) {
    showModalBottomSheet(
      context: context,
      isScrollControlled: true,
      shape: const RoundedRectangleBorder(
        borderRadius: BorderRadius.vertical(top: Radius.circular(24)),
      ),
      builder: (ctx) {
        return FutureBuilder<ApiResponse<dynamic>>(
          future: ApiService.instance.getDocuments(),
          builder: (context, snapshot) {
            return Container(
              padding: const EdgeInsets.all(24),
              constraints: BoxConstraints(
                maxHeight: MediaQuery.of(context).size.height * 0.75,
              ),
              child: Column(
                mainAxisSize: MainAxisSize.min,
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Row(
                    mainAxisAlignment: MainAxisAlignment.spaceBetween,
                    children: [
                      Text(
                        lang.t('Nyaraka Zilizohifadhiwa', 'Uploaded Documents'),
                        style: const TextStyle(fontSize: 17, fontWeight: FontWeight.w900),
                      ),
                      IconButton(
                        icon: const Icon(Icons.close_rounded),
                        onPressed: () => Navigator.pop(ctx),
                      ),
                    ],
                  ),
                  const Divider(),
                  if (snapshot.connectionState == ConnectionState.waiting)
                    const Padding(
                      padding: EdgeInsets.symmetric(vertical: 40),
                      child: Center(child: CircularProgressIndicator(color: TevdaColors.forestGreen)),
                    )
                  else if (snapshot.hasData && snapshot.data!.data is List && (snapshot.data!.data as List).isNotEmpty)
                    Expanded(
                      child: ListView.separated(
                        shrinkWrap: true,
                        itemCount: (snapshot.data!.data as List).length,
                        separatorBuilder: (_, __) => const SizedBox(height: 10),
                        itemBuilder: (context, i) {
                          final doc = (snapshot.data!.data as List)[i];
                          final docType = doc['document_type'] ?? 'Nyaraka';
                          final fileName = doc['file_name'] ?? 'Faili';
                          final status = doc['verification_status'] ?? 'pending';

                          return Container(
                            padding: const EdgeInsets.all(12),
                            decoration: BoxDecoration(
                              color: const Color(0xFFF8FAFC),
                              borderRadius: BorderRadius.circular(12),
                              border: Border.all(color: const Color(0xFFE2E8F0)),
                            ),
                            child: Row(
                              children: [
                                const Icon(Icons.description_rounded, color: TevdaColors.forestGreen, size: 28),
                                const SizedBox(width: 12),
                                Expanded(
                                  child: Column(
                                    crossAxisAlignment: CrossAxisAlignment.start,
                                    children: [
                                      Text(
                                        docType.toString().replaceAll('_', ' ').toUpperCase(),
                                        style: const TextStyle(fontWeight: FontWeight.w800, fontSize: 12),
                                      ),
                                      Text(fileName, style: const TextStyle(fontSize: 11, color: TevdaColors.slate500)),
                                    ],
                                  ),
                                ),
                                Container(
                                  padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 4),
                                  decoration: BoxDecoration(
                                    color: status == 'verified' ? Colors.green.shade50 : Colors.amber.shade50,
                                    borderRadius: BorderRadius.circular(6),
                                  ),
                                  child: Text(
                                    status.toString().toUpperCase(),
                                    style: TextStyle(
                                      fontSize: 10,
                                      fontWeight: FontWeight.w800,
                                      color: status == 'verified' ? Colors.green.shade800 : Colors.amber.shade800,
                                    ),
                                  ),
                                ),
                              ],
                            ),
                          );
                        },
                      ),
                    )
                  else
                    Padding(
                      padding: const EdgeInsets.symmetric(vertical: 30),
                      child: Center(
                        child: Text(
                          lang.t('Hakuna nyaraka zilizopakiwa bado.', 'No documents uploaded yet.'),
                          style: const TextStyle(color: TevdaColors.slate500),
                        ),
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

  void _confirmLogout(BuildContext context) {
    showDialog(
      context: context,
      builder: (ctx) {
        return AlertDialog(
          shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(20)),
          title: const Text('KUTOKA KWENYE MFUMO', style: TextStyle(fontWeight: FontWeight.w900, fontSize: 15)),
          content: const Text('Una uhakika unataka kutoka kwenye akaunti yako ya TEVDA?'),
          actions: [
            TextButton(
              onPressed: () => Navigator.pop(ctx),
              child: const Text('Ghairi'),
            ),
            ElevatedButton(
              onPressed: () async {
                Navigator.pop(ctx);
                await ApiService.instance.logout();
                if (widget.onLogout != null) {
                  widget.onLogout!();
                } else {
                  ScaffoldMessenger.of(context).showSnackBar(
                    const SnackBar(content: Text('Umetoka kwenye akaunti kwa usalama.')),
                  );
                }
              },
              style: ElevatedButton.styleFrom(backgroundColor: TevdaColors.error),
              child: const Text('Ondoka'),
            ),
          ],
        );
      },
    );
  }
}
