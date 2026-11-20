<?php

use App\Http\Controllers\BlogController;
use App\Http\Controllers\InquiryController;
use App\Http\Controllers\QuoteController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\TripController;
use App\Models\AboutContent;
use App\Models\ClimbingPrepContent;
use App\Models\EastAfricaPriceContent;
use App\Models\HomeContent;
use App\Models\Package;
use App\Models\Post;
use App\Models\Review;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome', [
        'trips' => Package::forSite(),
        'featuredReviews' => Review::approved()->featured()->with('package')->latest('stayed_at')->take(3)->get(),
        'home' => HomeContent::current(),
    ]);
})->name('home');

Route::get('/tours', function () {
    return view('tours', ['trips' => Package::forSite()]);
})->name('tours');

// Declared before the catch-all "/{trip}" route below, which would
// otherwise swallow "/reviews" (and "/about", "/team") as if they were
// package slugs.
Route::get('/reviews', [ReviewController::class, 'index'])->name('reviews.index');
Route::post('/reviews', [ReviewController::class, 'store'])->name('reviews.store');

Route::get('/about', function () {
    return view('about', ['about' => AboutContent::current()]);
})->name('about');

Route::get('/team', function () {
    return view('team', ['about' => AboutContent::current()]);
})->name('team');

Route::get('/climbing-preparation', function () {
    return view('climbing-preparation', [
        'prep' => ClimbingPrepContent::current(),
        'equipPdf' => HomeContent::current()->equip_pdf,
    ]);
})->name('climbing-prep');

Route::get('/kilimanjaro-east-african-citizens', function () {
    return view('east-africa-prices', [
        'page' => EastAfricaPriceContent::current(),
        'groups' => EastAfricaPriceContent::GROUPS,
        'liveSlugs' => Package::published()->pluck('slug')->all(),
    ]);
})->name('east-africa-prices');

Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/{post}', [BlogController::class, 'show'])->name('blog.show');

Route::post('/inquiries', [InquiryController::class, 'store'])->name('inquiries.store');

Route::post('/quotes', [QuoteController::class, 'store'])
    ->middleware('throttle:quotes')
    ->name('quotes.store');

Route::get('/quotes/{quote}/pdf', [QuoteController::class, 'pdf'])
    ->middleware('signed')
    ->name('quotes.pdf');

Route::get('/robots.txt', function () {
    $lines = [
        'User-agent: *',
        'Disallow: /admin',
        '',
        'Sitemap: '.route('sitemap'),
    ];

    return response(implode("\n", $lines))->header('Content-Type', 'text/plain');
});

Route::get('/sitemap.xml', function () {
    $staticPages = [
        ['url' => route('home'), 'priority' => '1.0', 'changefreq' => 'weekly'],
        ['url' => route('tours'), 'priority' => '0.9', 'changefreq' => 'weekly'],
        ['url' => route('reviews.index'), 'priority' => '0.6', 'changefreq' => 'weekly'],
        ['url' => route('about'), 'priority' => '0.5', 'changefreq' => 'monthly'],
        ['url' => route('team'), 'priority' => '0.5', 'changefreq' => 'monthly'],
        ['url' => route('climbing-prep'), 'priority' => '0.6', 'changefreq' => 'monthly'],
        ['url' => route('east-africa-prices'), 'priority' => '0.7', 'changefreq' => 'monthly'],
        ['url' => route('blog.index'), 'priority' => '0.7', 'changefreq' => 'weekly'],
    ];

    $packages = Package::published()->ordered()->get(['slug', 'updated_at'])->map(fn ($p) => [
        'url' => url($p->slug),
        'lastmod' => $p->updated_at?->toAtomString(),
        'priority' => '0.8',
        'changefreq' => 'monthly',
    ]);

    $posts = Post::published()->ordered()->get(['slug', 'updated_at'])->map(fn ($p) => [
        'url' => route('blog.show', $p->slug),
        'lastmod' => $p->updated_at?->toAtomString(),
        'priority' => '0.6',
        'changefreq' => 'monthly',
    ]);

    $urls = collect($staticPages)->concat($packages)->concat($posts);

    return response()
        ->view('sitemap', ['urls' => $urls])
        ->header('Content-Type', 'application/xml');
})->name('sitemap');

Route::post('/{trip}/pdf-request', [TripController::class, 'pdfRequest'])
    ->where('trip', '[a-z0-9-]+')
    ->name('trip.pdf.request');

Route::get('/{trip}/pdf', [TripController::class, 'pdf'])
    ->where('trip', '[a-z0-9-]+')
    ->middleware('signed')
    ->name('trip.pdf');

Route::get('/{trip}', [TripController::class, 'show'])
    ->where('trip', '[a-z0-9-]+')
    ->name('trip.show');
