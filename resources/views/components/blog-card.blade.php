@props(['blog', 'featured' => false])

@php
    $imageUrl = blogger_media_url($blog['image'] ?? $blog['image_url'] ?? null);
    $categoryName = $blog['category']['name'] ?? $blog['category_name'] ?? 'Creator Insights';
    $categorySlug = $blog['category']['slug'] ?? $blog['category_slug'] ?? 'creator-insights';
    $slug = $blog['slug'] ?? '#';
    $title = $blog['title'] ?? 'Untitled Article';
    $description = $blog['short_description'] ?? $blog['description'] ?? '';
    $readingTime = blogger_reading_time($blog['content'] ?? $description);
    $date = blogger_format_date($blog['created_at'] ?? $blog['publish_date'] ?? now());
    $viewsCount = $blog['views_count'] ?? (is_array($blog['views'] ?? null) ? count($blog['views']) : ($blog['views'] ?? 0));
@endphp

<article class="group relative flex flex-col bg-white dark:bg-[#0F172A] rounded-3xl border border-slate-200 dark:border-slate-800/80 overflow-hidden shadow-sm hover:shadow-xl hover:border-slate-300 dark:hover:border-slate-700 transition-all duration-300 transform hover:-translate-y-1">
    <!-- Card Cover Image -->
    <a href="{{ route('blog.show', $slug) }}" class="relative w-full overflow-hidden bg-slate-900 block {{ $featured ? 'h-64 sm:h-72' : 'h-52' }}">
        <img src="{{ $imageUrl }}" 
             alt="{{ $title }}" 
             loading="lazy"
             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
        
        <!-- Gradient Overlay -->
        <div class="absolute inset-0 bg-gradient-to-t from-black/75 via-black/25 to-transparent"></div>

        <!-- Category Badge -->
        <span class="absolute top-3 left-3 px-3 py-1 rounded-full text-xs font-black uppercase tracking-wider bg-[#DC2626] text-white shadow-md">
            {{ $categoryName }}
        </span>
    </a>

    <!-- Card Body -->
    <div class="p-6 flex-1 flex flex-col justify-between space-y-4">
        <div class="space-y-2.5">
            <div class="flex items-center gap-2 text-xs font-semibold text-slate-400">
                <span>{{ $date }}</span>
                <span>&bull;</span>
                <span class="flex items-center gap-1 text-slate-500 dark:text-slate-400 font-medium">
                    <svg class="w-3.5 h-3.5 text-[#06B6D4]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                    <span>{{ number_format((int)$viewsCount) }} {{ Str::plural('view', (int)$viewsCount) }}</span>
                </span>
            </div>

            <h3 class="text-lg {{ $featured ? 'sm:text-xl' : '' }} font-black text-slate-900 dark:text-white group-hover:text-[#DC2626] dark:group-hover:text-[#06B6D4] transition-colors leading-snug line-clamp-2">
                <a href="{{ route('blog.show', $slug) }}">
                    {{ $title }}
                </a>
            </h3>

            @if(!empty($description))
                <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-400 line-clamp-2 leading-relaxed">
                    {{ strip_tags($description) }}
                </p>
            @endif
        </div>

        <div class="pt-4 border-t border-slate-100 dark:border-slate-800/80 flex items-center justify-between">
            <span class="text-xs font-bold text-[#DC2626] dark:text-[#06B6D4] group-hover:translate-x-1 transition-transform inline-flex items-center gap-1">
                Read Article &rarr;
            </span>
        </div>
    </div>
</article>
