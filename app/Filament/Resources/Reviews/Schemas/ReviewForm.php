<?php

namespace App\Filament\Resources\Reviews\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ReviewForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Reviewer')
                    ->columns(3)
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->maxLength(120),

                        TextInput::make('email')
                            ->label('Email')
                            ->email()
                            ->maxLength(160)
                            ->helperText('Never shown on the site — for following up only.'),

                        Select::make('package_id')
                            ->label('Trip taken')
                            ->relationship('package', 'name')
                            ->searchable()
                            ->preload()
                            ->placeholder('Not specified'),

                        DatePicker::make('stayed_at')
                            ->label('Trip date')
                            ->default(now()),
                    ]),

                Section::make('Rating')
                    ->columns(3)
                    ->schema([
                        Select::make('rating')
                            ->label('Overall')
                            ->options([5 => '5 — Excellent', 4 => '4 — Good', 3 => '3 — Average', 2 => '2 — Poor', 1 => '1 — Very poor'])
                            ->required()
                            ->default(5),

                        Select::make('service_rating')
                            ->label('Guide & service')
                            ->options([5 => '5', 4 => '4', 3 => '3', 2 => '2', 1 => '1'])
                            ->placeholder('—'),

                        Select::make('value_rating')
                            ->label('Value for money')
                            ->options([5 => '5', 4 => '4', 3 => '3', 2 => '2', 1 => '1'])
                            ->placeholder('—'),
                    ]),

                Section::make('Review')
                    ->schema([
                        TextInput::make('title')
                            ->required()
                            ->maxLength(160)
                            ->columnSpanFull(),

                        Textarea::make('body')
                            ->label('Review text')
                            ->required()
                            ->rows(5)
                            ->maxLength(2000)
                            ->columnSpanFull(),

                        FileUpload::make('photo')
                            ->image()
                            ->disk('site')
                            ->directory('images/reviews')
                            ->visibility('public')
                            ->imageEditor()
                            ->columnSpanFull(),
                    ]),

                Section::make('Publishing')
                    ->columns(2)
                    ->schema([
                        Toggle::make('is_approved')
                            ->label('Approved')
                            ->helperText('Visible on the /reviews page once on.')
                            ->default(true),

                        Toggle::make('is_featured')
                            ->label('Featured on homepage')
                            ->helperText('Shown in the "Field notes" strip. Only approved reviews are eligible.'),
                    ]),
            ]);
    }
}
