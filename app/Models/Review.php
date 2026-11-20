<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Cache;

class Review extends Model
{
    protected $guarded = [];

    protected $casts = [
        'rating' => 'integer',
        'service_rating' => 'integer',
        'value_rating' => 'integer',
        'is_approved' => 'boolean',
        'is_featured' => 'boolean',
        'stayed_at' => 'date',
        'photos' => 'array',
    ];

    public function package(): BelongsTo
    {
        return $this->belongsTo(Package::class);
    }

    protected static function booted(): void
    {
        static::saved(fn () => Cache::forget('reviews:site-aggregate'));
        static::deleted(fn () => Cache::forget('reviews:site-aggregate'));
    }

    public function scopeApproved(Builder $query): Builder
    {
        return $query->where('is_approved', true);
    }

    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('is_featured', true);
    }

    /**
     * Up to the first two trip photos, falling back to the older single
     * `photo` column for reviews submitted before `photos` existed.
     *
     * @return array<int, string>
     */
    public function photoList(): array
    {
        $photos = array_values(array_filter($this->photos ?? []));

        if (empty($photos) && filled($this->photo)) {
            $photos = [$this->photo];
        }

        return array_slice($photos, 0, 2);
    }

    public function initials(): string
    {
        $words = preg_split('/\s+/', trim($this->name)) ?: [];

        $letters = array_map(fn ($w) => mb_strtoupper(mb_substr($w, 0, 1)), array_slice($words, 0, 2));

        return implode('', $letters) ?: '?';
    }

    /**
     * Sitewide count + average, used for the Organization structured data
     * emitted on every page. Cached since it's read on every page load but
     * only changes when a review is approved.
     *
     * @return array{count: int, average: float|null}
     */
    public static function siteAggregate(): array
    {
        return Cache::remember('reviews:site-aggregate', 3600, function () {
            $count = static::approved()->count();

            return [
                'count' => $count,
                'average' => $count ? round(static::approved()->avg('rating'), 1) : null,
            ];
        });
    }

    /**
     * Shape consumed by both the /reviews page and the homepage's featured
     * strip, so the two never drift out of sync with each other.
     */
    public function toCardArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'country' => $this->country,
            'initials' => $this->initials(),
            'rating' => $this->rating,
            'service_rating' => $this->service_rating,
            'value_rating' => $this->value_rating,
            'title' => $this->title,
            'body' => $this->body,
            'photo' => $this->photo,
            'photos' => $this->photoList(),
            'trip' => $this->package?->name,
            'trip_url' => $this->package ? url($this->package->slug) : null,
            'stayed_at' => $this->stayed_at?->format('M j, Y'),
        ];
    }
}
