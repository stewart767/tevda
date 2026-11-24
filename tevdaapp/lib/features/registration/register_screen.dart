import 'package:flutter/material.dart';
import '../../core/theme.dart';
import '../../core/constants.dart';
import '../../core/localization.dart';
import '../../core/api_service.dart';

class RegisterScreen extends StatefulWidget {
  const RegisterScreen({super.key});

  @override
  State<RegisterScreen> createState() => _RegisterScreenState();
}

class _RegisterScreenState extends State<RegisterScreen> {
  int _currentStep = 0;
  int _selectedCategoryId = 1; // 1: Full, 2: Associate, 3: Honorary, 4: Institutional
  bool _isSubmitting = false;

  // Form Controllers
  final _fullNameCtrl = TextEditingController();
  final _phoneCtrl = TextEditingController();
  final _emailCtrl = TextEditingController();
  final _nidaCtrl = TextEditingController();
  final _licenseCtrl = TextEditingController();
  final _vehiclePlateCtrl = TextEditingController();
  final _vehicleMakeCtrl = TextEditingController();

  String _selectedRegion = 'Dar es Salaam';
  String _selectedDistrict = 'Kinondoni';
  final String _wardName = 'Kijitonyama';
  bool _agreedToTerms = false;

  bool get _isDriverCategory => (_selectedCategoryId == 1);

  @override
  void dispose() {
    _fullNameCtrl.dispose();
    _phoneCtrl.dispose();
    _emailCtrl.dispose();
    _nidaCtrl.dispose();
    _licenseCtrl.dispose();
    _vehiclePlateCtrl.dispose();
    _vehicleMakeCtrl.dispose();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    final lang = LanguageController.instance;

    return Scaffold(
      appBar: AppBar(
        title: Text(
          lang.t('Usajili wa Uanachama TEVDA', 'TEVDA Member Registration'),
          style: const TextStyle(fontWeight: FontWeight.w800, fontSize: 17),
        ),
      ),
      body: Stepper(
        type: StepperType.horizontal,
        currentStep: _currentStep,
        elevation: 0,
        controlsBuilder: (context, details) {
          return Padding(
            padding: const EdgeInsets.only(top: 24),
            child: Row(
              children: [
                if (_currentStep > 0)
                  Expanded(
                    child: OutlinedButton(
                      onPressed: details.onStepCancel,
                      style: OutlinedButton.styleFrom(padding: const EdgeInsets.symmetric(vertical: 14)),
                      child: Text(lang.t('Rudi Nyuma', 'Back')),
                    ),
                  ),
                if (_currentStep > 0) const SizedBox(width: 12),
                Expanded(
                  child: ElevatedButton(
                    onPressed: _isSubmitting ? null : details.onStepContinue,
                    style: ElevatedButton.styleFrom(
                      backgroundColor: TevdaColors.forestGreen,
                      padding: const EdgeInsets.symmetric(vertical: 14),
                    ),
                    child: _isSubmitting
                        ? const SizedBox(
                            width: 20,
                            height: 20,
                            child: CircularProgressIndicator(color: Colors.white, strokeWidth: 2),
                          )
                        : Text(
                            _currentStep == 3 ? lang.t('Kamilisha Usajili', 'Submit Registration') : lang.t('Endelea →', 'Continue →'),
                          ),
                  ),
                ),
              ],
            ),
          );
        },
        onStepContinue: () {
          if (_currentStep < 3) {
            setState(() => _currentStep++);
          } else {
            _completeRegistration(context);
          }
        },
        onStepCancel: () {
          if (_currentStep > 0) setState(() => _currentStep--);
        },
        steps: [
          // Step 1: Category
          Step(
            title: const Text('Kundi', style: TextStyle(fontSize: 11)),
            isActive: _currentStep >= 0,
            state: _currentStep > 0 ? StepState.complete : StepState.indexed,
            content: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                const Text(
                  'Chagua Kundi Rasmi la Uanachama:',
                  style: TextStyle(fontWeight: FontWeight.w900, fontSize: 14),
                ),
                const SizedBox(height: 12),
                ...AppConstants.membershipCategories.map((cat) {
                  final isSel = _selectedCategoryId == cat['id'];
                  return InkWell(
                    onTap: () => setState(() => _selectedCategoryId = cat['id'] as int),
                    child: Container(
                      margin: const EdgeInsets.only(bottom: 10),
                      padding: const EdgeInsets.all(14),
                      decoration: BoxDecoration(
                        color: isSel ? TevdaColors.primarySurface : Colors.white,
                        borderRadius: BorderRadius.circular(12),
                        border: Border.all(
                          color: isSel ? TevdaColors.forestGreen : const Color(0xFFE2E8F0),
                          width: isSel ? 2 : 1,
                        ),
                      ),
                      child: Row(
                        children: [
                          Icon(
                            isSel ? Icons.radio_button_checked : Icons.radio_button_off,
                            color: isSel ? TevdaColors.forestGreen : TevdaColors.slate400,
                            size: 20,
                          ),
                          const SizedBox(width: 12),
                          Expanded(
                            child: Column(
                              crossAxisAlignment: CrossAxisAlignment.start,
                              children: [
                                Text(
                                  cat['name_sw'] ?? cat['name'],
                                  style: const TextStyle(fontWeight: FontWeight.w800, fontSize: 13),
                                ),
                                const SizedBox(height: 2),
                                Text(cat['description'], style: const TextStyle(fontSize: 11, color: TevdaColors.slate600)),
                              ],
                            ),
                          ),
                        ],
                      ),
                    ),
                  );
                }),
              ],
            ),
          ),

          // Step 2: Personal & Location
          Step(
            title: const Text('Taarifa', style: TextStyle(fontSize: 11)),
            isActive: _currentStep >= 1,
            state: _currentStep > 1 ? StepState.complete : StepState.indexed,
            content: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                const Text('Taarifa Binafsi & Eneo:', style: TextStyle(fontWeight: FontWeight.w900, fontSize: 14)),
                const SizedBox(height: 12),
                TextField(
                  controller: _fullNameCtrl,
                  decoration: const InputDecoration(labelText: 'Jina Kamili (Kama lilivyo NIDA) *', border: OutlineInputBorder()),
                ),
                const SizedBox(height: 12),
                TextField(
                  controller: _phoneCtrl,
                  decoration: const InputDecoration(labelText: 'Namba ya Simu (M-Pesa / Tigo) *', border: OutlineInputBorder()),
                ),
                const SizedBox(height: 12),
                DropdownButtonFormField<String>(
                  initialValue: _selectedRegion,
                  decoration: const InputDecoration(labelText: 'Mkoa (Tanzania Region) *', border: OutlineInputBorder()),
                  items: AppConstants.tanzaniaRegions.take(6).map((r) => DropdownMenuItem(value: r, child: Text(r))).toList(),
                  onChanged: (v) => setState(() => _selectedRegion = v!),
                ),
                const SizedBox(height: 12),
                TextField(
                  controller: TextEditingController(text: _selectedDistrict),
                  decoration: const InputDecoration(labelText: 'Wilaya (District) *', border: OutlineInputBorder()),
                  onChanged: (v) => _selectedDistrict = v,
                ),
              ],
            ),
          ),

