import 'package:flutter/material.dart';
import '../../core/theme.dart';
import '../../core/localization.dart';
import '../../core/api_service.dart';
import '../payments/payment_screen.dart';

class TrackingScreen extends StatefulWidget {
  final VoidCallback? onLogout;
  final VoidCallback? onEditApplication;

  const TrackingScreen({
    super.key,
    this.onLogout,
    this.onEditApplication,
  });

  @override
  State<TrackingScreen> createState() => _TrackingScreenState();
}

class _TrackingScreenState extends State<TrackingScreen> {
  bool _isLoading = true;
  Map<String, dynamic>? _statusData;
  String? _errorMessage;

  @override
  void initState() {
    super.initState();
    _loadStatus();
  }

  Future<void> _loadStatus() async {
    setState(() {
      _isLoading = true;
      _errorMessage = null;
    });

    final res = await ApiService.instance.getApplicationStatus();
    if (!mounted) return;

    if (res.success && res.data != null && res.data is Map) {
      setState(() {
        _statusData = Map<String, dynamic>.from(res.data);
        _isLoading = false;
      });
    } else {
      setState(() {
        _errorMessage = res.message;
        _isLoading = false;
      });
    }
  }

  @override
  Widget build(BuildContext context) {
    final lang = LanguageController.instance;

    return Scaffold(
      backgroundColor: TevdaColors.background,
      appBar: AppBar(
        title: Text(
          lang.t('Ufuatiliaji wa Maombi', 'Application Tracking'),
          style: const TextStyle(fontWeight: FontWeight.w800, fontSize: 18),
        ),
        actions: [
          IconButton(
            icon: const Icon(Icons.refresh_rounded),
            tooltip: lang.t('Pakia Upya', 'Refresh'),
            onPressed: _loadStatus,
          ),
          if (widget.onLogout != null)
            IconButton(
              icon: const Icon(Icons.logout_rounded, color: TevdaColors.error),
              tooltip: lang.t('Ondoka', 'Logout'),
              onPressed: () => _confirmLogout(context),
            ),
        ],
      ),
      body: _isLoading
          ? const Center(
              child: Column(
                mainAxisAlignment: MainAxisAlignment.center,
                children: [
                  CircularProgressIndicator(color: TevdaColors.forestGreen),
                  SizedBox(height: 16),
                  Text(
                    'Inapakia hali ya maombi yako...',
                    style: TextStyle(fontWeight: FontWeight.w700, color: TevdaColors.slate600),
                  ),
                ],
              ),
            )
          : _errorMessage != null
              ? _buildErrorView(lang)
              : _buildTrackingContent(lang),
    );
  }

