<?php

use App\Http\Controllers\InquiryController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\TripController;
use App\Models\HomeContent;
use App\Models\Package;
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
// otherwise swallow "/reviews" as if it were a package slug.
Route::get('/reviews', [ReviewController::class, 'index'])->name('reviews.index');
Route::post('/reviews', [ReviewController::class, 'store'])->name('reviews.store');

Route::post('/inquiries', [InquiryController::class, 'store'])->name('inquiries.store');

Route::get('/{trip}', [TripController::class, 'show'])
    ->where('trip', '[a-z0-9-]+')
    ->name('trip.show');
