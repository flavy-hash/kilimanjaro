<?php

namespace Database\Seeders;

use App\Models\Accommodation;
use App\Models\Package;
use Illuminate\Database\Seeder;

class KilimanjaroExtraRoutesSeeder extends Seeder
{
    /**
     * Rongai, Umbwe and Northern Circuit — the three routes the homepage's
     * route explorer already shows on its map but couldn't link to, since
     * only Machame/Lemosho/Marangu existed as real packages. Slugs match the
     * explorer's own route ids exactly (rongai/umbwe/northern) so its
     * "View the full itinerary" link picks these up automatically.
     *
     * Camp names and elevations are kept consistent with the same waypoint
     * data already drawn on that map, not re-derived independently, so the
     * two never contradict each other. Safe to re-run: matched on slug.
     */
    public function run(): void
    {
        $camping = Accommodation::where('name', 'Full-service mountain camps')->first();
        $huts = Accommodation::where('name', 'Mandara, Horombo & Kibo huts')->first();

        foreach ($this->rows() as $sort => $row) {
            $package = Package::firstOrNew(['slug' => $row['slug']]);
            $package->fill([
                'category' => 'kilimanjaro',
                'circuit' => null,
                'is_published' => true,
                'sort_order' => 6 + $sort,
            ] + $row['data'])->save();

            $stayIds = array_filter([
                $row['huts_night'] ? $huts?->id : null,
                $camping?->id,
            ]);
            $package->stays()->syncWithoutDetaching($stayIds);
        }
    }

