<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Accommodation extends Model
{
    protected $guarded = [];

    protected $casts = [
        'amenities' => 'array',
        'stars' => 'integer',
    ];

    public function packages(): BelongsToMany
    {
        return $this->belongsToMany(Package::class)->withTimestamps();
    }

    /**
     * Shape consumed by the public site's "Where you'll stay" card.
     */
    public function toStayArray(): array
    {
        return [
            'name' => $this->name,
            'image' => $this->image,
            'stars' => $this->stars,
            'place' => $this->place,
            'class' => $this->class,
            'amenities' => $this->amenities ?? [],
            'note' => $this->note,
        ];
    }
}
