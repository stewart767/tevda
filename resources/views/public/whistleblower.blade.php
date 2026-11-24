@extends('layouts.app')

@section('title', 'Confidential Whistleblower & Complaints — TEVDA')

@section('content')
<div class="relative bg-[#002040] text-white py-16 lg:py-20 overflow-hidden">
    <div class="absolute -right-24 -top-24 w-96 h-96 bg-[#108010]/20 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute -left-24 -bottom-24 w-96 h-96 bg-[#E5B51B]/10 rounded-full blur-3xl pointer-events-none"></div>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10">
        <span class="inline-flex items-center gap-1.5 text-xs font-bold uppercase tracking-widest text-[#E5B51B] bg-white/10 backdrop-blur-md px-3.5 py-1.5 rounded-full border border-[#E5B51B]/30 mb-3">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            <span>Confidential Integrity Channel</span>
        </span>
        <h1 class="text-3xl sm:text-5xl font-black font-heading mt-2 mb-3 text-white tracking-tight">Whistleblower & Incident Reporting</h1>
        <p class="text-slate-300 text-sm max-w-2xl mx-auto leading-relaxed">Report fraud, fake fee collections, driver misconduct, discrimination, unsafe battery handling, or misuse of the TEVDA name confidentially or anonymously.</p>
    </div>
</div>

<div class="py-16 bg-white">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="bg-rose-50 border-l-4 border-rose-600 p-5 rounded-2xl mb-8 text-xs sm:text-sm text-rose-900 leading-relaxed space-y-2">
            <p><strong>Strict Confidentiality Guarantee:</strong> Reports submitted through this portal are encrypted and routed directly to the TEVDA Governance & Audit Committee.</p>
            <p>You can choose to remain <strong>100% anonymous</strong>. You will receive an encrypted Tracking Ticket ID to check report status without disclosing your identity.</p>
        </div>

        <div class="bg-white p-8 sm:p-10 rounded-3xl border border-slate-200 shadow-xl">
            <form action="{{ route('whistleblower.submit') }}" method="POST" enctype="multipart/form-data" class="space-y-6" x-data="{ anonymous: false }">
                @csrf

                <div>
                    <label class="block text-xs font-bold text-slate-800 mb-1.5">Incident Category <span class="text-rose-600">*</span></label>
                    <select name="category" required class="w-full bg-slate-50 border border-slate-300 rounded-xl px-4 py-3 text-sm focus:outline-hidden focus:border-rose-500">
                        <option value="fraud">Fraud / Fake Fee Demands / Impersonation of TEVDA</option>
                        <option value="misconduct">Officer or Driver Misconduct / Extortion</option>
                        <option value="unsafe_practices">Unsafe Battery Handling / Hazardous Charging Practices</option>
                        <option value="discrimination">Discrimination / Harassment</option>
                        <option value="misuse_of_name">Misuse of TEVDA Logo or Association Identity</option>
                        <option value="other">Other Violation</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-800 mb-1.5">Detailed Description of Incident <span class="text-rose-600">*</span></label>
                    <textarea name="description" rows="5" required placeholder="Please provide specific details: dates, locations, persons involved, vehicle numbers, or payments demanded..." class="w-full bg-slate-50 border border-slate-300 rounded-xl px-4 py-3 text-sm focus:outline-hidden focus:border-rose-500">{{ old('description') }}</textarea>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-800 mb-1.5">Supporting Evidence (Optional)</label>
                    <input type="file" name="evidence" class="w-full text-xs text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-slate-100 file:text-slate-700 hover:file:bg-slate-200">
                    <p class="text-[11px] text-slate-400 mt-1">Upload screenshots, payment slips, photos or documents (PDF, JPG, PNG up to 10MB).</p>
                </div>

                <!-- Anonymous Toggle -->
                <div class="p-4 bg-slate-50 rounded-2xl border border-slate-200">
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" name="is_anonymous" value="1" x-model="anonymous" class="rounded text-rose-600 focus:ring-rose-500 h-4 w-4">
                        <span class="text-xs font-bold text-slate-800">Submit Anonymously (Do not record my personal name or contact details)</span>
                    </label>
                </div>

                <!-- Optional Contact Details if not anonymous -->
                <div x-show="!anonymous" x-transition class="space-y-4 pt-2 border-t border-slate-200">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-500">Contact Details (Optional for follow-up)</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-xs text-slate-600 mb-1">Your Name</label>
                            <input type="text" name="reporter_name" value="{{ old('reporter_name') }}" class="w-full bg-slate-50 border border-slate-300 rounded-xl px-3 py-2 text-xs">
                        </div>
                        <div>
                            <label class="block text-xs text-slate-600 mb-1">Your Phone</label>
                            <input type="text" name="reporter_phone" value="{{ old('reporter_phone') }}" placeholder="+255..." class="w-full bg-slate-50 border border-slate-300 rounded-xl px-3 py-2 text-xs">
                        </div>
                        <div>
                            <label class="block text-xs text-slate-600 mb-1">Your Email</label>
                            <input type="email" name="reporter_email" value="{{ old('reporter_email') }}" class="w-full bg-slate-50 border border-slate-300 rounded-xl px-3 py-2 text-xs">
                        </div>
                    </div>
                </div>

                <button type="submit" class="w-full bg-rose-600 hover:bg-rose-700 text-white font-bold py-4 px-6 rounded-xl text-sm transition shadow-lg">
                    Submit Confidential Report
                </button>
            </form>
        </div>

        <div class="mt-8 text-center">
            <a href="{{ route('whistleblower.track') }}" class="text-xs font-semibold text-slate-600 hover:text-emerald-700">
                Already have a Complaint Reference Ticket? Track Ticket Status &rarr;
            </a>
        </div>

    </div>
</div>
@endsection
