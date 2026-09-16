@extends('layouts.app')

@section('title', 'Strategic Partners & Stakeholders — TEVDA')

@section('content')
<div class="relative hero-pattern text-white py-16 lg:py-20 overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center sm:text-left">
        <span class="text-xs font-extrabold uppercase tracking-widest text-emerald-300 bg-emerald-950/80 px-3.5 py-1.5 rounded-full border border-emerald-800/80">Collaborations & Ecosystem</span>
        <h1 class="text-3xl sm:text-5xl font-black font-heading mt-3 mb-3 text-white">Partners & Strategic Stakeholders</h1>
        <p class="text-slate-300 text-sm sm:text-base max-w-2xl leading-relaxed">Building national cross-sector partnerships with government authorities, financial institutions, EV manufacturers, and technical research bodies.</p>
    </div>
</div>

<div class="py-16 bg-white subtle-grid-pattern">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-16">
        
        <!-- Partners Directory -->
        <div>
            <div class="text-center max-w-3xl mx-auto mb-12">
                <span class="text-xs font-extrabold uppercase tracking-widest text-emerald-700 bg-emerald-50 px-3.5 py-1.5 rounded-xl border border-emerald-200/60">Our Network</span>
                <h2 class="text-3xl sm:text-4xl font-black text-slate-900 font-heading mt-3">Confirmed Stakeholders & Partners</h2>
                <p class="text-slate-600 text-xs sm:text-sm mt-1">Institutions collaborating with TEVDA to develop sustainable green commercial mobility.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach ($partners as $partner)
                    <div class="glass-card p-6 rounded-3xl border border-slate-200/80 shadow-xs flex flex-col justify-between group">
                        <div>
                            <div class="w-16 h-16 rounded-2xl bg-white flex items-center justify-center p-2 mb-4 shadow-sm border border-slate-100 overflow-hidden">
                                @if ($partner->logo_url)
                                    <img src="{{ $partner->logo_url }}" alt="{{ $partner->name }}" class="max-h-12 max-w-full object-contain group-hover:scale-105 transition-transform" onerror="this.style.display='none'; this.nextElementSibling.classList.remove('hidden');">
                                    <div class="hidden w-full h-full rounded-xl bg-emerald-700 text-white font-black text-sm flex items-center justify-center">
                                        {{ strtoupper(substr($partner->name, 0, 3)) }}
                                    </div>
                                @else
                                    <div class="w-full h-full rounded-xl bg-emerald-700 text-white font-black text-sm flex items-center justify-center">
                                        {{ strtoupper(substr($partner->name, 0, 3)) }}
                                    </div>
                                @endif
                            </div>

                            <span class="text-[10px] uppercase font-extrabold text-emerald-800 bg-emerald-50 px-2 py-0.5 rounded-md inline-block mb-2">
                                {{ ucwords(str_replace('_', ' ', $partner->category)) }}
                            </span>

                            <h3 class="font-black text-base text-slate-900 font-heading mb-2">{{ $partner->name }}</h3>
                            <p class="text-xs text-slate-600 leading-relaxed mb-4">{{ $partner->description }}</p>
                        </div>

                        @if ($partner->website_url)
                            <div class="pt-3 border-t border-slate-100">
                                <a href="{{ $partner->website_url }}" target="_blank" rel="noopener noreferrer" class="text-xs font-bold text-emerald-700 hover:text-emerald-800 inline-flex items-center gap-1">
                                    <span>Visit Website</span>
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                </a>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Partner With TEVDA Inquiry Form -->
        <div class="bg-slate-950 text-white rounded-3xl p-8 sm:p-12 shadow-2xl border border-slate-800 relative overflow-hidden">
            <div class="absolute -right-20 -top-20 w-80 h-80 bg-emerald-500/10 rounded-full blur-3xl pointer-events-none"></div>

            <div class="max-w-3xl mb-8 relative z-10">
                <span class="text-xs font-extrabold uppercase tracking-widest text-emerald-400 bg-emerald-950 px-3.5 py-1.5 rounded-xl border border-emerald-800">Join the Ecosystem</span>
                <h2 class="text-3xl font-black font-heading mt-3 mb-2 text-white">Partner With TEVDA</h2>
                <p class="text-slate-300 text-xs sm:text-sm">Are you an EV assembler, battery swapping operator, financial institution, college, or development partner? Connect with our Secretariat to explore collaboration.</p>
            </div>

            @if(session('success'))
                <div class="mb-6 p-4 bg-emerald-950/80 border border-emerald-500/50 rounded-2xl text-emerald-300 text-xs font-bold">
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('partners.enquiry.submit') }}" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-6 relative z-10">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-300 mb-1.5">Organisation Name <span class="text-rose-400">*</span></label>
                    <input type="text" name="organisation_name" required value="{{ old('organisation_name') }}" class="w-full bg-slate-900 border border-slate-700/80 rounded-xl px-4 py-3 text-xs sm:text-sm text-white focus:outline-hidden focus:border-emerald-500 transition">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-300 mb-1.5">Partner Category</label>
                    <select name="category" class="w-full bg-slate-900 border border-slate-700/80 rounded-xl px-4 py-3 text-xs sm:text-sm text-white focus:outline-hidden focus:border-emerald-500 transition">
                        <option value="government_and_authorities">Government / Regulatory Body</option>
                        <option value="financial_institutions">Financial Institution / Green Fund</option>
                        <option value="ev_companies">EV Manufacturer / Swapping Operator</option>
                        <option value="colleges_and_research">Technical College / Academic Research</option>
                        <option value="development_organisations">Development Partner / NGO</option>
                        <option value="employers_and_logistics">Logistics Operator / Employer</option>
                        <option value="other">Other Strategic Partner</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-300 mb-1.5">Contact Person Name <span class="text-rose-400">*</span></label>
                    <input type="text" name="contact_person" required value="{{ old('contact_person') }}" class="w-full bg-slate-900 border border-slate-700/80 rounded-xl px-4 py-3 text-xs sm:text-sm text-white focus:outline-hidden focus:border-emerald-500 transition">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-300 mb-1.5">Email Address <span class="text-rose-400">*</span></label>
                    <input type="email" name="email" required value="{{ old('email') }}" class="w-full bg-slate-900 border border-slate-700/80 rounded-xl px-4 py-3 text-xs sm:text-sm text-white focus:outline-hidden focus:border-emerald-500 transition">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-300 mb-1.5">Phone Number <span class="text-rose-400">*</span></label>
                    <input type="text" name="phone" required value="{{ old('phone') }}" placeholder="+255..." class="w-full bg-slate-900 border border-slate-700/80 rounded-xl px-4 py-3 text-xs sm:text-sm text-white focus:outline-hidden focus:border-emerald-500 transition">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-300 mb-1.5">Collaboration Interests</label>
                    <input type="text" name="collaboration_interests" placeholder="e.g. Battery Swapping, Driver Financing, Training" value="{{ old('collaboration_interests') }}" class="w-full bg-slate-900 border border-slate-700/80 rounded-xl px-4 py-3 text-xs sm:text-sm text-white focus:outline-hidden focus:border-emerald-500 transition">
                </div>

                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-slate-300 mb-1.5">Partnership Proposal / Message <span class="text-rose-400">*</span></label>
                    <textarea name="message" rows="4" required class="w-full bg-slate-900 border border-slate-700/80 rounded-xl px-4 py-3 text-xs sm:text-sm text-white focus:outline-hidden focus:border-emerald-500 transition">{{ old('message') }}</textarea>
                </div>

                <div class="md:col-span-2">
                    <button type="submit" class="bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-black px-8 py-4 rounded-2xl text-xs sm:text-sm transition shadow-xl hover:shadow-emerald-500/30">
                        Submit Partnership Inquiry
                    </button>
                </div>
            </form>
        </div>

    </div>
</div>
@endsection
