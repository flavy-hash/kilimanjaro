<?php

namespace App\Filament\Resources\Inquiries\Tables;

use App\Models\Inquiry;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Collection;

class InquiriesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('reference')
                    ->label('Ref')
                    ->badge()
                    ->color('warning')
                    ->searchable()
                    ->copyable(),

                TextColumn::make('name')
                    ->label('Customer')
                    ->weight('semibold')
                    ->description(fn (Inquiry $record) => $record->email)
                    ->searchable(['name', 'email']),

                TextColumn::make('package.name')
                    ->label('Package')
                    ->placeholder('— not specified')
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('start_date')
                    ->label('Start')
                    ->date('j M Y')
                    ->placeholder('—')
                    ->sortable(),

                TextColumn::make('group_size')
                    ->label('Pax')
                    ->alignCenter(),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn (string $state) => Inquiry::STATUSES[$state] ?? $state)
                    ->color(fn (string $state) => self::statusColour($state))
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Received')
                    ->since()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options(Inquiry::STATUSES)
                    ->multiple(),

                SelectFilter::make('package')
                    ->relationship('package', 'name')
                    ->searchable()
                    ->preload(),
            ])
            ->defaultSort('created_at', 'desc')
            ->recordActions([
                ActionGroup::make([
                    ViewAction::make(),
                    EditAction::make(),

                    // Statuses as one-click items, grouped inside the same menu.
                    ActionGroup::make(self::statusActions())
                        ->dropdown(false),

                    ActionGroup::make([
                        Action::make('email')
                            ->label('Email customer')
                            ->icon('heroicon-m-envelope')
                            ->url(fn (Inquiry $record) => 'mailto:'.$record->email.'?subject='.rawurlencode('Your Safiri enquiry '.$record->reference))
                            ->openUrlInNewTab(),

                        DeleteAction::make(),
                    ])->dropdown(false),
                ]),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    ...self::statusBulkActions(),
                    DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateHeading('No enquiries yet')
            ->emptyStateDescription('Booking requests from the website land here.');
    }

    /**
     * One "Mark as …" action per status, hidden for the status the record is
     * already in so the menu only offers real transitions.
     *
     * @return array<int, Action>
     */
    protected static function statusActions(): array
    {
        return collect(Inquiry::STATUSES)
            ->map(fn (string $label, string $value) => Action::make('status_'.$value)
                ->label('Mark as '.$label)
                ->icon(self::statusIcon($value))
                ->color(self::statusColour($value))
                ->hidden(fn (Inquiry $record) => $record->status === $value)
                ->action(function (Inquiry $record) use ($value, $label) {
                    $record->update(['status' => $value]);

                    Notification::make()
                        ->title("Marked as {$label}")
                        ->body($record->reference.' — '.$record->name)
                        ->success()
                        ->send();
                }))
            ->values()
            ->all();
    }

    /**
     * @return array<int, BulkAction>
     */
    protected static function statusBulkActions(): array
    {
        return collect(Inquiry::STATUSES)
            ->map(fn (string $label, string $value) => BulkAction::make('bulk_status_'.$value)
                ->label('Mark as '.$label)
                ->icon(self::statusIcon($value))
                ->color(self::statusColour($value))
                ->deselectRecordsAfterCompletion()
                ->action(function (Collection $records) use ($value, $label) {
                    $records->each->update(['status' => $value]);

                    Notification::make()
                        ->title($records->count().' marked as '.$label)
                        ->success()
                        ->send();
                }))
            ->values()
            ->all();
    }

    protected static function statusColour(string $status): string
    {
        return match ($status) {
            'new' => 'warning',
            'contacted', 'quoted' => 'info',
            'booked' => 'success',
            default => 'gray',
        };
    }

    protected static function statusIcon(string $status): string
    {
        return match ($status) {
            'new' => 'heroicon-m-sparkles',
            'contacted' => 'heroicon-m-phone',
            'quoted' => 'heroicon-m-document-text',
            'booked' => 'heroicon-m-check-badge',
            default => 'heroicon-m-archive-box',
        };
    }
}
