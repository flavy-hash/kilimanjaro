<?php

namespace App\Filament\Resources\ZanzibarPackages;

use App\Filament\Resources\ZanzibarPackages\Pages\CreateZanzibarPackage;
use App\Filament\Resources\ZanzibarPackages\Pages\EditZanzibarPackage;
use App\Filament\Resources\ZanzibarPackages\Pages\ListZanzibarPackages;
use App\Filament\Resources\Packages\BasePackageResource;
use BackedEnum;
use Filament\Support\Icons\Heroicon;

class ZanzibarPackageResource extends BasePackageResource
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedSun;

    protected static ?string $navigationLabel = 'Zanzibar';

    protected static ?string $modelLabel = 'Zanzibar package';

    protected static string|\UnitEnum|null $navigationGroup = 'Packages';

    protected static ?int $navigationSort = 5;

    protected static string $packageCategory = 'zanzibar';

    protected static ?string $packageCircuit = null;

    public static function getPages(): array
    {
        return [
            'index' => ListZanzibarPackages::route('/'),
            'create' => CreateZanzibarPackage::route('/create'),
            'edit' => EditZanzibarPackage::route('/{record}/edit'),
        ];
    }
}
