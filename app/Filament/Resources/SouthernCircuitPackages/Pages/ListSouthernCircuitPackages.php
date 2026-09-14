<?php

namespace App\Filament\Resources\SouthernCircuitPackages\Pages;

use App\Filament\Resources\SouthernCircuitPackages\SouthernCircuitPackageResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListSouthernCircuitPackages extends ListRecords
{
    protected static string $resource = SouthernCircuitPackageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
