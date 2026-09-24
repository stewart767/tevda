<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', __('Member Portal')) — TEVDA</title>

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.8/dist/cdn.min.js"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="bg-slate-100 text-slate-800 antialiased font-sans min-h-screen flex flex-col">
    <!-- Top Bar -->
    <header class="bg-slate-900 text-white shadow-md sticky top-0 z-30">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <a href="{{ route('portal.dashboard') }}" class="flex items-center gap-3">
                    @if(\App\Models\Setting::hasCustomLogo())
                        <img src="{{ \App\Models\Setting::getLogoUrl() }}" alt="Logo" class="h-9 w-auto max-w-[40px] object-contain">
                    @else
                        <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-emerald-500 to-teal-700 flex items-center justify-center text-white font-bold text-base shadow-sm">
                            TEV
                        </div>
                    @endif
                    <div>
                        <span class="text-base font-extrabold tracking-tight text-white block font-heading">TEVDA PORTAL</span>
                        <span class="text-[9px] uppercase tracking-wider text-emerald-400 font-semibold block">Member Area</span>
                    </div>
                </a>

                <div class="flex items-center space-x-3">
                    <!-- Notifications Dropdown -->
                    @php
                        $unreadNotifications = auth()->user()->unreadNotificationsCustom;
                    @endphp
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="relative p-2 text-slate-300 hover:text-white rounded-lg hover:bg-slate-800 transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                            @if ($unreadNotifications->count() > 0)
                                <span class="absolute top-1.5 right-1.5 w-2.5 h-2.5 bg-emerald-500 rounded-full ring-2 ring-slate-900"></span>
                            @endif
                        </button>

                        <div x-show="open" @click.outside="open = false" x-transition class="absolute right-0 mt-2 w-80 bg-white rounded-2xl shadow-xl border border-slate-100 py-3 z-50 text-slate-800">
                            <div class="px-4 py-2 border-b border-slate-100 flex justify-between items-center">
                                <span class="font-bold text-sm">Notifications</span>
                                <span class="text-xs bg-emerald-100 text-emerald-800 font-bold px-2 py-0.5 rounded-full">{{ $unreadNotifications->count() }} new</span>
                            </div>
                            <div class="max-h-64 overflow-y-auto divide-y divide-slate-50">
                                @forelse ($unreadNotifications as $notif)
                                    <a href="{{ route('portal.notifications.read', $notif->id) }}" class="block p-3 hover:bg-slate-50 transition">
                                        <p class="text-xs font-bold text-slate-900">{{ $notif->title }}</p>
                                        <p class="text-xs text-slate-600 line-clamp-2 mt-0.5">{{ $notif->message }}</p>
                                        <span class="text-[10px] text-slate-400 mt-1 block">{{ $notif->created_at->diffForHumans() }}</span>
                                    </a>
                                @empty
                                    <div class="p-4 text-center text-xs text-slate-500">No new notifications</div>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    <!-- Language Switcher in Portal -->
                    @include('partials.language_selector')

                    <a href="{{ route('home') }}" class="text-xs text-slate-300 hover:text-white hidden sm:inline px-2 py-1">{{ __('Public Site') }}</a>

                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="text-xs bg-slate-800 hover:bg-rose-900 text-slate-300 hover:text-white px-3 py-1.5 rounded-lg transition font-medium">{{ __('Logout') }}</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Sub navigation -->
        <nav class="bg-slate-800 border-t border-slate-700/60 overflow-x-auto">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex space-x-1 sm:space-x-4 py-1.5">
                <a href="{{ route('portal.dashboard') }}" class="px-3 py-1.5 rounded-lg text-xs sm:text-sm font-semibold whitespace-nowrap {{ request()->routeIs('portal.dashboard') ? 'bg-emerald-600 text-white' : 'text-slate-300 hover:bg-slate-700 hover:text-white' }} transition">{{ __('Dashboard') }}</a>
                <a href="{{ route('portal.profile') }}" class="px-3 py-1.5 rounded-lg text-xs sm:text-sm font-semibold whitespace-nowrap {{ request()->routeIs('portal.profile*') ? 'bg-emerald-600 text-white' : 'text-slate-300 hover:bg-slate-700 hover:text-white' }} transition">{{ __('My Profile') }}</a>
                <a href="{{ route('portal.training') }}" class="px-3 py-1.5 rounded-lg text-xs sm:text-sm font-semibold whitespace-nowrap {{ request()->routeIs('portal.training*') ? 'bg-emerald-600 text-white' : 'text-slate-300 hover:bg-slate-700 hover:text-white' }} transition">{{ __('Training') }}</a>
                <a href="{{ route('portal.opportunities') }}" class="px-3 py-1.5 rounded-lg text-xs sm:text-sm font-semibold whitespace-nowrap {{ request()->routeIs('portal.opportunities*') ? 'bg-emerald-600 text-white' : 'text-slate-300 hover:bg-slate-700 hover:text-white' }} transition">{{ __('Opportunities') }}</a>
                <a href="{{ route('portal.projects') }}" class="px-3 py-1.5 rounded-lg text-xs sm:text-sm font-semibold whitespace-nowrap {{ request()->routeIs('portal.projects*') ? 'bg-emerald-600 text-white' : 'text-slate-300 hover:bg-slate-700 hover:text-white' }} transition">{{ __('Projects') }}</a>
                <a href="{{ route('portal.payments') }}" class="px-3 py-1.5 rounded-lg text-xs sm:text-sm font-semibold whitespace-nowrap {{ request()->routeIs('portal.payments*') ? 'bg-emerald-600 text-white' : 'text-slate-300 hover:bg-slate-700 hover:text-white' }} transition">{{ __('Payments & Invoices') }}</a>
            </div>
        </nav>
    </header>

    <main class="flex-grow max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-8">
        @include('partials.alerts')
        @yield('content')
    </main>

    <footer class="bg-white border-t border-slate-200 py-6 text-center text-xs text-slate-500">
        <p>&copy; {{ date('Y') }} {{ __('Tanzania Electric Vehicle Drivers Association (TEVDA)') }}. {{ __('SMART DRIVERS SMART MOBILITY') }}.</p>
    </footer>

    @include('partials.translator_script')

    @stack('scripts')
</body>
</html>
