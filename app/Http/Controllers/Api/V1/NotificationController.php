<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\NotificationCustom;
use App\Models\News;
use App\Models\Member;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NotificationController extends BaseApiController
{
    /**
     * List user notifications and system announcements
     */
    public function index(Request $request): JsonResponse
    {
        $member = Member::first();

        // System broadcast announcements from News
        $announcements = News::where('is_published', true)->latest()->take(5)->get();

        $notifications = [
            [
                'id' => 'notif-1',
                'title' => 'Uthibitisho wa Kitambulisho cha Kidijitali',
                'summary' => 'Kitambulisho chako cha kidijitali (TEVDA-2026-000128) kimeidhinishwa rasmi na kiko tayari kutumika.',
                'body' => "Habari Ndugu Mwanachama,\n\nTunapenda kukutaarifu kuwa uhakiki wa taarifa zako na usajili wa chombo chako cha moto umekamilika rasmi na Ofisi Kuu ya TEVDA.\n\nKitambulisho chako cha Kidijitali (Digital Smart ID) sasa ni hai kikiwa na QR Code inayotambulika kisheria na Mamlaka ya Udhibiti Usafiri Ardhini (LATRA) pamoja na Jeshi la Polisi Kitengo cha Usalama Barabarani.\n\nUnaweza kuonyesha kitambulisho hiki moja kwa moja kwenye simu yako au kupakua cheti rasmi cha uanachama.",
                'category' => 'Kitambulisho',
                'time' => 'Dakika 10 zilizopita',
                'is_important' => true,
                'is_read' => false,
                'action_label' => 'Tazama Kitambulisho Changu',
                'target_tab_index' => 1,
            ],
            [
                'id' => 'notif-2',
                'title' => 'Ratiba ya Mafunzo ya Usalama wa Betri',
                'summary' => 'Kikao cha mafunzo ya vitendo ya usalama wa betri za EV kimepangwa kufanyika 28 Nov 2026 saa 3:00 asubuhi.',
                'body' => "Ndugu Dereva,\n\nTEVDA kwa kushirikiana na wadau wa nishati safi inakualika kwenye mafunzo maalum ya vitendo ya 'EV Battery Safety & High-Voltage Maintenance'.\n\nTarehe: 28 Novemba 2026\nMuda: Saa 3:00 Asubuhi - 6:00 Mchana\nEneo: Kituo cha TEVDA, Sinza Mori, Dar es Salaam\n\nMada Kuu zitakazofundishwa:\n1. Mbinu sahihi za kuchaji betri wakati wa mvua au unyevu mwingi.\n2. Kuzuia na kugundua mapema dalili za joto kali (Thermal Runaway).\n3. Hatua salama za kuchukua wakati wa dharura ya hitilafu ya umeme barabarani.\n\nWashiriki wote watapata cheti cha usalama (Safety Certificate).",
                'category' => 'Mafunzo',
                'time' => 'Saa 2 zilizopita',
                'is_important' => true,
                'is_read' => false,
                'action_label' => 'Fungua Moduli za Mafunzo',
                'target_tab_index' => 2,
            ],
            [
                'id' => 'notif-3',
                'title' => 'Kumbukumbu ya Namba ya Malipo (Control Number)',
                'summary' => 'Control Number 482173056194 inasubiri uhakiki wa malipo ya ada ya uanachama.',
                'body' => "Ndugu Mwanachama,\n\nMalipo yako kupitia namba ya malipo (Control Number) 482173056194 yanashughulikiwa na mfumo wa fedha.\n\nKumbuka kuwa ada rasmi ya uanachama inathibitishwa na Baraza Kuu la TEVDA. Pindi malipo yanapothibitishwa, utapokea risiti rasmi na kitambulisho chako kitahuishwa mara moja.",
                'category' => 'Malipo',
                'time' => 'Jana, 14:45',
                'is_important' => false,
                'is_read' => true,
                'action_label' => 'Tazama Sehemu ya Malipo',
                'target_tab_index' => 0,
            ],
            [
                'id' => 'notif-4',
                'title' => 'Ilani ya Usalama: Vituo vya Kuchaji Umeme',
                'summary' => 'Ukaguzi wa vituo vya kuchaji unaendelea Kinondoni na Ilala. Ripoti changamoto yoyote.',
                'body' => "TAARIFA KWA MADEREVA WOTE:\n\nKamati ya Ulinzi na Usalama ya TEVDA inaendelea na zoezi la ukaguzi wa vituo vya kuchajia vyombo vya moto katika Wilaya za Kinondoni na Ilala.\n\nIwapo unakutana na:\n• Vituo vinavyotoza viwango holela vilivyo kinyume na makubaliano.\n• Waya zilizo wazi au plugs zenye cheche wakati wa kuchaji.\n• Unyanyasaji au vituo visivyo na vifaa vya kuzimia moto.\n\nTafadhali tumia sehemu ya 'Report an Issue' ndani ya mfumo huu kuripoti mara moja.",
                'category' => 'Tahadhari',
                'time' => 'Siku 2 zilizopita',
                'is_important' => false,
                'is_read' => true,
                'action_label' => 'Toa Ripoti ya Kituo',
                'target_tab_index' => 3,
            ],
        ];

        return $this->successResponse([
            'unread_count' => 2,
            'notifications' => $notifications,
            'announcements' => $announcements,
        ], 'Arifa na matangazo.');
    }

    /**
     * Mark single notification as read
     */
    public function markRead(string $id): JsonResponse
    {
        return $this->successResponse(['id' => $id, 'is_read' => true], 'Arifa imesomwa.');
    }

    /**
     * Mark all notifications as read
     */
    public function markAllRead(): JsonResponse
    {
        return $this->successResponse(['unread_count' => 0], 'Arifa zote zimetiwa alama ya kusomwa.');
    }
}
