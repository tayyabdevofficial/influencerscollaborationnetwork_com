<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class WebhookReceiverController extends Controller
{
    /**
     * Instant Cache Purge Webhook endpoint.
     * Called by AutomateWrite whenever a blog is published, updated, or deleted,
     * or when ads/settings are toggled.
     */
    public function handle(Request $request): JsonResponse
    {
        $signature = $request->header('X-Signature') ?? $request->header('X-Webhook-Signature');
        $event = $request->input('event', 'cache.purge');
        $secret = config('blogger.api_secret');

        // Signature validation if secret is configured
        if ($secret && $signature) {
            $expected = hash_hmac('sha256', $request->getContent(), $secret);
            if (!hash_equals($expected, $signature)) {
                return response()->json(['error' => 'Invalid signature'], 401);
            }
        }

        // Flush application cache
        try {
            Cache::flush();
            Log::info("Influencers Collaboration Network cache purged successfully via webhook event: {$event}");
        } catch (\Throwable $e) {
            Log::error("Failed to purge cache on webhook: " . $e->getMessage());
        }

        return response()->json([
            'status' => 'success',
            'event' => $event,
            'message' => 'Influencers Collaboration Network cache cleared successfully.',
            'purged_at' => now()->toIso8601String(),
        ]);
    }

    /**
     * Heartbeat health monitor endpoint.
     */
    public function health(): JsonResponse
    {
        return response()->json([
            'status' => 'ok',
            'website' => config('site.name'),
            'domain' => config('site.domain'),
            'timestamp' => now()->toIso8601String(),
            'cache_driver' => config('cache.default'),
        ]);
    }
}
