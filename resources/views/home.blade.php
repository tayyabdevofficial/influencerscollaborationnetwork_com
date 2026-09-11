@extends('layouts.app')

@section('title', config('site.name') . ' - ' . config('site.tagline'))
@section('meta_description', config('site.tagline'))

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 space-y-10">

        <!-- Hero Spotlight Slider -->
        @if(!empty($headerSliderBlogs) && count($headerSliderBlogs) > 0)
            <x-hero-slider :blogs="$headerSliderBlogs" />
        @endif

        <!-- Ad Placement Top -->
        <x-ad-banner placement="home_top" />

        <!-- Featured Creator Spotlights & Leaderboard Section -->
        <section class="space-y-6">
            <div class="flex items-center justify-between border-b border-slate-200 dark:border-slate-800 pb-4">
                <div>
                    <span class="text-xs font-black uppercase tracking-widest text-[#DC2626]">Spotlight Series</span>
                    <h2 class="text-2xl sm:text-3xl font-black text-slate-900 dark:text-white tracking-tight">
                        Featured Creator Breakthroughs
                    </h2>
                </div>
                <div class="hidden sm:flex items-center gap-2">
                    <span class="px-3 py-1 rounded-full text-xs font-extrabold bg-red-100 dark:bg-red-950/60 text-[#DC2626] dark:text-red-400">
                        🔥 Trending Now
                    </span>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                <!-- Main Grid (8 cols) -->
                <div class="lg:col-span-8">
                    @if(!empty($featuredBlogs) && count($featuredBlogs) > 0)
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            @foreach(array_slice($featuredBlogs, 0, 4) as $featured)
                                <x-blog-card :blog="$featured" />
                            @endforeach
                        </div>
                    @endif
                </div>

                <!-- Trending Radar Sidebar (4 cols) -->
                <div class="lg:col-span-4 space-y-6">
                    <div class="rounded-3xl bg-white dark:bg-[#0F172A] p-6 border border-slate-200 dark:border-slate-800 shadow-sm space-y-6">
                        <div class="flex items-center justify-between border-b border-slate-100 dark:border-slate-800 pb-3">
                            <div class="flex items-center gap-2">
                                <span class="w-2.5 h-2.5 rounded-full bg-[#06B6D4] animate-ping"></span>
                                <h3 class="text-base font-black text-slate-900 dark:text-white uppercase tracking-wider">
                                    Creator Radar
                                </h3>
                            </div>
                            <span class="text-[11px] font-bold text-slate-400">Top Hits</span>
                        </div>

                        <div class="space-y-4">
                            @php
                                $sidebarStories = !empty($todayTopBlogs) ? $todayTopBlogs : $recentBlogs;
                            @endphp
                            @foreach(collect($sidebarStories)->take(5) as $index => $story)
                                @php
                                    $storySlug = $story['slug'] ?? '#';
                                    $storyUrl = route('blog.show', $storySlug);
                                    $storyTitle = $story['title'] ?? '';
                                    $storyCat = $story['category']['name'] ?? 'Creator';
                                    $storyDate = blogger_format_date($story['published_at'] ?? null);
                                    $storyImg = blogger_media_url($story['image_200x200'] ?? $story['image_url'] ?? null);
                                @endphp
                                <a href="{{ $storyUrl }}" class="flex items-center gap-3.5 group p-2 rounded-2xl hover:bg-slate-50 dark:hover:bg-slate-800/60 transition-colors">
                                    <span class="text-xl font-black text-slate-300 dark:text-slate-700 group-hover:text-[#DC2626] transition-colors shrink-0 w-6 text-center">
                                        0{{ $index + 1 }}
                                    </span>
                                    <div class="w-14 h-14 rounded-xl overflow-hidden bg-slate-900 shrink-0">
                                        <img src="{{ $storyImg }}" alt="{{ $storyTitle }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform">
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <span class="text-[10px] font-black uppercase tracking-wider text-[#DC2626] block">{{ $storyCat }}</span>
                                        <h4 class="text-xs sm:text-sm font-bold text-slate-800 dark:text-slate-200 group-hover:text-[#06B6D4] transition-colors line-clamp-2 leading-snug">
                                            {{ $storyTitle }}
                                        </h4>
                                        <span class="text-[10px] text-slate-400 mt-0.5 block">{{ $storyDate }}</span>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Mid-Page Ad Placement -->
        <x-ad-banner placement="home_middle" />

        <!-- Recent Studio Articles Grid -->
        <section class="space-y-6">
            <div class="flex items-center justify-between border-b border-slate-200 dark:border-slate-800 pb-4">
                <div>
                    <span class="text-xs font-black uppercase tracking-widest text-[#06B6D4]">Fresh Drops</span>
                    <h2 class="text-2xl sm:text-3xl font-black text-slate-900 dark:text-white tracking-tight">
                        Latest Insights &amp; Network Stories
                    </h2>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($recentBlogs as $blog)
                    <x-blog-card :blog="$blog" />
                @endforeach
            </div>
        </section>

        <!-- Viral Creator Dares Callout Banner -->
        <section class="relative overflow-hidden rounded-3xl p-8 sm:p-12 bg-gradient-to-r from-[#DC2626] via-[#B91C1C] to-[#06B6D4] text-white shadow-2xl flex flex-col md:flex-row items-center justify-between gap-8">
            <div class="space-y-3 max-w-xl text-center md:text-left">
                <span class="px-3 py-1 rounded-full text-xs font-black bg-black/40 text-[#06B6D4] uppercase tracking-wider inline-block">
                    ⚡ Creator Chemistry Test
                </span>
                <h3 class="text-2xl sm:text-3xl lg:text-4xl font-black leading-tight">
                    Dare Your Squad on WhatsApp!
                </h3>
                <p class="text-xs sm:text-sm text-white/90 leading-relaxed font-medium">
                    Pick your genuine creator answers, generate your secret challenge link, and see which friends or collaborators truly know you inside out!
                </p>
            </div>
            <a href="{{ route('quizzes.index') }}" class="px-8 py-4 rounded-2xl font-black text-sm sm:text-base bg-white text-slate-900 hover:bg-black hover:text-white shadow-2xl transition-all hover:scale-105 shrink-0 flex items-center gap-2">
                <span>Start A Creator Dare</span>
                <span>🚀</span>
            </a>
        </section>

        <!-- Creator Playbook Newsletter Box -->
        <x-newsletter-box />

    </div>
@endsection
