import 'package:flutter/material.dart';
import '../../core/theme.dart';
import '../../core/localization.dart';

class NotificationItem {
  final String id;
  final String title;
  final String summary;
  final String body;
  final String category;
  final String time;
  final IconData icon;
  final Color iconColor;
  final Color iconBg;
  final bool isImportant;
  bool isRead;
  final String? actionLabel;
  final int? targetTabIndex;

  NotificationItem({
    required this.id,
    required this.title,
    required this.summary,
    required this.body,
    required this.category,
    required this.time,
    required this.icon,
    required this.iconColor,
    required this.iconBg,
    this.isImportant = false,
    this.isRead = false,
    this.actionLabel,
    this.targetTabIndex,
  });
}

class NotificationController extends ChangeNotifier {
  static final NotificationController instance = NotificationController();

  final List<NotificationItem> _items = [
    NotificationItem(
      id: 'notif-1',
      title: 'Uthibitisho wa Kitambulisho cha Kidijitali',
      summary: 'Kitambulisho chako cha kidijitali (TEVDA-2026-000128) kimeidhinishwa rasmi na kiko tayari kutumika.',
      body: 'Habari Ndugu Amani Juma,\n\nTunapenda kukutaarifu kuwa uhakiki wa taarifa zako na usajili wa chombo chako cha moto (Electric Bajaji, Namba T 482 EVM) umekamilika rasmi na Ofisi Kuu ya TEVDA.\n\nKitambulisho chako cha Kidijitali (Digital Smart ID) sasa ni hai (Active) kikiwa na QR Code inayotambulika kisheria na Mamlaka ya Udhibiti Usafiri Ardhini (LATRA) pamoja na Jeshi la Polisi Kitengo cha Usalama Barabarani.\n\nUnaweza kuonyesha kitambulisho hiki moja kwa moja kwenye simu yako au kupakua cheti rasmi cha uanachama kupitia sehemu ya "Digital Smart ID".',
      category: 'Kitambulisho',
      time: 'Dakika 10 zilizopita',
      icon: Icons.badge_rounded,
      iconColor: TevdaColors.forestGreen,
      iconBg: TevdaColors.primarySurface,
      isImportant: true,
      isRead: false,
      actionLabel: 'Tazama Kitambulisho Changu →',
      targetTabIndex: 1,
    ),
    NotificationItem(
      id: 'notif-2',
      title: 'Ratiba ya Mafunzo ya Usalama wa Betri',
      summary: 'Kikao cha mafunzo ya vitendo ya usalama wa betri za EV kimepangwa kufanyika 28 Nov 2026 saa 3:00 asubuhi.',
      body: 'Ndugu Dereva,\n\nTEVDA kwa kushirikiana na wadau wa nishati safi inakualika kwenye mafunzo maalum ya vitendo ya "EV Battery Safety & High-Voltage Maintenance".\n\n📅 Tarehe: 28 Novemba 2026\n⏰ Muda: Saa 3:00 Asubuhi - 6:00 Mchana\n📍 Eneo: Kituo cha TEVDA, Sinza Mori, Dar es Salaam\n\nMada Kuu zitakazofundishwa:\n1. Mbinu sahihi za kuchaji betri wakati wa mvua au unyevu mwingi.\n2. Kuzuia na kugundua mapema dalili za joto kali (Thermal Runaway).\n3. Hatua salama za kuchukua wakati wa dharura ya hitilafu ya umeme barabarani.\n\nWashiriki wote watapata cheti cha usalama (Safety Certificate).',
      category: 'Mafunzo',
      time: 'Saa 2 zilizopita',
      icon: Icons.school_rounded,
      iconColor: const Color(0xFF2563EB),
      iconBg: const Color(0xFFEFF6FF),
      isImportant: true,
      isRead: false,
      actionLabel: 'Fungua Moduli za Mafunzo →',
      targetTabIndex: 2,
    ),
    NotificationItem(
      id: 'notif-3',
      title: 'Kumbukumbu ya Namba ya Malipo (Control Number)',
      summary: 'Control Number 482173056194 inasubiri uhakiki wa malipo ya ada ya uanachama.',
      body: 'Ndugu Mwanachama,\n\nKumbukumbu ya namba yako ya malipo (Control Number) 482173056194 imesajiliwa kwenye mfumo.\n\n• Kumbukumbu: TXN-EV-9402816\n• Kiasi: Ada Rasmi kulingana na Baraza Kuu\n• Njia ya Malipo: GePG / Simu ya Mkononi (M-Pesa, Tigo, Airtel)\n• Hali: INASUBIRI UHAKIKI (Pending Verification)\n\nUkikamilisha malipo, tafadhali thibitisha namba ya kumbukumbu ya muamala ndani ya programu.',
      category: 'Malipo',
      time: 'Jana, 14:45',
      icon: Icons.receipt_long_rounded,
      iconColor: TevdaColors.emeraldGreen,
      iconBg: TevdaColors.primarySurface,
      isImportant: false,
      isRead: true,
      actionLabel: 'Tazama Sehemu ya Malipo →',
      targetTabIndex: 0,
    ),
    NotificationItem(
      id: 'notif-4',
      title: 'Ilani ya Usalama: Vituo vya Kuchaji Umeme',
      summary: 'Ukaguzi wa vituo vya kuchaji unaendelea Kinondoni na Ilala. Ripoti changamoto yoyote.',
      body: 'TAARIFA KWA MADEREVA WOTE:\n\nKamati ya Ulinzi na Usalama ya TEVDA inaendelea na zoezi la ukaguzi wa vituo vya kuchajia vyombo vya moto katika Wilaya za Kinondoni na Ilala.\n\nIwapo unakutana na:\n• Vituo vinavyotoza viwango holela vilivyo kinyume na makubaliano.\n• Waya zilizo wazi au plugs zenye cheche wakati wa kuchaji.\n• Unyanyasaji au vituo visivyo na vifaa vya kuzimia moto.\n\nTafadhali tumia sehemu ya "Report an Issue" ndani ya mfumo huu kuripoti mara moja.',
      category: 'Tahadhari',
      time: 'Siku 2 zilizopita',
      icon: Icons.warning_amber_rounded,
      iconColor: TevdaColors.goldDark,
      iconBg: TevdaColors.goldLight,
      isImportant: false,
      isRead: true,
      actionLabel: 'Toa Ripoti ya Kituo →',
      targetTabIndex: 3,
    ),
  ];

