<?php

namespace App\Filament\Resources\Accommodations\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class AccommodationsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image')
                    ->label('')
                    ->disk('site')
                    ->height(44)
                    ->width(66),

                TextColumn::make('name')
                    ->label('Property')
                    ->weight('semibold')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('stars')
                    ->label('Rating')
                    ->formatStateUsing(fn (?int $state) => str_repeat('★', (int) $state))
                    ->color('warning')
                    ->sortable(),

                TextColumn::make('place')
                    ->label('Location')
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('class')
                    ->label('Class')
                    ->badge()
                    ->searchable(),

                TextColumn::make('packages_count')
                    ->label('Used by')
                    ->counts('packages')
                    ->suffix(' packages')
                    ->badge()
                    ->color('gray'),

                TextColumn::make('updated_at')
                    ->dateTime('j M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('stars')
                    ->options([1 => '1 star', 2 => '2 stars', 3 => '3 stars', 4 => '4 stars', 5 => '5 stars']),
            ])
            ->defaultSort('name')
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
