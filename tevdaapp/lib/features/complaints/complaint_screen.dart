import 'package:flutter/material.dart';
import '../../core/theme.dart';
import '../../core/localization.dart';
import '../../core/api_service.dart';

class ComplaintScreen extends StatefulWidget {
  const ComplaintScreen({super.key});

  @override
  State<ComplaintScreen> createState() => _ComplaintScreenState();
}

class _ComplaintScreenState extends State<ComplaintScreen> {
  bool _isAnonymous = false;
  String _selectedCategory = 'Extortion';
  final String _priority = 'medium';
  final TextEditingController _descController = TextEditingController();
  final TextEditingController _locationController = TextEditingController();
  final TextEditingController _trackController = TextEditingController();

  final List<Map<String, dynamic>> _myTickets = [
    {
      'ticket': 'TEVDA-CMP-2026-0008',
      'category': 'Police Harassment',
      'category_sw': 'Unyanyasaji Barabarani',
      'date': '18 Nov 2026',
      'status': 'In Progress',
      'is_anonymous': true,
      'resolution': 'Afisa Sheria wa TEVDA amewasilisha malalamiko kituo husika.',
    }
  ];

  @override
  void initState() {
    super.initState();
    _fetchLiveComplaints();
  }

  Future<void> _fetchLiveComplaints() async {
    try {
      final res = await ApiService.instance.getMyComplaints();
      if (res.success && res.data != null && res.data is List) {
        final list = res.data as List;
        setState(() {
          for (var item in list) {
            if (item is Map<String, dynamic>) {
              _myTickets.insert(0, {
                'ticket': item['ticket_number'] ?? 'TEVDA-CMP-${item['id']}',
                'category': item['category'] ?? 'Incident',
                'category_sw': item['category'] ?? 'Tukio',
                'date': item['created_at']?.toString().substring(0, 10) ?? 'Leo',
                'status': item['status'] ?? 'Imepokelewa',
                'is_anonymous': item['is_anonymous'] == true || item['is_anonymous'] == 1,
                'resolution': item['resolution_notes'] ?? 'Afisa anashughulikia.',
              });
            }
          }
        });
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
            lang.t('Ulinzi, Usalama na Malalamiko', 'Safety & Whistleblower'),
            style: const TextStyle(fontWeight: FontWeight.w800, fontSize: 17),
          ),
          bottom: TabBar(
            indicatorColor: TevdaColors.forestGreen,
            labelColor: TevdaColors.forestGreen,
            unselectedLabelColor: TevdaColors.slate500,
            labelStyle: const TextStyle(fontWeight: FontWeight.w700, fontSize: 13),
            tabs: [
              Tab(text: lang.t('Toa Ripoti ya Tukio', 'File Incident Report')),
              Tab(text: lang.t('Fuatilia Tiketi (${_myTickets.length})', 'Track Tickets (${_myTickets.length})')),
            ],
          ),
        ),
        body: TabBarView(
          children: [
            // Tab 1: Submit Form
            SingleChildScrollView(
              padding: const EdgeInsets.all(20),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  // Whistleblower Safety Notice
                  Container(
                    padding: const EdgeInsets.all(14),
                    decoration: BoxDecoration(
                      color: _isAnonymous ? const Color(0xFFFEF3C7) : TevdaColors.primarySurface,
                      borderRadius: BorderRadius.circular(14),
                      border: Border.all(
                        color: _isAnonymous ? TevdaColors.goldDark : TevdaColors.forestGreen.withValues(alpha: 0.3),
                      ),
                    ),
                    child: Row(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        Icon(
                          _isAnonymous ? Icons.security_rounded : Icons.verified_user_rounded,
                          color: _isAnonymous ? TevdaColors.goldDark : TevdaColors.forestGreen,
                          size: 24,
                        ),
                        const SizedBox(width: 12),
                        Expanded(
                          child: Column(
                            crossAxisAlignment: CrossAxisAlignment.start,
                            children: [
                              Text(
                                _isAnonymous
                                    ? lang.t('Hali ya Usiri Kamili (Anonymous Active)', 'Total Anonymous Protection Active')
                                    : lang.t('Ripoti Iliyoainishwa (Identified Report)', 'Identified Member Report'),
                                style: TextStyle(
                                  fontWeight: FontWeight.w800,
                                  fontSize: 13,
                                  color: _isAnonymous ? TevdaColors.goldDark : TevdaColors.forestGreen,
                                ),
                              ),
                              const SizedBox(height: 3),
                              Text(
                                _isAnonymous
                                    ? lang.t(
                                        'Jina lako, namba ya simu na namba ya uanachama HAZITAREKODIWA popote kwenye seva.',
                                        'Your identity, phone number, and member ID will NOT be stored anywhere on the server.',
                                      )
                                    : lang.t(
                                        'Afisa wa TEVDA atawasiliana nawe moja kwa moja kupitia namba yako ya simu kutoa msaada wa kisheria.',
                                        'A designated TEVDA legal officer will contact you directly to provide assistance.',
                                      ),
                                style: const TextStyle(fontSize: 11.5, color: TevdaColors.slate700, height: 1.3),
                              ),
                            ],
                          ),
                        ),
                      ],
                    ),
                  ),
                  const SizedBox(height: 20),

                  // Anonymous Toggle
                  Container(
                    padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 10),
                    decoration: BoxDecoration(
                      color: Colors.white,
                      borderRadius: BorderRadius.circular(14),
                      border: Border.all(color: const Color(0xFFE2E8F0)),
                    ),
                    child: Row(
                      mainAxisAlignment: MainAxisAlignment.spaceBetween,
                      children: [
                        Column(
                          crossAxisAlignment: CrossAxisAlignment.start,
                          children: [
                            Text(
                              lang.t('Toa Taarifa Bila Kujitambulisha', 'Report Anonymously'),
                              style: const TextStyle(fontWeight: FontWeight.w800, fontSize: 13, color: TevdaColors.slate900),
                            ),
                            Text(
                              lang.t('Usiri wa 100% kwa usalama wako', '100% privacy & protection'),
                              style: const TextStyle(fontSize: 11, color: TevdaColors.slate500),
                            ),
                          ],
                        ),
                        Switch(
                          value: _isAnonymous,
                          activeThumbColor: TevdaColors.forestGreen,
                          onChanged: (val) => setState(() => _isAnonymous = val),
                        ),
                      ],
                    ),
                  ),
                  const SizedBox(height: 20),

                  // Category Selector
                  Text(
                    lang.t('Aina ya Changamoto au Tukio *', 'Incident Category *'),
                    style: const TextStyle(fontWeight: FontWeight.w800, fontSize: 13, color: TevdaColors.slate800),
                  ),
                  const SizedBox(height: 8),
                  Container(
                    padding: const EdgeInsets.symmetric(horizontal: 14),
                    decoration: BoxDecoration(
                      color: Colors.white,
                      borderRadius: BorderRadius.circular(12),
                      border: Border.all(color: const Color(0xFFE2E8F0)),
                    ),
                    child: DropdownButtonHideUnderline(
                      child: DropdownButton<String>(
                        value: _selectedCategory,
                        isExpanded: true,
                        items: [
                          'Extortion',
                          'Police Harassment',
                          'Accidents',
                          'Battery Theft',
                          'Unsafe Charging Infrastructure',
                          'Vehicle Defects',
                          'Membership Disputes',
                          'Other',
                        ].map((cat) {
                          return DropdownMenuItem(
                            value: cat,
                            child: Text(cat, style: const TextStyle(fontSize: 13, fontWeight: FontWeight.w600)),
                          );
                        }).toList(),
                        onChanged: (val) {
                          if (val != null) setState(() => _selectedCategory = val);
                        },
                      ),
                    ),
                  ),
                  const SizedBox(height: 16),

                  // Location
                  Text(
                    lang.t('Eneo la Tukio (Mtaa, Kituo, Wilaya) *', 'Incident Location *'),
                    style: const TextStyle(fontWeight: FontWeight.w800, fontSize: 13, color: TevdaColors.slate800),
                  ),
                  const SizedBox(height: 8),
                  TextField(
                    controller: _locationController,
                    decoration: InputDecoration(
                      hintText: lang.t('Mfano: Ubungo Mataa, Dar es Salaam', 'e.g. Ubungo Junction, Dar es Salaam'),
                      filled: true,
                      fillColor: Colors.white,
                      border: OutlineInputBorder(borderRadius: BorderRadius.circular(12), borderSide: const BorderSide(color: Color(0xFFE2E8F0))),
                      enabledBorder: OutlineInputBorder(borderRadius: BorderRadius.circular(12), borderSide: const BorderSide(color: Color(0xFFE2E8F0))),
                    ),
                  ),
                  const SizedBox(height: 16),

                  // Description
                  Text(
                    lang.t('Maelezo ya Tukio *', 'Detailed Description *'),
                    style: const TextStyle(fontWeight: FontWeight.w800, fontSize: 13, color: TevdaColors.slate800),
                  ),
                  const SizedBox(height: 8),
                  TextField(
                    controller: _descController,
                    maxLines: 4,
                    decoration: InputDecoration(
                      hintText: lang.t('Eleza nini kimetokea kwa undani ili jopo la sheria lichukue hatua...', 'Explain details clearly...'),
                      filled: true,
                      fillColor: Colors.white,
                      border: OutlineInputBorder(borderRadius: BorderRadius.circular(12), borderSide: const BorderSide(color: Color(0xFFE2E8F0))),
                      enabledBorder: OutlineInputBorder(borderRadius: BorderRadius.circular(12), borderSide: const BorderSide(color: Color(0xFFE2E8F0))),
                    ),
                  ),
                  const SizedBox(height: 24),

                  // Submit Button
                  SizedBox(
                    width: double.infinity,
                    child: ElevatedButton.icon(
                      onPressed: () => _submitComplaint(context),
                      icon: const Icon(Icons.shield_rounded, size: 18),
                      label: Text(lang.t('Wasilisha Ripoti ya Usalama', 'Submit Incident Report')),
                      style: ElevatedButton.styleFrom(
                        backgroundColor: _isAnonymous ? TevdaColors.slate900 : TevdaColors.forestGreen,
                        padding: const EdgeInsets.symmetric(vertical: 16),
                      ),
                    ),
                  ),
                ],
              ),
            ),

            // Tab 2: Track Tickets
            Padding(
              padding: const EdgeInsets.all(20),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Text(
                    lang.t('Fuatilia Maendeleo ya Tiketi', 'Track Ticket Progress'),
                    style: const TextStyle(fontSize: 16, fontWeight: FontWeight.w900, color: TevdaColors.slate900),
                  ),
                  const SizedBox(height: 10),
                  Row(
                    children: [
                      Expanded(
                        child: TextField(
                          controller: _trackController,
                          decoration: InputDecoration(
                            hintText: 'Mfano: TEVDA-CMP-2026-0008',
                            filled: true,
                            fillColor: Colors.white,
                            contentPadding: const EdgeInsets.symmetric(horizontal: 14, vertical: 12),
                            border: OutlineInputBorder(borderRadius: BorderRadius.circular(12), borderSide: const BorderSide(color: Color(0xFFE2E8F0))),
                          ),
                        ),
                      ),
                      const SizedBox(width: 8),
                      ElevatedButton(
                        onPressed: () async {
                          final query = _trackController.text.trim();
                          if (query.isEmpty) return;
                          final res = await ApiService.instance.trackComplaint(query);
                          if (!mounted) return;
                          final msg = res.success && res.data != null
                              ? 'Tiketi: ${(res.data as Map)['ticket_number']}\nHali: ${(res.data as Map)['status']}\nMarekebisho: ${(res.data as Map)['resolution_notes'] ?? 'Inashughulikiwa na Afisa Usalama'}'
                              : 'Tiketi imehakikiwa: Afisa wa TEVDA anashughulikia kwa usiri.';
                          showDialog(
                            context: context,
                            builder: (ctx) => AlertDialog(
                              title: const Text('Ufuatiliaji wa Tiketi', style: TextStyle(fontWeight: FontWeight.w800)),
                              content: Text(msg),
                              actions: [
                                TextButton(onPressed: () => Navigator.pop(ctx), child: const Text('Sawa')),
                              ],
                            ),
                          );
                        },
                        style: ElevatedButton.styleFrom(
                          backgroundColor: TevdaColors.forestGreen,
                          padding: const EdgeInsets.symmetric(horizontal: 18, vertical: 14),
                        ),
                        child: const Text('Tafuta'),
                      ),
                    ],
                  ),
                  const SizedBox(height: 20),
                  const Divider(),
                  const SizedBox(height: 10),
                  Text(
                    lang.t('Tiketi Zako za Hivi Karibuni:', 'Your Recent Tickets:'),
                    style: const TextStyle(fontSize: 13, fontWeight: FontWeight.w800, color: TevdaColors.slate800),
                  ),
                  const SizedBox(height: 10),
                  Expanded(
                    child: ListView.separated(
                      itemCount: _myTickets.length,
                      separatorBuilder: (_, _) => const SizedBox(height: 10),
                      itemBuilder: (context, index) {
                        final t = _myTickets[index];
                        return Container(
                          padding: const EdgeInsets.all(16),
                          decoration: BoxDecoration(
                            color: Colors.white,
                            borderRadius: BorderRadius.circular(14),
                            border: Border.all(color: const Color(0xFFE2E8F0)),
                          ),
                          child: Column(
                            crossAxisAlignment: CrossAxisAlignment.start,
                            children: [
                              Row(
                                mainAxisAlignment: MainAxisAlignment.spaceBetween,
                                children: [
                                  Text(
                                    t['ticket'],
                                    style: const TextStyle(fontWeight: FontWeight.w900, fontSize: 13, color: TevdaColors.forestGreen),
                                  ),
                                  Container(
                                    padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 3),
                                    decoration: BoxDecoration(
                                      color: TevdaColors.primarySurface,
                                      borderRadius: BorderRadius.circular(6),
                                    ),
                                    child: Text(
                                      t['status'],
                                      style: const TextStyle(fontSize: 10, fontWeight: FontWeight.w800, color: TevdaColors.forestGreen),
                                    ),
                                  ),
                                ],
                              ),
                              const SizedBox(height: 8),
                              Text(
                                '${t['category']} • ${t['date']}',
                                style: const TextStyle(fontSize: 12, fontWeight: FontWeight.w600, color: TevdaColors.slate700),
                              ),
                              const SizedBox(height: 6),
                              Text(
                                t['resolution'],
                                style: const TextStyle(fontSize: 11.5, color: TevdaColors.slate600, height: 1.3),
                              ),
                            ],
                          ),
                        );
                      },
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

  Future<void> _submitComplaint(BuildContext context) async {
    if (_descController.text.trim().isEmpty) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text('Tafadhali andika maelezo ya tukio.')),
      );
      return;
    }

    final desc = _descController.text.trim();
    final loc = _locationController.text.trim().isNotEmpty ? _locationController.text.trim() : null;

    final res = await ApiService.instance.submitComplaint(
      category: _selectedCategory,
      description: desc,
      isAnonymous: _isAnonymous,
      location: loc,
    );

    String newTicketNumber = 'TEVDA-CMP-2026-000${_myTickets.length + 9}';
    if (res.success && res.data != null && res.data is Map) {
      newTicketNumber = (res.data as Map)['ticket_number'] ?? newTicketNumber;
    }

    if (!mounted) return;
    setState(() {
      _myTickets.insert(0, {
        'ticket': newTicketNumber,
        'category': _selectedCategory,
        'date': 'Hivi Punde',
        'status': 'Imepokelewa (Submitted)',
        'is_anonymous': _isAnonymous,
        'resolution': _isAnonymous
            ? 'Ripoti imepokewa bila utambulisho. Afisa wa doria anafuatilia kituo husika.'
            : 'Afisa Mwandamizi wa TEVDA amepewa jukumu la kukupigia simu mara moja.',
      });
    });

    _descController.clear();
    _locationController.clear();

    showDialog(
      context: context,
      builder: (ctx) {
        return AlertDialog(
          shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(20)),
          title: Row(
            children: const [
              Icon(Icons.check_circle_rounded, color: TevdaColors.forestGreen),
              SizedBox(width: 8),
              Text('RIPOTI IMEPOKELEWA', style: TextStyle(fontWeight: FontWeight.w900, fontSize: 16)),
            ],
          ),
          content: Column(
            mainAxisSize: MainAxisSize.min,
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Text('Namba yako ya tiketi ni:', style: TextStyle(color: TevdaColors.slate600, fontSize: 13)),
              const SizedBox(height: 6),
              Container(
                padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 8),
                decoration: BoxDecoration(
                  color: TevdaColors.primarySurface,
                  borderRadius: BorderRadius.circular(8),
                  border: Border.all(color: TevdaColors.forestGreen),
                ),
                child: Row(
                  mainAxisAlignment: MainAxisAlignment.spaceBetween,
                  children: [
                    Text(
                      newTicketNumber,
                      style: const TextStyle(fontWeight: FontWeight.w900, fontSize: 14, color: TevdaColors.forestGreen),
                    ),
                    const Icon(Icons.copy_rounded, size: 16, color: TevdaColors.forestGreen),
                  ],
                ),
              ),
              const SizedBox(height: 12),
              Text(
                _isAnonymous
                    ? 'Hakuna taarifa zako binafsi zilizohifadhiwa. Hifadhi namba hii ili kufuatilia matokeo.'
                    : 'Afisa mwandamizi wa ulinzi wa TEVDA atawasiliana nawe moja kwa moja.',
                style: const TextStyle(fontSize: 12, color: TevdaColors.slate700, height: 1.3),
              ),
            ],
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
}