    /** @return array<int, array{slug:string, huts_night:bool, data:array}> */
    protected function rows(): array
    {
        return [
            [
                'slug' => 'rongai',
                'huts_night' => true,
                'data' => [
                    'name' => 'Rongai Route',
                    'nickname' => 'the quiet northern side',
                    'location' => 'Kilimanjaro National Park · Moshi',
                    'days' => 7,
                    'price' => 2600,
                    'tag' => 'Moderate',
                    'rate' => '88% summit rate',
                    'rating' => 4.8,
                    'reviews' => 310,
                    'group' => 'Max 10',
                    'difficulty' => 'Challenging',
                    'best_time' => 'Jan–Mar, Jun–Oct',
                    'start_point' => 'Moshi',
                    'image' => 'images/hero-kilimanjaro.jpg',
                    'accommodation' => 'Camping throughout, with a night in Horombo huts on the descent',
                    'blurb' => "Kilimanjaro's quiet northern approach — fewer climbers, drier trails, and a steady, well-paced route to the summit.",
                    'overview' => "Rongai is the only route that climbs the mountain's dry northern side, starting near the Kenyan border and crossing quiet pine forest most climbers never see. It's markedly less crowded than the southern routes — you'll often have a camp to yourselves — and its daily elevation gain is gentler than Machame or Umbwe, which suits first-time high-altitude trekkers. The descent switches onto the Marangu route, so the trip ends with a night in the Horombo huts rather than another night under canvas, and you come down through a completely different side of the mountain from the one you climbed.",
                    'highlights' => [
                        'The only route up the dry northern side, near the Kenyan border, through quiet pine forest',
                        'By far the least-crowded path on the mountain — often just your own group at camp',
                        'A gentler daily elevation gain than Machame or Umbwe, with Mawenzi Tarn as a dramatic high camp',
                        'Descends via Marangu, so you see two completely different faces of Kilimanjaro',
                        'Daily health checks with pulse oximetry and emergency oxygen carried',
                    ],
                    'included' => [
                        'Park, camping and rescue fees',
                        'Certified mountain guides and full crew',
                        'All meals and drinking water on the mountain',
                        'Four-season tents and sleeping mats',
                        'Emergency oxygen and pulse oximeter',
                        'Transfers to and from the gate',
                    ],
                    'excluded' => [
                        'International flights and visa fees',
                        'Hotels before and after the climb',
                        'Travel and high-altitude insurance',
                        'Tips for guides, porters and cook',
                        'Personal climbing gear and rentals',
                    ],
                    'gallery' => ['images/gal-kili-climbers.jpg', 'images/stay-kili.jpg', 'images/gal-kili-summit.jpg'],
                    'itinerary' => [
                        ['t' => 'Nalemuru Gate to Simba Camp', 'd' => 'Enter through the quiet northern gate near the Kenyan border and climb through pine forest to Simba Camp on the edge of the moorland.', 'elev' => '1,950 → 2,671 m', 'stay' => 'Simba Camp', 'meals' => 'L, D'],
                        ['t' => 'Simba Camp to Second Cave', 'd' => 'A short, gentle day across open moorland, with wide views back toward Kenya.', 'elev' => '2,671 → 3,450 m', 'stay' => 'Second Cave Camp', 'meals' => 'B, L, D'],
                        ['t' => 'Second Cave to Kikelewa Camp', 'd' => "Cross into the alpine desert zone toward Mawenzi's jagged outline, camping in a sheltered valley.", 'elev' => '3,450 → 3,630 m', 'stay' => 'Kikelewa Camp', 'meals' => 'B, L, D'],
                        ['t' => 'Kikelewa to Mawenzi Tarn', 'd' => 'A steep but short climb to camp beneath Mawenzi Peak — one of the most dramatic settings on the mountain, and a good acclimatisation stop.', 'elev' => '3,630 → 4,315 m', 'stay' => 'Mawenzi Tarn Camp', 'meals' => 'B, L, D'],
                        ['t' => 'Mawenzi Tarn to Kibo Hut', 'd' => "Cross the lunar \"saddle\" between Mawenzi and Kibo to the final camp before the summit push. Early dinner and bed.", 'elev' => '4,315 → 4,720 m', 'stay' => 'Kibo Hut', 'meals' => 'B, L, D'],
                        ['t' => 'Summit night — Uhuru Peak', 'd' => 'Midnight start for Uhuru Peak at sunrise, then descend all the way down to the Horombo huts.', 'elev' => '4,720 → 5,895 → 3,720 m', 'stay' => 'Horombo Hut', 'meals' => 'B, L, D'],
                        ['t' => 'Descent to Marangu Gate', 'd' => 'Final descent through the rainforest to Marangu Gate, then transfer to Moshi.', 'elev' => '3,720 → 1,860 m', 'stay' => '—', 'meals' => 'B'],
                    ],
                ],
            ],
            [
                'slug' => 'umbwe',
                'huts_night' => false,
                'data' => [
                    'name' => 'Umbwe Route',
                    'nickname' => 'the steep direct line',
                    'location' => 'Kilimanjaro National Park · Moshi',
                    'days' => 6,
                    'price' => 2300,
                    'tag' => 'Hard',
                    'rate' => '72% summit rate',
                    'rating' => 4.7,
                    'reviews' => 210,
                    'group' => 'Max 8',
                    'difficulty' => 'Very challenging',
                    'best_time' => 'Jan–Mar, Jun–Oct',
                    'start_point' => 'Moshi',
                    'image' => 'images/hero-kilimanjaro.jpg',
                    'accommodation' => 'Camping throughout (crew-carried tents)',
                    'blurb' => 'The steepest, most direct line up Kilimanjaro — for strong, experienced trekkers only.',
                    'overview' => "Umbwe climbs a narrow forested ridge almost straight into the Barranco Valley, with none of the gradual buildup the other routes give you. It's the shortest, steepest path on the mountain, and the one with the least time to acclimatise — we only run it for trekkers who are fit, experienced at altitude, and want the quietest possible lower slopes. From Barranco onward it joins the same route as Machame, including the Barranco Wall scramble, so the scenery on the upper mountain is every bit as dramatic. This is a route we're honest about: it has the lowest summit success rate we offer, precisely because it skips the acclimatisation time that makes the other routes safer.",
                    'highlights' => [
                        'The steepest and most direct line to the summit — no easing in',
                        'Joins the classic Barranco Valley and Wall from day two, same dramatic scenery as Machame',
                        'Fewer climbers on the lower slopes than any other route',
                        'Best suited to strong, experienced trekkers comfortable with rapid elevation gain',
                        'Daily health checks with pulse oximetry and emergency oxygen carried',
                    ],
                    'included' => [
                        'Park, camping and rescue fees',
                        'Certified mountain guides and full crew',
                        'All meals and drinking water on the mountain',
                        'Four-season tents and sleeping mats',
                        'Emergency oxygen and pulse oximeter',
                        'Transfers to and from the gate',
                    ],
                    'excluded' => [
                        'International flights and visa fees',
                        'Hotels before and after the climb',
                        'Travel and high-altitude insurance',
                        'Tips for guides, porters and cook',
                        'Personal climbing gear and rentals',
                    ],
                    'gallery' => ['images/gal-kili-climbers.jpg', 'images/stay-kili.jpg', 'images/gal-kili-summit.jpg'],
                    'itinerary' => [
                        ['t' => 'Umbwe Gate to Umbwe Camp', 'd' => 'A steep climb straight up a forested ridge — no gradual warm-up on this route.', 'elev' => '1,600 → 2,850 m', 'stay' => 'Umbwe Camp', 'meals' => 'L, D'],
                        ['t' => 'Umbwe Camp to Barranco Camp', 'd' => 'Climb out of the forest onto the ridge proper, joining the classic Barranco Valley by afternoon.', 'elev' => '2,850 → 3,900 m', 'stay' => 'Barranco Camp', 'meals' => 'B, L, D'],
                        ['t' => 'Barranco Wall to Karanga', 'd' => 'Scramble the Barranco Wall at sunrise — the best morning on the mountain — then cross to Karanga Camp.', 'elev' => '3,900 → 3,995 m', 'stay' => 'Karanga Camp', 'meals' => 'B, L, D'],
                        ['t' => 'Karanga to Barafu', 'd' => 'A short climb to Barafu, staging point for the summit push. Early dinner and bed.', 'elev' => '3,995 → 4,673 m', 'stay' => 'Barafu Camp', 'meals' => 'B, L, D'],
                        ['t' => 'Summit night — Uhuru Peak', 'd' => 'Midnight start for Uhuru Peak at sunrise, then a long descent to Millenium Camp.', 'elev' => '4,673 → 5,895 → 3,950 m', 'stay' => 'Millenium Camp', 'meals' => 'B, L, D'],
                        ['t' => 'Descent to Mweka Gate', 'd' => 'Final descent through the forest to the gate, then transfer to Moshi.', 'elev' => '3,950 → 1,640 m', 'stay' => '—', 'meals' => 'B'],
                    ],
                ],
            ],
            [
                'slug' => 'northern',
                'huts_night' => false,
                'data' => [
                    'name' => 'Northern Circuit',
                    'nickname' => 'the full traverse',
                    'location' => 'Kilimanjaro National Park · Moshi',
                    'days' => 9,
                    'price' => 3150,
                    'tag' => 'Moderate',
                    'rate' => '95% summit rate',
                    'rating' => 4.9,
                    'reviews' => 260,
                    'group' => 'Max 10',
                    'difficulty' => 'Challenging',
                    'best_time' => 'Jan–Mar, Jun–Oct',
                    'start_point' => 'Moshi',
                    'image' => 'images/hero-kilimanjaro.jpg',
                    'accommodation' => 'Camping throughout (crew-carried tents)',
                    'blurb' => 'The longest route on the mountain, arcing around the remote northern side for the best acclimatisation and summit odds of any path up.',
                    'overview' => "The Northern Circuit is the longest way up Kilimanjaro, and that's the entire point. It shares Lemosho's scenic opening days — the same rainforest start and wide crossing of the Shira Plateau — before breaking north instead of south, onto slopes almost no other route touches. Nine days on the mountain means more time at altitude to acclimatise than any other itinerary we run, which is why it carries the highest summit success rate we offer. It's a genuinely quiet route: once you leave the Shira Plateau you're on the remote side of the mountain, often without seeing another group until the final approach to the summit.",
                    'highlights' => [
                        'The longest route on Kilimanjaro — nine days for the best acclimatisation of any path',
                        'Arcs around the remote, rarely-visited northern slopes most climbers never see',
                        "Shares Lemosho's scenic start across the Shira Plateau before breaking off to the quiet side",
                        'The highest summit success rate of any standard route',
                        'Daily health checks with pulse oximetry and emergency oxygen carried',
                    ],
                    'included' => [
                        'Park, camping and rescue fees',
                        'Certified mountain guides and full crew',
                        'All meals and drinking water on the mountain',
                        'Four-season tents and sleeping mats',
                        'Emergency oxygen and pulse oximeter',
                        'Transfers to and from the gate',
                    ],
                    'excluded' => [
                        'International flights and visa fees',
                        'Hotels before and after the climb',
                        'Travel and high-altitude insurance',
                        'Tips for guides, porters and cook',
                        'Personal climbing gear and rentals',
                    ],
                    'gallery' => ['images/gal-kili-climbers.jpg', 'images/stay-kili.jpg', 'images/gal-kili-summit.jpg'],
                    'itinerary' => [
                        ['t' => 'Londorossi Gate to Mti Mkubwa', 'd' => 'Trek through quiet rainforest to Mti Mkubwa (Big Tree) Camp — the same gentle start as Lemosho.', 'elev' => '2,100 → 2,835 m', 'stay' => 'Mti Mkubwa Camp', 'meals' => 'L, D'],
                        ['t' => 'Mti Mkubwa to Shira One', 'd' => 'Climb out of the forest onto the wide Shira Plateau.', 'elev' => '2,835 → 3,610 m', 'stay' => 'Shira 1 Camp', 'meals' => 'B, L, D'],
                        ['t' => 'Shira to Moir Hut', 'd' => 'Break off from the Lemosho route here, heading north instead of south — the point where the crowds disappear.', 'elev' => '3,610 → 4,161 m', 'stay' => 'Moir Hut', 'meals' => 'B, L, D'],
                        ['t' => 'Moir Hut to Buffalo Camp', 'd' => 'A quiet day traversing the remote northern slopes, rarely visited by other routes.', 'elev' => '4,161 → 4,020 m', 'stay' => 'Buffalo Camp', 'meals' => 'B, L, D'],
                        ['t' => 'Buffalo Camp to Third Cave', 'd' => "Continue the traverse around the mountain's northern flank.", 'elev' => '4,020 → 3,800 m', 'stay' => '3rd Cave Camp', 'meals' => 'B, L, D'],
                        ['t' => 'Third Cave to School Hut', 'd' => 'Climb to the high camp on the eastern side, in position for the summit push.', 'elev' => '3,800 → 4,800 m', 'stay' => 'School Hut', 'meals' => 'B, L, D'],
                        ['t' => 'Acclimatisation day at School Hut', 'd' => 'A short walk above camp and back, sleeping again at School Hut — the extra day this route is built around.', 'elev' => '4,800 → 4,800 m', 'stay' => 'School Hut', 'meals' => 'B, L, D'],
                        ['t' => 'Summit night — Uhuru Peak', 'd' => 'Midnight start for Uhuru Peak at sunrise, then a long descent to Millenium Camp.', 'elev' => '4,800 → 5,895 → 3,950 m', 'stay' => 'Millenium Camp', 'meals' => 'B, L, D'],
                        ['t' => 'Descent to Mweka Gate', 'd' => 'Final descent through the forest to the gate, then transfer to Moshi.', 'elev' => '3,950 → 1,640 m', 'stay' => '—', 'meals' => 'B'],
                    ],
                ],
            ],
        ];
    }
}
