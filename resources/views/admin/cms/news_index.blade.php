@extends('layouts.admin')

@section('title', 'News & Media Administration')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-900">News & Media Announcements</h1>
            <p class="text-sm text-slate-500">Publish official press releases, association circulars, and sector updates.</p>
        </div>
        <div>
            <a href="{{ route('admin.cms.news.create') }}" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold transition flex items-center gap-1.5 shadow-sm shadow-emerald-600/20">
                <i class="fa-solid fa-plus"></i> Write News Article
            </a>
        </div>
    </div>

    <!-- News Table -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-50 border-b border-slate-200 text-xs font-bold text-slate-500 uppercase tracking-wider">
                    <tr>
                        <th class="px-5 py-4">Article Title</th>
                        <th class="px-4 py-4">Category</th>
                        <th class="px-4 py-4">Publication Date</th>
                        <th class="px-4 py-4">Status</th>
                        <th class="px-5 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($news as $article)
                        <tr class="hover:bg-slate-50/70 transition">
                            <td class="px-5 py-4">
                                <div class="font-black text-slate-900 text-xs flex items-center gap-2">
                                    {{ $article->title }}
                                </div>
                                <div class="text-[11px] text-slate-500 line-clamp-1 mt-0.5">{{ $article->summary }}</div>
                            </td>
                            <td class="px-4 py-4">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-lg text-xs font-semibold bg-slate-100 text-slate-700">
                                    {{ ucwords(str_replace('_', ' ', $article->category)) }}
                                </span>
                            </td>
                            <td class="px-4 py-4 text-xs text-slate-500 whitespace-nowrap">
                                {{ $article->publication_date ? $article->publication_date->format('d M Y') : 'N/A' }}
                            </td>
                            <td class="px-4 py-4">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold {{ $article->is_published ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-slate-100 text-slate-600 border border-slate-200' }}">
                                    {{ $article->is_published ? 'Published' : 'Draft' }}
                                </span>
                            </td>
                            <td class="px-5 py-4 text-right whitespace-nowrap">
                                <a href="{{ route('news.show', $article->slug) }}" target="_blank" class="inline-flex items-center gap-1 px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg text-xs font-bold transition">
                                    <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i> View Live
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-5 py-12 text-center text-slate-400 text-xs">
                                <i class="fa-solid fa-newspaper text-3xl mb-2"></i>
                                <p>No news articles published yet.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($news->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $news->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
