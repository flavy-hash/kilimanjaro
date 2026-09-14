<?php

namespace App\Filament\Resources\KilimanjaroPackages;

use App\Filament\Resources\KilimanjaroPackages\Pages\CreateKilimanjaroPackage;
use App\Filament\Resources\KilimanjaroPackages\Pages\EditKilimanjaroPackage;
use App\Filament\Resources\KilimanjaroPackages\Pages\ListKilimanjaroPackages;
use App\Filament\Resources\Packages\BasePackageResource;
use BackedEnum;
use Filament\Support\Icons\Heroicon;

class KilimanjaroPackageResource extends BasePackageResource
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedArrowTrendingUp;

    protected static ?string $navigationLabel = 'Kilimanjaro';

    protected static ?string $modelLabel = 'Kilimanjaro package';

    protected static string|\UnitEnum|null $navigationGroup = 'Packages';

    protected static ?int $navigationSort = 4;

    protected static string $packageCategory = 'kilimanjaro';

    protected static ?string $packageCircuit = null;

    public static function getPages(): array
    {
        return [
            'index' => ListKilimanjaroPackages::route('/'),
            'create' => CreateKilimanjaroPackage::route('/create'),
            'edit' => EditKilimanjaroPackage::route('/{record}/edit'),
        ];
    }
}
