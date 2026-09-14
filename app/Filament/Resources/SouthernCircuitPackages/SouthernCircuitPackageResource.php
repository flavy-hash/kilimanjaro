<?php

namespace App\Filament\Resources\SouthernCircuitPackages;

use App\Filament\Resources\SouthernCircuitPackages\Pages\CreateSouthernCircuitPackage;
use App\Filament\Resources\SouthernCircuitPackages\Pages\EditSouthernCircuitPackage;
use App\Filament\Resources\SouthernCircuitPackages\Pages\ListSouthernCircuitPackages;
use App\Filament\Resources\Packages\BasePackageResource;
use BackedEnum;
use Filament\Support\Icons\Heroicon;

class SouthernCircuitPackageResource extends BasePackageResource
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedMap;

    protected static ?string $navigationLabel = 'Southern Circuit';

    protected static ?string $modelLabel = 'southern circuit package';

    protected static string|\UnitEnum|null $navigationGroup = 'Packages';

    protected static ?int $navigationSort = 3;

    protected static string $packageCategory = 'safari';

    protected static ?string $packageCircuit = 'southern';

    public static function getPages(): array
    {
        return [
            'index' => ListSouthernCircuitPackages::route('/'),
            'create' => CreateSouthernCircuitPackage::route('/create'),
            'edit' => EditSouthernCircuitPackage::route('/{record}/edit'),
        ];
    }
}
