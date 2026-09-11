@props(['topics' => []])

@if(!empty($topics) && count($topics) > 0)
<div class="py-3 px-4 sm:px-6 rounded-2xl bg-gradient-to-r from-red-500/10 via-cyan-500/10 to-transparent border border-red-500/20 dark:border-red-500/30 flex items-center gap-3 overflow-hidden">
    <div class="flex items-center gap-2 shrink-0 text-xs font-black uppercase tracking-wider text-[#DC2626] dark:text-[#F87171]">
        <span class="w-2.5 h-2.5 rounded-full bg-[#DC2626] animate-ping"></span>
        <span>Live Buzz</span>
    </div>

    <div class="flex items-center gap-4 overflow-x-auto no-scrollbar whitespace-nowrap text-xs font-bold text-slate-700 dark:text-slate-300">
        @foreach($topics as $topic)
            @php
                $name = is_array($topic) ? ($topic['name'] ?? $topic['title'] ?? '') : (string) $topic;
                $slug = is_array($topic) ? ($topic['slug'] ?? '') : '';
            @endphp
            @if(!empty($name))
                <a href="{{ !empty($slug) ? route('category.show', $slug) : route('search', ['q' => $name]) }}" 
                   class="inline-flex items-center gap-1.5 hover:text-[#DC2626] dark:hover:text-[#06B6D4] transition-colors py-0.5">
                    <span class="text-[#06B6D4]">#</span>
                    <span>{{ $name }}</span>
                </a>
            @endif
        @endforeach
    </div>
</div>
@endif
