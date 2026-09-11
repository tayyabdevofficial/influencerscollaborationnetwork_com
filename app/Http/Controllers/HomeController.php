<?php

namespace App\Http\Controllers;

use App\Services\BloggerApiClient;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    protected BloggerApiClient $client;

    public function __construct(BloggerApiClient $client)
    {
        $this->client = $client;
    }

    public function index(Request $request)
    {
        $data = $this->client->getHomeData();

        return view('home', [
            'headerSliderBlogs' => $data['headerSliderBlogs'] ?? [],
            'featuredBlogs' => $data['featuredBlogs'] ?? [],
            'trendingTopics' => $data['trendingTopics'] ?? [],
            'recentBlogs' => $data['recentBlogs'] ?? [],
            'todayTopBlogs' => $data['todayTopBlogs'] ?? [],
            'allCategories' => $data['allCategories'] ?? [],
            'metaTags' => $data['metaTags'] ?? '',
            'siteTitle' => config('site.name') . ' - ' . config('site.tagline'),
            'siteDescription' => config('site.description'),
        ]);
    }
}
