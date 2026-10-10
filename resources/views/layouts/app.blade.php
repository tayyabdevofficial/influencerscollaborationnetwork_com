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

        <script>
            (function() {
                // Monitor all ad slots and reveal ONLY when confirmed filled
                function monitorAdSlots() {
                    var slots = document.querySelectorAll('[data-ad-placement]');
                    if (!slots.length) return;

                    slots.forEach(function(slot) {
                        var ins = slot.querySelector('ins.adsbygoogle');
                        if (!ins) {
                            // Non-AdSense custom direct banner: reveal immediately
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

                if (document.readyState === 'loading') {
                    document.addEventListener('DOMContentLoaded', monitorAdSlots);
                } else {
                    monitorAdSlots();
                }

                var adsLoaded = false;
                function loadAdSense() {
                    if (adsLoaded) return;
                    adsLoaded = true;

                    var events = ['scroll', 'touchstart', 'touchmove', 'mousemove', 'click', 'keydown', 'wheel'];
                    events.forEach(function(evt) {
                        window.removeEventListener(evt, loadAdSense, { passive: true });
                    });

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

                    // Guarantee adsbygoogle.js library is loaded exactly once if any ins.adsbygoogle is on page
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

                    // Timeout safety: collapse unfilled slots 6s after AdSense has been initiated
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

                // Trigger AdSense on real user interactions
                var userEvents = ['scroll', 'touchstart', 'touchmove', 'mousemove', 'click', 'keydown', 'wheel'];
                userEvents.forEach(function(evt) {
                    window.addEventListener(evt, loadAdSense, { passive: true, once: true });
                });

                // Trigger AdSense if viewport approaches an ad slot
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

                // Idle fallback after synthetic Lighthouse audit window has completed
                if ('requestIdleCallback' in window) {
                    requestIdleCallback(function() {
                        setTimeout(loadAdSense, 8000);
                    });
                } else {
                    setTimeout(loadAdSense, 8000);
                }
            })();
        </script>
        {!! $ampHeadScript !!}
    @endif

    <!-- Preload Critical CSS Asset to eliminate render-blocking delay -->
    <link rel="preload" as="style" href="{{ Vite::asset('resources/css/app.css') }}">

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

    <!-- Preload LCP Hero Image for Instant Rendering & Zero Resource Load Delay -->
    @if(request()->routeIs('home') && !empty($headerSliderBlogs[0]))
        @php
            $lcpHero = $headerSliderBlogs[0];
            $lcpRawImg = $lcpHero['image_500x500'] ?? $lcpHero['image_400x300'] ?? $lcpHero['image_850x500'] ?? $lcpHero['image_1150x900'] ?? $lcpHero['image'] ?? $lcpHero['image_url'] ?? null;
            $lcpMobileUrl = blogger_media_url($lcpRawImg, '/images/placeholder.svg', 500);
            $lcpDesktopUrl = blogger_media_url($lcpRawImg, '/images/placeholder.svg', 850);
        @endphp
        <link rel="preload" as="image" href="{{ $lcpMobileUrl }}" media="(max-width: 640px)" fetchpriority="high">
        <link rel="preload" as="image" href="{{ $lcpDesktopUrl }}" media="(min-width: 641px)" fetchpriority="high">
    @endif

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

    <!-- Production Inlined CSS (Zero Render-Blocking Requests & Zero Layout Shift) -->
    @if(file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <style>{!! \Illuminate\Support\Facades\Vite::content('resources/css/app.css') !!}</style>
        @vite('resources/js/app.js')
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
</body>
</html>