  List<NotificationItem> get items => List.unmodifiable(_items);
  int get unreadCount => _items.where((item) => !item.isRead).length;

  void markAsRead(String id) {
    final index = _items.indexWhere((item) => item.id == id);
    if (index != -1 && !_items[index].isRead) {
      _items[index].isRead = true;
      notifyListeners();
    }
  }

  void markAllAsRead() {
    for (var item in _items) {
      item.isRead = true;
    }
    notifyListeners();
  }
}

class NotificationsScreen extends StatefulWidget {
  final Function(int)? onNavigateTab;

  const NotificationsScreen({super.key, this.onNavigateTab});

  @override
  State<NotificationsScreen> createState() => _NotificationsScreenState();
}

class _NotificationsScreenState extends State<NotificationsScreen> {
  String _selectedCategory = 'All';

  @override
  Widget build(BuildContext context) {
    final lang = LanguageController.instance;

    return AnimatedBuilder(
      animation: NotificationController.instance,
      builder: (context, _) {
        final controller = NotificationController.instance;
        final allItems = controller.items;
        final filteredItems = _selectedCategory == 'All'
            ? allItems
            : allItems.where((i) => i.category == _selectedCategory).toList();

        return Scaffold(
          appBar: AppBar(
            title: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text(
                  lang.t('Arifa na Ujumbe', 'Notifications'),
                  style: const TextStyle(fontWeight: FontWeight.w800, fontSize: 18),
                ),
                Text(
                  controller.unreadCount > 0
                      ? '${controller.unreadCount} ${lang.t("hazijasomwa", "unread")}'
                      : lang.t('Zote zimesomwa', 'All caught up'),
                  style: TextStyle(
                    fontSize: 12,
                    color: controller.unreadCount > 0 ? TevdaColors.forestGreen : TevdaColors.slate500,
                    fontWeight: FontWeight.w600,
                  ),
                ),
              ],
            ),
            actions: [
              if (controller.unreadCount > 0)
                TextButton(
                  onPressed: () => controller.markAllAsRead(),
                  child: Text(
                    lang.t('Soma Zote', 'Read All'),
                    style: const TextStyle(
                      color: TevdaColors.forestGreen,
                      fontWeight: FontWeight.w700,
                      fontSize: 13,
                    ),
                  ),
                ),
              const SizedBox(width: 8),
            ],
          ),
          body: Column(
            children: [
              // Category filter pills
              SingleChildScrollView(
                scrollDirection: Axis.horizontal,
                padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 12),
                child: Row(
                  children: ['All', 'Kitambulisho', 'Mafunzo', 'Malipo', 'Tahadhari'].map((cat) {
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
                          side: BorderSide(
                            color: isSel ? TevdaColors.forestGreen : const Color(0xFFE2E8F0),
                          ),
                        ),
                        onSelected: (_) => setState(() => _selectedCategory = cat),
                      ),
                    );
                  }).toList(),
                ),
              ),

