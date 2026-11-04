<?php

namespace App\Notifications;

use App\Filament\Resources\Quotes\QuoteResource;
use App\Models\Quote;
use Filament\Notifications\Notification as FilamentNotification;
use Filament\Notifications\Actions\Action;
use Illuminate\Notifications\Notification;

class NewInstantQuote extends Notification
{
    public function __construct(public Quote $quote)
    {
        //
    }

    /** @return array<int, string> */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return FilamentNotification::make()
            ->title("New instant quote {$this->quote->reference}")
            ->body(sprintf(
                '%s · %s · %s',
                $this->quote->customer_name,
                $this->quote->package?->name ?? 'No trip specified',
                $this->quote->money($this->quote->total())
            ))
            ->actions([
                Action::make('open')
                    ->label('Open quote')
                    ->url(QuoteResource::getUrl('edit', ['record' => $this->quote]))
                    ->markAsRead(),
            ])
            ->getDatabaseMessage();
    }
}
