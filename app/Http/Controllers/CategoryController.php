<?php

namespace App\Http\Controllers;

use App\Services\BloggerApiClient;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    protected BloggerApiClient $client;

    public function __construct(BloggerApiClient $client)
    {
        $this->client = $client;
    }

    public function category(Request $request, string $slug)
    {
        $page = (int) $request->get('page', 1);
        $data = $this->client->getCategoryBlogs($slug, $page);

        if (empty($data) || empty($data['category'])) {
            abort(404, 'Category not found');
        }

        return view('category', [
            'category' => $data['category'],
            'blogs' => $data['blogs'] ?? [],
            'subCategories' => $data['subCategories'] ?? [],
            'metaTags' => $data['metaTags'] ?? '',
            'isSubCategory' => false,
        ]);
    }

    public function subCategory(Request $request, string $slug)
    {
        $page = (int) $request->get('page', 1);
        $data = $this->client->getSubCategoryBlogs($slug, $page);

        if (empty($data) || empty($data['subCategory'])) {
            abort(404, 'Subcategory not found');
        }

        return view('category', [
            'category' => $data['subCategory'],
            'blogs' => $data['blogs'] ?? [],
            'subCategories' => [],
            'metaTags' => $data['metaTags'] ?? '',
            'isSubCategory' => true,
        ]);
    }
}
