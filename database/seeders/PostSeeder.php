<?php

namespace Database\Seeders;

use App\Models\Post;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class PostSeeder extends Seeder
{
    public function run(): void
    {
        foreach ($this->rows() as $i => $row) {
            Post::updateOrCreate(['slug' => $row['slug']], $row['data'] + [
                'is_published' => true,
                'published_at' => Carbon::now()->subDays(($i + 1) * 6),
            ]);
        }
    }

    /** @return array<int, array{slug:string, data:array}> */
    protected function rows(): array
    {
        return [
            [
                'slug' => 'five-climate-zones-of-kilimanjaro',
                'data' => [
                    'title' => 'The Five Climate Zones of Kilimanjaro, Explained',
                    'excerpt' => "Climbing Kilimanjaro means walking through five distinct climates in under a week — from rainforest to arctic summit. Here's what each one is actually like.",
                    'cover_image' => 'images/hero-kilimanjaro.jpg',
                    'author_name' => 'Perfect Kilimanjaro',
                    'body' => <<<'HTML'
<p>Most mountains have one climate. Kilimanjaro has five, stacked on top of each other like layers of a very tall cake — and on a standard six to nine day climb, you walk through every one of them.</p>
<h2>1. Cultivation zone (800–1,800m)</h2>
<p>You won't see much of this one as a climber — it's the farmland and villages around the base of the mountain, coffee and banana plantations mostly. Most routes start just above it, at the park gate.</p>
<h2>2. Rainforest (1,800–2,800m)</h2>
<p>The first day of walking on every route. Warm, humid, and genuinely a rainforest — thick canopy, colobus monkeys if you're lucky, and often a light drizzle. This is where your gear first gets tested against real weather.</p>
<h2>3. Heather and moorland (2,800–4,000m)</h2>
<p>The forest thins out fast and gives way to giant heather and the strange, otherworldly groundsel plants Kilimanjaro is known for. Temperatures drop noticeably, and the sun at altitude is far stronger than it feels.</p>
<h2>4. Alpine desert (4,000–5,000m)</h2>
<p>This is where most of your summit-approach camps sit. Rocky, dry, and sparse — little grows here, and nights regularly drop below freezing even though you're near the equator.</p>
<h2>5. Arctic summit zone (5,000m+)</h2>
<p>Ice, rock and snow. Temperatures on summit night commonly reach -15°C to -20°C with windchill. It's the shortest zone by distance but the one every itinerary is built around.</p>
<p>Knowing this ahead of time changes how you pack — you're not dressing for one climate, you're dressing for five, in the space of about five days.</p>
HTML,
                ],
            ],
            [
                'slug' => 'how-to-choose-a-kilimanjaro-route',
                'data' => [
                    'title' => 'How to Choose a Kilimanjaro Route',
                    'excerpt' => 'Six official routes, wildly different summit rates and crowds. Here\'s how to actually pick between Machame, Lemosho, Marangu and the rest.',
                    'cover_image' => 'images/gal-kili-climbers.jpg',
                    'author_name' => 'Perfect Kilimanjaro',
                    'body' => <<<'HTML'
<p>Every route ends at the same summit sign, but the six days getting there can look completely different depending on which one you pick. Here's the short version.</p>
<h2>If you want the highest summit odds</h2>
<p>Choose a longer route — <strong>Lemosho</strong> (7-8 days) or the <strong>Northern Circuit</strong> (8-9 days). More days means more time to acclimatize, and it shows directly in the numbers: these routes consistently post higher summit success rates than the shorter options.</p>
<h2>If this is your first big trek</h2>
<p><strong>Marangu</strong> is the gentlest profile and the only route with hut accommodation instead of tents — appealing if camping isn't your thing. Its trade-off is a faster ascent profile, which is part of why its summit rate trails the longer routes.</p>
<h2>If you want fewer other climbers</h2>
<p><strong>Rongai</strong> approaches from the quiet northern side near the Kenyan border and sees a fraction of the traffic the southern routes do. You'll likely have camps close to yourself.</p>
<h2>If you're an experienced, fit hiker short on time</h2>
<p><strong>Umbwe</strong> is the steepest and most direct line on the mountain — six days, no easing in. It has the lowest summit rate of the standard routes precisely because it moves fast, so it suits climbers who are confident about their altitude tolerance.</p>
<h2>The one common thread</h2>
<p>Whichever route you pick, the guide's decisions on pace and rest days matter more than the route name on a brochure. A good crew can make a shorter route feel manageable, and a bad one can make a long route miserable — that's the part worth asking about before you book.</p>
HTML,
                ],
            ],
            [
                'slug' => 'when-to-see-the-great-migration',
                'data' => [
                    'title' => 'When to Visit for the Great Migration',
                    'excerpt' => 'The migration moves year-round, not just in July. Here\'s where the herds actually are, month by month.',
                    'cover_image' => 'images/gal-lion.jpg',
                    'author_name' => 'Perfect Kilimanjaro',
                    'body' => <<<'HTML'
<p>"When's the best time for the migration?" is the question we get asked more than any other, and the honest answer is: it depends what you want to see, because the migration is a year-round, circular event — not a single month.</p>
<h2>January–March: Calving season, southern Serengeti</h2>
<p>Around 500,000 calves are born in a few short weeks on the short-grass plains near Ndutu. It's also when predator action is at its most concentrated, since a lot of vulnerable young animals attract a lot of attention.</p>
<h2>April–May: The long rains</h2>
<p>The quietest, greenest months. Herds are dispersed across the southern and central Serengeti. Fewer vehicles, lower rates, and still excellent general game viewing — just don't come expecting river crossings.</p>
<h2>June–July: The move north</h2>
<p>The herds start their push north and west toward the Grumeti River, with the first river crossings of the year possible from June onward.</p>
<h2>August–October: Mara River crossings</h2>
<p>The headline event — herds crossing the Mara River between the northern Serengeti and Kenya's Maasai Mara, crocodiles included. This is the busiest and most photographed period, and vehicle numbers at popular crossing points reflect that.</p>
<h2>November–December: The short rains, heading south</h2>
<p>The herds turn back south toward the calving grounds, often via the eastern Serengeti. A good shoulder-season window — decent weather, thinner crowds, and the cycle beginning again.</p>
<p>The takeaway: there's no single "wrong" time to see the Serengeti, but if a river crossing specifically is the goal, plan for July through October and build in a few flexible days, since exact timing shifts year to year with the rains.</p>
HTML,
                ],
            ],
        ];
    }
}
