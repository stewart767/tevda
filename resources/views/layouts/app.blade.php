<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>@yield('title', __('Tanzania Electric Vehicle Drivers Association (TEVDA) — SMART DRIVERS SMART MOBILITY'))</title>
    <meta name="description" content="@yield('meta_description', __('Official portal of Tanzania Electric Vehicle Drivers Association (TEVDA). Connecting drivers and operators of commercially used electric vehicles with training, employment, finance, and green mobility partnerships.'))">
    
    <!-- Alpine.js for lightweight UI interactivity -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.8/dist/cdn.min.js"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    @stack('styles')
</head>
<body class="bg-slate-50 text-slate-900 flex flex-col min-h-screen antialiased selection:bg-emerald-500 selection:text-white">

    @include('partials.header')

    <main class="flex-grow">
        @yield('content')
    </main>

    @include('partials.footer')

    @include('partials.translator_script')

    @stack('scripts')
</body>
</html>
