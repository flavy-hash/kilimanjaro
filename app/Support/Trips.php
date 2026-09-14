<?php

namespace App\Support;

/**
 * Seed data only — the live site reads packages from the database.
 *
 * This is the original hard-coded package list, kept as the import source for
 * PackageSeeder. Editing it does nothing until the seeder is re-run; to change
 * a live package, use the admin panel at /admin.
 *
 * @see \Database\Seeders\PackageSeeder
 */
class Trips
{
    /**
     * @return array<int, array<string, mixed>>
     */
    public static function all(): array
    {
        return [
            [
                'id' => 'northern-circuit', 'category' => 'safari', 'name' => 'Northern Circuit Safari',
                'nickname' => 'Serengeti · Ngorongoro', 'location' => 'Tarangire · Serengeti · Ngorongoro',
                'days' => 5, 'price' => 1450, 'tag' => 'Classic',
                'rate' => '96% wildlife sightings', 'rating' => 4.9, 'reviews' => 2847,
                'group' => 'Max 6', 'difficulty' => 'Easy', 'best_time' => 'Jun–Oct, Jan–Mar', 'start_point' => 'Arusha',
                'image' => 'images/hero-safari.jpg', 'accommodation' => 'Permanent lodges & tented camps',
                'blurb' => 'The signature loop through Tarangire, Ngorongoro Crater and the Serengeti plains, timed to seasonal migration reports.',
                'overview' => "This is the trip most people picture when they think of Tanzania. Five days across the Northern Circuit's three headline parks — Tarangire's baobab country, the endless Serengeti plains, and the crater floor at Ngorongoro. We plan the route around the latest migration reports rather than a fixed loop, so where you spend your longest days depends on where the herds actually are the week you travel. Small group, one vehicle, one guide for the whole trip.",
                'highlights' => ['4x4 land cruiser with a pop-up roof and a guaranteed window seat', 'A full descent into Ngorongoro Crater at sunrise, before the day vehicles arrive', 'Route re-planned weekly against current migration reports', 'The same guide for all five days, not a different driver per park', 'Park fees, camps and all meals included in the headline price'],
                'included' => ['All park and conservation fees', 'Private 4x4 land cruiser with pop-up roof', 'Professional Tanzanian guide throughout', 'Full board accommodation as listed', 'Bottled drinking water in the vehicle', 'Airport transfers in Arusha'],
                'excluded' => ['International flights and visa fees', 'Travel and medical insurance', 'Tips for your guide and camp crew', 'Alcoholic drinks and personal items', 'Optional balloon safari over the Serengeti'],
                'stay' => [
                    'name' => 'Serengeti Tented Camp', 'image' => 'images/stay-safari.jpg', 'stars' => 4,
                    'place' => 'Central Serengeti', 'class' => 'Mid-range · Full board',
                    'amenities' => ['Hot showers', 'Solar power', 'Dining tent', 'Laundry'],
                    'note' => 'Permanent tented camp with en-suite bathrooms, moved seasonally to stay close to the herds.',
                ],
                'gallery' => ['images/gal-lion.jpg', 'images/gal-elephants.jpg', 'images/hero-safari.jpg'],
                'itinerary' => [
                    ['t' => 'Arrival & Tarangire', 'd' => 'Land in Arusha and head straight to Tarangire for an afternoon game drive among its baobabs and elephant herds.', 'stay' => 'Tarangire lodge', 'meals' => 'L, D'],
                    ['t' => 'Into the Serengeti', 'd' => "Morning drive south, crossing into the Serengeti's endless plains by afternoon.", 'stay' => 'Central Serengeti camp', 'meals' => 'B, L, D'],
                    ['t' => 'Full day, Serengeti', 'd' => 'A full day tracking wildlife on the central plains, routed to wherever the migration currently is.', 'stay' => 'Central Serengeti camp', 'meals' => 'B, L, D'],
                    ['t' => 'Ngorongoro Crater', 'd' => 'Descend into the crater at sunrise for the densest wildlife concentration in Africa.', 'stay' => 'Crater rim lodge', 'meals' => 'B, L, D'],
                    ['t' => 'Departure', 'd' => 'Final morning drive, then transfer back to Arusha or the airport.', 'stay' => '—', 'meals' => 'B'],
                ],
            ],
            [
                'id' => 'southern-circuit', 'category' => 'safari', 'name' => 'Southern Circuit Safari',
                'nickname' => 'Ruaha · Nyerere', 'location' => 'Ruaha · Nyerere (Selous) · Mikumi',
                'days' => 6, 'price' => 1850, 'tag' => 'Mid-range',
                'rate' => 'Low-traffic parks', 'rating' => 4.8, 'reviews' => 612,
                'group' => 'Max 6', 'difficulty' => 'Easy–Moderate', 'best_time' => 'Jun–Oct', 'start_point' => 'Dar es Salaam',
                'image' => 'images/menu-southern.jpg', 'accommodation' => 'Tented camps, fly-in options',
                'blurb' => "Tanzania's wilder, quieter parks — bigger elephant herds, fewer vehicles, and prices that reflect it.",
                'overview' => "The southern parks see a fraction of the Northern Circuit's traffic, and it shows: bigger elephant herds, wild dog sightings, and hours of game viewing without another vehicle in sight. Six days across Ruaha, Nyerere (formerly Selous) and Mikumi, with boat safaris on the Rufiji River that simply aren't possible up north. Fly between parks to save a day, or drive it if you'd rather see the country in between.",
                'highlights' => ['Boat safari on the Rufiji River — not possible in the northern parks', 'Guided walking safari in Nyerere, on foot with an armed ranger', "Ruaha's big elephant herds and one of Tanzania's densest lion populations", 'Fly-in or drive between parks, priced either way', 'Whole days of game viewing without meeting another vehicle'],
                'included' => ['All park and reserve fees', 'Private 4x4 safari vehicle and guide', 'Boat safari on the Rufiji River', 'Full board accommodation as listed', 'Bottled drinking water throughout', 'Ground transfers between parks'],
                'excluded' => ['International and domestic flights', 'Travel and medical insurance', 'Tips for guide and camp staff', 'Alcoholic drinks and laundry', 'Walking safari permits where extra'],
                'stay' => [
                    'name' => 'Ruaha River Camp', 'image' => 'images/stay-safari.jpg', 'stars' => 4,
                    'place' => 'Ruaha National Park', 'class' => 'Mid-range · Full board',
                    'amenities' => ['Wi-Fi in lounge', 'Hot showers', 'Bar', 'Guided walks'],
                    'note' => 'Tented rooms on a rocky bend of the Great Ruaha, with elephants passing the deck most evenings.',
                ],
                'gallery' => ['images/menu-southern.jpg', 'images/gal-elephants.jpg', 'images/stay-safari.jpg'],
                'itinerary' => [
                    ['t' => 'Arrival, Ruaha', 'd' => 'Fly into Ruaha National Park and head out on an afternoon game drive.', 'stay' => 'Ruaha River camp', 'meals' => 'L, D'],
                    ['t' => 'Ruaha, full day', 'd' => "A full day among Ruaha's baobabs, big cats and some of Tanzania's largest elephant herds.", 'stay' => 'Ruaha River camp', 'meals' => 'B, L, D'],
                    ['t' => 'On to Nyerere', 'd' => 'Fly to Nyerere (Selous) and take an afternoon boat safari on the Rufiji River.', 'stay' => 'Rufiji tented camp', 'meals' => 'B, L, D'],
                    ['t' => 'Nyerere, full day', 'd' => "Walking safari and game drives across one of Africa's largest protected wilderness areas.", 'stay' => 'Rufiji tented camp', 'meals' => 'B, L, D'],
                    ['t' => 'Mikumi', 'd' => 'Transfer to Mikumi National Park for an afternoon game drive along the Mkata floodplain.', 'stay' => 'Mikumi lodge', 'meals' => 'B, L, D'],
                    ['t' => 'Departure', 'd' => 'Morning game drive, then transfer out to Dar es Salaam or Arusha.', 'stay' => '—', 'meals' => 'B'],
                ],
            ],
            [
                'id' => 'migration-safari', 'category' => 'safari', 'name' => 'Great Migration Safari',
                'nickname' => 'River-crossing season', 'location' => 'Northern Serengeti · Mara River · Ngorongoro',
                'days' => 7, 'price' => 2450, 'tag' => 'Luxury',
                'rate' => 'Timed to crossings', 'rating' => 4.9, 'reviews' => 934,
                'group' => 'Max 6', 'difficulty' => 'Easy', 'best_time' => 'Jul–Oct', 'start_point' => 'Arusha',
                'image' => 'images/trip-migration.jpg', 'accommodation' => 'Luxury permanent tented camps',
                'blurb' => 'Built around the wildebeest river crossings, with permanent tented camps positioned close to the action.',
                'overview' => "Seven days built around one thing: being in the right place when the herds cross the Mara River. Crossings are unpredictable — they can happen twice in a morning or not at all for two days — so this itinerary gives you two full days at the crossing points rather than one, and puts you in camps positioned within striking distance rather than a two-hour drive away. Your guide reads herd movement daily and adjusts where you wait.",
                'highlights' => ['Two full days held at the Mara River crossing points, not one', 'Camps positioned within striking distance of the crossings', 'Dedicated private guide and vehicle for all seven days', 'Flights between Arusha and the northern Serengeti included', 'Crater morning at Ngorongoro to close the trip'],
                'included' => ['All park and conservation fees', 'Private 4x4 with guaranteed window seat', 'Dedicated guide for the full seven days', 'Full board luxury tented camps', 'All drinks at camp including house wine', 'Flights between Arusha and the Serengeti'],
                'excluded' => ['International flights and visa fees', 'Travel and medical insurance', 'Tips for guide and camp crew', 'Hot air balloon safari (optional extra)', 'Premium spirits and champagne'],
                'stay' => [
                    'name' => 'Mara River Luxury Camp', 'image' => 'images/stay-safari.jpg', 'stars' => 5,
                    'place' => 'Northern Serengeti', 'class' => 'Luxury · All inclusive',
                    'amenities' => ['Wi-Fi', 'En-suite bathrooms', 'Bar & lounge', 'Laundry'],
                    'note' => 'Large en-suite tents a short drive from the main crossing points, so you are on the river early.',
                ],
                'gallery' => ['images/trip-migration.jpg', 'images/gal-lion.jpg', 'images/stay-safari.jpg'],
                'itinerary' => [
                    ['t' => 'Arrival, Arusha', 'd' => "Land and transfer straight into the Serengeti's northern reaches, timed to the herds.", 'stay' => 'Northern Serengeti camp', 'meals' => 'L, D'],
                    ['t' => 'Positioned north', 'd' => 'Settle into camp near the Mara River, close to where crossings are currently happening.', 'stay' => 'Mara River camp', 'meals' => 'B, L, D'],
                    ['t' => 'River crossing watch', 'd' => "A full day at the river, waiting on the herds' unpredictable crossing moments.", 'stay' => 'Mara River camp', 'meals' => 'B, L, D'],
                    ['t' => 'River crossing watch II', 'd' => "A second day at the crossing points, following the guide's read of herd movement.", 'stay' => 'Mara River camp', 'meals' => 'B, L, D'],
                    ['t' => 'Central Serengeti', 'd' => 'Move south to the central plains for big cats and wide-open game viewing.', 'stay' => 'Central Serengeti camp', 'meals' => 'B, L, D'],
                    ['t' => 'Ngorongoro Crater', 'd' => 'Descend into the crater at sunrise for the densest wildlife concentration in Africa.', 'stay' => 'Crater rim lodge', 'meals' => 'B, L, D'],
                    ['t' => 'Departure', 'd' => 'Final drive, then transfer back to Arusha or the airport.', 'stay' => '—', 'meals' => 'B'],
                ],
            ],
            [
                'id' => 'machame', 'category' => 'kilimanjaro', 'name' => 'Machame Route',
                'nickname' => 'the Whiskey Route', 'location' => 'Kilimanjaro National Park · Moshi',
                'days' => 7, 'price' => 2450, 'tag' => 'Moderate–Hard',
                'rate' => '85% summit rate', 'rating' => 4.8, 'reviews' => 1930,
                'group' => 'Max 10', 'difficulty' => 'Challenging', 'best_time' => 'Jan–Mar, Jun–Oct', 'start_point' => 'Moshi',
                'image' => 'images/hero-kilimanjaro.jpg', 'accommodation' => 'Camping throughout (crew-carried tents)',
                'blurb' => 'The most scenic approach, climbing through all five zones with a full acclimatization day at Barranco.',
                'overview' => "Machame is the most walked route on Kilimanjaro for good reason: it climbs high and sleeps low, which is exactly what acclimatisation wants. It crosses all five climate zones — rainforest, moorland, alpine desert, and the arctic summit zone — and has the best scenery on the mountain. We run it over seven days rather than six. That extra day at Barranco is the single biggest factor in whether people summit, and we won't sell the shorter version.",
                'highlights' => ['Seven days rather than six — the extra acclimatisation day at Barranco', 'The Barranco Wall scramble, the best morning on the mountain', 'All five climate zones, rainforest to arctic summit', 'Midnight summit push timed to reach Uhuru Peak at sunrise', 'Daily health checks with pulse oximetry and emergency oxygen carried'],
                'included' => ['Park, camping and rescue fees', 'Certified mountain guides and full crew', 'All meals and drinking water on the mountain', 'Four-season tents and sleeping mats', 'Emergency oxygen and pulse oximeter', 'Transfers to and from the gate'],
                'excluded' => ['International flights and visa fees', 'Hotels before and after the climb', 'Travel and high-altitude insurance', 'Tips for guides, porters and cook', 'Personal climbing gear and rentals'],
                'stay' => [
                    'name' => 'Full-service mountain camps', 'image' => 'images/stay-kili.jpg', 'stars' => 3,
                    'place' => 'Kilimanjaro National Park', 'class' => 'Camping · Crew-carried',
                    'amenities' => ['4-season tents', 'Mess tent', 'Hot meals', 'Private toilet tent'],
                    'note' => 'Crew moves ahead each day and has camp standing before you arrive. Sleeping mats provided; bring your own bag.',
                ],
                'gallery' => ['images/gal-kili-climbers.jpg', 'images/stay-kili.jpg', 'images/gal-kili-summit.jpg'],
                'itinerary' => [
                    ['t' => 'Machame Gate to Machame Camp', 'd' => 'Trek through montane rainforest to Machame Camp.', 'elev' => '1,640 → 2,835 m', 'stay' => 'Machame Camp', 'meals' => 'L, D'],
                    ['t' => 'Machame to Shira', 'd' => 'Climb out of the forest onto the moorland plateau at Shira Camp.', 'elev' => '2,835 → 3,750 m', 'stay' => 'Shira Camp', 'meals' => 'B, L, D'],
                    ['t' => 'Shira to Barranco via Lava Tower', 'd' => 'An acclimatisation day: climb to Lava Tower at 4,600m, then descend to sleep lower at Barranco.', 'elev' => '3,750 → 4,600 → 3,960 m', 'stay' => 'Barranco Camp', 'meals' => 'B, L, D'],
                    ['t' => 'Barranco Wall to Karanga', 'd' => 'Scramble the Barranco Wall, then cross the valleys to Karanga Camp.', 'elev' => '3,960 → 4,035 m', 'stay' => 'Karanga Camp', 'meals' => 'B, L, D'],
                    ['t' => 'Karanga to Barafu', 'd' => 'A short climb to Barafu, staging point for the summit push. Early dinner and bed.', 'elev' => '4,035 → 4,673 m', 'stay' => 'Barafu Camp', 'meals' => 'B, L, D'],
                    ['t' => 'Summit night — Uhuru Peak', 'd' => 'Midnight start for Uhuru Peak at sunrise, then a long descent to Mweka Camp.', 'elev' => '4,673 → 5,895 → 3,100 m', 'stay' => 'Mweka Camp', 'meals' => 'B, L, D'],
                    ['t' => 'Descent to Mweka Gate', 'd' => 'Final descent through the forest to the gate, then transfer to Moshi.', 'elev' => '3,100 → 1,640 m', 'stay' => '—', 'meals' => 'B'],
                ],
            ],
            [
                'id' => 'lemosho', 'category' => 'kilimanjaro', 'name' => 'Lemosho Route',
                'nickname' => 'the quiet start', 'location' => 'Kilimanjaro National Park · Moshi',
                'days' => 8, 'price' => 2780, 'tag' => 'Moderate',
                'rate' => '90% summit rate', 'rating' => 4.9, 'reviews' => 845,
                'group' => 'Max 10', 'difficulty' => 'Challenging', 'best_time' => 'Jan–Mar, Jun–Oct', 'start_point' => 'Moshi',
                'image' => 'images/hero-kilimanjaro.jpg', 'accommodation' => 'Camping throughout (crew-carried tents)',
                'blurb' => 'An extra day of altitude gain buys the best summit odds on the mountain, with wide views across Shira Plateau.',
                'overview' => "If summiting matters more to you than saving a day, this is the route. Eight days on Lemosho gives the most gradual altitude profile of any standard Kilimanjaro itinerary, and our summit rate on it is the highest we run. The first two days are notably quiet — Lemosho approaches from the west and doesn't merge with the busier routes until Barranco — and the Shira Plateau crossing is the best walking on the mountain.",
                'highlights' => ['The highest summit rate of any route we run, at around 90%', 'Two quiet opening days before joining the busier routes', 'The Shira Plateau crossing, the best walking on Kilimanjaro', 'Eight days means the most gradual altitude profile available', 'Daily health checks with pulse oximetry and emergency oxygen carried'],
                'included' => ['Park, camping and rescue fees', 'Certified mountain guides and full crew', 'All meals and drinking water on the mountain', 'Four-season tents and sleeping mats', 'Emergency oxygen and pulse oximeter', 'Transfers to and from the gate'],
                'excluded' => ['International flights and visa fees', 'Hotels before and after the climb', 'Travel and high-altitude insurance', 'Tips for guides, porters and cook', 'Personal climbing gear and rentals'],
                'stay' => [
                    'name' => 'Full-service mountain camps', 'image' => 'images/stay-kili.jpg', 'stars' => 3,
                    'place' => 'Kilimanjaro National Park', 'class' => 'Camping · Crew-carried',
                    'amenities' => ['4-season tents', 'Mess tent', 'Hot meals', 'Private toilet tent'],
                    'note' => 'Crew moves ahead each day and has camp standing before you arrive. Sleeping mats provided; bring your own bag.',
                ],
                'gallery' => ['images/stay-kili.jpg', 'images/gal-kili-climbers.jpg', 'images/hero-kilimanjaro.jpg'],
                'itinerary' => [
                    ['t' => 'Lemosho Gate to Mti Mkubwa', 'd' => 'Trek through quiet rainforest to Mti Mkubwa (Big Tree) Camp.', 'elev' => '2,100 → 2,835 m', 'stay' => 'Mti Mkubwa Camp', 'meals' => 'L, D'],
                    ['t' => 'To Shira 1 Camp', 'd' => 'Climb out of the forest and cross onto the Shira Plateau.', 'elev' => '2,835 → 3,500 m', 'stay' => 'Shira 1 Camp', 'meals' => 'B, L, D'],
                    ['t' => 'Across Shira Ridge', 'd' => 'A gentle acclimatisation day walking east across the plateau.', 'elev' => '3,500 → 3,850 m', 'stay' => 'Shira 2 Camp', 'meals' => 'B, L, D'],
                    ['t' => 'To Barranco via Lava Tower', 'd' => 'Climb to Lava Tower at 4,600m, then descend to sleep lower at Barranco.', 'elev' => '3,850 → 4,600 → 3,960 m', 'stay' => 'Barranco Camp', 'meals' => 'B, L, D'],
                    ['t' => 'Barranco Wall to Karanga', 'd' => 'Scramble the Barranco Wall, then cross the valleys to Karanga Camp.', 'elev' => '3,960 → 4,035 m', 'stay' => 'Karanga Camp', 'meals' => 'B, L, D'],
                    ['t' => 'Karanga to Barafu', 'd' => 'A short climb to Barafu, staging point for the summit. Early dinner and bed.', 'elev' => '4,035 → 4,673 m', 'stay' => 'Barafu Camp', 'meals' => 'B, L, D'],
                    ['t' => 'Summit night — Uhuru Peak', 'd' => 'Midnight start for Uhuru Peak at sunrise, then a long descent to Mweka Camp.', 'elev' => '4,673 → 5,895 → 3,100 m', 'stay' => 'Mweka Camp', 'meals' => 'B, L, D'],
                    ['t' => 'Descent to Mweka Gate', 'd' => 'Final descent through the forest to the gate, then transfer to Moshi.', 'elev' => '3,100 → 1,640 m', 'stay' => '—', 'meals' => 'B'],
                ],
            ],
            [
                'id' => 'marangu', 'category' => 'kilimanjaro', 'name' => 'Marangu Route',
                'nickname' => 'the Coca-Cola Route', 'location' => 'Kilimanjaro National Park · Moshi',
                'days' => 6, 'price' => 2100, 'tag' => 'Moderate',
                'rate' => '65% summit rate', 'rating' => 4.6, 'reviews' => 530,
                'group' => 'Max 10', 'difficulty' => 'Moderate', 'best_time' => 'Jan–Mar, Jun–Oct', 'start_point' => 'Moshi',
                'image' => 'images/hero-kilimanjaro.jpg', 'accommodation' => 'Mountain huts (shared, dormitory-style)',
                'blurb' => 'The only route with hut accommodation instead of tents, and the gentlest opening days.',
                'overview' => "Marangu is the only route on Kilimanjaro where you sleep in huts rather than tents, which makes it the pick for anyone travelling in the wet season or who simply doesn't want to camp. The opening days are the gentlest on the mountain. Be honest with yourself about the trade-off though: at six days it has the least acclimatisation time of any route we run, and the summit rate reflects that. We include the extra day at Horombo as standard — skip it and the odds drop further.",
                'highlights' => ['The only Kilimanjaro route with hut accommodation, not tents', 'The extra acclimatisation day at Horombo included as standard', 'Gentlest opening gradient of any route on the mountain', 'Best option in the wet season, when camping is miserable', 'Daily health checks with pulse oximetry and emergency oxygen carried'],
                'included' => ['Park, hut and rescue fees', 'Certified mountain guides and full crew', 'All meals and drinking water on the mountain', 'Dormitory hut bunks and mattresses', 'Emergency oxygen and pulse oximeter', 'Transfers to and from the gate'],
                'excluded' => ['International flights and visa fees', 'Hotels before and after the climb', 'Travel and high-altitude insurance', 'Tips for guides, porters and cook', 'Personal climbing gear and rentals'],
                'stay' => [
                    'name' => 'Mandara, Horombo & Kibo huts', 'image' => 'images/stay-kili.jpg', 'stars' => 3,
                    'place' => 'Kilimanjaro National Park', 'class' => 'Mountain huts · Dormitory',
                    'amenities' => ['Bunk beds', 'Dining hall', 'Piped water', 'Solar light'],
                    'note' => 'Shared dormitory huts with mattresses provided. Bring your own sleeping bag; bathrooms are shared.',
                ],
                'gallery' => ['images/gal-kili-summit.jpg', 'images/hero-kilimanjaro.jpg', 'images/gal-kili-climbers.jpg'],
                'itinerary' => [
                    ['t' => 'Marangu Gate to Mandara Hut', 'd' => 'Trek through rainforest to Mandara Hut, with an optional walk to Maundi Crater.', 'elev' => '1,860 → 2,700 m', 'stay' => 'Mandara Hut', 'meals' => 'L, D'],
                    ['t' => 'To Horombo Hut', 'd' => 'Climb out of the forest into open moorland with first clear views of Kibo.', 'elev' => '2,700 → 3,720 m', 'stay' => 'Horombo Hut', 'meals' => 'B, L, D'],
                    ['t' => 'Acclimatisation day at Horombo', 'd' => 'A walk up towards Zebra Rocks and back, sleeping again at Horombo.', 'elev' => '3,720 → 4,000 → 3,720 m', 'stay' => 'Horombo Hut', 'meals' => 'B, L, D'],
                    ['t' => 'Across the saddle to Kibo', 'd' => 'Cross the alpine desert saddle to Kibo Hut. Early dinner and bed.', 'elev' => '3,720 → 4,703 m', 'stay' => 'Kibo Hut', 'meals' => 'B, L, D'],
                    ['t' => 'Summit night — Uhuru Peak', 'd' => 'Midnight start via Gilman\'s Point to Uhuru Peak, then descend all the way to Horombo.', 'elev' => '4,703 → 5,895 → 3,720 m', 'stay' => 'Horombo Hut', 'meals' => 'B, L, D'],
                    ['t' => 'Descent to Marangu Gate', 'd' => 'Final descent through the forest to the gate, then transfer to Moshi.', 'elev' => '3,720 → 1,860 m', 'stay' => '—', 'meals' => 'B'],
                ],
            ],
            [
                'id' => 'zanzibar-beach', 'category' => 'zanzibar', 'name' => 'Zanzibar Beach Escape',
                'nickname' => 'Stone Town & shores', 'location' => 'Stone Town · Nungwi · Zanzibar',
                'days' => 4, 'price' => 650, 'tag' => 'Relaxed',
                'rate' => 'Free cancellation 48h', 'rating' => 4.9, 'reviews' => 1520,
                'group' => 'Max 8', 'difficulty' => 'Easy', 'best_time' => 'Jun–Oct, Dec–Feb', 'start_point' => 'Zanzibar',
                'image' => 'images/hero-zanzibar.jpg', 'accommodation' => 'Beachfront hotel & Stone Town guesthouse',
                'blurb' => "Two days exploring Stone Town's history and spice markets, then two full days on Nungwi's beaches.",
                'overview' => "The standard way to end a safari or a climb, and the reason most people extend their trip by a few days. Two nights in Stone Town to walk the old quarter, the spice market and the waterfront, then north to Nungwi where the water is the colour it looks in the photographs. Nothing is over-scheduled: one guided morning, and the rest is yours. Transfers and hotels are handled, so all you decide is what time to get up.",
                'highlights' => ["Guided walk through Stone Town's old quarter and spice market", 'Two full unscheduled days on Nungwi beach', 'Beachfront hotel and Stone Town guesthouse both included', 'Private transfers, including airport pickup and drop-off', 'Pairs directly onto the end of any safari or Kilimanjaro trip'],
                'included' => ['All accommodation on a bed and breakfast basis', 'Guided Stone Town walking tour', 'Private transfers including airport pickup', 'Ferry or flight transfer to Zanzibar', 'Bottled water on transfers'],
                'excluded' => ['International flights and visa fees', 'Lunches and dinners', 'Travel and medical insurance', 'Tips and personal spending', 'Optional water sports and excursions'],
                'stay' => [
                    'name' => 'Nungwi beachfront hotel', 'image' => 'images/stay-beach.jpg', 'stars' => 4,
                    'place' => 'Nungwi, north Zanzibar', 'class' => 'Mid-range · Bed & breakfast',
                    'amenities' => ['Wi-Fi', 'Pool', 'Beachfront', 'Air conditioning'],
                    'note' => 'Rooms open onto the sand at the north end of Nungwi, where the tide stays swimmable all day.',
                ],
                'gallery' => ['images/hero-zanzibar.jpg', 'images/gal-stonetown.jpg', 'images/stay-beach.jpg'],
                'itinerary' => [
                    ['t' => 'Arrival, Stone Town', 'd' => 'Transfer in and settle into Stone Town for the evening.', 'stay' => 'Stone Town guesthouse', 'meals' => '—'],
                    ['t' => 'Stone Town tour', 'd' => "Guided walk through Stone Town's old quarter, markets and waterfront.", 'stay' => 'Stone Town guesthouse', 'meals' => 'B'],
                    ['t' => 'North to Nungwi', 'd' => 'Transfer north to Nungwi for a full free day on the beach.', 'stay' => 'Nungwi beachfront hotel', 'meals' => 'B'],
                    ['t' => 'Departure', 'd' => 'Final morning at the beach before your transfer out.', 'stay' => '—', 'meals' => 'B'],
                ],
            ],
            [
                'id' => 'zanzibar-dhow', 'category' => 'zanzibar', 'name' => 'Spice & Dhow Adventure',
                'nickname' => 'Culture + snorkeling', 'location' => 'Stone Town · Spice farms · Mnemba Atoll',
                'days' => 3, 'price' => 520, 'tag' => 'Active',
                'rate' => 'Small group, max 8', 'rating' => 4.8, 'reviews' => 398,
                'group' => 'Max 8', 'difficulty' => 'Easy–Active', 'best_time' => 'Jun–Oct, Dec–Feb', 'start_point' => 'Stone Town',
                'image' => 'images/trip-dhow.jpg', 'accommodation' => 'Small guesthouse, Stone Town',
                'blurb' => 'A spice farm morning, an afternoon dhow cruise, then a full day snorkeling the reefs off Mnemba.',
                'overview' => "Three days for people who'd rather do something than lie still. A working spice farm outside Stone Town in the morning — cardamom, clove, nutmeg, tasted off the tree — a traditional dhow out along the coast at sunset, and a full day snorkeling the reef off Mnemba Atoll, which is as good as Indian Ocean snorkeling gets. Groups are capped at eight so the boat never feels crowded.",
                'highlights' => ['Working spice farm tour with tasting straight off the tree', 'Sunset cruise on a traditional wooden dhow', 'Full day snorkeling the reef off Mnemba Atoll', 'Groups capped at eight so the boat never feels crowded', 'Snorkel gear and marine park fees included'],
                'included' => ['Two nights guesthouse accommodation', 'Guided spice farm tour and tasting', 'Sunset dhow cruise with soft drinks', 'Full day Mnemba snorkeling trip with lunch', 'Snorkel equipment and marine park fees'],
                'excluded' => ['International flights and visa fees', 'Dinners in Stone Town', 'Travel and medical insurance', 'Tips for guides and boat crew', 'Scuba diving upgrades'],
                'stay' => [
                    'name' => 'Stone Town guesthouse', 'image' => 'images/stay-beach.jpg', 'stars' => 3,
                    'place' => 'Stone Town', 'class' => 'Budget · Bed & breakfast',
                    'amenities' => ['Wi-Fi', 'Rooftop terrace', 'Air conditioning', 'Breakfast'],
                    'note' => 'A small, family-run house in the old quarter, a few minutes walk from the night market.',
                ],
                'gallery' => ['images/trip-dhow.jpg', 'images/gal-stonetown.jpg', 'images/hero-zanzibar.jpg'],
                'itinerary' => [
                    ['t' => 'Spice farm morning', 'd' => 'Morning spice farm tour and tasting outside Stone Town.', 'stay' => 'Stone Town guesthouse', 'meals' => 'B, L'],
                    ['t' => 'Sunset dhow cruise', 'd' => 'Traditional dhow out along the coast for sunset.', 'stay' => 'Stone Town guesthouse', 'meals' => 'B'],
                    ['t' => 'Snorkeling, Mnemba Atoll', 'd' => 'Full day snorkeling the reefs off Mnemba Atoll, with lunch on the boat.', 'stay' => '—', 'meals' => 'B, L'],
                ],
            ],
            [
                'id' => 'zanzibar-honeymoon', 'category' => 'zanzibar', 'name' => 'Zanzibar Honeymoon',
                'nickname' => 'private & secluded', 'location' => 'South-east coast · Zanzibar',
                'days' => 5, 'price' => 1350, 'tag' => 'Luxury',
                'rate' => 'Private villa option', 'rating' => 5.0, 'reviews' => 210,
                'group' => 'Private', 'difficulty' => 'Easy', 'best_time' => 'Jun–Oct, Dec–Feb', 'start_point' => 'Zanzibar',
                'image' => 'images/trip-romantic.jpg', 'accommodation' => 'Private beachfront villa',
                'blurb' => 'A quieter stretch of coast, private beachfront villa, and a private sunset dinner arranged on request.',
                'overview' => "Five days on the quieter south-east coast, away from the busier northern beaches. A private beachfront villa with your own stretch of sand, a sunset dinner set up on the beach, and an optional couples spa afternoon. Deliberately unstructured — there is exactly one thing scheduled on day three and the rest of the week is left empty on purpose. Private transfers throughout so you never share a minibus.",
                'highlights' => ['Private beachfront villa with your own stretch of sand', 'A private dinner set up on the beach at sunset', 'The quieter south-east coast, away from the northern crowds', 'Deliberately unscheduled — one fixed plan in five days', 'Private transfers throughout, never a shared minibus'],
                'included' => ['Four nights private beachfront villa', 'Daily breakfast served at the villa', 'Private sunset dinner on the beach', 'Private airport and island transfers', 'Welcome bottle on arrival'],
                'excluded' => ['International flights and visa fees', 'Lunches and most dinners', 'Travel and medical insurance', 'Spa treatments unless booked', 'Tips and personal spending'],
                'stay' => [
                    'name' => 'Private beachfront villa', 'image' => 'images/stay-beach.jpg', 'stars' => 5,
                    'place' => 'South-east coast', 'class' => 'Luxury · Private villa',
                    'amenities' => ['Private pool', 'Chef on request', 'Wi-Fi', 'Air conditioning'],
                    'note' => 'A standalone villa with its own gate onto the beach, staffed but private — nobody else books it while you are there.',
                ],
                'gallery' => ['images/trip-romantic.jpg', 'images/stay-beach.jpg', 'images/hero-zanzibar.jpg'],
                'itinerary' => [
                    ['t' => 'Arrival', 'd' => 'Private transfer to your beachfront villa.', 'stay' => 'Private beachfront villa', 'meals' => '—'],
                    ['t' => 'Free day, beach', 'd' => 'A full day with nothing planned, on your own stretch of sand.', 'stay' => 'Private beachfront villa', 'meals' => 'B'],
                    ['t' => 'Sunset dinner', 'd' => 'A private dinner arranged on the beach at sunset.', 'stay' => 'Private beachfront villa', 'meals' => 'B, D'],
                    ['t' => 'Optional spa day', 'd' => 'Couples spa treatment, or another day doing nothing at all.', 'stay' => 'Private beachfront villa', 'meals' => 'B'],
                    ['t' => 'Departure', 'd' => 'Private transfer back for your flight out.', 'stay' => '—', 'meals' => 'B'],
                ],
            ],
        ];
    }

    public static function find(string $id): ?array
    {
        foreach (self::all() as $trip) {
            if ($trip['id'] === $id) {
                return $trip;
            }
        }

        return null;
    }
}
