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

                Section::make('Group pricing')
                    ->description('Optional breakdown by group size. When filled in, the package page shows a "price per person by group size" table in the booking card and a full pricing table in the article body.')
                    ->schema([
                        Repeater::make('price_tiers')
                            ->hiddenLabel()
                            ->addActionLabel('Add group size')
                            ->reorderable()
                            ->default([])
                            ->schema([
                                TextInput::make('label')
                                    ->label('Group size')
                                    ->required()
                                    ->maxLength(40)
                                    ->placeholder('1 person'),

                                TextInput::make('park_fees')
                                    ->label('Park fees (USD)')
                                    ->numeric()
                                    ->minValue(0)
                                    ->prefix('$')
                                    ->helperText('Optional — leave blank to hide the breakdown and show only the total.'),

                                TextInput::make('other_costs')
                                    ->label('Other costs (USD)')
                                    ->numeric()
                                    ->minValue(0)
                                    ->prefix('$'),

                                TextInput::make('price')
                                    ->label('Total per person (USD)')
                                    ->required()
                                    ->numeric()
                                    ->minValue(0)
                                    ->prefix('$'),
                            ])
                            ->columns(4),
                    ]),

                Section::make('Overview')
                    ->schema([
                        Textarea::make('quote')
                            ->label('Opening quote')
                            ->rows(4)
                            ->maxLength(1000)
                            ->placeholder($isClimb ? '"The 6-day Lemosho is our shortest version of this route — and I want to be upfront with you about what that means..."' : null)
                            ->helperText('Optional pull-quote shown in a callout box near the top of the page — e.g. a word from Priscus.'),

                        Textarea::make('overview')
                            ->label('Full description')
                            ->rows(6)
                            ->helperText('The long paragraph under the "Overview" heading.')
                            ->columnSpanFull(),
                    ]),

                Section::make('Recommended for')
                    ->description('Short list of traveller profiles this specific itinerary suits — shown right under the opening quote.')
                    ->schema([
                        Repeater::make('recommended_for')
                            ->hiddenLabel()
                            ->simple(
                                TextInput::make('value')
                                    ->required()
                                    ->maxLength(160)
                                    ->placeholder('Experienced trekkers')
                            )
                            ->addActionLabel('Add profile')
                            ->reorderable()
                            ->default([]),
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

                Section::make('Duration comparison')
                    ->description('Only needed when this route is offered in several durations (e.g. Lemosho 6 / 8 / 9 days) — explains how this one differs and who should pick it instead of another.')
                    ->collapsible()
                    ->collapsed()
                    ->schema([
                        Textarea::make('route_comparison_intro')
                            ->label('Intro paragraph')
                            ->rows(3)
                            ->placeholder('The Lemosho Route is available as a 6, 7, or 8-day itinerary...')
                            ->columnSpanFull(),

                        Repeater::make('route_comparison_tiers')
                            ->label('Version-by-version breakdown')
                            ->addActionLabel('Add version')
                            ->reorderable()
                            ->default([])
                            ->schema([
                                Select::make('medal')
                                    ->label('Badge')
                                    ->options(['gold' => 'Gold', 'silver' => 'Silver', 'bronze' => 'Bronze'])
                                    ->native(false)
                                    ->helperText('Shown as a small award icon.'),
                                TextInput::make('title')
                                    ->label('Version title')
                                    ->required()
                                    ->maxLength(120)
                                    ->placeholder('The 6-day version'),
                                TextInput::make('rate')
                                    ->label('Summit success rate')
                                    ->maxLength(60)
                                    ->placeholder('~60%'),
                                Textarea::make('description')
                                    ->label('Description')
                                    ->required()
                                    ->rows(2)
                                    ->columnSpanFull(),
                            ])
                            ->columns(3)
                            ->columnSpanFull(),

                        Repeater::make('choose_this_if')
                            ->label('Choose this version if…')
                            ->simple(TextInput::make('value')->required()->maxLength(160))
                            ->addActionLabel('Add reason')
                            ->reorderable()
                            ->default([]),

                        Repeater::make('choose_other_if')
                            ->label('Choose a different version if…')
                            ->simple(TextInput::make('value')->required()->maxLength(160))
                            ->addActionLabel('Add reason')
                            ->reorderable()
                            ->default([]),

                        TextInput::make('choose_other_label')
                            ->label('Other version(s) label')
                            ->maxLength(60)
                            ->placeholder('the 7 or 8-day')
                            ->helperText('Used in the "Choose ___ instead" heading.')
                            ->columnSpanFull(),
                    ]),

                Section::make('Itinerary')
                    ->description('One entry per day, in order. Every day is expanded by default on the site.')
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

                                TextInput::make('distance')
                                    ->label('Distance')
                                    ->maxLength(40)
                                    ->placeholder('18 km'),

                                TextInput::make('time')
                                    ->label('Hiking time')
                                    ->maxLength(40)
                                    ->placeholder('8–10 hours'),

                                TextInput::make('vegetation')
                                    ->label('Vegetation / zone')
                                    ->maxLength(80)
                                    ->placeholder('Rainforest, Moorland & Shira Plateau'),

                                TextInput::make('difficulty')
                                    ->label('Day difficulty')
                                    ->maxLength(80)
                                    ->placeholder('Hard — the longest, most demanding day of the route'),

                                TextInput::make('stay')
                                    ->label('Stay')
                                    ->maxLength(120)
                                    ->placeholder('Machame Camp'),

                                TextInput::make('meals')
                                    ->label('Meals')
                                    ->maxLength(40)
                                    ->placeholder('B, L, D'),

                                Textarea::make('note')
                                    ->label('Callout note (optional)')
                                    ->rows(2)
                                    ->placeholder('Perfect Kilimanjaro note: Rest this evening...')
                                    ->helperText('Shown as a highlighted box under the day. Leave blank to omit.')
                                    ->columnSpanFull(),
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

                Section::make('Tipping guide')
                    ->description('Optional table shown after Included/Not included.')
                    ->schema([
                        Repeater::make('tipping_table')
                            ->hiddenLabel()
                            ->addActionLabel('Add crew member')
                            ->reorderable()
                            ->default([])
                            ->schema([
                                TextInput::make('role')
                                    ->label('Crew member')
                                    ->required()
                                    ->maxLength(60)
                                    ->placeholder('Porters'),

                                TextInput::make('tip')
                                    ->label('Recommended tip per day')
                                    ->required()
                                    ->maxLength(40)
                                    ->placeholder('$6 – $10'),
                            ])
                            ->columns(2),
                    ]),

                Section::make('Optional extras')
                    ->description('Add-ons clients can book on top of this package.')
                    ->schema([
                        Repeater::make('optional_extras')
                            ->hiddenLabel()
                            ->simple(
                                TextInput::make('value')
                                    ->required()
                                    ->maxLength(200)
                                    ->placeholder('Private Portable Toilet — $100 per group')
                            )
                            ->addActionLabel('Add extra')
                            ->reorderable()
                            ->default([]),
                    ]),

                Section::make('Closing note')
                    ->description('A personal closing letter shown at the end of the page, signed by Priscus.')
                    ->schema([
                        Textarea::make('closing_note')
                            ->hiddenLabel()
                            ->rows(8)
                            ->placeholder('I will be honest with you — as I always am...'),
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
