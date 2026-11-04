<?php

namespace App\Mail;

use App\Models\Quote;
use App\Services\QuotePdfBuilder;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Attachment;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewQuoteReceived extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Quote $quote)
    {
        //
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "New instant quote {$this->quote->reference} — {$this->quote->customer_name}",
            replyTo: [new Address($this->quote->customer_email, $this->quote->customer_name)],
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.new-quote',
        );
    }

    /** @return array<int, Attachment> */
    public function attachments(): array
    {
        $pdf = app(\App\Services\QuotePdfBuilder::class)->build($this->quote);

        return [
            Attachment::fromData(fn () => $pdf->output(), $this->quote->pdfFilename())
                ->withMime('application/pdf'),
        ];
    }
}
