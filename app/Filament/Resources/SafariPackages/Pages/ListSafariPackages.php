<?php

namespace App\Filament\Resources\SafariPackages\Pages;

use App\Filament\Resources\SafariPackages\SafariPackageResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListSafariPackages extends ListRecords
{
    protected static string $resource = SafariPackageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
