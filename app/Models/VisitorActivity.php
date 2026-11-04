<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * One row per public-site page view. Fully anonymous by design: no cookies,
 * no IP addresses, no names. visitor_hash is a salted hash of (IP + user
 * agent + today's date), so it changes every day and can only ever group one
 * visitor's pages on one day — never trace them across days. Rows older than
 * RETENTION_DAYS are purged by the prune:visitor-activity command.
 */
class VisitorActivity extends Model
{
    public $timestamps = false;

    protected $guarded = ['id'];

    protected $casts = [
        'visited_at' => 'datetime',
    ];

    public const RETENTION_DAYS = 180;
}
