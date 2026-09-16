@extends('layouts.admin')

@section('title', 'Official Resources & Policy Downloads')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-900">Official Resources & Publications</h1>
            <p class="text-sm text-slate-500">Manage association constitution, regulatory filings, guidelines, and member documents.</p>
        </div>
        <div>
            <button type="button" onclick="document.getElementById('uploadModal').classList.remove('hidden')" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold transition flex items-center gap-1.5 shadow-sm shadow-emerald-600/20">
                <i class="fa-solid fa-cloud-arrow-up"></i> Upload Resource Document
            </button>
        </div>
    </div>

    <!-- Resources Table -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-50 border-b border-slate-200 text-xs font-bold text-slate-500 uppercase tracking-wider">
                    <tr>
                        <th class="px-5 py-4">Document Title</th>
                        <th class="px-4 py-4">Category</th>
                        <th class="px-4 py-4">Version / Authority</th>
                        <th class="px-4 py-4">Visibility</th>
                        <th class="px-4 py-4">Downloads</th>
                        <th class="px-5 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($resources as $res)
                        <tr class="hover:bg-slate-50/70 transition">
                            <td class="px-5 py-4">
                                <div class="font-black text-slate-900 text-xs flex items-center gap-2">
                                    <i class="fa-solid fa-file-pdf text-rose-500 text-sm"></i>
                                    {{ $res->title }}
                                </div>
                                <div class="text-[11px] text-slate-500 mt-0.5">
                                    {{ strtoupper($res->file_type ?? 'PDF') }} • {{ round(($res->file_size ?? 0) / 1024) }} KB
                                </div>
                            </td>
                            <td class="px-4 py-4">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-lg text-xs font-semibold bg-slate-100 text-slate-700">
                                    {{ ucwords(str_replace('_', ' ', $res->category)) }}
                                </span>
                            </td>
                            <td class="px-4 py-4 text-xs text-slate-600">
                                <div>Ver {{ $res->version ?? '1.0' }}</div>
                                <div class="text-[11px] text-slate-400">{{ $res->approving_authority ?? 'TEVDA Council' }}</div>
                            </td>
                            <td class="px-4 py-4">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold {{ $res->visibility === 'public' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-purple-50 text-purple-700 border border-purple-200' }}">
                                    {{ ucfirst(str_replace('_', ' ', $res->visibility)) }}
                                </span>
                            </td>
                            <td class="px-4 py-4 text-xs font-bold text-slate-700">
                                {{ $res->download_count ?? 0 }}
                            </td>
                            <td class="px-5 py-4 text-right whitespace-nowrap">
                                <a href="{{ route('resources.download', $res->slug) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-slate-900 hover:bg-emerald-600 text-white rounded-lg text-xs font-bold transition">
                                    <i class="fa-solid fa-download text-xs"></i> Download
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-12 text-center text-slate-400 text-xs">
                                <i class="fa-solid fa-folder-open text-3xl mb-2"></i>
                                <p>No resource documents uploaded yet.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($resources->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $resources->links() }}
            </div>
        @endif
    </div>
</div>

<!-- Modal: Upload Resource -->
<div id="uploadModal" class="hidden fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-lg w-full p-6 shadow-2xl space-y-4 border border-slate-200">
        <div class="flex items-center justify-between">
            <h3 class="text-base font-black text-slate-900 flex items-center gap-2">
                <i class="fa-solid fa-cloud-arrow-up text-emerald-600"></i> Upload Official Document
            </h3>
            <button type="button" onclick="document.getElementById('uploadModal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
        <form method="POST" action="{{ route('admin.cms.resources.store') }}" enctype="multipart/form-data" class="space-y-3">
            @csrf
            <div>
                <label class="block text-xs font-bold text-slate-600 mb-1">Document Title *</label>
                <input type="text" name="title" required placeholder="e.g. TEVDA Constitution & Bylaws (Official)" class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs outline-none focus:ring-2 focus:ring-emerald-500">
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold text-slate-600 mb-1">Category *</label>
                    <select name="category" required class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs outline-none focus:ring-2 focus:ring-emerald-500">
                        <option value="constitution_and_governance">Constitution & Governance</option>
                        <option value="membership">Membership Guidelines</option>
                        <option value="training">Training Materials</option>
                        <option value="projects">Projects & Proposals</option>
                        <option value="policies">Policies & Code of Conduct</option>
                        <option value="reports">Annual Reports</option>
                        <option value="forms">Official Forms</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-600 mb-1">Visibility *</label>
                    <select name="visibility" required class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs outline-none focus:ring-2 focus:ring-emerald-500">
                        <option value="public">Public (Open Download)</option>
                        <option value="members_only">Members Only (Portal)</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold text-slate-600 mb-1">Version</label>
                    <input type="text" name="version" value="1.0" class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs outline-none">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-600 mb-1">Effective Date</label>
                    <input type="date" name="effective_date" value="{{ date('Y-m-d') }}" class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs outline-none">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-600 mb-1">Select File (PDF, DOCX, XLSX, Max 20MB) *</label>
                <input type="file" name="file" required class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100">
            </div>

            <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-100">
                <button type="button" onclick="document.getElementById('uploadModal').classList.add('hidden')" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-bold">
                    Cancel
                </button>
                <button type="submit" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold shadow-md shadow-emerald-600/20">
                    Upload & Save
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
