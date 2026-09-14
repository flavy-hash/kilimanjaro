<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Inquiry extends Model
{
    protected $guarded = [];

    protected $casts = [
        'start_date' => 'date',
        'group_size' => 'integer',
    ];

    public const STATUSES = [
        'new' => 'New',
        'contacted' => 'Contacted',
        'quoted' => 'Quoted',
        'booked' => 'Booked',
        'closed' => 'Closed',
    ];

    public function package(): BelongsTo
    {
        return $this->belongsTo(Package::class);
    }

    /**
     * Reference shown to the customer on submit, e.g. KIL-4F7QB.
     */
    public static function makeReference(?string $category): string
    {
        $prefix = match ($category) {
            'kilimanjaro' => 'KIL',
            'zanzibar' => 'ZNZ',
            default => 'SAF',
        };

        $alphabet = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';
        $suffix = '';
        for ($i = 0; $i < 5; $i++) {
            $suffix .= $alphabet[random_int(0, strlen($alphabet) - 1)];
        }

        return "{$prefix}-{$suffix}";
    }
}
