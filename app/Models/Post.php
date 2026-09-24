<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $guarded = [];

    protected $casts = [
        'is_published' => 'boolean',
        'published_at' => 'datetime',
    ];

    /** Laravel resolves route-model-binding on this instead of the id. */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_published', true)->where('published_at', '<=', now());
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderByDesc('published_at');
    }

    public function readingMinutes(): int
    {
        $words = str_word_count(strip_tags($this->body ?? ''));

        return max(1, (int) ceil($words / 200));
    }

    /** @return array<string, mixed> */
    public function toCardArray(): array
    {
        return [
            'slug' => $this->slug,
            'title' => $this->title,
            'excerpt' => $this->excerpt,
            'cover_image' => $this->cover_image,
            'author_name' => $this->author_name,
            'published_at' => $this->published_at?->format('M j, Y'),
            'reading_minutes' => $this->readingMinutes(),
        ];
    }
}
