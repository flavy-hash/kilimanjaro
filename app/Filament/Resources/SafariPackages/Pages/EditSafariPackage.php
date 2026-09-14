<?php

namespace App\Filament\Resources\SafariPackages\Pages;

use App\Filament\Resources\SafariPackages\SafariPackageResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditSafariPackage extends EditRecord
{
    protected static string $resource = SafariPackageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
