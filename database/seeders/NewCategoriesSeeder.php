<?php

namespace Database\Seeders;

use App\Models\Package;
use Illuminate\Database\Seeder;

/**
 * Seeds the first packages for the Biking category (cycling safaris).
 * Reuses existing site imagery rather than introducing new, unlicensed
 * photos. Safe to re-run: matched on slug.
 */
class NewCategoriesSeeder extends Seeder
{
    public function run(): void
    {
        foreach ($this->rows() as $sort => $row) {
            Package::updateOrCreate(
                ['slug' => $row['slug']],
                $row['data'] + ['is_published' => true, 'sort_order' => 9 + $sort]
            );
        }
    }

    /** @return array<int, array{slug:string, data:array}> */
    protected function rows(): array
    {
        return [
            [
                'slug' => 'ngorongoro-highlands-cycling-safari',
                'data' => [
                    'category' => 'biking',
                    'circuit' => null,
                    'name' => 'Ngorongoro Highlands Cycling Safari',
                    'nickname' => 'the crater rim, by bike',
                    'location' => 'Ngorongoro Highlands · Karatu',
                    'days' => 5,
                    'price' => 1850,
                    'tag' => 'Active',
                    'rate' => 'Guided support-vehicle rides',
                    'rating' => 4.7,
                    'reviews' => 38,
                    'group' => 'Max 8',
                    'difficulty' => 'Moderate',
                    'best_time' => 'Jun–Oct, dry and cool at altitude',
                    'start_point' => 'Arusha',
                    'image' => 'images/hero-kilimanjaro.jpg',
                    'accommodation' => 'Farm lodges and tented camps',
                    'blurb' => 'Ride through coffee farms, Maasai villages and highland forest on the rim of the Ngorongoro Crater.',
                    'overview' => "Most safaris put you behind a windscreen for a week straight. This one swaps a chunk of that vehicle time for mountain bikes on quiet farm roads and forest tracks through the Ngorongoro Highlands — coffee estates around Karatu, Maasai villages, and a ride along part of the crater rim itself. A support vehicle carries water, spare bikes and your luggage, and picks up anyone who wants a break, so the pace is set by the group, not a schedule. It closes with a vehicle-based game drive on the crater floor, where bikes aren't allowed for safety reasons.",
                    'highlights' => [
                        'Multi-day riding through coffee farms and Maasai villages in the Ngorongoro Highlands',
                        'A ride along part of the Ngorongoro Crater rim, at altitude with views into the caldera',
                        'A support vehicle following the whole way — water, spares, and an easy way to bow out for a stretch',
                        'Finishes with a full vehicle-based game drive on the crater floor',
                        'Quality hardtail mountain bikes provided, or bring your own',
                    ],
                    'included' => [
                        'Mountain bike hire and a fully-equipped support vehicle',
                        'Park and conservation fees, including one crater floor game drive',
                        'Certified cycling guide and safari driver-guide',
                        'All meals and drinking water for the trip',
                        'Accommodation in farm lodges and tented camps',
                        'Airport and hotel transfers in Arusha',
                    ],
                    'excluded' => [
                        'International flights and visa fees',
                        'Travel and medical insurance',
                        'Tips for guides and support crew',
                        'Personal cycling gear (helmets provided, clip-in shoes are not)',
                        'Alcoholic drinks and personal purchases',
                    ],
                    'gallery' => ['images/hero-kilimanjaro.jpg', 'images/stay-kili.jpg', 'images/gal-elephants.jpg'],
                    'itinerary' => [
                        ['t' => 'Arrival & bike fitting', 'd' => 'Arrive in Arusha, bike fitting and a short shakeout ride in the afternoon.', 'stay' => 'Arusha hotel', 'meals' => 'D'],
                        ['t' => 'Arusha to Karatu', 'd' => 'Ride through coffee-growing country toward Karatu, support vehicle following with lunch and water.', 'stay' => 'Karatu farm lodge', 'meals' => 'B, L, D'],
                        ['t' => 'Highland villages ride', 'd' => 'A full day riding through Maasai highland villages and forest tracks below the crater rim.', 'stay' => 'Karatu farm lodge', 'meals' => 'B, L, D'],
                        ['t' => 'Ngorongoro Crater rim ride', 'd' => 'Ride part of the crater rim itself at altitude, with the support vehicle handling the steepest sections.', 'stay' => 'Crater rim tented camp', 'meals' => 'B, L, D'],
                        ['t' => 'Crater floor game drive & departure', 'd' => 'Bikes are left behind for safety — a vehicle-based game drive on the crater floor, then transfer back to Arusha.', 'stay' => '—', 'meals' => 'B, L'],
                    ],
                ],
            ],
            [
                'slug' => 'kilimanjaro-foothills-ride',
                'data' => [
                    'category' => 'biking',
                    'circuit' => null,
                    'name' => 'Kilimanjaro Foothills Ride',
                    'nickname' => 'coffee farms below the mountain',
                    'location' => 'Moshi · Kilimanjaro foothills',
                    'days' => 3,
                    'price' => 780,
                    'tag' => 'Easy',
                    'rate' => 'Gentle grades, no climbing gear needed',
                    'rating' => 4.6,
                    'reviews' => 51,
                    'group' => 'Max 10',
                    'difficulty' => 'Easy',
                    'best_time' => 'Year-round',
                    'start_point' => 'Moshi',
                    'image' => 'images/hero-kilimanjaro.jpg',
                    'accommodation' => 'Guesthouse in Moshi',
                    'blurb' => 'A short, easy ride through coffee and banana farms on the lower slopes of Kilimanjaro — no mountain gear required.',
                    'overview' => "Not everyone who wants to see Kilimanjaro up close wants to climb it. This short ride stays entirely below 1,800 meters, on farm roads through the coffee and banana shambas that cover the mountain's lower slopes, with village stops and views up toward the summit whenever the cloud clears. It's built as a gentle add-on for travelers combining a safari or beach trip with a taste of the Kilimanjaro region — no technical riding, no altitude, and no mountain gear needed.",
                    'highlights' => [
                        "Rides entirely on Kilimanjaro's lower farm slopes — no altitude, no technical terrain",
                        'Stops in Chagga villages among coffee and banana plantations',
                        'Views up toward the summit on clear days',
                        'A relaxed pace suited to riders of most fitness levels',
                        'Easily combined with a safari or Zanzibar trip before or after',
                    ],
                    'included' => [
                        'Bike hire and a local cycling guide',
                        'Village and farm access fees',
                        'Lunch each riding day and drinking water',
                        'Guesthouse accommodation in Moshi',
                        'Airport transfer in Kilimanjaro / Moshi',
                    ],
                    'excluded' => [
                        'International flights and visa fees',
                        'Travel and medical insurance',
                        'Tips for your guide',
                        'Dinner (widely available in Moshi town)',
                        'Personal cycling gear',
                    ],
                    'gallery' => ['images/hero-kilimanjaro.jpg', 'images/stay-kili.jpg'],
                    'itinerary' => [
                        ['t' => 'Arrival & village ride', 'd' => 'Arrive in Moshi, afternoon bike fitting and an easy ride through a nearby Chagga village.', 'stay' => 'Moshi guesthouse', 'meals' => 'L'],
                        ['t' => 'Coffee farm ride', 'd' => "A full day riding farm roads through coffee and banana plantations on Kilimanjaro's lower slopes, with a coffee-processing stop.", 'stay' => 'Moshi guesthouse', 'meals' => 'B, L'],
                        ['t' => 'Waterfall ride & departure', 'd' => 'A morning ride to a nearby waterfall, then back to Moshi for your onward journey.', 'stay' => '—', 'meals' => 'B, L'],
                    ],
                ],
            ],
        ];
    }
}
