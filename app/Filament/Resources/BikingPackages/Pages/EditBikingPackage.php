<?php

namespace App\Filament\Resources\BikingPackages\Pages;

use App\Filament\Resources\BikingPackages\BikingPackageResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditBikingPackage extends EditRecord
{
    protected static string $resource = BikingPackageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
