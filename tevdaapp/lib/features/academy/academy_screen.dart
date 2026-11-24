import 'package:flutter/material.dart';
import '../../core/theme.dart';
import '../../core/constants.dart';
import '../../core/localization.dart';
import '../../core/api_service.dart';

class AcademyScreen extends StatefulWidget {
  const AcademyScreen({super.key});

  @override
  State<AcademyScreen> createState() => _AcademyScreenState();
}

class _AcademyScreenState extends State<AcademyScreen> {
  String _selectedCategory = 'All';
  List<Map<String, dynamic>> _courses = List.from(AppConstants.officialCourses);
  final Map<int, double> _courseProgress = {
    1: 1.0, // Completed 100%
    2: 0.65, // In progress 65%
    3: 0.30, // In progress 30%
    4: 0.0,
    5: 0.0,
    6: 0.0,
  };

  @override
  void initState() {
    super.initState();
    _fetchLiveAcademy();
  }

  Future<void> _fetchLiveAcademy() async {
    try {
      final res = await ApiService.instance.getCourses();
      if (res.success && res.data != null && res.data is List) {
        final list = res.data as List;
        if (list.isNotEmpty) {
          setState(() {
            _courses = list.map((c) {
              final m = c as Map<String, dynamic>;
              return {
                'id': m['id'] ?? 1,
                'code': m['code'] ?? 'EV-100',
                'title': m['title'] ?? '',
                'title_sw': m['title_sw'] ?? m['title'] ?? '',
                'category': m['category'] ?? 'EV Operations',
                'duration_hours': m['duration_hours'] ?? 8,
                'pass_mark_percentage': m['pass_mark_percentage'] ?? 75,
                'description': m['description'] ?? '',
                'description_sw': m['description_sw'] ?? m['description'] ?? '',
                'modules_count': m['modules_count'] ?? 4,
              };
            }).toList();
          });
        }
      }

      final progRes = await ApiService.instance.getMyCourseProgress();
      if (progRes.success && progRes.data != null && progRes.data is List) {
        final pList = progRes.data as List;
        setState(() {
          for (var p in pList) {
            if (p is Map<String, dynamic>) {
              final cId = p['course_id'] as int?;
              final pct = (p['progress_percentage'] as num?)?.toDouble() ?? 0.0;
              if (cId != null) {
                _courseProgress[cId] = pct / 100.0;
              }
            }
          }
        });
      }
    } catch (_) {}
  }

