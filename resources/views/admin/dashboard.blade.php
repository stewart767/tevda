@extends('layouts.admin')

@section('title', 'Administrative Dashboard')
@section('page_title', 'Overview & Platform Operations')

@section('content')
<div class="space-y-8">
    
    <!-- KPI Counters Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        <!-- Active Members -->
        <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-xs flex items-center justify-between">
            <div>
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Active Members</span>
                <h3 class="text-2xl font-black text-slate-900 font-heading mt-1">{{ $stats['active_members'] }}</h3>
                <span class="text-[11px] text-emerald-600 font-semibold mt-1 block">{{ $stats['total_members'] }} total registered</span>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-emerald-100 text-emerald-700 flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
            </div>
        </div>

        <!-- Pending Applications -->
        <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-xs flex items-center justify-between">
            <div>
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Pending Review</span>
                <h3 class="text-2xl font-black text-amber-600 font-heading mt-1">{{ $stats['pending_applications'] }}</h3>
                <a href="{{ route('admin.members.index', ['status' => 'submitted']) }}" class="text-[11px] text-amber-700 hover:underline font-semibold mt-1 block">Review applications &rarr;</a>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-amber-100 text-amber-700 flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
            </div>
        </div>

        <!-- Certificates Issued -->
        <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-xs flex items-center justify-between">
            <div>
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Certificates Issued</span>
                <h3 class="text-2xl font-black text-cyan-700 font-heading mt-1">{{ $stats['certificates_issued'] }}</h3>
                <span class="text-[11px] text-cyan-600 font-semibold mt-1 block">Valid & QR Verified</span>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-cyan-100 text-cyan-700 flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/></svg>
            </div>
        </div>

        <!-- Open Complaints -->
        <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-xs flex items-center justify-between">
            <div>
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Open Complaints</span>
                <h3 class="text-2xl font-black text-rose-600 font-heading mt-1">{{ $stats['open_complaints'] }}</h3>
                <a href="{{ route('admin.cms.complaints.index') }}" class="text-[11px] text-rose-700 hover:underline font-semibold mt-1 block">Whistleblower tickets &rarr;</a>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-rose-100 text-rose-700 flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            </div>
        </div>
    </div>

    <!-- Middle Section: Breakdown Cards -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Members by Category -->
        <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-xs space-y-4">
            <h3 class="text-sm font-bold text-slate-900 font-heading">Approved Members by Tier</h3>
            <div class="space-y-3">
                @foreach ($membersByCategory as $cat)
                    <div>
                        <div class="flex justify-between text-xs mb-1">
                            <span class="font-semibold text-slate-700">{{ $cat->name }}</span>
                            <span class="font-bold text-slate-900">{{ $cat->members_count }}</span>
                        </div>
                        <div class="w-full bg-slate-100 rounded-full h-2">
                            <div class="bg-emerald-600 h-2 rounded-full" style="width: {{ $stats['active_members'] > 0 ? ($cat->members_count / $stats['active_members'] * 100) : 0 }}%"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Regional Distribution -->
        <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-xs space-y-4">
            <h3 class="text-sm font-bold text-slate-900 font-heading">Top Regions</h3>
            <div class="space-y-3">
                @forelse ($membersByRegion as $reg)
                    <div class="flex justify-between items-center text-xs py-1.5 border-b border-slate-100">
                        <span class="font-semibold text-slate-700">{{ $reg->name }}</span>
                        <span class="font-bold bg-slate-100 px-2.5 py-0.5 rounded-full text-slate-800">{{ $reg->members_count }}</span>
                    </div>
                @empty
                    <p class="text-xs text-slate-500 py-3 text-center">New regional distribution data will populate upon member intake.</p>
                @endforelse
            </div>
        </div>

        <!-- Quick Governance Actions -->
        <div class="bg-slate-900 text-white p-6 rounded-3xl shadow-xs space-y-4 flex flex-col justify-between">
            <div>
                <span class="text-[10px] uppercase font-bold text-emerald-400 tracking-wider">Quick Actions</span>
                <h3 class="text-base font-bold font-heading mt-1 mb-4">Direct Administrative Shortcuts</h3>
                <div class="space-y-2">
                    <a href="{{ route('admin.training.session.create') }}" class="w-full block bg-slate-800 hover:bg-slate-700 text-xs font-semibold py-2.5 px-4 rounded-xl transition text-slate-200">
                        + Schedule Training Session
                    </a>
                    <a href="{{ route('admin.opportunities.create') }}" class="w-full block bg-slate-800 hover:bg-slate-700 text-xs font-semibold py-2.5 px-4 rounded-xl transition text-slate-200">
                        + Publish Verified Opportunity
                    </a>
                    <a href="{{ route('admin.projects.create') }}" class="w-full block bg-slate-800 hover:bg-slate-700 text-xs font-semibold py-2.5 px-4 rounded-xl transition text-slate-200">
                        + Launch EV Project
                    </a>
                </div>
            </div>
            <div class="pt-4 border-t border-slate-800 text-[11px] text-slate-400">
                All administrative actions are permanently recorded in the immutable audit log.
            </div>
        </div>
    </div>

    <!-- Bottom Grids: Recent Applications & Audit Trail -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        
        <!-- Recent Applications Table -->
        <div class="lg:col-span-7 bg-white p-6 rounded-3xl border border-slate-200 shadow-xs space-y-4">
            <div class="flex justify-between items-center pb-2 border-b border-slate-100">
                <h3 class="text-sm font-bold text-slate-900 font-heading">Recent Membership Applications</h3>
                <a href="{{ route('admin.members.index') }}" class="text-xs font-bold text-emerald-700 hover:text-emerald-800">View All</a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="text-slate-400 uppercase font-semibold border-b border-slate-100">
                            <th class="pb-3">Applicant</th>
                            <th class="pb-3">Category</th>
                            <th class="pb-3">Status</th>
                            <th class="pb-3 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($recentApplications as $app)
                            <tr class="hover:bg-slate-50">
                                <td class="py-3">
                                    <strong class="text-slate-900 block">{{ $app->full_name }}</strong>
                                    <span class="text-[11px] text-slate-500">{{ $app->phone }}</span>
                                </td>
                                <td class="py-3 text-slate-600">{{ $app->category->name }}</td>
                                <td class="py-3">
                                    <span class="font-bold px-2 py-0.5 rounded-full text-[10px] uppercase {{ $app->status === 'approved' ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">
                                        {{ $app->status }}
                                    </span>
                                </td>
                                <td class="py-3 text-right">
                                    <a href="{{ route('admin.members.show', $app->id) }}" class="text-emerald-700 hover:text-emerald-800 font-bold">Review &rarr;</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-6 text-center text-slate-400">No applications recorded yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Recent Audit Trail -->
        <div class="lg:col-span-5 bg-white p-6 rounded-3xl border border-slate-200 shadow-xs space-y-4">
            <div class="flex justify-between items-center pb-2 border-b border-slate-100">
                <h3 class="text-sm font-bold text-slate-900 font-heading">Recent Audit Trail</h3>
                <a href="{{ route('admin.audit.index') }}" class="text-xs font-bold text-emerald-700 hover:text-emerald-800">Full Log</a>
            </div>

            <div class="space-y-3">
                @forelse ($recentAuditLogs as $log)
                    <div class="p-3 bg-slate-50 rounded-xl border border-slate-200/80 text-xs">
                        <div class="flex justify-between items-start">
                            <span class="font-bold text-slate-900">{{ $log->user_name }}</span>
                            <span class="text-[10px] text-slate-400">{{ $log->created_at->diffForHumans() }}</span>
                        </div>
                        <p class="text-slate-600 mt-0.5">Action: <span class="font-mono font-semibold text-emerald-800">{{ $log->action }}</span> ({{ $log->module }})</p>
                    </div>
                @empty
                    <p class="text-xs text-slate-400 py-4 text-center">Audit logs will record staff actions automatically.</p>
                @endforelse
            </div>
        </div>

    </div>

</div>
@endsection
