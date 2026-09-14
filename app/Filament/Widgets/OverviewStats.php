<?php

namespace App\Filament\Widgets;

use App\Models\Inquiry;
use App\Models\Package;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class OverviewStats extends StatsOverviewWidget
{
    protected ?string $heading = 'At a glance';

    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $published = Package::published()->count();
        $draft = Package::where('is_published', false)->count();

        $newInquiries = Inquiry::where('status', 'new')->count();
        $last30 = Inquiry::where('created_at', '>=', now()->subDays(30))->count();
        $prev30 = Inquiry::whereBetween('created_at', [now()->subDays(60), now()->subDays(30)])->count();

        $avgPrice = (int) round(Package::published()->avg('price') ?? 0);

        return [
            Stat::make('Live packages', $published)
                ->description($draft > 0 ? "{$draft} unpublished" : 'All packages published')
                ->descriptionIcon($draft > 0 ? 'heroicon-m-eye-slash' : 'heroicon-m-check-circle')
                ->color($draft > 0 ? 'warning' : 'success'),

            Stat::make('New inquiries', $newInquiries)
                ->description($newInquiries > 0 ? 'Waiting on a reply' : 'Nothing waiting')
                ->descriptionIcon('heroicon-m-inbox-arrow-down')
                ->color($newInquiries > 0 ? 'warning' : 'gray'),

            Stat::make('Inquiries, last 30 days', $last30)
                ->description(self::trend($last30, $prev30))
                ->descriptionIcon($last30 >= $prev30 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->color($last30 >= $prev30 ? 'success' : 'danger'),

            Stat::make('Average price', '$'.number_format($avgPrice))
                ->description('Across published packages')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('gray'),
        ];
    }

    private static function trend(int $current, int $previous): string
    {
        if ($previous === 0) {
            return $current === 0 ? 'No change' : 'First inquiries in this window';
        }

        $delta = round((($current - $previous) / $previous) * 100);

        return ($delta >= 0 ? '+' : '').$delta.'% vs previous 30 days';
    }
}
