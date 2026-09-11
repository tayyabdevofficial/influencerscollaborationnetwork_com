@extends('layouts.app')

@php
    $searchTerm = $query ?? request()->get('q', '');
@endphp

@section('title', (!empty($searchTerm) ? 'Search: ' . e($searchTerm) : 'Search Creator Stories') . ' - ' . config('site.name'))
@section('meta_description', 'Search creator guides, influencer monetization breakdowns, and collaboration tips on ' . config('site.name') . '.')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-10">
    <!-- Search Banner -->
    <div class="rounded-3xl bg-white dark:bg-[#0F172A] border border-slate-200 dark:border-slate-800 p-8 sm:p-12 shadow-sm text-center max-w-3xl mx-auto space-y-6">
        <h1 class="text-2xl sm:text-4xl font-black text-slate-900 dark:text-white tracking-tight">
            Search The Creator Studio
        </h1>

        <form action="{{ route('search') }}" method="GET" class="relative max-w-xl mx-auto">
            <input type="search" 
                   name="q" 
                   value="{{ $searchTerm }}"
                   placeholder="Search growth hacks, sponsorships, viral hooks..." 
                   class="w-full rounded-2xl bg-slate-50 dark:bg-slate-800/80 py-4 pl-5 pr-28 text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#DC2626] font-medium text-base border border-slate-200 dark:border-slate-700">
            <button type="submit" class="absolute right-2 top-2 bottom-2 px-5 rounded-xl bg-gradient-to-r from-[#DC2626] to-[#06B6D4] text-white font-black text-xs sm:text-sm transition-all shadow-sm">
                Search
            </button>
        </form>

        @if(!empty($searchTerm))
            <p class="text-sm text-slate-500 dark:text-slate-400">
                Found stories matching <span class="font-bold text-[#DC2626]">&ldquo;{{ $searchTerm }}&rdquo;</span>
            </p>
        @endif
    </div>

    <!-- Horizontal Ad -->
    <x-ad-banner placement="horizontal_ad" />

    <!-- Results Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
        <div class="lg:col-span-8 space-y-8">
            @if(!empty($blogs) && count($blogs) > 0)
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    @foreach($blogs as $blog)
                        <x-blog-card :blog="$blog" />
                    @endforeach
                </div>
            @else
                <div class="text-center py-16 px-4 rounded-3xl bg-white dark:bg-[#0F172A] border border-slate-200 dark:border-slate-800 space-y-4">
                    <div class="w-16 h-16 rounded-full bg-slate-100 dark:bg-slate-800 text-slate-400 mx-auto flex items-center justify-center text-2xl">
                        🔍
                    </div>
                    <h3 class="text-lg font-black text-slate-900 dark:text-white">No creator stories found</h3>
                    <p class="text-sm text-slate-500 dark:text-slate-400 max-w-sm mx-auto">
                        We couldn't find any articles matching your search query. Try broader keywords or browse our categories.
                    </p>
                </div>
            @endif
        </div>

        <!-- Sidebar -->
        <aside class="lg:col-span-4 space-y-8">
            <div class="p-6 rounded-3xl bg-gradient-to-br from-[#0F172A] to-[#080C14] border border-slate-800 text-white shadow-lg space-y-4">
                <span class="text-3xl">🚀</span>
                <h4 class="text-base font-black text-white leading-tight">
                    Creator Dares &amp; Challenges
                </h4>
                <p class="text-xs text-slate-300 leading-relaxed">
                    Test your friends and followers with a custom dare link.
                </p>
                <a href="{{ route('quizzes.index') }}" class="block w-full text-center py-2.5 px-4 rounded-xl text-xs font-black bg-[#DC2626] hover:bg-[#B91C1C] text-white transition-colors">
                    Start A Challenge &rarr;
                </a>
            </div>

            <x-ad-banner placement="sidebar" />
        </aside>
    </div>
</div>
@endsection
