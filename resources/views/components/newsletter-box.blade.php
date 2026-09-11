<div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-[#0F172A] via-[#131D33] to-[#080C14] border border-slate-800 text-white p-8 sm:p-12 shadow-2xl">
    <!-- Cyber Neon Glow Backdrops -->
    <div class="absolute -right-10 -top-10 w-64 h-64 bg-[#DC2626]/20 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute -left-10 -bottom-10 w-64 h-64 bg-[#06B6D4]/20 rounded-full blur-3xl pointer-events-none"></div>

    <div class="relative z-10 max-w-2xl mx-auto text-center space-y-6">
        <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-white/10 backdrop-blur-md border border-white/20 text-xs font-black uppercase tracking-widest text-[#06B6D4]">
            <span>⚡</span> VIP Creator Network
        </div>

        <h2 class="text-3xl sm:text-4xl font-black tracking-tight text-white">
            Level Up Your Creator Playbook
        </h2>

        <p class="text-sm sm:text-base text-slate-300 leading-relaxed max-w-lg mx-auto">
            Get exclusive creator breakdowns, brand sponsorship rates, viral hook tactics, and collaboration invitations delivered weekly.
        </p>

        <div id="newsletter-msg" class="hidden p-4 rounded-2xl text-sm font-bold"></div>

        <form id="newsletter-form" action="{{ route('newsletter.subscribe') }}" method="POST" class="flex flex-col sm:flex-row gap-3 max-w-md mx-auto">
            @csrf
            <input type="email" 
                   name="email" 
                   required 
                   placeholder="Enter your creator email..."
                   class="flex-1 px-5 py-3.5 rounded-2xl bg-slate-900/90 border border-slate-700 text-white placeholder-slate-400 text-sm focus:outline-none focus:ring-2 focus:ring-[#DC2626] transition-all">
            <button type="submit" 
                    id="newsletter-btn"
                    class="px-7 py-3.5 rounded-2xl bg-gradient-to-r from-[#DC2626] to-[#06B6D4] text-white font-black text-sm shadow-lg hover:shadow-xl hover:scale-105 active:scale-95 transition-all">
                Subscribe
            </button>
        </form>

        <div class="text-[11px] text-slate-500 font-medium">
            Join 45,000+ creators • No spam, unsubscribe anytime
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const nForm = document.getElementById('newsletter-form');
        const nMsg = document.getElementById('newsletter-msg');
        const nBtn = document.getElementById('newsletter-btn');

        if (nForm) {
            nForm.addEventListener('submit', async (e) => {
                e.preventDefault();
                nBtn.disabled = true;
                const oldText = nBtn.textContent;
                nBtn.textContent = 'Subscribing...';
                nMsg.classList.add('hidden');

                try {
                    const formData = new FormData(nForm);
                    const res = await fetch(nForm.action, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json',
                        }
                    });

                    const data = await res.json();
                    if (res.ok && data.success !== false) {
                        nMsg.className = 'p-4 rounded-2xl bg-emerald-950/60 border border-emerald-500/40 text-emerald-300 text-sm font-bold flex items-center justify-center gap-2';
                        nMsg.innerHTML = `<span>🎉</span> <span>${data.message || 'Thank you for subscribing to Creator Studio Insider!'}</span>`;
                        nMsg.classList.remove('hidden');
                        nForm.reset();
                    } else {
                        nMsg.className = 'p-4 rounded-2xl bg-rose-950/60 border border-rose-500/40 text-rose-300 text-sm font-bold';
                        nMsg.textContent = data.message || 'Unable to subscribe. Please try again.';
                        nMsg.classList.remove('hidden');
                    }
                } catch (err) {
                    nMsg.className = 'p-4 rounded-2xl bg-rose-950/60 border border-rose-500/40 text-rose-300 text-sm font-bold';
                    nMsg.textContent = 'An unexpected error occurred. Please try again later.';
                    nMsg.classList.remove('hidden');
                } finally {
                    nBtn.disabled = false;
                    nBtn.textContent = oldText;
                }
            });
        }
    });
</script>
