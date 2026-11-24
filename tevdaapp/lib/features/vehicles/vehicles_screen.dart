import 'package:flutter/material.dart';
import '../../core/theme.dart';
import '../../core/localization.dart';
import '../../core/api_service.dart';

class VehiclesScreen extends StatefulWidget {
  const VehiclesScreen({super.key});

  @override
  State<VehiclesScreen> createState() => _VehiclesScreenState();
}

class _VehiclesScreenState extends State<VehiclesScreen> {
  final List<Map<String, dynamic>> _vehicles = [
    {
      'id': 1,
      'type': 'Electric Bajaji',
      'make': 'Ampersand',
      'model': 'E-Trike 2025',
      'plate': 'T 482 EVM',
      'battery_kwh': 12.0,
      'charging': 'Battery Swap',
      'daily_km': 85,
      'ownership': 'Driver-Operated (Lease-to-Own)',
      'status': 'LATRA Verified',
      'is_primary': true,
    },
    {
      'id': 2,
      'type': 'Electric Bodaboda',
      'make': 'Spiro',
      'model': 'Equator Max',
      'plate': 'MC 918 EVT',
      'battery_kwh': 4.5,
      'charging': 'Battery Swap',
      'daily_km': 110,
      'ownership': 'Owned',
      'status': 'LATRA Verified',
      'is_primary': false,
    },
  ];

  @override
  void initState() {
    super.initState();
    _fetchLiveVehicles();
  }

