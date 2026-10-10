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

        $w = (int) $request->query('w', 0);
        $targetWidth = $w > 0 ? min(1200, max(150, $w)) : 800;
        $quality = 65; // High-efficiency WebP compression meeting Google PageSpeed threshold

        $cacheDir = storage_path('app/public/blogger_media');
        $cachePath = $cacheDir . '/' . md5($token . '_v5_w' . $targetWidth . '_q' . $quality) . '.webp';

        // Check local cache on disk
        if (File::exists($cachePath)) {
            $mimeType = File::mimeType($cachePath) ?: 'image/webp';
            return response()->file($cachePath, [
                'Content-Type' => $mimeType,
                'Cache-Control' => 'public, max-age=31536000, immutable',
            ]);
        }

        // Fetch from backend
        $stream = $this->client->downloadMediaStream($token);

        if (!$stream || empty($stream['body'])) {
            return redirect('/images/placeholder.svg');
        }

        // Cache locally with GD WebP compression (quality 65)
        $isWebpSaved = false;
        try {
            if (!File::isDirectory($cacheDir)) {
                File::makeDirectory($cacheDir, 0755, true);
            }

            if (function_exists('imagecreatefromstring') && function_exists('imagewebp') && !str_contains($stream['contentType'] ?? '', 'svg')) {
                $img = @imagecreatefromstring($stream['body']);
                if ($img !== false) {
                    imagepalettetotruecolor($img);
                    imagealphablending($img, true);
                    imagesavealpha($img, true);

                    // Downscale if image width exceeds layout requirement
                    $width = imagesx($img);
                    $height = imagesy($img);
                    if ($width > $targetWidth && $height > 0) {
                        $newHeight = (int) round(($height * $targetWidth) / $width);
                        $resized = imagecreatetruecolor($targetWidth, $newHeight);
                        imagealphablending($resized, false);
                        imagesavealpha($resized, true);
                        imagecopyresampled($resized, $img, 0, 0, 0, 0, $targetWidth, $newHeight, $width, $height);
                        imagedestroy($img);
                        $img = $resized;
                    }

                    if (@imagewebp($img, $cachePath, $quality)) {
                        $isWebpSaved = true;
                    }
                    imagedestroy($img);
                }
            }

            if (!$isWebpSaved && !File::exists($cachePath)) {
                File::put($cachePath, $stream['body']);
            }
        } catch (\Throwable $e) {
            // Ignore write errors and stream anyway
        }

        return response()->file($cachePath, [
            'Content-Type' => $isWebpSaved ? 'image/webp' : ($stream['contentType'] ?? 'image/webp'),
            'Cache-Control' => 'public, max-age=31536000, immutable',
        ]);
    }
}