  Widget _buildErrorView(LanguageController lang) {
    return Center(
      child: Padding(
        padding: const EdgeInsets.all(24.0),
        child: Column(
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            const Icon(Icons.error_outline_rounded, size: 64, color: TevdaColors.error),
            const SizedBox(height: 16),
            Text(
              _errorMessage ?? 'Hitilafu ya kupakia taarifa.',
              textAlign: TextAlign.center,
              style: const TextStyle(fontSize: 15, fontWeight: FontWeight.w700, color: TevdaColors.slate800),
            ),
            const SizedBox(height: 24),
            ElevatedButton.icon(
              onPressed: _loadStatus,
              icon: const Icon(Icons.refresh_rounded),
              label: Text(lang.t('Jaribu Tena', 'Try Again')),
              style: ElevatedButton.styleFrom(
                backgroundColor: TevdaColors.forestGreen,
                foregroundColor: Colors.white,
                padding: const EdgeInsets.symmetric(horizontal: 24, vertical: 12),
                shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
              ),
            ),
          ],
        ),
      ),
    );
  }

  Widget _buildTrackingContent(LanguageController lang) {
    final data = _statusData ?? {};
    final status = data['current_status'] as String? ?? 'submitted';
    final refNumber = data['reference_number'] as String? ?? data['member_number'] as String? ?? 'TEVDA-2026-XXXX';
    final category = data['category'] as String? ?? 'Full Member';
    final submissionDate = data['submission_date'] as String? ?? '';
    final controlNo = data['control_number'] as String? ?? '';
    final feeAmount = data['invoice_amount'] ?? 50000;
    final reviewerNotes = data['required_corrections'] as String? ?? data['administrative_messages'] as String?;
    final history = (data['status_history'] as List?) ?? [];

    Color statusColor = TevdaColors.forestGreen;
    String statusTitle = 'Maombi Yamewasilishwa';
    String statusSubtitle = 'Maombi yako yanapitiwa na Sekretarieti ya TEVDA.';
    IconData statusIcon = Icons.hourglass_top_rounded;

    switch (status) {
      case 'draft':
        statusColor = Colors.orange;
        statusTitle = 'Rasimu ya Maombi';
        statusSubtitle = 'Maombi yako hayajakamilika. Tafadhali kamilisha na uwasilishe.';
        statusIcon = Icons.edit_note_rounded;
        break;
      case 'submitted':
        statusColor = const Color(0xFF0284C7);
        statusTitle = 'Yamewasilishwa Rasmi';
        statusSubtitle = 'Maombi yako yamepokelewa na yanapitiwa na Sekretarieti.';
        statusIcon = Icons.mark_email_read_rounded;
        break;
      case 'payment_pending':
        statusColor = TevdaColors.gold;
        statusTitle = 'Inasubiri Malipo ya Ada';
        statusSubtitle = 'Tafadhali lipia ada ya uanachama kwa Control Number ya GePG.';
        statusIcon = Icons.payment_rounded;
        break;
      case 'payment_confirmed':
      case 'under_review':
        statusColor = const Color(0xFF0D9488);
        statusTitle = 'Uhakiki Unaendelea';
        statusSubtitle = 'Malipo yamethibitishwa. Nyaraka na vyeti vinahakikiwa.';
        statusIcon = Icons.fact_check_rounded;
        break;
      case 'documents_incomplete':
      case 'additional_info_required':
        statusColor = TevdaColors.error;
        statusTitle = 'Marekebisho Yanahitajika';
        statusSubtitle = reviewerNotes ?? 'Kuna taarifa au nyaraka zinazohitaji kusahihishwa.';
        statusIcon = Icons.warning_amber_rounded;
        break;
      case 'approved':
      case 'active':
        statusColor = TevdaColors.forestGreen;
        statusTitle = 'Maombi Yameidhinishwa';
        statusSubtitle = 'Hongera! Uanachama wako umeidhinishwa na Kitambulisho cha Kidijitali kiko hai.';
        statusIcon = Icons.verified_rounded;
        break;
      case 'rejected':
        statusColor = TevdaColors.error;
        statusTitle = 'Maombi Yamekataliwa';
        statusSubtitle = reviewerNotes ?? 'Maombi yako hayakukidhi vigezo vya Katiba ya TEVDA.';
        statusIcon = Icons.cancel_rounded;
        break;
    }

    return SingleChildScrollView(
      padding: const EdgeInsets.symmetric(horizontal: 20, vertical: 16),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          // Application Reference Header Card
          Container(
            padding: const EdgeInsets.all(20),
            decoration: BoxDecoration(
              gradient: LinearGradient(
                colors: [statusColor, statusColor.withValues(alpha: 0.85)],
                begin: Alignment.topLeft,
                end: Alignment.bottomRight,
              ),
              borderRadius: BorderRadius.circular(20),
              boxShadow: [
                BoxShadow(
                  color: statusColor.withValues(alpha: 0.28),
                  blurRadius: 16,
                  offset: const Offset(0, 8),
                ),
              ],
            ),
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Row(
                  mainAxisAlignment: MainAxisAlignment.spaceBetween,
                  children: [
                    Container(
                      padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 4),
                      decoration: BoxDecoration(
                        color: Colors.white.withValues(alpha: 0.2),
                        borderRadius: BorderRadius.circular(20),
                      ),
                      child: Row(
                        children: [
                          Icon(statusIcon, color: Colors.white, size: 16),
                          const SizedBox(width: 6),
                          Text(
                            statusTitle,
                            style: const TextStyle(
                              color: Colors.white,
                              fontWeight: FontWeight.w800,
                              fontSize: 12,
                            ),
                          ),
                        ],
                      ),
                    ),
                    if (submissionDate.isNotEmpty)
                      Text(
                        submissionDate,
                        style: TextStyle(
                          color: Colors.white.withValues(alpha: 0.9),
                          fontSize: 11,
                          fontWeight: FontWeight.w600,
                        ),
                      ),
                  ],
                ),
                const SizedBox(height: 16),
                Text(
                  lang.t('Namba ya Kumbukumbu ya Maombi', 'Application Reference Number'),
                  style: TextStyle(
                    color: Colors.white.withValues(alpha: 0.85),
                    fontSize: 12,
                    fontWeight: FontWeight.w600,
                  ),
                ),
                const SizedBox(height: 4),
                Text(
                  refNumber,
                  style: const TextStyle(
                    color: Colors.white,
                    fontSize: 22,
                    fontWeight: FontWeight.w900,
                    letterSpacing: 1.1,
                  ),
                ),
                const SizedBox(height: 12),
                Container(
                  padding: const EdgeInsets.all(12),
                  decoration: BoxDecoration(
                    color: Colors.black.withValues(alpha: 0.15),
                    borderRadius: BorderRadius.circular(12),
                  ),
                  child: Row(
                    children: [
                      const Icon(Icons.badge_outlined, color: Colors.white, size: 18),
                      const SizedBox(width: 8),
                      Expanded(
                        child: Text(
                          '${lang.t('Kundi la Uanachama', 'Category')}: $category',
                          style: const TextStyle(
                            color: Colors.white,
                            fontSize: 12,
                            fontWeight: FontWeight.w700,
                          ),
                        ),
                      ),
                    ],
                  ),
                ),
              ],
            ),
          ),
          const SizedBox(height: 20),

          // Action Cards (Pay or Edit)
          if (controlNo.isNotEmpty && status != 'approved' && status != 'active')
            _buildGePGPayCard(context, controlNo, feeAmount, lang),

          if (status == 'documents_incomplete' || status == 'additional_info_required')
            _buildCorrectionRequiredCard(reviewerNotes, lang),

          if (status == 'draft')
            _buildResumeDraftCard(lang),

          const SizedBox(height: 20),

          // Lifecycle Tracking Progress Steps
          Text(
            lang.t('Hatua za Uhakiki na Idhini', 'Verification & Approval Pipeline'),
            style: const TextStyle(fontSize: 15, fontWeight: FontWeight.w900, color: TevdaColors.slate800),
          ),
          const SizedBox(height: 12),
          _buildLifecycleStepper(status, lang),

          const SizedBox(height: 24),

          // Official Timeline History
          if (history.isNotEmpty) ...[
            Text(
              lang.t('Historia ya Matukio', 'Timeline History'),
              style: const TextStyle(fontSize: 15, fontWeight: FontWeight.w900, color: TevdaColors.slate800),
            ),
            const SizedBox(height: 12),
            _buildTimelineHistory(history),
            const SizedBox(height: 24),
          ],

          // Official Association Support Information
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
                  child: const Icon(Icons.info_outline_rounded, color: TevdaColors.forestGreen),
                ),
                const SizedBox(width: 14),
                Expanded(
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      Text(
                        lang.t('Msaada wa Sekretarieti', 'Secretariat Support'),
                        style: const TextStyle(fontWeight: FontWeight.w800, fontSize: 13),
                      ),
                      const SizedBox(height: 2),
                      Text(
                        lang.t(
                          'Ukiwa na swali lolote kuhusu maombi yako, wasiliana nasi kupitia support@tevda.or.tz au piga +255 750 000 000.',
                          'For any inquiries regarding your application, contact support@tevda.or.tz or call +255 750 000 000.',
                        ),
                        style: const TextStyle(fontSize: 11, color: TevdaColors.slate600, height: 1.3),
                      ),
                    ],
                  ),
                ),
              ],
            ),
          ),
          const SizedBox(height: 30),
        ],
      ),
    );
  }

  Widget _buildGePGPayCard(BuildContext context, String controlNo, dynamic feeAmount, LanguageController lang) {
    return Container(
      margin: const EdgeInsets.only(bottom: 20),
      padding: const EdgeInsets.all(18),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(18),
        border: Border.all(color: TevdaColors.gold.withValues(alpha: 0.5), width: 1.5),
        boxShadow: [
          BoxShadow(
            color: TevdaColors.gold.withValues(alpha: 0.08),
            blurRadius: 10,
            offset: const Offset(0, 4),
          ),
        ],
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Row(
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
            children: [
              Row(
                children: [
                  Container(
                    padding: const EdgeInsets.all(8),
                    decoration: BoxDecoration(
                      color: TevdaColors.goldSurface,
                      borderRadius: BorderRadius.circular(10),
                    ),
                    child: const Icon(Icons.account_balance_wallet_rounded, color: TevdaColors.gold, size: 20),
                  ),
                  const SizedBox(width: 10),
                  Text(
                    lang.t('Malipo ya Ada ya Usajili', 'Registration Fee Payment'),
                    style: const TextStyle(fontWeight: FontWeight.w900, fontSize: 13.5, color: TevdaColors.slate900),
                  ),
                ],
              ),
              Container(
                padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 3),
                decoration: BoxDecoration(
                  color: Colors.orange.shade50,
                  borderRadius: BorderRadius.circular(6),
                ),
                child: Text(
                  'TZS $feeAmount',
                  style: TextStyle(fontWeight: FontWeight.w900, fontSize: 12, color: Colors.orange.shade800),
                ),
              ),
            ],
          ),
          const SizedBox(height: 14),
          Text(
            lang.t(
              'Maombi yako yanasubiri uthibitisho wa malipo ya ada ya kujiunga. Tumia namba ifuatayo ya GePG:',
              'Your application requires payment confirmation. Use the following GePG control number:',
            ),
            style: const TextStyle(fontSize: 12, color: TevdaColors.slate600, height: 1.3),
          ),
          const SizedBox(height: 12),
          Container(
            padding: const EdgeInsets.symmetric(horizontal: 14, vertical: 10),
            decoration: BoxDecoration(
              color: const Color(0xFFF8FAFC),
              borderRadius: BorderRadius.circular(12),
              border: Border.all(color: const Color(0xFFE2E8F0)),
            ),
            child: Row(
              mainAxisAlignment: MainAxisAlignment.spaceBetween,
              children: [
                Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    const Text('Control Number (GePG)', style: TextStyle(fontSize: 10.5, color: TevdaColors.slate500, fontWeight: FontWeight.w700)),
                    const SizedBox(height: 2),
                    Text(
                      controlNo,
                      style: const TextStyle(
                        fontSize: 16,
                        fontWeight: FontWeight.w900,
                        letterSpacing: 1.5,
                        color: TevdaColors.slate900,
                      ),
                    ),
                  ],
                ),
                ElevatedButton.icon(
                  onPressed: () {
                    Navigator.push(
                      context,
                      MaterialPageRoute(
                        builder: (_) => const PaymentScreen(),
                      ),
                    );
                  },
                  icon: const Icon(Icons.arrow_forward_rounded, size: 16),
                  label: Text(lang.t('Lipia Sasa', 'Pay Now')),
                  style: ElevatedButton.styleFrom(
                    backgroundColor: TevdaColors.forestGreen,
                    foregroundColor: Colors.white,
                    padding: const EdgeInsets.symmetric(horizontal: 14, vertical: 8),
                    textStyle: const TextStyle(fontSize: 12, fontWeight: FontWeight.w800),
                    shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(10)),
                  ),
                ),
              ],
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildCorrectionRequiredCard(String? notes, LanguageController lang) {
    return Container(
      margin: const EdgeInsets.only(bottom: 20),
      padding: const EdgeInsets.all(18),
      decoration: BoxDecoration(
        color: const Color(0xFFFEF2F2),
        borderRadius: BorderRadius.circular(18),
        border: Border.all(color: TevdaColors.error.withValues(alpha: 0.4), width: 1.5),
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Row(
            children: [
              const Icon(Icons.warning_amber_rounded, color: TevdaColors.error, size: 24),
              const SizedBox(width: 10),
              Text(
                lang.t('Maelekezo ya Mkaguzi wa TEVDA', 'Secretariat Reviewer Feedback'),
                style: const TextStyle(
                  fontWeight: FontWeight.w900,
                  fontSize: 13.5,
                  color: TevdaColors.error,
                ),
              ),
            ],
          ),
          const SizedBox(height: 10),
          Text(
            notes ?? 'Tafadhali rekebisha au pakia upya nyaraka zilizoainishwa na mkaguzi.',
            style: const TextStyle(fontSize: 12.5, color: TevdaColors.slate800, height: 1.4),
          ),
          const SizedBox(height: 14),
          if (widget.onEditApplication != null)
            SizedBox(
              width: double.infinity,
              child: ElevatedButton.icon(
                onPressed: widget.onEditApplication,
                icon: const Icon(Icons.edit_note_rounded),
                label: Text(lang.t('Rekebisha Taarifa Zako Sasa', 'Update Application Details')),
                style: ElevatedButton.styleFrom(
                  backgroundColor: TevdaColors.error,
                  foregroundColor: Colors.white,
                  shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
                  padding: const EdgeInsets.symmetric(vertical: 12),
                  textStyle: const TextStyle(fontWeight: FontWeight.w800),
                ),
              ),
            ),
        ],
      ),
    );
  }

  Widget _buildResumeDraftCard(LanguageController lang) {
    return Container(
      margin: const EdgeInsets.only(bottom: 20),
      padding: const EdgeInsets.all(18),
      decoration: BoxDecoration(
        color: const Color(0xFFFFFBEB),
        borderRadius: BorderRadius.circular(18),
        border: Border.all(color: Colors.amber.shade400, width: 1.5),
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Row(
            children: [
              Icon(Icons.edit_document, color: Colors.amber.shade800, size: 22),
              const SizedBox(width: 10),
              Text(
                lang.t('Rasimu ya Maombi Imehifadhiwa', 'Draft Saved'),
                style: TextStyle(
                  fontWeight: FontWeight.w900,
                  fontSize: 13.5,
                  color: Colors.amber.shade900,
                ),
              ),
            ],
          ),
          const SizedBox(height: 8),
          Text(
            lang.t(
              'Una maombi ambayo bado hayajawasilishwa. Kamilisha hatua zilizobaki ili sekretarieti ianze uhakiki.',
              'Your application draft is saved. Complete remaining steps to submit for review.',
            ),
            style: TextStyle(fontSize: 12, color: Colors.amber.shade900, height: 1.3),
          ),
          const SizedBox(height: 14),
          if (widget.onEditApplication != null)
            SizedBox(
              width: double.infinity,
              child: ElevatedButton.icon(
                onPressed: widget.onEditApplication,
                icon: const Icon(Icons.play_arrow_rounded),
                label: Text(lang.t('Endelea na Maombi', 'Resume Application')),
                style: ElevatedButton.styleFrom(
                  backgroundColor: Colors.amber.shade800,
                  foregroundColor: Colors.white,
                  shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
                  padding: const EdgeInsets.symmetric(vertical: 12),
                  textStyle: const TextStyle(fontWeight: FontWeight.w800),
                ),
              ),
            ),
        ],
      ),
    );
  }

  Widget _buildLifecycleStepper(String currentStatus, LanguageController lang) {
    final steps = [
      {'key': 'draft', 'title': 'Rasimu ya Awali', 'en': 'Initial Draft'},
      {'key': 'submitted', 'title': 'Yamewasilishwa', 'en': 'Submitted'},
      {'key': 'payment', 'title': 'Malipo ya Ada (GePG)', 'en': 'Fee Payment'},
      {'key': 'review', 'title': 'Uhakiki wa Sekretarieti', 'en': 'Secretariat Review'},
      {'key': 'approved', 'title': 'Idhini na Kitambulisho', 'en': 'Approval & Smart ID'},
    ];

    int activeIndex = 1;
    if (currentStatus == 'draft') activeIndex = 0;
    if (currentStatus == 'submitted') activeIndex = 1;
    if (currentStatus == 'payment_pending') activeIndex = 2;
    if (currentStatus == 'payment_confirmed' || currentStatus == 'under_review') activeIndex = 3;
    if (currentStatus == 'approved' || currentStatus == 'active') activeIndex = 4;

    return Container(
      padding: const EdgeInsets.all(18),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(18),
        border: Border.all(color: const Color(0xFFE2E8F0)),
      ),
      child: Column(
        children: List.generate(steps.length, (index) {
          final isPast = index < activeIndex;
          final isCurrent = index == activeIndex;
          final isLast = index == steps.length - 1;
          final item = steps[index];

          Color circleColor = const Color(0xFFCBD5E1);
          Color textColor = TevdaColors.slate500;
          Widget iconWidget = Text('${index + 1}', style: const TextStyle(color: Colors.white, fontSize: 11, fontWeight: FontWeight.w800));

          if (isPast) {
            circleColor = TevdaColors.forestGreen;
            textColor = TevdaColors.forestGreen;
            iconWidget = const Icon(Icons.check_rounded, color: Colors.white, size: 14);
          } else if (isCurrent) {
            circleColor = currentStatus == 'documents_incomplete' || currentStatus == 'additional_info_required'
                ? TevdaColors.error
                : TevdaColors.gold;
            textColor = TevdaColors.slate900;
            iconWidget = const Icon(Icons.radio_button_checked_rounded, color: Colors.white, size: 14);
          }

          return Row(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Column(
                children: [
                  Container(
                    width: 26,
                    height: 26,
                    decoration: BoxDecoration(
                      color: circleColor,
                      shape: BoxShape.circle,
                    ),
                    child: Center(child: iconWidget),
                  ),
                  if (!isLast)
                    Container(
                      width: 2,
                      height: 32,
                      color: isPast ? TevdaColors.forestGreen : const Color(0xFFE2E8F0),
                    ),
                ],
              ),
              const SizedBox(width: 14),
              Expanded(
                child: Padding(
                  padding: const EdgeInsets.only(top: 3),
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      Text(
                        lang.t(item['title']!, item['en']!),
                        style: TextStyle(
                          fontSize: 13,
                          fontWeight: isCurrent ? FontWeight.w900 : (isPast ? FontWeight.w700 : FontWeight.w600),
                          color: textColor,
                        ),
                      ),
                      if (isCurrent)
                        Text(
                          lang.t('Hatua ya sasa inayoshughulikiwa', 'Current in-progress step'),
                          style: TextStyle(fontSize: 10.5, color: circleColor, fontWeight: FontWeight.w700),
                        ),
                    ],
                  ),
                ),
              ),
            ],
          );
        }),
      ),
    );
  }

  Widget _buildTimelineHistory(List history) {
    return Container(
      padding: const EdgeInsets.all(16),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(16),
        border: Border.all(color: const Color(0xFFE2E8F0)),
      ),
      child: Column(
        children: history.map((h) {
          final title = h['status'] ?? h['title'] ?? 'Tukio';
          final note = h['note'] ?? h['description'] ?? '';
          final time = h['created_at'] ?? h['time'] ?? '';

          return Padding(
            padding: const EdgeInsets.symmetric(vertical: 8.0),
            child: Row(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                const Icon(Icons.circle, size: 8, color: TevdaColors.forestGreen),
                const SizedBox(width: 12),
                Expanded(
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      Row(
                        mainAxisAlignment: MainAxisAlignment.spaceBetween,
                        children: [
                          Text(title, style: const TextStyle(fontWeight: FontWeight.w800, fontSize: 12.5)),
                          if (time.isNotEmpty)
                            Text(time, style: const TextStyle(fontSize: 10.5, color: TevdaColors.slate500)),
                        ],
                      ),
                      if (note.isNotEmpty)
                        Padding(
                          padding: const EdgeInsets.only(top: 2.0),
                          child: Text(note, style: const TextStyle(fontSize: 11, color: TevdaColors.slate600)),
                        ),
                    ],
                  ),
                ),
              ],
            ),
          );
        }).toList(),
      ),
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
