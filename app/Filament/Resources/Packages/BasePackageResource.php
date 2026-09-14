<?php

namespace App\Filament\Resources\Packages;

use App\Filament\Resources\Packages\Schemas\PackageForm;
use App\Filament\Resources\Packages\Tables\PackagesTable;
use App\Models\Package;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

/**
 * Shared behaviour for the four package areas. Each subclass pins a category
 * (and circuit, for safaris) so its list only ever shows its own packages and
 * new records are created into the right bucket automatically.
 */
abstract class BasePackageResource extends Resource
{
    protected static ?string $model = Package::class;

    protected static ?string $recordTitleAttribute = 'name';

    protected static string $packageCategory = 'safari';

    protected static ?string $packageCircuit = null;

    public static function form(Schema $schema): Schema
    {
        return PackageForm::configure($schema, static::$packageCategory, static::$packageCircuit);
    }

    public static function table(Table $table): Table
    {
        return PackagesTable::configure($table);
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery()->where('category', static::$packageCategory);

        if (static::$packageCircuit !== null) {
            $query->where('circuit', static::$packageCircuit);
        }

        return $query;
    }

    public static function getNavigationBadge(): ?string
    {
        return (string) static::getEloquentQuery()->count();
    }
}
