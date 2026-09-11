<?php

namespace App\Http\Controllers;

use App\Services\BloggerApiClient;
use Illuminate\Http\Request;

class PageController extends Controller
{
    protected BloggerApiClient $client;

    public function __construct(BloggerApiClient $client)
    {
        $this->client = $client;
    }

    public function about()
    {
        return view('pages.about');
    }

    public function contact()
    {
        return view('pages.contact');
    }

    public function contactSubmit(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:150',
            'subject' => 'nullable|string|max:150',
            'message' => 'required|string|max:3000',
        ]);

        $response = $this->client->submitContactMessage([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'subject' => $validated['subject'] ?? 'Collaboration Inquiry',
            'message' => $validated['message'],
        ]);

        $isSuccess = ($response['success'] ?? false) || (($response['status'] ?? 500) === 200);
        $message = $response['data']['message'] ?? ($isSuccess 
            ? 'Your message has been received! Our collaboration team will get back to you shortly.'
            : 'Unable to send message. Please try again.');

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => $isSuccess,
                'message' => $message,
            ], $isSuccess ? 200 : ($response['status'] ?? 400));
        }

        if ($isSuccess) {
            return back()->with('success', $message);
        }

        return back()->with('error', $message);
    }

    public function privacy()
    {
        return view('pages.privacy');
    }

    public function terms()
    {
        return view('pages.terms');
    }

    public function cookies()
    {
        return view('pages.cookies');
    }
}
