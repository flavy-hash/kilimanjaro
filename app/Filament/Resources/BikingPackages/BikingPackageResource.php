<?php

namespace App\Filament\Resources\BikingPackages;

use App\Filament\Resources\BikingPackages\Pages\CreateBikingPackage;
use App\Filament\Resources\BikingPackages\Pages\EditBikingPackage;
use App\Filament\Resources\BikingPackages\Pages\ListBikingPackages;
use App\Filament\Resources\Packages\BasePackageResource;
use BackedEnum;
use Filament\Support\Icons\Heroicon;

class BikingPackageResource extends BasePackageResource
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBolt;

    protected static ?string $navigationLabel = 'Biking';

    protected static ?string $modelLabel = 'biking package';

    protected static string|\UnitEnum|null $navigationGroup = 'Packages';

    protected static ?int $navigationSort = 7;

    protected static string $packageCategory = 'biking';

    protected static ?string $packageCircuit = null;

    public static function getPages(): array
    {
        return [
            'index' => ListBikingPackages::route('/'),
            'create' => CreateBikingPackage::route('/create'),
            'edit' => EditBikingPackage::route('/{record}/edit'),
        ];
    }
}
