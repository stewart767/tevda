@extends('layouts.admin')

@section('title', 'Administrative Dashboard')
@section('page_title', 'Overview & Platform Operations')

@section('content')
<div class="space-y-8">
    
    <!-- KPI Counters Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        <!-- Active Members -->
        <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Active Members</span>
                <h3 class="text-2xl font-black text-[#002040] font-heading mt-1">{{ $stats['active_members'] }}</h3>
                <span class="text-[11px] text-[#108010] font-semibold mt-1 block">{{ $stats['total_members'] }} total registered</span>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-[#108010]/10 text-[#108010] flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
            </div>
        </div>

        <!-- Pending Applications -->
        <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Pending Review</span>
                <h3 class="text-2xl font-black text-[#E5B51B] font-heading mt-1">{{ $stats['pending_applications'] }}</h3>
                <a href="{{ route('admin.members.index', ['status' => 'submitted']) }}" class="text-[11px] text-[#002040] hover:text-[#108010] font-semibold mt-1 block">Review applications &rarr;</a>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-[#E5B51B]/15 text-[#c49807] flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
            </div>
        </div>

        <!-- Certificates Issued -->
        <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Certificates Issued</span>
                <h3 class="text-2xl font-black text-[#002040] font-heading mt-1">{{ $stats['certificates_issued'] }}</h3>
                <span class="text-[11px] text-[#108010] font-semibold mt-1 block">Valid & QR Verified</span>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-[#002040]/10 text-[#002040] flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/></svg>
            </div>
        </div>

        <!-- Open Complaints -->
        <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm flex items-center justify-between">
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
        <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm space-y-4">
            <h3 class="text-sm font-bold text-[#002040] font-heading">Approved Members by Tier</h3>
            <div class="space-y-3">
                @foreach ($membersByCategory as $cat)
                    <div>
                        <div class="flex justify-between text-xs mb-1">
                            <span class="font-semibold text-slate-700">{{ $cat->name }}</span>
                            <span class="font-bold text-[#002040]">{{ $cat->members_count }}</span>
                        </div>
                        <div class="w-full bg-slate-100 rounded-full h-2">
                            <div class="bg-[#108010] h-2 rounded-full" style="width: {{ $stats['active_members'] > 0 ? ($cat->members_count / $stats['active_members'] * 100) : 0 }}%"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Regional Distribution -->
        <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm space-y-4">
            <h3 class="text-sm font-bold text-[#002040] font-heading">Top Regions</h3>
            <div class="space-y-3">
                @forelse ($membersByRegion as $reg)
                    <div class="flex justify-between items-center text-xs py-1.5 border-b border-slate-100">
                        <span class="font-semibold text-slate-700">{{ $reg->name }}</span>
                        <span class="font-bold bg-[#108010]/10 text-[#108010] px-2.5 py-0.5 rounded-full">{{ $reg->members_count }}</span>
                    </div>
                @empty
                    <p class="text-xs text-slate-500 py-3 text-center">New regional distribution data will populate upon member intake.</p>
                @endforelse
            </div>
        </div>

        <!-- Quick Governance Actions -->
        <div class="bg-[#002040] text-white p-6 rounded-3xl shadow-xl space-y-4 flex flex-col justify-between border border-white/10 relative overflow-hidden">
            <div class="absolute -right-16 -top-16 w-64 h-64 bg-[#108010]/20 rounded-full blur-2xl pointer-events-none"></div>

            <div class="relative z-10">
                <span class="text-[10px] uppercase font-bold text-[#E5B51B] tracking-wider">Quick Actions</span>
                <h3 class="text-base font-bold font-heading mt-1 mb-4 text-white">Direct Administrative Shortcuts</h3>
                <div class="space-y-2">
                    <a href="{{ route('admin.training.session.create') }}" class="w-full block bg-white/10 hover:bg-white/20 text-xs font-semibold py-2.5 px-4 rounded-xl transition text-white border border-white/10">
                        + Schedule Training Session
                    </a>
                    <a href="{{ route('admin.opportunities.create') }}" class="w-full block bg-white/10 hover:bg-white/20 text-xs font-semibold py-2.5 px-4 rounded-xl transition text-white border border-white/10">
                        + Publish Verified Opportunity
                    </a>
                    <a href="{{ route('admin.projects.create') }}" class="w-full block bg-white/10 hover:bg-white/20 text-xs font-semibold py-2.5 px-4 rounded-xl transition text-white border border-white/10">
                        + Launch EV Project
                    </a>
                </div>
            </div>
            <div class="pt-4 border-t border-white/10 text-[11px] text-slate-400 relative z-10">
                All administrative actions are permanently recorded in the immutable audit log.
            </div>
        </div>
    </div>

    <!-- Bottom Grids: Recent Applications & Audit Trail -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        
        <!-- Recent Applications Table -->
        <div class="lg:col-span-7 bg-white p-6 rounded-3xl border border-slate-200 shadow-sm space-y-4">
            <div class="flex justify-between items-center pb-2 border-b border-slate-100">
                <h3 class="text-sm font-bold text-[#002040] font-heading">Recent Membership Applications</h3>
                <a href="{{ route('admin.members.index') }}" class="text-xs font-bold text-[#108010] hover:text-[#002040] transition">View All</a>
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
                            <tr class="hover:bg-slate-50 transition">
                                <td class="py-3">
                                    <strong class="text-[#002040] block">{{ $app->full_name }}</strong>
                                    <span class="text-[11px] text-slate-500">{{ $app->phone }}</span>
                                </td>
                                <td class="py-3 text-slate-600">{{ $app->category->name }}</td>
                                <td class="py-3">
                                    <span class="font-bold px-2 py-0.5 rounded-full text-[10px] uppercase {{ $app->status === 'approved' ? 'bg-[#108010]/10 text-[#108010]' : 'bg-amber-100 text-amber-800' }}">
                                        {{ $app->status }}
                                    </span>
                                </td>
                                <td class="py-3 text-right">
                                    <a href="{{ route('admin.members.show', $app->id) }}" class="text-[#108010] hover:text-[#002040] font-bold text-xs transition">
                                        Review &rarr;
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-4 text-center text-slate-400 text-xs">No recent applications submitted.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Recent Audit Trail Logs -->
        <div class="lg:col-span-5 bg-white p-6 rounded-3xl border border-slate-200 shadow-sm space-y-4">
            <div class="flex justify-between items-center pb-2 border-b border-slate-100">
                <h3 class="text-sm font-bold text-[#002040] font-heading">Recent Audit Logs</h3>
                <a href="{{ route('admin.audit.index') }}" class="text-xs font-bold text-[#108010] hover:text-[#002040] transition">Audit Log &rarr;</a>
            </div>

            <div class="space-y-3">
                @forelse ($recentAuditLogs as $log)
                    <div class="p-3 bg-slate-50 rounded-2xl border border-slate-100 text-xs space-y-1">
                        <div class="flex justify-between items-center text-[10px]">
                            <span class="font-bold text-[#002040]">{{ $log->user?->name ?? 'System' }}</span>
                            <span class="text-slate-400">{{ $log->created_at->diffForHumans() }}</span>
                        </div>
                        <p class="text-slate-600">{{ $log->description }}</p>
                    </div>
                @empty
                    <p class="text-xs text-slate-400 py-3 text-center">No recent audit log entries.</p>
                @endforelse
            </div>
        </div>

    </div>
</div>
@endsection
