import 'package:flutter/material.dart';
import '../../core/theme.dart';
import '../../core/constants.dart';
import '../../core/localization.dart';
import '../../core/api_service.dart';

class ExploreScreen extends StatefulWidget {
  const ExploreScreen({super.key});

  @override
  State<ExploreScreen> createState() => _ExploreScreenState();
}

class _ExploreScreenState extends State<ExploreScreen> {
  String _selectedCategory = 'All';
  String _searchQuery = '';
  List<Map<String, dynamic>> _opportunities = List.from(AppConstants.verifiedOpportunities);
  final List<Map<String, dynamic>> _myApplications = [
    {
      'id': 1,
      'title': 'Clean Energy Green Financing (Low-Interest Loan)',
      'provider': 'CRDB & Tanzania E-Mobility Fund',
      'date': '20 Nov 2026',
      'status': 'In Review',
      'ticket': 'APP-EV-2026-081',
    }
  ];

  @override
  void initState() {
    super.initState();
    _fetchLiveOpportunities();
  }

  Future<void> _fetchLiveOpportunities() async {
    try {
      final res = await ApiService.instance.getOpportunities();
      if (res.success && res.data != null && res.data is List) {
        final list = res.data as List;
        if (list.isNotEmpty) {
          setState(() {
            _opportunities = list.map((item) {
              final m = item as Map<String, dynamic>;
              return {
                'id': m['id'] ?? 1,
                'title': m['title'] ?? '',
                'title_sw': m['title_sw'] ?? m['title'] ?? '',
                'provider': m['provider'] ?? 'TEVDA Partner',
                'category': m['category'] ?? 'Vehicle Financing',
                'type': m['type'] ?? 'Loan / Grant',
                'eligibility': m['eligibility'] ?? 'TEVDA Active Member',
                'location': m['location'] ?? 'Tanzania Nzima',
                'deadline': m['deadline'] ?? '31 Dec 2026',
                'is_featured': m['is_featured'] ?? false,
              };
            }).toList();
          });
        }
      }

      final appRes = await ApiService.instance.getMyApplications();
      if (appRes.success && appRes.data != null && appRes.data is List) {
        final aList = appRes.data as List;
        setState(() {
          for (var a in aList) {
            if (a is Map<String, dynamic>) {
              _myApplications.insert(0, {
                'id': a['id'] ?? 1,
                'title': a['title'] ?? 'Fursa ya TEVDA',
                'provider': a['provider'] ?? 'Mshirika wa TEVDA',
                'date': a['created_at']?.toString().substring(0, 10) ?? 'Leo',
                'status': a['status'] ?? 'Imewasilishwa',
                'ticket': a['application_number'] ?? 'APP-EV-2026-00${a['id']}',
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
    final opportunities = _opportunities;

    final filtered = opportunities.where((item) {
      final matchesCat = (_selectedCategory == 'All' || item['category'] == _selectedCategory);
      final matchesSearch = _searchQuery.isEmpty ||
          item['title'].toString().toLowerCase().contains(_searchQuery.toLowerCase()) ||
          item['provider'].toString().toLowerCase().contains(_searchQuery.toLowerCase()) ||
          item['location'].toString().toLowerCase().contains(_searchQuery.toLowerCase());
      return matchesCat && matchesSearch;
    }).toList();

    return DefaultTabController(
      length: 2,
      child: Scaffold(
        appBar: AppBar(
          title: Text(
            lang.t('Gundua Fursa za EV', 'Explore Opportunities'),
            style: const TextStyle(fontWeight: FontWeight.w800, fontSize: 18),
          ),
          bottom: TabBar(
            indicatorColor: TevdaColors.forestGreen,
            labelColor: TevdaColors.forestGreen,
            unselectedLabelColor: TevdaColors.slate500,
            labelStyle: const TextStyle(fontWeight: FontWeight.w700, fontSize: 13),
            tabs: [
              Tab(text: lang.t('Fursa Zilizopo', 'Active Opportunities')),
              Tab(text: lang.t('Maombi Yangu (${_myApplications.length})', 'My Applications (${_myApplications.length})')),
            ],
          ),
        ),
        body: TabBarView(
          children: [
            // Tab 1: Directory
            Column(
              children: [
                // Search Input
                Padding(
                  padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 12),
                  child: TextField(
                    decoration: InputDecoration(
                      hintText: lang.t('Tafuta ajira, mikopo, ruzuku, vipuri...', 'Search jobs, loans, grants...'),
                      prefixIcon: const Icon(Icons.search_rounded, color: TevdaColors.slate400),
                      filled: true,
                      fillColor: Colors.white,
                      contentPadding: const EdgeInsets.symmetric(horizontal: 16, vertical: 12),
                      border: OutlineInputBorder(
                        borderRadius: BorderRadius.circular(14),
                        borderSide: const BorderSide(color: Color(0xFFE2E8F0)),
                      ),
                      enabledBorder: OutlineInputBorder(
                        borderRadius: BorderRadius.circular(14),
                        borderSide: const BorderSide(color: Color(0xFFE2E8F0)),
                      ),
                    ),
                    onChanged: (val) => setState(() => _searchQuery = val),
                  ),
                ),

                // Category Chips
                SingleChildScrollView(
                  scrollDirection: Axis.horizontal,
                  padding: const EdgeInsets.symmetric(horizontal: 16),
                  child: Row(
                    children: [
                      'All',
                      'Employment',
                      'Vehicle Financing',
                      'Grants',
                      'Spare Parts',
                    ].map((cat) {
                      final isSel = _selectedCategory == cat;
                      return Padding(
                        padding: const EdgeInsets.only(right: 8),
                        child: ChoiceChip(
                          label: Text(
                            cat == 'All' ? lang.t('Zote', 'All') : cat,
                            style: TextStyle(
                              fontSize: 12,
                              fontWeight: isSel ? FontWeight.w700 : FontWeight.w500,
                              color: isSel ? Colors.white : TevdaColors.slate700,
                            ),
                          ),
                          selected: isSel,
                          selectedColor: TevdaColors.forestGreen,
                          backgroundColor: Colors.white,
                          shape: RoundedRectangleBorder(
                            borderRadius: BorderRadius.circular(20),
                            side: BorderSide(color: isSel ? TevdaColors.forestGreen : const Color(0xFFE2E8F0)),
                          ),
                          onSelected: (_) => setState(() => _selectedCategory = cat),
                        ),
                      );
                    }).toList(),
                  ),
                ),
                const SizedBox(height: 12),

                // List
                Expanded(
                  child: filtered.isEmpty
                      ? Center(
                          child: Text(lang.t('Hakuna fursa zilizopatikana', 'No matching opportunities found')),
                        )
                      : ListView.separated(
                          padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 6),
                          itemCount: filtered.length,
                          separatorBuilder: (_, _) => const SizedBox(height: 12),
                          itemBuilder: (context, index) {
                            final opp = filtered[index];
                            return _buildOpportunityCard(opp);
                          },
                        ),
                ),
              ],
            ),

            // Tab 2: My Applications
            _myApplications.isEmpty
                ? Center(child: Text(lang.t('Hujawasilisha maombi ya fursa bado', 'No applications submitted yet')))
                : ListView.separated(
                    padding: const EdgeInsets.all(16),
                    itemCount: _myApplications.length,
                    separatorBuilder: (_, _) => const SizedBox(height: 12),
                    itemBuilder: (context, index) {
                      final app = _myApplications[index];
                      return Container(
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
                              mainAxisAlignment: MainAxisAlignment.spaceBetween,
                              children: [
                                Container(
                                  padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 4),
                                  decoration: BoxDecoration(
                                    color: const Color(0xFFEFF6FF),
                                    borderRadius: BorderRadius.circular(6),
                                  ),
                                  child: Text(
                                    app['ticket'],
                                    style: const TextStyle(fontSize: 11, fontWeight: FontWeight.w700, color: Color(0xFF2563EB)),
                                  ),
                                ),
                                Container(
                                  padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 4),
                                  decoration: BoxDecoration(
                                    color: TevdaColors.goldLight,
                                    borderRadius: BorderRadius.circular(20),
                                  ),
                                  child: Text(
                                    app['status'],
                                    style: const TextStyle(fontSize: 10, fontWeight: FontWeight.w800, color: TevdaColors.goldDark),
                                  ),
                                ),
                              ],
                            ),
                            const SizedBox(height: 10),
                            Text(
                              app['title'],
                              style: const TextStyle(fontWeight: FontWeight.w800, fontSize: 14, color: TevdaColors.slate900),
                            ),
                            const SizedBox(height: 4),
                            Text(
                              'Mtoa Fursa: ${app['provider']}',
                              style: const TextStyle(fontSize: 12, color: TevdaColors.slate600),
                            ),
                            const SizedBox(height: 8),
                            Text(
                              'Tarehe ya Maombi: ${app['date']}',
                              style: const TextStyle(fontSize: 11, color: TevdaColors.slate400),
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

  Widget _buildOpportunityCard(Map<String, dynamic> opp) {
    final lang = LanguageController.instance;

    return Container(
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
      child: Material(
        color: Colors.transparent,
        child: InkWell(
          borderRadius: BorderRadius.circular(16),
          onTap: () => _openOpportunityDetail(opp),
          child: Padding(
            padding: const EdgeInsets.all(16),
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Row(
                  mainAxisAlignment: MainAxisAlignment.spaceBetween,
                  children: [
                    Container(
                      padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 3),
                      decoration: BoxDecoration(
                        color: TevdaColors.primarySurface,
                        borderRadius: BorderRadius.circular(6),
                      ),
                      child: Text(
                        opp['type'],
                        style: const TextStyle(fontSize: 10.5, fontWeight: FontWeight.w800, color: TevdaColors.forestGreen),
                      ),
                    ),
                    Text(
                      'Hadi: ${opp['deadline']}',
                      style: const TextStyle(fontSize: 11, color: TevdaColors.goldDark, fontWeight: FontWeight.w700),
                    ),
                  ],
                ),
                const SizedBox(height: 10),
                Text(
                  lang.isSwahili ? opp['title_sw'] : opp['title'],
                  style: const TextStyle(fontSize: 14, fontWeight: FontWeight.w800, color: TevdaColors.slate900),
                ),
                const SizedBox(height: 4),
                Row(
                  children: [
                    const Icon(Icons.business_rounded, size: 14, color: TevdaColors.slate500),
                    const SizedBox(width: 4),
                    Text(
                      opp['provider'],
                      style: const TextStyle(fontSize: 12, color: TevdaColors.slate700, fontWeight: FontWeight.w600),
                    ),
                  ],
                ),
                const SizedBox(height: 4),
                Row(
                  children: [
                    const Icon(Icons.location_on_outlined, size: 14, color: TevdaColors.slate400),
                    const SizedBox(width: 4),
                    Text(
                      opp['location'],
                      style: const TextStyle(fontSize: 11, color: TevdaColors.slate500),
                    ),
                  ],
                ),
                const SizedBox(height: 12),
                const Divider(height: 1, color: Color(0xFFF1F5F9)),
                const SizedBox(height: 10),
                Row(
                  children: [
                    Expanded(
                      child: Text(
                        opp['eligibility'],
                        maxLines: 1,
                        overflow: TextOverflow.ellipsis,
                        style: const TextStyle(fontSize: 11, color: TevdaColors.slate500, fontStyle: FontStyle.italic),
                      ),
                    ),
                    const SizedBox(width: 8),
                    Text(
                      lang.t('Tazama Vigezo →', 'View Details →'),
                      style: const TextStyle(fontSize: 12, fontWeight: FontWeight.w800, color: TevdaColors.forestGreen),
                    ),
                  ],
                ),
              ],
            ),
          ),
        ),
      ),
    );
  }

  void _openOpportunityDetail(Map<String, dynamic> opp) {
    showModalBottomSheet(
      context: context,
      isScrollControlled: true,
      backgroundColor: Colors.transparent,
      builder: (ctx) {
        return Container(
          height: MediaQuery.of(ctx).size.height * 0.82,
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
                  Container(
                    padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 4),
                    decoration: BoxDecoration(
                      color: TevdaColors.primarySurface,
                      borderRadius: BorderRadius.circular(8),
                    ),
                    child: Text(
                      opp['type'],
                      style: const TextStyle(fontSize: 11, fontWeight: FontWeight.w800, color: TevdaColors.forestGreen),
                    ),
                  ),
                  IconButton(icon: const Icon(Icons.close), onPressed: () => Navigator.pop(ctx)),
                ],
              ),
              const SizedBox(height: 8),
              Text(
                opp['title_sw'] ?? opp['title'],
                style: const TextStyle(fontSize: 18, fontWeight: FontWeight.w900, color: TevdaColors.slate900),
              ),
              const SizedBox(height: 6),
              Text(
                'Mtoa Fursa: ${opp['provider']}',
                style: const TextStyle(fontSize: 13, fontWeight: FontWeight.w700, color: TevdaColors.forestGreen),
              ),
              const SizedBox(height: 14),
              const Divider(),
              const SizedBox(height: 10),
              const Text('Vigezo na Masharti ya Ushiriki:', style: TextStyle(fontSize: 14, fontWeight: FontWeight.w800)),
              const SizedBox(height: 6),
              Text(opp['eligibility'], style: const TextStyle(fontSize: 13, color: TevdaColors.slate700, height: 1.4)),
              const SizedBox(height: 16),
              const Text('Eneo la Utoaji:', style: TextStyle(fontSize: 14, fontWeight: FontWeight.w800)),
              const SizedBox(height: 6),
              Text(opp['location'], style: const TextStyle(fontSize: 13, color: TevdaColors.slate600)),
              const Spacer(),
              SizedBox(
                width: double.infinity,
                child: ElevatedButton.icon(
                  onPressed: () {
                    Navigator.pop(ctx);
                    final oppId = opp['id'] as int;
                    ApiService.instance.applyOpportunity(oppId, 'Maombi ya mwanachama kupitia TEVDA App');
                    setState(() {
                      _myApplications.insert(0, {
                        'id': _myApplications.length + 1,
                        'title': opp['title_sw'] ?? opp['title'],
                        'provider': opp['provider'],
                        'date': 'Hivi Punde',
                        'status': 'Imewasilishwa (Submitted)',
                        'ticket': 'APP-EV-2026-0${_myApplications.length + 82}',
                      });
                    });
                    ScaffoldMessenger.of(context).showSnackBar(
                      const SnackBar(
                        content: Text('Maombi yako yamewasilishwa kwa mtoa fursa rasmi!'),
                        backgroundColor: TevdaColors.forestGreen,
                      ),
                    );
                  },
                  icon: const Icon(Icons.send_rounded, size: 18),
                  label: const Text('Tuma Maombi Sasa'),
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
}
