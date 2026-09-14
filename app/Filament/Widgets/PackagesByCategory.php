<?php

namespace App\Filament\Widgets;

use App\Models\Package;
use Filament\Widgets\ChartWidget;

class PackagesByCategory extends ChartWidget
{
    protected ?string $heading = 'Packages by area';

    protected static ?int $sort = 3;

    protected ?string $maxHeight = '260px';

    protected function getData(): array
    {
        $counts = [
            'Safaris' => Package::where('category', 'safari')->where('circuit', 'northern')->count(),
            'Southern Circuit' => Package::where('category', 'safari')->where('circuit', 'southern')->count(),
            'Kilimanjaro' => Package::where('category', 'kilimanjaro')->count(),
            'Zanzibar' => Package::where('category', 'zanzibar')->count(),
        ];

        return [
            'datasets' => [[
                'label' => 'Packages',
                'data' => array_values($counts),
                'backgroundColor' => ['#e0b04a', '#c98f3a', '#ff7a2f', '#33c7c2'],
                'borderWidth' => 0,
            ]],
            'labels' => array_keys($counts),
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
