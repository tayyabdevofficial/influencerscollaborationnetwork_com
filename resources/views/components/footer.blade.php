<footer class="bg-slate-900 text-slate-400 dark:bg-[#05080E] border-t border-slate-800 transition-colors duration-300">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-10">

            <!-- Brand Column -->
            <div class="lg:col-span-2 space-y-4">
                <a href="{{ route('home') }}" class="flex items-center gap-3">
                    <img src="{{ asset('logo.png') }}" alt="{{ config('site.name') }}" class="h-10 w-auto">
                    <div>
                        <span class="font-extrabold text-white text-base tracking-tight block">Influencers Collaboration</span>
                        <span class="text-xs text-[#06B6D4] font-semibold uppercase tracking-wider block">Network</span>
                    </div>
                </a>
                <p class="text-sm text-slate-400 leading-relaxed max-w-sm">
                    The premier hub for content creators, viral influencers, digital media entrepreneurs, and cutting-edge collaborative growth stories.
                </p>
                <div class="flex items-center gap-3 pt-2">
                    <span class="px-3 py-1 rounded-full bg-slate-800 text-[#06B6D4] text-xs font-bold border border-slate-700">
                        ⚡ 100% Real Creator Insights
                    </span>
                    <span class="px-3 py-1 rounded-full bg-slate-800 text-[#10B981] text-xs font-bold border border-slate-700">
                        📈 Global Network
                    </span>
                </div>
            </div>

            <!-- Quick Links -->
            <div class="space-y-3">
                <h4 class="text-xs font-black uppercase tracking-widest text-white">Explore</h4>
                <ul class="space-y-2 text-sm font-medium">
                    <li><a href="{{ route('home') }}" class="hover:text-white transition-colors">Home Studio</a></li>
                    <li><a href="{{ route('quizzes.index') }}" class="text-[#DC2626] hover:text-[#06B6D4] transition-colors font-bold">⚡ Creator Dares</a></li>
                    <li><a href="{{ route('blog.random') }}" class="hover:text-white transition-colors">Random Story</a></li>
                    <li><a href="{{ route('pages.about') }}" class="hover:text-white transition-colors">About Us</a></li>
                    <li><a href="{{ route('pages.contact') }}" class="hover:text-white transition-colors">Partner &amp; Collab</a></li>
                </ul>
            </div>

            <!-- Legal & Trust -->
            <div class="space-y-3">
                <h4 class="text-xs font-black uppercase tracking-widest text-white">Legal &amp; Trust</h4>
                <ul class="space-y-2 text-sm font-medium">
                    <li><a href="{{ route('pages.privacy') }}" class="hover:text-white transition-colors">Privacy Policy</a></li>
                    <li><a href="{{ route('pages.terms') }}" class="hover:text-white transition-colors">Terms of Service</a></li>
                    <li><a href="{{ route('pages.cookies') }}" class="hover:text-white transition-colors">Cookie Policy</a></li>
                    <li><a href="{{ route('sitemap') }}" class="hover:text-white transition-colors">XML Sitemap</a></li>
                </ul>
            </div>

            <!-- Newsletter Callout -->
            <div class="space-y-4">
                <h4 class="text-xs font-black uppercase tracking-widest text-white">Creator Briefing</h4>
                <p class="text-xs text-slate-400 leading-relaxed">
                    Get weekly algorithm leaks, influencer sponsorship trends, and growth breakdowns straight to your inbox.
                </p>
                <div id="footer-newsletter-msg" class="hidden p-3 rounded-xl text-xs font-bold"></div>
                <form id="footer-newsletter-form" action="{{ route('newsletter.subscribe') }}" method="POST" class="space-y-2">
                    @csrf
                    <input type="email" name="email" required placeholder="creator@channel.com"
                           class="w-full px-3.5 py-2.5 rounded-xl bg-slate-800 border border-slate-700 text-white placeholder-slate-500 text-xs focus:outline-none focus:ring-2 focus:ring-[#DC2626]">
                    <button type="submit" id="footer-newsletter-btn" class="w-full py-2.5 px-4 rounded-xl bg-gradient-to-r from-[#DC2626] to-[#06B6D4] text-white font-bold text-xs hover:opacity-90 transition-opacity">
                        Join The Network
                    </button>
                </form>
                <script>
                    document.addEventListener('DOMContentLoaded', () => {
                        const fnForm = document.getElementById('footer-newsletter-form');
                        const fnMsg = document.getElementById('footer-newsletter-msg');
                        const fnBtn = document.getElementById('footer-newsletter-btn');
                        if (fnForm) {
                            fnForm.addEventListener('submit', async (e) => {
                                e.preventDefault();
                                fnBtn.disabled = true;
                                const orig = fnBtn.textContent;
                                fnBtn.textContent = 'Joining...';
                                fnMsg.classList.add('hidden');
                                try {
                                    const fd = new FormData(fnForm);
                                    const res = await fetch(fnForm.action, {
                                        method: 'POST',
                                        body: fd,
                                        headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
                                    });
                                    const data = await res.json();
                                    if (res.ok && data.success !== false) {
                                        fnMsg.className = 'p-3 rounded-xl bg-emerald-950/60 border border-emerald-500/40 text-emerald-300 text-xs font-bold';
                                        fnMsg.textContent = data.message || 'Subscribed successfully!';
                                        fnMsg.classList.remove('hidden');
                                        fnForm.reset();
                                    } else {
                                        fnMsg.className = 'p-3 rounded-xl bg-rose-950/60 border border-rose-500/40 text-rose-300 text-xs font-bold';
                                        fnMsg.textContent = data.message || 'Unable to subscribe. Please try again.';
                                        fnMsg.classList.remove('hidden');
                                    }
                                } catch (err) {
                                    fnMsg.className = 'p-3 rounded-xl bg-rose-950/60 border border-rose-500/40 text-rose-300 text-xs font-bold';
                                    fnMsg.textContent = 'An unexpected error occurred. Please try again.';
                                    fnMsg.classList.remove('hidden');
                                } finally {
                                    fnBtn.disabled = false;
                                    fnBtn.textContent = orig;
                                }
                            });
                        }
                    });
                </script>
            </div>

        </div>

        <div class="mt-12 pt-8 border-t border-slate-800/80 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-slate-500">
            <div>
                &copy; {{ date('Y') }} {{ config('site.name') }}. All rights reserved.
            </div>
            <div class="flex items-center gap-4">
                <span>Designed for Modern Creators &amp; Storytellers</span>
            </div>
        </div>
    </div>
</footer>
