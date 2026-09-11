<?php

namespace App\Http\Controllers;

use App\Services\BloggerApiClient;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    protected BloggerApiClient $client;

    public function __construct(BloggerApiClient $client)
    {
        $this->client = $client;
    }

    public function index(Request $request)
    {
        $term = trim($request->get('q', ''));
        $page = (int) $request->get('page', 1);

        $results = [];
        if (!empty($term)) {
            $data = $this->client->searchBlogs($term, $page);
            $results = $data['blogs'] ?? [];
        }

        return view('search', [
            'query' => $term,
            'blogs' => $results,
        ]);
    }
}