  @override
  Widget build(BuildContext context) {
    final lang = LanguageController.instance;
    final courses = _courses;
    final filteredCourses = _selectedCategory == 'All'
        ? courses
        : courses.where((c) => c['category'] == _selectedCategory).toList();

    return Scaffold(
      appBar: AppBar(
        title: Text(
          lang.t('TEVDA Academy', 'TEVDA Academy'),
          style: const TextStyle(fontWeight: FontWeight.w800, fontSize: 18),
        ),
        actions: [
          IconButton(
            tooltip: lang.t('Vyeti Vyangu vya Mafunzo', 'My Certificates'),
            icon: const Icon(Icons.military_tech_rounded, color: TevdaColors.goldDark),
            onPressed: () => _openMyCertificatesModal(context),
          ),
          const SizedBox(width: 8),
        ],
      ),
      body: Column(
        children: [
          // Academy Stats Banner
          Container(
            margin: const EdgeInsets.symmetric(horizontal: 16, vertical: 10),
            padding: const EdgeInsets.all(16),
            decoration: BoxDecoration(
              gradient: const LinearGradient(
                colors: [Color(0xFF064E3B), Color(0xFF0B9B62)],
                begin: Alignment.topLeft,
                end: Alignment.bottomRight,
              ),
              borderRadius: BorderRadius.circular(16),
            ),
            child: Row(
              children: [
                Container(
                  width: 52,
                  height: 52,
                  decoration: BoxDecoration(
                    color: Colors.white.withValues(alpha: 0.15),
                    shape: BoxShape.circle,
                  ),
                  child: const Icon(Icons.school_rounded, color: Colors.white, size: 28),
                ),
                const SizedBox(width: 14),
                Expanded(
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      Text(
                        lang.t('Elimu na Mafunzo Endelevu ya EV', 'Continuous EV Education'),
                        style: const TextStyle(color: Colors.white, fontWeight: FontWeight.w900, fontSize: 14),
                      ),
                      const SizedBox(height: 4),
                      Text(
                        lang.t('Kozi 6 Zilizoidhinishwa • Cheti Rasmi', '6 Accredited Courses • Official Certs'),
                        style: TextStyle(color: Colors.white.withValues(alpha: 0.85), fontSize: 12),
                      ),
                    ],
                  ),
                ),
              ],
            ),
          ),

          // Categories Filter
          SingleChildScrollView(
            scrollDirection: Axis.horizontal,
            padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 6),
            child: Row(
              children: ['All', 'EV Operations', 'Road Safety', 'Financial Planning', 'Maintenance', 'Empowerment'].map((cat) {
                final isSel = _selectedCategory == cat;
                return Padding(
                  padding: const EdgeInsets.only(right: 8),
                  child: ChoiceChip(
                    label: Text(
                      cat == 'All' ? lang.t('Kozi Zote', 'All Courses') : cat,
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
          const SizedBox(height: 8),

          // Course List
          Expanded(
            child: ListView.separated(
              padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 8),
              itemCount: filteredCourses.length,
              separatorBuilder: (_, _) => const SizedBox(height: 12),
              itemBuilder: (context, index) {
                final course = filteredCourses[index];
                final courseId = course['id'] as int;
                final progress = _courseProgress[courseId] ?? 0.0;
                return _buildCourseCard(course, progress);
              },
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildCourseCard(Map<String, dynamic> course, double progress) {
    final lang = LanguageController.instance;
    final isDone = progress >= 1.0;

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
          onTap: () => _openCourseDetail(course),
          child: Padding(
            padding: const EdgeInsets.all(16),
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Row(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Container(
                      width: 44,
                      height: 44,
                      decoration: BoxDecoration(
                        color: isDone ? TevdaColors.primarySurface : const Color(0xFFEFF6FF),
                        borderRadius: BorderRadius.circular(12),
                      ),
                      child: Icon(
                        isDone ? Icons.verified_rounded : Icons.menu_book_rounded,
                        color: isDone ? TevdaColors.forestGreen : const Color(0xFF2563EB),
                        size: 22,
                      ),
                    ),
                    const SizedBox(width: 14),
                    Expanded(
                      child: Column(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          Container(
                            padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 2),
                            decoration: BoxDecoration(
                              color: TevdaColors.slate100,
                              borderRadius: BorderRadius.circular(6),
                            ),
                            child: Text(
                              course['category'],
                              style: const TextStyle(fontSize: 10, fontWeight: FontWeight.w700, color: TevdaColors.slate600),
                            ),
                          ),
                          const SizedBox(height: 6),
                          Text(
                            lang.isSwahili ? course['title_sw'] : course['title'],
                            style: const TextStyle(fontSize: 14, fontWeight: FontWeight.w800, color: TevdaColors.slate900),
                          ),
                          const SizedBox(height: 4),
                          Text(
                            course['description'],
                            maxLines: 2,
                            overflow: TextOverflow.ellipsis,
                            style: const TextStyle(fontSize: 12, color: TevdaColors.slate600, height: 1.3),
                          ),
                        ],
                      ),
                    ),
                  ],
                ),
                const SizedBox(height: 14),
                const Divider(height: 1, color: Color(0xFFF1F5F9)),
                const SizedBox(height: 10),

                // Meta row: Hours, Pass mark, Modules
                Row(
                  children: [
                    _buildMetaPill(Icons.schedule_rounded, '${course['duration_hours']} Masaa'),
                    const SizedBox(width: 8),
                    _buildMetaPill(Icons.grade_rounded, 'Pass: ${course['pass_mark_percentage']}%'),
                    const SizedBox(width: 8),
                    _buildMetaPill(Icons.layers_rounded, '${course['modules_count']} Moduli'),
                    const Spacer(),
                    if (isDone)
                      Container(
                        padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 4),
                        decoration: BoxDecoration(
                          color: TevdaColors.primarySurface,
                          borderRadius: BorderRadius.circular(12),
                        ),
                        child: Row(
                          children: const [
                            Icon(Icons.check_circle, size: 14, color: TevdaColors.forestGreen),
                            SizedBox(width: 4),
                            Text('IMEKAMILIKA', style: TextStyle(fontSize: 10, fontWeight: FontWeight.w800, color: TevdaColors.forestGreen)),
                          ],
                        ),
                      )
                    else if (progress > 0)
                      Text(
                        '${(progress * 100).toInt()}%',
                        style: const TextStyle(fontSize: 12, fontWeight: FontWeight.w800, color: TevdaColors.emeraldGreen),
                      )
                    else
                      Text(
                        lang.t('Anza Sasa →', 'Start →'),
                        style: const TextStyle(fontSize: 12, fontWeight: FontWeight.w800, color: TevdaColors.forestGreen),
                      ),
                  ],
                ),

                if (progress > 0 && !isDone) ...[
                  const SizedBox(height: 8),
                  LinearProgressIndicator(
                    value: progress,
                    backgroundColor: TevdaColors.slate100,
                    valueColor: const AlwaysStoppedAnimation<Color>(TevdaColors.emeraldGreen),
                    minHeight: 5,
                    borderRadius: BorderRadius.circular(4),
                  ),
                ],
              ],
            ),
          ),
        ),
      ),
    );
  }