              // Notification list
              Expanded(
                child: filteredItems.isEmpty
                    ? Center(
                        child: Column(
                          mainAxisAlignment: MainAxisAlignment.center,
                          children: [
                            Icon(Icons.notifications_none_rounded, size: 64, color: TevdaColors.slate400),
                            const SizedBox(height: 12),
                            Text(
                              lang.t('Hakuna arifa kwa sasa', 'No notifications right now'),
                              style: TextStyle(color: TevdaColors.slate600, fontSize: 15),
                            ),
                          ],
                        ),
                      )
                    : ListView.separated(
                        padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 8),
                        itemCount: filteredItems.length,
                        separatorBuilder: (_, _) => const SizedBox(height: 10),
                        itemBuilder: (context, index) {
                          final item = filteredItems[index];
                          return _buildNotificationCard(item);
                        },
                      ),
              ),
            ],
          ),
        );
      },
    );
  }

  Widget _buildNotificationCard(NotificationItem item) {
    return InkWell(
      onTap: () => _openNotificationDetails(item),
      borderRadius: BorderRadius.circular(16),
      child: Container(
        padding: const EdgeInsets.all(16),
        decoration: BoxDecoration(
          color: item.isRead ? Colors.white : const Color(0xFFF0FDF4),
          borderRadius: BorderRadius.circular(16),
          border: Border.all(
            color: item.isRead ? const Color(0xFFE2E8F0) : TevdaColors.forestGreen.withValues(alpha: 0.3),
            width: item.isRead ? 1.0 : 1.5,
          ),
          boxShadow: [
            BoxShadow(
              color: Colors.black.withValues(alpha: 0.03),
              blurRadius: 8,
              offset: const Offset(0, 2),
            ),
          ],
        ),
        child: Row(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Container(
              width: 44,
              height: 44,
              decoration: BoxDecoration(
                color: item.iconBg,
                shape: BoxShape.circle,
              ),
              child: Icon(item.icon, color: item.iconColor, size: 22),
            ),
            const SizedBox(width: 14),
            Expanded(
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Row(
                    children: [
                      Container(
                        padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 2),
                        decoration: BoxDecoration(
                          color: TevdaColors.slate100,
                          borderRadius: BorderRadius.circular(6),
                        ),
                        child: Text(
                          item.category,
                          style: const TextStyle(fontSize: 10, fontWeight: FontWeight.w700, color: TevdaColors.slate600),
                        ),
                      ),
                      const Spacer(),
                      Text(
                        item.time,
                        style: const TextStyle(fontSize: 11, color: TevdaColors.slate400),
                      ),
                      if (!item.isRead) ...[
                        const SizedBox(width: 6),
                        Container(
                          width: 8,
                          height: 8,
                          decoration: const BoxDecoration(
                            color: TevdaColors.emeraldGreen,
                            shape: BoxShape.circle,
                          ),
                        ),
                      ],
                    ],
                  ),
                  const SizedBox(height: 6),
                  Text(
                    item.title,
                    style: TextStyle(
                      fontSize: 14,
                      fontWeight: item.isRead ? FontWeight.w600 : FontWeight.w800,
                      color: TevdaColors.slate900,
                    ),
                  ),
                  const SizedBox(height: 4),
                  Text(
                    item.summary,
                    maxLines: 2,
                    overflow: TextOverflow.ellipsis,
                    style: const TextStyle(fontSize: 12, color: TevdaColors.slate600, height: 1.3),
                  ),
                ],
              ),
            ),
          ],
        ),
      ),
    );
  }

  void _openNotificationDetails(NotificationItem item) {
    NotificationController.instance.markAsRead(item.id);

    showModalBottomSheet(
      context: context,
      isScrollControlled: true,
      backgroundColor: Colors.transparent,
      builder: (ctx) {
        return Container(
          height: MediaQuery.of(ctx).size.height * 0.78,
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
                  decoration: BoxDecoration(
                    color: TevdaColors.slate200,
                    borderRadius: BorderRadius.circular(2),
                  ),
                ),
              ),
              const SizedBox(height: 20),
              Row(
                children: [
                  Container(
                    width: 48,
                    height: 48,
                    decoration: BoxDecoration(
                      color: item.iconBg,
                      shape: BoxShape.circle,
                    ),
                    child: Icon(item.icon, color: item.iconColor, size: 26),
                  ),
                  const SizedBox(width: 14),
                  Expanded(
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        Text(
                          item.category.toUpperCase(),
                          style: const TextStyle(
                            fontSize: 11,
                            fontWeight: FontWeight.w800,
                            color: TevdaColors.forestGreen,
                            letterSpacing: 0.8,
                          ),
                        ),
                        Text(
                          item.time,
                          style: const TextStyle(fontSize: 12, color: TevdaColors.slate500),
                        ),
                      ],
                    ),
                  ),
                ],
              ),
              const SizedBox(height: 16),
              Text(
                item.title,
                style: const TextStyle(
                  fontSize: 18,
                  fontWeight: FontWeight.w900,
                  color: TevdaColors.slate900,
                ),
              ),
              const SizedBox(height: 16),
              const Divider(color: Color(0xFFE2E8F0)),
              const SizedBox(height: 12),
              Expanded(
                child: SingleChildScrollView(
                  child: Text(
                    item.body,
                    style: const TextStyle(
                      fontSize: 14,
                      color: TevdaColors.slate700,
                      height: 1.6,
                    ),
                  ),
                ),
              ),
              const SizedBox(height: 16),
              if (item.actionLabel != null)
                SizedBox(
                  width: double.infinity,
                  child: ElevatedButton(
                    onPressed: () {
                      Navigator.pop(ctx);
                      if (item.targetTabIndex != null && widget.onNavigateTab != null) {
                        Navigator.pop(context); // close notification screen
                        widget.onNavigateTab!(item.targetTabIndex!);
                      }
                    },
                    style: ElevatedButton.styleFrom(
                      backgroundColor: TevdaColors.forestGreen,
                      padding: const EdgeInsets.symmetric(vertical: 14),
                      shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
                    ),
                    child: Text(
                      item.actionLabel!,
                      style: const TextStyle(fontWeight: FontWeight.w700, fontSize: 14, color: Colors.white),
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
