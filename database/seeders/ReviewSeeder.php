<?php

namespace Database\Seeders;

use App\Models\Package;
use App\Models\Review;
use Illuminate\Database\Seeder;

class ReviewSeeder extends Seeder
{
    /**
     * The first three match the quotes already hard-coded on the homepage
     * ("Field notes"), so the /reviews page and the homepage strip agree
     * with each other instead of showing two different sets of opinions.
     * Safe to re-run: matched on title, which is unique per review here.
     */
    public function run(): void
    {
        $rows = [
            [
                'package' => 'northern-circuit',
                'name' => 'Julia M.',
                'rating' => 5,
                'service_rating' => 5,
                'value_rating' => 5,
                'title' => 'Best safari of our lives',
                'body' => "Our guide rerouted us around a migration crossing three days before we arrived — something no brochure could have known. Best safari of our lives.",
                'stayed_at' => '2026-07-14',
                'is_featured' => true,
            ],
            [
                'package' => 'lemosho',
                'name' => 'Daniel K.',
                'rating' => 5,
                'service_rating' => 5,
                'value_rating' => 4,
                'title' => 'Honest, well-paced Lemosho climb',
                'body' => "Summited via Lemosho with a crew that clearly knew what they were doing. Honest about pacing, never rushed us, and the food on the mountain was shockingly good.",
                'stayed_at' => '2026-06-02',
                'is_featured' => true,
            ],
            [
                'package' => 'zanzibar-beach',
                'name' => 'Amara R.',
                'rating' => 5,
                'service_rating' => 5,
                'value_rating' => 5,
                'title' => 'Stone Town to the beach, no stress',
                'body' => "Stone Town, spice farms, then three days doing nothing on a beach that looked unreal in every photo. Booking on WhatsApp was refreshingly simple.",
                'stayed_at' => '2026-05-20',
                'is_featured' => true,
            ],
            [
                'package' => 'southern-circuit',
                'name' => 'Hassan A.',
                'rating' => 4,
                'service_rating' => 4,
                'value_rating' => 5,
                'title' => 'Ruaha without the crowds',
                'body' => "Went south instead of the usual northern loop and didn't regret it — cats everywhere and barely another vehicle in sight all week. Camp was simple but comfortable. Only downside was a bumpy transfer road, but that's the south for you.",
                'stayed_at' => '2026-04-11',
                'is_featured' => false,
            ],
            [
                'package' => 'marangu',
                'name' => 'Neema J.',
                'rating' => 4,
                'service_rating' => 4,
                'value_rating' => 4,
                'title' => 'Good first climb, huts were a nice touch',
                'body' => "Sleeping in huts instead of a tent made this a gentler introduction to the mountain than I expected. Guide kept a steady pace and checked on everyone constantly. Didn't summit due to weather, but they were upfront about the odds beforehand.",
                'stayed_at' => '2026-03-02',
                'is_featured' => false,
            ],
        ];

        foreach ($rows as $row) {
            $package = Package::where('slug', $row['package'])->first();

            Review::firstOrCreate(
                ['title' => $row['title']],
                [
                    'package_id' => $package?->id,
                    'name' => $row['name'],
                    'rating' => $row['rating'],
                    'service_rating' => $row['service_rating'],
                    'value_rating' => $row['value_rating'],
                    'body' => $row['body'],
                    'stayed_at' => $row['stayed_at'],
                    'is_approved' => true,
                    'is_featured' => $row['is_featured'],
                ]
            );
        }
    }
}
