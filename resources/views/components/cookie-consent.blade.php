<div id="cookie-consent-banner" class="hidden fixed bottom-4 left-4 right-4 sm:left-auto sm:right-6 sm:max-w-md z-50 p-6 rounded-3xl bg-white/95 dark:bg-[#0F172A]/95 backdrop-blur-xl border border-slate-200 dark:border-slate-800 shadow-2xl space-y-4">
    <div class="flex items-start gap-3">
        <span class="text-2xl">🍪</span>
        <div class="space-y-1">
            <h4 class="text-sm font-black text-slate-900 dark:text-white">Creator Studio Cookie Notice</h4>
            <p class="text-xs text-slate-600 dark:text-slate-400 leading-relaxed">
                We use cookies to tailor analytics and preserve your theme choices. Read our <a href="{{ route('pages.cookies') }}" class="underline hover:text-[#DC2626]">Cookie Policy</a>.
            </p>
        </div>
    </div>

    <div class="flex items-center gap-2 pt-1">
        <button type="button" onclick="acceptCookies()" class="flex-1 py-2.5 px-4 rounded-xl bg-[#DC2626] hover:bg-[#B91C1C] text-white font-bold text-xs transition-colors shadow-sm">
            Accept All
        </button>
        <button type="button" onclick="rejectCookies()" class="px-4 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 font-bold text-xs transition-colors">
            Essential Only
        </button>
    </div>
</div>

<script>
    (function() {
        if (!localStorage.getItem('icn_cookie_consent')) {
            setTimeout(() => {
                const b = document.getElementById('cookie-consent-banner');
                if (b) b.classList.remove('hidden');
            }, 1000);
        }
        window.acceptCookies = function() {
            localStorage.setItem('icn_cookie_consent', 'accepted');
            document.getElementById('cookie-consent-banner').classList.add('hidden');
        };
        window.rejectCookies = function() {
            localStorage.setItem('icn_cookie_consent', 'essential');
            document.getElementById('cookie-consent-banner').classList.add('hidden');
        };
    })();
</script>
