<?php

namespace App\Http\Controllers;

use App\Services\BloggerApiClient;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    protected BloggerApiClient $client;

    public function __construct(BloggerApiClient $client)
    {
        $this->client = $client;
    }

    public function show(string $slug)
    {
        $data = $this->client->getBlog($slug);

        if (empty($data) || empty($data['blog'])) {
            abort(404, 'Article not found');
        }

        return view('blog-detail', [
            'blog' => $data['blog'],
            'comments' => $data['comments'] ?? [],
            'relatedBlogs' => $data['relatedBlogs'] ?? [],
            'nextBlog' => $data['nextBlog'] ?? null,
            'prevBlog' => $data['prevBlog'] ?? null,
            'metaTags' => $data['metaTags'] ?? '',
        ]);
    }

    public function comment(Request $request, string $slug)
    {
        $request->validate([
            'blog_id' => 'required',
            'full_name' => 'nullable|string|max:100',
            'name' => 'nullable|string|max:100',
            'email' => 'required|email|max:150',
            'description' => 'nullable|string|max:2000',
            'comment' => 'nullable|string|max:2000',
        ]);

        $fullName = $request->input('full_name') ?: $request->input('name');
        $desc = $request->input('description') ?: $request->input('comment');

        if (empty($fullName) || empty($desc)) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Name and comment are required.'], 422);
            }
            return back()->with('error', 'Name and comment are required.');
        }

        $response = $this->client->submitComment([
            'blog_id' => $request->input('blog_id'),
            'full_name' => $fullName,
            'email' => $request->input('email'),
            'description' => $desc,
        ]);

        $isSuccess = ($response['success'] ?? false) || (($response['status'] ?? 500) === 200);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => $isSuccess,
                'message' => $isSuccess ? 'Your comment has been posted successfully!' : ($response['data']['message'] ?? 'Unable to submit comment.'),
                'comment' => [
                    'full_name' => $fullName,
                    'name' => $fullName,
                    'description' => $desc,
                    'comment' => $desc,
                    'created_at' => now()->toIso8601String(),
                ]
            ], $isSuccess ? 200 : ($response['status'] ?? 500));
        }

        if ($isSuccess) {
            return back()->with('success', 'Your comment has been posted successfully!');
        }

        return back()->with('error', 'Unable to submit comment at this time. Please try again.');
    }

    public function random()
    {
        $homeData = $this->client->getHomeData();
        $blogs = array_merge(
            $homeData['headerSliderBlogs'] ?? [],
            $homeData['featuredBlogs'] ?? [],
            $homeData['recentBlogs'] ?? []
        );

        if (!empty($blogs)) {
            $randomBlog = $blogs[array_rand($blogs)];
            if (!empty($randomBlog['slug'])) {
                return redirect()->route('blog.show', $randomBlog['slug']);
            }
        }

        return redirect()->route('home');
    }
}
