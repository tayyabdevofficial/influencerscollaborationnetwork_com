<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('site.name') . ' - ' . config('site.tagline'))</title>
    <meta name="description" content="@yield('meta_description', config('site.tagline'))">

    @if(!empty($metaTags))
        {!! $metaTags !!}
    @endif

    <!-- Google Fonts: Plus Jakarta Sans & Outfit -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800;900&family=Plus+Jakarta+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400;1,600&display=swap" rel="stylesheet">

    <!-- Favicon & Theme Color -->
    <link rel="icon" type="image/png" href="{{ asset('favicon-96x96.png') }}" sizes="96x96" />
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}" />
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" />
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}" />
    <link rel="manifest" href="{{ asset('site.webmanifest') }}" />
    <meta name="theme-color" content="#DC2626">

    <!-- Anti-Flash Dark Mode Initialization Script (Auto System Default) -->
    <script>
        (function() {
            try {
                const savedTheme = localStorage.getItem('icn_theme');
                const systemDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
                if (savedTheme === 'dark' || (!savedTheme && systemDark)) {
                    document.documentElement.classList.add('dark');
                } else {
                    document.documentElement.classList.remove('dark');
                }
            } catch (e) {}
        })();
    </script>

    <!-- Vite Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-50 text-slate-900 dark:bg-[#080C14] dark:text-slate-100 font-sans antialiased min-h-screen flex flex-col transition-colors duration-300 selection:bg-[#DC2626] selection:text-white">

    <!-- Top Glow Line -->
    <div class="h-1 w-full bg-gradient-to-r from-[#DC2626] via-[#06B6D4] to-[#10B981]"></div>

    <!-- Header Navigation -->
    @include('components.header', ['categories' => $allCategories ?? []])

    <!-- Main Content Area -->
    <main class="flex-grow">
        @yield('content')
    </main>

    <!-- Footer Component -->
    @include('components.footer')

    <!-- Modals & Overlays -->
    @include('components.search-modal')
    @include('components.cookie-consent')

    <!-- Device Tracking Cookie Generator -->
    <script>
        (function() {
            function getCookie(name) {
                const value = `; ${document.cookie}`;
                const parts = value.split(`; ${name}=`);
                if (parts.length === 2) return parts.pop().split(';').shift();
            }
            if (!getCookie('icn_device_id')) {
                const deviceId = 'icn_' + Math.random().toString(36).substring(2, 15) + Math.random().toString(36).substring(2, 15);
                document.cookie = `icn_device_id=${deviceId}; path=/; max-age=${60 * 60 * 24 * 365}; SameSite=Lax`;
            }
        })();
    </script>
</body>
</html>
