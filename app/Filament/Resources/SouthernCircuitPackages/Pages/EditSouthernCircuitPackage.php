<?php

namespace App\Filament\Resources\SouthernCircuitPackages\Pages;

use App\Filament\Resources\SouthernCircuitPackages\SouthernCircuitPackageResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditSouthernCircuitPackage extends EditRecord
{
    protected static string $resource = SouthernCircuitPackageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
