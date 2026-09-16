@extends('layouts.app')

@section('title', 'Contact Us — TEVDA Secretariat')

@section('content')
<div class="relative hero-pattern text-white py-16 lg:py-20 overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center sm:text-left">
        <span class="text-xs font-extrabold uppercase tracking-widest text-emerald-300 bg-emerald-950/80 px-3.5 py-1.5 rounded-full border border-emerald-800/80">Get In Touch</span>
        <h1 class="text-3xl sm:text-5xl font-black font-heading mt-3 mb-3 text-white">Contact the Secretariat</h1>
        <p class="text-slate-300 text-sm sm:text-base max-w-2xl leading-relaxed">Connect with our administrative officers regarding membership inquiries, training sessions, partnerships, and general association assistance.</p>
    </div>
</div>

<div class="py-16 bg-white subtle-grid-pattern">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12">
            
            <!-- Left Contact Info -->
            <div class="lg:col-span-5 space-y-8">
                <div>
                    <span class="text-xs font-extrabold uppercase tracking-widest text-emerald-700 bg-emerald-50 px-3.5 py-1.5 rounded-xl border border-emerald-200/60">Headquarters</span>
                    <h2 class="text-2xl sm:text-3xl font-black text-slate-900 font-heading mt-3 mb-2">Official Head Office</h2>
                    <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                        The TEVDA Secretariat serves members, driver delegates, partners, and the public Monday through Friday.
                    </p>
                </div>

                <div class="space-y-4 text-xs sm:text-sm text-slate-700">
                    <div class="glass-card p-5 rounded-2xl border border-slate-200/80 flex items-start gap-4">
                        <div class="w-11 h-11 rounded-2xl bg-emerald-100 text-emerald-700 flex items-center justify-center shrink-0 shadow-xs">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        </div>
                        <div>
                            <strong class="text-slate-900 block font-bold text-sm">Physical Address:</strong>
                            <p class="text-xs text-slate-600 mt-0.5">Sinza Mori, P.O. Box 40015, Dar es Salaam, Tanzania</p>
                        </div>
                    </div>

                    <div class="glass-card p-5 rounded-2xl border border-slate-200/80 flex items-start gap-4">
                        <div class="w-11 h-11 rounded-2xl bg-cyan-100 text-cyan-700 flex items-center justify-center shrink-0 shadow-xs">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                        </div>
                        <div>
                            <strong class="text-slate-900 block font-bold text-sm">Telephone Helpline:</strong>
                            <a href="tel:+255757700401" class="text-xs text-emerald-700 font-bold hover:underline mt-0.5 block">+255 757 700 401</a>
                        </div>
                    </div>

                    <div class="glass-card p-5 rounded-2xl border border-slate-200/80 flex items-start gap-4">
                        <div class="w-11 h-11 rounded-2xl bg-indigo-100 text-indigo-700 flex items-center justify-center shrink-0 shadow-xs">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        </div>
                        <div>
                            <strong class="text-slate-900 block font-bold text-sm">Official Email:</strong>
                            <a href="mailto:info@tevda.or.tz" class="text-xs text-emerald-700 font-bold hover:underline mt-0.5 block">info@tevda.or.tz</a>
                        </div>
                    </div>

                    <div class="glass-card p-5 rounded-2xl border border-slate-200/80 flex items-start gap-4">
                        <div class="w-11 h-11 rounded-2xl bg-amber-100 text-amber-700 flex items-center justify-center shrink-0 shadow-xs">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <div>
                            <strong class="text-slate-900 block font-bold text-sm">Working Hours:</strong>
                            <p class="text-xs text-slate-600 mt-0.5">Monday – Friday: 8:00 a.m. – 5:00 p.m. EAT</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Contact Form -->
            <div class="lg:col-span-7">
                <div class="glass-card p-8 sm:p-10 rounded-3xl border border-slate-200/80 shadow-xl">
                    <h2 class="text-2xl font-black text-slate-900 font-heading mb-2">Send an Inquiry to Secretariat</h2>
                    <p class="text-xs text-slate-500 mb-6">Complete the form below and our administrative team will respond promptly.</p>

                    @if(session('success'))
                        <div class="mb-6 p-4 bg-emerald-50 border border-emerald-300 rounded-2xl text-emerald-800 text-xs font-bold flex items-center gap-2">
                            <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            <span>{{ session('success') }}</span>
                        </div>
                    @endif

                    <form action="{{ route('contact.submit') }}" method="POST" class="space-y-4">
                        @csrf

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1.5">Full Name <span class="text-rose-500">*</span></label>
                                <input type="text" name="full_name" required value="{{ old('full_name') }}" class="w-full bg-slate-50 border border-slate-300 rounded-xl px-4 py-3 text-xs sm:text-sm focus:outline-hidden focus:border-emerald-500 focus:bg-white transition">
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1.5">Telephone Number <span class="text-rose-500">*</span></label>
                                <input type="text" name="phone" required value="{{ old('phone') }}" placeholder="+255..." class="w-full bg-slate-50 border border-slate-300 rounded-xl px-4 py-3 text-xs sm:text-sm focus:outline-hidden focus:border-emerald-500 focus:bg-white transition">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1.5">Email Address <span class="text-rose-500">*</span></label>
                                <input type="email" name="email" required value="{{ old('email') }}" class="w-full bg-slate-50 border border-slate-300 rounded-xl px-4 py-3 text-xs sm:text-sm focus:outline-hidden focus:border-emerald-500 focus:bg-white transition">
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1.5">Reason for Contact <span class="text-rose-500">*</span></label>
                                <select name="reason" required class="w-full bg-slate-50 border border-slate-300 rounded-xl px-4 py-3 text-xs sm:text-sm focus:outline-hidden focus:border-emerald-500 focus:bg-white transition">
                                    <option value="membership">Membership Registration & Verification</option>
                                    <option value="training">Driver Training Programmes</option>
                                    <option value="opportunities">Grants & Financing Opportunities</option>
                                    <option value="partnerships">Strategic Partnerships & CSR</option>
                                    <option value="projects">EV Projects & Deployments</option>
                                    <option value="branches">Zonal / Regional Branch Networks</option>
                                    <option value="general_enquiry" selected>General Information Inquiry</option>
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1.5">Region</label>
                                <input type="text" name="region" placeholder="e.g. Dar es Salaam, Arusha" value="{{ old('region') }}" class="w-full bg-slate-50 border border-slate-300 rounded-xl px-4 py-3 text-xs sm:text-sm focus:outline-hidden focus:border-emerald-500 focus:bg-white transition">
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1.5">District</label>
                                <input type="text" name="district" placeholder="e.g. Kinondoni, Ilala" value="{{ old('district') }}" class="w-full bg-slate-50 border border-slate-300 rounded-xl px-4 py-3 text-xs sm:text-sm focus:outline-hidden focus:border-emerald-500 focus:bg-white transition">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Your Message <span class="text-rose-500">*</span></label>
                            <textarea name="message" rows="5" required class="w-full bg-slate-50 border border-slate-300 rounded-xl px-4 py-3 text-xs sm:text-sm focus:outline-hidden focus:border-emerald-500 focus:bg-white transition">{{ old('message') }}</textarea>
                        </div>

                        <div class="flex items-start gap-2 pt-2">
                            <input type="checkbox" name="consent" id="consent" required class="mt-1 rounded text-emerald-600 focus:ring-emerald-500">
                            <label for="consent" class="text-xs text-slate-600">I consent to TEVDA Secretariat processing my submitted contact information to respond to this enquiry.</label>
                        </div>

                        <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-500 text-white font-black py-4 px-6 rounded-2xl text-xs sm:text-sm transition shadow-lg shadow-emerald-600/20">
                            Submit Message to TEVDA
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
