<?php

namespace App\Services;

use App\Models\Quote;
use App\Models\Setting;
use Barryvdh\DomPDF\Facade\Pdf;
use Barryvdh\DomPDF\PDF as PdfInstance;

/**
 * Builds the quotation PDF the same way everywhere it's needed — the guest's
 * signed download, the office notification email's attachment, and the
 * admin's "Download PDF" / "Email to customer" actions — so none of them can
 * drift out of sync with each other.
 */
class QuotePdfBuilder
{
    public function build(Quote $quote): PdfInstance
    {
        $logoPath = public_path('apple-touch-icon.png');

        return Pdf::loadView('pdf.quote', [
            'quote' => $quote,
            'logoBase64' => is_file($logoPath) ? base64_encode(file_get_contents($logoPath)) : null,
            'bankAccounts' => Setting::quoteBankAccounts(),
            'paymentInstructions' => Setting::quotePaymentInstructions(),
            'terms' => Setting::quoteTerms(),
        ])->setPaper('a4');
    }
}
