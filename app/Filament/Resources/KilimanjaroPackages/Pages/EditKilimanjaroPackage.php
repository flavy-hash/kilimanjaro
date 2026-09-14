<?php

namespace App\Filament\Resources\KilimanjaroPackages\Pages;

use App\Filament\Resources\KilimanjaroPackages\KilimanjaroPackageResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditKilimanjaroPackage extends EditRecord
{
    protected static string $resource = KilimanjaroPackageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
