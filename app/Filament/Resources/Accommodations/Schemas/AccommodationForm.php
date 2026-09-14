<?php

namespace App\Filament\Resources\Accommodations\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class AccommodationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Property')
                    ->columns(2)
                    ->schema([
                        TextInput::make('name')
                            ->label('Property name')
                            ->required()
                            ->maxLength(160)
                            ->placeholder('Serengeti Tented Camp')
                            ->columnSpanFull(),

                        TextInput::make('place')
                            ->label('Location')
                            ->maxLength(160)
                            ->placeholder('Central Serengeti')
                            ->helperText('Shown as a badge on the package page.'),

                        Select::make('stars')
                            ->label('Star rating')
                            ->options([1 => '1 star', 2 => '2 stars', 3 => '3 stars', 4 => '4 stars', 5 => '5 stars'])
                            ->default(3)
                            ->required(),

                        TextInput::make('class')
                            ->label('Class / board basis')
                            ->maxLength(160)
                            ->placeholder('Mid-range · Full board')
                            ->helperText('Appears on the photo badge, e.g. "Luxury · All inclusive".')
                            ->columnSpanFull(),

                        FileUpload::make('image')
                            ->label('Photo')
                            ->image()
                            ->disk('site')
                            ->directory('images')
                            ->visibility('public')
                            ->imageEditor()
                            ->columnSpanFull(),
                    ]),

                Section::make('Detail')
                    ->schema([
                        Textarea::make('note')
                            ->label('Description')
                            ->rows(3)
                            ->maxLength(600)
                            ->placeholder('Permanent tented camp with en-suite bathrooms, moved seasonally to stay close to the herds.'),

                        Repeater::make('amenities')
                            ->label('Amenities')
                            ->simple(
                                TextInput::make('value')
                                    ->required()
                                    ->maxLength(60)
                                    ->placeholder('Hot showers')
                            )
                            ->addActionLabel('Add amenity')
                            ->reorderable()
                            ->default([]),
                    ]),
            ]);
    }
}
