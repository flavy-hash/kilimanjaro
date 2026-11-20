<?php

namespace App\Http\Controllers;

use App\Models\HomeContent;
use App\Models\PdfDownloadLead;
use App\Models\Package;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\URL;
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
            // Same admin-managed "why choose us" cards as the homepage.
            'whyItems' => HomeContent::current()->why_items,
        ]);
    }

    /**
     * Captures the visitor's email, then hands them a short-lived signed
     * link to the real download — the only way to reach pdf() below.
     */
    public function pdfRequest(Request $request, string $trip): RedirectResponse
    {
        $package = Package::published()->where('slug', $trip)->first();

        abort_unless($package, 404);

        $data = $request->validate([
            'email' => ['required', 'email', 'max:255'],
        ]);

        PdfDownloadLead::create([
            'email' => $data['email'],
            'package_slug' => $package->slug,
            'package_name' => $package->name,
        ]);

        return redirect()->to(
            URL::temporarySignedRoute('trip.pdf', now()->addMinutes(15), ['trip' => $trip])
        );
    }

    public function pdf(string $trip): Response
    {
        $package = Package::published()->with('stays')->where('slug', $trip)->first();

        abort_unless($package, 404);

        $pdf = Pdf::loadView('pdf.trip', [
            'trip' => $package->toTripArray(),
            'home' => HomeContent::current(),
        ])->setPaper('a4');

        return $pdf->download($trip.'.pdf');
    }
}
