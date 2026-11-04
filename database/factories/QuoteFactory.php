<?php

namespace Database\Factories;

use App\Enums\QuoteSource;
use App\Enums\QuoteStatus;
use App\Models\Package;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\Quote>
 */
class QuoteFactory extends Factory
{
    public function definition(): array
    {
        return [
            'source' => QuoteSource::Website,
            'status' => QuoteStatus::Sent,
            'package_id' => Package::factory(),
            'customer_name' => fake()->name(),
            'customer_email' => fake()->safeEmail(),
            'adults' => 2,
            'children' => 0,
            'travel_start' => now()->addMonth()->toDateString(),
            'items' => [
                ['description' => 'Sample trip — adult', 'quantity' => 2, 'unit_price' => 1000],
            ],
            'discount' => 0,
            'valid_until' => now()->addDays(14)->toDateString(),
        ];
    }
}
