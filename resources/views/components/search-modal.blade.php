<div id="search-modal" 
     class="fixed inset-0 z-50 flex items-start justify-center pt-20 px-4 bg-black/70 backdrop-blur-sm opacity-0 pointer-events-none transition-all duration-200"
     onclick="if(event.target === this) window.closeSearchModal()">
    <div class="bg-white dark:bg-[#0F172A] rounded-3xl border border-slate-200 dark:border-slate-800 shadow-2xl w-full max-w-2xl transform scale-95 transition-all duration-200 overflow-hidden" id="search-modal-box">
        <form action="{{ route('search') }}" method="GET" class="p-4 sm:p-6 space-y-4">
            <div class="flex items-center gap-3 border-b border-slate-200 dark:border-slate-800 pb-4">
                <svg class="w-6 h-6 text-[#DC2626]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                <input type="text" 
                       id="search-modal-input" 
                       name="q" 
                       required 
                       placeholder="Search creator stories, viral tips, collab guides..."
                       class="w-full bg-transparent text-base sm:text-lg font-bold text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none">
                <button type="button" onclick="window.closeSearchModal()" class="p-1 rounded-xl text-slate-400 hover:text-slate-600 dark:hover:text-slate-200">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>

            <div class="flex items-center justify-between text-xs text-slate-400">
                <span>Press <kbd class="px-2 py-0.5 rounded bg-slate-100 dark:bg-slate-800 font-mono text-[10px] text-slate-600 dark:text-slate-300">Enter</kbd> to search</span>
                <span>Press <kbd class="px-2 py-0.5 rounded bg-slate-100 dark:bg-slate-800 font-mono text-[10px] text-slate-600 dark:text-slate-300">ESC</kbd> to exit</span>
            </div>
        </form>
    </div>
</div>

<script>
    window.openSearchModal = function() {
        const modal = document.getElementById('search-modal');
        const box = document.getElementById('search-modal-box');
        const input = document.getElementById('search-modal-input');
        if (modal && box) {
            modal.classList.remove('opacity-0', 'pointer-events-none');
            box.classList.remove('scale-95');
            box.classList.add('scale-100');
            setTimeout(() => input && input.focus(), 50);
        }
    };

    window.closeSearchModal = function() {
        const modal = document.getElementById('search-modal');
        const box = document.getElementById('search-modal-box');
        if (modal && box) {
            modal.classList.add('opacity-0', 'pointer-events-none');
            box.classList.remove('scale-100');
            box.classList.add('scale-95');
        }
    };

    document.addEventListener('keydown', function(e) {
        if ((e.metaKey || e.ctrlKey) && e.key === 'k') {
            e.preventDefault();
            window.openSearchModal();
        }
        if (e.key === 'Escape') {
            window.closeSearchModal();
        }
    });
</script>