  Widget _buildMetaPill(IconData icon, String text) {
    return Row(
      children: [
        Icon(icon, size: 14, color: TevdaColors.slate400),
        const SizedBox(width: 4),
        Text(text, style: const TextStyle(fontSize: 11, color: TevdaColors.slate500, fontWeight: FontWeight.w600)),
      ],
    );
  }

  void _openCourseDetail(Map<String, dynamic> course) {
    showModalBottomSheet(
      context: context,
      isScrollControlled: true,
      backgroundColor: Colors.transparent,
      builder: (ctx) {
        return Container(
          height: MediaQuery.of(ctx).size.height * 0.88,
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
                      course['category'],
                      style: const TextStyle(fontSize: 11, fontWeight: FontWeight.w800, color: TevdaColors.forestGreen),
                    ),
                  ),
                  IconButton(icon: const Icon(Icons.close), onPressed: () => Navigator.pop(ctx)),
                ],
              ),
              const SizedBox(height: 8),
              Text(
                course['title_sw'] ?? course['title'],
                style: const TextStyle(fontSize: 18, fontWeight: FontWeight.w900, color: TevdaColors.slate900),
              ),
              const SizedBox(height: 8),
              Text(course['description'], style: const TextStyle(fontSize: 13, color: TevdaColors.slate600, height: 1.4)),
              const SizedBox(height: 16),

              // Duration & Pass mark card
              Container(
                padding: const EdgeInsets.all(14),
                decoration: BoxDecoration(
                  color: TevdaColors.slate50,
                  borderRadius: BorderRadius.circular(12),
                  border: Border.all(color: const Color(0xFFE2E8F0)),
                ),
                child: Row(
                  mainAxisAlignment: MainAxisAlignment.spaceAround,
                  children: [
                    Column(
                      children: [
                        const Text('Muda wa Kozi', style: TextStyle(fontSize: 11, color: TevdaColors.slate500)),
                        const SizedBox(height: 2),
                        Text('${course['duration_hours']} Masaa', style: const TextStyle(fontSize: 14, fontWeight: FontWeight.w900, color: TevdaColors.slate900)),
                      ],
                    ),
                    Container(width: 1, height: 28, color: const Color(0xFFCBD5E1)),
                    Column(
                      children: [
                        const Text('Alama za Kufaulu', style: TextStyle(fontSize: 11, color: TevdaColors.slate500)),
                        const SizedBox(height: 2),
                        Text('${course['pass_mark_percentage']}%', style: const TextStyle(fontSize: 14, fontWeight: FontWeight.w900, color: TevdaColors.forestGreen)),
                      ],
                    ),
                    Container(width: 1, height: 28, color: const Color(0xFFCBD5E1)),
                    Column(
                      children: [
                        const Text('Moduli', style: TextStyle(fontSize: 11, color: TevdaColors.slate500)),
                        const SizedBox(height: 2),
                        Text('${course['modules_count']}', style: const TextStyle(fontSize: 14, fontWeight: FontWeight.w900, color: TevdaColors.slate900)),
                      ],
                    ),
                  ],
                ),
              ),
              const SizedBox(height: 16),
              const Text('Moduli za Mafunzo (Syllabus):', style: TextStyle(fontSize: 14, fontWeight: FontWeight.w800, color: TevdaColors.slate900)),
              const SizedBox(height: 8),

