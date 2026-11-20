<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Editable copy for the dedicated /about and /team pages. A singleton like
 * HomeContent: one row, fetched through current(), with defaults() as the
 * fallback so the pages render sensibly before an admin has touched them.
 */
class AboutContent extends Model
{
    protected $table = 'about_content';

    protected $guarded = ['id'];

    protected $casts = [
        'story_paragraphs' => 'array',
        'statements' => 'array',
        'values' => 'array',
        'team_members' => 'array',
        'generations' => 'array',
        'do_items' => 'array',
        'safety_points' => 'array',
    ];

    public static function current(): self
    {
        return static::query()->first() ?? static::create(static::defaults());
    }

    /** @return array<string, mixed> */
    public static function defaults(): array
    {
        return [
            'about_hero_tag' => 'About us',
            'about_hero_title' => 'A small team, <em>run on the ground</em>',
            'about_hero_lede' => "Perfect Kilimanjaro is a small Arusha-based team running every safari, climb and Zanzibar trip in-house — not a booking platform reselling someone else's itinerary.",
            'about_hero_image' => 'images/hero-kilimanjaro.jpg',

            'story_title' => 'Our heritage',
            'story_heading' => 'Born on the Slopes of <em>Kilimanjaro</em>',
            'story_paragraphs' => [
                'Perfect Kilimanjaro is more than a tour company. It is the story of the Ngatambaku Mtui family, a Chagga family from Marangu Village, on the slopes of Kilimanjaro, whose lives have been tied to this mountain for three generations. Guiding is not just our profession. It is our inheritance.',
                'We come from Marangu, where the oldest route on the mountain begins and where the first recorded ascent in 1889 found its guide. Hans Meyer reached the summit with the help of Yohani Kinyala Lauwo, a young Chagga man from Marangu. Our people have lived at the foot of this mountain for centuries. We farmed its slopes, prayed at its waterfalls, and guided those who came to climb it. Kilimanjaro was never a mystery to us. It was home.',
            ],

            'statements' => [
                [
                    'title' => 'Our mission',
                    'text' => 'To run every Tanzania trip — safari, Kilimanjaro or Zanzibar — ourselves, at a fair price, with local guides and crew who are paid properly and treated as the experts they are.',
                ],
                [
                    'title' => 'Our vision',
                    'text' => "To be the operator Tanzanian guides recommend to their own families — proof that a locally-run team can compete with foreign-owned agencies without cutting corners on pay or safety.",
                ],
            ],

            'generations_tag' => 'Three generations',
            'generations_title' => 'Three Generations, <em>One Mountain</em>',
            'generations' => [
                [
                    'name' => 'Our grandfather',
                    'text' => 'Began guiding in the 1970s, when there were no tour operators, no websites, and no bookings. When visitors arrived at the park headquarters and asked who could take them up the mountain, the word went out to the villages, and men from Marangu who had known these trails all their lives stepped forward. He had no certificate and no agency, only his reputation and his knowledge of the mountain.',
                ],
                [
                    'name' => 'Our father, Peter Dominic Mtui',
                    'text' => 'Became a certified guide in 1983 and spent more than 30 years on Kilimanjaro. He learned the way great guides learn, by carrying loads, watching, and listening. His first lead climb came in a week of unusually heavy snow, when many guides turned back. He did not. Over the years, clients from Switzerland, Germany, and beyond kept returning and asking for him by name, writing letters to a parish in Marangu, which he walked to collect. That was the beginning of structured tourism on Kilimanjaro.',
                ],
                [
                    'name' => 'Priscus Peter Mtui, our owner and lead guide',
                    'text' => 'First summited at 14. At 17 he began as a porter, carrying luggage on more than 100 trips over seven years, before becoming a certified lead guide in 2012. He has now summited Kilimanjaro more than 350 times, every one of them leading a group. He has also cycled across Tanzania, pioneered the first Kilimanjaro biking expeditions in the early 2000s, and even led cyclists to the summit. His brother Augustine also guides on the mountain.',
                ],
            ],
            'generations_closing' => 'Together, our family has guided more than 1,000 groups to the summit.',

            'do_tag' => 'What we do',
            'do_title' => 'Every way to <em>see Tanzania</em>',
            'do_items' => [
                [
                    'title' => 'Kilimanjaro climbs',
                    'text' => 'All major routes, including the Daytime Summit option, with experienced local guides.',
                ],
                [
                    'title' => 'Safari adventures',
                    'text' => 'Midrange and luxury safaris, migration tours, and honeymoons.',
                ],
                [
                    'title' => 'Cycling expeditions',
                    'text' => 'From scenic tours around the mountain to summit challenges.',
                ],
                [
                    'title' => 'Beach and holiday extensions',
                    'text' => 'To Zanzibar and beyond — every journey tailored to your needs and your budget.',
                ],
            ],

            'safety_tag' => 'Safety',
            'safety_title' => 'How We Keep <em>You Safe</em>',
            'safety_lede' => 'Our motto has never changed: to take every client to the top of Africa, safely.',
            'safety_points' => [
                'Every guide is Wilderness First Responder (WFR) certified.',
                'Health checks begin on Day Zero, at the hotel in Moshi, and continue every morning and evening on the mountain.',
                'We use the Lake Louise Score and measure oxygen saturation and heart rate at every camp.',
                'We carry emergency oxygen and first-aid kits on every climb. Oxygen is for emergencies and the descent, not for going up.',
                'If your oxygen saturation falls below 75% and you show other altitude symptoms such as headache, nausea, dizziness, or extreme fatigue, we will bring you down to a lower elevation. No summit certificate is worth your life.',
                'When we say summit, we mean Uhuru Peak.',
            ],

            'values_tag' => 'What we believe',
            'values_heading' => 'What <em>We Believe</em>',
            'values' => [
                [
                    'title' => 'Your porters are people, not equipment',
                    'text' => 'Every porter and crew member is paid fair wages, eats well, and sleeps under proper shelter. They are the backbone of every successful climb.',
                ],
                [
                    'title' => 'Every climber deserves respect',
                    'text' => 'East African climbers receive the same guide, the same food, and the same care as every international guest, at the fees they actually qualify for. This mountain belongs to all of us.',
                ],
                [
                    'title' => 'Honest advice over easy sales',
                    'text' => 'We will tell you when a shorter route lowers your chances, which gear you should never rent, and what a climb really costs, even when it is not what you hoped to hear.',
                ],
                [
                    'title' => 'The mountain deserves respect',
                    'text' => 'Kilimanjaro is not a trophy. It is a living ecosystem and a sacred landmark for the Tanzanian people.',
                ],
            ],

            'locally_owned_title' => '100% Locally Owned',
            'locally_owned_text' => "Perfect Kilimanjaro is a Tanzanian, family-owned company with no income outflow. Everything is run in Tanzania, by Tanzanian guides, so every dollar you spend supports local guides, porters, cooks, drivers, and communities. Tourism makes up nearly 20% of Tanzania's economy, and traveling with us directly helps strengthen it.",

            'closing_text' => 'When you climb with us, you walk with fifty years of unbroken family knowledge, and you are never just a booking number. You are our guest on our mountain.',

            'about_cta_title' => 'Want to meet <em>the team?</em>',
            'about_cta_text' => 'See who plans and leads every trip, from our guides to our office crew in Arusha.',

            'team_hero_tag' => 'Our team',
            'team_hero_title' => 'The people <em>behind every trip</em>',
            'team_hero_lede' => 'A dozen safari guides, six Kilimanjaro crew leads, and a small Zanzibar-based team — all Tanzanian, all full-time, and all paid above the standard park-wage rates.',
            'team_members' => [],

            'team_cta_title' => 'Ready to plan <em>your trip?</em>',
            'team_cta_text' => "Tell us your dates, budget and what you want to see — we'll match you with the right guide and itinerary.",
        ];
    }
}
