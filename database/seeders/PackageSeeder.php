<?php

namespace Database\Seeders;

use App\Models\Accommodation;
use App\Models\Package;
use App\Support\Trips;
use Illuminate\Database\Seeder;

class PackageSeeder extends Seeder
{
    /**
     * Moves the original hard-coded trip list into the database. Safe to re-run:
     * records are matched on slug / accommodation name.
     */
    public function run(): void
    {
        // Packages that belong to the Southern Circuit admin area rather than Safaris.
        $southern = ['southern-circuit'];

        foreach (Trips::all() as $i => $trip) {
            $stay = $trip['stay'];

            $accommodation = Accommodation::firstOrNew(['name' => $stay['name']]);
            $accommodation->fill([
                'image' => $stay['image'],
                'stars' => $stay['stars'],
                'place' => $stay['place'],
                'class' => $stay['class'],
                'amenities' => $stay['amenities'],
                'note' => $stay['note'],
            ])->save();

            $package = Package::firstOrNew(['slug' => $trip['id']]);
            $package->fill([
                'category' => $trip['category'],
                'circuit' => $trip['category'] === 'safari'
                    ? (in_array($trip['id'], $southern, true) ? 'southern' : 'northern')
                    : null,
                'name' => $trip['name'],
                'nickname' => $trip['nickname'],
                'location' => $trip['location'],
                'days' => $trip['days'],
                'price' => $trip['price'],
                'tag' => $trip['tag'],
                'rate' => $trip['rate'],
                'rating' => $trip['rating'],
                'reviews' => $trip['reviews'],
                'group' => $trip['group'],
                'difficulty' => $trip['difficulty'],
                'best_time' => $trip['best_time'],
                'start_point' => $trip['start_point'],
                'image' => $trip['image'],
                'accommodation' => $trip['accommodation'],
                'blurb' => $trip['blurb'],
                'overview' => $trip['overview'],
                'highlights' => $trip['highlights'],
                'included' => $trip['included'],
                'excluded' => $trip['excluded'],
                'gallery' => $trip['gallery'],
                'itinerary' => $trip['itinerary'],
                'is_published' => true,
                'sort_order' => $i,
            ])->save();

            $package->stays()->syncWithoutDetaching([$accommodation->id]);
        }
    }
}