              Expanded(
                child: ListView.separated(
                  itemCount: course['modules_count'] as int,
                  separatorBuilder: (_, _) => const SizedBox(height: 8),
                  itemBuilder: (c, idx) {
                    return Container(
                      padding: const EdgeInsets.all(12),
                      decoration: BoxDecoration(
                        color: Colors.white,
                        borderRadius: BorderRadius.circular(10),
                        border: Border.all(color: const Color(0xFFE2E8F0)),
                      ),
                      child: Row(
                        children: [
                          CircleAvatar(
                            radius: 14,
                            backgroundColor: TevdaColors.slate100,
                            child: Text('${idx + 1}', style: const TextStyle(fontSize: 11, fontWeight: FontWeight.w700, color: TevdaColors.slate700)),
                          ),
                          const SizedBox(width: 12),
                          Expanded(
                            child: Text(
                              'Moduli ${idx + 1}: Misingi ya Vitendo & Hatua za Usalama',
                              style: const TextStyle(fontSize: 12, fontWeight: FontWeight.w600, color: TevdaColors.slate800),
                            ),
                          ),
                          const Icon(Icons.play_circle_outline_rounded, color: TevdaColors.forestGreen, size: 20),
                        ],
                      ),
                    );
                  },
                ),
              ),
              const SizedBox(height: 16),

