@extends('layouts.app')

@section('title', 'Creator Quizzes Unavailable - ' . config('site.name'))

@section('content')
<div class="min-h-[70vh] flex items-center justify-center px-4 py-16">
    <div class="max-w-md w-full text-center space-y-6 bg-white dark:bg-[#0F172A] p-8 sm:p-10 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-xl">
        <div class="w-20 h-20 mx-auto rounded-3xl bg-red-100 dark:bg-red-950/80 text-[#DC2626] flex items-center justify-center text-4xl shadow-inner animate-pulse">
            ⚡
        </div>

        <div class="space-y-2">
            <h1 class="text-2xl font-black text-slate-900 dark:text-white">
                Creator Challenges Offline
            </h1>
            <p class="text-sm text-slate-600 dark:text-slate-400 leading-relaxed">
                {{ $message ?? 'Creator dares and quizzes are currently undergoing scheduled upgrades or disabled by the studio administration.' }}
            </p>
        </div>

        <div class="pt-2">
            <a href="{{ route('home') }}" class="inline-flex items-center gap-2 px-6 py-3.5 rounded-2xl bg-[#DC2626] hover:bg-[#B91C1C] text-white text-xs font-black uppercase tracking-wider transition-all shadow-md">
                <span>&larr; Back to Creator Home</span>
            </a>
        </div>
    </div>
</div>
@endsection
