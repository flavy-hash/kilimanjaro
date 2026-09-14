<?php

namespace App\Filament\Resources\Packages\Tables;

use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class PackagesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image')
                    ->label('')
                    ->disk('site')
                    ->height(42)
                    ->width(63),

                TextColumn::make('name')
                    ->label('Package')
                    ->weight('semibold')
                    ->description(fn ($record) => $record->nickname)
                    ->searchable()
                    ->sortable(),

                TextColumn::make('days')
                    ->label('Days')
                    ->suffix(' d')
                    ->sortable(),

                TextColumn::make('price')
                    ->label('From')
                    ->money('USD')
                    ->sortable(),

                TextColumn::make('difficulty')
                    ->badge()
                    ->color('gray')
                    ->toggleable(),

                TextColumn::make('stays.name')
                    ->label('Stays')
                    ->badge()
                    ->color('gray')
                    ->limitList(2)
                    ->expandableLimitedList()
                    ->placeholder('—')
                    ->toggleable(),

                TextColumn::make('inquiries_count')
                    ->label('Inquiries')
                    ->counts('inquiries')
                    ->badge()
                    ->color(fn (int $state) => $state > 0 ? 'warning' : 'gray'),

                IconColumn::make('is_published')
                    ->label('Live')
                    ->boolean()
                    ->sortable(),

                TextColumn::make('sort_order')
                    ->label('Order')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TernaryFilter::make('is_published')
                    ->label('Published'),
            ])
            ->defaultSort('sort_order')
            ->reorderable('sort_order')
            ->recordActions([
                Action::make('view')
                    ->label('View')
                    ->icon('heroicon-m-arrow-top-right-on-square')
                    ->color('gray')
                    ->url(fn ($record) => url($record->slug))
                    ->openUrlInNewTab(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
