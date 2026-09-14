<?php

namespace App\Filament\Resources\ZanzibarPackages\Pages;

use App\Filament\Resources\ZanzibarPackages\ZanzibarPackageResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditZanzibarPackage extends EditRecord
{
    protected static string $resource = ZanzibarPackageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
