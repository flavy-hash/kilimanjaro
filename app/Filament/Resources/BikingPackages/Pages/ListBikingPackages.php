<?php

namespace App\Filament\Resources\BikingPackages\Pages;

use App\Filament\Resources\BikingPackages\BikingPackageResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListBikingPackages extends ListRecords
{
    protected static string $resource = BikingPackageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
