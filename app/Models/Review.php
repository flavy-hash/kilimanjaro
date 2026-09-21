<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
    ];

    public function package(): BelongsTo
    {
        return $this->belongsTo(Package::class);
    }

    public function scopeApproved(Builder $query): Builder
    {
        return $query->where('is_approved', true);
    }

    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('is_featured', true);
    }

    public function initials(): string
    {
        $words = preg_split('/\s+/', trim($this->name)) ?: [];

        $letters = array_map(fn ($w) => mb_strtoupper(mb_substr($w, 0, 1)), array_slice($words, 0, 2));

        return implode('', $letters) ?: '?';
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
            'initials' => $this->initials(),
            'rating' => $this->rating,
            'service_rating' => $this->service_rating,
            'value_rating' => $this->value_rating,
            'title' => $this->title,
            'body' => $this->body,
            'photo' => $this->photo,
            'trip' => $this->package?->name,
            'trip_url' => $this->package ? url($this->package->slug) : null,
            'stayed_at' => $this->stayed_at?->format('M j, Y'),
        ];
    }
}
