<?php

namespace App\Filament\Resources\PdfDownloadLeads;

use App\Filament\Resources\PdfDownloadLeads\Pages\ListPdfDownloadLeads;
use App\Models\PdfDownloadLead;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

/**
 * Emails captured on the public site before a visitor is allowed to download
 * a package's PDF itinerary (see TripController::pdfRequest). Read-only
 * beyond deleting a row — leads are only ever created from the public form.
 */
class PdfDownloadLeadResource extends Resource
{
    protected static ?string $model = PdfDownloadLead::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedEnvelopeOpen;

    protected static ?string $navigationLabel = 'PDF Download Leads';

    protected static ?string $modelLabel = 'lead';

    protected static ?string $pluralModelLabel = 'PDF Download Leads';

    protected static string|\UnitEnum|null $navigationGroup = 'Insights';

    protected static ?int $navigationSort = 2;

    public static function canCreate(): bool
    {
        return false;
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->heading('PDF Download Leads')
            ->description('Everyone who entered their email to download a package itinerary PDF.')
            ->columns([
                TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->copyable()
                    ->weight('semibold'),

                TextColumn::make('package_name')
                    ->label('Package')
                    ->placeholder(fn (PdfDownloadLead $record) => $record->package_slug)
                    ->searchable(),

                TextColumn::make('created_at')
                    ->label('Requested')
                    ->since()
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateHeading('No downloads requested yet')
            ->emptyStateDescription('Emails collected before a PDF download will show up here.');
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPdfDownloadLeads::route('/'),
        ];
    }
}
