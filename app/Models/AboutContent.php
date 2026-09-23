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

            'story_title' => 'Our story',
            'story_heading' => 'Not a booking platform — <em>a small local team</em>',
            'story_paragraphs' => [
                'Started by guides who spent years leading trips for foreign-owned operators and wanted travelers to see more of what they were actually paying for — without a foreign agency taking the largest cut of every booking.',
                "We plan every itinerary ourselves, drive our own vehicles, and work only with camps and crews we know personally. When you book with us, the guide who answers your WhatsApp message is the same team that runs your trip on the ground.",
                "That's meant staying small on purpose. We'd rather run fewer trips well than grow past the point where we can still vouch for every guide, cook and driver by name.",
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

            'values_tag' => 'What we do differently',
            'values_heading' => 'Why book <em>with us</em>',
            'values' => [
                [
                    'title' => 'Fair wages for crew',
                    'text' => 'Porters and guides paid above the industry standard, always — not the minimum the park allows.',
                ],
                [
                    'title' => 'No brochure markup',
                    'text' => 'Priced directly off what the trip costs to run, not an agency margin stacked on top of a wholesale rate.',
                ],
                [
                    'title' => 'Tailor-made, not off the shelf',
                    'text' => 'Every itinerary is adjusted to your dates, pace and budget rather than sold as a fixed package.',
                ],
            ],

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
