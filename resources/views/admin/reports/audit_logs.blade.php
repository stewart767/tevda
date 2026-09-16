@extends('layouts.admin')

@section('title', 'System Audit Trail')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-900">System Audit Trail & Security Logs</h1>
            <p class="text-sm text-slate-500">Immutable forensic audit log tracking all administrative actions, document verifications, status changes, and user activities.</p>
        </div>
        <div>
            <span class="inline-flex items-center px-3 py-2 rounded-xl bg-slate-100 text-slate-700 text-xs font-bold">
                <i class="fa-solid fa-list-check mr-1.5"></i> {{ $logs->total() }} Logged Events
            </span>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm">
        <form method="GET" action="{{ route('admin.audit.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
            <div>
                <label class="block text-xs font-bold text-slate-600 mb-1">Search Keywords</label>
                <div class="relative">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="User name, IP, Record ID..." class="w-full pl-9 pr-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-emerald-500 outline-none">
                    <i class="fa-solid fa-magnifying-glass absolute left-3 top-2.5 text-slate-400 text-xs"></i>
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-600 mb-1">Module</label>
                <select name="module" class="w-full py-2 px-3 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-emerald-500 outline-none">
                    <option value="">All Modules</option>
                    <option value="membership" {{ request('module') == 'membership' ? 'selected' : '' }}>Membership</option>
                    <option value="training" {{ request('module') == 'training' ? 'selected' : '' }}>Training</option>
                    <option value="certificate" {{ request('module') == 'certificate' ? 'selected' : '' }}>Certificate</option>
                    <option value="opportunity" {{ request('module') == 'opportunity' ? 'selected' : '' }}>Opportunity</option>
                    <option value="project" {{ request('module') == 'project' ? 'selected' : '' }}>Project</option>
                    <option value="payment" {{ request('module') == 'payment' ? 'selected' : '' }}>Payment / Finance</option>
                    <option value="governance" {{ request('module') == 'governance' ? 'selected' : '' }}>Governance</option>
                    <option value="cms" {{ request('module') == 'cms' ? 'selected' : '' }}>CMS / News</option>
                    <option value="complaints" {{ request('module') == 'complaints' ? 'selected' : '' }}>Complaints</option>
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-600 mb-1">Action Type</label>
                <input type="text" name="action" value="{{ request('action') }}" placeholder="e.g. approve, verify..." class="w-full py-2 px-3 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-emerald-500 outline-none">
            </div>

            <div class="flex items-end gap-2">
                <button type="submit" class="flex-1 py-2 px-4 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold transition flex items-center justify-center gap-1.5 shadow-sm shadow-emerald-600/20">
                    <i class="fa-solid fa-filter"></i> Filter
                </button>
                @if(request()->anyFilled(['search', 'module', 'action']))
                    <a href="{{ route('admin.audit.index') }}" class="py-2 px-3 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl text-xs font-bold transition">
                        <i class="fa-solid fa-rotate-right"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Audit Logs Table -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-50 border-b border-slate-200 text-xs font-bold text-slate-500 uppercase tracking-wider">
                    <tr>
                        <th class="px-5 py-4">Timestamp</th>
                        <th class="px-4 py-4">Operator / User</th>
                        <th class="px-4 py-4">Module & Action</th>
                        <th class="px-4 py-4">Record ID</th>
                        <th class="px-4 py-4">Audit Payload / State</th>
                        <th class="px-5 py-4">IP Address</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($logs as $log)
                        <tr class="hover:bg-slate-50/70 transition">
                            <td class="px-5 py-4 text-xs text-slate-500 whitespace-nowrap">
                                <div class="font-bold text-slate-700">{{ $log->created_at->format('d M Y') }}</div>
                                <div class="text-[11px] text-slate-400 font-mono">{{ $log->created_at->format('H:i:s') }}</div>
                            </td>
                            <td class="px-4 py-4">
                                <div class="font-black text-slate-900 text-xs">{{ $log->user_name ?? ($log->user?->name ?? 'System Process') }}</div>
                                <div class="text-[11px] text-slate-400">{{ $log->user?->email ?? '' }}</div>
                            </td>
                            <td class="px-4 py-4">
                                <div class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider bg-slate-100 text-slate-700">
                                    {{ $log->module }}
                                </div>
                                <div class="text-xs font-bold text-slate-800 mt-1">{{ ucwords(str_replace('_', ' ', $log->action)) }}</div>
                            </td>
                            <td class="px-4 py-4 font-mono text-xs text-slate-600">
                                {{ $log->record_id ?? 'N/A' }}
                            </td>
                            <td class="px-4 py-4 text-xs text-slate-600 max-w-xs">
                                @if($log->new_values)
                                    <div class="bg-slate-50 p-2 rounded-lg font-mono text-[10px] text-slate-600 line-clamp-2 border border-slate-100">
                                        {{ json_encode($log->new_values) }}
                                    </div>
                                @else
                                    <span class="text-slate-400 italic">No state changes</span>
                                @endif
                            </td>
                            <td class="px-5 py-4 font-mono text-xs text-slate-500 whitespace-nowrap">
                                {{ $log->ip_address ?? '127.0.0.1' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-12 text-center text-slate-400 text-xs">
                                <i class="fa-solid fa-clock-rotate-left text-3xl mb-2"></i>
                                <p>No audit logs recorded matching search criteria.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($logs->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $logs->withQueryString()->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
