<?php

namespace App\Filament\Resources\EastAfricaPackages\Pages;

use App\Filament\Resources\EastAfricaPackages\EastAfricaPackageResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListEastAfricaPackages extends ListRecords
{
    protected static string $resource = EastAfricaPackageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
