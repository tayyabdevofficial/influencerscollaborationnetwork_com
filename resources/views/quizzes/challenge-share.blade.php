@extends('layouts.app')

@section('title', ($challenge['creator_name'] ?? 'Your') . "'s Dare Challenge - Share & Scoreboard - " . config('site.name'))
@section('meta_description', 'Share your dare challenge with your squad on WhatsApp and see who gets the top rank on your live scoreboard!')

@section('content')
<div class="min-h-screen py-8 sm:py-12">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 space-y-8">

        @php
            $shareUrl = route('quizzes.challenge.take', $challenge['share_token']);
            $encodedUrl = urlencode($shareUrl);
            $creatorName = $challenge['creator_name'] ?? 'Your Friend';
            $creatorAvatar = $challenge['creator_avatar'] ?? '⚡';
            $quizTitle = $quiz['title'] ?? 'The Creator Dare Challenge';

            $whatsappMsg = rawurlencode(
                "{$creatorAvatar} *{$creatorName}* has challenged you to an exclusive Creator Dare!\n\n" .
                "🤔 *Think you REALLY know their tastes & brain?* Let's test it! 🤫\n\n" .
                "🎯 *Dare:* {$quizTitle}\n\n" .
                "👇 *Tap to accept the challenge:* 👇\n" .
                "{$shareUrl}\n\n" .
                "⚡ Guess their answers & see if you can top the Leaderboard! 👑🏆"
            );
        @endphp

        <!-- Success Celebration Card -->
        <div class="bg-gradient-to-br from-[#DC2626] via-[#B91C1C] to-[#06B6D4] rounded-3xl text-white p-6 sm:p-10 shadow-2xl text-center relative overflow-hidden">
            <div class="absolute -right-8 -top-8 w-40 h-40 bg-white/10 rounded-full blur-2xl pointer-events-none"></div>
            <div class="absolute -left-8 -bottom-8 w-40 h-40 bg-cyan-400/20 rounded-full blur-2xl pointer-events-none"></div>

            <div class="relative z-10 space-y-4">
                <div class="w-20 h-20 mx-auto rounded-3xl bg-white/20 backdrop-blur-md border border-white/30 flex items-center justify-center text-4xl shadow-inner">
                    {{ $challenge['creator_avatar'] ?? '⚡' }}
                </div>

                <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-white/20 backdrop-blur-md text-xs font-black uppercase tracking-wider text-cyan-200">
                    <span>🎉</span> Creator Dare Link Live!
                </div>

                <h1 class="text-2xl sm:text-4xl font-black tracking-tight">
                    {{ $challenge['creator_name'] }}'s Dare Challenge
                </h1>

                <p class="text-sm sm:text-base text-white/90 max-w-lg mx-auto">
                    Based on <strong>{{ $quiz['title'] ?? 'Quiz' }}</strong>. Share your private link below and watch your friends try to guess your choices!
                </p>
            </div>
        </div>

        @if(session('success'))
            <div class="p-4 rounded-2xl bg-emerald-50 dark:bg-emerald-950/50 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-200 text-sm font-semibold flex items-center gap-3">
                <span class="text-xl">✨</span>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <!-- Share Actions Card -->
        <div class="bg-white dark:bg-[#0F172A] rounded-3xl border border-slate-200 dark:border-slate-800 p-6 sm:p-8 shadow-sm space-y-6">
            <div class="space-y-1 text-center sm:text-left">
                <h2 class="text-lg font-black text-slate-900 dark:text-white">Share With Your Squad</h2>
                <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400">Blast this link directly to WhatsApp contacts, Discord servers, or group chats.</p>
            </div>

            <!-- WhatsApp One-Click Share -->
            <a href="https://api.whatsapp.com/send?text={{ $whatsappMsg }}" target="_blank" rel="noopener noreferrer"
               class="w-full py-4 px-6 rounded-2xl bg-[#25D366] hover:bg-[#20bd5a] text-white font-black text-base sm:text-lg shadow-lg hover:shadow-xl hover:scale-[1.02] active:scale-95 transition-all flex items-center justify-center gap-3">
                <svg class="w-6 h-6 fill-current" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
                <span>Share on WhatsApp Now</span>
            </a>

            <!-- Copy Link Bar -->
            <div class="space-y-2">
                <label class="block text-xs font-black uppercase tracking-wider text-slate-400">
                    Or Copy Dare Link
                </label>
                <div class="flex items-center gap-2 p-2 rounded-2xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800">
                    <input type="text" id="share-link-input" readonly value="{{ $shareUrl }}"
                           class="flex-1 bg-transparent px-3 py-1.5 text-xs sm:text-sm font-semibold text-slate-800 dark:text-slate-200 outline-none select-all truncate">
                    <button type="button" onclick="copyShareLink()" id="copy-btn"
                            class="px-4 py-2 rounded-xl bg-slate-900 dark:bg-white text-white dark:text-slate-900 font-bold text-xs sm:text-sm hover:bg-[#DC2626] dark:hover:bg-[#DC2626] dark:hover:text-white transition-colors shrink-0 flex items-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3" /></svg>
                        <span id="copy-text">Copy Link</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Live Scoreboard -->
        <div class="bg-white dark:bg-[#0F172A] rounded-3xl border border-slate-200 dark:border-slate-800 p-6 sm:p-8 shadow-sm space-y-6">
            <div class="flex items-center justify-between border-b border-slate-100 dark:border-slate-800 pb-4">
                <div>
                    <h2 class="text-xl font-black text-slate-900 dark:text-white flex items-center gap-2">
                        <span>🏆</span> Friend Scoreboard
                    </h2>
                    <p class="text-xs text-slate-500 dark:text-slate-400">See how your friends performed on your challenge.</p>
                </div>
                <div class="px-3 py-1 rounded-full bg-red-100 dark:bg-red-950/60 text-[#DC2626] text-xs font-black">
                    {{ count($leaderboard) }} {{ \Illuminate\Support\Str::plural('Friend', count($leaderboard)) }}
                </div>
            </div>

            @if(empty($leaderboard) || count($leaderboard) === 0)
                <div class="text-center py-12 space-y-3">
                    <div class="text-4xl">⏳</div>
                    <div class="font-bold text-slate-800 dark:text-slate-200">No friends have taken your dare yet!</div>
                    <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 max-w-sm mx-auto">
                        Share your link on WhatsApp or group chats above. When they answer, their scores will pop up here instantly!
                    </p>
                </div>
            @else
                <div class="divide-y divide-slate-100 dark:divide-slate-800">
                    @foreach($leaderboard as $idx => $attempt)
                        <div class="py-3.5 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full {{ $idx === 0 ? 'bg-amber-100 text-amber-600 font-black' : ($idx === 1 ? 'bg-slate-200 text-slate-700 font-bold' : ($idx === 2 ? 'bg-orange-100 text-orange-600 font-bold' : 'bg-slate-100 text-slate-500 text-xs')) }} flex items-center justify-center text-sm shrink-0">
                                    {{ $idx === 0 ? '👑' : $idx + 1 }}
                                </div>
                                <div>
                                    <div class="font-bold text-sm text-slate-900 dark:text-white flex items-center gap-2">
                                        <span>{{ $attempt['friend_name'] }}</span>
                                        @if(!empty($attempt['verdict']))
                                            <span class="text-[11px] font-bold text-[#06B6D4]">({{ $attempt['verdict'] }})</span>
                                        @endif
                                    </div>
                                    <div class="text-[11px] text-slate-400">
                                        {{ \Carbon\Carbon::parse($attempt['created_at'])->diffForHumans() }}
                                    </div>
                                </div>
                            </div>

                            <div class="flex items-center gap-2.5 self-end sm:self-auto">
                                <div class="text-right">
                                    <span class="text-sm font-black text-slate-900 dark:text-white">
                                        {{ $attempt['score'] }} / {{ $attempt['total_questions'] }}
                                    </span>
                                </div>
                                <span class="px-2.5 py-1 rounded-full text-xs font-black {{ $attempt['percentage'] >= 70 ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-950/60 dark:text-emerald-300' : ($attempt['percentage'] >= 50 ? 'bg-cyan-100 text-cyan-700 dark:bg-cyan-950/60 dark:text-cyan-300' : 'bg-amber-100 text-amber-700 dark:bg-amber-950/60 dark:text-amber-300') }}">
                                    {{ $attempt['percentage'] }}%
                                </span>

                                @if(!empty($attempt['breakdown']))
                                    <button type="button" 
                                            onclick="openAnswersModal({{ $idx }})"
                                            class="px-3 py-1.5 rounded-xl bg-red-50 hover:bg-red-100 dark:bg-red-950/60 dark:hover:bg-red-900/80 text-[#DC2626] dark:text-red-300 text-xs font-bold inline-flex items-center gap-1.5 transition-colors shadow-sm cursor-pointer">
                                        <span>👁️</span>
                                        <span>View Answers</span>
                                    </button>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Secondary Actions -->
        <div class="flex flex-col sm:flex-row items-center justify-center gap-4 text-center">
            <a href="{{ route('quizzes.challenge.take', $challenge['share_token']) }}?preview=1" class="text-xs sm:text-sm font-bold text-slate-600 dark:text-slate-400 hover:text-[#DC2626] transition-colors">
                👀 Preview Friend View
            </a>
            <span class="hidden sm:inline text-slate-300 dark:text-slate-700">&bull;</span>
            <a href="{{ route('quizzes.index') }}" class="text-xs sm:text-sm font-bold text-slate-600 dark:text-slate-400 hover:text-[#DC2626] transition-colors">
                ⚡ Create Another Quiz
            </a>
        </div>

    </div>
