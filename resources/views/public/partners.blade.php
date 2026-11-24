@extends('layouts.app')

@section('title', 'Strategic Partners & Stakeholders — TEVDA')

@section('content')
<div class="relative bg-[#002040] text-white py-16 lg:py-20 overflow-hidden">
    <!-- Subtle Brand Background Gradients -->
    <div class="absolute -right-24 -top-24 w-96 h-96 bg-[#108010]/20 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute -left-24 -bottom-24 w-96 h-96 bg-[#E5B51B]/10 rounded-full blur-3xl pointer-events-none"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center sm:text-left">
        <span class="inline-flex items-center gap-1.5 text-xs font-bold uppercase tracking-widest text-[#E5B51B] bg-white/10 backdrop-blur-md px-3.5 py-1.5 rounded-full border border-[#E5B51B]/30">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
            <span>Collaborations & Ecosystem</span>
        </span>
        <h1 class="text-3xl sm:text-5xl font-black font-heading mt-3 mb-3 text-white tracking-tight">Partners & Strategic Stakeholders</h1>
        <p class="text-slate-300 text-sm sm:text-base max-w-2xl leading-relaxed">Building national cross-sector partnerships with government authorities, financial institutions, EV manufacturers, and technical research bodies.</p>
    </div>
</div>

<div class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-16">
        
        <!-- Partners Directory -->
        <div>
            <div class="text-center max-w-3xl mx-auto mb-12">
                <span class="text-xs font-extrabold uppercase tracking-widest text-[#108010] bg-[#108010]/10 px-3.5 py-1.5 rounded-xl border border-[#108010]/20">Our Network</span>
                <h2 class="text-3xl sm:text-4xl font-black text-[#002040] font-heading mt-3">Confirmed Stakeholders & Partners</h2>
                <p class="text-slate-600 text-xs sm:text-sm mt-1">Institutions collaborating with TEVDA to develop sustainable green commercial mobility.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach ($partners as $partner)
                    <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm hover:shadow-xl transition-all duration-300 flex flex-col justify-between group">
                        <div>
                            <div class="w-16 h-16 rounded-2xl bg-slate-50 flex items-center justify-center p-2 mb-4 shadow-xs border border-slate-100 overflow-hidden">
                                @if ($partner->logo_url)
                                    <img src="{{ $partner->logo_url }}" alt="{{ $partner->name }}" class="max-h-12 max-w-full object-contain group-hover:scale-105 transition-transform" onerror="this.style.display='none'; this.nextElementSibling.classList.remove('hidden');">
                                    <div class="hidden w-full h-full rounded-xl bg-[#002040] text-white font-black text-sm flex items-center justify-center">
                                        {{ strtoupper(substr($partner->name, 0, 3)) }}
                                    </div>
                                @else
                                    <div class="w-full h-full rounded-xl bg-[#002040] text-white font-black text-sm flex items-center justify-center">
                                        {{ strtoupper(substr($partner->name, 0, 3)) }}
                                    </div>
                                @endif
                            </div>

                            <span class="text-[10px] uppercase font-extrabold text-[#108010] bg-[#108010]/10 px-2 py-0.5 rounded-md inline-block mb-2">
                                {{ ucwords(str_replace('_', ' ', $partner->category)) }}
                            </span>

                            <h3 class="font-black text-base text-[#002040] font-heading mb-2">{{ $partner->name }}</h3>
                            <p class="text-xs text-slate-600 leading-relaxed mb-4">{{ $partner->description }}</p>
                        </div>

                        @if ($partner->website_url)
                            <div class="pt-3 border-t border-slate-100">
                                <a href="{{ $partner->website_url }}" target="_blank" rel="noopener noreferrer" class="text-xs font-bold text-[#108010] hover:text-[#002040] inline-flex items-center gap-1 transition">
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
        <div class="bg-[#002040] text-white rounded-3xl p-8 sm:p-12 shadow-2xl border border-slate-800 relative overflow-hidden">
            <div class="absolute -right-20 -top-20 w-80 h-80 bg-[#108010]/20 rounded-full blur-3xl pointer-events-none"></div>

            <div class="max-w-3xl mb-8 relative z-10">
                <span class="text-xs font-extrabold uppercase tracking-widest text-[#E5B51B] bg-white/10 px-3.5 py-1.5 rounded-xl border border-[#E5B51B]/30">Join the Ecosystem</span>
                <h2 class="text-3xl font-black font-heading mt-3 mb-2 text-white">Partner With TEVDA</h2>
                <p class="text-slate-300 text-xs sm:text-sm">Are you an EV assembler, battery swapping operator, financial institution, college, or development partner? Connect with our Secretariat to explore collaboration.</p>
            </div>

            @if(session('success'))
                <div class="mb-6 p-4 bg-[#108010]/20 border border-[#108010] rounded-2xl text-emerald-300 text-xs font-bold">
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('partners.enquiry.submit') }}" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-6 relative z-10">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-300 mb-1.5">Organisation Name <span class="text-rose-400">*</span></label>
                    <input type="text" name="organisation_name" required value="{{ old('organisation_name') }}" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-3 text-xs sm:text-sm text-white focus:outline-none focus:border-[#108010] focus:ring-1 focus:ring-[#108010] transition">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-300 mb-1.5">Partner Category</label>
                    <select name="category" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-3 text-xs sm:text-sm text-white focus:outline-none focus:border-[#108010] transition">
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
                    <input type="text" name="contact_person" required value="{{ old('contact_person') }}" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-3 text-xs sm:text-sm text-white focus:outline-none focus:border-[#108010] transition">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-300 mb-1.5">Email Address <span class="text-rose-400">*</span></label>
                    <input type="email" name="email" required value="{{ old('email') }}" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-3 text-xs sm:text-sm text-white focus:outline-none focus:border-[#108010] transition">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-300 mb-1.5">Phone Number <span class="text-rose-400">*</span></label>
                    <input type="text" name="phone" required value="{{ old('phone') }}" placeholder="+255..." class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-3 text-xs sm:text-sm text-white focus:outline-none focus:border-[#108010] transition">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-300 mb-1.5">Collaboration Interests</label>
                    <input type="text" name="collaboration_interests" placeholder="e.g. Battery Swapping, Driver Financing, Training" value="{{ old('collaboration_interests') }}" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-3 text-xs sm:text-sm text-white focus:outline-none focus:border-[#108010] transition">
                </div>

                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-slate-300 mb-1.5">Partnership Proposal / Message <span class="text-rose-400">*</span></label>
                    <textarea name="message" rows="4" required class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-3 text-xs sm:text-sm text-white focus:outline-none focus:border-[#108010] transition">{{ old('message') }}</textarea>
                </div>

                <div class="md:col-span-2">
                    <button type="submit" class="bg-[#108010] hover:bg-[#0c660c] text-white font-bold px-8 py-3.5 rounded-xl text-xs sm:text-sm transition-all shadow-md shadow-[#108010]/30 cursor-pointer">
                        Submit Partnership Inquiry
                    </button>
                </div>
            </form>
        </div>

    </div>
</div>
@endsection
