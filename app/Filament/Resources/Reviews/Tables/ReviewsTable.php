<?php

namespace App\Filament\Resources\Reviews\Tables;

use App\Models\Review;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Collection;

class ReviewsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Reviewer')
                    ->weight('semibold')
                    ->description(fn (Review $record) => $record->title)
                    ->searchable(['name', 'title', 'body']),

                TextColumn::make('package.name')
                    ->label('Trip')
                    ->placeholder('— general')
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('rating')
                    ->label('Rating')
                    ->badge()
                    ->color(fn (int $state) => match (true) {
                        $state >= 5 => 'success',
                        $state >= 4 => 'info',
                        $state >= 3 => 'warning',
                        default => 'danger',
                    })
                    ->formatStateUsing(fn (int $state) => str_repeat('★', $state))
                    ->sortable(),

                IconColumn::make('is_approved')
                    ->label('Approved')
                    ->boolean()
                    ->sortable(),

                IconColumn::make('is_featured')
                    ->label('Featured')
                    ->boolean()
                    ->sortable(),

                TextColumn::make('stayed_at')
                    ->label('Trip date')
                    ->date('j M Y')
                    ->placeholder('—')
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Submitted')
                    ->since()
                    ->sortable(),
            ])
            ->filters([
                TernaryFilter::make('is_approved')
                    ->label('Approved'),

                TernaryFilter::make('is_featured')
                    ->label('Featured'),

                SelectFilter::make('package')
                    ->relationship('package', 'name')
                    ->searchable()
                    ->preload(),
            ])
            ->defaultSort('created_at', 'desc')
            ->recordActions([
                ActionGroup::make([
                    EditAction::make(),

                    Action::make('approve')
                        ->label('Approve')
                        ->icon('heroicon-m-check-badge')
                        ->color('success')
                        ->visible(fn (Review $record) => ! $record->is_approved)
                        ->action(fn (Review $record) => self::setApproved($record, true)),

                    Action::make('unapprove')
                        ->label('Unapprove')
                        ->icon('heroicon-m-eye-slash')
                        ->color('gray')
                        ->visible(fn (Review $record) => $record->is_approved)
                        ->action(fn (Review $record) => self::setApproved($record, false)),

                    Action::make('feature')
                        ->label('Feature on homepage')
                        ->icon('heroicon-m-star')
                        ->color('warning')
                        ->visible(fn (Review $record) => $record->is_approved && ! $record->is_featured)
                        ->action(fn (Review $record) => self::setFeatured($record, true)),

                    Action::make('unfeature')
                        ->label('Remove from homepage')
                        ->icon('heroicon-m-star')
                        ->color('gray')
                        ->visible(fn (Review $record) => $record->is_featured)
                        ->action(fn (Review $record) => self::setFeatured($record, false)),

                    DeleteAction::make(),
                ]),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    BulkAction::make('bulk_approve')
                        ->label('Approve')
                        ->icon('heroicon-m-check-badge')
                        ->color('success')
                        ->deselectRecordsAfterCompletion()
                        ->action(fn (Collection $records) => self::bulkSet($records, ['is_approved' => true], 'approved')),

                    BulkAction::make('bulk_unapprove')
                        ->label('Unapprove')
                        ->icon('heroicon-m-eye-slash')
                        ->color('gray')
                        ->deselectRecordsAfterCompletion()
                        ->action(fn (Collection $records) => self::bulkSet($records, ['is_approved' => false], 'unapproved')),

                    DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateHeading('No reviews yet')
            ->emptyStateDescription('Submissions from the public /reviews page land here awaiting approval.');
    }

    protected static function setApproved(Review $record, bool $value): void
    {
        $record->update(['is_approved' => $value, 'is_featured' => $value ? $record->is_featured : false]);

        Notification::make()
            ->title($value ? 'Review approved' : 'Review unapproved')
            ->body($record->name.' — '.$record->title)
            ->success()
            ->send();
    }

    protected static function setFeatured(Review $record, bool $value): void
    {
        $record->update(['is_featured' => $value]);

        Notification::make()
            ->title($value ? 'Added to homepage' : 'Removed from homepage')
            ->body($record->name.' — '.$record->title)
            ->success()
            ->send();
    }

    /**
     * @param  array<string, mixed>  $attributes
     */
    protected static function bulkSet(Collection $records, array $attributes, string $label): void
    {
        $records->each->update($attributes);

        Notification::make()
            ->title($records->count().' review(s) '.$label)
            ->success()
            ->send();
    }
}
