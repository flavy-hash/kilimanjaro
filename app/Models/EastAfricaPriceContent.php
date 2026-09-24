<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Editable content for the /kilimanjaro-east-african-citizens page (resident
 * prices in TZS). A singleton like ClimbingPrepContent: one row, fetched
 * through current().
 */
class EastAfricaPriceContent extends Model
{
    protected $table = 'east_africa_price_content';

    protected $guarded = ['id'];

    protected $casts = [
        'routes' => 'array',
        'included' => 'array',
        'excluded' => 'array',
        'why_points' => 'array',
        'citizens_paragraphs' => 'array',
        'reasons' => 'array',
    ];

    /** Group-size columns, keyed as they're stored on each route row. */
    public const GROUPS = [
        'p1' => '1 Client',
        'p2' => '2 Clients',
        'p3' => '3 – 4 Clients',
        'p5' => '5+ Clients',
    ];

    public static function current(): self
    {
        return static::query()->first() ?? static::create(static::defaults());
    }

    /** @return array<string, mixed> */
    public static function defaults(): array
    {
        $route = fn (string $name, int $days, ?string $slug, array $p) => [
            'route' => $name, 'days' => $days, 'slug' => $slug,
            'p1' => $p[0], 'p2' => $p[1], 'p3' => $p[2], 'p5' => $p[3],
        ];

        return [
            'hero_title' => 'Kilimanjaro climbing prices for East African citizens',
            'hero_lede' => "Climb Africa's highest mountain with a local family of guides. All prices are per person, in Tanzanian Shillings (TZS).",
            'hero_image' => 'images/hero-kilimanjaro.jpg',
            'intro' => 'Perfect Kilimanjaro is a 100% locally owned company with three generations of Kilimanjaro guiding experience. We offer affordable and professional climbing packages for East African citizens and residents. All prices are quoted in Tanzanian Shillings (TZS).',

            'routes' => [
                $route('Lemosho Route', 8, 'lemosho', [2700000, 2040000, 1755000, 1650000]),
                $route('Lemosho Route', 7, null, [2500000, 1870000, 1630000, 1500000]),
                $route('Lemosho Route', 6, 'lemosho-6-days', [2100000, 1700000, 1510000, 1375000]),
                $route('Machame Route', 7, 'machame', [2500000, 1870000, 1630000, 1500000]),
                $route('Machame Route', 6, 'machame-6-days', [1970000, 1650000, 1450000, 1320000]),
                $route('Rongai Route', 7, 'rongai', [2500000, 1870000, 1630000, 1500000]),
                $route('Rongai Route', 6, null, [2100000, 1700000, 1510000, 1375000]),
                $route('Umbwe Route', 6, 'umbwe', [1970000, 1650000, 1450000, 1320000]),
                $route('Marangu Route', 6, 'marangu', [1900000, 1450000, 1250000, 1150000]),
                $route('Marangu Route', 5, 'marangu-5-days', [1650000, 1250000, 1100000, 930000]),
            ],

            'included' => [
                'Kilimanjaro National Park fees',
                'Rescue fees',
                'Professional mountain guides',
                'Assistant guides (where applicable)',
                'Porters and mountain cook',
                'Crew salaries and welfare',
                'Government taxes',
                'All meals during the climb',
                'Sleeping tents and dining tent (camping routes)',
                'Camping equipment (camping routes)',
                'Transportation from Moshi to the park gate and back to the hotel after the climb',
                'Pick-up from Moshi Bus Stand upon arrival',
                'Two nights in Moshi on Bed & Breakfast — one night before and one night after the climb',
            ],
            'excluded' => [
                'Flights',
                'Tanzania visa fees',
                'Travel insurance',
                'Personal climbing gear',
                'Tips for guides, porters, and cook',
                'Lunch and dinner at the hotel',
                'Drinks and personal expenses',
                'Additional hotel nights not included in the itinerary',
            ],

            'why_title' => 'Why choose Perfect Kilimanjaro?',
            'why_intro' => 'Perfect Kilimanjaro is a locally owned company built by a family with three generations of Kilimanjaro guiding experience. Our team was born and raised on the slopes of Mount Kilimanjaro and has helped thousands of climbers reach the summit safely. By booking with us, you receive professional service while directly supporting local guides, porters, and their families.',
            'why_points' => [
                'A 100% locally owned company with three generations of Kilimanjaro guiding experience',
                'Experienced mountain guides and professional assistant guides',
                'Well-trained porters and dedicated mountain cooks',
                'Quality mountain meals and proper camping equipment',
                'Safety monitoring throughout the climb',
                'Emergency oxygen carried on the mountain',
                'Comfortable accommodation before and after the trek',
            ],

            'citizens_title' => 'Why East African citizens climb with us',
            'citizens_paragraphs' => [
                'At Perfect Kilimanjaro, we believe that climbers from East Africa deserve the same high-quality experience as climbers from anywhere else in the world. We proudly welcome trekkers from Tanzania, Kenya, Uganda, Rwanda, Burundi, and South Sudan with specially designed packages that take advantage of the reduced Kilimanjaro National Park fees available to East African citizens and residents.',
                'Because East African citizens pay significantly lower park fees than international visitors, climbing Kilimanjaro can cost less than half of what many foreign visitors pay. However, lower park fees should never mean lower-quality service.',
                'Unfortunately, many East African climbers receive a different experience from what international visitors receive. Some companies cut costs by hiring less experienced guides, paying lower crew wages, providing basic meals, or reducing safety standards. While the price may look attractive, the overall experience and safety can suffer. At Perfect Kilimanjaro, we do things differently.',
            ],
            'reasons' => [
                ['title' => 'The same service we provide to international clients', 'text' => 'Whether you come from Kenya, Uganda, Tanzania, Rwanda, Burundi, South Sudan, the United States, Europe, Asia, or South America, we provide the same professional service. We do not believe in giving one standard of service to international clients and another standard to East African clients.'],
                ['title' => 'Safety comes first', 'text' => 'Mount Kilimanjaro is a serious high-altitude mountain. The biggest challenge for most climbers is altitude sickness. Our guides are trained to recognize early signs of altitude-related illnesses and respond quickly when necessary. We carry supplemental oxygen on our climbs and closely monitor clients throughout the trek. Our goal is simple: help every client reach the summit safely and return home with great memories.'],
                ['title' => 'Experience matters', 'text' => 'A guide who climbs Kilimanjaro once or twice a year is not the same as a guide who climbs the mountain dozens of times every year. Our team consists of active, experienced mountain professionals who regularly work on Kilimanjaro. Their experience allows them to better manage altitude challenges, changing weather conditions, and client needs.'],
                ['title' => 'A local family with three generations of experience', 'text' => 'Our grandfather guided on Kilimanjaro in the 1970s. Our father guided in the 1980s and has reached the summit hundreds of times. Today, we continue that tradition with a professional team dedicated to providing safe, ethical, and memorable Kilimanjaro adventures.'],
                ['title' => 'Excellent value for money', 'text' => 'East African climbers already benefit from reduced park fees. We use that advantage to offer affordable packages without sacrificing quality. You pay less because the government offers lower park fees for East African citizens — not because we cut corners on safety, food, guides, or equipment.'],
            ],

            'cta_title' => 'Climb Kilimanjaro with confidence',
            'cta_text' => 'For bookings and inquiries, contact Perfect Kilimanjaro and start your journey to the Roof of Africa.',
            'whatsapp' => '+255 752 967 222',
            'email' => 'priscus87@gmail.com',
        ];
    }
}
