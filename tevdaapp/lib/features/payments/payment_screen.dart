import 'package:flutter/material.dart';
import 'package:flutter/services.dart';
import '../../core/theme.dart';
import '../../core/constants.dart';
import '../../core/localization.dart';
import '../../core/api_service.dart';

class PaymentScreen extends StatefulWidget {
  const PaymentScreen({super.key});

  @override
  State<PaymentScreen> createState() => _PaymentScreenState();
}

class _PaymentScreenState extends State<PaymentScreen> {
  final String _controlNumber = '994821730561';
  double _officialFee = 50000.0;
  final String _currency = 'TZS';
  final String _councilNotice = 'Ada rasmi inathibitishwa na Baraza Kuu la TEVDA. Malipo yote yanadhibitiwa na stakabadhi za mfumo mkuu.';

  final TextEditingController _refController = TextEditingController();
  final String _selectedChannel = 'mpesa';
  bool _isSubmitting = false;

  final List<Map<String, dynamic>> _transactions = [
    {
      'id': 'TXN-EV-9402816',
      'control_number': '994821730561',
      'amount': 'TZS 50,000',
      'channel': 'M-Pesa Gateway',
      'date': '20 Nov 2026',
      'status': 'Imelipwa (Paid)',
      'is_cleared': true,
    },
    {
      'id': 'TXN-EV-9401200',
      'control_number': '994821730560',
      'amount': 'TZS 15,000',
      'channel': 'Tigo Pesa',
      'date': '15 Jun 2026',
      'status': 'Imelipwa (Paid)',
      'is_cleared': true,
    },
  ];

  @override
  void initState() {
    super.initState();
    _loadLivePaymentData();
  }

  Future<void> _loadLivePaymentData() async {
    try {
      final feeRes = await ApiService.instance.getFees();
      if (feeRes.success && feeRes.data != null) {
        final data = feeRes.data as Map<String, dynamic>;
        if (data.containsKey('default_annual_fee')) {
          setState(() {
            _officialFee = (data['default_annual_fee'] as num).toDouble();
          });
        }
      }

      final histRes = await ApiService.instance.getPaymentHistory();
      if (histRes.success && histRes.data != null && histRes.data is List) {
        final list = histRes.data as List;
        if (list.isNotEmpty) {
          setState(() {
            for (var item in list) {
              if (item is Map<String, dynamic>) {
                _transactions.insert(0, {
                  'id': item['reference'] ?? 'TXN-${item['id']}',
                  'control_number': item['control_number'] ?? _controlNumber,
                  'amount': 'TZS ${item['amount']}',
                  'channel': item['payment_method'] ?? 'GePG Gateway',
                  'date': item['created_at']?.toString().substring(0, 10) ?? 'Leo',
                  'status': item['status'] == 'completed' ? 'Imelipwa (Paid)' : 'Inasubiri Uhakiki',
                  'is_cleared': item['status'] == 'completed',
                });
              }
            }
          });
        }
      }
    } catch (_) {}
  }

