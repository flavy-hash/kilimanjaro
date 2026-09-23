<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Editable copy for the public homepage. A singleton: exactly one row,
 * fetched through current(). Falls back to the site's original hardcoded
 * copy via defaults() so nothing on the page changes until an admin edits it.
 */
class HomeContent extends Model
{
    protected $table = 'home_content';

    protected $guarded = ['id'];

    protected $casts = [
        'hero_stats' => 'array',
        'intro_paragraphs' => 'array',
        'activities' => 'array',
        'faqs' => 'array',
    ];

    public static function current(): self
    {
        return static::query()->first() ?? static::create(static::defaults());
    }

    /** @return array<string, mixed> */
    public static function defaults(): array
    {
        return [
            'hero_stats' => [
                ['num' => '3', 'suffix' => '', 'label' => 'Ways to explore'],
                ['num' => '180', 'suffix' => '+', 'label' => 'Trips run'],
                ['num' => '4.9', 'suffix' => '★', 'label' => 'Average rating'],
                ['num' => '<1', 'suffix' => 'hr', 'label' => 'Reply time'],
            ],

            'intro_tag' => 'Who we are',
            'intro_title' => 'One Arusha team, <em>every step of the way</em>.',
            'intro_paragraphs' => [
                "Safari is the Swahili word for journey — and in Tanzania that journey happens on a lot more than the back of a Land Cruiser. It's a dawn game drive across the Serengeti plains, a week climbing through five climate zones on Kilimanjaro, or a slow afternoon on a dhow off Zanzibar. We run all three, in-house, from one office in Arusha.",
                "We're not a booking platform reselling someone else's itinerary. Perfect Kilimanjaro is a small team of Tanzanian guides and planners who build every trip, drive every vehicle, and answer every message ourselves — so the price you're quoted is the trip you actually get.",
                'On safari, we run the <strong>Northern Circuit</strong> (Tarangire, Serengeti and Ngorongoro), the quieter <strong>Southern Circuit</strong> (Ruaha, Nyerere and Mikumi), and migration-timed trips built around the Mara River crossings. On the mountain, we guide the <strong>Machame</strong>, <strong>Lemosho</strong> and <strong>Marangu</strong> routes. On the coast, we run beach, spice-and-dhow and honeymoon trips across <strong>Zanzibar</strong>.',
                'Safaris come in Classic, Mid-range and Luxury tiers, Kilimanjaro routes are picked by fitness and time rather than price, and our Zanzibar trips range from relaxed beach time to a more active spice-and-dhow week — so the itinerary fits you, not the other way around.',
            ],

            'pillars_tag' => 'What we run',
            'pillars_title' => 'Three trips. <em>One local team.</em>',
            'pillars_lede' => 'No third party brokers every itinerary below is planned and led by our own Tanzanian guides and crews.',

            'routes_tag' => 'Kilimanjaro routes',
            'routes_title' => 'Pick your <em>path up the mountain</em>',
            'routes_lede' => "Six ways up Africa's highest peak — trace each one's camps, gates and the way back down. We guide Machame, Lemosho and Marangu ourselves; the rest are here for reference.",

            'equip_title' => 'Kilimanjaro equipment list',
            'equip_description' => 'Everything you need to pack for the mountain — layer by layer, from rainforest trailhead to summit night at −15°C.',
            'equip_pdf' => 'documents/kilimanjaro-equipment-checklist.pdf',

            'trips_tag' => 'Trips & pricing',
            'trips_title' => 'Pick your <em>adventure</em>',
            'trips_lede' => 'All prices are per person, land cost only — park fees, guides, camps and meals on the trip included.',

            'activities_tag' => 'On the mountain',
            'activities_title' => 'Four moments that define <em>the climb</em>',
            'activities_lede' => 'The things climbers remember most, from the gate to Uhuru Peak.',
            'activities' => [
                [
                    'image' => 'images/hero-kilimanjaro.jpg',
                    'tag' => 'Kilimanjaro',
                    'eyebrow' => 'Day one to five',
                    'title' => 'Five climate zones',
                    'text' => "From rainforest at the gate through moorland and alpine desert to the arctic summit zone — each day on the mountain looks like a different country.",
                ],
                [
                    'image' => 'images/gal-kili-climbers.jpg',
                    'tag' => 'Barranco Wall',
                    'eyebrow' => 'The scramble',
                    'title' => 'Hands-on-rock terrain',
                    'text' => 'A short, exposed scramble up the Barranco Wall — the most talked-about hour on the mountain, and one every guide has climbed hundreds of times.',
                ],
                [
                    'image' => 'images/stay-kili.jpg',
                    'tag' => 'Camp life',
                    'eyebrow' => 'Every evening',
                    'title' => 'Camp life on the mountain',
                    'text' => "Hot meals cooked by your own crew, tents pitched before you arrive, and card games under more stars than you've ever seen.",
                ],
                [
                    'image' => 'images/gal-kili-summit.jpg',
                    'tag' => 'Uhuru Peak',
                    'eyebrow' => 'Midnight ascent',
                    'title' => 'The summit push',
                    'text' => 'A midnight climb through the dark to reach Uhuru Peak for sunrise — the single moment every route on the mountain is built around.',
                ],
            ],

            'video_tag' => 'Watch first',
            'video_title' => 'Take a glimpse into <em>the mountain</em>',
            'video_youtube_id' => null,

            'about_tag' => 'About us',
            'about_title' => 'Run by the people <em>on the ground</em>',
            'about_lede' => "Safiri is a small Arusha-based team, not a booking platform reselling someone else's itinerary.",
            'about_story_title' => 'Our story',
            'about_story_text' => 'Started by guides who spent years leading trips for foreign-owned operators and wanted travelers to see more of what they were actually paying for. We plan every itinerary in-house and work only with crews we know personally.',
            'about_team_title' => 'Our team',
            'about_team_text' => 'A dozen safari guides, six Kilimanjaro crew leads, and a small Zanzibar-based team — all Tanzanian, all full-time, and all paid above the standard park-wage rates.',
            'faqs' => [
                [
                    'question' => 'Do I need a visa to visit Tanzania?',
                    'answer' => 'Most nationalities can get an e-visa online before arrival, or a visa on arrival at Kilimanjaro International Airport. We send exact instructions once your trip is booked.',
                ],
                [
                    'question' => "When's the best time to see the migration?",
                    'answer' => 'River crossings typically run July–October in the northern Serengeti; calving season is January–March in the south. We track current herd positions and adjust your route accordingly.',
                ],
                [
                    'question' => 'Are these trips suitable for families?',
                    'answer' => "Yes — safaris and Zanzibar trips work well for all ages. Kilimanjaro climbs are better suited to kids 12+ who've done some hiking before.",
                ],
            ],

            'cta_title' => 'Not sure which trip <em>fits you?</em>',
            'cta_text' => "Tell us your dates, budget and what you want to see — we'll match you to the right itinerary and send a day-by-day plan.",
        ];
    }
}
