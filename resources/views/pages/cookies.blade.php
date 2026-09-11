@extends('layouts.app')

@section('title', 'Cookie Policy - ' . config('site.name'))
@section('meta_description', 'Learn about how Influencers Collaboration Network uses cookies to optimize creator workflows.')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12 space-y-8">
    <div class="space-y-2 border-b border-slate-200 dark:border-slate-800 pb-6">
        <h1 class="text-3xl sm:text-4xl font-black text-slate-900 dark:text-white">Cookie Policy</h1>
        <p class="text-xs text-slate-400">Last updated: {{ date('F d, Y') }}</p>
    </div>

    <div class="prose space-y-6">
        <p>
            This Cookie Policy explains what cookies are, how <strong>{{ config('site.name') }}</strong> uses cookies and similar technologies, and what choices you have regarding them.
        </p>

        <h3>1. What Are Cookies?</h3>
        <p>
            Cookies are small text files placed on your device by websites you visit. They are widely used to make websites work properly, provide analytics, and save your personal preferences.
        </p>

        <h3>2. Cookies We Use</h3>
        <ul>
            <li><strong>Essential Cookies:</strong> Required to operate core features, retain session states, and prevent security vulnerabilities.</li>
            <li><strong>Preference Cookies:</strong> Remember your choices such as your selected display theme (Light / Dark / System default).</li>
            <li><strong>Analytics Cookies:</strong> Help us measure aggregate page views, popular creator topics, and traffic origins.</li>
        </ul>

        <h3>3. Managing Cookies</h3>
        <p>
            You can modify your browser settings to decline or clear cookies at any time. Please note that disabling cookies may affect certain interactive features such as theme remembering or challenge scoreboard tracking.
        </p>
    </div>
</div>
@endsection
