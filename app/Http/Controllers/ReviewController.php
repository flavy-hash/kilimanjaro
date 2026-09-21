<?php

namespace App\Http\Controllers;

use App\Models\Package;
use App\Models\Review;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReviewController extends Controller
{
    public function index(): View
    {
        $reviews = Review::approved()
            ->with('package')
            ->latest('stayed_at')
            ->get();

        $count = $reviews->count();
        $avg = fn ($col) => $count ? round($reviews->avg($col), 1) : 0;

        return view('reviews', [
            'reviews' => $reviews->map->toCardArray()->all(),
            'stats' => [
                'count' => $count,
                'overall' => $avg('rating'),
                'service' => $avg('service_rating'),
                'value' => $avg('value_rating'),
            ],
            'packages' => Package::published()->ordered()->get(['id', 'slug', 'name', 'category']),
            'trips' => Package::forSite(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['nullable', 'email', 'max:160'],
            'package' => ['nullable', 'string', 'exists:packages,slug'],
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'service_rating' => ['nullable', 'integer', 'min:1', 'max:5'],
            'value_rating' => ['nullable', 'integer', 'min:1', 'max:5'],
            'title' => ['required', 'string', 'max:160'],
            'body' => ['required', 'string', 'max:2000'],
            'photo' => ['nullable', 'image', 'max:4096'],
        ]);

        $package = isset($data['package'])
            ? Package::where('slug', $data['package'])->first()
            : null;

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('images/reviews', 'site');
        }

        Review::create([
            'package_id' => $package?->id,
            'name' => $data['name'],
            'email' => $data['email'] ?? null,
            'rating' => $data['rating'],
            'service_rating' => $data['service_rating'] ?? null,
            'value_rating' => $data['value_rating'] ?? null,
            'title' => $data['title'],
            'body' => $data['body'],
            'photo' => $photoPath,
            'stayed_at' => now(),
            // Every public submission needs a look before it goes live.
            'is_approved' => false,
        ]);

        return redirect()
            ->route('reviews.index')
            ->with('review_submitted', true);
    }
}
