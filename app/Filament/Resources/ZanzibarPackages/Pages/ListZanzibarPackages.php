<?php

namespace App\Filament\Resources\ZanzibarPackages\Pages;

use App\Filament\Resources\ZanzibarPackages\ZanzibarPackageResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListZanzibarPackages extends ListRecords
{
    protected static string $resource = ZanzibarPackageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
