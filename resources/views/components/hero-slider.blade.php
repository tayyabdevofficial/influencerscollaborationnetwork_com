@props(['blogs' => []])

@if(!empty($blogs) && count($blogs) > 0)
<div class="relative w-full h-[380px] sm:h-[460px] lg:h-[500px] overflow-hidden rounded-3xl bg-slate-900 border border-slate-800 shadow-2xl" id="hero-slider">
    @foreach($blogs as $index => $blog)
        @php
            $rawHeroImage = $blog['image_850x500'] ?? $blog['image_1150x900'] ?? $blog['image_500x500'] ?? $blog['image_400x300'] ?? $blog['image'] ?? $blog['image_url'] ?? null;
            $mainImage850  = blogger_media_url(!empty($blog['image_850x500']) ? $blog['image_850x500'] : $rawHeroImage, '/images/placeholder.svg', 850);
            $mainImage700  = blogger_media_url($rawHeroImage, '/images/placeholder.svg', 700);
            $categoryName = $blog['category']['name'] ?? $blog['category_name'] ?? 'Spotlight';
            $slug = $blog['slug'] ?? '#';
            $title = $blog['title'] ?? '';
            $date = blogger_format_date($blog['created_at'] ?? $blog['publish_date'] ?? now());
            $viewsCount = $blog['views_count'] ?? (is_array($blog['views'] ?? null) ? count($blog['views']) : ($blog['views'] ?? 0));
        @endphp
        <div class="slider-slide absolute inset-0 @if($index !== 0) transition-opacity duration-700 ease-in-out opacity-0 pointer-events-none z-0 @else opacity-100 z-10 @endif" data-slide="{{ $index }}" aria-hidden="{{ $index === 0 ? 'false' : 'true' }}" @if($index !== 0) inert @endif>
            <img src="{{ $mainImage850 }}" 
                 srcset="{{ $mainImage700 }} 700w, {{ $mainImage850 }} 850w"
                 sizes="(max-width: 640px) 100vw, 850px"
                 alt="{{ $title }}" 
                 @if($index === 0) fetchpriority="high" loading="eager" decoding="sync" @else loading="lazy" decoding="async" @endif 
                 width="850" 
                 height="500" 
                 class="w-full h-full object-cover">
                <!-- Dual Legibility Scrims -->
                <div class="absolute inset-0 bg-gradient-to-t from-[#080C14] via-[#080C14]/70 to-transparent"></div>
                <div class="absolute inset-0 bg-gradient-to-r from-[#080C14]/90 via-[#080C14]/40 to-transparent"></div>

                <div class="absolute bottom-0 left-0 right-0 p-6 sm:p-10 lg:p-14 z-20 max-w-3xl space-y-4">
                    <div class="flex items-center gap-3 flex-wrap">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-black uppercase tracking-wider bg-[#DC2626] text-white shadow-lg">
                            <span>🔥</span> {{ $categoryName }}
                        </span>
                        
                        <!-- Views Count Pill -->
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-black/75 text-white border border-white/10">
                            <svg class="w-3.5 h-3.5 text-[#06B6D4]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            <span>{{ number_format((int)$viewsCount) }} {{ Str::plural('view', (int)$viewsCount) }}</span>
                        </span>

                        <span class="text-xs font-semibold text-slate-300">{{ $date }}</span>
                    </div>

                    <h2 class="text-2xl sm:text-4xl lg:text-5xl font-black text-white leading-tight drop-shadow-md">
                        <a href="{{ route('blog.show', $slug) }}" @if($index !== 0) tabindex="-1" @endif class="hover:text-[#06B6D4] transition-colors">
                            {{ $title }}
                        </a>
                    </h2>

                    <div class="pt-2 flex items-center gap-4">
                        <a href="{{ route('blog.show', $slug) }}" 
                           aria-label="Read Full Story: {{ $title }}"
                           @if($index !== 0) tabindex="-1" @endif
                           class="px-6 py-3 rounded-2xl bg-gradient-to-r from-[#DC2626] to-[#06B6D4] text-white font-extrabold text-xs sm:text-sm hover:scale-105 transition-all shadow-lg inline-flex items-center gap-2">
                            <span>Read Full Story</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
                        </a>
                    </div>
                </div>
        </div>
    @endforeach

    <!-- Navigation Arrows -->
    @if(count($blogs) > 1)
        <div class="absolute bottom-6 right-6 z-30 flex items-center gap-2">
            <button type="button" id="prev-slide-btn" aria-label="Previous story" class="w-10 h-10 rounded-2xl bg-black/75 hover:bg-[#DC2626] text-white flex items-center justify-center transition-all cursor-pointer">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7" /></svg>
            </button>
            <button type="button" id="next-slide-btn" aria-label="Next story" class="w-10 h-10 rounded-2xl bg-black/75 hover:bg-[#06B6D4] text-white flex items-center justify-center transition-all cursor-pointer">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" /></svg>
            </button>
        </div>
    @endif
</div>

@if(count($blogs) > 1)
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const slides = document.querySelectorAll('.slider-slide');
        let current = 0;
        const total = slides.length;

        function showSlide(index) {
            slides.forEach((slide, i) => {
                const links = slide.querySelectorAll('a, button');
                if (i === index) {
                    slide.classList.remove('opacity-0', 'pointer-events-none', 'z-0');
                    slide.classList.add('opacity-100', 'z-10');
                    slide.setAttribute('aria-hidden', 'false');
                    slide.removeAttribute('inert');
                    links.forEach(l => l.removeAttribute('tabindex'));
                } else {
                    slide.classList.add('opacity-0', 'pointer-events-none', 'z-0');
                    slide.classList.remove('opacity-100', 'z-10');
                    slide.setAttribute('aria-hidden', 'true');
                    slide.setAttribute('inert', '');
                    links.forEach(l => l.setAttribute('tabindex', '-1'));
                }
            });
            current = index;
        }

        const prevBtn = document.getElementById('prev-slide-btn');
        const nextBtn = document.getElementById('next-slide-btn');

        if (nextBtn) {
            nextBtn.addEventListener('click', () => {
                showSlide((current + 1) % total);
            });
        }
        if (prevBtn) {
            prevBtn.addEventListener('click', () => {
                showSlide((current - 1 + total) % total);
            });
        }

        // Auto advance every 6s
        setInterval(() => {
            showSlide((current + 1) % total);
        }, 6000);
    });
</script>
@endif
@endif
