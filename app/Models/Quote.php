<?php

namespace App\Models;

use App\Enums\QuoteSource;
use App\Enums\QuoteStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Quote extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'status' => QuoteStatus::class,
        'source' => QuoteSource::class,
        'adults' => 'integer',
        'children' => 'integer',
        'travel_start' => 'date',
        'travel_end' => 'date',
        'items' => 'array',
        'discount' => 'decimal:2',
        'show_bank_details' => 'boolean',
        'valid_until' => 'date',
    ];

    protected static function booted(): void
    {
        // Defense in depth: both NOT NULL columns default at the schema
        // level, but that default is skipped if a caller explicitly passes
        // null (as the admin form's inquiry-prefill path once did).
        static::creating(function (Quote $quote) {
            $quote->adults ??= 1;
            $quote->children ??= 0;
        });

        // The reference embeds the row's own id, so it can only be set once
        // the insert has actually happened and $this->id exists.
        static::created(function (Quote $quote) {
            if (blank($quote->reference)) {
                $quote->reference = sprintf('QT-%s-%06d', now()->year, $quote->id);
                $quote->saveQuietly();
            }
        });
    }

    public function package(): BelongsTo
    {
        return $this->belongsTo(Package::class);
    }

    public function subtotal(): float
    {
        return collect($this->items ?? [])->sum(
            fn (array $item) => (float) ($item['quantity'] ?? 0) * (float) ($item['unit_price'] ?? 0)
        );
    }

    public function total(): float
    {
        return max(0, $this->subtotal() - (float) $this->discount);
    }

    public function money(float $amount): string
    {
        return 'US$ '.number_format($amount, 0);
    }

    public function pdfFilename(): string
    {
        return Str::slug($this->reference ?: 'quote').'.pdf';
    }

    /**
     * One line per traveler group, following the shared adult/child pricing
     * rule: adults pay the package's full price, children under 16 pay a
     * configurable percentage of it. Used by both the public quote request
     * and the admin "auto-fill from product" action, so the two can never
     * price a trip differently.
     *
     * @return array<int, array{description: string, quantity: int, unit_price: float}>
     */
    public static function itemsForPackage(Package $package, int $adults, int $children): array
    {
        $label = trim($package->name.($package->days ? " · {$package->days} Days" : ''));
        $items = [];

        if ($adults > 0) {
            $items[] = [
                'description' => "{$label} — adult",
                'quantity' => $adults,
                'unit_price' => (float) $package->price,
            ];
        }

        if ($children > 0) {
            $childPrice = round((float) $package->price * (Setting::childPricePercent() / 100), 2);

            $items[] = [
                'description' => "{$label} — child (under 16)",
                'quantity' => $children,
                'unit_price' => $childPrice,
            ];
        }

        return $items;
    }

    public static function travelEndFor(Package $package, \DateTimeInterface $start): ?\Illuminate\Support\Carbon
    {
        if (! $package->days) {
            return null;
        }

        return \Illuminate\Support\Carbon::parse($start)->addDays(max(0, $package->days - 1));
    }
}
