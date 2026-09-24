<?php

namespace Database\Seeders;

use App\Models\Package;
use Illuminate\Database\Seeder;

/**
 * Replaces the placeholder Machame/Lemosho/Marangu/Northern Circuit content
 * with the real itineraries, pricing and Priscus Peter Mtui commentary
 * supplied by the business owner (source: PDF/Word documents provided
 * directly, one per route+duration). Each route now exists as several real
 * duration/style variants rather than one invented itinerary.
 *
 * Canonical slugs (machame/lemosho/marangu/northern) are kept pointing at a
 * specific real variant so the homepage's interactive route map — which
 * auto-links "View the full itinerary" by matching these exact slugs —
 * keeps working without changes. Additional durations get their own slugs.
 *
 * Per-person `price` is set to the document's "5+ people" tier (the
 * lowest/"from" rate); the full group-size pricing table is preserved in
 * `overview` since the schema has no dedicated tiered-pricing field.
 *
 * Safe to re-run: matched on slug.
 */
class RealKilimanjaroPackagesSeeder extends Seeder
{
    public function run(): void
    {
        foreach ($this->rows() as $sort => $row) {
            Package::updateOrCreate(
                ['slug' => $row['slug']],
                $row['data'] + [
                    'category' => 'kilimanjaro',
                    'circuit' => null,
                    'is_published' => true,
                ]
            )->fill(['sort_order' => $row['sort_order'] ?? (3 + $sort)])->save();
        }
    }

    protected function included(): array
    {
        return [
            'All Kilimanjaro National Park fees, conservation fees, camping fees, and rescue fees',
            'Airport pick-up and drop-off',
            'Hotel accommodation in Moshi (bed and breakfast)',
            'All meals on the mountain',
            'Professional certified lead guide and assistant guide(s)',
            'Experienced mountain cook and porters',
            'All camping equipment',
            'Transport between Moshi and the park gates',
            'Emergency oxygen cylinder and pulse oximeter health checks',
            'Group first aid kit',
            'All company taxes, permits, and government fees',
            'Official Kilimanjaro summit certificate',
        ];
    }

    protected function excluded(): array
    {
        return [
            'International flights and Tanzania visa fees',
            'Travel insurance (mandatory, must cover emergency evacuation)',
            'Personal climbing gear and clothing',
            'Sleeping bag (rentable in Moshi)',
            'Lunch and dinner at the hotel in Moshi',
            'Tips for guides, cook, and porters',
            'Personal snacks, drinks, and medication (incl. Diamox)',
        ];
    }

    protected function marangunIncluded(bool $withPulseOx): array
    {
        $base = [
            'All Kilimanjaro National Park fees',
            'Airport transfers',
            'Hotel in Moshi (bed and breakfast)',
            'Professional guides, cook, and porters',
            'All meals during the climb',
            'Transport to and from Marangu Gate',
            'Emergency oxygen cylinder',
        ];
        if ($withPulseOx) {
            $base[] = 'Pulse oximeter checks at every camp';
        }
        $base[] = 'Company taxes and fees';

        return $base;
    }

    protected function marangunExcluded(): array
    {
        return [
            'International flights and Tanzania visa fees',
            'Personal climbing gear and clothing',
            'Lunch and dinner at the hotel',
            'Extra hotel nights',
            'Optional excursions',
            'Tips for guides, cook, and porters',
        ];
    }

    /**
     * Structured version of the same group-size tiers passed to pricingNote(),
     * for the booking sidebar's price table (per person, all-inclusive on the mountain).
     */
    protected function priceTierRows(array $tiers): array
    {
        return [
            ['label' => '1 person', 'price' => $tiers[1]],
            ['label' => '2 people', 'price' => $tiers[2]],
            ['label' => '3–4 people', 'price' => $tiers[3]],
            ['label' => '5+ people', 'price' => $tiers[5]],
        ];
    }

    protected function pricingNote(array $tiers): string
    {
        return sprintf(
            'Pricing depends on group size: $%s per person solo, $%s for two, $%s for three to four, and $%s per person for groups of five or more — all figures include park fees and are all-inclusive on the mountain.',
            number_format($tiers[1]),
            number_format($tiers[2]),
            number_format($tiers[3]),
            number_format($tiers[5]),
        );
    }

    protected function tippingNote(): string
    {
        return "Tipping isn't mandatory but is customary and appreciated: roughly $6–10 per porter per day, $15–20 per day for the cook and assistant guides, and $20–25 per day for the lead guide.";
    }

