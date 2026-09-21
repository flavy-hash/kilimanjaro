<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Package extends Model
{
    protected $guarded = [];

    protected $casts = [
        'highlights' => 'array',
        'included' => 'array',
        'excluded' => 'array',
        'gallery' => 'array',
        'itinerary' => 'array',
        'is_published' => 'boolean',
        'rating' => 'float',
    ];

    /**
     * Named `stays`, not `accommodations`: there is already an `accommodation`
     * column holding the short summary line, and an attribute of the same name
     * would shadow the relation.
     */
    public function stays(): BelongsToMany
    {
        return $this->belongsToMany(Accommodation::class)
            ->withTimestamps()
            ->orderByPivot('id');
    }

    public function inquiries(): HasMany
    {
        return $this->hasMany(Inquiry::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_published', true);
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order')->orderBy('id');
    }

    /**
     * Every published package, in the shape the public site expects.
     *
     * @return array<int, array<string, mixed>>
     */
    public static function forSite(): array
    {
        return static::published()
            ->ordered()
            ->with('stays')
            ->get()
            ->map
            ->toTripArray()
            ->all();
    }

    /**
     * The public site renders packages from a plain array. Keeping that shape here
     * means the Blade/JS front end is unchanged by the move to the database — the
     * key `id` deliberately carries the slug, because it is what the URLs use.
     */
    public function toTripArray(): array
    {
        return [
            'id' => $this->slug,
            'category' => $this->category,
            'circuit' => $this->circuit,
            'name' => $this->name,
            'nickname' => $this->nickname,
            'location' => $this->location,
            'days' => $this->days,
            'price' => $this->price,
            'tag' => $this->tag,
            'rate' => $this->rate,
            'rating' => $this->rating,
            'reviews' => $this->reviews,
            'group' => $this->group,
            'difficulty' => $this->difficulty,
            'best_time' => $this->best_time,
            'start_point' => $this->start_point,
            'image' => $this->image,
            'accommodation' => $this->accommodation ?: $this->stays->first()?->class,
            'blurb' => $this->blurb,
            'overview' => $this->overview,
            'highlights' => $this->highlights ?? [],
            'included' => $this->included ?? [],
            'excluded' => $this->excluded ?? [],
            'gallery' => $this->gallery ?? [],
            'itinerary' => $this->itinerary ?? [],
            'stays' => $this->stayArrays(),
        ];
    }

    /**
     * One block per linked accommodation. Empty when none are linked yet — the
     * detail page hides the whole section in that case.
     *
     * @return array<int, array<string, mixed>>
     */
    protected function stayArrays(): array
    {
        return $this->stays->map->toStayArray()->all();
    }
}
