<?php

if (!function_exists('blogger_media_url')) {
    /**
     * Transform any backend image URL or token to local proxied URL /media/{token}.
     * Ensures zero exposure of the backend admin host or storage path.
     */
    function blogger_media_url(?string $rawPath, string $fallback = '/images/placeholder.svg'): string
    {
        if (empty($rawPath)) {
            return asset($fallback);
        }

        // Check if rawPath contains a token (e.g. /media/{token}, /website/media/{token}, /api/v1/website/media/{token})
        if (preg_match('#(?:^|/)media/([a-zA-Z0-9_\-\.]+)#', $rawPath, $matches)) {
            return url('/media/' . $matches[1]);
        }

        // If it's already just a raw token string
        if (preg_match('/^[a-zA-Z0-9_\-\.]+$/', $rawPath) && !str_starts_with($rawPath, 'http')) {
            return url('/media/' . $rawPath);
        }

        return $rawPath;
    }
}

if (!function_exists('blogger_reading_time')) {
    /**
     * Calculate approximate reading time in minutes.
     */
    function blogger_reading_time(?string $content, int $defaultTime = 3): int
    {
        if (empty($content)) {
            return $defaultTime;
        }

        $wordCount = str_word_count(strip_tags($content));
        $minutes = (int) ceil($wordCount / 200);

        return max(1, $minutes);
    }
}

if (!function_exists('blogger_format_date')) {
    /**
     * Format date nicely for blogs.
     */
    function blogger_format_date(?string $dateStr): string
    {
        if (empty($dateStr)) {
            return '';
        }

        try {
            $date = \Carbon\Carbon::parse($dateStr);
            if ($date->isToday()) {
                return 'Today, ' . $date->format('g:i A');
            }
            if ($date->isYesterday()) {
                return 'Yesterday, ' . $date->format('g:i A');
            }
            if ($date->diffInDays(now()) < 7) {
                return $date->diffForHumans();
            }
            return $date->format('M d, Y');
        } catch (\Throwable) {
            return (string) $dateStr;
        }
    }
}
