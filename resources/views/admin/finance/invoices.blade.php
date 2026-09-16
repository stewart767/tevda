@extends('layouts.admin')

@section('title', 'Invoices Management')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.finance.index') }}" class="w-9 h-9 rounded-xl bg-white border border-slate-200 flex items-center justify-center text-slate-600 hover:text-slate-900 transition">
                <i class="fa-solid fa-arrow-left text-sm"></i>
            </a>
            <div>
                <h1 class="text-2xl font-black text-slate-900">Official Association Invoices</h1>
                <p class="text-sm text-slate-500">Registry of all billed membership dues, subscriptions, and training registration invoices.</p>
            </div>
        </div>
        <div>
            <span class="inline-flex items-center px-3 py-2 rounded-xl bg-emerald-50 text-emerald-700 text-xs font-bold border border-emerald-200">
                <i class="fa-solid fa-file-invoice mr-1.5"></i> {{ $invoices->total() }} Total Invoices
            </span>
        </div>
    </div>

    <!-- Invoices Table -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-50 border-b border-slate-200 text-xs font-bold text-slate-500 uppercase tracking-wider">
                    <tr>
                        <th class="px-5 py-4">Invoice Number</th>
                        <th class="px-4 py-4">Member Driver</th>
                        <th class="px-4 py-4">Description</th>
                        <th class="px-4 py-4">Amount</th>
                        <th class="px-4 py-4">Issue / Due Date</th>
                        <th class="px-4 py-4">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($invoices as $inv)
                        <tr class="hover:bg-slate-50/70 transition">
                            <td class="px-5 py-4">
                                <span class="font-mono font-bold text-xs text-slate-900 bg-slate-100 px-2.5 py-1 rounded-lg block w-fit">
                                    {{ $inv->invoice_number }}
                                </span>
                                @if(!empty($inv->control_number))
                                    <span class="font-mono font-bold text-[11px] text-emerald-800 bg-emerald-50 border border-emerald-200 px-2 py-0.5 rounded mt-1 inline-block">
                                        CTRL: {{ $inv->control_number }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-4">
                                <div class="font-black text-slate-900 text-xs">{{ $inv->member->full_name ?? 'Member' }}</div>
                                <div class="text-[11px] text-slate-500">{{ $inv->member->membership_number ?? '' }}</div>
                            </td>
                            <td class="px-4 py-4 text-xs text-slate-700">
                                {{ $inv->description ?? 'Membership Tariff' }}
                            </td>
                            <td class="px-4 py-4 text-xs font-black text-slate-900 whitespace-nowrap">
                                {{ number_format($inv->amount) }} {{ $inv->currency }}
                            </td>
                            <td class="px-4 py-4 text-xs text-slate-500 whitespace-nowrap">
                                <div>Issued: {{ $inv->issue_date ? $inv->issue_date->format('d M Y') : $inv->created_at->format('d M Y') }}</div>
                                <div class="text-[11px] text-slate-400">Due: {{ $inv->due_date ? $inv->due_date->format('d M Y') : 'Immediate' }}</div>
                            </td>
                            <td class="px-4 py-4">
                                @php
                                    $invStatuses = [
                                        'paid' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                        'unpaid' => 'bg-amber-50 text-amber-700 border-amber-200',
                                        'partially_paid' => 'bg-blue-50 text-blue-700 border-blue-200',
                                        'overdue' => 'bg-rose-50 text-rose-700 border-rose-200',
                                        'cancelled' => 'bg-slate-100 text-slate-600 border-slate-200',
                                    ];
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold border {{ $invStatuses[$inv->status] ?? 'bg-slate-100 text-slate-700 border-slate-200' }}">
                                    {{ ucfirst(str_replace('_', ' ', $inv->status)) }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-12 text-center text-slate-400 text-xs">
                                <i class="fa-solid fa-file-invoice text-3xl mb-2"></i>
                                <p>No invoices recorded in database.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($invoices->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $invoices->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