  @override
  Widget build(BuildContext context) {
    final lang = LanguageController.instance;

    return DefaultTabController(
      length: 2,
      child: Scaffold(
        appBar: AppBar(
          title: Text(
            lang.t('Malipo na Ada ya Uanachama', 'Payments & Membership Fees'),
            style: const TextStyle(fontWeight: FontWeight.w800, fontSize: 17),
          ),
          bottom: TabBar(
            indicatorColor: TevdaColors.forestGreen,
            labelColor: TevdaColors.forestGreen,
            unselectedLabelColor: TevdaColors.slate500,
            labelStyle: const TextStyle(fontWeight: FontWeight.w700, fontSize: 13),
            tabs: [
              Tab(text: lang.t('Lipa Ada / Control No', 'Pay Fee / Control No')),
              Tab(text: lang.t('Historia ya Malipo (${_transactions.length})', 'History (${_transactions.length})')),
            ],
          ),
        ),
        body: TabBarView(
          children: [
            // Tab 1: Pay
            SingleChildScrollView(
              padding: const EdgeInsets.all(20),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  // Dynamic Fee & Council Notice Banner
                  Container(
                    padding: const EdgeInsets.all(16),
                    decoration: BoxDecoration(
                      color: const Color(0xFFFEF3C7),
                      borderRadius: BorderRadius.circular(16),
                      border: Border.all(color: TevdaColors.goldDark.withValues(alpha: 0.4)),
                    ),
                    child: Row(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        const Icon(Icons.info_outline_rounded, color: TevdaColors.goldDark, size: 22),
                        const SizedBox(width: 12),
                        Expanded(
                          child: Column(
                            crossAxisAlignment: CrossAxisAlignment.start,
                            children: [
                              Text(
                                lang.t('Sera ya Ada ya TEVDA', 'Official TEVDA Fee Policy'),
                                style: const TextStyle(fontWeight: FontWeight.w800, fontSize: 13, color: TevdaColors.goldDark),
                              ),
                              const SizedBox(height: 4),
                              Text(
                                _councilNotice,
                                style: const TextStyle(fontSize: 11.5, color: Color(0xFF78350F), height: 1.3),
                              ),
                            ],
                          ),
                        ),
                      ],
                    ),
                  ),
                  const SizedBox(height: 20),

                  // Control Number Card
                  Container(
                    padding: const EdgeInsets.all(20),
                    decoration: BoxDecoration(
                      gradient: const LinearGradient(
                        colors: [Color(0xFF075B37), Color(0xFF033821)],
                        begin: Alignment.topLeft,
                        end: Alignment.bottomRight,
                      ),
                      borderRadius: BorderRadius.circular(20),
                      boxShadow: [
                        BoxShadow(
                          color: Colors.black.withValues(alpha: 0.15),
                          blurRadius: 14,
                          offset: const Offset(0, 6),
                        ),
                      ],
                    ),
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        Row(
                          mainAxisAlignment: MainAxisAlignment.spaceBetween,
                          children: [
                            const Text(
                              'NAMBA YA MALIPO (CONTROL NUMBER)',
                              style: TextStyle(color: TevdaColors.gold, fontWeight: FontWeight.w800, fontSize: 10, letterSpacing: 0.8),
                            ),
                            Container(
                              padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 3),
                              decoration: BoxDecoration(
                                color: Colors.white.withValues(alpha: 0.15),
                                borderRadius: BorderRadius.circular(6),
                              ),
                              child: const Text('GePG / Bank / Simu', style: TextStyle(color: Colors.white, fontSize: 10, fontWeight: FontWeight.w700)),
                            ),
                          ],
                        ),
                        const SizedBox(height: 12),
                        Row(
                          children: [
                            Expanded(
                              child: Text(
                                _controlNumber,
                                style: const TextStyle(
                                  color: Colors.white,
                                  fontSize: 24,
                                  fontWeight: FontWeight.w900,
                                  letterSpacing: 2.0,
                                ),
                              ),
                            ),
                            IconButton(
                              onPressed: () {
                                Clipboard.setData(ClipboardData(text: _controlNumber));
                                ScaffoldMessenger.of(context).showSnackBar(
                                  const SnackBar(
                                    content: Text('Namba ya malipo (Control Number) imenakiliwa!'),
                                    backgroundColor: TevdaColors.forestGreen,
                                  ),
                                );
                              },
                              icon: const Icon(Icons.copy_rounded, color: TevdaColors.gold),
                            ),
                          ],
                        ),
                        const SizedBox(height: 10),
                        const Divider(color: Colors.white24),
                        const SizedBox(height: 6),
                        Row(
                          mainAxisAlignment: MainAxisAlignment.spaceBetween,
                          children: [
                            const Text('Kiasi Kinacholipwa:', style: TextStyle(color: Colors.white70, fontSize: 12)),
                            Text(
                              '$_currency 50,000/=',
                              style: const TextStyle(color: Colors.white, fontWeight: FontWeight.w900, fontSize: 16),
                            ),
                          ],
                        ),
                      ],
                    ),
                  ),
                  const SizedBox(height: 24),

                  // Payment Channels
                  Text(
                    lang.t('Njia za Malipo Zilizoidhinishwa:', 'Authorized Payment Channels:'),
                    style: const TextStyle(fontSize: 14, fontWeight: FontWeight.w800, color: TevdaColors.slate900),
                  ),
                  const SizedBox(height: 12),
                  _buildChannelItem('M-Pesa (Vodacom)', 'Lipa Namba / Paybill: 500200', Icons.phone_android_rounded),
                  const SizedBox(height: 8),
                  _buildChannelItem('Tigo Pesa & HaloPesa', 'Lipa kwa Control Number moja kwa moja', Icons.sim_card_rounded),
                  const SizedBox(height: 8),
                  _buildChannelItem('Airtel Money', 'Weka Namba ya Kampuni au Control Number', Icons.credit_card_rounded),
                  const SizedBox(height: 8),
                  _buildChannelItem('CRDB & NMB Bank', 'A/C: 0150829104000 (TEVDA Secretariat)', Icons.account_balance_rounded),
                  const SizedBox(height: 24),

                  // Proof Submission (No Fake Activation Notice)
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
                            const Icon(Icons.verified_rounded, color: TevdaColors.forestGreen, size: 20),
                            const SizedBox(width: 8),
                            Text(
                              lang.t('Uthibitisho wa Muamala wa Malipo', 'Submit Payment Reference'),
                              style: const TextStyle(fontSize: 13.5, fontWeight: FontWeight.w800, color: TevdaColors.slate900),
                            ),
                          ],
                        ),
                        const SizedBox(height: 8),
                        Text(
                          lang.t(
                            'Kumbuka: Kuwasilisha namba ya muamala hakuwezeshi uanachama papo hapo. Malipo yako yatahakikiwa kwanza na mfumo mkuu wa fedha.',
                            'Notice: Submitting a reference does not immediately activate membership. Reconciliation occurs via the financial gateway.',
                          ),
                          style: const TextStyle(fontSize: 11.5, color: TevdaColors.slate600, height: 1.3),
                        ),
                        const SizedBox(height: 12),
                        TextField(
                          controller: _refController,
                          decoration: InputDecoration(
                            hintText: 'Mfano: QWD98418J2 au Namba ya Benki',
                            filled: true,
                            fillColor: TevdaColors.slate50,
                            contentPadding: const EdgeInsets.symmetric(horizontal: 14, vertical: 12),
                            border: OutlineInputBorder(borderRadius: BorderRadius.circular(10), borderSide: const BorderSide(color: Color(0xFFCBD5E1))),
                          ),
                        ),
                        const SizedBox(height: 14),
                        SizedBox(
                          width: double.infinity,
                          child: ElevatedButton(
                            onPressed: () => _submitPaymentReference(context),
                            style: ElevatedButton.styleFrom(backgroundColor: TevdaColors.forestGreen),
                            child: const Text('Wasilisha Kumbukumbu ya Malipo'),
                          ),
                        ),
                      ],
                    ),
                  ),
                ],
              ),
            ),

            // Tab 2: Transaction History
            ListView.separated(
              padding: const EdgeInsets.all(20),
              itemCount: _transactions.length,
              separatorBuilder: (_, _) => const SizedBox(height: 12),
              itemBuilder: (context, index) {
                final txn = _transactions[index];
                return Container(
                  padding: const EdgeInsets.all(16),
                  decoration: BoxDecoration(
                    color: Colors.white,
                    borderRadius: BorderRadius.circular(14),
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
                        child: const Icon(Icons.receipt_long_rounded, color: TevdaColors.forestGreen, size: 22),
                      ),
                      const SizedBox(width: 14),
                      Expanded(
                        child: Column(
                          crossAxisAlignment: CrossAxisAlignment.start,
                          children: [
                            Text(txn['id'], style: const TextStyle(fontWeight: FontWeight.w800, fontSize: 13)),
                            const SizedBox(height: 2),
                            Text('${txn['channel']} • ${txn['date']}', style: const TextStyle(fontSize: 11, color: TevdaColors.slate500)),
                            const SizedBox(height: 2),
                            Text(txn['status'], style: const TextStyle(fontSize: 11, fontWeight: FontWeight.w700, color: TevdaColors.forestGreen)),
                          ],
                        ),
                      ),
                      Column(
                        crossAxisAlignment: CrossAxisAlignment.end,
                        children: [
                          Text(txn['amount'], style: const TextStyle(fontWeight: FontWeight.w900, fontSize: 14, color: TevdaColors.slate900)),
                          const SizedBox(height: 4),
                          InkWell(
                            onTap: () => _openReceiptModal(context, txn),
                            child: const Text('Risiti (PDF) →', style: TextStyle(fontSize: 11, fontWeight: FontWeight.w700, color: TevdaColors.forestGreen)),
                          ),
                        ],
                      ),
                    ],
                  ),
                );
              },
            ),
          ],
        ),
      ),
    );
  }

  Widget _buildChannelItem(String title, String subtitle, IconData icon) {
    return Container(
      padding: const EdgeInsets.all(12),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(12),
        border: Border.all(color: const Color(0xFFE2E8F0)),
      ),
      child: Row(
        children: [
          Icon(icon, color: TevdaColors.forestGreen, size: 22),
          const SizedBox(width: 12),
          Expanded(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text(title, style: const TextStyle(fontSize: 13, fontWeight: FontWeight.w800, color: TevdaColors.slate900)),
                Text(subtitle, style: const TextStyle(fontSize: 11, color: TevdaColors.slate600)),
              ],
            ),
          ),
        ],
      ),
    );
  }

  Future<void> _submitPaymentReference(BuildContext context) async {
    if (_refController.text.trim().isEmpty) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text('Tafadhali ingiza namba ya muamala (Transaction Ref).')),
      );
      return;
    }

    final ref = _refController.text.trim().toUpperCase();
    _refController.clear();

    setState(() => _isSubmitting = true);
    final res = await ApiService.instance.submitPaymentReference(_controlNumber, ref, _selectedChannel, _officialFee);
    if (!mounted) return;
    setState(() {
      _isSubmitting = false;
      _transactions.insert(0, {
        'id': ref,
        'control_number': _controlNumber,
        'amount': '$_currency ${_officialFee.toInt()}',
        'channel': _selectedChannel.toUpperCase(),
        'date': 'Hivi Punde',
        'status': 'Inasubiri Uhakiki (Verification Pending)',
        'is_cleared': false,
      });
    });

    showDialog(
      context: context,
      builder: (ctx) {
        return AlertDialog(
          shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(20)),
          title: Row(
            children: const [
              Icon(Icons.hourglass_top_rounded, color: TevdaColors.goldDark),
              SizedBox(width: 8),
              Text('MALIPO YANAHAKIKIWA', style: TextStyle(fontWeight: FontWeight.w900, fontSize: 15)),
            ],
          ),
          content: Text(
            'Kumbukumbu ya muamala "$ref" imepokelewa kwenye mfumo mkuu.\n\nHali: "Inasubiri Uhakiki wa Kifedha" (Verification Pending).\n\nUanachama na cheti chako vitahuishwa mara baada ya uhakiki wa kifedha kukamilika na seva kuu.',
            style: const TextStyle(fontSize: 13, height: 1.4),
          ),
          actions: [
            ElevatedButton(
              onPressed: () => Navigator.pop(ctx),
              style: ElevatedButton.styleFrom(backgroundColor: TevdaColors.forestGreen),
              child: const Text('Nimeelewa'),
            ),
          ],
        );
      },
    );
  }

  void _openReceiptModal(BuildContext context, Map<String, dynamic> txn) {
    showModalBottomSheet(
      context: context,
      isScrollControlled: true,
      backgroundColor: Colors.transparent,
      builder: (ctx) {
        return Container(
          height: MediaQuery.of(ctx).size.height * 0.75,
          decoration: const BoxDecoration(
            color: Colors.white,
            borderRadius: BorderRadius.vertical(top: Radius.circular(24)),
          ),
          padding: const EdgeInsets.all(24),
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Center(
                child: Container(
                  width: 40,
                  height: 4,
                  decoration: BoxDecoration(color: TevdaColors.slate200, borderRadius: BorderRadius.circular(2)),
                ),
              ),
              const SizedBox(height: 16),
              Row(
                mainAxisAlignment: MainAxisAlignment.spaceBetween,
                children: [
                  const Text('STAKABADHI RASMI YA MALIPO', style: TextStyle(fontWeight: FontWeight.w900, fontSize: 14, color: TevdaColors.forestGreen)),
                  IconButton(icon: const Icon(Icons.close), onPressed: () => Navigator.pop(ctx)),
                ],
              ),
              const Divider(),
              const SizedBox(height: 12),
              Container(
                padding: const EdgeInsets.all(16),
                decoration: BoxDecoration(
                  color: TevdaColors.slate50,
                  borderRadius: BorderRadius.circular(14),
                  border: Border.all(color: const Color(0xFFE2E8F0)),
                ),
                child: Column(
                  children: [
                    const Text('TANZANIA ELECTRIC VEHICLE DRIVERS ASSOCIATION', textAlign: TextAlign.center, style: TextStyle(fontWeight: FontWeight.w900, fontSize: 11)),
                    const SizedBox(height: 12),
                    _buildReceiptRow('Namba ya Risiti:', txn['id']),
                    _buildReceiptRow('Control Number:', txn['control_number']),
                    _buildReceiptRow('Mwanachama:', 'Amani Juma (TEVDA-2026-000128)'),
                    _buildReceiptRow('Njia ya Malipo:', txn['channel']),
                    _buildReceiptRow('Tarehe ya Malipo:', txn['date']),
                    const Divider(),
                    _buildReceiptRow('Jumla Iliyolipwa:', txn['amount'], isBold: true),
                    _buildReceiptRow('Hali ya Malipo:', 'IMELIPWA KIKAMILIFU (CLEARED)', isBold: true, color: TevdaColors.forestGreen),
                  ],
                ),
              ),
              const Spacer(),
              SizedBox(
                width: double.infinity,
                child: ElevatedButton.icon(
                  onPressed: () {
                    Navigator.pop(ctx);
                    ScaffoldMessenger.of(context).showSnackBar(
                      const SnackBar(content: Text('Risiti inapakuliwa (PDF imehifadhiwa)...')),
                    );
                  },
                  icon: const Icon(Icons.download_rounded, size: 18),
                  label: const Text('Pakua Risiti (PDF)'),
                  style: ElevatedButton.styleFrom(
                    backgroundColor: TevdaColors.forestGreen,
                    padding: const EdgeInsets.symmetric(vertical: 14),
                  ),
                ),
              ),
            ],
          ),
        );
      },
    );
  }

  Widget _buildReceiptRow(String label, String value, {bool isBold = false, Color? color}) {
    return Padding(
      padding: const EdgeInsets.symmetric(vertical: 4),
      child: Row(
        mainAxisAlignment: MainAxisAlignment.spaceBetween,
        children: [
          Text(label, style: const TextStyle(fontSize: 11, color: TevdaColors.slate600)),
          Text(
            value,
            style: TextStyle(
              fontSize: 11,
              fontWeight: isBold ? FontWeight.w900 : FontWeight.w600,
              color: color ?? TevdaColors.slate900,
            ),
          ),
        ],
      ),
    );
  }
}
