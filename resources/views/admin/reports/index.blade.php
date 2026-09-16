@extends('layouts.admin')

@section('title', 'System Analytics & Comprehensive Reports')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-900">Platform Analytics & Intelligence Reports</h1>
            <p class="text-sm text-slate-500">Holistic performance indicators across membership growth, financial sustainability, training, and strategic deployments.</p>
        </div>
        <div>
            <a href="{{ route('admin.audit.index') }}" class="px-4 py-2 bg-slate-900 hover:bg-slate-800 text-white rounded-xl text-xs font-bold transition flex items-center gap-1.5 shadow-sm">
                <i class="fa-solid fa-clock-rotate-left"></i> View Audit Trail
            </a>
        </div>
    </div>

    <!-- Metric Pillar Grids -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Membership Card -->
        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm space-y-3">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Membership</span>
                <span class="p-2 bg-emerald-50 text-emerald-600 rounded-xl"><i class="fa-solid fa-users"></i></span>
            </div>
            <div class="text-3xl font-black text-slate-900">{{ $membershipStats['total'] }}</div>
            <div class="space-y-1 text-xs text-slate-500 pt-2 border-t border-slate-100">
                <div class="flex justify-between">
                    <span>Approved / Active:</span>
                    <strong class="text-emerald-600">{{ $membershipStats['approved'] }}</strong>
                </div>
                <div class="flex justify-between">
                    <span>Pending Review:</span>
                    <strong class="text-amber-600">{{ $membershipStats['pending'] }}</strong>
                </div>
                <div class="flex justify-between">
                    <span>Rejected:</span>
                    <strong class="text-rose-600">{{ $membershipStats['rejected'] }}</strong>
                </div>
            </div>
        </div>

        <!-- Financial Performance Card -->
        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm space-y-3">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Finance & Dues</span>
                <span class="p-2 bg-blue-50 text-blue-600 rounded-xl"><i class="fa-solid fa-money-bill-wave"></i></span>
            </div>
            <div class="text-2xl font-black text-slate-900">{{ number_format($financialStats['total_revenue']) }} <span class="text-xs font-bold text-slate-500">TZS</span></div>
            <div class="space-y-1 text-xs text-slate-500 pt-2 border-t border-slate-100">
                <div class="flex justify-between">
                    <span>Paid Transactions:</span>
                    <strong class="text-slate-800">{{ $financialStats['completed_count'] }}</strong>
                </div>
                <div class="flex justify-between">
                    <span>Pending Float:</span>
                    <strong class="text-amber-600">{{ number_format($financialStats['pending_amount']) }} TZS</strong>
                </div>
            </div>
        </div>

        <!-- Training & Certification Card -->
        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm space-y-3">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Training & Safety</span>
                <span class="p-2 bg-purple-50 text-purple-600 rounded-xl"><i class="fa-solid fa-graduation-cap"></i></span>
            </div>
            <div class="text-3xl font-black text-slate-900">{{ $trainingStats['enrolments'] }}</div>
            <div class="space-y-1 text-xs text-slate-500 pt-2 border-t border-slate-100">
                <div class="flex justify-between">
                    <span>Certified Drivers:</span>
                    <strong class="text-purple-600">{{ $trainingStats['certificates_issued'] }}</strong>
                </div>
                <div class="flex justify-between">
                    <span>Certification Rate:</span>
                    <strong class="text-slate-800">
                        {{ $trainingStats['enrolments'] > 0 ? round(($trainingStats['certificates_issued'] / $trainingStats['enrolments']) * 100) : 0 }}%
                    </strong>
                </div>
            </div>
        </div>

        <!-- Opportunities & Projects Card -->
        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm space-y-3">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Deployments</span>
                <span class="p-2 bg-amber-50 text-amber-600 rounded-xl"><i class="fa-solid fa-bolt"></i></span>
            </div>
            <div class="text-3xl font-black text-slate-900">{{ $projectStats['applications'] + $opportunityStats['applications'] }}</div>
            <div class="space-y-1 text-xs text-slate-500 pt-2 border-t border-slate-100">
                <div class="flex justify-between">
                    <span>EV Project Applications:</span>
                    <strong class="text-slate-800">{{ $projectStats['applications'] }}</strong>
                </div>
                <div class="flex justify-between">
                    <span>Fleet Job Applications:</span>
                    <strong class="text-slate-800">{{ $opportunityStats['applications'] }}</strong>
                </div>
            </div>
        </div>
    </div>

    <!-- Strategic Alignment & Governance Overview -->
    <div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-sm space-y-4">
        <h2 class="text-base font-black text-slate-900 flex items-center gap-2">
            <i class="fa-solid fa-chart-pie text-emerald-600"></i> Association Pillars Alignment
        </h2>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-xs">
            <div class="p-4 bg-slate-50 rounded-xl border border-slate-100 space-y-2">
                <div class="font-bold text-slate-800 flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span> Driver Welfare & Empowerment
                </div>
                <p class="text-slate-500">Standardizing driver rights, advocacy, verified identities, and social security inclusion across Tanzania.</p>
            </div>

            <div class="p-4 bg-slate-50 rounded-xl border border-slate-100 space-y-2">
                <div class="font-bold text-slate-800 flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-blue-500"></span> Technical Competency & Safety
                </div>
                <p class="text-slate-500">Delivering certified curriculum covering high-voltage battery safety, smart charging, and proactive maintenance.</p>
            </div>

            <div class="p-4 bg-slate-50 rounded-xl border border-slate-100 space-y-2">
                <div class="font-bold text-slate-800 flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-amber-500"></span> E-Mobility Transition
                </div>
                <p class="text-slate-500">Accelerating electric vehicle adoption in partnership with regulators, OEM manufacturers, and green financing institutions.</p>
            </div>
        </div>
    </div>
</div>
@endsection
