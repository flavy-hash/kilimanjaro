<?php

namespace App\Filament\Resources\Quotes\Pages;

use App\Enums\QuoteSource;
use App\Enums\QuoteStatus;
use App\Filament\Resources\Quotes\QuoteResource;
use App\Models\Inquiry;
use App\Models\Quote;
use App\Models\Setting;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Carbon;
use Livewire\Attributes\Url;

class CreateQuote extends CreateRecord
{
    protected static string $resource = QuoteResource::class;

    /**
     * Reached from the Inquiries list's "Create quote" row action
     * (?inquiry=123), so the admin doesn't have to retype a customer's
     * details that are already sitting on their booking inquiry.
     */
    #[Url]
    public ?int $inquiry = null;

    protected function fillForm(): void
    {
        $inquiry = $this->inquiry ? Inquiry::with('package')->find($this->inquiry) : null;

        if (! $inquiry) {
            parent::fillForm();

            return;
        }

        $adults = $inquiry->group_size ?: 1;

        // Every field QuoteForm declares is listed explicitly — filling with
        // only the inquiry-derived values would leave the rest (children,
        // status, show_bank_details, discount, …) null instead of their
        // schema default, which the "children" column's NOT NULL constraint
        // doesn't allow.
        $this->form->fill([
            'status' => QuoteStatus::Draft->value,
            'show_bank_details' => true,
            'discount' => 0,
            'customer_name' => $inquiry->name,
            'customer_email' => $inquiry->email,
            'customer_phone' => $inquiry->phone,
            'customer_country' => null,
            'package_id' => $inquiry->package_id,
            'adults' => $adults,
            'children' => 0,
            'travel_start' => $inquiry->start_date?->toDateString(),
            'travel_end' => ($inquiry->package && $inquiry->start_date)
                ? Quote::travelEndFor($inquiry->package, Carbon::parse($inquiry->start_date))?->toDateString()
                : null,
            'valid_until' => now()->addDays(Setting::quoteValidityDays())->toDateString(),
            'items' => $inquiry->package
                ? Quote::itemsForPackage($inquiry->package, $adults, 0)
                : [],
            'notes' => null,
        ]);
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['source'] = QuoteSource::Admin->value;

        return $data;
    }
}
