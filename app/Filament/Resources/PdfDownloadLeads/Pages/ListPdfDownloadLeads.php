<?php

namespace App\Filament\Resources\PdfDownloadLeads\Pages;

use App\Filament\Resources\PdfDownloadLeads\PdfDownloadLeadResource;
use Filament\Resources\Pages\ListRecords;

class ListPdfDownloadLeads extends ListRecords
{
    protected static string $resource = PdfDownloadLeadResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
