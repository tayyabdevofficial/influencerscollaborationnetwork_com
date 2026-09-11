<?php

namespace App\Http\Controllers;

use App\Services\BloggerApiClient;
use Illuminate\Http\Request;

class NewsletterController extends Controller
{
    protected BloggerApiClient $client;

    public function __construct(BloggerApiClient $client)
    {
        $this->client = $client;
    }

    public function subscribe(Request $request)
    {
        $request->validate([
            'email' => 'required|email|max:150',
        ]);

        $response = $this->client->submitSubscriber($request->input('email'));

        $isSuccess = ($response['success'] ?? false) || (($response['status'] ?? 500) === 200);
        $message = $response['data']['message'] ?? ($isSuccess 
            ? 'Thank you for subscribing to Creator Studio Insider!' 
            : 'Unable to subscribe at this moment. Please try again.');

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => $isSuccess,
                'message' => $message,
            ], $isSuccess ? 200 : ($response['status'] ?? 400));
        }

        if ($isSuccess) {
            return back()->with('newsletter_success', $message);
        }

        return back()->with('newsletter_error', $message);
    }
}
