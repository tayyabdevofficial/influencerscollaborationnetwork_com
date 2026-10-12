<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Preload LCP Hero Image for Instant 0ms Load Delay Discovery -->
    @if(request()->routeIs('home') && !empty($headerSliderBlogs[0]))
        @php
            $lcpHero = $headerSliderBlogs[0];
            $lcpRawImg = $lcpHero['image_850x500'] ?? $lcpHero['image_1150x900'] ?? $lcpHero['image_500x500'] ?? $lcpHero['image_400x300'] ?? $lcpHero['image'] ?? $lcpHero['image_url'] ?? null;
            $lcpMobileUrl = blogger_media_url($lcpRawImg, '/images/placeholder.svg', 700);
            $lcpDesktopUrl = blogger_media_url(!empty($lcpHero['image_850x500']) ? $lcpHero['image_850x500'] : $lcpRawImg, '/images/placeholder.svg', 850);
        @endphp
        <link rel="preload" as="image" href="{{ $lcpDesktopUrl }}" imagesrcset="{{ $lcpMobileUrl }} 700w, {{ $lcpDesktopUrl }} 850w" imagesizes="(max-width: 640px) 100vw, 850px" fetchpriority="high">
    @endif

    <style>
        /* Zero Non-Composited Animations Override */
        .shimmer-loading {
            animation: none !important;
            background-position: 0 0 !important;
        }
    </style>

    <title>@yield('title', config('site.name') . ' - ' . config('site.tagline'))</title>
    <meta name="description" content="@yield('meta_description', config('site.tagline'))">

    @if(!empty($metaTags))
        {!! $metaTags !!}
    @endif

    <!-- Global AdSense Scripts (Deferred for Zero-Reflow & Maximum PageSpeed) -->
    @if($adsEnabled ?? false)
        @php
            $headScript = $websiteAds['head_script'] ?? '';
            $ampHeadScript = $websiteAds['amp_head_script'] ?? '';
        @endphp

        <style>
            /* Critical CSS: Initially collapsed to ZERO space on screen */
            .ad-slot-wrapper {
                display: block !important;
                width: 100% !important;
                height: 0 !important;
                min-height: 0 !important;
                max-height: 0 !important;
                margin: 0 !important;
                padding: 0 !important;
                overflow: hidden !important;
                opacity: 0 !important;
                pointer-events: none !important;
                border: none !important;
                clear: both;
            }

            .ad-slot-wrapper .ad-slot-inner {
                height: 0 !important;
                max-height: 0 !important;
                margin: 0 !important;
                padding: 0 !important;
                overflow: hidden !important;
            }

            /* Revealed smoothly ONLY once verified filled by Google AdSense */
            .ad-slot-wrapper.ad-slot-filled {
                height: auto !important;
                min-height: auto !important;
                max-height: none !important;
                margin-top: 1.25rem !important;
                margin-bottom: 1.25rem !important;
                overflow: visible !important;
                opacity: 1 !important;
                pointer-events: auto !important;
                transition: opacity 0.35s ease-in;
            }

            .ad-slot-wrapper.ad-slot-filled .ad-slot-inner {
                height: auto !important;
                max-height: none !important;
                overflow: visible !important;
            }

            /* Unfilled slots stay permanently hidden with 0 space */
            .ad-slot-wrapper.ad-slot-unfilled,
            ins.adsbygoogle[data-ad-status="unfilled"],
            [data-ad-placement]:has(ins.adsbygoogle[data-ad-status="unfilled"]) {
                display: none !important;
            }
        </style>
        {!! $ampHeadScript !!}
    @endif


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

    <!-- Production CSS & Self-Hosted Fonts (Graceful Inlined Fallback with Zero 500 Errors) -->
    @php
        $inlinedCss = null;
        try {
            $inlinedCss = \Illuminate\Support\Facades\Vite::content('resources/css/app.css');
        } catch (\Throwable $e) {
            $inlinedCss = null;
        }

        $inlinedFontCss = null;
        $primaryFontPreload = null;
        try {
            $fontsManifestPath = public_path('build/fonts-manifest.json');
            if (file_exists($fontsManifestPath)) {
                $fm = json_decode(file_get_contents($fontsManifestPath), true);
                if (!empty($fm['style']['file']) && file_exists(public_path('build/' . $fm['style']['file']))) {
                    $inlinedFontCss = file_get_contents(public_path('build/' . $fm['style']['file']));
                }
                if (!empty($fm['preloads'][0]['file']) && file_exists(public_path('build/' . $fm['preloads'][0]['file']))) {
                    $primaryFontPreload = asset('build/' . $fm['preloads'][0]['file']);
                }
            }
        } catch (\Throwable $e) {
            $inlinedFontCss = null;
            $primaryFontPreload = null;
        }
    @endphp
    @if(!empty($primaryFontPreload))
        <link rel="preload" href="{{ $primaryFontPreload }}" as="font" type="font/woff2" crossorigin="anonymous">
    @endif
    @if(file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @elseif(!empty($inlinedCss))
        @if(!empty($inlinedFontCss))
            <style>{!! $inlinedFontCss !!}</style>
        @endif
        <style>{!! $inlinedCss !!}</style>
        @vite('resources/js/app.js')
    @else
        @if(!empty($inlinedFontCss))
            <style>{!! $inlinedFontCss !!}</style>
        @endif
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
</head>
<body class="bg-slate-50 text-slate-900 dark:bg-[#080C14] dark:text-slate-100 font-sans antialiased min-h-screen flex flex-col transition-colors duration-300 selection:bg-[#DC2626] selection:text-white">

    <!-- Top Glow Line -->
    <div class="h-1 w-full bg-gradient-to-r from-[#DC2626] via-[#06B6D4] to-[#10B981]"></div>

    <!-- Header Navigation -->
    @include('components.header', ['categories' => $allCategories ?? []])

    <!-- Top Header Ad Placement (On Home page, displayed right after Hero Blogs) -->
    @if(!request()->routeIs('home'))
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full my-4">
            <x-ad-banner placement="header" />
        </div>
    @endif

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
    @if($adsEnabled ?? false)
        <script>
            (function() {
                function monitorAdSlots() {
                    var slots = document.querySelectorAll('[data-ad-placement]');
                    if (!slots.length) return;

                    slots.forEach(function(slot) {
                        var ins = slot.querySelector('ins.adsbygoogle');
                        if (!ins) {
                            revealSlot(slot);
                            return;
                        }

                        function revealSlot() {
                            if (slot.classList.contains('ad-slot-filled')) return;
                            slot.classList.remove('ad-slot-unfilled');
                            slot.classList.add('ad-slot-filled');
                            slot.removeAttribute('style');
                            var inner = slot.querySelector('.ad-slot-inner') || slot.firstElementChild;
                            if (inner) inner.removeAttribute('style');
                            var label = slot.querySelector('.ad-label');
                            if (label) label.classList.remove('hidden');
                        }

                        function collapseSlot() {
                            if (slot.classList.contains('ad-slot-filled')) return;
                            slot.classList.add('ad-slot-unfilled');
                            slot.style.display = 'none';
                        }

                        function checkStatus() {
                            var status = ins.getAttribute('data-ad-status');
                            if (status === 'filled') {
                                revealSlot();
                            } else if (status === 'unfilled') {
                                collapseSlot();
                            }
                        }

                        var observer = new MutationObserver(checkStatus);
                        observer.observe(ins, { attributes: true, attributeFilter: ['data-ad-status'] });
                        checkStatus();
                    });
                }

                monitorAdSlots();

                var adsLoaded = false;
                function loadAdSense() {
                    if (adsLoaded) return;
                    adsLoaded = true;

                    var events = ['scroll', 'touchstart', 'touchmove', 'mousemove', 'click', 'keydown', 'wheel'];
                    events.forEach(function(evt) {
                        window.removeEventListener(evt, loadAdSense, { passive: true });
                    });

                    @php
                        $headScript = $websiteAds['head_script'] ?? '';
                    @endphp
                    @if(!empty($headScript))
                        var temp = document.createElement('div');
                        temp.innerHTML = {!! json_encode($headScript) !!};
                        var scripts = temp.querySelectorAll('script');
                        scripts.forEach(function(s) {
                            var newScript = document.createElement('script');
                            Array.from(s.attributes).forEach(function(attr) {
                                newScript.setAttribute(attr.name, attr.value);
                            });
                            if (s.src) {
                                newScript.src = s.src;
                            } else {
                                newScript.textContent = s.textContent;
                            }
                            document.head.appendChild(newScript);
                        });
                    @endif

                    if (!document.querySelector('script[src*="adsbygoogle.js"]')) {
                        var anyIns = document.querySelector('ins.adsbygoogle');
                        if (anyIns) {
                            var clientId = anyIns.getAttribute('data-ad-client') || 'ca-pub-1759319613562086';
                            var adScript = document.createElement('script');
                            adScript.async = true;
                            adScript.crossOrigin = 'anonymous';
                            adScript.src = 'https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=' + clientId;
                            document.head.appendChild(adScript);
                        }
                    }

                    monitorAdSlots();

                    setTimeout(function() {
                        document.querySelectorAll('[data-ad-placement]').forEach(function(slot) {
                            var ins = slot.querySelector('ins.adsbygoogle');
                            if (ins && ins.getAttribute('data-ad-status') !== 'filled') {
                                slot.classList.add('ad-slot-unfilled');
                                slot.style.display = 'none';
                            }
                        });
                    }, 6000);
                }

                var userEvents = ['scroll', 'touchstart', 'touchmove', 'mousemove', 'click', 'keydown', 'wheel'];
                userEvents.forEach(function(evt) {
                    window.addEventListener(evt, loadAdSense, { passive: true, once: true });
                });

                if ('IntersectionObserver' in window) {
                    var adObserver = new IntersectionObserver(function(entries) {
                        for (var i = 0; i < entries.length; i++) {
                            if (entries[i].isIntersecting) {
                                loadAdSense();
                                adObserver.disconnect();
                                break;
                            }
                        }
                    }, { rootMargin: '350px' });

                    document.querySelectorAll('[data-ad-placement]').forEach(function(el) {
                        adObserver.observe(el);
                    });
                }

                if ('requestIdleCallback' in window) {
                    requestIdleCallback(function() {
                        setTimeout(loadAdSense, 8000);
                    });
                } else {
                    setTimeout(loadAdSense, 8000);
                }
            })();
        </script>
    @endif
</body>
</html>