  Future<void> _fetchLiveVehicles() async {
    try {
      final res = await ApiService.instance.getVehicles();
      if (res.success && res.data != null && res.data is List) {
        final list = res.data as List;
        if (list.isNotEmpty) {
          setState(() {
            _vehicles.clear();
            for (var v in list) {
              if (v is Map<String, dynamic>) {
                _vehicles.add({
                  'id': v['id'] ?? 1,
                  'type': v['type'] ?? 'Electric Vehicle',
                  'make': v['make'] ?? 'EV Maker',
                  'model': v['model'] ?? 'EV Model',
                  'plate': v['registration_number'] ?? v['plate'] ?? 'T EV',
                  'battery_kwh': (v['battery_capacity_kwh'] as num?)?.toDouble() ?? 10.0,
                  'charging': v['charging_type'] ?? 'Battery Swap',
                  'daily_km': v['average_daily_range_km'] ?? 80,
                  'ownership': v['ownership_type'] ?? 'Owned',
                  'status': v['verification_status'] ?? 'Verified',
                  'is_primary': v['is_primary'] == true || v['is_primary'] == 1,
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

    return Scaffold(
      appBar: AppBar(
        title: Text(
          lang.t('Vyombo Vyangu vya Umeme (EVs)', 'My Electric Vehicles'),
          style: const TextStyle(fontWeight: FontWeight.w800, fontSize: 17),
        ),
        actions: [
          IconButton(
            tooltip: lang.t('Sajili Chombo Kipya', 'Register Vehicle'),
            icon: const Icon(Icons.add_circle_rounded, color: TevdaColors.forestGreen),
            onPressed: () => _openAddVehicleModal(context),
          ),
          const SizedBox(width: 8),
        ],
      ),
      body: SingleChildScrollView(
        padding: const EdgeInsets.all(20),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            // Summary Card
            Container(
              padding: const EdgeInsets.all(16),
              decoration: BoxDecoration(
                gradient: const LinearGradient(
                  colors: [Color(0xFF075B37), Color(0xFF0B9B62)],
                  begin: Alignment.topLeft,
                  end: Alignment.bottomRight,
                ),
                borderRadius: BorderRadius.circular(16),
              ),
              child: Row(
                children: [
                  Container(
                    width: 48,
                    height: 48,
                    decoration: BoxDecoration(
                      color: Colors.white.withValues(alpha: 0.18),
                      shape: BoxShape.circle,
                    ),
                    child: const Icon(Icons.electric_moped_rounded, color: Colors.white, size: 28),
                  ),
                  const SizedBox(width: 14),
                  Expanded(
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        Text(
                          lang.t('Vyombo ${_vehicles.length} Vimesajiliwa TEVDA', '${_vehicles.length} EVs Registered'),
                          style: const TextStyle(color: Colors.white, fontWeight: FontWeight.w900, fontSize: 14),
                        ),
                        const SizedBox(height: 3),
                        Text(
                          lang.t('Vyombo vyote vimethibitishwa na LATRA', 'All vehicles verified with LATRA'),
                          style: TextStyle(color: Colors.white.withValues(alpha: 0.85), fontSize: 11.5),
                        ),
                      ],
                    ),
                  ),
                ],
              ),
            ),
            const SizedBox(height: 20),

            Text(
              lang.t('Orodha ya Vyombo Vyako:', 'Your Vehicles List:'),
              style: const TextStyle(fontSize: 14, fontWeight: FontWeight.w800, color: TevdaColors.slate900),
            ),
            const SizedBox(height: 12),

            ..._vehicles.map((v) => _buildVehicleCard(v)),

            const SizedBox(height: 20),
            SizedBox(
              width: double.infinity,
              child: OutlinedButton.icon(
                onPressed: () => _openAddVehicleModal(context),
                icon: const Icon(Icons.add_rounded, size: 18),
                label: Text(lang.t('+ Ongeza Chombo Kingine cha Umeme', '+ Register Another EV')),
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
      ),
    );
  }

  Widget _buildVehicleCard(Map<String, dynamic> v) {
    return Container(
      margin: const EdgeInsets.only(bottom: 14),
      padding: const EdgeInsets.all(16),
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
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Row(
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
            children: [
              Row(
                children: [
                  Container(
                    padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 4),
                    decoration: BoxDecoration(
                      color: TevdaColors.primarySurface,
                      borderRadius: BorderRadius.circular(6),
                    ),
                    child: Text(
                      v['plate'],
                      style: const TextStyle(fontWeight: FontWeight.w900, fontSize: 13, color: TevdaColors.forestGreen),
                    ),
                  ),
                  if (v['is_primary']) ...[
                    const SizedBox(width: 8),
                    Container(
                      padding: const EdgeInsets.symmetric(horizontal: 6, vertical: 2),
                      decoration: BoxDecoration(
                        color: TevdaColors.goldLight,
                        borderRadius: BorderRadius.circular(4),
                      ),
                      child: const Text('KIKUU', style: TextStyle(fontSize: 9, fontWeight: FontWeight.w800, color: TevdaColors.goldDark)),
                    ),
                  ],
                ],
              ),
              Container(
                padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 3),
                decoration: BoxDecoration(
                  color: TevdaColors.primarySurface,
                  borderRadius: BorderRadius.circular(20),
                ),
                child: Row(
                  children: [
                    const Icon(Icons.verified_rounded, size: 12, color: TevdaColors.forestGreen),
                    const SizedBox(width: 4),
                    Text(v['status'], style: const TextStyle(fontSize: 10, fontWeight: FontWeight.w800, color: TevdaColors.forestGreen)),
                  ],
                ),
              ),
            ],
          ),
          const SizedBox(height: 10),
          Text(
            '${v['make']} ${v['model']} (${v['type']})',
            style: const TextStyle(fontSize: 15, fontWeight: FontWeight.w900, color: TevdaColors.slate900),
          ),
          const SizedBox(height: 4),
          Text(
            'Umiliki: ${v['ownership']}',
            style: const TextStyle(fontSize: 12, color: TevdaColors.slate600),
          ),
          const SizedBox(height: 12),
          const Divider(height: 1, color: Color(0xFFF1F5F9)),
          const SizedBox(height: 8),
          Row(
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
            children: [
              _buildMiniDetail(Icons.battery_charging_full_rounded, '${v['battery_kwh']} kWh'),
              _buildMiniDetail(Icons.swap_horiz_rounded, v['charging']),
              _buildMiniDetail(Icons.speed_rounded, '~${v['daily_km']} km/siku'),
            ],
          ),
        ],
      ),
    );
  }

  Widget _buildMiniDetail(IconData icon, String text) {
    return Row(
      children: [
        Icon(icon, size: 14, color: TevdaColors.slate400),
        const SizedBox(width: 4),
        Text(text, style: const TextStyle(fontSize: 11, color: TevdaColors.slate600, fontWeight: FontWeight.w600)),
      ],
    );
  }

  void _openAddVehicleModal(BuildContext context) {
    final plateCtrl = TextEditingController();
    final makeCtrl = TextEditingController();
    final modelCtrl = TextEditingController();
    String selectedType = 'Electric Bajaji';
    String selectedOwnership = 'Owned';

    showModalBottomSheet(
      context: context,
      isScrollControlled: true,
      backgroundColor: Colors.transparent,
      builder: (ctx) {
        return StatefulBuilder(
          builder: (context, setModalState) {
            return Container(
              height: MediaQuery.of(ctx).size.height * 0.85,
              decoration: const BoxDecoration(
                color: Colors.white,
                borderRadius: BorderRadius.vertical(top: Radius.circular(24)),
              ),
              padding: const EdgeInsets.all(24),
              child: SingleChildScrollView(
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
                        const Text(
                          'USAJILI WA CHOMBO CHA EV',
                          style: TextStyle(fontWeight: FontWeight.w900, fontSize: 15, color: TevdaColors.forestGreen),
                        ),
                        IconButton(icon: const Icon(Icons.close), onPressed: () => Navigator.pop(ctx)),
                      ],
                    ),
                    const Divider(),
                    const SizedBox(height: 12),

                    const Text('Aina ya Chombo *', style: TextStyle(fontWeight: FontWeight.w800, fontSize: 12)),
                    const SizedBox(height: 6),
                    DropdownButtonFormField<String>(
                      initialValue: selectedType,
                      items: ['Electric Bajaji', 'Electric Bodaboda', 'Electric Car', 'Electric Bus', 'Electric Van', 'Electric Truck'].map((t) {
                        return DropdownMenuItem(value: t, child: Text(t, style: const TextStyle(fontSize: 13)));
                      }).toList(),
                      onChanged: (v) => setModalState(() => selectedType = v!),
                      decoration: InputDecoration(
                        filled: true,
                        fillColor: TevdaColors.slate50,
                        border: OutlineInputBorder(borderRadius: BorderRadius.circular(10)),
                      ),
                    ),
                    const SizedBox(height: 14),

                    const Text('Namba ya Usajili wa Chombo (Plate) *', style: TextStyle(fontWeight: FontWeight.w800, fontSize: 12)),
                    const SizedBox(height: 6),
                    TextField(
                      controller: plateCtrl,
                      decoration: InputDecoration(
                        hintText: 'Mfano: T 789 EVM',
                        filled: true,
                        fillColor: TevdaColors.slate50,
                        border: OutlineInputBorder(borderRadius: BorderRadius.circular(10)),
                      ),
                    ),
                    const SizedBox(height: 14),

                    const Text('Kampuni Iliyounda (Make) *', style: TextStyle(fontWeight: FontWeight.w800, fontSize: 12)),
                    const SizedBox(height: 6),
                    TextField(
                      controller: makeCtrl,
                      decoration: InputDecoration(
                        hintText: 'Mfano: Ampersand, Spiro, Roam, Wuling',
                        filled: true,
                        fillColor: TevdaColors.slate50,
                        border: OutlineInputBorder(borderRadius: BorderRadius.circular(10)),
                      ),
                    ),
                    const SizedBox(height: 14),

                    const Text('Mfano wa Chombo (Model) *', style: TextStyle(fontWeight: FontWeight.w800, fontSize: 12)),
                    const SizedBox(height: 6),
                    TextField(
                      controller: modelCtrl,
                      decoration: InputDecoration(
                        hintText: 'Mfano: EV-300 Pro 2026',
                        filled: true,
                        fillColor: TevdaColors.slate50,
                        border: OutlineInputBorder(borderRadius: BorderRadius.circular(10)),
                      ),
                    ),
                    const SizedBox(height: 20),

                    SizedBox(
                      width: double.infinity,
                      child: ElevatedButton(
                        onPressed: () {
                          if (plateCtrl.text.trim().isEmpty) return;
                          final plate = plateCtrl.text.trim().toUpperCase();
                          final make = makeCtrl.text.trim().isNotEmpty ? makeCtrl.text.trim() : 'Ampersand';
                          final model = modelCtrl.text.trim().isNotEmpty ? modelCtrl.text.trim() : 'EV Runner';
                          Navigator.pop(ctx);

                          ApiService.instance.addVehicle({
                            'registration_number': plate,
                            'make': make,
                            'model': model,
                            'type': selectedType,
                            'battery_capacity_kwh': 10.0,
                            'charging_type': 'Battery Swap',
                            'average_daily_range_km': 90,
                            'ownership_type': selectedOwnership,
                          });

                          setState(() {
                            _vehicles.add({
                              'id': _vehicles.length + 1,
                              'type': selectedType,
                              'make': make,
                              'model': model,
                              'plate': plate,
                              'battery_kwh': 10.0,
                              'charging': 'Battery Swap',
                              'daily_km': 90,
                              'ownership': selectedOwnership,
                              'status': 'Inasubiri Ukaguzi',
                              'is_primary': false,
                            });
                          });
                          ScaffoldMessenger.of(context).showSnackBar(
                            const SnackBar(
                              content: Text('Chombo kimesajiliwa kikamilifu na kinasubiri ukaguzi wa LATRA!'),
                              backgroundColor: TevdaColors.forestGreen,
                            ),
                          );
                        },
                        style: ElevatedButton.styleFrom(
                          backgroundColor: TevdaColors.forestGreen,
                          padding: const EdgeInsets.symmetric(vertical: 14),
                        ),
                        child: const Text('Hifadhi Chombo Kipya'),
                      ),
                    ),
                  ],
                ),
              ),
            );
          },
        );
      },
    );
  }
}
