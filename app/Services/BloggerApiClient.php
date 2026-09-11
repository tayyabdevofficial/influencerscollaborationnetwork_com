<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class BloggerApiClient
{
    protected string $baseUrl;
    protected string $apiKey;
    protected string $apiSecret;
    protected string $clientDomain;
    protected int $timeout;
    protected bool $cacheEnabled;
    protected int $defaultTtl;

    public function __construct()
    {
        $this->baseUrl = rtrim(config('blogger.api_url', 'http://127.0.0.1:8000/api/v1'), '/');
        $this->apiKey = (string) config('blogger.api_key', '');
        $this->apiSecret = (string) config('blogger.api_secret', '');
        $this->clientDomain = (string) config('blogger.client_domain', 'influencerscollaborationnetwork.com');
        $this->timeout = (int) config('blogger.timeout', 10);
        $this->cacheEnabled = (bool) config('blogger.cache_enabled', true);
        $this->defaultTtl = (int) config('blogger.cache_ttl', 120);
    }

    /**
     * Generate security handshake headers including HMAC-SHA256 signature.
     */
    protected function generateHeaders(string $method, string $uriPath): array
    {
        $timestamp = (string) time();
        $signature = hash_hmac(
            'sha256',
            $timestamp . strtoupper($method) . $uriPath,
            $this->apiSecret
        );

        $headers = [
            'X-API-KEY' => $this->apiKey,
            'X-Timestamp' => $timestamp,
            'X-Signature' => $signature,
            'Client-Domain' => $this->clientDomain,
            'Accept' => 'application/json',
        ];

        // Forward visitor client context for unique view tracking & technical logging
        if (function_exists('request') && request()) {
            $visitorIp = request()->ip();
            $visitorUa = request()->userAgent();
            $visitorDevice = request()->cookie('icn_device_id') ?? session('icn_device_id');

            if ($visitorIp) {
                $headers['X-Visitor-IP'] = $visitorIp;
            }
            if ($visitorUa) {
                $headers['X-Visitor-User-Agent'] = $visitorUa;
            }
            if ($visitorDevice) {
                $headers['X-Visitor-Device'] = $visitorDevice;
            }
        }

        return $headers;
    }

    /**
     * Perform an authenticated API request with local caching.
     */
    public function get(string $path, array $queryParams = [], ?int $ttl = null): array
    {
        $path = '/' . ltrim($path, '/');
        $queryString = !empty($queryParams) ? '?' . http_build_query($queryParams) : '';
        $fullUri = $path . $queryString;
        $url = $this->baseUrl . $fullUri;

        $cacheKey = 'blogger_api_' . md5($this->apiKey . '_' . $fullUri);
        $ttl = $ttl ?? $this->defaultTtl;

        $bypassCache = (function_exists('request') && request() && (request()->has('nocache') || request()->has('refresh')));

        if ($this->cacheEnabled && $ttl > 0 && !$bypassCache && Cache::has($cacheKey)) {
            return Cache::get($cacheKey, []);
        }

        try {
            $parsedUrl = parse_url($url);
            $hmacUri = ($parsedUrl['path'] ?? '') . (isset($parsedUrl['query']) ? '?' . $parsedUrl['query'] : '');

            $headers = $this->generateHeaders('GET', $hmacUri);

            $response = Http::withHeaders($headers)
                ->timeout($this->timeout)
                ->get($url);

            if ($response->successful()) {
                $data = $response->json() ?? [];
                if ($this->cacheEnabled && $ttl > 0) {
                    Cache::put($cacheKey, $data, now()->addSeconds($ttl));
                }
                return $data;
            }

            Log::error("BloggerApiClient GET error [{$url}]: HTTP {$response->status()}", [
                'body' => $response->body(),
            ]);

            return [];
        } catch (\Throwable $e) {
            Log::error("BloggerApiClient GET exception [{$url}]: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Perform an authenticated POST request.
     */
    public function post(string $path, array $payload = []): array
    {
        $path = '/' . ltrim($path, '/');
        $url = $this->baseUrl . $path;

        try {
            $parsedUrl = parse_url($url);
            $hmacUri = $parsedUrl['path'] ?? $path;
            $headers = $this->generateHeaders('POST', $hmacUri);

            $response = Http::withHeaders($headers)
                ->timeout($this->timeout)
                ->post($url, $payload);

            return [
                'success' => $response->successful(),
                'status' => $response->status(),
                'data' => $response->json() ?? [],
                'raw' => $response->body(),
            ];
        } catch (\Throwable $e) {
            Log::error("BloggerApiClient POST exception [{$url}]: " . $e->getMessage());
            return [
                'success' => false,
                'status' => 500,
                'data' => ['message' => 'Unable to connect to server.'],
            ];
        }
    }

    /**
     * High-level helper methods
     */
    public function getHomeData(): array
    {
        return $this->get('/website/home', [], 60);
    }

    public function getBlog(string $slug): array
    {
        return $this->get("/website/blogs/{$slug}", [], 0);
    }

    public function getCategoryBlogs(string $slug, int $page = 1): array
    {
        return $this->get("/website/categoryBlogs/{$slug}", ['page' => $page], 60);
    }

    public function getSubCategoryBlogs(string $slug, int $page = 1): array
    {
        return $this->get("/website/subCategoryBlogs/{$slug}", ['page' => $page], 60);
    }

    public function searchBlogs(string $term, int $page = 1): array
    {
        return $this->get('/website/search', ['search' => $term, 'page' => $page], 15);
    }

    public function getSitemap(): array
    {
        return $this->get('/website/sitemap', [], 3600);
    }

    public function getMetaTags(string $pageName): array
    {
        return $this->get("/website/metaTags/{$pageName}", [], 30);
    }

    public function getWebsiteAds(): array
    {
        return $this->get('/website/ads', [], 0);
    }

    public function submitComment(array $data): array
    {
        return $this->post('/website/blog/comment/store', $data);
    }

    public function submitSubscriber(string $email): array
    {
        return $this->post('/website/subscribe/store', ['email' => $email]);
    }

    public function submitContactMessage(array $data): array
    {
        return $this->post('/website/contact/store', $data);
    }

    /**
     * Viral Quizzes & Challenges API Methods
     */
    public function getQuizzes(): array
    {
        return $this->get('/website/quizzes', [], 30);
    }

    public function getQuizDetail(string $slug): array
    {
        return $this->get("/website/quizzes/{$slug}", [], 0);
    }

    public function createQuizChallenge(string $slug, array $data): array
    {
        return $this->post("/website/quizzes/{$slug}/create-challenge", $data);
    }

    public function getQuizChallenge(string $token, bool $isCreator = false): array
    {
        $query = $isCreator ? ['creator' => 1] : [];
        return $this->get("/website/quiz-challenge/{$token}", $query, 0);
    }

    public function submitQuizChallengeAttempt(string $token, array $data): array
    {
        return $this->post("/website/quiz-challenge/{$token}/submit", $data);
    }

    public function getQuizChallengeLeaderboard(string $token): array
    {
        return $this->get("/website/quiz-challenge/{$token}/leaderboard", [], 0);
    }

    public function getQuizChallengeAttempt(string $token, int $attemptId): array
    {
        return $this->get("/website/quiz-challenge/{$token}/attempt/{$attemptId}", [], 0);
    }

    /**
     * Download binary media stream for proxying.
     */
    public function downloadMediaStream(string $token): ?array
    {
        $url = $this->baseUrl . "/website/media/{$token}";
        try {
            $parsedUrl = parse_url($url);
            $hmacUri = $parsedUrl['path'] ?? "/api/v1/website/media/{$token}";
            $headers = $this->generateHeaders('GET', $hmacUri);

            $response = Http::withHeaders($headers)
                ->timeout(15)
                ->get($url);

            if ($response->successful()) {
                return [
                    'body' => $response->body(),
                    'contentType' => $response->header('Content-Type') ?: 'image/webp',
                ];
            }
        } catch (\Throwable $e) {
            Log::error("Media stream download error [{$token}]: " . $e->getMessage());
        }

        return null;
    }
}