          // Step 3: Licensing & Vehicle
          Step(
            title: const Text('Leseni/EV', style: TextStyle(fontSize: 11)),
            isActive: _currentStep >= 2,
            state: _currentStep > 2 ? StepState.complete : StepState.indexed,
            content: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                if (!_isDriverCategory)
                  Container(
                    padding: const EdgeInsets.all(12),
                    margin: const EdgeInsets.only(bottom: 14),
                    decoration: BoxDecoration(
                      color: TevdaColors.primarySurface,
                      borderRadius: BorderRadius.circular(10),
                    ),
                    child: const Text(
                      'Uteuzi wa Mwanachama Mshiriki hauhitaji leseni ya udereva. Weka NIDA na taaluma yako tu.',
                      style: TextStyle(fontSize: 12, color: TevdaColors.forestGreen, fontWeight: FontWeight.w700),
                    ),
                  ),
                TextField(
                  controller: _nidaCtrl,
                  decoration: const InputDecoration(labelText: 'Namba ya NIDA (Tarwimu 20) *', border: OutlineInputBorder()),
                ),
                const SizedBox(height: 12),
                if (_isDriverCategory) ...[
                  TextField(
                    controller: _licenseCtrl,
                    decoration: const InputDecoration(labelText: 'Namba ya Leseni ya Udereva *', border: OutlineInputBorder()),
                  ),
                  const SizedBox(height: 12),
                  TextField(
                    controller: _vehiclePlateCtrl,
                    decoration: const InputDecoration(labelText: 'Namba ya Chombo cha EV (Plate)', border: OutlineInputBorder()),
                  ),
                ],
              ],
            ),
          ),

          // Step 4: Consent & Declaration
          Step(
            title: const Text('Kibali', style: TextStyle(fontSize: 11)),
            isActive: _currentStep >= 3,
            state: StepState.indexed,
            content: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                const Text('Tamko na Makubaliano ya Katiba:', style: TextStyle(fontWeight: FontWeight.w900, fontSize: 14)),
                const SizedBox(height: 10),
                Text(
                  'Ninathibitisha kuwa taarifa zote nilizotoa ni za kweli na sahihi. Ninakubali kufuata Katiba, Maadili na Miongozo ya TEVDA, LATRA, na Jeshi la Polisi Usalama Barabarani.',
                  style: const TextStyle(fontSize: 12, color: TevdaColors.slate700, height: 1.4),
                ),
                const SizedBox(height: 14),
                CheckboxListTile(
                  value: _agreedToTerms,
                  activeColor: TevdaColors.forestGreen,
                  title: const Text('Ninakubali masharti na kanuni za uanachama.', style: TextStyle(fontSize: 12, fontWeight: FontWeight.w700)),
                  onChanged: (v) => setState(() => _agreedToTerms = v ?? false),
                  controlAffinity: ListTileControlAffinity.leading,
                  contentPadding: EdgeInsets.zero,
                ),
              ],
            ),
          ),
        ],
      ),
    );
  }

  Future<void> _completeRegistration(BuildContext context) async {
    if (!_agreedToTerms) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text('Tafadhali weka alama ya kukubali masharti ya uanachama.')),
      );
      return;
    }

    if (_fullNameCtrl.text.trim().isEmpty || _phoneCtrl.text.trim().isEmpty) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text('Tafadhali jaza jina kamili na namba ya simu.')),
      );
      return;
    }

    setState(() => _isSubmitting = true);

    final payload = {
      'category_id': _selectedCategoryId,
      'name': _fullNameCtrl.text.trim(),
      'phone': _phoneCtrl.text.trim(),
      'email': _emailCtrl.text.trim().isNotEmpty ? _emailCtrl.text.trim() : null,
      'nida_number': _nidaCtrl.text.trim().isNotEmpty ? _nidaCtrl.text.trim() : null,
      'driving_license_number': _licenseCtrl.text.trim().isNotEmpty ? _licenseCtrl.text.trim() : null,
      'region': _selectedRegion,
      'district': _selectedDistrict,
      'ward': _wardName,
      'vehicle_plate': _vehiclePlateCtrl.text.trim().isNotEmpty ? _vehiclePlateCtrl.text.trim() : null,
    };

    final response = await ApiService.instance.register(payload);
    if (!mounted) return;
    setState(() => _isSubmitting = false);

    final data = response.data is Map<String, dynamic> ? response.data as Map<String, dynamic> : null;
    final ref = data?['application_number'] ?? data?['member_number'] ?? 'TEVDA-2026-${DateTime.now().millisecondsSinceEpoch.toString().substring(7)}';

    showDialog(
      context: context,
      builder: (ctx) {
        return AlertDialog(
          shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(20)),
          title: Row(
            children: const [
              Icon(Icons.check_circle_rounded, color: TevdaColors.forestGreen),
              SizedBox(width: 8),
              Text('USAJILI UMEPOKELEWA', style: TextStyle(fontWeight: FontWeight.w900, fontSize: 15)),
            ],
          ),
          content: Text(
            'Hongera sana! Usajili wako umepokelewa kwenye seva ya TEVDA.\n\nNamba ya Kumbukumbu:\n$ref\n\nHali: "Inasubiri Uhakiki & Ada ya Uanachama"\n\nAda rasmi itathibitishwa kupitia Control Number ya GePG/Benki kabla ya kuwezesha kitambulisho.',
            style: const TextStyle(fontSize: 13, height: 1.4),
          ),
          actions: [
            ElevatedButton(
              onPressed: () {
                Navigator.pop(ctx);
                Navigator.pop(context);
              },
              style: ElevatedButton.styleFrom(backgroundColor: TevdaColors.forestGreen),
              child: const Text('Nenda Kwenye Malipo'),
            ),
          ],
        );
      },
    );
  }
}
