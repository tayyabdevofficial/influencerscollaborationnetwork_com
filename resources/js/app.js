// Influencers Collaboration Network Theme Management (Default Auto with System Match)
window.toggleTheme = function () {
    const isDark = document.documentElement.classList.toggle('dark');
    localStorage.setItem('icn_theme', isDark ? 'dark' : 'light');
    updateThemeIcons();
};

function updateThemeIcons() {
    const isDark = document.documentElement.classList.contains('dark');
    document.querySelectorAll('.theme-sun-icon').forEach(el => {
        el.style.display = isDark ? 'block' : 'none';
    });
    document.querySelectorAll('.theme-moon-icon').forEach(el => {
        el.style.display = isDark ? 'none' : 'block';
    });
}

// Auto update according to system if user has not set manual preference
try {
    window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', e => {
        if (!localStorage.getItem('icn_theme')) {
            if (e.matches) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
            updateThemeIcons();
        }
    });
} catch (e) {}

// Copy story link utility with toast
window.copyCreatorLink = function (url) {
    navigator.clipboard.writeText(url || window.location.href).then(() => {
        showCreatorToast('Article link copied to clipboard!');
    }).catch(() => {
        showCreatorToast('Could not copy link.');
    });
};

window.showCreatorToast = function (message, isError = false) {
    let toast = document.getElementById('creator-toast');
    if (!toast) {
        toast = document.createElement('div');
        toast.id = 'creator-toast';
        toast.className = 'fixed bottom-6 right-6 z-50 px-5 py-3 rounded-2xl shadow-2xl transition-all duration-300 transform translate-y-12 opacity-0 flex items-center gap-3 text-sm font-semibold';
        document.body.appendChild(toast);
    }
    toast.className = `fixed bottom-6 right-6 z-50 px-5 py-3 rounded-2xl shadow-2xl transition-all duration-300 transform translate-y-12 opacity-0 flex items-center gap-3 text-sm font-semibold ${
        isError ? 'bg-red-700 text-white' : 'bg-slate-900 text-white dark:bg-white dark:text-slate-900 border border-slate-700/50'
    }`;
    toast.textContent = message;
    toast.classList.remove('translate-y-12', 'opacity-0');
    toast.classList.add('translate-y-0', 'opacity-100');

    setTimeout(() => {
        toast.classList.remove('translate-y-0', 'opacity-100');
        toast.classList.add('translate-y-12', 'opacity-0');
    }, 3200);
};

// Search modal helper functions
window.openSearchModal = function () {
    const modal = document.getElementById('search-modal');
    if (modal) {
        modal.classList.remove('hidden');
        document.getElementById('modal-search-input')?.focus();
    }
};

window.closeSearchModal = function () {
    const modal = document.getElementById('search-modal');
    if (modal) {
        modal.classList.add('hidden');
    }
};

// Animated Mobile Nav Drawer Helpers
window.openMobileNav = function () {
    const backdrop = document.getElementById('mobile-nav-backdrop');
    const drawer = document.getElementById('mobile-nav-drawer');
    backdrop?.classList.remove('opacity-0', 'pointer-events-none');
    backdrop?.classList.add('opacity-100', 'pointer-events-auto');
    drawer?.classList.remove('translate-x-full');
    drawer?.classList.add('translate-x-0');
    document.body.style.overflow = 'hidden';
};

window.closeMobileNav = function () {
    const backdrop = document.getElementById('mobile-nav-backdrop');
    const drawer = document.getElementById('mobile-nav-drawer');
    backdrop?.classList.remove('opacity-100', 'pointer-events-auto');
    backdrop?.classList.add('opacity-0', 'pointer-events-none');
    drawer?.classList.remove('translate-x-0');
    drawer?.classList.add('translate-x-full');
    document.body.style.overflow = '';
};

window.toggleMobileAccordion = function (id, btn) {
    const el = document.getElementById(id);
    const icon = btn?.querySelector('svg');
    if (el) {
        el.classList.toggle('hidden');
        if (icon) {
            icon.classList.toggle('rotate-180');
        }
    }
};

document.addEventListener('DOMContentLoaded', () => {
    updateThemeIcons();

    // Search keyboard shortcut (Ctrl+K or Cmd+K)
    window.addEventListener('keydown', (e) => {
        if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
            e.preventDefault();
            const modal = document.getElementById('search-modal');
            if (modal) {
                if (modal.classList.contains('hidden')) {
                    window.openSearchModal();
                } else {
                    window.closeSearchModal();
                }
            }
        }
        if (e.key === 'Escape') {
            window.closeSearchModal();
            window.closeMobileNav();
        }
    });
});
