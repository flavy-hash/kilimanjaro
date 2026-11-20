<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class NavItem extends Model
{
    protected $guarded = [];

    protected $casts = [
        'links' => 'array',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * What a nav link does when clicked. Admins pick one of these plus a target
     * rather than typing JavaScript, so nothing stored here is ever executed as
     * code — the layout turns the pair into a handler at render time.
     */
    public const ACTIONS = [
        'category' => 'Jump to a category',
        'package' => 'Open a package',
        'about' => 'Scroll to an About section',
        'url' => 'Go to a URL',
    ];

    public const ABOUT_SECTIONS = [
        'about' => 'About us',
        'reviews' => 'Reviews',
        'faq' => 'FAQ',
    ];

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order')->orderBy('id');
    }

    /**
     * The menu as the layout's JS expects it. Falls back to the built-in menu
     * when the table is empty, so the site always has navigation.
     */
    public static function forSite(): array
    {
        $items = static::active()->ordered()->get();

        return $items->isEmpty()
            ? static::defaults()
            : $items->map->toNavArray()->all();
    }

    public function toNavArray(): array
    {
        if ($this->type === 'link') {
            return [
                'label' => $this->label,
                'href' => $this->resolveHref(),
            ];
        }

        return [
            'label' => $this->label,
            'image' => $this->image ? asset($this->image) : null,
            'title' => $this->title ?: $this->label,
            'desc' => $this->description,
            'cta' => $this->cta_label,
            'ctaAction' => $this->actionArray($this->cta_action, $this->cta_target),
            'links' => collect($this->links ?? [])
                ->filter(fn ($link) => filled($link['text'] ?? null))
                ->map(fn ($link) => [
                    'text' => $link['text'],
                    'action' => $this->actionArray($link['action'] ?? null, $link['target'] ?? null),
                ])
                ->values()
                ->all(),
        ];
    }

    protected function resolveHref(): string
    {
        $href = trim((string) $this->href);

        if ($href === '') {
            return url('/');
        }

        // Leave absolute links alone; treat everything else as a site path.
        return str_contains($href, '://') ? $href : url($href);
    }

    /**
     * Package links carry the package's category too, because the site's
     * jumpTrip() switches tab before scrolling to the card.
     */
    protected function actionArray(?string $action, ?string $target): ?array
    {
        if (blank($action)) {
            return null;
        }

        $payload = ['type' => $action, 'target' => $target];

        if ($action === 'url') {
            $payload['target'] = filled($target) && ! str_contains($target, '://')
                ? url($target)
                : $target;
        }

        if ($action === 'package') {
            $payload['category'] = Package::where('slug', $target)->value('category');
        }

        return $payload;
    }

    /**
     * The original hard-coded menu, kept as the fallback and as seed data.
     *
     * @see \Database\Seeders\NavItemSeeder
     */
    public static function defaults(): array
    {
        return collect(static::defaultRows())
            ->map(fn (array $row) => (new static($row))->toNavArray())
            ->all();
    }

    /**
     * Order matches the site's requested nav structure: Kilimanjaro, Biking,
     * Safari, Traditional tour, East African Prices, Climbing Preparation, Blog, About Us.
     * "Southern Circuit" is no longer a top-level item — it's folded into
     * the Safaris dropdown as a link, since it's a circuit within the safari
     * category rather than its own category.
     *
     * @return array<int, array<string, mixed>>
     */
    public static function defaultRows(): array
    {
        return [
            [
                'label' => 'Kilimanjaro',
                'type' => 'mega',
                'image' => 'images/hero-kilimanjaro.jpg',
                'title' => 'Kilimanjaro Climbs',
                'description' => "Guided treks up Africa's highest peak through five climate zones, on the route that fits your time and fitness.",
                'cta_label' => 'Explore Kilimanjaro',
                'cta_action' => 'category',
                'cta_target' => 'kilimanjaro',
                'links' => [
                    ['text' => 'Overview', 'action' => 'category', 'target' => 'kilimanjaro'],
                    ['text' => 'Machame · 7 Days', 'action' => 'package', 'target' => 'machame'],
                    ['text' => 'Lemosho · 8 Days', 'action' => 'package', 'target' => 'lemosho'],
                    ['text' => 'Marangu · 6 Days', 'action' => 'package', 'target' => 'marangu'],
                    ['text' => 'Northern Circuit · 8 Days', 'action' => 'package', 'target' => 'northern'],
                    ['text' => 'East African Citizens (TZS)', 'action' => 'url', 'target' => '/kilimanjaro-east-african-citizens'],
                ],
            ],
            [
                'label' => 'Biking',
                'type' => 'mega',
                'image' => 'images/hero-kilimanjaro.jpg',
                'title' => 'Cycling Safaris',
                'description' => "Mountain bike through highland farms, villages and crater rims, with a support vehicle following the whole way.",
                'cta_label' => 'Explore biking',
                'cta_action' => 'category',
                'cta_target' => 'biking',
                'links' => [
                    ['text' => 'View all biking trips', 'action' => 'category', 'target' => 'biking'],
                    ['text' => 'Ngorongoro Highlands · 5 Days', 'action' => 'package', 'target' => 'ngorongoro-highlands-cycling-safari'],
                    ['text' => 'Kilimanjaro Foothills · 3 Days', 'action' => 'package', 'target' => 'kilimanjaro-foothills-ride'],
                ],
            ],
            [
                'label' => 'Safari',
                'type' => 'mega',
                'image' => 'images/hero-safari.jpg',
                'title' => 'Tanzania Safaris',
                'description' => 'Northern Circuit favorites — Serengeti, Ngorongoro and Tarangire — plus the uncrowded southern parks.',
                'cta_label' => 'Explore safaris',
                'cta_action' => 'category',
                'cta_target' => 'safari',
                'links' => [
                    ['text' => 'View all safaris', 'action' => 'category', 'target' => 'safari'],
                    ['text' => 'Classic', 'action' => 'package', 'target' => 'northern-circuit'],
                    ['text' => 'Southern Circuit', 'action' => 'package', 'target' => 'southern-circuit'],
                    ['text' => 'Luxury', 'action' => 'package', 'target' => 'migration-safari'],
                ],
            ],
            [
                'label' => 'Traditional tour',
                'type' => 'mega',
                'image' => 'images/hero-zanzibar.jpg',
                'title' => 'Traditional Tours',
                'description' => 'Stone Town history, spice farm tours, dhow cruises and beach time on the Indian Ocean coast.',
                'cta_label' => 'Explore Traditional Tours',
                'cta_action' => 'category',
                'cta_target' => 'zanzibar',
                'links' => [
                    ['text' => 'View all traditional tours', 'action' => 'category', 'target' => 'zanzibar'],
                ],
            ],
            [
                'label' => 'East African Prices',
                'type' => 'link',
                'href' => '/kilimanjaro-east-african-citizens',
            ],
            [
                'label' => 'Climbing Preparation',
                'type' => 'link',
                'href' => '/climbing-preparation',
            ],
            [
                'label' => 'Blog',
                'type' => 'link',
                'href' => '/blog',
            ],
            [
                'label' => 'About Us',
                'type' => 'mega',
                'image' => 'images/menu-about.jpg',
                'title' => 'About Perfect Kilimanjaro',
                'description' => "A small Arusha-based team running every trip in-house — not a booking platform reselling someone else's itinerary.",
                'cta_label' => 'Read our story',
                'cta_action' => 'url',
                'cta_target' => '/about',
                'links' => [
                    ['text' => 'About Us', 'action' => 'url', 'target' => '/about'],
                    ['text' => 'Our Team', 'action' => 'url', 'target' => '/team'],
                    ['text' => 'Reviews', 'action' => 'url', 'target' => '/reviews'],
                    ['text' => 'FAQ', 'action' => 'about', 'target' => 'faq'],
                ],
            ],
        ];
    }
}