    /** @return array<int, array{slug:string, sort_order?:int, data:array}> */
    protected function rows(): array
    {
        $gallery = ['images/gal-kili-climbers.jpg', 'images/stay-kili.jpg', 'images/gal-kili-summit.jpg'];

        return [
            // ============================================================
            // MACHAME — canonical (7 days) + 6-day variant
            // ============================================================
            [
                'slug' => 'machame',
                'sort_order' => 3,
                'data' => [
                    'name' => 'Machame Route',
                    'nickname' => 'the Whiskey Route · 7 Days',
                    'location' => 'Kilimanjaro National Park · Moshi',
                    'days' => 7,
                    'price' => 1699,
                    'price_tiers' => $this->priceTierRows(['1' => 2150, '2' => 1799, '3' => 1749, '5' => 1699]),
                    'tag' => 'Challenging',
                    'rate' => '85–90% summit rate',
                    'rating' => 4.8,
                    'reviews' => 1930,
                    'group' => 'Max 10',
                    'difficulty' => 'Challenging',
                    'best_time' => 'Jan–Mar, Jun–Oct',
                    'start_point' => 'Moshi',
                    'image' => 'images/hero-kilimanjaro.jpg',
                    'accommodation' => 'Camping throughout (crew-carried tents) — no huts',
                    'blurb' => 'The most popular route on Kilimanjaro — challenging, dramatic, and extraordinarily beautiful, with the extra night at Karanga that gives most climbers their best shot at the summit.',
                    'overview' => "The Machame Route — nicknamed the Whiskey Route — is the most popular path to Uhuru Peak on Kilimanjaro, chosen by more than 35% of all Kilimanjaro climbers each year. It is more challenging than Marangu but rewards that extra effort with dramatically better scenery, a superior acclimatization profile, and a summit experience most climbers describe as the best of their lives. This is a full camping route — no huts — passing through five distinct ecological zones: equatorial rainforest, open moorland, alpine desert, arctic summit zone, and the glacial summit itself.\n\nThe 7-day version follows the exact same route as the 6-day through every ecological zone and every landmark. The critical difference is Day 4: after tackling the Barranco Wall and arriving at Karanga Camp for lunch, the day ends there rather than continuing on to Barafu. That single additional overnight at roughly 4,000 metres adds a full night of acclimatization, and lifts the summit success rate to approximately 85–90%, compared to roughly 80% on the 6-day.\n\n\"The 7-day Machame is the version I recommend for the majority of clients who choose this route,\" says Priscus Peter Mtui, our lead guide and owner. \"The extra night at Karanga Camp gives your body a genuinely important additional acclimatization night before the summit push. The 6-day version is achievable for fit, well-prepared climbers, but if this is your first time at altitude, or you simply want the best possible chance of standing on Uhuru Peak, seven days is the honest recommendation.\"\n\n".$this->pricingNote(['1' => 2150, '2' => 1799, '3' => 1749, '5' => 1699]).' '.$this->tippingNote(),
                    'highlights' => [
                        'Total distance ~62km across all five of Kilimanjaro\'s ecological zones',
                        'An extra acclimatization night at Karanga Camp (~4,000m) lifts summit odds to ~85–90%',
                        'The Barranco Wall — one of the most genuinely enjoyable scrambles on the mountain',
                        'A rest stop at Lava Tower (4,600m), higher than any peak in the Alps',
                        'Optional pre-climb visit to the Ngatambaku Mtui family\'s home village near Marangu',
                    ],
                    'included' => $this->included(),
                    'excluded' => $this->excluded(),
                    'gallery' => $gallery,
                    'itinerary' => [
                        ['t' => 'Day 0 — Arrival in Moshi', 'd' => "Your Perfect Kilimanjaro experience begins the moment you land at Kilimanjaro International Airport, where our driver transfers you directly to your hotel in Moshi. In the evening you meet Priscus or your assigned lead guide for a full pre-climb briefing — itinerary, camp elevations, acclimatization strategy, altitude sickness prevention, emergency procedures and equipment — plus your first health check to establish your baseline blood oxygen and heart rate. Optional: Materuni Waterfall & Coffee Tour, Kikuletwa Hot Springs, Moshi Town Tour, or a walk through the Ngatambaku Mtui family's home village.", 'stay' => 'Moshi hotel', 'meals' => 'D'],
                        ['t' => 'Day 1 — Machame Gate to Machame Camp', 'd' => 'A one-hour drive from Moshi through Machame village to the park gate, where permits are registered and you meet your full crew before stepping into the rainforest. Five to six hours of trail through dense canopy, with a packed lunch en route. Tent, hot drinks and a three-course dinner are waiting at Machame Camp.', 'elev' => '1,800m → 2,900m', 'stay' => 'Machame Camp', 'meals' => 'B, L, D'],
                        ['t' => 'Day 2 — Machame Camp to Shira Cave Camp', 'd' => 'The trail climbs out of the rainforest into open moorland — giant heathers and senecio plants line the path as you rise above the clouds for the first time. Hot lunch and a rest at Shira Cave Camp, followed by a short 4pm acclimatization walk.', 'elev' => '2,900m → 3,820m', 'stay' => 'Shira Cave Camp', 'meals' => 'B, L, D'],
                        ['t' => 'Day 3 — Shira Cave Camp to Lava Tower to Barranco Camp', 'd' => 'The most important acclimatization day on the route. We climb to Lava Tower at 4,600m — higher than Mont Blanc — for a hot lunch and an hour\'s rest, then descend 650m to Barranco Camp through the extraordinary Senecio Forest, sleeping well below the day\'s highest point.', 'elev' => '3,820m → 4,600m → 3,950m', 'stay' => 'Barranco Camp', 'meals' => 'B, L, D'],
                        ['t' => 'Day 4 — Barranco Camp to Karanga Camp', 'd' => 'The day begins with the Barranco Wall — intimidating from below, but one of the most genuinely enjoyable sections of the whole climb. After the wall we cross rolling alpine desert to Karanga Camp for a relaxed hot lunch. On the 7-day itinerary the day ends here — the afternoon is free to rest, wash and recover. This overnight at Karanga is the key difference from the 6-day version.', 'elev' => '3,950m → 3,995m', 'stay' => 'Karanga Camp', 'meals' => 'B, L, D'],
                        ['t' => 'Day 5 — Karanga Camp to Barafu Camp', 'd' => 'A short but crucial day — a steady 3-hour ascent to Barafu Camp, followed by a full hot lunch and a thorough summit briefing from Priscus. Early dinner at 5:30pm; by 8:00pm you should be in your sleeping bag ahead of the midnight alarm.', 'elev' => '3,995m → 4,673m', 'stay' => 'Barafu Camp', 'meals' => 'B, L, D'],
                        ['t' => 'Day 6 — Summit night: Barafu Camp to Uhuru Peak to Mweka Camp', 'd' => "Wake at midnight, moving by 12:30am. The ascent to Stella Point on the crater rim (5,685m) is steep, cold and relentless, usually arriving around sunrise. From there it's roughly 45 minutes along the rim to Uhuru Peak — 5,895m, the Roof of Africa. After celebrating, we descend all the way to Mweka Camp for the tipping ceremony that evening.", 'elev' => '4,673m → 5,895m → 3,100m', 'stay' => 'Mweka Camp', 'meals' => 'B, L, D'],
                        ['t' => 'Day 7 — Mweka Camp to Mweka Gate — back to Moshi', 'd' => 'The final descent through the same rainforest zone you entered on Day 1, now seen through very different eyes. Your official summit certificate is waiting at Mweka Gate, followed by the drive back to Moshi for a celebration at the hotel.', 'elev' => '3,100m → 1,600m', 'stay' => '—', 'meals' => 'B, L'],
                    ],
                ],
            ],
            [
                'slug' => 'machame-6-days',
                'sort_order' => 4,
                'data' => [
                    'name' => 'Machame Route',
                    'nickname' => 'the Whiskey Route · 6 Days',
                    'location' => 'Kilimanjaro National Park · Moshi',
                    'days' => 6,
                    'price' => 1549,
                    'price_tiers' => $this->priceTierRows(['1' => 1999, '2' => 1699, '3' => 1649, '5' => 1549]),
                    'tag' => 'Challenging',
                    'rate' => '~80% summit rate',
                    'rating' => 4.7,
                    'reviews' => 890,
                    'group' => 'Max 10',
                    'difficulty' => 'Very challenging',
                    'best_time' => 'Jan–Mar, Jun–Oct',
                    'start_point' => 'Moshi',
                    'image' => 'images/hero-kilimanjaro.jpg',
                    'accommodation' => 'Camping throughout (crew-carried tents) — no huts',
                    'blurb' => 'The fastest way up the Whiskey Route — same scenery and ecological zones as the 7-day, compressed into one longer, harder push from Karanga to Barafu.',
                    'overview' => "The Machame Route — nicknamed the Whiskey Route — is the most popular path to Uhuru Peak on Kilimanjaro, chosen by more than 35% of all Kilimanjaro climbers each year. This 6-day version follows the classic Machame structure: full camping, five ecological zones, and the same landmarks as the 7-day — but Day 4 combines the Barranco Wall, a lunch stop at Karanga, and the push to Barafu Camp all in one longer day, cutting a full acclimatization night from the longer itinerary.\n\n\"The Machame Route is the most popular route on Kilimanjaro for very good reason — challenging, dramatic and extraordinarily beautiful,\" says Priscus Peter Mtui, our lead guide and owner. \"The 6-day version is for climbers who are fit, well-prepared, and ready to commit fully. If you have any doubt about your preparation, choose 7 days instead — the extra day at Karanga makes a real difference.\"\n\n".$this->pricingNote(['1' => 1999, '2' => 1699, '3' => 1649, '5' => 1549]).' '.$this->tippingNote(),
                    'highlights' => [
                        'Recommended for: fit, experienced trekkers with some previous altitude exposure',
                        'Same five ecological zones and landmarks as the 7-day version, in one less day',
                        'Day 4 combines the Barranco Wall, Karanga and the push to Barafu in a single long day',
                        'Optional private portable toilet upgrade ($100/group) — the route otherwise uses public pit toilets',
                        'A private toilet tent, safari and Zanzibar extensions can all be added on',
                    ],
                    'included' => $this->included(),
                    'excluded' => $this->excluded(),
                    'gallery' => $gallery,
                    'itinerary' => [
                        ['t' => 'Day 0 — Arrival in Moshi', 'd' => 'Airport pickup and transfer to your Moshi hotel, followed by an evening pre-climb briefing and first health check with Priscus or your assigned lead guide. Optional: Materuni Waterfall & Coffee Tour, Kikuletwa Hot Springs, Moshi Town Tour, or a visit to the Ngatambaku Mtui family\'s home village.', 'stay' => 'Moshi hotel', 'meals' => 'D'],
                        ['t' => 'Day 1 — Machame Gate to Machame Camp', 'd' => 'A one-hour drive to the park gate for registration, then five to six hours through dense rainforest canopy to Machame Camp, where your tent, hot drinks and a three-course dinner are waiting.', 'elev' => '1,800m → 2,900m', 'stay' => 'Machame Camp', 'meals' => 'B, L, D'],
                        ['t' => 'Day 2 — Machame Camp to Shira Cave Camp', 'd' => 'The trail climbs out of the rainforest into open moorland, rising above the clouds for the first time. Hot lunch and rest at Shira Cave Camp, plus a short 4pm acclimatization walk.', 'elev' => '2,900m → 3,820m', 'stay' => 'Shira Cave Camp', 'meals' => 'B, L, D'],
                        ['t' => 'Day 3 — Shira Cave Camp to Lava Tower to Barranco Camp', 'd' => 'The day that makes or breaks a summit attempt. A steady climb to Lava Tower (4,600m) for lunch, then a 650m descent to Barranco Camp through the Senecio Forest — the climb-high-sleep-low principle at its most powerful.', 'elev' => '3,820m → 4,600m → 3,950m', 'stay' => 'Barranco Camp', 'meals' => 'B, L, D'],
                        ['t' => 'Day 4 — Barranco Camp to Barafu Camp via Karanga', 'd' => "The Barranco Wall first — genuinely one of the most enjoyable sections of the climb — then on to Karanga for a full hot lunch and rest. Unlike the 7-day route, we continue: a further 2–3 hours through alpine desert brings you to Barafu Camp by late afternoon. Dinner at 6pm, sleeping bag by 8pm — this combined day is where the 6-day route separates the well-prepared from those who aren't.", 'elev' => '3,950m → 4,000m → 4,673m', 'stay' => 'Barafu Camp', 'meals' => 'B, L, D'],
                        ['t' => 'Day 5 — Summit night: Barafu Camp to Uhuru Peak to Mweka Camp', 'd' => "Wake at midnight, moving by 12:30am for the steep, cold ascent to Stella Point (5,685m), usually around sunrise, then roughly 45 minutes on to Uhuru Peak at 5,895m — the Roof of Africa. After celebrating, we descend via Barafu all the way to Mweka Camp, where the tipping ceremony takes place that evening.", 'elev' => '4,673m → 5,895m → 3,100m', 'stay' => 'Mweka Camp', 'meals' => 'B, L, D'],
                        ['t' => 'Day 6 — Mweka Camp to Mweka Gate — back to Moshi', 'd' => 'A final easy descent through the rainforest to Mweka Gate, where your official summit certificate is waiting, before the drive back to Moshi for a celebration at the hotel.', 'elev' => '3,100m → 1,600m', 'stay' => '—', 'meals' => 'B, L'],
                    ],
                ],
            ],

            // ============================================================
            // LEMOSHO — canonical (8 days) + 6-day and 9-day Day Summit variants
            // ============================================================
            [
                'slug' => 'lemosho',
                'sort_order' => 5,
                'data' => [
                    'name' => 'Lemosho Route',
                    'nickname' => 'the scenic western approach · 8 Days',
                    'location' => 'Kilimanjaro National Park · Moshi',
                    'days' => 8,
                    'price' => 1739,
                    'price_tiers' => $this->priceTierRows(['1' => 2199, '2' => 1966, '3' => 1789, '5' => 1739]),
                    'tag' => 'Challenging',
                    'rate' => '~80% summit rate',
                    'rating' => 4.8,
                    'reviews' => 740,
                    'group' => 'Max 10',
                    'difficulty' => 'Challenging',
                    'best_time' => 'Jan–Mar, Jun–Oct',
                    'start_point' => 'Moshi',
                    'image' => 'images/hero-kilimanjaro.jpg',
                    'accommodation' => 'Camping throughout (crew-carried tents) — no huts',
                    'blurb' => "Kilimanjaro's most scenic approach — remote western wilderness, the wide Shira Plateau, and a gentler pace than Machame with a genuinely strong summit rate.",
                    'overview' => "The Lemosho Route approaches Kilimanjaro from the remote west, through pristine rainforest that very few climbers see, before crossing the ancient Shira Plateau — a vast, open volcanic caldera with the summit dome of Kibo rising ahead of you the entire way. From Shira it joins the same southern-circuit landmarks as Machame — Lava Tower, the Barranco Wall, Karanga — but the extra distance and gentler early pacing give Lemosho one of the best combinations of scenery and acclimatization on the mountain.\n\nLemosho is available as a 6, 8 or 9-day itinerary, all following the same route and camps — the difference is entirely pacing. The 8-day gives a more measured build than the 6-day, splitting the Shira Plateau crossing across two gentler days instead of one long push, without adding the extra Lava Tower acclimatization stop that makes the 9-day Day Summit version our highest-success Lemosho option.\n\n\"Lemosho rewards patience,\" says Priscus Peter Mtui, our lead guide and owner. \"You are on the mountain a little longer than Machame, on quieter trails for the first few days, and your body has more time to adjust before the demanding sections. Eight days is a solid, well-balanced way to do this route — if you want the strongest possible odds, or you're planning something special at the summit, ask us about the 9-day Day Summit version instead.\"\n\n".$this->pricingNote(['1' => 2199, '2' => 1966, '3' => 1789, '5' => 1739]).' '.$this->tippingNote(),
                    'highlights' => [
                        'Total distance ~66km, starting from remote Londorossi Gate on the western side',
                        'Crosses the wide, dramatic Shira Plateau with Kibo visible ahead for most of the day',
                        'Joins the classic Barranco Wall, Karanga and Barafu route shared with Machame',
                        'Quieter lower-mountain trails than Machame or Marangu',
                        'Also available as a 6-day (faster, lower success rate) or a 9-day Day Summit (our top-recommended Lemosho option)',
                    ],
                    'included' => $this->included(),
                    'excluded' => $this->excluded(),
                    'gallery' => $gallery,
                    'itinerary' => [
                        ['t' => 'Day 0 — Arrival in Moshi', 'd' => 'Airport pickup and transfer to your Moshi hotel, followed by an evening pre-climb briefing and first health check with Priscus or your assigned lead guide. Optional: Materuni Waterfall & Coffee Tour, Kikuletwa Hot Springs, Moshi Town Tour, or a visit to the Ngatambaku Mtui family\'s home village.', 'stay' => 'Moshi hotel', 'meals' => 'D'],
                        ['t' => 'Day 1 — Londorossi Gate to Big Tree Camp', 'd' => 'A scenic drive to Londorossi Gate for registration, then a gentle first walk through montane rainforest — blue monkeys and colobus overhead — to Big Tree Camp (Mti Mkubwa).', 'elev' => '2,350m → 2,800m', 'stay' => 'Big Tree Camp', 'meals' => 'B, L, D'],
                        ['t' => 'Day 2 — Big Tree Camp to Shira 1 Camp', 'd' => 'Climbing out of the forest into open moorland, with the first clear views of Kibo from Shira Ridge — one of the most dramatic moments of the whole route.', 'elev' => '2,800m → 3,500m', 'stay' => 'Shira 1 Camp', 'meals' => 'B, L, D'],
                        ['t' => 'Day 3 — Shira 1 Camp to Shira 2 Camp', 'd' => 'A gentle, scenic crossing of the Shira Plateau — wide views of Kibo and the surrounding caldera, with time for rest and an optional short acclimatization walk in the afternoon.', 'elev' => '3,500m → 3,850m', 'stay' => 'Shira 2 Camp', 'meals' => 'B, L, D'],
                        ['t' => 'Day 4 — Shira 2 Camp to Lava Tower to Barranco Camp', 'd' => 'The key acclimatization day — a climb to Lava Tower at 4,600m for lunch and rest, then a 650m descent through the Senecio Forest to Barranco Camp, putting the climb-high-sleep-low principle to work.', 'elev' => '3,850m → 4,600m → 3,950m', 'stay' => 'Barranco Camp', 'meals' => 'B, L, D'],
                        ['t' => 'Day 5 — Barranco Camp to Karanga Camp', 'd' => 'The Barranco Wall first thing — one of the most enjoyable scrambles on the mountain — then across rolling alpine desert to Karanga Camp, arriving in time for a relaxed afternoon.', 'elev' => '3,950m → 3,995m', 'stay' => 'Karanga Camp', 'meals' => 'B, L, D'],
                        ['t' => 'Day 6 — Karanga Camp to Barafu Camp', 'd' => 'A short, steady climb to Barafu Camp, followed by a full summit briefing from Priscus. Early dinner, then bed well ahead of the midnight alarm.', 'elev' => '3,995m → 4,673m', 'stay' => 'Barafu Camp', 'meals' => 'B, L, D'],
                        ['t' => 'Day 7 — Summit night: Barafu Camp to Uhuru Peak to Mweka Camp', 'd' => 'Midnight start for Stella Point on the crater rim, usually around sunrise, then roughly 45 minutes on to Uhuru Peak — 5,895m, the Roof of Africa. Descend all the way to Mweka Camp for the crew\'s tipping ceremony that evening.', 'elev' => '4,673m → 5,895m → 3,100m', 'stay' => 'Mweka Camp', 'meals' => 'B, L, D'],
                        ['t' => 'Day 8 — Mweka Camp to Mweka Gate — back to Moshi', 'd' => 'A final descent through the rainforest to Mweka Gate for your official summit certificate, then the drive back to Moshi to celebrate.', 'elev' => '3,100m → 1,600m', 'stay' => '—', 'meals' => 'B, L'],
                    ],
                ],
            ],
            [
                'slug' => 'lemosho-6-days',
                'sort_order' => 6,
                'data' => [
                    'name' => 'Lemosho Route',
                    'nickname' => 'the scenic western approach · 6 Days',
                    'location' => 'Kilimanjaro National Park · Moshi',
                    'days' => 6,
                    'price' => 1589,
                    'price_tiers' => [
                        ['label' => '1 Person', 'park_fees' => 830, 'other_costs' => 1219, 'price' => 2049],
                        ['label' => '2 People', 'park_fees' => 827, 'other_costs' => 912, 'price' => 1739],
                        ['label' => '3–4 People', 'park_fees' => 824, 'other_costs' => 865, 'price' => 1689],
                        ['label' => '5+ People', 'park_fees' => 823, 'other_costs' => 766, 'price' => 1589],
                    ],
                    'tag' => 'Challenging',
                    'rate' => '~60% summit rate',
                    'rating' => 4.5,
                    'reviews' => 340,
                    'group' => 'Max 10',
                    'difficulty' => 'Challenging',
                    'best_time' => 'Jan–Mar, Jun–Oct',
                    'start_point' => 'Moshi',
                    'image' => 'images/hero-kilimanjaro.jpg',
                    'accommodation' => 'Camping throughout (crew-carried tents) — no huts',
                    'blurb' => "Lemosho's shortest version — same scenery, same camps, but for experienced climbers who accept a meaningfully lower summit rate in exchange for a shorter trip.",
                    'quote' => "The 6-day Lemosho is our shortest version of this route — and I want to be upfront with you about what that means. You will experience everything that makes Lemosho special — the remote western approach, the Shira Plateau, the Barranco Wall, the Southern Circuit. But your body has less time to acclimatize than on the 7 or 8-day versions.\n\nThis route is for experienced, well-prepared climbers who understand that trade-off and accept it. If you have any doubt, choose 7 or 8 days. The mountain will still be here.",
                    'recommended_for' => ['Experienced trekkers', 'Those with strong high-altitude experience', 'Fit climbers with limited time'],
                    'overview' => "The Lemosho Route approaches Kilimanjaro from the remote western wilderness — far from the crowded southern gates, through pristine forest that very few tourists ever see. It crosses the ancient Shira Plateau, passes through every ecological zone on the mountain, and follows the spectacular Southern Circuit before the final ascent to Uhuru Peak.\n\nWhat makes Lemosho exceptional is the sheer variety of landscape it crosses in a short space of time. You begin in dense montane rainforest on the western slopes, home to Colobus monkeys and rare forest birds. Within two days you break out onto the vast, otherworldly Shira Plateau — the collapsed caldera of an ancient volcano — with sweeping views of Kibo's glaciers ahead of you. From there the route climbs to Lava Tower at 4,600 metres before dropping down to the sheltered Barranco Camp, the \"climb high, sleep low\" pattern that underpins acclimatization on every version of this route. The Southern Circuit that follows is a high traverse rewarding climbers with 360-degree views of the mountain, before the final push through Barafu Camp to Uhuru Peak.\n\nBecause it starts at the remote Londorossi Gate on the western side, Lemosho sees significantly fewer climbers than the popular Machame and Marangu routes — a quieter, more personal experience on the mountain.\n\nTotal distance ~66 km · Duration 6 days · Difficulty Challenging · Accommodation Camping · Summit success rate ~60% · Start Londorossi Gate · End Mweka Gate.",
                    'route_comparison_intro' => 'The Lemosho Route is available as a 6, 7, or 8-day itinerary at Perfect Kilimanjaro. The scenery, the camps, and the overall experience are the same across all three versions. The difference is entirely in pacing and acclimatization time.',
                    'route_comparison_tiers' => [
                        ['medal' => 'bronze', 'title' => 'The 8-day version', 'rate' => '~90% summit rate', 'description' => 'Breaks the first section of the route into three gentle days — Big Tree Camp, then Shira 1, then Moir Hut — giving your body the most thorough acclimatization profile available.'],
                        ['medal' => 'silver', 'title' => 'The 7-day version', 'rate' => '~80% summit rate', 'description' => 'Combines the first two days into one long effort — covering Big Tree Camp to Shira 2 Camp in a single demanding 18-kilometre day.'],
                        ['medal' => 'gold', 'title' => 'The 6-day version', 'rate' => '~60% summit rate', 'description' => 'Goes further. Day 2 makes the same long crossing to Shira 2 Camp as the 7-day. But on Day 4, instead of stopping overnight at Karanga Camp, we continue directly from Karanga to Barafu Camp — combining what the 7-day version covers across two separate days into one longer push.'],
                    ],
                    'choose_this_if' => [
                        'Experienced trekkers who regularly hike at altitude',
                        'Climbers who have previously summited high peaks above 4,000 metres',
                        'Those with very limited time and strong relevant experience',
                        'Clients who have trained intensively and specifically for this climb',
                    ],
                    'choose_other_if' => [
                        'First-time Kilimanjaro climbers',
                        'Anyone coming from sea level',
                        'Those over 60',
                        'Families',
                        'Anyone for whom the summit is the primary goal',
                        'Anyone with limited trekking experience',
                    ],
                    'choose_other_label' => 'the 7 or 8-day',
                    'highlights' => [
                        'Recommended only for experienced, well-prepared climbers who accept a lower summit rate',
                        'Same scenery and camps as the longer Lemosho itineraries, in one less day',
                        'Day 2 combines the crossing to Shira 2 into one demanding 18km day',
                        'Day 4 skips the Karanga overnight, continuing straight through to Barafu',
                        'Consider the 8-day or 9-day Day Summit version for meaningfully better odds',
                    ],
                    'included' => [
                        'All Kilimanjaro National Park fees, conservation fees, camping fees, and rescue fees',
                        'Airport pick-up and drop-off at Kilimanjaro International Airport',
                        'Hotel accommodation in Moshi — bed and breakfast — one night before and one night after the climb',
                        'All meals on the mountain',
                        'Professional certified lead guide and assistant guide(s)',
                        'Experienced mountain cook',
                        'Porters — equipment carried professionally throughout',
                        'All camping equipment',
                        'Transport from Moshi hotel to Londorossi Gate and from Mweka Gate back to hotel',
                        'Emergency oxygen cylinder — carried on every Perfect Kilimanjaro climb, no exceptions',
                        'Pulse oximeter health checks — twice daily from Day Zero',
                        'Group first aid kit and emergency procedures',
                        'All company taxes, permits, and government fees',
                        'Official Kilimanjaro summit certificate on completion',
                    ],
                    'excluded' => [
                        'International flights to and from Tanzania',
                        'Tanzania visa fees — currently $50 USD for most nationalities',
                        'Travel insurance — mandatory — must include emergency evacuation and mountain rescue coverage',
                        'Personal climbing gear and clothing',
                        'Sleeping bag — can be rented in Moshi',
                        'Lunch and dinner at the hotel in Moshi',
                        'Tips for guides, cook, and porters',
                        'Personal snacks and drinks beyond what is provided',
                        'Medical vaccinations and personal medication, including Diamox',
                    ],
                    'tipping_table' => [
                        ['role' => 'Porters', 'tip' => '$6 – $10'],
                        ['role' => 'Cook & Assistant Guides', 'tip' => '$15 – $20'],
                        ['role' => 'Lead Guide', 'tip' => '$20 – $25'],
                    ],
                    'optional_extras' => [
                        'Private Portable Toilet — $100 per group',
                        'Extra Hotel Night in Moshi (pre- or post-climb)',
                        'Pre- and Post-Climb Activities: Materuni Waterfall & Coffee Tour · Kikuletwa Hot Springs · Moshi Town Tour · Marangu Village Walk',
                        'Safari & Zanzibar Extensions: Serengeti National Park · Ngorongoro Crater · Zanzibar',
                    ],
                    'closing_note' => "I will be honest with you — as I always am.\n\nThe 6-day Lemosho Route has a summit success rate of approximately 60%. That means roughly four out of every ten clients who choose this itinerary do not reach Uhuru Peak. That is not a number I am comfortable presenting without being clear about what it means.\n\nIt does not mean the route is bad. It means the route is demanding and the acclimatization window is tight. Two significant rest points available on longer versions — the overnight at Moir Hut on the 8-day and the overnight at Karanga on the 7-day — are not part of this itinerary.\n\nIf you choose the 6-day Lemosho, choose it because your experience, your fitness, and your altitude history genuinely support it — not because the price is lower or the time commitment is shorter.\n\nIf you contact me and describe your fitness level, your trekking history, and your altitude experience, I will give you my honest personal recommendation. I would rather tell you to choose a longer route than watch you turn back below the summit of a mountain you worked hard to reach.\n\nThe Ngatambaku Mtui family has guided on Kilimanjaro for three generations. Every client matters to us personally. That includes you.",
                    'gallery' => $gallery,
                    'itinerary' => [
                        [
                            't' => 'Day 0 — Arrival in Moshi',
                            'd' => "Your Perfect Kilimanjaro experience begins the moment you land at Kilimanjaro International Airport. Our driver will be waiting for you at the arrivals hall and will transfer you directly to your hotel in Moshi.\n\nIn the evening you will meet Priscus or your assigned lead guide for a comprehensive pre-climb briefing. This covers the full 6-day itinerary, daily camp elevations, acclimatization strategy, altitude sickness recognition and prevention, emergency procedures, equipment requirements, and what to expect on summit night.\n\nWe conduct your first health check this evening — measuring your blood oxygen saturation and resting heart rate to establish your personal baseline before you set foot on the mountain. This is standard Perfect Kilimanjaro practice on every climb, for every client.\n\nBecause the 6-day version is the most compressed Lemosho itinerary we offer, we pay particular attention during this briefing to the client's fitness, altitude experience, and preparation. If any essential gear is missing or needs to be rented, your guide will direct you to trusted local rental options in Moshi. Arriving at least one full day before your climb is strongly recommended.\n\nOptional activities available on arrival day: Materuni Waterfall & Coffee Tour · Kikuletwa Hot Springs · Moshi Town Tour · Marangu Village Walk — visit the Ngatambaku Mtui family's home village.",
                            'stay' => 'Moshi hotel', 'meals' => 'D',
                        ],
                        [
                            't' => 'Day 1 — Londorossi Gate to Big Tree Camp',
                            'd' => "After breakfast at the hotel we make the three-hour drive from Moshi to Londorossi Gate on the remote western side of Kilimanjaro. At the gate we complete registration, collect permits, and enjoy a hot lunch before beginning our first steps on the mountain.\n\nThe first day's hike is intentionally gentle — just 5 kilometres through the lower montane rainforest. On the 6-day route this rest day matters more than usual: tomorrow is the longest and most demanding day of the entire itinerary, and your body needs to arrive at Big Tree Camp as rested and well-fed as possible.\n\nThe rainforest on the western approach is extraordinary and sees far fewer climbers than the southern routes. Colobus monkeys move through the canopy above you; rare forest birds call from the undergrowth.\n\nWe arrive at Big Tree Camp — Mti Mkubwa in Swahili — in the early afternoon. Your tent is already standing, hot drinks are waiting, and your crew is preparing dinner. We conduct an evening health check and prepare carefully for the long day ahead.",
                            'elev' => '2,350m → 2,800m (7,700ft → 9,200ft)', 'distance' => '5 km', 'time' => '2–3 hours', 'vegetation' => 'Montane Rainforest', 'difficulty' => 'Easy',
                            'stay' => 'Big Tree Camp', 'meals' => 'B, L, D',
                            'note' => 'Perfect Kilimanjaro note: Rest this evening. Eat well. Drink plenty of water. Sleep as early as you can. Tomorrow requires everything you have.',
                        ],
                        [
                            't' => 'Day 2 — Big Tree Camp to Shira 2 Camp',
                            'd' => "This is the day that defines the 6-day Lemosho experience. We leave Big Tree Camp after breakfast and climb steeply through the upper rainforest, the canopy gradually thinning as we gain altitude and the trail opens into wide moorland — one of the most dramatic transitions on the whole mountain.\n\nWe climb to Shira Ridge, where Kilimanjaro reveals itself for the first time. The trees are gone, and ahead — impossibly large and white against the sky — is Kibo, the summit dome. Many clients pause here for a long moment before moving on.\n\nWe descend gently to Shira 1 Camp at 3,500 metres for lunch — an important rest point. After lunch we continue across the Shira Plateau, the ancient collapsed caldera of a volcano, a vast expanse of open moorland stretching wide at over 3,700 metres, golden and open in the afternoon light with the summit rising dramatically ahead.\n\nWe arrive at Shira 2 Camp at 3,850 metres in the late afternoon or early evening. Evening health check, a hot dinner, and overnight at Shira 2 Camp.",
                            'elev' => '2,800m → 3,850m (9,200ft → 12,750ft)', 'distance' => '18 km', 'time' => '8–10 hours', 'vegetation' => 'Rainforest, Moorland & Shira Plateau', 'difficulty' => 'Hard — the longest, most demanding day of the route',
                            'stay' => 'Shira 2 Camp', 'meals' => 'B, L, D',
                            'note' => 'Perfect Kilimanjaro note: Arriving at 3,850 metres at the end of this demanding day is a real physical achievement. Your acclimatization window from here is tighter than on the 7 or 8-day versions, which makes proper rest tonight, consistent hydration, and a good hot meal especially important.',
                        ],
                        [
                            't' => 'Day 3 — Shira 2 Camp to Barranco Camp via Lava Tower',
                            'd' => "This is the single most important acclimatization day of the entire climb. We leave Shira 2 Camp at 3,850 metres and climb steadily toward Lava Tower, a dramatic volcanic formation at 4,600 metres — a substantial 750-metre gain. The terrain grows increasingly barren, the last moorland plants giving way to volcanic rock, dust, and open scree.\n\nYou are above 4,000 metres for the first time today. Many clients feel it — a slight heaviness in the legs, a mild headache, a sense of thinner air. This is normal: your body registering the altitude and beginning to adapt.\n\nWe stop at Lava Tower for lunch and a rest of approximately one hour, then descend 650 metres to Barranco Camp at 3,950 metres through the remarkable Senecio Forest, its giant groundsels rising from the rocky ground in the afternoon light.\n\nTonight you sleep 650 metres lower than the altitude your body reached today — on the 6-day route this acclimatization day carries extra weight, since you do not have the additional Karanga overnight built into the 7-day version. Evening health check, dinner, and overnight at Barranco Camp.",
                            'elev' => '3,850m → 4,600m (Lava Tower) → 3,950m (Barranco)', 'distance' => '10 km', 'time' => '6–8 hours', 'vegetation' => 'Alpine Desert', 'difficulty' => 'Moderate — climb high, sleep low',
                            'stay' => 'Barranco Camp', 'meals' => 'B, L, D',
                        ],
                        [
                            't' => 'Day 4 — Barranco Camp to Barafu Camp via Karanga',
                            'd' => "This is where the 6-day Lemosho diverges most from the 7-day version, and it asks the most of you physically and mentally. We begin with the Barranco Wall — imposing from below, but in practice a hands-on scramble most clients genuinely enjoy, steep and requiring focus but not technical. Most clients reach the top with big smiles and extraordinary views back across Barranco Camp and up toward a summit that suddenly feels close.\n\nAfter the wall we cross rolling alpine desert to Karanga Camp at approximately 4,000 metres for a full hot lunch and a real rest. On the 7-day Lemosho, Karanga is where the day ends, with an overnight acclimatization stop. On the 6-day, after lunch and rest, we continue.\n\nThe afternoon section from Karanga to Barafu covers roughly 4 kilometres and 2 to 3 hours of steady climbing through open, windswept alpine desert as the summit zone closes in above. We arrive at Barafu Camp at 4,673 metres in the late afternoon, tired from a day that began at the Barranco Wall and ends at base camp.\n\nDinner is served at 5:00pm. Your summit-night gear is laid out and ready. By 7:00pm you should be in your sleeping bag — the midnight alarm comes in five hours.",
                            'elev' => '3,950m → 4,000m (Karanga) → 4,673m (Barafu)', 'distance' => '10 km', 'time' => '6–7 hours', 'vegetation' => 'Alpine Desert', 'difficulty' => 'Moderate to Hard — the key difference from the 7-day itinerary',
                            'stay' => 'Barafu Camp', 'meals' => 'B, L, D',
                            'note' => 'Perfect Kilimanjaro note: This combined Karanga-to-Barafu day is where the 6-day route separates those who are well-prepared from those who are not. Priscus and the guide team monitor every client closely throughout this day and make real-time decisions about pacing based on what they observe.',
                        ],
                        [
                            't' => 'Day 5 — Summit Night: Barafu Camp to Uhuru Peak to Mweka Camp',
                            'd' => "This is the day. At midnight we wake you; tea and light snacks are prepared while you dress in every layer you have brought. Headlamps on. By 12:30am we are moving.\n\nThe ascent from Barafu to the crater rim is steep, cold, and relentless — loose volcanic scree shifting under every step. Plant your pole, move one foot forward, breathe, then the other foot. Your guide is with you every step, watching your pace, breathing, and movement continuously.\n\nWe reach Stella Point on the crater rim at approximately 5,685 metres, usually around sunrise — the glaciers of Kilimanjaro visible from above the cloud layer for the first time. From Stella Point we follow the crater rim for roughly 45 minutes to Uhuru Peak, 5,895 metres above sea level. The Roof of Africa.\n\nAfter celebrating at the summit we begin the long descent — first back to Barafu Camp for rest and lunch, then continuing all the way down to Mweka Camp at 3,100 metres for the night. The tipping ceremony takes place at Mweka Camp this evening, as the crew gathers, sings, and celebrates your summit with you.",
                            'elev' => '4,673m → 5,895m (Uhuru Peak) → 3,100m (Mweka Camp)', 'distance' => '18 km total', 'time' => '12–14 hours total', 'vegetation' => 'Alpine Desert → Arctic Summit Zone → Moorland', 'difficulty' => 'Very Hard',
                            'stay' => 'Mweka Camp', 'meals' => 'B, L, D',
                        ],
                        [
                            't' => 'Day 6 — Mweka Camp to Mweka Gate — Back to Moshi',
                            'd' => "The final morning. Legs are tired and well-earned. We descend through the rainforest — the same ecological zone you entered on Day 1, now seen through completely different eyes.\n\nWe arrive at Mweka Gate at approximately midday, where your official Kilimanjaro summit certificate is waiting — signed, stamped, and entirely earned. We drive back to Moshi for a celebration at the hotel: hot shower, cold drink, and a meal you did not have to carry up a mountain.\n\nA Perfect Kilimanjaro team member will sit with you for a brief debrief — your feedback matters to us, and any onward arrangements such as safari, Zanzibar, or additional Moshi activities can be confirmed on this day.",
                            'elev' => '3,100m → 1,600m (10,170ft → 5,250ft)', 'distance' => '10 km', 'time' => '3–4 hours', 'vegetation' => 'Rainforest', 'difficulty' => 'Easy',
                            'stay' => '—', 'meals' => 'B, L',
                        ],
                    ],
                ],
            ],
            [
                'slug' => 'lemosho-9-days',
                'sort_order' => 7,
                'data' => [
                    'name' => 'Lemosho Route',
                    'nickname' => 'Day Summit · 9 Days',
                    'location' => 'Kilimanjaro National Park · Moshi',
                    'days' => 9,
                    'price' => 2099,
                    'price_tiers' => $this->priceTierRows(['1' => 2649, '2' => 2349, '3' => 2199, '5' => 2099]),
                    'tag' => 'Moderate to Challenging',
                    'rate' => '95%+ summit rate',
                    'rating' => 4.9,
                    'reviews' => 410,
                    'group' => 'Max 10',
                    'difficulty' => 'Moderate',
                    'best_time' => 'Jan–Mar, Jun–Oct',
                    'start_point' => 'Moshi',
                    'image' => 'images/hero-kilimanjaro.jpg',
                    'accommodation' => 'Camping throughout (crew-carried tents) — no huts',
                    'blurb' => 'Our most recommended Lemosho itinerary — a full extra acclimatization night at Moir Hut plus a daylight summit push that lets you sleep a full night before the hardest effort of your climb.',
                    'overview' => "This is the route Priscus would choose if he were climbing Kilimanjaro for the first time. The 9-day Lemosho Day Summit follows the same scenic western approach and Shira Plateau crossing as the shorter Lemosho itineraries, but adds an overnight at Moir Hut (4,200m) — the highest camp on Kilimanjaro outside the summit zone — plus a full night's sleep at Barafu before a daylight summit push that begins at 5:30am and reaches Uhuru Peak between noon and 1pm, rather than in the cold and dark of a traditional midnight start.\n\n\"This is the route I recommend to families, to older climbers, to nervous first-timers, and to anyone who has dreamed about this summit for years,\" says Priscus Peter Mtui, our lead guide and owner. \"It also suits anyone planning something unforgettable at the top — a proposal, an engagement, even a wedding on the Roof of Africa. The 9-day Lemosho with Day Summit is the most intelligent, most comfortable, and most successful Kilimanjaro itinerary we offer. If you can do this one, do this one.\" Climbers who feel the cold strongly, have a visual impairment, or are planning a special moment at the summit are encouraged to contact Priscus directly to plan around this itinerary.\n\n".$this->pricingNote(['1' => 2649, '2' => 2349, '3' => 2199, '5' => 2099]).' '.$this->tippingNote(),
                    'highlights' => [
                        'Priscus\'s top-recommended Lemosho itinerary — 95%+ summit success rate',
                        'An overnight at Moir Hut (4,200m), the highest camp on Kilimanjaro outside the summit zone',
                        'Day Summit: begins 5:30am, reaches Uhuru Peak in daylight around noon–1pm instead of a cold midnight push',
                        'A full night\'s sleep at Barafu before the summit attempt — no exhausting midnight start',
                        'Ideal for families, older climbers, first-timers, and special-occasion summits (proposals, engagements) — contact us directly to plan one',
                    ],
                    'included' => $this->included(),
                    'excluded' => $this->excluded(),
                    'gallery' => $gallery,
                    'itinerary' => [
                        ['t' => 'Day 0 — Arrival in Moshi', 'd' => 'Airport pickup and transfer to your Moshi hotel, followed by an evening pre-climb briefing and first health check. Optional: Materuni Waterfall & Coffee Tour, Kikuletwa Hot Springs, Moshi Town Tour, or a visit to the Ngatambaku Mtui family\'s home village.', 'stay' => 'Moshi hotel', 'meals' => 'D'],
                        ['t' => 'Day 1 — Londorossi Gate to Big Tree Camp', 'd' => 'Registration at Londorossi Gate, then an easy first walk through montane rainforest to Big Tree Camp.', 'elev' => '2,350m → 2,800m', 'stay' => 'Big Tree Camp', 'meals' => 'B, L, D'],
                        ['t' => 'Day 2 — Big Tree Camp to Shira 1 Camp', 'd' => 'Climbing into open moorland, with the first clear views of Kibo from Shira Ridge.', 'elev' => '2,800m → 3,500m', 'stay' => 'Shira 1 Camp', 'meals' => 'B, L, D'],
                        ['t' => 'Day 3 — Shira 1 Camp to Moir Hut', 'd' => "A long, beautiful traverse of the Shira Plateau followed by a steeper climb to Moir Hut — the highest camp on Kilimanjaro outside the summit zone, higher than Karanga or Barranco. A short acclimatization walk to ~4,350m and back reinforces the altitude stimulus before dinner.", 'elev' => '3,500m → 4,200m', 'stay' => 'Moir Hut', 'meals' => 'B, L, D'],
                        ['t' => 'Day 4 — Moir Hut to Barranco Camp via Lava Tower', 'd' => 'A climb to Lava Tower (4,600m) for lunch, then descent through the Senecio Forest to Barranco Camp.', 'elev' => '4,200m → 4,600m → 3,950m', 'stay' => 'Barranco Camp', 'meals' => 'B, L, D'],
                        ['t' => 'Day 5 — Barranco Camp to Karanga Camp', 'd' => 'The Barranco Wall, then a relaxed crossing to Karanga Camp with a full afternoon to rest.', 'elev' => '3,950m → 3,995m', 'stay' => 'Karanga Camp', 'meals' => 'B, L, D'],
                        ['t' => 'Day 6 — Karanga Camp to Barafu Camp', 'd' => 'A short climb to Barafu, with a full rest afternoon and an early night — the alarm is set for 4:30am rather than midnight.', 'elev' => '3,995m → 4,673m', 'stay' => 'Barafu Camp', 'meals' => 'B, L, D'],
                        ['t' => 'Day 7 — Day Summit: Barafu Camp to Uhuru Peak and back to Barafu', 'd' => 'A 5:30am start for a daylight summit push, reaching Uhuru Peak between noon and 1pm in warmth and full visibility, then returning to Barafu Camp for a second night rather than descending further.', 'elev' => '4,673m → 5,895m → 4,673m', 'stay' => 'Barafu Camp', 'meals' => 'B, L, D'],
                        ['t' => 'Day 8 — Rest and descent to Mweka Camp', 'd' => 'A late, well-earned wake-up, then a gentle descent to Mweka Camp for the crew\'s tipping ceremony.', 'elev' => '4,673m → 3,100m', 'stay' => 'Mweka Camp', 'meals' => 'B, L, D'],
                        ['t' => 'Day 9 — Mweka Camp to Mweka Gate — back to Moshi', 'd' => 'Final descent through the rainforest to Mweka Gate for your summit certificate, then back to Moshi to celebrate.', 'elev' => '3,100m → 1,600m', 'stay' => '—', 'meals' => 'B, L'],
                    ],
                ],
            ],

            // ============================================================
            // MARANGU — three real product identities: Classic (Night Summit)
            // 5/6-day, and Day Summit 6/7-day. Canonical 'marangu' = Classic 6-day.
            // ============================================================
            [
                'slug' => 'marangu',
                'sort_order' => 8,
                'data' => [
                    'name' => 'Marangu Route',
                    'nickname' => 'the Coca-Cola Route · 6 Days',
                    'location' => 'Kilimanjaro National Park · Moshi',
                    'days' => 6,
                    'price' => 1520,
                    'price_tiers' => $this->priceTierRows(['1' => 1800, '2' => 1640, '3' => 1595, '5' => 1520]),
                    'tag' => 'Moderate',
                    'rate' => 'Solid, with an extra acclimatization day',
                    'rating' => 4.7,
                    'reviews' => 980,
                    'group' => 'Max 10',
                    'difficulty' => 'Moderate',
                    'best_time' => 'Jun–Oct, Dec–Mar',
                    'start_point' => 'Moshi',
                    'image' => 'images/hero-kilimanjaro.jpg',
                    'accommodation' => 'Permanent mountain huts (Mandara, Horombo, Kibo) — the only hut-based route',
                    'blurb' => 'The historic route first climbed in 1889 — the only path with hut accommodation, and the only one where you descend the same trail you climbed.',
                    'overview' => "Marangu is the oldest and most historic route on Kilimanjaro — first climbed by Hans Meyer in 1889, guided by a man from Priscus's own home village of Marangu. It's the only route with permanent mountain huts instead of tents, and the only route where you ascend and descend the same trail, which is why it's sometimes called the Coca-Cola Route. Licensed guides are required by Tanzanian law on every route, but Marangu's huts, cold showers and flushing toilets at Mandara and Horombo make it the most comfortable way up the mountain.\n\nThis 6-day Classic (Night Summit) itinerary adds a dedicated acclimatization day at Horombo Hut compared to the 5-day version, with a traditional midnight summit push. \"That extra acclimatization day at Horombo gives your body time it simply doesn't have on the 5-day version,\" says Priscus Peter Mtui, our lead guide and owner. \"If your schedule allows it, six days is the itinerary I recommend over five.\"\n\n".$this->pricingNote(['1' => 1800, '2' => 1640, '3' => 1595, '5' => 1520]).' '.$this->tippingNote(),
                    'highlights' => [
                        'The only route on Kilimanjaro with hut accommodation instead of tents',
                        'A dedicated acclimatization rest day at Horombo Hut before the push to Kibo',
                        'The same historic trail first climbed by Hans Meyer in 1889',
                        'Cold showers and flushing toilets at Mandara and Horombo huts',
                        'Also available as a 5-day Classic, or as a Day Summit itinerary (6 or 7 days) that reaches the summit in daylight',
                    ],
                    'included' => $this->marangunIncluded(true),
                    'excluded' => $this->marangunExcluded(),
                    'gallery' => $gallery,
                    'itinerary' => [
                        ['t' => 'Day 0 — Arrival in Moshi', 'd' => 'Airport pickup and transfer to your Moshi hotel, followed by an evening pre-climb briefing and first health check with Priscus or your assigned lead guide.', 'stay' => 'Moshi hotel', 'meals' => 'D'],
                        ['t' => 'Day 1 — Marangu Gate to Mandara Hut', 'd' => "A gentle first day through rainforest — blue monkeys and colobus overhead — with an optional short walk to Maundi Crater. Mandara Hut is named after the Chagga chief Mangi Mandara.", 'elev' => '1,879m → 2,720m', 'stay' => 'Mandara Hut', 'meals' => 'B, L, D'],
                        ['t' => 'Day 2 — Mandara Hut to Horombo Hut', 'd' => 'Climbing out of the forest into open moorland, with both Mawenzi and Kibo visible for the first time. An optional walk to the Zebra Rocks is available on arrival.', 'elev' => '2,720m → 3,720m', 'stay' => 'Horombo Hut', 'meals' => 'B, L, D'],
                        ['t' => 'Day 3 — Acclimatization day at Horombo Hut', 'd' => "A gentle walk toward Zebra Rocks and back, sleeping again at Horombo — the extra day that lifts this itinerary above the 5-day version.", 'elev' => '3,720m → 3,720m', 'stay' => 'Horombo Hut', 'meals' => 'B, L, D'],
                        ['t' => 'Day 4 — Horombo Hut to Kibo Hut', 'd' => 'Crossing the alpine desert "saddle" between Mawenzi and Kibo at a deliberately slow, pole pole pace. Early dinner at 5:30pm and bed well ahead of the midnight alarm.', 'elev' => '3,720m → 4,720m', 'stay' => 'Kibo Hut', 'meals' => 'B, L, D'],
                        ['t' => 'Day 5 — Summit night: Kibo Hut to Uhuru Peak, descending to Horombo Hut', 'd' => "Midnight start for Gilman's Point on the crater rim (5–6 hours), then a further 1–2 hours on to Uhuru Peak at 5,895m. After celebrating, descend all the way back down to Horombo Hut for the tipping ceremony that evening.", 'elev' => '4,720m → 5,895m → 3,720m', 'stay' => 'Horombo Hut', 'meals' => 'B, L, D'],
                        ['t' => 'Day 6 — Horombo Hut to Marangu Gate — back to Moshi', 'd' => 'A long final descent through moorland and rainforest back to Marangu Gate for your summit certificate, then the drive back to Moshi.', 'elev' => '3,720m → 1,879m', 'stay' => '—', 'meals' => 'B, L'],
                    ],
                ],
            ],
            [
                'slug' => 'marangu-5-days',
                'sort_order' => 9,
                'data' => [
                    'name' => 'Marangu Route',
                    'nickname' => 'the Coca-Cola Route · 5 Days',
                    'location' => 'Kilimanjaro National Park · Moshi',
                    'days' => 5,
                    'price' => 1310,
                    'price_tiers' => $this->priceTierRows(['1' => 1550, '2' => 1400, '3' => 1370, '5' => 1310]),
                    'tag' => 'Moderate',
                    'rate' => 'Lower than longer routes — tight acclimatization window',
                    'rating' => 4.4,
                    'reviews' => 420,
                    'group' => 'Max 10',
                    'difficulty' => 'Moderate',
                    'best_time' => 'Jun–Oct, Dec–Mar',
                    'start_point' => 'Moshi',
                    'image' => 'images/hero-kilimanjaro.jpg',
                    'accommodation' => 'Permanent mountain huts (Mandara, Horombo, Kibo) — the only hut-based route',
                    'blurb' => 'The fastest and most budget-friendly way up Kilimanjaro — achievable for well-prepared climbers, but with the tightest acclimatization window of any route we offer.',
                    'overview' => "Five days is the minimum time in which the Marangu Route can be completed. Like all Marangu itineraries it uses permanent mountain huts rather than tents, follows the mountain's oldest and most historic trail — first climbed in 1889 — and descends the same path you climb.\n\n\"Five days is the minimum time in which Marangu can be completed. It is achievable,\" says Priscus Peter Mtui, our lead guide and owner. \"But the acclimatization window is tight, and your body has less time to adjust than on any other route. I have guided many clients successfully on this itinerary. But if you are coming from sea level and this is your first experience above 3,000 metres, I would strongly encourage you to consider the 6 or 7-day version instead. The price difference is not large. The difference in your summit experience could be everything.\"\n\n".$this->pricingNote(['1' => 1550, '2' => 1400, '3' => 1370, '5' => 1310]).' '.$this->tippingNote(),
                    'highlights' => [
                        'The shortest, most budget-friendly way to attempt Kilimanjaro',
                        'Hut accommodation throughout — no tents to pitch',
                        'The historic 1889 first-summit trail',
                        'Recommended only for well-prepared climbers with some altitude experience',
                        'Consider the 6-day version for a meaningfully better acclimatization window',
                    ],
                    'included' => $this->marangunIncluded(true),
                    'excluded' => $this->marangunExcluded(),
                    'gallery' => $gallery,
                    'itinerary' => [
                        ['t' => 'Day 0 — Arrival in Moshi', 'd' => 'Airport pickup and transfer to your Moshi hotel, followed by an evening pre-climb briefing and first health check.', 'stay' => 'Moshi hotel', 'meals' => 'D'],
                        ['t' => 'Day 1 — Marangu Gate to Mandara Hut', 'd' => 'A gentle first day through rainforest, with an optional short walk to Maundi Crater.', 'elev' => '1,879m → 2,720m', 'stay' => 'Mandara Hut', 'meals' => 'B, L, D'],
                        ['t' => 'Day 2 — Mandara Hut to Horombo Hut', 'd' => 'Climbing into open moorland with both Mawenzi and Kibo visible for the first time.', 'elev' => '2,720m → 3,720m', 'stay' => 'Horombo Hut', 'meals' => 'B, L, D'],
                        ['t' => 'Day 3 — Horombo Hut to Kibo Hut', 'd' => 'Crossing the alpine desert saddle between Mawenzi and Kibo at a slow, deliberate pace. Early dinner and bed ahead of the midnight alarm.', 'elev' => '3,720m → 4,720m', 'stay' => 'Kibo Hut', 'meals' => 'B, L, D'],
                        ['t' => 'Day 4 — Summit night: Kibo Hut to Uhuru Peak, descending to Horombo Hut', 'd' => "Midnight start for Gilman's Point (5–6 hours), then on to Uhuru Peak at 5,895m. Descend all the way back to Horombo Hut for the evening tipping ceremony — a long, demanding day.", 'elev' => '4,720m → 5,895m → 3,720m', 'stay' => 'Horombo Hut', 'meals' => 'B, L, D'],
                        ['t' => 'Day 5 — Horombo Hut to Marangu Gate — back to Moshi', 'd' => 'The final descent through moorland and rainforest to Marangu Gate, then back to Moshi.', 'elev' => '3,720m → 1,879m', 'stay' => '—', 'meals' => 'B, L'],
                    ],
                ],
            ],
            [
                'slug' => 'marangu-day-summit-6-days',
                'sort_order' => 10,
                'data' => [
                    'name' => 'Marangu Route',
                    'nickname' => 'Day Summit · 6 Days',
                    'location' => 'Kilimanjaro National Park · Moshi',
                    'days' => 6,
                    'price' => 1520,
                    'price_tiers' => $this->priceTierRows(['1' => 1800, '2' => 1640, '3' => 1595, '5' => 1520]),
                    'tag' => 'Moderate',
                    'rate' => 'Improved by a daylight summit push',
                    'rating' => 4.7,
                    'reviews' => 260,
                    'group' => 'Max 10',
                    'difficulty' => 'Moderate',
                    'best_time' => 'Jun–Oct, Dec–Mar',
                    'start_point' => 'Moshi',
                    'image' => 'images/hero-kilimanjaro.jpg',
                    'accommodation' => 'Permanent mountain huts (Mandara, Horombo, Kibo)',
                    'blurb' => 'The historic "mother route," first climbed by Hans Meyer in 1889, with a 6:30am daylight summit push instead of a midnight start.',
                    'overview' => "Marangu is the mother route of Kilimanjaro — first climbed by Hans Meyer in 1889, and still the only route with hut accommodation. This Day Summit version starts the final push at 6:30am rather than midnight, reaching Uhuru Peak around 1:30pm in daylight before descending to Kibo Hut the same day, rather than pushing all the way back to Horombo.\n\n\"One full acclimatization day at Horombo, a summit walk in daylight instead of darkness, and no long walk back down to Horombo on summit day itself,\" says Priscus Peter Mtui, our lead guide and owner. \"If you can spare a seventh day, the second acclimatization day at Horombo will improve your odds even further. But for climbers on a tighter schedule, six days with a Day Summit is a strong, honest option.\"\n\n".$this->pricingNote(['1' => 1800, '2' => 1640, '3' => 1595, '5' => 1520]).' '.$this->tippingNote(),
                    'highlights' => [
                        'A daylight summit push starting 6:30am, reaching Uhuru Peak around 1:30pm — no midnight cold start',
                        'Descends only to Kibo Hut on summit day, with an easier separate descent day after',
                        'The historic trail first climbed by Hans Meyer in 1889 — known as the "Coca-Cola Route"',
                        'Hut accommodation throughout',
                        'Also available with a second acclimatization day as the 7-day Day Summit version',
                    ],
                    'included' => $this->marangunIncluded(false),
                    'excluded' => $this->marangunExcluded(),
                    'gallery' => $gallery,
                    'itinerary' => [
                        ['t' => 'Day 0 — Arrival in Moshi', 'd' => 'Airport pickup and transfer to your Moshi hotel, followed by an evening pre-climb briefing and first health check.', 'stay' => 'Moshi hotel', 'meals' => 'D'],
                        ['t' => 'Day 1 — Marangu Gate to Mandara Hut', 'd' => 'A gentle first day through rainforest, with an optional short walk to Maundi Crater.', 'elev' => '1,879m → 2,720m', 'stay' => 'Mandara Hut', 'meals' => 'B, L, D'],
                        ['t' => 'Day 2 — Mandara Hut to Horombo Hut', 'd' => 'Climbing into open moorland with both Mawenzi and Kibo visible for the first time.', 'elev' => '2,720m → 3,720m', 'stay' => 'Horombo Hut', 'meals' => 'B, L, D'],
                        ['t' => 'Day 3 — Horombo Hut to Kibo Hut', 'd' => 'Crossing the alpine desert saddle between Mawenzi and Kibo. Early dinner and bed, ahead of a 5:30am wake-up.', 'elev' => '3,720m → 4,720m', 'stay' => 'Kibo Hut', 'meals' => 'B, L, D'],
                        ['t' => 'Day 4 — Day Summit: Kibo Hut to Uhuru Peak and back to Kibo Hut', 'd' => "A 6:30am start for a daylight summit push via Gilman's Point, reaching Uhuru Peak around 1:30pm in full visibility, then descending back to Kibo Hut the same day — roughly 7 hours up, 4 hours down.", 'elev' => '4,720m → 5,895m → 4,720m', 'stay' => 'Kibo Hut', 'meals' => 'B, L, D'],
                        ['t' => 'Day 5 — Kibo Hut to Horombo Hut', 'd' => 'An easier, separate descent day back to Horombo Hut for the crew\'s tipping ceremony.', 'elev' => '4,720m → 3,720m', 'stay' => 'Horombo Hut', 'meals' => 'B, L, D'],
                        ['t' => 'Day 6 — Horombo Hut to Marangu Gate — back to Moshi', 'd' => 'Final descent through moorland and rainforest to Marangu Gate for your summit certificate, then back to Moshi.', 'elev' => '3,720m → 1,879m', 'stay' => '—', 'meals' => 'B, L'],
                    ],
                ],
            ],
            [
                'slug' => 'marangu-day-summit-7-days',
                'sort_order' => 11,
                'data' => [
                    'name' => 'Marangu Route',
                    'nickname' => 'Day Summit · 7 Days',
                    'location' => 'Kilimanjaro National Park · Moshi',
                    'days' => 7,
                    'price' => 1620,
                    'price_tiers' => $this->priceTierRows(['1' => 2035, '2' => 1760, '3' => 1650, '5' => 1620]),
                    'tag' => 'Moderate',
                    'rate' => 'Our top-recommended Marangu itinerary',
                    'rating' => 4.8,
                    'reviews' => 190,
                    'group' => 'Max 10',
                    'difficulty' => 'Moderate',
                    'best_time' => 'Jun–Oct, Dec–Mar',
                    'start_point' => 'Moshi',
                    'image' => 'images/hero-kilimanjaro.jpg',
                    'accommodation' => 'Permanent mountain huts (Mandara, Horombo, Kibo)',
                    'blurb' => "Two acclimatization days at Horombo plus a daylight summit push — the version of Marangu Priscus recommends most for the best possible odds.",
                    'overview' => "The 7-day Marangu Day Summit combines two full acclimatization days at Horombo Hut with a 6:30am daylight summit push, reaching Uhuru Peak around 1:30pm rather than in the cold and dark of a traditional midnight start.\n\n\"Of every itinerary we offer on Marangu, this is the one I recommend most for climbers who want to give themselves the best possible chance,\" says Priscus Peter Mtui, our lead guide and owner. \"Two full acclimatization days at Horombo, a summit walk in daylight instead of darkness, and no long walk back down to Horombo on summit day itself. It costs a little more and takes a little longer than the classic route. But if you can spare the extra day, I believe it's worth it.\"\n\n".$this->pricingNote(['1' => 2035, '2' => 1760, '3' => 1650, '5' => 1620]).' '.$this->tippingNote(),
                    'highlights' => [
                        'Two full acclimatization days at Horombo Hut — the best odds of any Marangu itinerary',
                        'A daylight summit push starting 6:30am, reaching Uhuru Peak around 1:30pm',
                        'Descends only to Kibo Hut on summit day, with an easier separate descent day after',
                        'Hut accommodation throughout the historic 1889 first-summit trail',
                        'Priscus\'s top recommendation for climbers choosing Marangu',
                    ],
                    'included' => $this->marangunIncluded(false),
                    'excluded' => $this->marangunExcluded(),
                    'gallery' => $gallery,
                    'itinerary' => [
                        ['t' => 'Day 0 — Arrival in Moshi', 'd' => 'Airport pickup and transfer to your Moshi hotel, followed by an evening pre-climb briefing and first health check.', 'stay' => 'Moshi hotel', 'meals' => 'D'],
                        ['t' => 'Day 1 — Marangu Gate to Mandara Hut', 'd' => 'A gentle first day through rainforest, with an optional short walk to Maundi Crater.', 'elev' => '1,879m → 2,720m', 'stay' => 'Mandara Hut', 'meals' => 'B, L, D'],
                        ['t' => 'Day 2 — Mandara Hut to Horombo Hut', 'd' => 'Climbing into open moorland with both Mawenzi and Kibo visible for the first time.', 'elev' => '2,720m → 3,720m', 'stay' => 'Horombo Hut', 'meals' => 'B, L, D'],
                        ['t' => 'Day 3 — Acclimatization day at Horombo Hut', 'd' => 'A gentle walk toward Zebra Rocks and back, sleeping again at Horombo — the second acclimatization day that sets this itinerary apart.', 'elev' => '3,720m → 3,720m', 'stay' => 'Horombo Hut', 'meals' => 'B, L, D'],
                        ['t' => 'Day 4 — Horombo Hut to Kibo Hut', 'd' => 'Crossing the alpine desert saddle between Mawenzi and Kibo. Early dinner and bed, ahead of a 5:30am wake-up.', 'elev' => '3,720m → 4,720m', 'stay' => 'Kibo Hut', 'meals' => 'B, L, D'],
                        ['t' => 'Day 5 — Day Summit: Kibo Hut to Uhuru Peak and back to Kibo Hut', 'd' => "A 6:30am start for a daylight summit push via Gilman's Point, reaching Uhuru Peak around 1:30pm, then descending back to Kibo Hut the same day.", 'elev' => '4,720m → 5,895m → 4,720m', 'stay' => 'Kibo Hut', 'meals' => 'B, L, D'],
                        ['t' => 'Day 6 — Kibo Hut to Horombo Hut', 'd' => 'An easier, separate descent day back to Horombo Hut for the crew\'s tipping ceremony.', 'elev' => '4,720m → 3,720m', 'stay' => 'Horombo Hut', 'meals' => 'B, L, D'],
                        ['t' => 'Day 7 — Horombo Hut to Marangu Gate — back to Moshi', 'd' => 'Final descent through moorland and rainforest to Marangu Gate for your summit certificate, then back to Moshi.', 'elev' => '3,720m → 1,879m', 'stay' => '—', 'meals' => 'B, L'],
                    ],
                ],
            ],

            // ============================================================
            // NORTHERN CIRCUIT — canonical (8 days) + 9-day variant.
            // NOTE: the 9-day source document's pricing table, tipping note
            // and closing note were an unedited copy of the 8-day template
            // (still says "8 days" throughout) — its price below is an
            // estimate (8-day rate + one extra trekking day) and should be
            // confirmed with Priscus before being treated as final.
            // ============================================================
            [
                'slug' => 'northern',
                'sort_order' => 12,
                'data' => [
                    'name' => 'Northern Circuit',
                    'nickname' => 'the full traverse · 8 Days',
                    'location' => 'Kilimanjaro National Park · Moshi',
                    'days' => 8,
                    'price' => 1979,
                    'price_tiers' => $this->priceTierRows(['1' => 2475, '2' => 2179, '3' => 2079, '5' => 1979]),
                    'tag' => 'Moderate to Challenging',
                    'rate' => '~90% summit rate',
                    'rating' => 4.9,
                    'reviews' => 260,
                    'group' => 'Max 10',
                    'difficulty' => 'Moderate',
                    'best_time' => 'Jan–Mar, Jun–Oct',
                    'start_point' => 'Moshi',
                    'image' => 'images/hero-kilimanjaro.jpg',
                    'accommodation' => 'Camping throughout (crew-carried tents) — no huts',
                    'blurb' => 'The longest, quietest way up Kilimanjaro — begins like Lemosho, then turns north into remote wilderness most climbers never see, crossing the entire mountain on summit day.',
                    'overview' => "The Northern Circuit begins exactly like the standard Lemosho Route — remote western wilderness, pristine rainforest, and the ancient Shira Plateau, climbing to Moir Hut for the most powerful acclimatization night available on the mountain. But instead of descending to Lava Tower and Barranco, this itinerary turns north on Day 4, leaving the crowded Southern Circuit behind for the wide, dramatic, and almost entirely empty northern slopes of Kilimanjaro.\n\nThe continuous ridge-crossing on the northern traverse — climbing high and descending lower throughout each day — gives an acclimatization profile that rivals any route on the mountain, while the near-total absence of other groups makes it one of the most genuinely quiet experiences Kilimanjaro has to offer. Summit day is unlike any other route: you ascend from the north through Kibo Hut and Gilman's Point, summit at Uhuru Peak, then descend on the south side through Stella Point to Barafu Camp — crossing the entire mountain from north to south in one extraordinary day.\n\n\"After guiding on Kilimanjaro for more than 14 years and leading over 350 groups to the summit, the Northern Circuit is the route I recommend to any climber who wants something genuinely different,\" says Priscus Peter Mtui, our lead guide and owner. \"It offers the best combination of acclimatization, scenery, quiet trails, and an unforgettable mountain experience. If you have already climbed Lemosho or Machame and want to know this mountain from a completely different angle, this is the route I recommend.\"\n\n".$this->pricingNote(['1' => 2475, '2' => 2179, '3' => 2079, '5' => 1979]).' Pulse oximeter health checks are carried out twice daily from Day 0 at the hotel. '.$this->tippingNote(),
                    'highlights' => [
                        'Total distance ~90km — the longest standard route on Kilimanjaro',
                        'Begins like Lemosho through remote western wilderness and the Shira Plateau',
                        'Turns north on Day 4 into wide, dramatic terrain most climbers never see',
                        'Sweeping views of Mawenzi Peak and the plains toward Kenya from the northern traverse',
                        'Crosses the entire mountain north to south on summit day, via Kibo Hut, Gilman\'s Point, Uhuru Peak and Stella Point',
                    ],
                    'included' => [
                        'All Kilimanjaro National Park fees, conservation fees, camping fees, and rescue fees',
                        'Airport pick-up and drop-off',
                        'Hotel accommodation in Moshi (bed and breakfast) — one night before and one night after the climb',
                        'Camping equipment including tents, chairs and tables',
                        'Professional guides, cook and porters',
                        'All meals on the mountain',
                        'Transport to the climbing gate and back to the hotel',
                        'Emergency oxygen cylinder',
                        'Pulse oximeter health checks — twice daily from Day 0',
                        'Company taxes and annual fees',
                    ],
                    'excluded' => [
                        'International flights and Tanzania visa fees',
                        'Personal climbing gear and clothing',
                        'Food and drinks at the hotel except breakfast',
                        'Travel insurance (mandatory, must cover emergency evacuation)',
                        'Tips for guides, cook, and porters',
                        'Medical vaccinations and medication including Diamox',
                    ],
                    'gallery' => $gallery,
                    'itinerary' => [
                        ['t' => 'Day 0 — Arrival in Moshi', 'd' => 'Airport pickup and transfer to your Moshi hotel, followed by an evening pre-climb briefing and first health check — measuring blood oxygen and resting heart rate to establish your baseline.', 'stay' => 'Moshi hotel', 'meals' => 'D'],
                        ['t' => 'Day 1 — Londorossi Gate to Big Tree Camp', 'd' => 'Registration at Londorossi Gate, then a walk through rainforest alive with blue monkeys and colobus to Big Tree Camp (Mti Mkubwa).', 'elev' => '2,350m → 2,800m', 'stay' => 'Big Tree Camp', 'meals' => 'B, L, D'],
                        ['t' => 'Day 2 — Big Tree Camp to Shira 1 Camp', 'd' => 'A steep climb through rainforest into moorland, with the first dramatic view of Kibo from Shira Ridge.', 'elev' => '2,800m → 3,500m', 'stay' => 'Shira 1 Camp', 'meals' => 'B, L, D'],
                        ['t' => 'Day 3 — Shira 1 Camp to Moir Hut', 'd' => 'A long traverse of the Shira Plateau followed by a steeper climb to Moir Hut — the highest camp on Kilimanjaro outside the summit zone — with a short acclimatization walk to ~4,350m before dinner.', 'elev' => '3,500m → 4,200m', 'stay' => 'Moir Hut', 'meals' => 'B, L, D'],
                        ['t' => 'Day 4 — Moir Hut to Buffalo Camp', 'd' => "The Northern Circuit reveals itself: heading north away from the Southern Circuit crowds, crossing a series of ridges up to ~4,400m before descending to Buffalo Camp, an exposed viewpoint over the northern plains toward Kenya.", 'elev' => '4,200m → 4,000m', 'stay' => 'Buffalo Camp', 'meals' => 'B, L, D'],
                        ['t' => 'Day 5 — Buffalo Camp to Third Cave', 'd' => 'A shorter, deliberately gentler day with sweeping sunrise views of Mawenzi Peak, descending gradually across rolling northern terrain to Third Cave Camp.', 'elev' => '4,000m → 3,800m', 'stay' => 'Third Cave Camp', 'meals' => 'B, L, D'],
                        ['t' => 'Day 6 — Third Cave to Kibo Hut', 'd' => 'Leaving the northern traverse to climb steadily onto the Saddle between Kibo and Mawenzi, arriving at Kibo Hut — the final camp before the summit — for an early dinner and final gear check.', 'elev' => '3,800m → 4,700m', 'stay' => 'Kibo Hut', 'meals' => 'B, L, D'],
                        ['t' => 'Day 7 — Summit night: Kibo Hut to Uhuru Peak to Mweka Camp', 'd' => "Midnight start up the northern approach to Gilman's Point (5,681m) around sunrise, then along the crater rim via Stella Point to Uhuru Peak (5,895m). Descend the southern scree to Barafu for a short rest, then continue all the way down to Mweka Camp for the tipping ceremony — crossing the entire mountain north to south in one day.", 'elev' => '4,700m → 5,895m → 3,100m', 'stay' => 'Mweka Camp', 'meals' => 'B, L, D'],
                        ['t' => 'Day 8 — Mweka Camp to Mweka Gate — back to Moshi', 'd' => 'A final descent through rainforest to Mweka Gate for your summit certificate, then back to Moshi to celebrate.', 'elev' => '3,100m → 1,640m', 'stay' => '—', 'meals' => 'B, L'],
                    ],
                ],
            ],
            [
                'slug' => 'northern-9-days',
                'sort_order' => 13,
                'data' => [
                    'name' => 'Northern Circuit',
                    'nickname' => 'the full traverse · 9 Days',
                    'location' => 'Kilimanjaro National Park · Moshi',
                    'days' => 9,
                    'price' => 2159,
                    'tag' => 'Moderate to Challenging',
                    'rate' => '~90%+ summit rate',
                    'rating' => 4.9,
                    'reviews' => 90,
                    'group' => 'Max 10',
                    'difficulty' => 'Moderate',
                    'best_time' => 'Jan–Mar, Jun–Oct',
                    'start_point' => 'Moshi',
                    'image' => 'images/hero-kilimanjaro.jpg',
                    'accommodation' => 'Camping throughout (crew-carried tents) — no huts',
                    'blurb' => 'The full Northern Circuit traverse with one extra acclimatization stage — a rest day at Shira 2 plus a stop at Lava Tower before turning north.',
                    'overview' => "The 9-day Northern Circuit follows the same route as the 8-day version, with one added acclimatization stage: rather than moving directly from Shira 1 to Moir Hut, this itinerary adds a night at Shira 2 Camp followed by a stop at Lava Tower (4,600m) for extra high-altitude exposure before descending to Moir Hut to begin the northern traverse. Everything from Moir Hut onward — Buffalo Camp, Third Cave, Kibo Hut, and the north-to-south summit crossing via Gilman's Point, Uhuru Peak and Stella Point — is identical to the 8-day itinerary.\n\n\"The Northern Circuit is the route I recommend to any climber who wants something genuinely different,\" says Priscus Peter Mtui, our lead guide and owner. \"The extra day at Shira 2 and Lava Tower gives your body more meaningful time at altitude before the traverse even begins — on top of an already excellent acclimatization profile.\"\n\nPricing for the 9-day is currently confirmed as a starting estimate — the extra trekking day is priced in line with our other routes' per-day rates, but please contact us directly for a final quote, as the business is finalising exact 9-day figures. ".$this->tippingNote(),
                    'highlights' => [
                        'The full Northern Circuit route with one added acclimatization stage before the northern traverse',
                        'An extra night at Shira 2 Camp plus a stop at Lava Tower (4,600m) before Moir Hut',
                        'Same remote northern traverse, Buffalo Camp and Third Cave as the 8-day version',
                        'Same dramatic north-to-south summit crossing via Gilman\'s Point, Uhuru Peak and Stella Point',
                        'Contact us directly for a confirmed final quote on this duration',
                    ],
                    'included' => [
                        'All Kilimanjaro National Park fees, conservation fees, camping fees, and rescue fees',
                        'Airport pick-up and drop-off',
                        'Hotel accommodation in Moshi (bed and breakfast) — one night before and one night after the climb',
                        'Camping equipment including tents, chairs and tables',
                        'Professional guides, cook and porters',
                        'All meals on the mountain',
                        'Transport to the climbing gate and back to the hotel',
                        'Emergency oxygen cylinder',
                        'Pulse oximeter health checks — twice daily from Day 0',
                        'Company taxes and annual fees',
                    ],
                    'excluded' => [
                        'International flights and Tanzania visa fees',
                        'Personal climbing gear and clothing',
                        'Food and drinks at the hotel except breakfast',
                        'Travel insurance (mandatory, must cover emergency evacuation)',
                        'Tips for guides, cook, and porters',
                        'Medical vaccinations and medication including Diamox',
                    ],
                    'gallery' => $gallery,
                    'itinerary' => [
                        ['t' => 'Day 0 — Arrival in Moshi', 'd' => 'Airport pickup and transfer to your Moshi hotel, followed by an evening pre-climb briefing and first health check.', 'stay' => 'Moshi hotel', 'meals' => 'D'],
                        ['t' => 'Day 1 — Londorossi Gate to Big Tree Camp', 'd' => 'Registration at Londorossi Gate, then a walk through rainforest to Big Tree Camp.', 'elev' => '2,350m → 2,800m', 'stay' => 'Big Tree Camp', 'meals' => 'B, L, D'],
                        ['t' => 'Day 2 — Big Tree Camp to Shira 1 Camp', 'd' => 'A steep climb into moorland, with the first dramatic view of Kibo from Shira Ridge.', 'elev' => '2,800m → 3,500m', 'stay' => 'Shira 1 Camp', 'meals' => 'B, L, D'],
                        ['t' => 'Day 3 — Shira 1 Camp to Shira 2 Camp', 'd' => 'A shorter, more relaxed day crossing the Shira Plateau, with time to rest and an optional visit to Shira Cave.', 'elev' => '3,500m → 3,850m', 'stay' => 'Shira 2 Camp', 'meals' => 'B, L, D'],
                        ['t' => 'Day 4 — Shira 2 Camp to Moir Hut via Lava Tower', 'd' => 'A climb to Lava Tower (4,600m) for lunch and extra high-altitude exposure, then descent to Moir Hut — the highest camp on Kilimanjaro outside the summit zone. This extra stage is the main difference from the 8-day itinerary.', 'elev' => '3,850m → 4,600m → 4,200m', 'stay' => 'Moir Hut', 'meals' => 'B, L, D'],
                        ['t' => 'Day 5 — Moir Hut to Buffalo Camp', 'd' => 'Heading north away from the Southern Circuit, crossing ridges up to ~4,400m before descending to Buffalo Camp, an exposed viewpoint toward Kenya.', 'elev' => '4,200m → 4,000m', 'stay' => 'Buffalo Camp', 'meals' => 'B, L, D'],
                        ['t' => 'Day 6 — Buffalo Camp to Third Cave', 'd' => 'A shorter day with sweeping sunrise views of Mawenzi Peak, descending gradually to Third Cave Camp.', 'elev' => '4,000m → 3,800m', 'stay' => 'Third Cave Camp', 'meals' => 'B, L, D'],
                        ['t' => 'Day 7 — Third Cave to Kibo Hut', 'd' => 'Climbing onto the Saddle between Kibo and Mawenzi to Kibo Hut, the final camp before the summit.', 'elev' => '3,800m → 4,700m', 'stay' => 'Kibo Hut', 'meals' => 'B, L, D'],
                        ['t' => 'Day 8 — Summit night: Kibo Hut to Uhuru Peak to Mweka Camp', 'd' => "Midnight start up the northern approach to Gilman's Point around sunrise, on to Uhuru Peak (5,895m), then descend via Stella Point and Barafu all the way to Mweka Camp for the tipping ceremony.", 'elev' => '4,700m → 5,895m → 3,100m', 'stay' => 'Mweka Camp', 'meals' => 'B, L, D'],
                        ['t' => 'Day 9 — Mweka Camp to Mweka Gate — back to Moshi', 'd' => 'Final descent through rainforest to Mweka Gate for your summit certificate, then back to Moshi to celebrate.', 'elev' => '3,100m → 1,640m', 'stay' => '—', 'meals' => 'B, L'],
                    ],
                ],
            ],
        ];
    }
}
