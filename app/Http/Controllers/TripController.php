<?php

namespace App\Http\Controllers;

use App\Models\Package;
use Illuminate\View\View;

class TripController extends Controller
{
    public function show(string $trip): View
    {
        $package = Package::published()->with('stays')->where('slug', $trip)->first();

        abort_unless($package, 404);

        $related = Package::published()
            ->ordered()
            ->where('category', $package->category)
            ->whereKeyNot($package->getKey())
            ->take(3)
            ->get()
            ->map
            ->toTripArray()
            ->all();

        return view('trip', [
            'trip' => $package->toTripArray(),
            'related' => $related,
            'trips' => Package::forSite(),
        ]);
    }
}
