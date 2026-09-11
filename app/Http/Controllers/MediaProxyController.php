<?php

namespace App\Http\Controllers;

use App\Services\BloggerApiClient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class MediaProxyController extends Controller
{
    protected BloggerApiClient $client;

    public function __construct(BloggerApiClient $client)
    {
        $this->client = $client;
    }

    public function stream(Request $request, string $token)
    {
        // Sanitize token
        if (!preg_match('/^[a-zA-Z0-9_\-\.]+$/', $token)) {
            abort(400, 'Invalid media token');
        }

        $cacheDir = storage_path('app/public/blogger_media');
        $cachePath = $cacheDir . '/' . $token;

        // Check local cache on disk
        if (File::exists($cachePath)) {
            $mimeType = File::mimeType($cachePath) ?: 'image/webp';
            return response()->file($cachePath, [
                'Content-Type' => $mimeType,
                'Cache-Control' => 'public, max-age=2592000, immutable',
            ]);
        }

        // Fetch from backend
        $stream = $this->client->downloadMediaStream($token);

        if (!$stream || empty($stream['body'])) {
            return redirect('/images/placeholder.svg');
        }

        // Cache locally
        try {
            if (!File::isDirectory($cacheDir)) {
                File::makeDirectory($cacheDir, 0755, true);
            }
            File::put($cachePath, $stream['body']);
        } catch (\Throwable $e) {
            // Ignore write errors and stream anyway
        }

        return response($stream['body'], 200, [
            'Content-Type' => $stream['contentType'] ?? 'image/webp',
            'Cache-Control' => 'public, max-age=2592000, immutable',
        ]);
    }
}