</div>

<!-- Friend Answers Modal Popup -->
<div id="answers-modal" 
     class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/70 backdrop-blur-sm opacity-0 pointer-events-none transition-all duration-200"
     onclick="if(event.target === this) closeAnswersModal()">
    <div class="bg-white dark:bg-[#0F172A] rounded-3xl border border-slate-200 dark:border-slate-800 shadow-2xl w-full max-w-xl max-h-[85vh] flex flex-col transform scale-95 transition-all duration-200 overflow-hidden" id="answers-modal-box">
        <!-- Modal Header -->
        <div class="p-6 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between bg-gradient-to-r from-red-50/50 via-cyan-50/30 to-white dark:from-slate-800/80 dark:to-[#0F172A]">
            <div>
                <div class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-red-100 dark:bg-red-950 text-[#DC2626] text-[10px] font-black uppercase tracking-wider mb-1">
                    Scoreboard Answer Review
                </div>
                <h3 class="text-lg font-black text-slate-900 dark:text-white flex items-center gap-2">
                    <span id="modal-friend-name">Friend</span>'s Answers
                </h3>
                <p class="text-xs text-slate-500 dark:text-slate-400" id="modal-score-subtitle">
                    Score: 0 / 0 (0%)
                </p>
            </div>
            <button type="button" onclick="closeAnswersModal()" class="p-2 rounded-xl text-slate-400 hover:text-slate-700 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" /></svg>
            </button>
        </div>

        <!-- Modal Body (Scrollable Questions List) -->
        <div class="p-6 overflow-y-auto space-y-4" id="modal-breakdown-list">
        </div>

        <!-- Modal Footer -->
        <div class="p-4 border-t border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/40 flex justify-end">
            <button type="button" onclick="closeAnswersModal()" class="px-5 py-2.5 rounded-xl bg-slate-200 dark:bg-slate-700 hover:bg-slate-300 dark:hover:bg-slate-600 text-slate-800 dark:text-white font-bold text-xs transition-colors">
                Close
            </button>
        </div>
    </div>
</div>

<script>
    window.dareLeaderboard = @json($leaderboard);

    function copyShareLink() {
        const input = document.getElementById('share-link-input');
        input.select();
        input.setSelectionRange(0, 99999);
        navigator.clipboard.writeText(input.value).then(() => {
            const btnText = document.getElementById('copy-text');
            btnText.innerText = 'Copied! ✨';
            setTimeout(() => {
                btnText.innerText = 'Copy Link';
            }, 2500);
        });
    }

    window.openAnswersModal = function(idx) {
        const attempt = (window.dareLeaderboard && window.dareLeaderboard[idx]) ? window.dareLeaderboard[idx] : null;
        if (!attempt) return;

        const friendNameEl = document.getElementById('modal-friend-name');
        if (friendNameEl) friendNameEl.textContent = attempt.friend_name || 'Friend';

        const subtitleEl = document.getElementById('modal-score-subtitle');
        if (subtitleEl) {
            subtitleEl.textContent = `Score: ${attempt.score} / ${attempt.total_questions} (${attempt.percentage}%) ${attempt.verdict ? '• ' + attempt.verdict : ''}`;
        }

        const container = document.getElementById('modal-breakdown-list');
        if (!container) return;
        container.innerHTML = '';

        if (attempt.breakdown && attempt.breakdown.length > 0) {
            attempt.breakdown.forEach((item, qIdx) => {
                const card = document.createElement('div');
                card.className = `p-4 rounded-2xl border-2 ${item.is_match ? 'border-emerald-500/30 bg-emerald-50/30 dark:bg-emerald-950/20' : 'border-rose-500/30 bg-rose-50/30 dark:bg-rose-950/20'} space-y-2.5`;

                const topRow = document.createElement('div');
                topRow.className = 'flex items-start justify-between gap-3';

                const titleCol = document.createElement('div');
                titleCol.className = 'text-xs font-bold text-slate-900 dark:text-white flex-1';

                const qNum = document.createElement('span');
                qNum.className = 'text-[10px] font-black uppercase tracking-wider text-slate-400 block mb-0.5';
                qNum.textContent = `Question ${qIdx + 1}`;

                const qText = document.createElement('span');
                qText.textContent = item.question_text || '';

                titleCol.appendChild(qNum);
                titleCol.appendChild(qText);

                const badge = document.createElement('span');
                badge.className = `px-2.5 py-0.5 rounded-full text-[10px] font-black shrink-0 ${item.is_match ? 'bg-emerald-500 text-white' : 'bg-rose-500 text-white'}`;
                badge.textContent = item.is_match ? '✓ MATCH' : '✗ MISSED';

                topRow.appendChild(titleCol);
                topRow.appendChild(badge);

                const answersGrid = document.createElement('div');
                answersGrid.className = 'grid grid-cols-1 sm:grid-cols-2 gap-2 text-xs';

                const guessCol = document.createElement('div');
                guessCol.className = 'p-2.5 rounded-xl bg-white dark:bg-slate-800/80 border border-slate-200 dark:border-slate-700';
                guessCol.innerHTML = `
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Their Guess</span>
                    <div class="font-semibold text-slate-800 dark:text-slate-200 flex items-center gap-1.5 mt-0.5">
                        <span>${item.is_match ? '🟢' : '🔴'}</span>
                        <span class="friend-ans-text"></span>
                    </div>
                `;
                guessCol.querySelector('.friend-ans-text').textContent = item.friend_answer || 'No answer';

                const truthCol = document.createElement('div');
                truthCol.className = 'p-2.5 rounded-xl bg-white dark:bg-slate-800/80 border border-slate-200 dark:border-slate-700';
                truthCol.innerHTML = `
                    <span class="text-[10px] font-bold text-[#DC2626] uppercase tracking-wider block">Your Truth</span>
                    <div class="font-semibold text-slate-800 dark:text-slate-200 flex items-center gap-1.5 mt-0.5">
                        <span>⚡</span>
                        <span class="creator-ans-text"></span>
                    </div>
                `;
                truthCol.querySelector('.creator-ans-text').textContent = item.creator_answer || 'Hidden';

                answersGrid.appendChild(guessCol);
                answersGrid.appendChild(truthCol);

                card.appendChild(topRow);
                card.appendChild(answersGrid);
                container.appendChild(card);
            });
        } else {
            container.innerHTML = '<div class="text-center py-6 text-slate-400 text-xs">No detailed answer breakdown available for this attempt.</div>';
        }

        const modal = document.getElementById('answers-modal');
        const box = document.getElementById('answers-modal-box');
        if (modal && box) {
            modal.classList.remove('opacity-0', 'pointer-events-none');
            box.classList.remove('scale-95');
            box.classList.add('scale-100');
        }
    };

    window.closeAnswersModal = function() {
        const modal = document.getElementById('answers-modal');
        const box = document.getElementById('answers-modal-box');
        if (modal && box) {
            modal.classList.add('opacity-0', 'pointer-events-none');
            box.classList.remove('scale-100');
            box.classList.add('scale-95');
        }
    };

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') window.closeAnswersModal();
    });

    try {
        const currentDare = {
            token: @json($challenge['share_token'] ?? ''),
            quiz_title: @json($quiz['title'] ?? 'Creator Dare'),
            creator_name: @json($challenge['creator_name'] ?? 'You'),
            creator_avatar: @json($challenge['creator_avatar'] ?? '⚡'),
            created_at: new Date().toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' })
        };
        let stored = JSON.parse(localStorage.getItem('icn_my_dares') || '[]');
        if (!stored.some(d => d.token === currentDare.token)) {
            stored.unshift(currentDare);
            localStorage.setItem('icn_my_dares', JSON.stringify(stored.slice(0, 20)));
        }
    } catch(e) {}
</script>
@endsection
