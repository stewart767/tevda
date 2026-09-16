@extends('layouts.portal')

@section('title', 'Invoices & Payments — TEVDA Member Portal')

@section('content')
<div class="space-y-8">
    
    <!-- Header -->
    <div>
        <h1 class="text-2xl font-bold text-slate-900 font-heading">Invoices & Payment Records</h1>
        <p class="text-xs text-slate-500">View official membership fees, submit mobile money/bank payment proofs, and download receipts.</p>
    </div>

    <!-- Invoices List -->
    <div class="space-y-6">
        @forelse ($invoices as $invoice)
            <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200 shadow-sm space-y-6" x-data="{ payOpen: false }">
                <div class="flex flex-col sm:flex-row justify-between sm:items-center gap-4 pb-4 border-b border-slate-100">
                    <div>
                        <span class="text-xs font-mono text-slate-400 block uppercase">Invoice Number:</span>
                        <h2 class="text-lg font-bold text-slate-900 font-mono">{{ $invoice->invoice_number }}</h2>
                        <p class="text-xs text-slate-600 mt-0.5">{{ $invoice->purpose }}</p>
                    </div>

                    <div class="flex items-center gap-4">
                        <div class="text-right">
                            <span class="text-xs text-slate-400 block">Total Amount:</span>
                            <span class="text-xl font-black text-slate-900 font-mono">{{ number_format($invoice->amount) }} {{ $invoice->currency }}</span>
                        </div>

                        <span class="text-xs font-bold px-3 py-1.5 rounded-xl uppercase {{ $invoice->status === 'paid' ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800' }}">
                            {{ strtoupper($invoice->status) }}
                        </span>
                    </div>
                </div>

                <!-- Payments submitted under this invoice -->
                @if ($invoice->payments->count() > 0)
                    <div class="space-y-3">
                        <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400">Payment Transactions</h3>
                        @foreach ($invoice->payments as $payment)
                            <div class="p-4 bg-slate-50 rounded-2xl border border-slate-200 flex flex-col sm:flex-row justify-between sm:items-center gap-3 text-xs">
                                <div>
                                    <strong class="text-slate-900 font-mono">{{ $payment->payment_reference }}</strong>
                                    <span class="text-slate-500 block">Method: {{ ucwords(str_replace('_', ' ', $payment->payment_method)) }} • Ref: <span class="font-mono font-bold">{{ $payment->transaction_reference }}</span></span>
                                </div>
                                <div class="flex items-center gap-3">
                                    <span class="font-bold px-2.5 py-0.5 rounded-full {{ $payment->status === 'completed' ? 'bg-emerald-100 text-emerald-800' : ($payment->status === 'failed' ? 'bg-rose-100 text-rose-800' : 'bg-amber-100 text-amber-800') }}">
                                        {{ strtoupper($payment->status) }}
                                    </span>
                                    @if ($payment->receipt)
                                        <a href="{{ route('admin.finance.receipts.download', $payment->receipt->id) }}" class="bg-slate-900 hover:bg-slate-800 text-white font-bold px-3 py-1.5 rounded-lg transition text-[11px]">
                                            Download Receipt
                                        </a>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif

                <!-- Payment Action Button if unpaid -->
                @if ($invoice->status === 'unpaid')
                    <div>
                        <button type="button" @click="payOpen = !payOpen" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-3 px-6 rounded-xl text-xs transition shadow-xs">
                            Submit Payment Proof / Reference
                        </button>

                        <div x-show="payOpen" x-transition class="mt-4 p-6 bg-slate-50 rounded-2xl border border-slate-200 space-y-4">
                            <h4 class="text-xs font-bold text-slate-800 uppercase tracking-wider">Submit Transaction Verification</h4>
                            <form action="{{ route('portal.payments.submit_proof', $invoice->id) }}" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                @csrf

                                <div>
                                    <label class="block text-[11px] font-bold text-slate-700 mb-1">Payment Method</label>
                                    <select name="payment_method" class="w-full bg-white border border-slate-300 rounded-xl px-3 py-2 text-xs">
                                        <option value="mobile_money">M-Pesa / Tigo Pesa / Airtel Money</option>
                                        <option value="bank_transfer">Bank Transfer (NMB / CRDB / etc.)</option>
                                        <option value="cash">Secretariat Cash Payment</option>
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-[11px] font-bold text-slate-700 mb-1">Transaction Ref / Receipt No. <span class="text-rose-500">*</span></label>
                                    <input type="text" name="transaction_reference" required placeholder="e.g. MPESA-REF-XXXX" class="w-full bg-white border border-slate-300 rounded-xl px-3 py-2 text-xs font-mono">
                                </div>

                                <div>
                                    <label class="block text-[11px] font-bold text-slate-700 mb-1">Amount Paid (TZS) <span class="text-rose-500">*</span></label>
                                    <input type="number" name="amount" required value="{{ $invoice->amount }}" class="w-full bg-white border border-slate-300 rounded-xl px-3 py-2 text-xs font-mono">
                                </div>

                                <div class="sm:col-span-2">
                                    <label class="block text-[11px] font-bold text-slate-700 mb-1">Upload Receipt / Screenshot (Optional)</label>
                                    <input type="file" name="proof_file" class="text-xs text-slate-500 file:mr-2 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:bg-slate-200">
                                </div>

                                <div class="flex items-end">
                                    <button type="submit" class="w-full bg-slate-900 hover:bg-slate-800 text-white font-bold py-2.5 px-4 rounded-xl text-xs transition">
                                        Submit for Verification
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                @endif
            </div>
        @empty
            <div class="p-12 text-center text-slate-500 bg-white rounded-3xl border border-slate-200 text-sm">
                No invoices issued on your account.
            </div>
        @endforelse
    </div>

</div>
@endsection
