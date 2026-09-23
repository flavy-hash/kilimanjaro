<?php

namespace App\Filament\Resources\Packages\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class PackageForm
{
    /**
     * One schema shared by every package area (Safaris, Southern Circuit,
     * Kilimanjaro, Zanzibar). The category/circuit are fixed per area so the
     * admin never has to pick them.
     */
    public static function configure(Schema $schema, string $category, ?string $circuit = null): Schema
    {
        $isClimb = $category === 'kilimanjaro';

        return $schema
            ->components([
                Hidden::make('category')->default($category),
                Hidden::make('circuit')->default($circuit),

                Section::make('Headline')
                    ->description('Shown in the hero and on every package card.')
                    ->columns(2)
                    ->schema([
                        TextInput::make('name')
                            ->label('Package name')
                            ->required()
                            ->maxLength(160)
                            ->live(onBlur: true)
                            ->afterStateUpdated(function (Get $get, Set $set, ?string $state) {
                                // Only auto-fill the slug while it is still empty, so
                                // renaming a live package never breaks its URL.
                                if (filled($state) && blank($get('slug'))) {
                                    $set('slug', Str::slug($state));
                                }
                            })
                            ->placeholder($isClimb ? 'Machame Route' : 'Northern Circuit Safari'),

                        TextInput::make('slug')
                            ->label('URL slug')
                            ->required()
                            ->maxLength(160)
                            ->unique(ignoreRecord: true)
                            ->rules(['regex:/^[a-z0-9-]+$/'])
                            ->helperText(fn (?string $state) => 'Page will live at /'.($state ?: 'your-slug')),

                        TextInput::make('nickname')
                            ->label('Tagline')
                            ->maxLength(160)
                            ->placeholder($isClimb ? 'the Whiskey Route' : 'Serengeti · Ngorongoro')
                            ->helperText('Small italic line above the title.'),

                        TextInput::make('location')
                            ->label('Location line')
                            ->maxLength(200)
                            ->placeholder($isClimb ? 'Kilimanjaro National Park · Moshi' : 'Tarangire · Serengeti · Ngorongoro'),

                        FileUpload::make('image')
                            ->label('Hero image')
                            ->image()
                            ->disk('site')
                            ->directory('images')
                            ->visibility('public')
                            ->imageEditor()
                            ->columnSpanFull(),

                        Textarea::make('blurb')
                            ->label('Short summary')
                            ->rows(2)
                            ->maxLength(400)
                            ->helperText('One sentence. Used on cards, the hero and social previews.')
                            ->columnSpanFull(),
                    ]),

                Section::make('Quick facts')
                    ->description('These fill the booking card on the package page.')
                    ->columns(3)
                    ->schema([
                        TextInput::make('days')
                            ->label('Days')
                            ->numeric()
                            ->minValue(1)
                            ->maxValue(60)
                            ->required()
                            ->default(5),

                        TextInput::make('price')
                            ->label('Price from (USD)')
                            ->numeric()
                            ->minValue(0)
                            ->required()
                            ->prefix('$')
                            ->default(1000),

                        TextInput::make('group')
                            ->label('Group size')
                            ->maxLength(40)
                            ->placeholder('Max 6'),

                        TextInput::make('difficulty')
                            ->label('Difficulty')
                            ->maxLength(40)
                            ->placeholder($isClimb ? 'Challenging' : 'Easy'),

                        TextInput::make('best_time')
                            ->label('Best time')
                            ->maxLength(60)
                            ->placeholder('Jun–Oct'),

                        TextInput::make('start_point')
                            ->label('Starts & ends')
                            ->maxLength(60)
                            ->placeholder($isClimb ? 'Moshi' : 'Arusha'),

                        TextInput::make('tag')
                            ->label('Tier badge')
                            ->maxLength(40)
                            ->placeholder('Classic')
                            ->helperText('Card badge, e.g. Classic / Mid-range / Luxury.'),

                        TextInput::make('rate')
                            ->label('Accent badge')
                            ->maxLength(60)
                            ->placeholder($isClimb ? '85% summit rate' : '96% wildlife sightings'),

                        TextInput::make('rating')
                            ->label('Rating')
                            ->numeric()
                            ->step(0.1)
                            ->minValue(0)
                            ->maxValue(5)
                            ->default(4.9),

                        TextInput::make('reviews')
                            ->label('Review count')
                            ->numeric()
                            ->minValue(0)
                            ->default(0),
                    ]),

                Section::make('Overview')
                    ->schema([
                        Textarea::make('overview')
                            ->label('Full description')
                            ->rows(6)
                            ->helperText('The long paragraph under the "Overview" heading.'),
                    ]),

                Section::make('Highlights')
                    ->description('Shown as the two-column icon grid.')
                    ->schema([
                        Repeater::make('highlights')
                            ->hiddenLabel()
                            ->simple(
                                TextInput::make('value')
                                    ->required()
                                    ->maxLength(200)
                                    ->placeholder('Ngorongoro Crater floor at first light')
                            )
                            ->addActionLabel('Add highlight')
                            ->reorderable()
                            ->default([]),
                    ]),

                Section::make('Itinerary')
                    ->description('One entry per day, in order. Opens as an accordion on the site.')
                    ->schema([
                        Repeater::make('itinerary')
                            ->hiddenLabel()
                            ->addActionLabel('Add day')
                            ->reorderable()
                            ->collapsible()
                            ->itemLabel(fn (array $state): ?string => $state['t'] ?? null)
                            ->default([])
                            ->schema([
                                TextInput::make('t')
                                    ->label('Day title')
                                    ->required()
                                    ->maxLength(160)
                                    ->placeholder($isClimb ? 'Machame Gate to Machame Camp' : 'Arrival & Tarangire')
                                    ->columnSpanFull(),

                                Textarea::make('d')
                                    ->label('Description')
                                    ->rows(3)
                                    ->required()
                                    ->columnSpanFull(),

                                TextInput::make('elev')
                                    ->label('Elevation')
                                    ->maxLength(80)
                                    ->placeholder('1,640 → 2,835 m')
                                    ->helperText('Optional — leave blank for non-climbing days.'),

                                TextInput::make('stay')
                                    ->label('Stay')
                                    ->maxLength(120)
                                    ->placeholder('Machame Camp'),

                                TextInput::make('meals')
                                    ->label('Meals')
                                    ->maxLength(40)
                                    ->placeholder('B, L, D'),
                            ])
                            ->columns(3),
                    ]),

                Section::make("Where you'll stay")
                    ->columns(2)
                    ->schema([
                        Select::make('stays')
                            ->label('Accommodation')
                            ->relationship('stays', 'name')
                            ->multiple()
                            ->searchable()
                            ->preload()
                            ->createOptionForm(fn (Schema $s) => \App\Filament\Resources\Accommodations\Schemas\AccommodationForm::configure($s)->getComponents())
                            ->helperText('Pick every property used on this trip — each one gets its own card on the package page. Manage the full list under Accommodation.')
                            ->columnSpanFull(),

                        TextInput::make('accommodation')
                            ->label('Summary line')
                            ->maxLength(200)
                            ->placeholder('Permanent lodges & tented camps')
                            ->helperText('Short text shown as a badge next to the stay card.'),
                    ]),

                Section::make('Gallery')
                    ->schema([
                        FileUpload::make('gallery')
                            ->hiddenLabel()
                            ->image()
                            ->multiple()
                            ->reorderable()
                            ->appendFiles()
                            ->disk('site')
                            ->directory('images')
                            ->visibility('public')
                            ->panelLayout('grid')
                            ->helperText('Three photos works best — they render as a grid with a lightbox.'),
                    ]),

                Section::make('Video')
                    ->description('Shown as a "watch first" card on the package page. Left blank, the page shows no video.')
                    ->schema([
                        TextInput::make('video_youtube_id')
                            ->label('YouTube video ID')
                            ->maxLength(20)
                            ->helperText('The id from the video\'s URL — e.g. for youtube.com/watch?v=dQw4w9WgXcQ, that\'s "dQw4w9WgXcQ".'),
                    ]),

                Section::make('Included & not included')
                    ->columns(2)
                    ->schema([
                        Repeater::make('included')
                            ->label("What's included")
                            ->simple(
                                TextInput::make('value')
                                    ->required()
                                    ->maxLength(200)
                                    ->placeholder('All park and conservation fees')
                            )
                            ->addActionLabel('Add item')
                            ->reorderable()
                            ->default([]),

                        Repeater::make('excluded')
                            ->label("What's not included")
                            ->simple(
                                TextInput::make('value')
                                    ->required()
                                    ->maxLength(200)
                                    ->placeholder('International flights and visa fees')
                            )
                            ->addActionLabel('Add item')
                            ->reorderable()
                            ->default([]),
                    ]),

                Section::make('Publishing')
                    ->columns(2)
                    ->schema([
                        Toggle::make('is_published')
                            ->label('Published')
                            ->default(true)
                            ->helperText('Unpublished packages are hidden from the site and return 404.'),

                        TextInput::make('sort_order')
                            ->label('Sort order')
                            ->numeric()
                            ->default(0)
                            ->helperText('Lower numbers appear first.'),
                    ]),
            ]);
    }
}
