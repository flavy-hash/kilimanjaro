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
        'why_items' => 'array',
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
            'hero_video' => null,
            'hero_poster' => 'images/hero-kilimanjaro.jpg',
            'hero_region' => 'Kilimanjaro Region',
            'hero_headline' => 'Mount Kilimanjaro',
            'hero_subtitle' => "Africa's rooftop, five climate zones up",

            'hero_stats' => [
                ['num' => '3', 'suffix' => '', 'label' => 'Ways to explore'],
                ['num' => '180', 'suffix' => '+', 'label' => 'Trips run'],
                ['num' => '4.9', 'suffix' => '★', 'label' => 'Average rating'],
                ['num' => '<1', 'suffix' => 'hr', 'label' => 'Reply time'],
            ],

            'intro_tag' => 'Who we are',
            'intro_title' => 'Ready to conquer <em>Mount Kilimanjaro?</em>',
            'intro_image' => 'images/gal-kili-climbers.jpg',
            'intro_cta_label' => 'Plan Your Adventure Today',
            'intro_paragraphs' => [
                'Three generations. One family. Over 1,000 successful summits led to the top of Africa\'s highest peak.',
                'Since the 1970s, the Ngatambaku Mtui family has guided climbers up Kilimanjaro — starting with our grandfather, Dominic Mtui, continued by our father, Peter Mtui, and now led by Priscus, who has personally guided over 350 successful summits. Guiding isn\'t our business. It\'s our heritage.',
            ],

            'why_tag' => 'Why choose us',
            'why_title' => 'Why <em>Perfect Kilimanjaro</em>',
            'why_lede' => "What actually sets a family-run, in-house operator apart from a booking platform reselling someone else's itinerary.",
            'why_items' => [
                [
                    'title' => 'Three Generations of Kilimanjaro Guides',
                    'text' => "Local guides have been at the heart of Kilimanjaro since Hans Meyer's first ascent in 1889, and our family has guided here since the 1970s. Priscus, our owner and guide, first climbed at 14, worked as a porter for seven years, and has now summited more than 350 times.",
                    'image' => 'images/team/01M33YK3T3WAP2HQ22R6XW5M2R.webp',
                ],
                [
                    'title' => 'Safety First, Every Day',
                    'text' => 'Every guide is Wilderness First Responder (WFR) certified. We check your health every morning and evening with the Lake Louise Score and oxygen saturation readings. We carry emergency oxygen on every climb, and helicopter rescue is available with your travel insurance.',
                    'image' => 'images/gal-kili-summit.jpg',
                ],
                [
                    'title' => '100% Locally Owned',
                    'text' => 'There is no income outflow. Everything is run in Tanzania, with Tanzanian guides, so your money supports local jobs, families, and communities.',
                    'image' => 'images/gal-kili-climbers.jpg',
                ],
                [
                    'title' => 'Pioneers of Biking Adventures in Tanzania',
                    'text' => 'We were the first to introduce Kilimanjaro biking expeditions in the early 2000s, from scenic rides around the mountain to cycling all the way to the summit.',
                    'image' => 'images/hero-kilimanjaro.jpg',
                ],
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
            'about_lede' => "Perfect Kilimanjaro is a small Arusha-based team, not a booking platform reselling someone else's itinerary.",
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

            'tripadvisor_url' => null,
            'google_reviews_url' => null,
        ];
    }
}
