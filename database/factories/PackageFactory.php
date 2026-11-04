<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\Package>
 */
class PackageFactory extends Factory
{
    public function definition(): array
    {
        return [
            'slug' => fake()->unique()->slug(3),
            'category' => 'safari',
            'name' => fake()->city().' Safari',
            'days' => fake()->numberBetween(3, 9),
            'price' => fake()->numberBetween(800, 3500),
            'is_published' => true,
        ];
    }

    public function unpublished(): static
    {
        return $this->state(fn () => ['is_published' => false]);
    }

    public function withoutPrice(): static
    {
        return $this->state(fn () => ['price' => 0]);
    }
}
