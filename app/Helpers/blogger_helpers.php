<?php

if (!function_exists('blogger_media_url')) {
    /**
     * Transform any backend image URL or token to local proxied URL /media/{token}.
     * Ensures zero exposure of the backend admin host or storage path.
     */
    function blogger_media_url(?string $rawPath, string $fallback = '/images/placeholder.svg', ?int $width = null): string
    {
        if (empty($rawPath)) {
            return asset($fallback);
        }

        // Check if rawPath contains /media/{token} (capture full token including base64 padding)
        if (preg_match('#/media/([^?&]+)#', $rawPath, $matches)) {
            $token = $matches[1];
            $query = $width ? '?w=' . $width : '';
            return url('/media/' . $token . $query);
        }

        // If it's a full URL without /media/
        if (str_starts_with($rawPath, 'http://') || str_starts_with($rawPath, 'https://')) {
            if ($width) {
                return $rawPath . (str_contains($rawPath, '?') ? '&' : '?') . 'w=' . $width;
            }
            return $rawPath;
        }

        // If it's already just a raw token string
        $query = $width ? '?w=' . $width : '';
        return url('/media/' . $rawPath . $query);
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
