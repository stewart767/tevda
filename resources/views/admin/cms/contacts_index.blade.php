@extends('layouts.admin')

@section('title', 'Inquiries & Contact Messages')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-900">General Public Inquiries</h1>
            <p class="text-sm text-slate-500">Respond to messages and questions submitted through the official website contact portal.</p>
        </div>
        <div>
            <span class="inline-flex items-center px-3 py-2 rounded-xl bg-emerald-50 text-emerald-700 text-xs font-bold border border-emerald-200">
                <i class="fa-solid fa-envelope mr-1.5"></i> {{ $messages->total() }} Total Messages
            </span>
        </div>
    </div>

    <!-- Messages Table -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-50 border-b border-slate-200 text-xs font-bold text-slate-500 uppercase tracking-wider">
                    <tr>
                        <th class="px-5 py-4">Sender</th>
                        <th class="px-4 py-4">Subject & Message</th>
                        <th class="px-4 py-4">Received</th>
                        <th class="px-4 py-4">Status</th>
                        <th class="px-5 py-4 text-right">Update Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($messages as $msg)
                        <tr class="hover:bg-slate-50/70 transition">
                            <td class="px-5 py-4">
                                <div class="font-black text-slate-900 text-xs">{{ $msg->name }}</div>
                                <div class="text-[11px] text-slate-500">{{ $msg->email }}</div>
                                @if($msg->phone)
                                    <div class="text-[10px] text-slate-400 font-mono">{{ $msg->phone }}</div>
                                @endif
                            </td>
                            <td class="px-4 py-4 text-xs text-slate-700 max-w-sm">
                                <div class="font-bold text-slate-900 mb-0.5">{{ $msg->subject }}</div>
                                <p class="line-clamp-2 text-slate-500">{{ $msg->message }}</p>
                                @if($msg->reply_notes)
                                    <div class="mt-1 p-2 bg-slate-50 rounded-lg text-[11px] text-slate-600 border border-slate-100">
                                        <strong>Reply Notes:</strong> {{ $msg->reply_notes }}
                                    </div>
                                @endif
                            </td>
                            <td class="px-4 py-4 text-xs text-slate-400 whitespace-nowrap">
                                {{ $msg->created_at->format('d M Y, H:i') }}
                            </td>
                            <td class="px-4 py-4">
                                @php
                                    $msgBadges = [
                                        'new' => 'bg-blue-50 text-blue-700 border-blue-200',
                                        'in_progress' => 'bg-amber-50 text-amber-700 border-amber-200',
                                        'replied' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                        'closed' => 'bg-slate-100 text-slate-600 border-slate-200',
                                    ];
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold border {{ $msgBadges[$msg->status] ?? 'bg-slate-100 text-slate-700 border-slate-200' }}">
                                    {{ ucfirst(str_replace('_', ' ', $msg->status)) }}
                                </span>
                            </td>
                            <td class="px-5 py-4 text-right">
                                <form method="POST" action="{{ route('admin.cms.contacts.status', $msg->id) }}" class="inline-flex items-center gap-1.5">
                                    @csrf
                                    <select name="status" class="py-1 px-2 bg-slate-50 border border-slate-200 rounded-lg text-xs outline-none">
                                        <option value="new" {{ $msg->status == 'new' ? 'selected' : '' }}>New</option>
                                        <option value="in_progress" {{ $msg->status == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                        <option value="replied" {{ $msg->status == 'replied' ? 'selected' : '' }}>Replied</option>
                                        <option value="closed" {{ $msg->status == 'closed' ? 'selected' : '' }}>Closed</option>
                                    </select>
                                    <button type="submit" class="px-2.5 py-1 bg-slate-900 hover:bg-emerald-600 text-white rounded-lg text-xs font-bold transition">
                                        Save
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-5 py-12 text-center text-slate-400 text-xs">
                                <i class="fa-solid fa-inbox text-3xl mb-2"></i>
                                <p>No contact inquiries received.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($messages->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $messages->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
