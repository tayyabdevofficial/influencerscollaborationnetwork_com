<?php

return [
    'api_url' => env('BLOGGER_API_URL', 'http://127.0.0.1:8000/api/v1'),
    'api_key' => env('BLOGGER_API_KEY', ''),
    'api_secret' => env('BLOGGER_API_SECRET', ''),
    'client_domain' => env('BLOGGER_CLIENT_DOMAIN', 'influencerscollaborationnetwork.com'),
    'cache_ttl' => env('BLOGGER_CACHE_TTL', 3600),
    'proxy_media' => env('BLOGGER_PROXY_MEDIA', true),
    'media_cache_days' => env('BLOGGER_MEDIA_CACHE_DAYS', 30),
];
