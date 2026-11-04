<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * An email address captured before a visitor is allowed to download a
 * package's PDF itinerary. Simple lead capture — one row per download
 * request, duplicates included, so the admin can see repeat interest.
 */
class PdfDownloadLead extends Model
{
    protected $guarded = ['id'];
}
