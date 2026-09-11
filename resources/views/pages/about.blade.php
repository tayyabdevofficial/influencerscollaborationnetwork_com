@extends('layouts.app')

@section('title', 'About Us - ' . config('site.name'))
@section('meta_description', 'Learn about Influencers Collaboration Network, our mission to empower digital creators, and how we foster collaborative media ecosystems.')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12 space-y-10">
    <div class="text-center space-y-4">
        <span class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full text-xs font-black uppercase tracking-wider bg-[#DC2626] text-white">
            <span>⚡</span> The Creator Ecosystem
        </span>
        <h1 class="text-3xl sm:text-5xl font-black text-slate-900 dark:text-white tracking-tight leading-tight">
            Empowering Digital Creators &amp; Storytellers
        </h1>
        <p class="text-base sm:text-lg text-slate-600 dark:text-slate-300 max-w-2xl mx-auto leading-relaxed font-medium">
            Influencers Collaboration Network connects creators, digital storytellers, and media entrepreneurs to scale their voice, share strategies, and forge impactful brand partnerships.
        </p>
    </div>

    <!-- Pillars Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 pt-4">
        <div class="p-6 rounded-3xl bg-white dark:bg-[#0F172A] border border-slate-200 dark:border-slate-800 shadow-sm space-y-3">
            <div class="w-12 h-12 rounded-2xl bg-red-100 dark:bg-red-950/80 text-[#DC2626] flex items-center justify-center text-2xl font-black">
                🚀
            </div>
            <h3 class="text-lg font-bold text-slate-900 dark:text-white">Growth &amp; Scale</h3>
            <p class="text-xs text-slate-600 dark:text-slate-400 leading-relaxed">
                Actionable tactics on algorithm shifts, audience retention, and high-impact multi-platform syndication.
            </p>
        </div>

        <div class="p-6 rounded-3xl bg-white dark:bg-[#0F172A] border border-slate-200 dark:border-slate-800 shadow-sm space-y-3">
            <div class="w-12 h-12 rounded-2xl bg-cyan-100 dark:bg-cyan-950/80 text-[#06B6D4] flex items-center justify-center text-2xl font-black">
                🤝
            </div>
            <h3 class="text-lg font-bold text-slate-900 dark:text-white">Collaborations</h3>
            <p class="text-xs text-slate-600 dark:text-slate-400 leading-relaxed">
                Facilitating organic cross-creator partnerships, podcast co-hosting, and collaborative storytelling campaigns.
            </p>
        </div>

        <div class="p-6 rounded-3xl bg-white dark:bg-[#0F172A] border border-slate-200 dark:border-slate-800 shadow-sm space-y-3">
            <div class="w-12 h-12 rounded-2xl bg-emerald-100 dark:bg-emerald-950/80 text-[#10B981] flex items-center justify-center text-2xl font-black">
                💎
            </div>
            <h3 class="text-lg font-bold text-slate-900 dark:text-white">Monetization</h3>
            <p class="text-xs text-slate-600 dark:text-slate-400 leading-relaxed">
                Transparent sponsor rate cards, digital merchandise blueprints, and sustainable business foundations.
            </p>
        </div>
    </div>

    <!-- Editorial Integrity Card -->
    <div class="p-8 sm:p-10 rounded-3xl bg-slate-900 border border-slate-800 text-white space-y-4 shadow-xl">
        <h2 class="text-xl sm:text-2xl font-black">Our Creator Commitment</h2>
        <p class="text-sm text-slate-300 leading-relaxed">
            Every story, guide, and breakdown published on Influencers Collaboration Network is thoroughly researched, practitioner-backed, and optimized for creators looking to build generational media presence.
        </p>
    </div>
</div>
@endsection
