<?php

namespace App\Filament\Resources\NavItems\Tables;

use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class NavItemsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image')
                    ->label('')
                    ->disk('site')
                    ->height(38)
                    ->width(57)
                    ->placeholder('—'),

                TextColumn::make('label')
                    ->label('Menu item')
                    ->weight('semibold')
                    ->description(fn ($record) => $record->type === 'link' ? $record->href : $record->title)
                    ->searchable()
                    ->sortable(),

                TextColumn::make('type')
                    ->label('Style')
                    ->badge()
                    ->color(fn (string $state) => $state === 'mega' ? 'warning' : 'gray')
                    ->formatStateUsing(fn (string $state) => $state === 'mega' ? 'Dropdown' : 'Plain link'),

                TextColumn::make('links')
                    ->label('Links')
                    ->badge()
                    ->color('gray')
                    ->state(fn ($record) => collect($record->links ?? [])->pluck('text')->all())
                    ->limitList(2)
                    ->expandableLimitedList()
                    ->placeholder('—'),

                IconColumn::make('is_active')
                    ->label('Live')
                    ->boolean()
                    ->sortable(),

                TextColumn::make('sort_order')
                    ->label('Position')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TernaryFilter::make('is_active')
                    ->label('Shown in menu'),
            ])
            ->defaultSort('sort_order')
            ->reorderable('sort_order')
            ->recordActions([
                Action::make('preview')
                    ->label('Preview')
                    ->icon('heroicon-m-arrow-top-right-on-square')
                    ->color('gray')
                    ->url(fn () => url('/'))
                    ->openUrlInNewTab(),
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateHeading('No menu items yet')
            ->emptyStateDescription('The site is showing its built-in menu. Add items here to take it over.');
    }
}
