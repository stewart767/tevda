<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Payment;
use App\Models\Invoice;
use App\Models\Receipt;
use App\Models\Member;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class PaymentController extends BaseApiController
{
    /**
     * Get transaction history for current member
     */
    public function history(Request $request): JsonResponse
    {
        $member = Member::first();
        if (!$member) {
            return $this->errorResponse('Mwanachama hajapatikana.', 404);
        }

        $payments = Payment::with(['invoice', 'receipt'])
            ->where('member_id', $member->id)
            ->latest()
            ->get();

        $invoices = Invoice::where('member_id', $member->id)->latest()->get();

        return $this->successResponse([
            'payments' => $payments,
            'invoices' => $invoices,
            'summary' => [
                'total_paid' => $payments->where('status', 'paid')->sum('amount'),
                'pending_verification' => $payments->where('status', 'pending')->count(),
            ]
        ], 'Historia ya malipo.');
    }

    /**
     * Generate GePG / Electronic Payment Control Number
     */
    public function generateControlNumber(Request $request): JsonResponse
    {
        $member = Member::first();
        if (!$member) {
            return $this->errorResponse('Mwanachama hajapatikana.', 404);
        }

        $purpose = $request->input('purpose', 'membership_registration');
        $amount = (float) $request->input('amount', 50000.00); // authoritative or configured fee

        // Standard 12-digit Tanzanian Control Number generator
        $controlNumber = '99' . str_pad((string) mt_rand(1000000000, 9999999999), 10, '0', STR_PAD_LEFT);
        $expiresAt = now()->addDays(7);

        $invoice = Invoice::create([
            'member_id' => $member->id,
            'invoice_number' => Invoice::generateInvoiceNumber(),
            'control_number' => $controlNumber,
            'amount' => $amount,
            'purpose' => $purpose ?: 'Membership Registration',
            'status' => 'unpaid',
            'due_date' => $expiresAt,
            'notes' => 'Ada ya Uanachama TEVDA (' . ($member->category ? $member->category->name : 'Full Member') . ')',
        ]);

        return $this->successResponse([
            'control_number' => $controlNumber,
            'invoice_number' => $invoice->invoice_number,
            'amount' => $amount,
            'currency' => 'TZS',
            'expires_at' => $expiresAt->toIso8601String(),
            'channels' => [
                'mpesa' => ['name' => 'M-Pesa Paybill', 'number' => '500200'],
                'airtel' => ['name' => 'Airtel Money Lipa', 'number' => '500200'],
                'tigo' => ['name' => 'Tigo Pesa Lipa', 'number' => '500200'],
                'banks' => ['CRDB Bank', 'NMB Bank', 'NBC Bank', 'Azania Bank'],
            ],
            'instructions' => "Chagua Lipa kwa Control Number kwenye mtandao wako wa simu au benki kisha weka {$controlNumber}.",
        ], 'Namba ya malipo (Control Number) imetengenezwa.');
    }

    /**
     * Submit Proof of Payment Reference
     * IMPORTANT: Submitting proof does NOT automatically mark membership as Active.
     * It sets status to 'pending_verification' for financial clearance.
     */
    public function submitReference(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'control_number' => 'required|string',
            'transaction_reference' => 'required|string|min:6',
            'payment_method' => 'required|string', // mpesa, tigopesa, airtel, bank
            'amount' => 'required|numeric|min:1',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('Taarifa za muamala hazijakamilika.', 422, $validator->errors());
        }

        $member = Member::first();
        if (!$member) {
            return $this->errorResponse('Mwanachama hajapatikana.', 404);
        }

        $controlNumber = $request->input('control_number');
        $invoice = Invoice::where('control_number', $controlNumber)->first();

        $methodInput = strtolower($request->input('payment_method'));
        $paymentMethod = match($methodInput) {
            'mpesa', 'tigopesa', 'airtel', 'halopesa', 'mobile_money' => 'mobile_money',
            'crdb', 'nmb', 'bank', 'bank_transfer' => 'bank_transfer',
            default => 'mobile_money',
        };

        $payment = Payment::create([
            'payment_reference' => 'PAY-' . strtoupper(Str::random(10)),
            'invoice_id' => $invoice ? $invoice->id : null,
            'member_id' => $member->id,
            'amount' => $request->input('amount'),
            'currency' => 'TZS',
            'payment_method' => $paymentMethod,
            'transaction_reference' => strtoupper(trim($request->input('transaction_reference'))),
            'status' => 'pending', // Under verification - NEVER immediately active!
            'paid_at' => now(),
            'notes' => 'Submitted by mobile member. Awaiting financial reconciliation / gateway callback.',
        ]);

        return $this->successResponse([
            'payment_id' => $payment->id,
            'payment_reference' => $payment->payment_reference,
            'status' => 'pending_verification',
            'notice' => 'Kumbukumbu ya muamala imepokelewa. Malipo yako yatahakikiwa na mfumo wa fedha kabla ya kuwezesha kitambulisho na uanachama rasmi.',
        ], 'Muamala umepokelewa na unasubiri ukaguzi.');
    }

    /**
     * Verify payment status
     */
    public function verifyStatus(string $reference): JsonResponse
    {
        $payment = Payment::where('payment_reference', $reference)
            ->orWhere('transaction_reference', $reference)
            ->first();

        if (!$payment) {
            return $this->errorResponse('Muamala haukuthibitika kwenye kumbukumbu.', 404);
        }

        return $this->successResponse([
            'payment_reference' => $payment->payment_reference,
            'status' => $payment->status,
            'amount' => $payment->amount,
            'is_cleared' => in_array($payment->status, ['completed', 'paid']),
            'verified_at' => $payment->verified_at ? $payment->verified_at->toIso8601String() : null,
        ], 'Hali ya muamala.');
    }

    /**
     * Download receipt data
     */
    public function receipt(int $id): JsonResponse
    {
        $payment = Payment::with(['member', 'invoice'])->find($id);
        if (!$payment) {
            return $this->errorResponse('Risiti haijapatikana.', 404);
        }

        return $this->successResponse([
            'receipt_number' => 'REC-' . date('Y') . '-' . str_pad($payment->id, 5, '0', STR_PAD_LEFT),
            'payer_name' => $payment->member ? $payment->member->full_name : 'Mwanachama',
            'member_number' => $payment->member ? $payment->member->membership_number : 'N/A',
            'amount' => $payment->amount,
            'currency' => 'TZS',
            'payment_method' => strtoupper($payment->payment_method),
            'transaction_ref' => $payment->transaction_reference,
            'paid_at' => $payment->paid_at ? $payment->paid_at->format('d M Y H:i:s') : $payment->created_at->format('d M Y H:i:s'),
            'status' => strtoupper($payment->status),
            'issued_by' => 'Tanzania Electric Vehicle Drivers Association',
        ], 'Taarifa za risiti.');
    }
}
