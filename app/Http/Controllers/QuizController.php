<?php

namespace App\Http\Controllers;

use App\Services\BloggerApiClient;
use Illuminate\Http\Request;

class QuizController extends Controller
{
    protected BloggerApiClient $client;

    public function __construct(BloggerApiClient $client)
    {
        $this->client = $client;
    }

    /**
     * Check if viral quizzes are enabled for this website in the backend response.
     */
    protected function isFeatureDisabled(array $response): bool
    {
        return !empty($response['feature_disabled']);
    }

    public function index(Request $request)
    {
        $data = $this->client->getQuizzes();

        if ($this->isFeatureDisabled($data)) {
            return response()->view('quizzes.disabled', [
                'message' => $data['message'] ?? 'Creator Quizzes & Challenges are currently unavailable.',
            ], 200);
        }

        $myCreatedDares = session('my_created_dares', []);

        return view('quizzes.index', [
            'quizzes' => $data['quizzes'] ?? [],
            'siteTitle' => 'Creator Quizzes & Collab Challenges - ' . config('site.name'),
            'siteDescription' => 'Test your creator chemistry, create custom viral dares, and share with your squad!',
            'myCreatedDares' => $myCreatedDares,
        ]);
    }

    public function createChallenge(string $slug)
    {
        $data = $this->client->getQuizDetail($slug);

        if ($this->isFeatureDisabled($data)) {
            return response()->view('quizzes.disabled', [
                'message' => $data['message'] ?? 'This quiz challenge is currently unavailable.',
            ], 200);
        }

        if (empty($data) || empty($data['quiz'])) {
            abort(404, 'Quiz not found');
        }

        return view('quizzes.create-challenge', [
            'quiz' => $data['quiz'],
        ]);
    }

    public function storeChallenge(Request $request, string $slug)
    {
        $request->validate([
            'creator_name' => 'required|string|max:50',
            'creator_avatar' => 'nullable|string|max:10',
            'answers' => 'required|array',
        ]);

        $response = $this->client->createQuizChallenge($slug, $request->all());

        if ($this->isFeatureDisabled($response['data'] ?? [])) {
            return response()->view('quizzes.disabled', [
                'message' => $response['data']['message'] ?? 'Creating challenges is currently disabled.',
            ], 200);
        }

        if ($response['success'] ?? false) {
            $shareToken = $response['data']['challenge']['share_token'] ?? null;
            if ($shareToken) {
                // Save locally to user session for quick access
                $dares = session('my_created_dares', []);
                $dares[$shareToken] = [
                    'token' => $shareToken,
                    'quiz_title' => $response['data']['quiz']['title'] ?? 'Creator Challenge',
                    'creator_name' => $request->input('creator_name'),
                    'creator_avatar' => $request->input('creator_avatar', '⚡'),
                    'created_at' => now()->diffForHumans(),
                ];
                session(['my_created_dares' => $dares]);

                return redirect()->route('quizzes.challenge.share', $shareToken)
                    ->with('success', 'Your creator challenge is ready to share!');
            }
        }

        return back()->withInput()->with('error', $response['data']['message'] ?? 'Unable to create challenge at this moment.');
    }

    public function shareDashboard(string $token)
    {
        $data = $this->client->getQuizChallenge($token, true);

        if ($this->isFeatureDisabled($data)) {
            return response()->view('quizzes.disabled', [
                'message' => $data['message'] ?? 'This challenge is currently unavailable.',
            ], 200);
        }

        if (empty($data) || empty($data['challenge'])) {
            abort(404, 'Challenge not found');
        }

        return view('quizzes.challenge-share', [
            'challenge' => $data['challenge'],
            'quiz' => $data['quiz'] ?? [],
            'leaderboard' => $data['leaderboard'] ?? [],
        ]);
    }

    public function takeChallenge(string $token)
    {
        $data = $this->client->getQuizChallenge($token, false);

        if ($this->isFeatureDisabled($data)) {
            return response()->view('quizzes.disabled', [
                'message' => $data['message'] ?? 'This challenge is currently unavailable.',
            ], 200);
        }

        if (empty($data) || empty($data['challenge'])) {
            abort(404, 'Challenge not found');
        }

        return view('quizzes.take-challenge', [
            'challenge' => $data['challenge'],
            'quiz' => $data['quiz'] ?? [],
            'leaderboard' => $data['leaderboard'] ?? [],
        ]);
    }

    public function submitAttempt(Request $request, string $token)
    {
        $request->validate([
            'friend_name' => 'required|string|max:50',
            'answers' => 'required|array',
        ]);

        $response = $this->client->submitQuizChallengeAttempt($token, $request->all());

        if ($this->isFeatureDisabled($response['data'] ?? [])) {
            return response()->view('quizzes.disabled', [
                'message' => $response['data']['message'] ?? 'Submitting attempts is currently disabled.',
            ], 200);
        }

        if ($response['success'] ?? false) {
            $attemptId = $response['data']['attempt']['id'] ?? null;
            return redirect()->route('quizzes.challenge.score', [$token, 'attempt' => $attemptId]);
        }

        return back()->withInput()->with('error', $response['data']['message'] ?? 'Unable to submit your responses.');
    }

    public function showScore(Request $request, string $token)
    {
        $attemptId = (int) $request->get('attempt');
        if (!$attemptId) {
            return redirect()->route('quizzes.challenge.take', $token);
        }

        $data = $this->client->getQuizChallengeAttempt($token, $attemptId);

        if ($this->isFeatureDisabled($data)) {
            return response()->view('quizzes.disabled', [
                'message' => $data['message'] ?? 'Results are currently unavailable.',
            ], 200);
        }

        if (empty($data) || empty($data['result'])) {
            abort(404, 'Attempt result not found');
        }

        return view('quizzes.challenge-result', [
            'token' => $token,
            'result' => $data['result'],
        ]);
    }
}
