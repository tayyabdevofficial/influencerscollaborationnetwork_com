<?php

use App\Http\Controllers\BlogController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MediaProxyController;
use App\Http\Controllers\NewsletterController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\WebhookReceiverController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes for Influencers Collaboration Network
|--------------------------------------------------------------------------
*/

// Home
Route::get('/', [HomeController::class, 'index'])->name('home');

// Creator Quizzes & Viral Challenges
Route::get('/quizzes', [QuizController::class, 'index'])->name('quizzes.index');
Route::get('/quiz/{slug}', [QuizController::class, 'createChallenge'])->name('quizzes.create');
Route::post('/quiz/{slug}/create', [QuizController::class, 'storeChallenge'])->name('quizzes.store');
Route::get('/quiz/challenge/{token}/share', [QuizController::class, 'shareDashboard'])->name('quizzes.challenge.share');
Route::get('/quiz/challenge/{token}', [QuizController::class, 'takeChallenge'])->name('quizzes.challenge.take');
Route::post('/quiz/challenge/{token}/submit', [QuizController::class, 'submitAttempt'])->name('quizzes.challenge.submit');
Route::get('/quiz/challenge/{token}/score', [QuizController::class, 'showScore'])->name('quizzes.challenge.score');

// Media Proxy (Secure obfuscated streaming with local caching)
Route::get('/media/{token}', [MediaProxyController::class, 'stream'])->name('media.proxy');
Route::get('/api/v1/website/media/{token}', [MediaProxyController::class, 'stream']);

// Search
Route::get('/search', [SearchController::class, 'index'])->name('search');

// Blog Detail & Comments
Route::get('/random-story', [BlogController::class, 'random'])->name('blog.random');
Route::get('/blog/{slug}', [BlogController::class, 'show'])->name('blog.show');
Route::post('/blog/{slug}/comment', [BlogController::class, 'comment'])->name('blog.comment');

// Categories & Subcategories
Route::get('/category/{slug}', [CategoryController::class, 'category'])->name('category.show');
Route::get('/subcategory/{slug}', [CategoryController::class, 'subCategory'])->name('subcategory.show');

// Newsletter Subscription
Route::post('/newsletter', [NewsletterController::class, 'subscribe']);
Route::post('/newsletter/subscribe', [NewsletterController::class, 'subscribe'])->name('newsletter.subscribe');

// SEO Sitemap
Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');

// Informational & Legal Pages
Route::get('/about-us', [PageController::class, 'about'])->name('pages.about');
Route::get('/contact-us', [PageController::class, 'contact'])->name('pages.contact');
Route::post('/contact', [PageController::class, 'contactSubmit']);
Route::post('/contact-us/submit', [PageController::class, 'contactSubmit'])->name('pages.contact.submit');
Route::get('/privacy-policy', [PageController::class, 'privacy'])->name('pages.privacy');
Route::get('/terms-and-conditions', [PageController::class, 'terms'])->name('pages.terms');
Route::get('/cookie-policy', [PageController::class, 'cookies'])->name('pages.cookies');

// Instant Cache Purge Webhook & Health Heartbeat
Route::post('/api/webhook/purge-cache', [WebhookReceiverController::class, 'handle'])->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\ValidateCsrfToken::class]);
Route::get('/api/health', [WebhookReceiverController::class, 'health'])->name('api.health');
