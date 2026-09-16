@extends('layouts.admin')

@section('title', 'Finance & Fee Configuration')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-900">Finance & Fee Administration</h1>
            <p class="text-sm text-slate-500">Track association collections, configure membership tariff rates, and verify driver payments.</p>
        </div>
        <div>
            <a href="{{ route('admin.finance.invoices') }}" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-bold transition flex items-center gap-1.5">
                <i class="fa-solid fa-file-invoice-dollar"></i> View All Invoices
            </a>
        </div>
    </div>

    <!-- Financial KPI Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl">
                <i class="fa-solid fa-sack-dollar"></i>
            </div>
            <div>
                <div class="text-xl font-black text-slate-900">{{ number_format($stats['total_collected']) }} TZS</div>
                <div class="text-xs text-slate-500">Total Collected</div>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-xl">
                <i class="fa-solid fa-clock-rotate-left"></i>
            </div>
            <div>
                <div class="text-2xl font-black text-slate-900">{{ $stats['pending_payments'] }}</div>
                <div class="text-xs text-slate-500">Pending Verifications</div>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center text-xl">
                <i class="fa-solid fa-receipt"></i>
            </div>
            <div>
                <div class="text-2xl font-black text-slate-900">{{ $stats['unpaid_invoices'] }}</div>
                <div class="text-xs text-slate-500">Unpaid Invoices</div>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-xl">
                <i class="fa-solid fa-file-invoice"></i>
            </div>
            <div>
                <div class="text-2xl font-black text-slate-900">{{ $stats['total_invoices'] }}</div>
                <div class="text-xs text-slate-500">Total Invoices Issued</div>
            </div>
        </div>
    </div>

    <!-- Official Fee Schedule Configuration -->
    <div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-sm space-y-4">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-base font-black text-slate-900 flex items-center gap-2">
                    <i class="fa-solid fa-sliders text-emerald-600"></i> Association Fee Schedule & Tariffs
                </h2>
                <p class="text-xs text-slate-500">Configure confirmed association fees. Unconfirmed fees remain 0 or configurable by authorized administrators.</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($fees as $fee)
                <div class="p-4 rounded-xl border border-slate-200 bg-slate-50/50 space-y-3 text-xs">
                    <div class="flex items-center justify-between">
                        <span class="font-bold text-slate-900 text-sm">{{ $fee->name }}</span>
                        <span class="px-2 py-0.5 rounded text-[10px] font-bold {{ $fee->is_active ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-200 text-slate-600' }}">
                            {{ $fee->is_active ? 'Active' : 'Disabled' }}
                        </span>
                    </div>
                    <div class="text-slate-500 text-[11px]">{{ $fee->description ?? 'Official membership/service fee' }}</div>

                    <!-- Fee Update Form -->
                    <form method="POST" action="{{ route('admin.finance.fees.update', $fee->id) }}" class="space-y-2 pt-2 border-t border-slate-200">
                        @csrf
                        <div class="flex items-center gap-2">
                            <input type="number" name="amount" value="{{ $fee->amount }}" min="0" step="500" class="flex-1 py-1.5 px-3 bg-white border border-slate-200 rounded-lg text-xs font-mono font-bold outline-none focus:ring-1 focus:ring-emerald-500">
                            <span class="font-bold text-slate-500">{{ $fee->currency }}</span>
                            <button type="submit" class="px-3 py-1.5 bg-slate-900 hover:bg-emerald-600 text-white rounded-lg text-xs font-bold transition">
                                Save
                            </button>
                        </div>
                    </form>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Payments Table & Verification Queue -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex items-center justify-between">
            <h2 class="text-base font-black text-slate-900 flex items-center gap-2">
                <i class="fa-solid fa-money-check-dollar text-emerald-600"></i> Driver Payments & Bank Confirmation Queue
            </h2>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-50 border-b border-slate-200 text-xs font-bold text-slate-500 uppercase tracking-wider">
                    <tr>
                        <th class="px-5 py-4">Reference / Receipt</th>
                        <th class="px-4 py-4">Member Driver</th>
                        <th class="px-4 py-4">Amount</th>
                        <th class="px-4 py-4">Method & Channel</th>
                        <th class="px-4 py-4">Date & Proof</th>
                        <th class="px-4 py-4">Status & Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($recentPayments as $pay)
                        <tr class="hover:bg-slate-50/70 transition">
                            <td class="px-5 py-4">
                                <div class="font-mono font-bold text-xs text-slate-900">{{ $pay->payment_reference }}</div>
                                @if($pay->receipt)
                                    <div class="text-[11px] font-mono text-emerald-600 flex items-center gap-1 mt-0.5">
                                        <i class="fa-solid fa-file-invoice"></i> {{ $pay->receipt->receipt_number }}
                                        <a href="{{ route('admin.finance.receipts.download', $pay->receipt->id) }}" class="underline ml-1" title="Download Receipt PDF">PDF</a>
                                    </div>
                                @endif
                            </td>
                            <td class="px-4 py-4">
                                <div class="font-black text-slate-900 text-xs">{{ $pay->member->full_name ?? 'Anonymous / Member' }}</div>
                                <div class="text-[11px] text-slate-500">{{ $pay->member->membership_number ?? '' }}</div>
                            </td>
                            <td class="px-4 py-4 text-xs font-black text-slate-900">
                                {{ number_format($pay->amount) }} {{ $pay->currency }}
                            </td>
                            <td class="px-4 py-4 text-xs text-slate-600">
                                <div>{{ ucwords(str_replace('_', ' ', $pay->payment_method ?? 'Mobile Money')) }}</div>
                                <div class="text-[11px] text-slate-400 font-mono">{{ $pay->gateway_transaction_reference ?? '' }}</div>
                            </td>
                            <td class="px-4 py-4 text-xs text-slate-500 whitespace-nowrap">
                                <div>{{ $pay->created_at->format('d M Y, H:i') }}</div>
                                @if($pay->proof_document_path)
                                    <a href="{{ asset('storage/' . $pay->proof_document_path) }}" target="_blank" class="text-[11px] text-blue-600 hover:underline flex items-center gap-1 mt-0.5">
                                        <i class="fa-solid fa-paperclip"></i> View Proof Slip
                                    </a>
                                @endif
                            </td>
                            <td class="px-4 py-4">
                                @if($pay->status === 'pending')
                                    <!-- Inline Verification Form -->
                                    <form method="POST" action="{{ route('admin.finance.payments.verify', $pay->id) }}" class="flex items-center gap-1.5">
                                        @csrf
                                        <button type="submit" name="status" value="completed" class="px-2.5 py-1 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-xs font-bold transition flex items-center gap-1">
                                            <i class="fa-solid fa-check"></i> Confirm
                                        </button>
                                        <button type="submit" name="status" value="failed" class="px-2.5 py-1 bg-rose-600 hover:bg-rose-700 text-white rounded-lg text-xs font-bold transition flex items-center gap-1">
                                            <i class="fa-solid fa-xmark"></i> Reject
                                        </button>
                                    </form>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold {{ $pay->status === 'completed' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-rose-50 text-rose-700 border border-rose-200' }}">
                                        {{ ucfirst($pay->status) }}
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-12 text-center text-slate-400 text-xs">
                                <i class="fa-solid fa-receipt text-3xl mb-2"></i>
                                <p>No payment records logged.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($recentPayments->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $recentPayments->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
