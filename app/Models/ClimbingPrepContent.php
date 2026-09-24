<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Editable copy for the dedicated /climbing-preparation page. A singleton
 * like HomeContent/AboutContent: one row, fetched through current().
 */
class ClimbingPrepContent extends Model
{
    protected $table = 'climbing_prep_content';

    protected $guarded = ['id'];

    protected $casts = [
        'fitness_paragraphs' => 'array',
        'altitude_paragraphs' => 'array',
        'tips' => 'array',
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
            'hero_tag' => 'Climbing preparation',
            'hero_title' => 'How to get ready for <em>Kilimanjaro</em>',
            'hero_lede' => "Fitness, altitude and gear — what actually matters before you get to the gate, from a team that watches climbers succeed and struggle every week.",
            'hero_image' => 'images/gal-kili-climbers.jpg',

            'fitness_title' => 'Fitness',
            'fitness_paragraphs' => [
                "You don't need to be an athlete to summit Kilimanjaro, but you do need to be comfortable walking for six to eight hours a day, several days in a row. The single best preparation is simply hiking — ideally on hills or stairs, with a loaded daypack, at least once a week for two to three months before you travel.",
                'Cardio work (running, cycling, swimming) builds the base fitness that makes long summit-night pushes more manageable, but it does not replace time on your feet. Add some strength work for your legs and core if you can, since it helps on the steep, loose-scree sections more than raw endurance does.',
                "Pace matters more than power on the mountain. Guides deliberately walk slower than most clients want to at first — \"pole pole\" (slowly, slowly) is the standard Swahili phrase you'll hear all week — because a controlled pace is what actually gets people to the summit, not fitness alone.",
            ],

            'altitude_title' => 'Altitude',
            'altitude_paragraphs' => [
                'Altitude affects everyone differently, regardless of fitness level or age — it is the single biggest reason climbers turn back, not tired legs. The best defense is a route with enough days built in to acclimatize properly; this is exactly why longer routes like Lemosho and the Northern Circuit have meaningfully higher summit rates than the shortest ones.',
                "\"Climb high, sleep low\" is the guiding principle: several of our itineraries include a detour to a higher point during the day before dropping back down to a lower camp for the night, which helps your body adjust faster than climbing straight up would.",
                'Every guide on our team carries a pulse oximeter and checks each climber twice daily above certain altitudes. Symptoms like headache, nausea or loss of appetite are common and usually manageable — but a guide\'s decision to descend someone for their safety is final and non-negotiable.',
            ],

            'tips_title' => 'Practical tips',
            'tips' => [
                [
                    'title' => 'Break in your boots',
                    'text' => 'Wear your hiking boots on at least a few real hikes before you travel — new boots and summit night do not mix.',
                ],
                [
                    'title' => 'Layer, don\'t bulk up',
                    'text' => 'Several thin layers you can add or remove work far better than one heavy jacket, since temperature swings hugely between day and night on the mountain.',
                ],
                [
                    'title' => 'Drink more water than feels necessary',
                    'text' => 'Staying well hydrated measurably reduces altitude symptoms. Aim for 3-4 litres a day on the mountain.',
                ],
                [
                    'title' => 'Talk to your doctor about altitude medication',
                    'text' => 'Some climbers take a preventive medication (commonly acetazolamide) starting a day or two before the climb — discuss it with your doctor well before you travel.',
                ],
            ],

            'faqs' => [
                [
                    'question' => 'Do I need previous climbing experience?',
                    'answer' => "No technical climbing skills are required on any of our routes — it's walking (trekking), not mountaineering. Ropes and climbing gear are not used.",
                ],
                [
                    'question' => "What's the minimum age?",
                    'answer' => "We generally recommend 12 and up, and it depends on the individual child's fitness and hiking experience more than age alone. Talk to us before booking if you're planning a family climb.",
                ],
                [
                    'question' => 'Which route has the best chance of summiting?',
                    'answer' => 'Routes with more days for acclimatization — Lemosho and the Northern Circuit in particular — have meaningfully higher summit success rates than shorter routes like Marangu or Umbwe.',
                ],
                [
                    'question' => 'Can I rent gear instead of buying everything?',
                    'answer' => "Yes — most specialist cold-weather gear (down jackets, sleeping bags, poles) can be rented in Moshi or Arusha before your climb. We can arrange this for you.",
                ],
            ],

            'cta_title' => 'Still deciding on a route?',
            'cta_text' => "Tell us your fitness level and travel dates and we'll recommend the route that fits — and send our full equipment checklist.",
        ];
    }
}
