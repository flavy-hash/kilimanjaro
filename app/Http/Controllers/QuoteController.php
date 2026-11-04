<?php

namespace App\Http\Controllers;

use App\Enums\QuoteSource;
use App\Enums\QuoteStatus;
use App\Mail\NewQuoteReceived;
use App\Models\Package;
use App\Models\Quote;
use App\Models\Setting;
use App\Models\User;
use App\Notifications\NewInstantQuote;
use App\Services\QuotePdfBuilder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\URL;

class QuoteController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        // Silently rejected: a real visitor never sees or fills this field.
        if (filled($request->input('website'))) {
            return back()->withErrors(['package' => 'Something went wrong — please try again.'], 'quote');
        }

        $package = Package::published()
            ->where('slug', $request->input('package'))
            ->first();

        abort_unless($package, 404);
        abort_unless($package->price > 0, 404);

        $data = $request->validateWithBag('quote', [
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:160'],
            'phone' => ['nullable', 'string', 'max:40'],
            'country' => ['nullable', 'string', 'max:80'],
            'travel_start' => ['required', 'date', 'after_or_equal:today'],
            'adults' => ['required', 'integer', 'min:1', 'max:30'],
            'children' => ['nullable', 'integer', 'min:0', 'max:30'],
        ]);

        $adults = (int) $data['adults'];
        $children = (int) ($data['children'] ?? 0);
        $travelStart = Carbon::parse($data['travel_start']);

        $quote = Quote::create([
            'source' => QuoteSource::Website,
            'status' => QuoteStatus::Sent,
            'package_id' => $package->id,
            'customer_name' => $data['name'],
            'customer_email' => $data['email'],
            'customer_phone' => $data['phone'] ?? null,
            'customer_country' => $data['country'] ?? null,
            'adults' => $adults,
            'children' => $children,
            'travel_start' => $travelStart,
            'travel_end' => Quote::travelEndFor($package, $travelStart),
            'items' => Quote::itemsForPackage($package, $adults, $children),
            'valid_until' => now()->addDays(Setting::quoteValidityDays()),
            'notes' => 'Instant estimate generated from the website. We will confirm availability and the final price before any payment is taken.',
        ]);

        $this->notifyOffice($quote);

        $pdfUrl = URL::temporarySignedRoute('quotes.pdf', now()->addDays(7), ['quote' => $quote->id]);

        return back()->with('quote_result', [
            'reference' => $quote->reference,
            'total' => $quote->money($quote->total()),
            'pdf_url' => $pdfUrl,
        ]);
    }

    public function pdf(Quote $quote): Response
    {
        $pdf = app(QuotePdfBuilder::class)->build($quote);

        return $pdf->download($quote->pdfFilename());
    }

    private function notifyOffice(Quote $quote): void
    {
        if (! Setting::quoteEmailsEnabled()) {
            return;
        }

        try {
            foreach (Setting::quoteOfficeEmails() as $email) {
                Mail::to($email)->send(new NewQuoteReceived($quote));
            }
        } catch (\Throwable $e) {
            // The quote is already saved — a mail outage must never stop the
            // guest getting their PDF, just get logged for follow-up.
            Log::error('Failed to send quote notification email', [
                'quote_id' => $quote->id,
                'error' => $e->getMessage(),
            ]);
            report($e);
        }

        try {
            Notification::send(User::all(), new NewInstantQuote($quote));
        } catch (\Throwable $e) {
            Log::error('Failed to send quote admin notification', [
                'quote_id' => $quote->id,
                'error' => $e->getMessage(),
            ]);
            report($e);
        }
    }
}
