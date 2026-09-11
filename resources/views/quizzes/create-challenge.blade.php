@extends('layouts.app')

@section('title', 'Create Challenge: ' . ($quiz['title'] ?? 'Creator Dare') . ' - ' . config('site.name'))
@section('meta_description', 'Answer these personal creator questions to generate your unique dare link and test your friends and squad!')

@section('content')
<div class="min-h-screen py-8 sm:py-12">
    <div class="max-w-3xl mx-auto px-4 sm:px-6">

        <!-- Header Card -->
        <div class="bg-white dark:bg-[#0F172A] rounded-3xl border border-slate-200 dark:border-slate-800 overflow-hidden shadow-sm mb-8">
            @if(!empty($quiz['image_url']) || !empty($quiz['cover_image']))
                <div class="w-full h-48 sm:h-64 overflow-hidden relative">
                    <img src="{{ blogger_media_url($quiz['image_url'] ?? $quiz['cover_image'] ?? null) }}" alt="{{ $quiz['title'] }}" class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-gradient-to-t from-[#080C14] via-transparent to-transparent"></div>
                </div>
            @endif

            <div class="p-6 sm:p-8">
                <div class="flex items-center gap-2 text-xs font-black uppercase tracking-wider text-[#DC2626] mb-2">
                    <a href="{{ route('quizzes.index') }}" class="hover:underline flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7" /></svg>
                        All Quizzes
                    </a>
                    <span>&bull;</span>
                    <span>Dare Creator Mode</span>
                </div>
                <h1 class="text-2xl sm:text-3xl font-black text-slate-900 dark:text-white tracking-tight">
                    {{ $quiz['title'] }}
                </h1>
                @if(!empty($quiz['description']))
                    <p class="mt-2 text-sm sm:text-base text-slate-600 dark:text-slate-400">
                        {{ $quiz['description'] }}
                    </p>
                @endif

                <!-- Instructions -->
                <div class="mt-6 p-4 rounded-2xl bg-red-50 dark:bg-red-950/40 border border-red-200 dark:border-red-900/60 flex items-start gap-3">
                    <span class="text-2xl">⚡</span>
                    <div class="text-xs sm:text-sm text-red-900 dark:text-red-200">
                        <strong>Creator Rules:</strong> Choose <em>your honest answers</em> below. Once finished, you'll receive a secret dare link to blast on WhatsApp or group chats. Your squad will try to guess your exact answers!
                    </div>
                </div>

                <!-- Sticky Progress Bar Indicator -->
                <div class="mt-6 space-y-2">
                    <div class="flex justify-between text-xs font-bold text-slate-500 dark:text-slate-400">
                        <span id="progress-text">Answered 0 of {{ count($quiz['questions'] ?? []) }} questions</span>
                        <span id="progress-percent">0%</span>
                    </div>
                    <div class="w-full h-2.5 bg-slate-100 dark:bg-slate-800 rounded-full overflow-hidden">
                        <div id="progress-bar-fill" class="h-full bg-gradient-to-r from-[#DC2626] to-[#06B6D4] transition-all duration-300 w-0 rounded-full"></div>
                    </div>
                </div>
            </div>
        </div>

        @if(session('error'))
            <div class="mb-6 p-4 rounded-2xl bg-rose-50 dark:bg-rose-950/50 border border-rose-200 dark:border-rose-800 text-rose-700 dark:text-rose-300 text-sm font-semibold flex items-center gap-3">
                <span>⚠️</span>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        <form id="quiz-create-form" action="{{ route('quizzes.store', $quiz['slug']) }}" method="POST" class="space-y-8">
            @csrf

            <!-- Step 1: Your Profile & Avatar -->
            <div class="bg-white dark:bg-[#0F172A] rounded-3xl border border-slate-200 dark:border-slate-800 p-6 sm:p-8 shadow-sm space-y-6">
                <div class="flex items-center gap-3 border-b border-slate-100 dark:border-slate-800 pb-4">
                    <div class="w-8 h-8 rounded-full bg-red-100 dark:bg-red-950/80 text-[#DC2626] font-black text-sm flex items-center justify-center">
                        1
                    </div>
                    <div>
                        <h2 class="text-lg font-black text-slate-900 dark:text-white">Your Name &amp; Creator Tag</h2>
                        <p class="text-xs text-slate-500 dark:text-slate-400">This will be shown on your dare page so friends know whose brain they are guessing.</p>
                    </div>
                </div>

                <div class="space-y-4">
                    <div>
                        <label for="creator_name" class="block text-xs font-black uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-2">
                            Your Name or Nickname <span class="text-[#DC2626]">*</span>
                        </label>
                        <input type="text" id="creator_name" name="creator_name" required maxlength="50"
                               value="{{ old('creator_name') }}"
                               placeholder="e.g. Alex, @CryptoKing, Maya..."
                               class="w-full px-4 py-3.5 rounded-2xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/80 text-slate-900 dark:text-white text-base font-semibold focus:outline-none focus:ring-2 focus:ring-[#DC2626] transition-all">
                    </div>

                    <div>
                        <label class="block text-xs font-black uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-2">
                            Choose Your Emoji Avatar
                        </label>
                        <div class="flex flex-wrap gap-2.5">
                            @foreach(['⚡', '🔥', '🚀', '💎', '👑', '🎮', '🎧', '🎯', '📸', '🥑', '🦁', '✨'] as $emoji)
                                <label class="cursor-pointer">
                                    <input type="radio" name="creator_avatar" value="{{ $emoji }}" class="peer sr-only" {{ $loop->first ? 'checked' : '' }}>
                                    <div class="w-12 h-12 rounded-2xl border-2 border-slate-200 dark:border-slate-700 peer-checked:border-[#DC2626] peer-checked:bg-red-50 dark:peer-checked:bg-red-950/40 peer-checked:scale-110 flex items-center justify-center text-2xl transition-all duration-150 hover:bg-slate-100 dark:hover:bg-slate-800">
                                        {{ $emoji }}
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- Step 2: Questions -->
            @foreach($quiz['questions'] ?? [] as $qIndex => $question)
                <div class="bg-white dark:bg-[#0F172A] rounded-3xl border border-slate-200 dark:border-slate-800 p-6 sm:p-8 shadow-sm space-y-6 question-card" data-question-id="{{ $question['id'] }}">
                    <!-- Question Header -->
                    <div class="space-y-3">
                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 text-xs font-black">
                            Question {{ $qIndex + 1 }} of {{ count($quiz['questions']) }}
                        </div>
                        <h3 class="text-xl font-black text-slate-900 dark:text-white leading-snug">
                            {{ $question['question_text'] }}
                        </h3>

                        <!-- Optional Question Image -->
                        @if(!empty($question['image_url']) || !empty($question['image']))
                            <div class="rounded-2xl overflow-hidden border border-slate-200 dark:border-slate-800 max-h-72 w-full bg-slate-100 dark:bg-slate-800">
                                <img src="{{ blogger_media_url($question['image_url'] ?? $question['image'] ?? null) }}" alt="{{ $question['question_text'] }}" class="w-full h-full object-cover">
                            </div>
                        @endif
                    </div>

                    <!-- Options Grid -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-2">
                        @foreach($question['options'] ?? [] as $option)
                            <label class="relative block cursor-pointer group">
                                <input type="radio" 
                                       name="answers[{{ $question['id'] }}]" 
                                       value="{{ $option['id'] }}" 
                                       required
                                       onchange="updateProgress()"
                                       class="peer sr-only quiz-radio-input">
                                
                                <div class="option-box h-full p-4 rounded-2xl border-2 border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/40 transition-all duration-200 group-hover:border-red-300 dark:group-hover:border-red-700/60 flex items-center gap-3">
                                    <!-- Option Image if present -->
                                    @if(!empty($option['image_url']) || !empty($option['image']))
                                        <div class="w-12 h-12 rounded-xl overflow-hidden shrink-0 border border-slate-200 dark:border-slate-700 bg-white">
                                            <img src="{{ blogger_media_url($option['image_url'] ?? $option['image'] ?? null) }}" alt="{{ $option['option_text'] }}" class="w-full h-full object-cover">
                                        </div>
                                    @endif

                                    <!-- Option Text -->
                                    <div class="option-text flex-1 font-semibold text-sm text-slate-800 dark:text-slate-200">
                                        {{ $option['option_text'] }}
                                    </div>

                                    <!-- Selected Checkmark Circle -->
                                    <div class="radio-circle w-6 h-6 rounded-full border-2 border-slate-300 dark:border-slate-600 flex items-center justify-center transition-all shrink-0">
                                        <svg class="radio-tick w-3.5 h-3.5 text-white opacity-0 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3.5" d="M5 13l4 4L19 7" />
                                        </svg>
                                    </div>
                                </div>
                            </label>
                        @endforeach
                    </div>
                </div>
            @endforeach

            <!-- Submit Action Card -->
            <div class="sticky bottom-4 z-30 bg-white/95 dark:bg-[#0F172A]/95 backdrop-blur-md rounded-3xl border border-slate-200 dark:border-slate-800 p-4 sm:p-6 shadow-2xl flex flex-col sm:flex-row items-center justify-between gap-4">
                <div>
                    <div class="font-black text-slate-900 dark:text-white text-base">Ready to challenge your squad?</div>
                    <div class="text-xs text-slate-500 dark:text-slate-400">Make sure all questions are answered!</div>
                </div>
                <button type="submit" id="submit-quiz-btn" class="w-full sm:w-auto px-8 py-4 rounded-2xl bg-gradient-to-r from-[#DC2626] to-[#06B6D4] text-white font-black text-base shadow-lg hover:shadow-xl hover:scale-105 active:scale-95 transition-all duration-200 flex items-center justify-center gap-2">
                    <span>Create My Challenge Link</span>
                    <span>🚀</span>
                </button>
            </div>
        </form>

    </div>
</div>

<style>
/* Selected radio option styling */
.quiz-radio-input:checked + .option-box {
    border-color: #dc2626 !important;
    background-color: rgba(254, 242, 242, 0.95) !important;
    box-shadow: 0 4px 14px 0 rgba(220, 38, 38, 0.15) !important;
}
.dark .quiz-radio-input:checked + .option-box {
    border-color: #dc2626 !important;
    background-color: rgba(185, 28, 28, 0.25) !important;
    box-shadow: 0 4px 14px 0 rgba(220, 38, 38, 0.2) !important;
}
.quiz-radio-input:checked + .option-box .option-text {
    color: #b91c1c !important;
    font-weight: 700 !important;
}
.dark .quiz-radio-input:checked + .option-box .option-text {
    color: #f87171 !important;
}
.quiz-radio-input:checked + .option-box .radio-circle {
    background-color: #dc2626 !important;
    border-color: #dc2626 !important;
    transform: scale(1.1);
}
.quiz-radio-input:checked + .option-box .radio-circle .radio-tick {
    opacity: 1 !important;
    stroke: #ffffff !important;
}
</style>

<script>
    const totalQuestions = {{ count($quiz['questions'] ?? []) }};

    function updateProgress() {
        const checked = document.querySelectorAll('input[type="radio"][name^="answers"]:checked').length;
        const percent = totalQuestions > 0 ? Math.round((checked / totalQuestions) * 100) : 0;
        
        document.getElementById('progress-text').innerText = `Answered ${checked} of ${totalQuestions} questions`;
        document.getElementById('progress-percent').innerText = `${percent}%`;
        document.getElementById('progress-bar-fill').style.width = `${percent}%`;
    }

    document.getElementById('quiz-create-form').addEventListener('submit', function(e) {
        const checked = document.querySelectorAll('input[type="radio"][name^="answers"]:checked').length;
        if (checked < totalQuestions) {
            e.preventDefault();
            alert(`Please answer all ${totalQuestions} questions before creating your challenge! You still have ${totalQuestions - checked} unanswered.`);
            const cards = document.querySelectorAll('.question-card');
            for (let card of cards) {
                if (!card.querySelector('input[type="radio"]:checked')) {
                    card.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    card.classList.add('ring-2', 'ring-red-500');
                    setTimeout(() => card.classList.remove('ring-2', 'ring-red-500'), 2500);
                    break;
                }
            }
        }
    });

    document.addEventListener('DOMContentLoaded', updateProgress);
</script>
@endsection
