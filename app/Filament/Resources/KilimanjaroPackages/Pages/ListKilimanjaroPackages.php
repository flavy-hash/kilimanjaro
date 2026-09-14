<?php

namespace App\Filament\Resources\KilimanjaroPackages\Pages;

use App\Filament\Resources\KilimanjaroPackages\KilimanjaroPackageResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListKilimanjaroPackages extends ListRecords
{
    protected static string $resource = KilimanjaroPackageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
