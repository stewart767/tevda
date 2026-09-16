<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Member;
use App\Models\MembershipCard;
use App\Models\MembershipCategory;
use App\Models\Region;
use App\Models\AuditLog;
use App\Models\NotificationCustom;
use App\Services\PdfService;
use App\Services\QrCodeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class IdCardAdminController extends Controller
{
    /**
     * List all issued ID cards with search and filters.
     */
    public function index(Request $request)
    {
        $query = MembershipCard::with(['member.category', 'member.region', 'member.user']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('card_number', 'like', "%{$search}%")
                  ->orWhereHas('member', function ($mq) use ($search) {
                      $mq->where('full_name', 'like', "%{$search}%")
                         ->orWhere('membership_number', 'like', "%{$search}%")
                         ->orWhere('phone', 'like', "%{$search}%")
                         ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->where('is_active', true)
                      ->where(function ($q) {
                          $q->whereNull('expiry_date')->orWhere('expiry_date', '>=', now());
                      });
            } elseif ($request->status === 'expired') {
                $query->where('expiry_date', '<', now());
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            }
        }

        if ($request->filled('category_id')) {
            $query->whereHas('member', function ($mq) use ($request) {
                $mq->where('category_id', $request->category_id);
            });
        }

        if ($request->filled('region_id')) {
            $query->whereHas('member', function ($mq) use ($request) {
                $mq->where('region_id', $request->region_id);
            });
        }

        $cards = $query->latest('issue_date')->paginate(15);

        // Summary Statistics
        $totalCards = MembershipCard::count();
        $activeCards = MembershipCard::where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('expiry_date')->orWhere('expiry_date', '>=', now());
            })->count();
        $expiredCards = MembershipCard::where('expiry_date', '<', now())->count();
        $unissuedMembersCount = Member::where('status', 'approved')->whereDoesntHave('card')->count();

        $categories = MembershipCategory::all();
        $regions = Region::all();

        return view('admin.cards.index', compact(
            'cards',
            'totalCards',
            'activeCards',
            'expiredCards',
            'unissuedMembersCount',
            'categories',
            'regions'
        ));
    }

    /**
     * ID Card Creator Studio with live interactive preview.
     */
    public function create(Request $request)
    {
        $selectedMemberId = $request->query('member_id');
        $selectedMember = null;

        if ($selectedMemberId) {
            $selectedMember = Member::with(['category', 'region', 'card'])->find($selectedMemberId);
        }

        // Fetch approved members for selection dropdown
        $approvedMembers = Member::where('status', 'approved')
            ->with(['category', 'region', 'card'])
            ->orderBy('full_name')
            ->get();

        $categories = MembershipCategory::all();

        return view('admin.cards.create', compact('approvedMembers', 'selectedMember', 'categories'));
    }

    /**
     * Generate / Issue a new Member ID Card.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'member_id' => 'required|exists:members,id',
            'card_number' => 'nullable|string|max:100',
            'issue_date' => 'required|date',
            'expiry_date' => 'nullable|date|after_or_equal:issue_date',
            'theme' => 'nullable|string|in:emerald,midnight,cyan,clean',
            'motto' => 'nullable|string|max:100',
            'notes' => 'nullable|string|max:500',
        ]);

        DB::beginTransaction();
        try {
            $member = Member::with(['category', 'region'])->findOrFail($validated['member_id']);

            // Auto-generate membership number if missing
            if (!$member->membership_number) {
                $member->membership_number = Member::generateMembershipNumber();
                if ($member->status !== 'approved') {
                    $member->status = 'approved';
                    $member->approved_by = Auth::id();
                    $member->approved_at = now();
                }
                $member->save();
            }

            $cardNumber = !empty($validated['card_number']) 
                ? $validated['card_number'] 
                : 'CARD-' . $member->membership_number;

            $verifyMembershipUrl = url('/verify/membership/' . $member->membership_number);

            $cardData = [
                'name' => $member->full_name,
                'category' => $member->category->name,
                'region' => $member->region?->name ?? 'Tanzania',
                'phone' => $member->phone,
                'theme' => $validated['theme'] ?? 'emerald',
                'motto' => $validated['motto'] ?? 'SMART DRIVERS SMART MOBILITY',
                'notes' => $validated['notes'] ?? null,
                'issued_by' => Auth::user()->name,
            ];

            $card = MembershipCard::updateOrCreate(
                ['member_id' => $member->id],
                [
                    'card_number' => $cardNumber,
                    'issue_date' => $validated['issue_date'],
                    'expiry_date' => $validated['expiry_date'] ?? now()->addYear(),
                    'qr_code_path' => $verifyMembershipUrl,
                    'card_data' => $cardData,
                    'is_active' => true,
                ]
            );

            // Update member's expiry date if provided
            if (!empty($validated['expiry_date'])) {
                $member->expiry_date = $validated['expiry_date'];
                $member->save();
            }

            AuditLog::log('issued_id_card', 'membership', (string)$card->id, null, [
                'member' => $member->full_name,
                'card_number' => $card->card_number,
            ]);

            NotificationCustom::send(
                $member->user_id,
                'Digital Membership ID Card Issued',
                "Your TEVDA Member ID Card ({$card->card_number}) has been created and is now active in your dashboard.",
                route('portal.dashboard'),
                'success'
            );

            DB::commit();

            return redirect()->route('admin.cards.show', $card->id)
                ->with('success', "Member ID Card created and issued successfully for {$member->full_name}!");
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['error' => 'Failed to create ID card: ' . $e->getMessage()]);
        }
    }

    /**
     * View ID Card details with interactive 3D card preview.
     */
    public function show(int $id)
    {
        $card = MembershipCard::with(['member.category', 'member.region', 'member.primaryVehicle', 'member.user'])
            ->findOrFail($id);

        $member = $card->member;
        $verifyUrl = url('/verify/membership/' . $member->membership_number);
        $qrSvg = QrCodeService::svg($verifyUrl, 150);

        return view('admin.cards.show', compact('card', 'member', 'verifyUrl', 'qrSvg'));
    }

    /**
     * Download ID Card PDF (CR80 PVC standard or A4 printable sheet).
     */
    public function downloadPdf(int $id, Request $request)
    {
        $card = MembershipCard::with(['member.category', 'member.region'])->findOrFail($id);
        $member = $card->member;

        $format = $request->query('format', 'cr80'); // 'cr80' or 'a4'
        $theme = $request->query('theme', $card->card_data['theme'] ?? 'emerald');

        if ($format === 'a4') {
            $pdf = PdfService::generateMembershipCardA4SheetPdf($member, ['theme' => $theme]);
            return $pdf->download('TEVDA-Card-Sheet-' . $member->membership_number . '.pdf');
        }

        $pdf = PdfService::generateMembershipCardPdf($member, ['theme' => $theme, 'show_back' => true]);
        return $pdf->download('TEVDA-ID-Card-' . $member->membership_number . '.pdf');
    }

    /**
     * Direct print layout for ID cards.
     */
    public function printView(int $id, Request $request)
    {
        $card = MembershipCard::with(['member.category', 'member.region'])->findOrFail($id);
        $member = $card->member;

        $verifyUrl = url('/verify/membership/' . $member->membership_number);
        $qrCodeUri = QrCodeService::dataUri($verifyUrl, 120);
        $theme = $request->query('theme', $card->card_data['theme'] ?? 'emerald');

        return view('admin.cards.print', compact('card', 'member', 'verifyUrl', 'qrCodeUri', 'theme'));
    }

    /**
     * Reissue / Renew an existing ID card.
     */
    public function reissue(Request $request, int $id)
    {
        $card = MembershipCard::with('member')->findOrFail($id);
        $member = $card->member;

        $card->update([
            'issue_date' => now(),
            'expiry_date' => now()->addYear(),
            'is_active' => true,
        ]);

        $member->update([
            'expiry_date' => $card->expiry_date,
        ]);

        AuditLog::log('reissued_id_card', 'membership', (string)$card->id, null, [
            'card_number' => $card->card_number,
            'new_expiry' => $card->expiry_date->format('Y-m-d'),
        ]);

        return back()->with('success', "ID Card {$card->card_number} reissued successfully with 1-year validity!");
    }

    /**
     * Toggle ID Card active status.
     */
    public function toggleStatus(Request $request, int $id)
    {
        $card = MembershipCard::findOrFail($id);
        $card->is_active = !$card->is_active;
        $card->save();

        AuditLog::log('toggled_id_card_status', 'membership', (string)$card->id, null, ['is_active' => $card->is_active]);

        $statusStr = $card->is_active ? 'Activated' : 'Deactivated';
        return back()->with('success', "ID Card {$card->card_number} has been {$statusStr}.");
    }

    /**
     * Bulk generate ID cards for all approved members who do not currently have one.
     */
    public function bulkGenerate(Request $request)
    {
        $approvedMembers = Member::where('status', 'approved')
            ->whereDoesntHave('card')
            ->with(['category', 'region'])
            ->get();

        $count = 0;
        foreach ($approvedMembers as $member) {
            if (!$member->membership_number) {
                $member->membership_number = Member::generateMembershipNumber();
                $member->save();
            }

            $verifyMembershipUrl = url('/verify/membership/' . $member->membership_number);

            MembershipCard::create([
                'member_id' => $member->id,
                'card_number' => 'CARD-' . $member->membership_number,
                'issue_date' => now(),
                'expiry_date' => $member->expiry_date ?: now()->addYear(),
                'qr_code_path' => $verifyMembershipUrl,
                'card_data' => [
                    'name' => $member->full_name,
                    'category' => $member->category->name,
                    'region' => $member->region?->name ?? 'Tanzania',
                    'phone' => $member->phone,
                    'theme' => 'emerald',
                    'motto' => 'SMART DRIVERS SMART MOBILITY',
                ],
                'is_active' => true,
            ]);
            $count++;
        }

        AuditLog::log('bulk_generated_id_cards', 'membership', 'bulk', null, ['count' => $count]);

        return back()->with('success', "Successfully generated {$count} new Member ID Cards!");
    }

    /**
     * Batch download printable A4 sheet for multiple members.
     */
    public function bulkDownloadPdf(Request $request)
    {
        $query = Member::where('status', 'approved')
            ->whereHas('card', function ($q) {
                $q->where('is_active', true);
            })
            ->with(['category', 'region', 'card']);

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('region_id')) {
            $query->where('region_id', $request->region_id);
        }

        $members = $query->latest()->limit(50)->get();

        if ($members->isEmpty()) {
            return back()->with('info', 'No active member ID cards found matching criteria.');
        }

        $theme = $request->query('theme', 'emerald');
        $pdf = PdfService::generateMembershipCardA4SheetPdf($members, ['theme' => $theme]);

        return $pdf->download('TEVDA-Batch-ID-Cards-' . date('Ymd-His') . '.pdf');
    }

    /**
     * Generate or reissue ID card directly from member details.
     */
    public function generateForMember(Request $request, int $memberId)
    {
        $member = Member::with(['category', 'region'])->findOrFail($memberId);

        if (!$member->membership_number) {
            $member->membership_number = Member::generateMembershipNumber();
        }

        if ($member->status !== 'approved') {
            $member->status = 'approved';
            $member->approved_by = Auth::id();
            $member->approved_at = now();
        }

        $member->expiry_date = now()->addYear();
        $member->save();

        $verifyMembershipUrl = url('/verify/membership/' . $member->membership_number);

        $card = MembershipCard::updateOrCreate(
            ['member_id' => $member->id],
            [
                'card_number' => 'CARD-' . $member->membership_number,
                'issue_date' => now(),
                'expiry_date' => $member->expiry_date,
                'qr_code_path' => $verifyMembershipUrl,
                'card_data' => [
                    'name' => $member->full_name,
                    'category' => $member->category->name,
                    'region' => $member->region?->name ?? 'Tanzania',
                    'phone' => $member->phone,
                    'theme' => 'emerald',
                    'motto' => 'SMART DRIVERS SMART MOBILITY',
                ],
                'is_active' => true,
            ]
        );

        AuditLog::log('generated_member_card', 'membership', (string)$card->id, null, ['member' => $member->full_name]);

        return back()->with('success', "Member ID Card {$card->card_number} created and activated successfully!");
    }

    /**
     * Download ID card PDF directly from member details.
     */
    public function downloadMemberCardPdf(Request $request, int $memberId)
    {
        $member = Member::with(['category', 'region', 'card'])->findOrFail($memberId);

        if (!$member->membership_number) {
            return back()->with('error', 'Member does not have a membership number yet. Please generate their ID card first.');
        }

        $format = $request->query('format', 'cr80');
        if ($format === 'a4') {
            $pdf = PdfService::generateMembershipCardA4SheetPdf($member);
            return $pdf->download('TEVDA-Card-Sheet-' . $member->membership_number . '.pdf');
        }

        $pdf = PdfService::generateMembershipCardPdf($member);
        return $pdf->download('TEVDA-ID-Card-' . $member->membership_number . '.pdf');
    }
}
