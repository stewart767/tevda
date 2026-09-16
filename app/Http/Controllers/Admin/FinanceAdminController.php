<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Fee;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Receipt;
use App\Models\Member;
use App\Models\MembershipCategory;
use App\Models\AuditLog;
use App\Models\NotificationCustom;
use App\Services\PdfService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class FinanceAdminController extends Controller
{
    public function index()
    {
        $stats = [
            'total_collected' => Payment::where('status', 'completed')->sum('amount'),
            'pending_payments' => Payment::where('status', 'pending')->count(),
            'unpaid_invoices' => Invoice::where('status', 'unpaid')->count(),
            'total_invoices' => Invoice::count(),
        ];

        $recentPayments = Payment::with(['member.category', 'invoice', 'receipt'])->latest()->paginate(15);
        $fees = Fee::with('category')->get();

        return view('admin.finance.index', compact('stats', 'recentPayments', 'fees'));
    }

    public function invoices()
    {
        $invoices = Invoice::with(['member.category', 'payments'])->latest()->paginate(15);
        return view('admin.finance.invoices', compact('invoices'));
    }

    public function verifyPayment(Request $request, int $id)
    {
        $request->validate([
            'status' => 'required|in:completed,failed,refunded',
            'notes' => 'nullable|string|max:500',
        ]);

        $payment = Payment::with(['invoice', 'member'])->findOrFail($id);
        $oldStatus = $payment->status;

        DB::beginTransaction();
        try {
            $payment->update([
                'status' => $request->status,
                'verified_by' => Auth::id(),
                'verified_at' => now(),
                'notes' => $request->notes,
            ]);

            if ($request->status === 'completed') {
                // 1. Update invoice status
                if ($payment->invoice) {
                    $payment->invoice->update(['status' => 'paid']);
                }

                // 2. Generate Receipt
                $receipt = Receipt::updateOrCreate(
                    ['payment_id' => $payment->id],
                    [
                        'receipt_number' => Receipt::generateReceiptNumber(),
                        'invoice_id' => $payment->invoice_id,
                        'amount' => $payment->amount,
                        'currency' => $payment->currency,
                        'issued_at' => now(),
                    ]
                );

                // 3. Update member status if was payment_pending
                if ($payment->member && $payment->member->status === 'payment_pending') {
                    $payment->member->update(['status' => 'payment_confirmed']);
                }

                NotificationCustom::send(
                    $payment->member?->user_id,
                    'Payment Confirmed!',
                    "Your payment of {$payment->amount} {$payment->currency} (Ref: {$payment->payment_reference}) has been confirmed. Receipt: {$receipt->receipt_number}.",
                    route('portal.payments'),
                    'success'
                );
            }

            AuditLog::log('verified_payment', 'payment', (string)$payment->id, ['status' => $oldStatus], ['status' => $request->status]);

            DB::commit();

            return back()->with('success', "Payment {$payment->payment_reference} marked as " . strtoupper($request->status));
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Payment verification failed: ' . $e->getMessage()]);
        }
    }

    public function updateFee(Request $request, int $id)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0',
            'description' => 'nullable|string|max:255',
            'is_active' => 'nullable|boolean',
        ]);

        $fee = Fee::findOrFail($id);
        $oldAmount = $fee->amount;

        $fee->update([
            'amount' => $request->amount,
            'description' => $request->description,
            'is_active' => $request->boolean('is_active', true),
        ]);

        // If it's a category registration fee, sync with category table
        if ($fee->category_id && $fee->fee_type === 'membership_registration') {
            $fee->category->update(['registration_fee' => $request->amount]);
        } elseif ($fee->category_id && $fee->fee_type === 'annual_subscription') {
            $fee->category->update(['annual_fee' => $request->amount]);
        }

        AuditLog::log('updated_fee', 'finance', (string)$fee->id, ['amount' => $oldAmount], ['amount' => $request->amount]);

        return back()->with('success', "Fee '{$fee->name}' updated to {$request->amount} {$fee->currency}.");
    }

    public function downloadReceipt(int $id)
    {
        $receipt = Receipt::with(['payment.member', 'invoice'])->findOrFail($id);
        $pdf = PdfService::generateReceiptPdf($receipt);
        return $pdf->download($receipt->receipt_number . '.pdf');
    }
}
