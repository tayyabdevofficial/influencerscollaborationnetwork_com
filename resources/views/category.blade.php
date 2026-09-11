@extends('layouts.app')

@php
    $catName = $category['name'] ?? 'Category';
    $catDesc = $category['description'] ?? 'Explore articles and insights in ' . $catName . '.';
    $subCategories = $subCategories ?? $category['sub_categories'] ?? [];
    $catImage = blogger_media_url($category['thumbnail'] ?? null);
@endphp

@section('title', $catName . ' - ' . config('site.name'))
@section('meta_description', $catDesc)

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-10">
    <!-- Category Hero Header -->
    <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-[#0F172A] via-[#131D33] to-[#080C14] border border-slate-800 text-white p-8 sm:p-12 shadow-xl">
        <div class="max-w-2xl space-y-3">
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-black uppercase tracking-wider bg-[#DC2626] text-white">
                {{ $isSubCategory ? 'Sub-Niche' : 'Category Hub' }}
            </span>
            <h1 class="text-3xl sm:text-5xl font-black tracking-tight leading-tight">
                {{ $catName }}
            </h1>
            <p class="text-sm sm:text-base text-slate-300 leading-relaxed">
                {{ $catDesc }}
            </p>
        </div>

        <!-- Subcategories Filter Pills -->
        @if(!empty($subCategories) && count($subCategories) > 0)
            <div class="mt-8 pt-6 border-t border-slate-800 flex items-center gap-2 flex-wrap">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400 mr-2">Niche Channels:</span>
                @foreach($subCategories as $sub)
                    <a href="{{ route('subcategory.show', $sub['slug'] ?? '#') }}" class="px-3.5 py-1.5 rounded-full text-xs font-bold bg-slate-800 hover:bg-[#DC2626] text-white transition-all">
                        {{ $sub['name'] }}
                    </a>
                @endforeach
            </div>
        @endif
    </div>

    <!-- Main Content & Sidebar -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
        <!-- Articles Grid (8 cols on lg) -->
        <div class="lg:col-span-8 space-y-8">
            @if(!empty($blogs) && count($blogs) > 0)
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    @foreach($blogs as $blog)
                        <x-blog-card :blog="$blog" />
                    @endforeach
                </div>
            @else
                <div class="text-center py-16 bg-white dark:bg-[#0F172A] rounded-3xl border border-slate-200 dark:border-slate-800 p-8 space-y-3">
                    <div class="text-4xl">⚡</div>
                    <h3 class="text-lg font-black text-slate-900 dark:text-white">No Stories in this Channel Yet</h3>
                    <p class="text-xs sm:text-sm text-slate-400">Our creators are putting the finishing touches on new articles. Check back soon!</p>
                </div>
            @endif
        </div>

        <!-- Sidebar (4 cols on lg) -->
        <aside class="lg:col-span-4 space-y-8">
            <!-- Collab Challenge Card -->
            <div class="p-6 rounded-3xl bg-gradient-to-br from-[#0F172A] to-[#080C14] border border-slate-800 text-white shadow-lg space-y-4">
                <span class="text-3xl">🔥</span>
                <h4 class="text-base font-black text-white leading-tight">
                    Dare Your Creator Friends
                </h4>
                <p class="text-xs text-slate-300 leading-relaxed">
                    Create a personalized creator quiz, share it to WhatsApp or Discord, and see who gets 100%!
                </p>
                <a href="{{ route('quizzes.index') }}" class="block w-full text-center py-2.5 px-4 rounded-xl text-xs font-black bg-[#DC2626] hover:bg-[#B91C1C] text-white transition-colors">
                    Start A Challenge &rarr;
                </a>
            </div>

            <!-- Sidebar Ad Placement -->
            <x-ad-banner placement="sidebar" />
        </aside>
    </div>
</div>
@endsection
