@extends('layouts.admin')

@section('title', 'Member Profile: ' . $member->full_name)

@section('content')
<div class="space-y-6">
    <!-- Top Back & Actions Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.members.index') }}" class="w-9 h-9 rounded-xl bg-white border border-slate-200 flex items-center justify-center text-slate-600 hover:text-slate-900 transition">
                <i class="fa-solid fa-arrow-left text-sm"></i>
            </a>
            <div>
                <div class="flex items-center gap-2">
                    <h1 class="text-2xl font-black text-slate-900">{{ $member->full_name }}</h1>
                    @if($member->is_founding_member)
                        <span class="px-2 py-0.5 rounded-full text-xs font-bold bg-amber-100 text-amber-800 border border-amber-200">Founding Member</span>
                    @endif
                </div>
                <p class="text-xs text-slate-500 flex items-center gap-2 mt-0.5">
                    <span>Member No: <strong class="text-slate-800 font-mono">{{ $member->membership_number ?? 'Pending Approval' }}</strong></span>
                    <span>•</span>
                    <span>Applied on {{ $member->created_at->format('d M Y, H:i') }}</span>
                </p>
            </div>
        </div>

        <!-- Approval / Decision Action Buttons -->
        <div class="flex flex-wrap items-center gap-2">
            @if($member->status !== 'approved')
                <button type="button" onclick="document.getElementById('approveModal').classList.remove('hidden')" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold transition flex items-center gap-1.5 shadow-sm shadow-emerald-600/20">
                    <i class="fa-solid fa-circle-check"></i> Approve Application
                </button>
            @endif

            @if($member->status !== 'documents_incomplete')
                <button type="button" onclick="document.getElementById('incompleteModal').classList.remove('hidden')" class="px-3 py-2 bg-amber-500 hover:bg-amber-600 text-white rounded-xl text-xs font-bold transition flex items-center gap-1.5 shadow-sm">
                    <i class="fa-solid fa-file-circle-question"></i> Request Documents
                </button>
            @endif

            @if($member->status !== 'rejected')
                <button type="button" onclick="document.getElementById('rejectModal').classList.remove('hidden')" class="px-3 py-2 bg-rose-600 hover:bg-rose-700 text-white rounded-xl text-xs font-bold transition flex items-center gap-1.5 shadow-sm">
                    <i class="fa-solid fa-circle-xmark"></i> Reject
                </button>
            @endif

            @if($member->status === 'approved')
                @if($member->card)
                    <a href="{{ route('admin.cards.download', $member->card->id) }}" class="px-3 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold transition flex items-center gap-1.5 shadow-sm shadow-emerald-600/20">
                        <i class="fa-solid fa-id-card"></i> Download ID Card
                    </a>
                @else
                    <form action="{{ route('admin.members.card.generate', $member->id) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="px-3 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold transition flex items-center gap-1.5 shadow-sm">
                            <i class="fa-solid fa-plus"></i> Create ID Card
                        </button>
                    </form>
                @endif
                <a href="{{ route('verify.membership', $member->membership_number) }}" target="_blank" class="px-3 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-bold transition flex items-center gap-1.5">
                    <i class="fa-solid fa-arrow-up-right-from-square"></i> Public Verification
                </a>
            @endif
        </div>
    </div>

    <!-- Overview Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Column 1: Member Card & Status -->
        <div class="space-y-6">
            <!-- Summary Profile Card -->
            <div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-sm text-center">
                <div class="w-24 h-24 rounded-2xl bg-emerald-100 text-emerald-800 text-3xl font-black flex items-center justify-center mx-auto mb-4 border-2 border-emerald-200 shadow-inner uppercase overflow-hidden">
                    @if($member->passport_photo_path)
                        <img src="{{ asset('storage/' . $member->passport_photo_path) }}" alt="{{ $member->full_name }}" class="w-full h-full object-cover">
                    @else
                        {{ substr($member->full_name, 0, 2) }}
                    @endif
                </div>

                <h2 class="text-lg font-black text-slate-900">{{ $member->full_name }}</h2>
                <div class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold mt-2 bg-emerald-50 text-emerald-700 border border-emerald-200">
                    {{ $member->category->name ?? 'General Member' }}
                </div>

                @php
                    $statusStyles = [
                        'approved' => 'bg-emerald-500 text-white',
                        'submitted' => 'bg-blue-500 text-white',
                        'under_review' => 'bg-amber-500 text-white',
                        'documents_incomplete' => 'bg-orange-500 text-white',
                        'rejected' => 'bg-rose-500 text-white',
                    ];
                @endphp
                <div class="mt-4 pt-4 border-t border-slate-100 flex items-center justify-between text-xs">
                    <span class="text-slate-400">Membership Status</span>
                    <span class="px-2.5 py-0.5 rounded-full font-bold {{ $statusStyles[$member->status] ?? 'bg-slate-500 text-white' }}">
                        {{ ucwords(str_replace('_', ' ', $member->status)) }}
                    </span>
                </div>

                <div class="mt-2 flex items-center justify-between text-xs">
                    <span class="text-slate-400">Validity Expiry</span>
                    <span class="font-bold text-slate-700">{{ $member->expiry_date ? $member->expiry_date->format('d M Y') : 'Pending' }}</span>
                </div>
            </div>

            <!-- Digital ID Card Widget -->
            <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm space-y-3">
                <div class="flex items-center justify-between">
                    <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider flex items-center gap-1.5">
                        <i class="fa-solid fa-address-card text-emerald-600"></i> Official Member ID Card
                    </h3>
                    @if($member->card)
                        <span class="text-[10px] font-bold px-2 py-0.5 rounded-full {{ $member->card->is_active ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-100 text-slate-600' }}">
                            {{ $member->card->is_active ? 'ACTIVE' : 'INACTIVE' }}
                        </span>
                    @endif
                </div>

                @if($member->card)
                    <div class="p-3.5 bg-slate-900 text-white rounded-xl space-y-2 border border-emerald-500/30 shadow-xs">
                        <div class="flex justify-between items-center text-[10px]">
                            <span class="text-emerald-400 font-bold uppercase tracking-wider">TEVDA CR80</span>
                            <span class="font-mono text-slate-300">{{ $member->card->card_number }}</span>
                        </div>
                        <div class="text-xs font-bold text-white truncate">{{ $member->full_name }}</div>
                        <div class="text-[11px] font-mono text-emerald-400 font-bold">{{ $member->membership_number }}</div>
                        <div class="text-[10px] text-slate-400 flex justify-between pt-1 border-t border-slate-800">
                            <span>Issued: {{ $member->card->issue_date->format('d/m/Y') }}</span>
                            <span>Exp: {{ $member->card->expiry_date ? $member->card->expiry_date->format('d/m/Y') : 'Lifetime' }}</span>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-2 pt-1">
                        <a href="{{ route('admin.cards.download', $member->card->id) }}" class="py-2 px-3 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl transition flex items-center justify-center gap-1">
                            <i class="fa-solid fa-download"></i> PDF
                        </a>
                        <a href="{{ route('admin.cards.show', $member->card->id) }}" class="py-2 px-3 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-xl transition flex items-center justify-center gap-1">
                            <i class="fa-solid fa-eye"></i> View 3D
                        </a>
                    </div>
                @else
                    <div class="p-4 bg-slate-50 rounded-xl border border-slate-200 text-center space-y-2">
                        <p class="text-xs text-slate-500">No active ID card generated for this member yet.</p>
                        <form action="{{ route('admin.members.card.generate', $member->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl shadow-xs transition flex items-center justify-center gap-1.5">
                                <i class="fa-solid fa-plus"></i> Generate Member ID Card
                            </button>
                        </form>
                    </div>
                @endif
            </div>

            <!-- Reviewer Notes Log -->
            @if($member->reviewer_notes || $member->rejection_reason)
                <div class="bg-amber-50/70 border border-amber-200/80 rounded-2xl p-5 text-xs text-amber-900 space-y-2">
                    <div class="font-bold flex items-center gap-1.5">
                        <i class="fa-solid fa-circle-exclamation text-amber-600"></i> Reviewer Log
                    </div>
                    @if($member->reviewer_notes)
                        <p><strong class="font-semibold">Notes:</strong> {{ $member->reviewer_notes }}</p>
                    @endif
                    @if($member->rejection_reason)
                        <p><strong class="font-semibold text-rose-700">Rejection Reason:</strong> {{ $member->rejection_reason }}</p>
                    @endif
                </div>
            @endif

            <!-- Next of Kin -->
            <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm space-y-3">
                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider">Next of Kin / Emergency</h3>
                <div class="space-y-2 text-xs">
                    <div class="flex justify-between py-1 border-b border-slate-100">
                        <span class="text-slate-500">Contact Name</span>
                        <span class="font-bold text-slate-800">{{ $member->next_of_kin_name ?? 'N/A' }}</span>
                    </div>
                    <div class="flex justify-between py-1 border-b border-slate-100">
                        <span class="text-slate-500">Relationship</span>
                        <span class="font-bold text-slate-800">{{ $member->next_of_kin_relationship ?? 'N/A' }}</span>
                    </div>
                    <div class="flex justify-between py-1">
                        <span class="text-slate-500">Phone Number</span>
                        <span class="font-bold text-slate-800">{{ $member->next_of_kin_phone ?? 'N/A' }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Column 2 & 3: Detailed Credentials, Documents & EV Info -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Personal & Location Info -->
            <div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-sm">
                <h3 class="text-sm font-black text-slate-900 mb-4 flex items-center gap-2">
                    <i class="fa-solid fa-user-gear text-emerald-600"></i> Personal & Geographic Details
                </h3>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                    <div class="p-3 bg-slate-50 rounded-xl">
                        <span class="text-slate-400 block mb-1">National ID (NIDA)</span>
                        <span class="font-bold font-mono text-slate-800">{{ $member->nida_number ?? 'Not Provided' }}</span>
                    </div>
                    <div class="p-3 bg-slate-50 rounded-xl">
                        <span class="text-slate-400 block mb-1">Gender & Birth Date</span>
                        <span class="font-bold text-slate-800">{{ ucfirst($member->gender ?? 'N/A') }} • {{ $member->date_of_birth ? $member->date_of_birth->format('d M Y') : 'N/A' }}</span>
                    </div>
                    <div class="p-3 bg-slate-50 rounded-xl">
                        <span class="text-slate-400 block mb-1">Phone & Email</span>
                        <span class="font-bold text-slate-800">{{ $member->phone }} • {{ $member->email ?? 'No email' }}</span>
                    </div>
                    <div class="p-3 bg-slate-50 rounded-xl">
                        <span class="text-slate-400 block mb-1">Administrative Location</span>
                        <span class="font-bold text-slate-800">{{ $member->region->name ?? 'Tanzania' }}, {{ $member->district_name ?? $member->district?->name ?? 'District N/A' }}</span>
                    </div>
                </div>
            </div>

            <!-- Driving & Vehicle Credentials -->
            <div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-sm">
                <h3 class="text-sm font-black text-slate-900 mb-4 flex items-center gap-2">
                    <i class="fa-solid fa-id-card-clip text-emerald-600"></i> Driving Licence & EV Details
                </h3>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-xs mb-4">
                    <div class="p-3 bg-slate-50 rounded-xl">
                        <span class="text-slate-400 block mb-1">Driving Licence Number</span>
                        <span class="font-bold font-mono text-slate-800">{{ $member->driving_licence_number ?? 'N/A' }}</span>
                    </div>
                    <div class="p-3 bg-slate-50 rounded-xl">
                        <span class="text-slate-400 block mb-1">Licence Classes</span>
                        <span class="font-bold text-slate-800">{{ $member->licence_classes ? implode(', ', (array)$member->licence_classes) : 'N/A' }}</span>
                    </div>
                    <div class="p-3 bg-slate-50 rounded-xl">
                        <span class="text-slate-400 block mb-1">Licence Expiry</span>
                        <span class="font-bold text-slate-800">{{ $member->licence_expiry_date ? $member->licence_expiry_date->format('d M Y') : 'N/A' }}</span>
                    </div>
                </div>

                @if($member->primaryVehicle)
                    <div class="p-4 bg-emerald-50/50 border border-emerald-100 rounded-xl text-xs space-y-2">
                        <div class="font-bold text-emerald-900 flex items-center justify-between">
                            <span><i class="fa-solid fa-bolt text-emerald-600 mr-1.5"></i> Primary Electric Vehicle</span>
                            <span class="font-mono text-slate-700 bg-white px-2 py-0.5 rounded border border-emerald-200">{{ $member->primaryVehicle->registration_number }}</span>
                        </div>
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-2 text-slate-600 pt-1">
                            <div><span class="text-slate-400 block">Type:</span> {{ ucfirst($member->primaryVehicle->vehicle_type) }}</div>
                            <div><span class="text-slate-400 block">Make/Model:</span> {{ $member->primaryVehicle->make ?? 'N/A' }} {{ $member->primaryVehicle->model ?? '' }}</div>
                            <div><span class="text-slate-400 block">Ownership:</span> {{ ucfirst($member->primaryVehicle->ownership_type ?? 'N/A') }}</div>
                            <div><span class="text-slate-400 block">Battery / Tech:</span> {{ $member->primaryVehicle->battery_capacity_kwh ? $member->primaryVehicle->battery_capacity_kwh . ' kWh' : 'N/A' }}</div>
                        </div>
                    </div>
                @else
                    <div class="p-3 bg-slate-50 rounded-xl text-xs text-slate-500 italic">
                        No vehicle registered directly yet.
                    </div>
                @endif
            </div>

            <!-- Document Verification Workflow -->
            <div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-sm">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-sm font-black text-slate-900 flex items-center gap-2">
                        <i class="fa-solid fa-file-shield text-emerald-600"></i> Verification of Submitted Documents
                    </h3>
                    <span class="text-xs text-slate-400">{{ $member->documents->count() }} Attached</span>
                </div>

                @if($member->documents->isEmpty())
                    <div class="p-6 text-center text-slate-400 text-xs bg-slate-50 rounded-xl">
                        No formal uploaded documents recorded for this member.
                    </div>
                @else
                    <div class="space-y-4">
                        @foreach($member->documents as $doc)
                            <div class="p-4 border border-slate-200 rounded-xl bg-slate-50/50 space-y-3">
                                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                                    <div>
                                        <div class="font-bold text-xs text-slate-900 flex items-center gap-2">
                                            <i class="fa-solid fa-file-lines text-slate-500"></i>
                                            {{ ucwords(str_replace('_', ' ', $doc->document_type)) }}
                                            <span class="text-slate-400 font-normal">({{ $doc->file_name }})</span>
                                        </div>
                                        <div class="text-[11px] text-slate-500 mt-0.5">
                                            Uploaded: {{ $doc->created_at->format('d M Y, H:i') }}
                                            @if($doc->verifier)
                                                • Verified by {{ $doc->verifier->name }} on {{ $doc->verified_at?->format('d M Y') }}
                                            @endif
                                        </div>
                                    </div>

                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('documents.membership.download', $doc->id) }}" class="px-3 py-1.5 bg-white border border-slate-200 hover:bg-slate-100 text-slate-700 rounded-lg text-xs font-bold transition flex items-center gap-1">
                                            <i class="fa-solid fa-download text-xs"></i> Download
                                        </a>

                                        @php
                                            $docBadge = [
                                                'verified' => 'bg-emerald-100 text-emerald-800 border-emerald-200',
                                                'pending' => 'bg-amber-100 text-amber-800 border-amber-200',
                                                'rejected' => 'bg-rose-100 text-rose-800 border-rose-200',
                                            ];
                                        @endphp
                                        <span class="px-2.5 py-1 rounded-lg text-xs font-bold border {{ $docBadge[$doc->verification_status] ?? 'bg-slate-100 text-slate-700 border-slate-200' }}">
                                            {{ ucfirst($doc->verification_status) }}
                                        </span>
                                    </div>
                                </div>

                                <!-- Inline verification form -->
                                <form method="POST" action="{{ route('admin.members.documents.verify', $doc->id) }}" class="pt-2 border-t border-slate-200 flex flex-col sm:flex-row items-center gap-2">
                                    @csrf
                                    <input type="text" name="notes" value="{{ $doc->notes }}" placeholder="Verification notes (e.g. NIDA verified against database)..." class="flex-1 py-1.5 px-3 bg-white border border-slate-200 rounded-lg text-xs focus:ring-1 focus:ring-emerald-500 outline-none">
                                    <div class="flex items-center gap-1.5 w-full sm:w-auto">
                                        <button type="submit" name="status" value="verified" class="flex-1 sm:flex-initial px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-xs font-bold transition">
                                            <i class="fa-solid fa-check mr-1"></i> Verify
                                        </button>
                                        <button type="submit" name="status" value="rejected" class="flex-1 sm:flex-initial px-3 py-1.5 bg-rose-600 hover:bg-rose-700 text-white rounded-lg text-xs font-bold transition">
                                            <i class="fa-solid fa-xmark mr-1"></i> Reject
                                        </button>
                                    </div>
                                </form>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Invoices & Certificates Summary -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <!-- Certificates -->
                <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm space-y-3">
                    <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider flex items-center justify-between">
                        <span>Issued Certificates</span>
                        <span class="font-bold text-emerald-600">{{ $member->certificates->count() }}</span>
                    </h3>
                    @forelse($member->certificates as $cert)
                        <div class="p-3 bg-slate-50 rounded-xl border border-slate-100 text-xs flex items-center justify-between">
                            <div>
                                <div class="font-bold text-slate-800">{{ $cert->title }}</div>
                                <div class="text-[11px] text-slate-500 font-mono">{{ $cert->certificate_number }}</div>
                            </div>
                            <a href="{{ route('admin.certificates.show', $cert->id) }}" class="text-emerald-600 hover:text-emerald-700 font-bold">
                                View <i class="fa-solid fa-arrow-right ml-1"></i>
                            </a>
                        </div>
                    @empty
                        <p class="text-xs text-slate-400 italic">No certificates issued yet.</p>
                    @endforelse
                </div>

                <!-- Financial History -->
                <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm space-y-3">
                    <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider flex items-center justify-between">
                        <span>Invoices & Payments</span>
                        <span class="font-bold text-emerald-600">{{ $member->invoices->count() }}</span>
                    </h3>
                    @forelse($member->invoices as $inv)
                        <div class="p-3 bg-slate-50 rounded-xl border border-slate-100 text-xs flex items-center justify-between">
                            <div>
                                <div class="font-bold text-slate-800">{{ $inv->description ?? 'Invoice' }}</div>
                                <div class="text-[11px] text-slate-500 font-mono">{{ $inv->invoice_number }} • {{ number_format($inv->amount) }} {{ $inv->currency }}</div>
                            </div>
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold {{ $inv->status === 'paid' ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">
                                {{ strtoupper($inv->status) }}
                            </span>
                        </div>
                    @empty
                        <p class="text-xs text-slate-400 italic">No invoices recorded.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal: Approve Application -->
<div id="approveModal" class="hidden fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl space-y-4 border border-slate-200">
        <div class="flex items-center justify-between">
            <h3 class="text-base font-black text-slate-900 flex items-center gap-2">
                <i class="fa-solid fa-circle-check text-emerald-600"></i> Approve Member
            </h3>
            <button type="button" onclick="document.getElementById('approveModal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
        <p class="text-xs text-slate-500">
            Approving this member will generate a formatted <strong>TEVDA Membership Number</strong>, activate their <strong>Digital ID Card</strong>, and issue an official <strong>Certificate of Membership</strong> with public QR verification.
        </p>
        <form method="POST" action="{{ route('admin.members.approve', $member->id) }}" class="space-y-3">
            @csrf
            <div>
                <label class="block text-xs font-bold text-slate-600 mb-1">Approval Notes (Optional)</label>
                <textarea name="notes" rows="3" class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl text-xs outline-none focus:ring-2 focus:ring-emerald-500" placeholder="Verification confirmed by Executive Committee..."></textarea>
            </div>
            <div class="flex items-center justify-end gap-2 pt-2">
                <button type="button" onclick="document.getElementById('approveModal').classList.add('hidden')" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-bold">
                    Cancel
                </button>
                <button type="submit" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold shadow-md shadow-emerald-600/20">
                    Confirm Approval
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal: Request Documents -->
<div id="incompleteModal" class="hidden fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl space-y-4 border border-slate-200">
        <div class="flex items-center justify-between">
            <h3 class="text-base font-black text-slate-900 flex items-center gap-2">
                <i class="fa-solid fa-file-circle-question text-amber-500"></i> Request Additional Documents
            </h3>
            <button type="button" onclick="document.getElementById('incompleteModal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
        <form method="POST" action="{{ route('admin.members.mark_incomplete', $member->id) }}" class="space-y-3">
            @csrf
            <div>
                <label class="block text-xs font-bold text-slate-600 mb-1">Required Information / Action</label>
                <textarea name="notes" rows="4" required class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl text-xs outline-none focus:ring-2 focus:ring-amber-500" placeholder="Please upload a clear copy of your valid Driving Licence (Class C/E)..."></textarea>
            </div>
            <div class="flex items-center justify-end gap-2 pt-2">
                <button type="button" onclick="document.getElementById('incompleteModal').classList.add('hidden')" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-bold">
                    Cancel
                </button>
                <button type="submit" class="px-4 py-2 bg-amber-500 hover:bg-amber-600 text-white rounded-xl text-xs font-bold">
                    Send Notice
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal: Reject Application -->
<div id="rejectModal" class="hidden fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl space-y-4 border border-slate-200">
        <div class="flex items-center justify-between">
            <h3 class="text-base font-black text-slate-900 flex items-center gap-2">
                <i class="fa-solid fa-circle-xmark text-rose-600"></i> Reject Application
            </h3>
            <button type="button" onclick="document.getElementById('rejectModal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
        <form method="POST" action="{{ route('admin.members.reject', $member->id) }}" class="space-y-3">
            @csrf
            <div>
                <label class="block text-xs font-bold text-slate-600 mb-1">Reason for Rejection</label>
                <textarea name="reason" rows="4" required class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl text-xs outline-none focus:ring-2 focus:ring-rose-500" placeholder="The driving licence provided has expired and could not be validated..."></textarea>
            </div>
            <div class="flex items-center justify-end gap-2 pt-2">
                <button type="button" onclick="document.getElementById('rejectModal').classList.add('hidden')" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-bold">
                    Cancel
                </button>
                <button type="submit" class="px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white rounded-xl text-xs font-bold">
                    Confirm Rejection
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
