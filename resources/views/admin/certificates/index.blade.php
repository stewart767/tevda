@extends('layouts.admin')

@section('title', 'Certificate Management Engine')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-900">Certificate Management Engine</h1>
            <p class="text-sm text-slate-500">Track and issue tamper-proof certificates with embedded vector QR verification.</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.certificates.templates') }}" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-bold transition flex items-center gap-1.5">
                <i class="fa-solid fa-shapes"></i> Certificate Templates
            </a>
            <span class="inline-flex items-center px-3 py-2 rounded-xl bg-emerald-50 text-emerald-700 text-xs font-bold border border-emerald-200">
                <i class="fa-solid fa-award mr-1.5"></i> {{ $certificates->total() }} Total Issued
            </span>
        </div>
    </div>

    <!-- Filters & Search -->
    <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm">
        <form method="GET" action="{{ route('admin.certificates.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
            <div>
                <label class="block text-xs font-bold text-slate-600 mb-1">Search</label>
                <div class="relative">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cert No, Recipient Name..." class="w-full pl-9 pr-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-emerald-500 outline-none">
                    <i class="fa-solid fa-magnifying-glass absolute left-3 top-2.5 text-slate-400 text-xs"></i>
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-600 mb-1">Certificate Type</label>
                <select name="type" class="w-full py-2 px-3 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-emerald-500 outline-none">
                    <option value="">All Certificate Types</option>
                    <option value="membership" {{ request('type') == 'membership' ? 'selected' : '' }}>Membership Certificate</option>
                    <option value="training_completion" {{ request('type') == 'training_completion' ? 'selected' : '' }}>Training Completion</option>
                    <option value="workshop_attendance" {{ request('type') == 'workshop_attendance' ? 'selected' : '' }}>Workshop Attendance</option>
                    <option value="professional_accreditation" {{ request('type') == 'professional_accreditation' ? 'selected' : '' }}>Accreditation</option>
                    <option value="special_recognition" {{ request('type') == 'special_recognition' ? 'selected' : '' }}>Special Recognition</option>
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-600 mb-1">Status</label>
                <select name="status" class="w-full py-2 px-3 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-emerald-500 outline-none">
                    <option value="">All Statuses</option>
                    <option value="valid" {{ request('status') == 'valid' ? 'selected' : '' }}>Valid</option>
                    <option value="revoked" {{ request('status') == 'revoked' ? 'selected' : '' }}>Revoked</option>
                    <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>Expired</option>
                    <option value="replaced" {{ request('status') == 'replaced' ? 'selected' : '' }}>Replaced</option>
                </select>
            </div>

            <div class="flex items-end gap-2">
                <button type="submit" class="flex-1 py-2 px-4 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold transition flex items-center justify-center gap-1.5 shadow-sm shadow-emerald-600/20">
                    <i class="fa-solid fa-filter"></i> Filter
                </button>
                @if(request()->anyFilled(['search', 'type', 'status']))
                    <a href="{{ route('admin.certificates.index') }}" class="py-2 px-3 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl text-xs font-bold transition">
                        <i class="fa-solid fa-rotate-right"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Certificates Table -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-50 border-b border-slate-200 text-xs font-bold text-slate-500 uppercase tracking-wider">
                    <tr>
                        <th class="px-5 py-4">Certificate Number</th>
                        <th class="px-4 py-4">Recipient</th>
                        <th class="px-4 py-4">Type & Title</th>
                        <th class="px-4 py-4">Issue Date</th>
                        <th class="px-4 py-4">Status</th>
                        <th class="px-5 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($certificates as $cert)
                        <tr class="hover:bg-slate-50/70 transition">
                            <td class="px-5 py-4">
                                <div class="font-mono font-bold text-xs text-slate-900 flex items-center gap-1.5">
                                    <i class="fa-solid fa-qrcode text-emerald-600"></i>
                                    {{ $cert->certificate_number }}
                                </div>
                            </td>
                            <td class="px-4 py-4">
                                <div class="font-black text-slate-900 text-xs">{{ $cert->recipient_name }}</div>
                                @if($cert->member)
                                    <div class="text-[11px] text-slate-500">{{ $cert->member->membership_number ?? 'Member' }}</div>
                                @endif
                            </td>
                            <td class="px-4 py-4 text-xs">
                                <div class="font-bold text-slate-800">{{ $cert->title }}</div>
                                <div class="text-slate-500 text-[11px]">{{ $cert->course_name ?? ucwords(str_replace('_', ' ', $cert->certificate_type)) }}</div>
                            </td>
                            <td class="px-4 py-4 text-xs text-slate-500 whitespace-nowrap">
                                {{ $cert->issue_date ? $cert->issue_date->format('d M Y') : 'N/A' }}
                            </td>
                            <td class="px-4 py-4">
                                @php
                                    $certStatuses = [
                                        'valid' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                        'revoked' => 'bg-rose-50 text-rose-700 border-rose-200',
                                        'expired' => 'bg-amber-50 text-amber-700 border-amber-200',
                                        'replaced' => 'bg-slate-100 text-slate-600 border-slate-200',
                                    ];
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold border {{ $certStatuses[$cert->status] ?? 'bg-slate-100 text-slate-700 border-slate-200' }}">
                                    {{ ucfirst($cert->status) }}
                                </span>
                            </td>
                            <td class="px-5 py-4 text-right whitespace-nowrap">
                                <div class="flex items-center justify-end gap-1.5">
                                    <a href="{{ route('admin.certificates.download', $cert->id) }}" class="p-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg text-xs font-bold transition" title="Download PDF">
                                        <i class="fa-solid fa-file-arrow-down"></i>
                                    </a>
                                    <a href="{{ route('admin.certificates.show', $cert->id) }}" class="px-3 py-1.5 bg-slate-900 hover:bg-emerald-600 text-white rounded-lg text-xs font-bold transition">
                                        Details
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-12 text-center text-slate-400 text-xs">
                                <i class="fa-solid fa-award text-3xl mb-2"></i>
                                <p>No certificates found matching criteria.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($certificates->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $certificates->withQueryString()->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
