@extends('layouts.app')

@section('title', 'Partner & Collab - ' . config('site.name'))
@section('meta_description', 'Contact the Influencers Collaboration Network team for sponsorship inquiries, brand partnerships, or creator guest spotlights.')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12 space-y-10">
    <div class="text-center space-y-3">
        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-black uppercase tracking-wider bg-[#DC2626] text-white">
            <span>🤝</span> Partner With Us
        </span>
        <h1 class="text-3xl sm:text-5xl font-black text-slate-900 dark:text-white tracking-tight">
            Let's Build Something Viral Together
        </h1>
        <p class="text-sm sm:text-base text-slate-600 dark:text-slate-400 max-w-lg mx-auto">
            Got an editorial pitch, brand sponsorship proposal, or collaboration inquiry? Drop our studio a message below.
        </p>
    </div>

    <div class="bg-white dark:bg-[#0F172A] rounded-3xl border border-slate-200 dark:border-slate-800 p-6 sm:p-10 shadow-sm">
        <div id="contact-alert" class="hidden mb-6 p-4 rounded-2xl text-sm font-bold flex items-center gap-2"></div>

        <form id="contact-form" action="{{ route('pages.contact.submit') }}" method="POST" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-black uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-2">
                        Your Name / Brand Contact <span class="text-[#DC2626]">*</span>
                    </label>
                    <input type="text" name="name" required placeholder="Alex Morgan"
                           class="w-full px-4 py-3.5 rounded-2xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-[#DC2626]">
                </div>

                <div>
                    <label class="block text-xs font-black uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-2">
                        Email Address <span class="text-[#DC2626]">*</span>
                    </label>
                    <input type="email" name="email" required placeholder="alex@agency.com"
                           class="w-full px-4 py-3.5 rounded-2xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-[#DC2626]">
                </div>
            </div>

            <div>
                <label class="block text-xs font-black uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-2">
                    Subject / Channel Link
                </label>
                <input type="text" name="subject" placeholder="Sponsorship proposal / Creator spotlight request"
                       class="w-full px-4 py-3.5 rounded-2xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-[#DC2626]">
            </div>

            <div>
                <label class="block text-xs font-black uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-2">
                    Message Details <span class="text-[#DC2626]">*</span>
                </label>
                <textarea name="message" rows="5" required placeholder="Tell us about your project, reach, deliverables, or questions..."
                          class="w-full px-4 py-3.5 rounded-2xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-[#DC2626]"></textarea>
            </div>

            <button type="submit" id="contact-btn" class="px-8 py-4 rounded-2xl bg-gradient-to-r from-[#DC2626] to-[#06B6D4] text-white font-black text-sm shadow-lg hover:shadow-xl hover:scale-105 active:scale-95 transition-all inline-flex items-center gap-2">
                <span>Send Collaboration Message</span>
            </button>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const cForm = document.getElementById('contact-form');
        const cAlert = document.getElementById('contact-alert');
        const cBtn = document.getElementById('contact-btn');

        if (cForm) {
            cForm.addEventListener('submit', async (e) => {
                e.preventDefault();
                cBtn.disabled = true;
                const oldText = cBtn.innerHTML;
                cBtn.innerHTML = '<span>Sending...</span>';
                cAlert.classList.add('hidden');

                try {
                    const formData = new FormData(cForm);
                    const res = await fetch(cForm.action, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json',
                        }
                    });

                    const data = await res.json();
                    if (res.ok && data.success !== false) {
                        cAlert.className = 'mb-6 p-4 rounded-2xl bg-emerald-50 dark:bg-emerald-950/50 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-300 text-sm font-bold flex items-center gap-2';
                        cAlert.innerHTML = `<span>✨</span> <span>${data.message || 'Your message has been received! Our collaboration team will get back to you shortly.'}</span>`;
                        cAlert.classList.remove('hidden');
                        cForm.reset();
                    } else {
                        cAlert.className = 'mb-6 p-4 rounded-2xl bg-rose-50 dark:bg-rose-950/50 border border-rose-200 dark:border-rose-800 text-rose-800 dark:text-rose-300 text-sm font-bold flex items-center gap-2';
                        const errMsg = data.message || (data.errors ? Object.values(data.errors).flat()[0] : 'Unable to send message. Please check the fields.');
                        cAlert.innerHTML = `<span>⚠️</span> <span>${errMsg}</span>`;
                        cAlert.classList.remove('hidden');
                    }
                } catch (err) {
                    cAlert.className = 'mb-6 p-4 rounded-2xl bg-rose-50 dark:bg-rose-950/50 border border-rose-200 dark:border-rose-800 text-rose-800 dark:text-rose-300 text-sm font-bold flex items-center gap-2';
                    cAlert.innerHTML = `<span>⚠️</span> <span>An unexpected error occurred while sending your message. Please try again.</span>`;
                    cAlert.classList.remove('hidden');
                } finally {
                    cBtn.disabled = false;
                    cBtn.innerHTML = oldText;
                }
            });
        }
    });
</script>
@endsection
