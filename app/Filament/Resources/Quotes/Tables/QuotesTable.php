<?php

namespace App\Filament\Resources\Quotes\Tables;

use App\Enums\QuoteSource;
use App\Enums\QuoteStatus;
use App\Mail\NewQuoteReceived;
use App\Models\Quote;
use App\Services\QuotePdfBuilder;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class QuotesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('reference')
                    ->label('Ref')
                    ->badge()
                    ->color('gray')
                    ->searchable()
                    ->copyable(),

                TextColumn::make('customer_name')
                    ->label('Customer')
                    ->weight('semibold')
                    ->description(fn (Quote $record) => $record->customer_email)
                    ->searchable(['customer_name', 'customer_email']),

                TextColumn::make('package.name')
                    ->label('Product')
                    ->placeholder('— not specified')
                    ->searchable(),

                TextColumn::make('total')
                    ->label('Total')
                    ->state(fn (Quote $record) => $record->money($record->total())),

                TextColumn::make('status')
                    ->badge()
                    ->sortable(),

                TextColumn::make('source')
                    ->badge()
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Date')
                    ->since()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options(QuoteStatus::class),

                SelectFilter::make('source')
                    ->options(QuoteSource::class),
            ])
            ->defaultSort('created_at', 'desc')
            ->recordActions([
                EditAction::make(),

                Action::make('download')
                    ->label('Download PDF')
                    ->icon('heroicon-m-arrow-down-tray')
                    ->color('gray')
                    ->action(function (Quote $record) {
                        $pdf = app(QuotePdfBuilder::class)->build($record);

                        return response()->streamDownload(
                            function () use ($pdf) {
                                echo $pdf->output();
                            },
                            $record->pdfFilename()
                        );
                    }),

                Action::make('emailCustomer')
                    ->label('Email to customer')
                    ->icon('heroicon-m-envelope')
                    ->color('gray')
                    ->requiresConfirmation()
                    ->modalDescription(fn (Quote $record) => "Send the quote PDF to {$record->customer_email}?")
                    ->action(function (Quote $record) {
                        try {
                            \Illuminate\Support\Facades\Mail::to($record->customer_email)->send(new NewQuoteReceived($record));

                            $record->update(['status' => QuoteStatus::Sent]);

                            Notification::make()
                                ->success()
                                ->title('Quote emailed')
                                ->body("Sent to {$record->customer_email}.")
                                ->send();
                        } catch (\Throwable $e) {
                            report($e);

                            Notification::make()
                                ->danger()
                                ->title('Could not send the email')
                                ->body($e->getMessage())
                                ->send();
                        }
                    }),

                DeleteAction::make(),
            ])
            ->emptyStateHeading('No quotes yet')
            ->emptyStateDescription('Instant quotes requested on the website, and quotes you create by hand, land here.');
    }
}
