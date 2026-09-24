<?php

namespace App\Filament\Resources\EastAfricaPackages\Pages;

use App\Filament\Resources\EastAfricaPackages\EastAfricaPackageResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditEastAfricaPackage extends EditRecord
{
    protected static string $resource = EastAfricaPackageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
