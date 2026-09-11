@extends('layouts.app')

@php
    $title = $blog['title'] ?? 'Story';
    $shortDesc = $blog['short_description'] ?? '';
    $category = $blog['category']['name'] ?? 'Creator Insights';
    $categorySlug = $blog['category']['slug'] ?? null;
    $subCategory = $blog['sub_category']['name'] ?? null;
    $subCategorySlug = $blog['sub_category']['slug'] ?? null;
    $publishedDate = blogger_format_date($blog['published_at'] ?? $blog['created_at'] ?? null);
    $readTime = blogger_reading_time($blog['content'] ?? $shortDesc);
    $rawImage = $blog['image_1150x900'] ?? $blog['image_850x500'] ?? $blog['image_url'] ?? null;
    $imageUrl = blogger_media_url($rawImage);
    $viewsCount = $blog['views_count'] ?? 0;
@endphp

@section('title', $title . ' - ' . config('site.name'))
@section('meta_description', $shortDesc ?: 'Read ' . $title . ' on ' . config('site.name') . '.')

@section('content')
<article class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-10">

    <!-- Breadcrumb -->
    <nav class="flex items-center gap-2 text-xs text-slate-400 flex-wrap font-medium">
        <a href="{{ route('home') }}" class="hover:text-[#DC2626] transition-colors">Home</a>
        <span>&rsaquo;</span>
        @if($categorySlug)
            <a href="{{ route('category.show', $categorySlug) }}" class="hover:text-[#DC2626] transition-colors">{{ $category }}</a>
            <span>&rsaquo;</span>
        @endif
        @if($subCategory && $subCategorySlug)
            <a href="{{ route('subcategory.show', $subCategorySlug) }}" class="hover:text-[#DC2626] transition-colors">{{ $subCategory }}</a>
            <span>&rsaquo;</span>
        @endif
        <span class="text-slate-700 dark:text-slate-200 font-bold truncate max-w-xs sm:max-w-md">{{ $title }}</span>
    </nav>

    <!-- Header Section -->
    <div class="max-w-4xl mx-auto text-center space-y-4">
        @if($categorySlug)
            <a href="{{ route('category.show', $categorySlug) }}" class="inline-block px-4 py-1.5 rounded-full text-xs font-black uppercase tracking-wider bg-[#DC2626] text-white shadow-sm">
                {{ $category }}
            </a>
        @endif

        <h1 class="text-3xl sm:text-5xl font-black text-slate-900 dark:text-white tracking-tight leading-tight">
            {{ $title }}
        </h1>

        @if(!empty($shortDesc))
            <p class="text-base sm:text-xl text-slate-600 dark:text-slate-300 font-normal leading-relaxed max-w-2xl mx-auto">
                {{ $shortDesc }}
            </p>
        @endif

        <div class="flex items-center justify-center gap-4 text-xs sm:text-sm text-slate-400 pt-2 flex-wrap font-semibold">
            <span>{{ $publishedDate }}</span>
            <span>&bull;</span>
            <span class="flex items-center gap-1 text-[#06B6D4]">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                {{ $readTime }} min read
            </span>
            @if($viewsCount > 0)
                <span>&bull;</span>
                <span class="flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                    {{ number_format($viewsCount) }} views
                </span>
            @endif
        </div>
    </div>

    <!-- Featured Cover Image -->
    <div class="max-w-5xl mx-auto rounded-3xl overflow-hidden shadow-2xl bg-slate-950 aspect-[16/9] border border-slate-200 dark:border-slate-800">
        <img src="{{ $imageUrl }}" alt="{{ $title }}" class="w-full h-full object-cover">
    </div>

    <!-- Top Article Ad Banner -->
    <x-ad-banner placement="article_top" />

    <!-- Article Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 max-w-6xl mx-auto">

        <!-- Left Share Bar (Desktop) -->
        <div class="lg:col-span-1 hidden lg:block">
            <div class="sticky top-28 flex flex-col items-center gap-3">
                <span class="text-[10px] uppercase font-black text-slate-400 tracking-wider mb-1">Share</span>

                <!-- WhatsApp -->
                <a href="https://api.whatsapp.com/send?text={{ urlencode('🔥 Creator Insight: ' . $title . ' ' . url()->current()) }}" target="_blank" rel="noopener" class="p-2.5 rounded-2xl bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-200 hover:bg-[#25D366] hover:text-white transition-all" title="Share on WhatsApp">
                    <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981z"/></svg>
                </a>

                <!-- Twitter / X -->
                <a href="https://twitter.com/intent/tweet?url={{ urlencode(url()->current()) }}&text={{ urlencode($title) }}" target="_blank" rel="noopener" class="p-2.5 rounded-2xl bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-200 hover:bg-black hover:text-white transition-all" title="Share on X">
                    <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                </a>

                <!-- Copy Link Button -->
                <button type="button" onclick="window.copyCreatorLink()" class="p-2.5 rounded-2xl bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-200 hover:bg-[#DC2626] hover:text-white transition-all" title="Copy Link">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
                </button>
            </div>
        </div>

        <!-- Main Body (8 cols) -->
        <div class="lg:col-span-8 space-y-10">
            <div class="prose">
                {!! $blog['content'] ?? '' !!}
            </div>

            <!-- In-Article Horizontal Ad Placement -->
            <x-ad-banner placement="horizontal_ad" />

            <!-- Tags -->
            @php
                $tags = !empty($blog['tags_array']) ? $blog['tags_array'] : (is_array($blog['tags'] ?? null) ? $blog['tags'] : explode(',', $blog['tags'] ?? ''));
                $tags = array_filter(array_map('trim', $tags));
            @endphp
            @if(!empty($tags))
                <div class="pt-6 border-t border-slate-200 dark:border-slate-800">
                    <span class="text-xs font-black uppercase tracking-wider text-slate-400 block mb-3">Keywords &amp; Niches:</span>
                    <div class="flex flex-wrap gap-2">
                        @foreach($tags as $tag)
                            <a href="{{ route('search', ['q' => $tag]) }}" class="px-3.5 py-1.5 rounded-xl text-xs font-bold bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 transition-colors">
                                #{{ $tag }}
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Previous & Next Story Navigation -->
            @if(!empty($prevBlog) || !empty($nextBlog))
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-8 border-t border-slate-200 dark:border-slate-800">
                    @if(!empty($prevBlog))
                        <a href="{{ route('blog.show', $prevBlog['slug']) }}" class="p-5 rounded-3xl bg-white dark:bg-[#0F172A] border border-slate-200 dark:border-slate-800 hover:border-[#DC2626] transition-all group">
                            <span class="text-[11px] font-black text-[#DC2626] uppercase tracking-wider block mb-1">&larr; Previous Story</span>
                            <h4 class="text-sm font-bold text-slate-900 dark:text-white group-hover:text-[#DC2626] line-clamp-2">{{ $prevBlog['title'] }}</h4>
                        </a>
                    @else
                        <div></div>
                    @endif

                    @if(!empty($nextBlog))
                        <a href="{{ route('blog.show', $nextBlog['slug']) }}" class="p-5 rounded-3xl bg-white dark:bg-[#0F172A] border border-slate-200 dark:border-slate-800 hover:border-[#06B6D4] transition-all group sm:text-right">
                            <span class="text-[11px] font-black text-[#06B6D4] uppercase tracking-wider block mb-1">Next Story &rarr;</span>
                            <h4 class="text-sm font-bold text-slate-900 dark:text-white group-hover:text-[#06B6D4] line-clamp-2">{{ $nextBlog['title'] }}</h4>
                        </a>
                    @endif
                </div>
            @endif

            <!-- Comments Section -->
            @if($blog['allow_comments'] ?? true)
                <section class="pt-10 border-t border-slate-200 dark:border-slate-800 space-y-8" id="comments">
                    <div class="flex items-center justify-between">
                        <h3 class="text-xl font-black text-slate-900 dark:text-white flex items-center gap-2">
                            <span>Creator Discussions</span>
                            <span id="comments-count" class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300">
                                {{ count($comments ?? []) }}
                            </span>
                        </h3>
                    </div>

                    <!-- Comment Form with AJAX -->
                    <form id="comment-form" action="{{ route('blog.comment', $blog['slug']) }}" method="POST" class="p-6 sm:p-8 rounded-3xl bg-white dark:bg-[#0F172A] border border-slate-200 dark:border-slate-800 space-y-4 shadow-sm">
                        @csrf
                        <input type="hidden" name="blog_id" value="{{ $blog['id'] }}">

                        <div id="comment-alert" class="hidden p-4 rounded-2xl text-xs font-bold"></div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1.5">Your Name or Handle</label>
                                <input type="text" name="name" required placeholder="@yourhandle or Alex" class="w-full px-4 py-2.5 rounded-2xl bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700 text-sm focus:outline-none focus:ring-2 focus:ring-[#DC2626]">
                            </div>
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1.5">Email Address</label>
                                <input type="email" name="email" required placeholder="you@channel.com" class="w-full px-4 py-2.5 rounded-2xl bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700 text-sm focus:outline-none focus:ring-2 focus:ring-[#DC2626]">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1.5">Comment</label>
                            <textarea name="comment" rows="4" required placeholder="Drop your creator insights or take on this topic..." class="w-full px-4 py-3 rounded-2xl bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700 text-sm focus:outline-none focus:ring-2 focus:ring-[#DC2626]"></textarea>
                        </div>

                        <button type="submit" id="comment-submit-btn" class="px-6 py-3 rounded-2xl font-bold text-sm bg-gradient-to-r from-[#DC2626] to-[#06B6D4] text-white shadow-md hover:scale-105 transition-all inline-flex items-center gap-2">
                            <span>Post Comment</span>
                        </button>
                    </form>

                    <!-- Comments List (New comments are prepended immediately at top) -->
                    <div id="comments-list" class="space-y-4">
                        @forelse($comments ?? [] as $comm)
                            <div class="comment-item p-5 rounded-3xl bg-white dark:bg-[#0F172A] border border-slate-200 dark:border-slate-800 shadow-xs space-y-2">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-8 h-8 rounded-full bg-gradient-to-tr from-[#DC2626] to-[#06B6D4] text-white font-black text-xs flex items-center justify-center">
                                        {{ substr($comm['name'] ?? $comm['full_name'] ?? 'C', 0, 1) }}
                                    </div>
                                    <div>
                                        <span class="text-sm font-bold text-slate-900 dark:text-white block">{{ $comm['name'] ?? $comm['full_name'] ?? 'Creator' }}</span>
                                        <span class="text-[10px] text-slate-400">{{ blogger_format_date($comm['created_at'] ?? null) }}</span>
                                    </div>
                                </div>
                                <p class="text-xs sm:text-sm text-slate-700 dark:text-slate-300 leading-relaxed pl-10">
                                    {{ $comm['comment'] ?? $comm['description'] ?? '' }}
                                </p>
                            </div>
                        @empty
                            <p id="no-comments-msg" class="text-xs text-slate-400 italic text-center py-6">Be the first creator to share your take on this story.</p>
                        @endforelse
                    </div>
                </section>
            @endif
        </div>

        <!-- Sidebar Column (3 cols) -->
        <aside class="lg:col-span-3 space-y-8">
            <!-- Creator Dare Challenge Widget -->
            <div class="p-6 rounded-3xl bg-gradient-to-br from-[#0F172A] to-[#080C14] border border-slate-800 text-white shadow-lg space-y-4">
                <span class="text-3xl">⚡</span>
                <h4 class="text-base font-black text-white leading-tight">
                    Dare Your Squad
                </h4>
                <p class="text-xs text-slate-300 leading-relaxed">
                    Test how well your collaborators, community, or friends truly know your creator tastes and habits!
                </p>
                <a href="{{ route('quizzes.index') }}" class="block w-full text-center py-2.5 px-4 rounded-xl text-xs font-black bg-[#DC2626] hover:bg-[#B91C1C] text-white transition-colors">
                    Start A Challenge &rarr;
                </a>
            </div>

            <!-- Related Stories -->
            @if(!empty($relatedBlogs) && count($relatedBlogs) > 0)
                <div class="rounded-3xl bg-white dark:bg-[#0F172A] p-6 border border-slate-200 dark:border-slate-800 shadow-xs space-y-4">
                    <h4 class="text-xs font-black uppercase tracking-widest text-[#06B6D4] border-b border-slate-100 dark:border-slate-800 pb-2">
                        Related Reads
                    </h4>
                    <div class="space-y-4">
                        @foreach(collect($relatedBlogs)->take(4) as $related)
                            @php
                                $relSlug = $related['slug'] ?? '#';
                                $relUrl = route('blog.show', $relSlug);
                                $relTitle = $related['title'] ?? '';
                                $relImg = blogger_media_url($related['image_200x200'] ?? $related['image_url'] ?? null);
                            @endphp
                            <a href="{{ $relUrl }}" class="flex items-center gap-3 group">
                                <div class="w-14 h-14 rounded-xl overflow-hidden bg-slate-900 shrink-0">
                                    <img src="{{ $relImg }}" alt="{{ $relTitle }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform">
                                </div>
                                <div class="min-w-0 flex-1">
                                    <h5 class="text-xs font-bold text-slate-800 dark:text-slate-200 group-hover:text-[#DC2626] dark:group-hover:text-[#06B6D4] transition-colors line-clamp-2 leading-snug">
                                        {{ $relTitle }}
                                    </h5>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Sidebar Ad Placement -->
            <x-ad-banner placement="sidebar" />
        </aside>

    </div>
</article>

<script>
    // Instant AJAX Comment Submission (Immediate Prepend to Top & No Page Reload)
    document.addEventListener('DOMContentLoaded', () => {
        const form = document.getElementById('comment-form');
        const alertBox = document.getElementById('comment-alert');
        const submitBtn = document.getElementById('comment-submit-btn');
        const commentsList = document.getElementById('comments-list');
        const commentsCount = document.getElementById('comments-count');
        const noCommentsMsg = document.getElementById('no-comments-msg');

        if (form) {
            form.addEventListener('submit', async (e) => {
                e.preventDefault();
                submitBtn.disabled = true;
                const originalText = submitBtn.innerHTML;
                submitBtn.innerHTML = '<span>Posting...</span>';
                alertBox.classList.add('hidden');

                try {
                    const formData = new FormData(form);
                    const response = await fetch(form.action, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json',
                        }
                    });

                    const data = await response.json();

                    if (response.ok && data.success !== false) {
                        alertBox.className = 'p-4 rounded-2xl bg-emerald-50 dark:bg-emerald-950/50 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-300 text-xs font-bold';
                        alertBox.textContent = data.message || 'Your comment has been posted successfully!';
                        alertBox.classList.remove('hidden');

                        const commentData = data.comment || {
                            name: formData.get('name'),
                            comment: formData.get('comment'),
                        };

                        const authorName = commentData.name || commentData.full_name || 'Creator';
                        const commentBody = commentData.comment || commentData.description || '';
                        const initialLetter = authorName.charAt(0).toUpperCase();

                        const newCommentEl = document.createElement('div');
                        newCommentEl.className = 'comment-item p-5 rounded-3xl bg-slate-100/70 dark:bg-[#131D33] border border-slate-200 dark:border-slate-700 shadow-xs space-y-2 animate-fade-in';
                        newCommentEl.innerHTML = `
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-8 h-8 rounded-full bg-gradient-to-tr from-[#DC2626] to-[#06B6D4] text-white font-black text-xs flex items-center justify-center">
                                        ${initialLetter}
                                    </div>
                                    <div>
                                        <span class="text-sm font-bold text-slate-900 dark:text-white block">${authorName}</span>
                                        <span class="text-[10px] text-slate-400">Just now</span>
                                    </div>
                                </div>
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 dark:bg-emerald-950/50 text-emerald-700 dark:text-emerald-300">Live</span>
                            </div>
                            <p class="text-xs sm:text-sm text-slate-700 dark:text-slate-300 leading-relaxed pl-10">
                                ${commentBody}
                            </p>
                        `;

                        if (noCommentsMsg) {
                            noCommentsMsg.remove();
                        }
                        commentsList.prepend(newCommentEl);

                        if (commentsCount) {
                            const current = parseInt(commentsCount.textContent.trim()) || 0;
                            commentsCount.textContent = current + 1;
                        }

                        form.querySelector('[name="comment"]').value = '';
                    } else {
                        alertBox.className = 'p-4 rounded-2xl bg-rose-50 dark:bg-rose-950/50 border border-rose-200 dark:border-rose-800 text-rose-800 dark:text-rose-300 text-xs font-bold';
                        const errMsg = data.message || (data.errors ? Object.values(data.errors).flat()[0] : 'Failed to post comment.');
                        alertBox.textContent = errMsg;
                        alertBox.classList.remove('hidden');
                    }
                } catch (err) {
                    alertBox.className = 'p-4 rounded-2xl bg-rose-50 dark:bg-rose-950/50 border border-rose-200 dark:border-rose-800 text-rose-800 dark:text-rose-300 text-xs font-bold';
                    alertBox.textContent = 'An error occurred while posting your comment. Please try again.';
                    alertBox.classList.remove('hidden');
                } finally {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalText;
                }
            });
        }
    });
</script>
@endsection
