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

// Dynamic Table of Contents with smooth Auto-Scrolling Sidebar
function initTableOfContents() {
    const tocContainer = document.getElementById('table-of-contents-list');
    const articleBody = document.getElementById('blog-article-content') || document.querySelector('.prose-content') || document.querySelector('.prose');
    if (!tocContainer || !articleBody) return;

    const headings = Array.from(articleBody.querySelectorAll('h2, h3, h4'));
    if (headings.length < 2) {
        const tocWrapper = document.getElementById('table-of-contents-wrapper');
        if (tocWrapper) tocWrapper.style.display = 'none';
        return;
    }

    tocContainer.innerHTML = '';
    const items = [];

    headings.forEach((heading, index) => {
        let id = heading.id;
        if (!id) {
            id = heading.textContent
                .trim()
                .toLowerCase()
                .replace(/[^a-z0-9]+/g, '-')
                .replace(/(^-|-$)/g, '') || `section-${index}`;
            heading.id = id;
        }

        const tag = heading.tagName.toUpperCase();
        const isH3 = tag === 'H3';
        const isH4 = tag === 'H4';
        const li = document.createElement('li');
        li.className = 'relative';

        const a = document.createElement('a');
        a.href = `#${id}`;
        a.textContent = heading.textContent.trim();
        a.dataset.targetId = id;
        
        let indentClasses = 'pl-2.5 pr-2 font-medium text-slate-700 dark:text-slate-300 hover:text-[#DC2626] dark:hover:text-[#06B6D4]';
        if (isH3) {
            indentClasses = 'pl-5 pr-2 text-xs text-slate-500 dark:text-slate-400 hover:text-[#DC2626] dark:hover:text-[#06B6D4]';
        } else if (isH4) {
            indentClasses = 'pl-7 pr-2 text-[11px] text-slate-400 dark:text-slate-500 hover:text-[#DC2626] dark:hover:text-[#06B6D4]';
        }

        a.className = `toc-link block py-1.5 transition-all text-xs sm:text-sm rounded-lg leading-snug ${indentClasses} line-clamp-2`;

        a.addEventListener('click', (e) => {
            e.preventDefault();
            const target = document.getElementById(id);
            if (target) {
                target.scrollIntoView({ behavior: 'smooth' });
                history.pushState(null, null, `#${id}`);
                setActiveLink(id, true);
            }
        });

        li.appendChild(a);
        tocContainer.appendChild(li);
        items.push({ id, heading, link: a, isH3, isH4 });
    });

    let currentActiveId = null;

    function scrollTocToActive(activeLink) {
        if (!activeLink || !tocContainer) return;
        if (items.length > 0 && items[0].link === activeLink) {
            tocContainer.scrollTo({ top: 0, behavior: 'smooth' });
            return;
        }

        const containerRect = tocContainer.getBoundingClientRect();
        const linkRect = activeLink.getBoundingClientRect();
        const currentScroll = tocContainer.scrollTop;
        const relativeTop = linkRect.top - containerRect.top;
        const targetScroll = currentScroll + relativeTop - (containerRect.height / 2) + (linkRect.height / 2);

        tocContainer.scrollTo({
            top: Math.max(0, targetScroll),
            behavior: 'smooth'
        });
    }

    function setActiveLink(activeId, forceScroll = false) {
        if (currentActiveId === activeId && !forceScroll) return;
        currentActiveId = activeId;

        let activeLink = null;
        items.forEach(({ id, link, isH3, isH4 }) => {
            if (id === activeId) {
                activeLink = link;
                link.classList.add('text-[#DC2626]', 'dark:text-[#06B6D4]', 'font-bold', 'bg-red-50', 'dark:bg-slate-800');
                link.classList.remove('text-slate-400', 'text-slate-500', 'text-slate-700', 'dark:text-slate-500', 'dark:text-slate-400', 'dark:text-slate-300');
            } else {
                link.classList.remove('text-[#DC2626]', 'dark:text-[#06B6D4]', 'font-bold', 'bg-red-50', 'dark:bg-slate-800');
                if (isH4) {
                    link.classList.add('text-slate-400', 'dark:text-slate-500');
                } else if (isH3) {
                    link.classList.add('text-slate-500', 'dark:text-slate-400');
                } else {
                    link.classList.add('text-slate-700', 'dark:text-slate-300');
                }
            }
        });

        if (activeLink) {
            scrollTocToActive(activeLink);
        }
    }

    let isTicking = false;
    function updateActiveOnScroll() {
        if (items.length === 0) return;
        const scrollY = window.scrollY;
        let activeHeading = items[0];

        for (let i = 0; i < items.length; i++) {
            const headingTop = items[i].heading.getBoundingClientRect().top + scrollY - 140;
            if (scrollY >= headingTop) {
                activeHeading = items[i];
            } else {
                break;
            }
        }

        if (activeHeading) {
            setActiveLink(activeHeading.id);
        }
        isTicking = false;
    }

    window.addEventListener('scroll', () => {
        if (!isTicking) {
            window.requestAnimationFrame(updateActiveOnScroll);
            isTicking = true;
        }
    }, { passive: true });

    updateActiveOnScroll();
}

document.addEventListener('DOMContentLoaded', () => {
    updateThemeIcons();
    initTableOfContents();

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
