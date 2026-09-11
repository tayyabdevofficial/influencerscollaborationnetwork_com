@extends('layouts.app')

@section('title', 'Privacy Policy - ' . config('site.name'))
@section('meta_description', 'Privacy Policy for Influencers Collaboration Network detailing data collection, analytics, cookies, and protection measures.')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12 space-y-8">
    <div class="space-y-2 border-b border-slate-200 dark:border-slate-800 pb-6">
        <h1 class="text-3xl sm:text-4xl font-black text-slate-900 dark:text-white">Privacy Policy</h1>
        <p class="text-xs text-slate-400">Last updated: {{ date('F d, Y') }}</p>
    </div>

    <div class="prose space-y-6">
        <p>
            At <strong>{{ config('site.name') }}</strong> ("we," "our," or "us"), accessible from {{ config('site.domain') }}, we recognize the critical importance of protecting your privacy and personal data. This Privacy Policy details the types of information we gather and how it is applied.
        </p>

        <h3>1. Information We Collect</h3>
        <p>
            We collect information you explicitly provide to us when subscribing to our Creator Briefing newsletter, submitting comments on articles, completing contact inquiries, or participating in interactive creator dares and challenges.
        </p>

        <h3>2. Analytics &amp; Cookies</h3>
        <p>
            We use standard log files and local cookies to understand aggregate audience trends, track device preferences (such as dark mode selection), and monitor site health. We do not sell your personal information to third parties.
        </p>

        <h3>3. Viral Quizzes &amp; Dares Data</h3>
        <p>
            When creating or participating in viral challenge quizzes, user answers and display names are processed solely to compute match scores and render live challenge leaderboards. No private messages or credentials are required or stored.
        </p>

        <h3>4. Security of Your Data</h3>
        <p>
            We implement industry-standard cryptographic measures, secure proxied media endpoints, and protected token handshakes to ensure the utmost safety of all transmitted information.
        </p>

        <h3>5. Contact Us</h3>
        <p>
            If you have questions regarding this Privacy Policy, please reach out via our <a href="{{ route('pages.contact') }}">Contact Page</a>.
        </p>
    </div>
</div>
@endsection
