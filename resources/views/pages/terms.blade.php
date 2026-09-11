@extends('layouts.app')

@section('title', 'Terms & Conditions - ' . config('site.name'))
@section('meta_description', 'Terms and conditions for browsing and participating in Influencers Collaboration Network.')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12 space-y-8">
    <div class="space-y-2 border-b border-slate-200 dark:border-slate-800 pb-6">
        <h1 class="text-3xl sm:text-4xl font-black text-slate-900 dark:text-white">Terms &amp; Conditions</h1>
        <p class="text-xs text-slate-400">Last updated: {{ date('F d, Y') }}</p>
    </div>

    <div class="prose space-y-6">
        <p>
            Welcome to <strong>{{ config('site.name') }}</strong>. By accessing our platform and services, you agree to comply with and be bound by the following terms and conditions.
        </p>

        <h3>1. Acceptance of Terms</h3>
        <p>
            Your access to and use of this website is conditioned upon your acceptance of and compliance with these Terms. These apply to all visitors, users, and others who access or use the service.
        </p>

        <h3>2. Content &amp; Intellectual Property</h3>
        <p>
            All original articles, editorial analyses, branding, logos, and visual assets are the intellectual property of {{ config('site.name') }} or its respective contributors and are protected by applicable copyright and trademark laws.
        </p>

        <h3>3. Community Guidelines &amp; Comments</h3>
        <p>
            Users agree not to post defamatory, offensive, unlawful, or harmful material in our discussion sections. We reserve the right to moderate, edit, or remove any comments at our sole discretion.
        </p>

        <h3>4. Disclaimer</h3>
        <p>
            The creator guides, business analyses, and growth strategies provided on this website are for informational and educational purposes only. Results vary based on individual effort and execution.
        </p>

        <h3>5. Modifications</h3>
        <p>
            We reserve the right to revise or replace these Terms at any time without prior notice.
        </p>
    </div>
</div>
@endsection
