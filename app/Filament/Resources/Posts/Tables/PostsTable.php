<?php

namespace App\Filament\Resources\Posts\Tables;

use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class PostsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('cover_image')
                    ->label('')
                    ->disk('site')
                    ->square(),

                TextColumn::make('title')
                    ->weight('semibold')
                    ->searchable()
                    ->limit(50),

                TextColumn::make('author_name')
                    ->label('Author')
                    ->toggleable(),

                IconColumn::make('is_published')
                    ->label('Published')
                    ->boolean()
                    ->sortable(),

                TextColumn::make('published_at')
                    ->label('Publish date')
                    ->date('j M Y')
                    ->placeholder('—')
                    ->sortable(),
            ])
            ->filters([
                TernaryFilter::make('is_published')
                    ->label('Published'),
            ])
            ->defaultSort('published_at', 'desc')
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                DeleteBulkAction::make(),
            ])
            ->emptyStateHeading('No posts yet')
            ->emptyStateDescription('Write your first post to start the blog.');
    }
}
