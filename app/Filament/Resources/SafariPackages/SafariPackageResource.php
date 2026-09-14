<?php

namespace App\Filament\Resources\SafariPackages;

use App\Filament\Resources\SafariPackages\Pages\CreateSafariPackage;
use App\Filament\Resources\SafariPackages\Pages\EditSafariPackage;
use App\Filament\Resources\SafariPackages\Pages\ListSafariPackages;
use App\Filament\Resources\Packages\BasePackageResource;
use BackedEnum;
use Filament\Support\Icons\Heroicon;

class SafariPackageResource extends BasePackageResource
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedGlobeAmericas;

    protected static ?string $navigationLabel = 'Safaris';

    protected static ?string $modelLabel = 'safari package';

    protected static string|\UnitEnum|null $navigationGroup = 'Packages';

    protected static ?int $navigationSort = 2;

    protected static string $packageCategory = 'safari';

    protected static ?string $packageCircuit = 'northern';

    public static function getPages(): array
    {
        return [
            'index' => ListSafariPackages::route('/'),
            'create' => CreateSafariPackage::route('/create'),
            'edit' => EditSafariPackage::route('/{record}/edit'),
        ];
    }
}
