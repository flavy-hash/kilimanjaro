<?php

use App\Http\Controllers\InquiryController;
use App\Http\Controllers\TripController;
use App\Models\Package;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome', ['trips' => Package::forSite()]);
})->name('home');

Route::get('/tours', function () {
    return view('tours', ['trips' => Package::forSite()]);
})->name('tours');

Route::post('/inquiries', [InquiryController::class, 'store'])->name('inquiries.store');

Route::get('/{trip}', [TripController::class, 'show'])
    ->where('trip', '[a-z0-9-]+')
    ->name('trip.show');
