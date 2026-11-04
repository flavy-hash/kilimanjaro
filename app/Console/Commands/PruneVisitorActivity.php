<?php

namespace App\Console\Commands;

use App\Models\VisitorActivity;
use Illuminate\Console\Command;

class PruneVisitorActivity extends Command
{
    protected $signature = 'visitor-activity:prune';

    protected $description = 'Deletes visitor activity rows older than the retention window';

    public function handle(): int
    {
        $cutoff = now()->subDays(VisitorActivity::RETENTION_DAYS);

        $deleted = VisitorActivity::where('visited_at', '<', $cutoff)->delete();

        $this->info("Deleted {$deleted} visitor activity row(s) older than {$cutoff->toDateString()}.");

        return self::SUCCESS;
    }
}