              // Action buttons: Quiz & Lessons
              Row(
                children: [
                  Expanded(
                    child: ElevatedButton.icon(
                      onPressed: () {
                        Navigator.pop(ctx);
                        _openQuizModal(course);
                      },
                      icon: const Icon(Icons.quiz_rounded, size: 18),
                      label: const Text('Fanya Mtihani (Quiz)'),
                      style: ElevatedButton.styleFrom(
                        backgroundColor: TevdaColors.forestGreen,
                        padding: const EdgeInsets.symmetric(vertical: 14),
                      ),
                    ),
                  ),
                ],
              ),
            ],
          ),
        );
      },
    );
  }

  void _openQuizModal(Map<String, dynamic> course) {
    int selectedAnswer = -1;
    showModalBottomSheet(
      context: context,
      isScrollControlled: true,
      backgroundColor: Colors.transparent,
      builder: (ctx) {
        return StatefulBuilder(
          builder: (context, setModalState) {
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
                      Text(
                        'MTIHANI WA TATHMINI: ${course['pass_mark_percentage']}% PASS MARK',
                        style: const TextStyle(fontWeight: FontWeight.w900, fontSize: 12, color: TevdaColors.forestGreen),
                      ),
                      IconButton(icon: const Icon(Icons.close), onPressed: () => Navigator.pop(ctx)),
                    ],
                  ),
                  const SizedBox(height: 10),
                  const Text(
                    'Swali 1 kati ya 5:\nNi hatua gani ya kwanza ya dharura wakati betri ya chombo cha umeme inapoonyesha dalili za joto kali (Thermal Runaway)?',
                    style: TextStyle(fontSize: 14, fontWeight: FontWeight.w800, color: TevdaColors.slate900, height: 1.4),
                  ),
                  const SizedBox(height: 16),

                  ...[
                    'A. Endelea kuendesha chombo haraka kuelekea kituo cha kuchaji.',
                    'B. Simamisha chombo eneo salama, zima swichi kuu (Main Breaker), na uwaondoe abiria umbali wa mita 10+ mara moja.',
                    'C. Mimina maji ya kawaida kwenye sehemu ya plugs za chaji.',
                    'D. Fungua kifuniko cha seli za betri bila glavu za kinga.',
                  ].asMap().entries.map((entry) {
                    final idx = entry.key;
                    final text = entry.value;
                    final isSel = selectedAnswer == idx;
                    return InkWell(
                      onTap: () => setModalState(() => selectedAnswer = idx),
                      child: Container(
                        margin: const EdgeInsets.only(bottom: 10),
                        padding: const EdgeInsets.all(12),
                        decoration: BoxDecoration(
                          color: isSel ? TevdaColors.primarySurface : Colors.white,
                          borderRadius: BorderRadius.circular(10),
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
                            const SizedBox(width: 10),
                            Expanded(child: Text(text, style: const TextStyle(fontSize: 12, color: TevdaColors.slate800))),
                          ],
                        ),
                      ),
                    );
                  }),

                  const Spacer(),
                  SizedBox(
                    width: double.infinity,
                    child: ElevatedButton(
                      onPressed: selectedAnswer == -1
                          ? null
                          : () {
                              Navigator.pop(ctx);
                              final bool passed = (selectedAnswer == 1);
                              final int courseId = course['id'] as int;
                              ApiService.instance.submitAssessment(courseId, passed ? 100.0 : 40.0);
                              setState(() {
                                if (passed) {
                                  _courseProgress[courseId] = 1.0;
                                }
                              });
                              _showAssessmentResult(course, passed);
                            },
                      style: ElevatedButton.styleFrom(
                        backgroundColor: TevdaColors.forestGreen,
                        padding: const EdgeInsets.symmetric(vertical: 14),
                      ),
                      child: const Text('Wasilisha Majibu', style: TextStyle(fontWeight: FontWeight.w700)),
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

  void _showAssessmentResult(Map<String, dynamic> course, bool passed) {
    showDialog(
      context: context,
      builder: (ctx) {
        return AlertDialog(
          shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(20)),
          title: Row(
            children: [
              Icon(passed ? Icons.check_circle : Icons.error, color: passed ? TevdaColors.forestGreen : TevdaColors.error),
              const SizedBox(width: 10),
              Text(passed ? 'HONGERA SANA!' : 'HAKIKISHA MAJIBU', style: const TextStyle(fontSize: 16, fontWeight: FontWeight.w900)),
            ],
          ),
          content: Text(
            passed
                ? 'Umepata alama 100%! Umefaulu mtihani wa "${course['title_sw']}" na kiwango chako cha ufaulu kimevuka pass mark ya ${course['pass_mark_percentage']}%. Cheti chako kiko tayari kwenye sehemu ya vyeti.'
                : 'Hukufanikiwa kupata alama za kufaulu (Pass Mark: ${course['pass_mark_percentage']}%). Tafadhali soma tena moduli na ufanye mtihani upya.',
            style: const TextStyle(fontSize: 13, height: 1.4),
          ),
          actions: [
            TextButton(
              onPressed: () => Navigator.pop(ctx),
              child: const Text('Sawa', style: TextStyle(fontWeight: FontWeight.w700, color: TevdaColors.forestGreen)),
            ),
          ],
        );
      },
    );
  }

  void _openMyCertificatesModal(BuildContext context) {
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
                  const Text('VYETI VYANGU VYA MAFUNZO', style: TextStyle(fontWeight: FontWeight.w900, fontSize: 15, color: TevdaColors.forestGreen)),
                  IconButton(icon: const Icon(Icons.close), onPressed: () => Navigator.pop(ctx)),
                ],
              ),
              const Divider(),
              const SizedBox(height: 8),

              Expanded(
                child: ListView(
                  children: [
                    _buildCertificateItem('Core EV Operation & Swapping Mastery', '16 Masaa • Ufaulu 95%', 'TEVDA-CERT-2026-90412'),
                    const SizedBox(height: 12),
                    _buildCertificateItem('Defensive Driving & Urban EV Road Safety', '12 Masaa • Ufaulu 88%', 'TEVDA-CERT-2026-90413'),
                  ],
                ),
              ),
            ],
          ),
        );
      },
    );
  }

  Widget _buildCertificateItem(String title, String meta, String code) {
    return Container(
      padding: const EdgeInsets.all(16),
      decoration: BoxDecoration(
        color: const Color(0xFFFDFBF7),
        borderRadius: BorderRadius.circular(14),
        border: Border.all(color: TevdaColors.gold, width: 1.2),
      ),
      child: Row(
        children: [
          const Icon(Icons.military_tech_rounded, size: 36, color: TevdaColors.goldDark),
          const SizedBox(width: 12),
          Expanded(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text(title, style: const TextStyle(fontWeight: FontWeight.w800, fontSize: 13, color: TevdaColors.slate900)),
                const SizedBox(height: 2),
                Text(meta, style: const TextStyle(fontSize: 11, color: TevdaColors.slate600)),
                Text('Namba: $code', style: const TextStyle(fontSize: 10, color: TevdaColors.forestGreen, fontWeight: FontWeight.w700)),
              ],
            ),
          ),
          IconButton(
            icon: const Icon(Icons.download_rounded, color: TevdaColors.forestGreen),
            onPressed: () {
              ScaffoldMessenger.of(context).showSnackBar(
                const SnackBar(content: Text('Cheti kinapakuliwa (PDF)...')),
              );
            },
          ),
        ],
      ),
    );
  }
}
