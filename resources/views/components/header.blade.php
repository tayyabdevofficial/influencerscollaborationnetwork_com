@props(['categories' => []])

<header class="sticky top-0 z-40 w-full transition-colors duration-200">
    <!-- Main Navigation Bar -->
    <nav class="backdrop-blur-md bg-white/95 dark:bg-[#080C14]/95 border-b border-slate-200 dark:border-slate-800/80 shadow-xs transition-colors">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16 sm:h-20 gap-2">

                <!-- Brand Logo -->
                <div class="flex items-center gap-3 shrink-0">
                    <a href="{{ route('home') }}" class="flex items-center gap-3 group">
                        <img src="{{ asset('logo.png') }}" 
                             alt="{{ config('site.name') }}" 
                             class="h-8 sm:h-10 w-auto object-contain transition-transform duration-300 group-hover:scale-105">
                        <div class="hidden sm:block">
                            <span class="text-[11px] font-black tracking-widest text-[#DC2626] uppercase block">Creator Network</span>
                            <span class="text-[10px] font-bold text-slate-400 block -mt-0.5">Collab &amp; Influence</span>
                        </div>
                    </a>
                </div>

                <!-- Desktop Category Navigation (Max 4 primary categories + More dropdown) -->
                <div class="hidden lg:flex items-center gap-1 xl:gap-2 overflow-visible">
                    <!-- Quizzes/Dares link -->
                    <a href="{{ route('quizzes.index') }}" 
                       class="px-3 py-2 rounded-xl text-xs sm:text-sm font-bold transition-all inline-flex items-center gap-1.5 whitespace-nowrap shrink-0 {{ request()->routeIs('quizzes.*') ? 'text-[#DC2626] dark:text-[#06B6D4] bg-slate-100 dark:bg-slate-800' : 'text-slate-700 dark:text-slate-300 hover:text-[#DC2626] dark:hover:text-[#06B6D4] hover:bg-slate-50 dark:hover:bg-slate-800/50' }}">
                        <span>⚡ Dares</span>
                        <span class="px-1.5 py-0.5 rounded-md text-[9px] font-black uppercase bg-[#DC2626] text-white">Viral</span>
                    </a>

                    @php
                        $rawCats = !empty($categories) ? $categories : ($allCategories ?? []);
                        $allCats = collect($rawCats);
                        // Randomly display up to 4 categories that fit comfortably in available navbar space
                        $displayCats = $allCats->count() > 4 ? $allCats->shuffle()->take(4) : $allCats;
                    @endphp

                    @foreach($displayCats as $category)
                        @php
                            $subCats = $category['sub_categories'] ?? [];
                            $hasSubs = !empty($subCats) && count($subCats) > 0;
                            $isActive = request()->is('category/' . ($category['slug'] ?? ''));
                        @endphp

                        @if($hasSubs)
                            <div class="relative group shrink-0">
                                <a href="{{ route('category.show', $category['slug'] ?? '#') }}" 
                                   class="px-3 py-2 rounded-xl text-xs sm:text-sm font-bold inline-flex items-center gap-1 transition-all whitespace-nowrap {{ $isActive ? 'text-[#DC2626] dark:text-[#06B6D4] bg-slate-100 dark:bg-slate-800' : 'text-slate-700 dark:text-slate-300 hover:text-[#DC2626] dark:hover:text-[#06B6D4] hover:bg-slate-50 dark:hover:bg-slate-800/50' }}">
                                    <span>{{ $category['name'] }}</span>
                                    <svg class="w-3.5 h-3.5 text-slate-400 group-hover:rotate-180 transition-transform duration-200 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </a>

                                <div class="absolute left-0 top-full pt-2 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50 w-60">
                                    <div class="rounded-2xl bg-white dark:bg-[#0F172A] shadow-2xl border border-slate-200 dark:border-slate-800 p-2.5 space-y-1 backdrop-blur-xl">
                                        <div class="px-3 py-1.5 text-[10px] font-black uppercase tracking-wider text-slate-400 border-b border-slate-100 dark:border-slate-800">
                                            Sub-Categories
                                        </div>
                                        @foreach($subCats as $sub)
                                            <a href="{{ route('subcategory.show', $sub['slug'] ?? '#') }}" class="block px-3 py-2 rounded-xl text-xs sm:text-sm font-medium text-slate-700 dark:text-slate-300 hover:text-[#DC2626] dark:hover:text-[#06B6D4] hover:bg-slate-50 dark:hover:bg-slate-800/60 transition-colors truncate">
                                                &bull; {{ $sub['name'] }}
                                            </a>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @else
                            <a href="{{ route('category.show', $category['slug'] ?? '#') }}" 
                               class="px-3 py-2 rounded-xl text-xs sm:text-sm font-bold transition-all whitespace-nowrap shrink-0 {{ $isActive ? 'text-[#DC2626] dark:text-[#06B6D4] bg-slate-100 dark:bg-slate-800' : 'text-slate-700 dark:text-slate-300 hover:text-[#DC2626] dark:hover:text-[#06B6D4] hover:bg-slate-50 dark:hover:bg-slate-800/50' }}">
                                {{ $category['name'] }}
                            </a>
                        @endif
                    @endforeach
                </div>

                <!-- Right Action Bar: Search Trigger, Theme Toggle & Mobile Menu (Exact funfillia structure) -->
                <div class="flex items-center gap-2 shrink-0">
                    <!-- Search Icon Button -->
                    <button type="button" 
                            onclick="window.openSearchModal()" 
                            aria-label="Search articles"
                            title="Search (⌘K)"
                            class="p-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-red-500/50 flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </button>

                    <!-- Theme Toggle Switch -->
                    <x-theme-toggle />

                    <!-- Mobile Hamburger Button -->
                    <button type="button" 
                            onclick="window.openMobileNav()"
                            class="lg:hidden p-2.5 rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700 transition-colors"
                            aria-label="Open Menu">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                </div>

            </div>
        </div>
    </nav>

    <!-- Animated Mobile Drawer & Backdrop -->
    <div id="mobile-nav-backdrop" 
         class="fixed inset-0 z-50 bg-slate-950/60 backdrop-blur-sm opacity-0 pointer-events-none transition-opacity duration-300 lg:hidden"
         onclick="window.closeMobileNav()"></div>

    <div id="mobile-nav-drawer" 
         class="fixed top-0 right-0 bottom-0 z-50 w-80 max-w-[85vw] bg-white dark:bg-[#080C14] shadow-2xl border-l border-slate-200 dark:border-slate-800 transform translate-x-full transition-transform duration-300 ease-in-out lg:hidden flex flex-col">
        
        <!-- Drawer Header -->
        <div class="flex items-center justify-between p-5 border-b border-slate-100 dark:border-slate-800">
            <a href="{{ route('home') }}" class="flex items-center" onclick="window.closeMobileNav()">
                <img src="{{ asset('logo.png') }}" alt="{{ config('site.name') }}" class="h-8 w-auto object-contain">
            </a>
            <button type="button" 
                    onclick="window.closeMobileNav()" 
                    class="p-2 rounded-xl text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors"
                    aria-label="Close Menu">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- Quick Search inside Mobile Drawer -->
        <div class="p-4 border-b border-slate-100 dark:border-slate-800">
            <form action="{{ route('search') }}" method="GET" class="relative">
                <input type="search" 
                       name="q" 
                       placeholder="Search creator insights..." 
                       class="w-full px-4 py-2.5 pl-10 rounded-xl bg-slate-100 dark:bg-slate-800 border-0 text-sm text-slate-900 dark:text-white placeholder-slate-400 focus:ring-2 focus:ring-[#DC2626]">
                <svg class="w-4 h-4 text-slate-400 absolute left-3.5 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </form>
        </div>

        <!-- Drawer Nav Links & Accordion -->
        <div class="flex-1 overflow-y-auto p-4 space-y-2">
            <a href="{{ route('quizzes.index') }}" 
               onclick="window.closeMobileNav()"
               class="flex items-center justify-between px-3.5 py-2.5 rounded-xl font-bold text-sm bg-red-50 dark:bg-red-950/40 text-[#DC2626] dark:text-[#06B6D4] transition-colors">
                <div class="flex items-center gap-2">
                    <span>⚡</span>
                    <span>Creator Dares</span>
                </div>
                <span class="px-2 py-0.5 text-[10px] font-black uppercase rounded-full bg-[#DC2626] text-white">VIRAL</span>
            </a>

            <div class="pt-2 pb-1">
                <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 px-3">Categories</span>
            </div>

            @foreach($categories ?? [] as $index => $category)
                @php
                    $subCats = $category['sub_categories'] ?? [];
                    $hasSubs = !empty($subCats) && count($subCats) > 0;
                    $accordionId = 'mobile-sub-' . ($category['id'] ?? $index);
                @endphp

                @if($hasSubs)
                    <div class="rounded-xl overflow-hidden border border-slate-100 dark:border-slate-800">
                        <div class="flex items-center justify-between px-3.5 py-2.5 bg-slate-50/70 dark:bg-slate-800/50">
                            <a href="{{ route('category.show', $category['slug'] ?? '#') }}" 
                               onclick="window.closeMobileNav()"
                               class="font-semibold text-sm text-slate-900 dark:text-white hover:text-[#DC2626] dark:hover:text-[#06B6D4]">
                                {{ $category['name'] }}
                            </a>
                            <button type="button" 
                                    onclick="window.toggleMobileAccordion('{{ $accordionId }}', this)" 
                                    class="p-1 rounded-lg text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 transition-transform">
                                <svg class="w-4 h-4 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                        </div>
                        <div id="{{ $accordionId }}" class="hidden pl-4 pr-3 py-2 space-y-1 bg-white dark:bg-[#080C14] border-t border-slate-100 dark:border-slate-800/60">
                            @foreach($subCats as $sub)
                                <a href="{{ route('subcategory.show', $sub['slug'] ?? '#') }}" 
                                   onclick="window.closeMobileNav()"
                                   class="block px-3 py-1.5 rounded-lg text-xs text-slate-600 dark:text-slate-400 hover:text-[#DC2626] dark:hover:text-[#06B6D4] hover:bg-slate-50 dark:hover:bg-slate-800/30">
                                    {{ $sub['name'] }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                @else
                    <a href="{{ route('category.show', $category['slug'] ?? '#') }}" 
                       onclick="window.closeMobileNav()"
                       class="block px-3.5 py-2.5 rounded-xl font-semibold text-sm text-slate-900 dark:text-white hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">
                        {{ $category['name'] }}
                    </a>
                @endif
            @endforeach

            <div class="pt-2 pb-1">
                <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 px-3">Network</span>
            </div>

            <a href="{{ route('pages.about') }}" 
               onclick="window.closeMobileNav()"
               class="block px-3.5 py-2 rounded-xl text-sm font-semibold text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800">
                About Us
            </a>
            <a href="{{ route('pages.contact') }}" 
               onclick="window.closeMobileNav()"
               class="block px-3.5 py-2 rounded-xl text-sm font-semibold text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800">
                Collab &amp; Partner
            </a>
        </div>
    </div>
</header>
