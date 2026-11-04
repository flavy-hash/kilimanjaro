<?php

namespace App\Filament\Resources\VisitorActivity\Pages;

use App\Filament\Resources\VisitorActivity\VisitorActivityResource;
use Filament\Resources\Pages\ListRecords;

class ListVisitorActivities extends ListRecords
{
    protected static string $resource = VisitorActivityResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
