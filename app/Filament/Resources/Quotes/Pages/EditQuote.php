<?php

namespace App\Filament\Resources\Quotes\Pages;

use App\Enums\QuoteStatus;
use App\Filament\Resources\Quotes\QuoteResource;
use App\Mail\NewQuoteReceived;
use App\Models\Quote;
use App\Services\QuotePdfBuilder;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Mail;

class EditQuote extends EditRecord
{
    protected static string $resource = QuoteResource::class;

    protected function getHeaderActions(): array
    {
        return [
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
                        Mail::to($record->customer_email)->send(new NewQuoteReceived($record));

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
        ];
    }
}
