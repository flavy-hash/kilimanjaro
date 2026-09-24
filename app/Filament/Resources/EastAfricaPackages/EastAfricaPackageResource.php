<?php

namespace App\Filament\Resources\EastAfricaPackages;

use App\Filament\Resources\EastAfricaPackages\Pages\CreateEastAfricaPackage;
use App\Filament\Resources\EastAfricaPackages\Pages\EditEastAfricaPackage;
use App\Filament\Resources\EastAfricaPackages\Pages\ListEastAfricaPackages;
use App\Filament\Resources\Packages\BasePackageResource;
use BackedEnum;
use Filament\Support\Icons\Heroicon;

class EastAfricaPackageResource extends BasePackageResource
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedGlobeEuropeAfrica;

    protected static ?string $navigationLabel = 'East Africa';

    protected static ?string $modelLabel = 'East Africa package';

    protected static string|\UnitEnum|null $navigationGroup = 'Packages';

    protected static ?int $navigationSort = 6;

    protected static string $packageCategory = 'east_africa';

    protected static ?string $packageCircuit = null;

    public static function getPages(): array
    {
        return [
            'index' => ListEastAfricaPackages::route('/'),
            'create' => CreateEastAfricaPackage::route('/create'),
            'edit' => EditEastAfricaPackage::route('/{record}/edit'),
        ];
    }
}
