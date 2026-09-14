<?php

namespace App\Filament\Resources\NavItems\Schemas;

use App\Models\NavItem;
use App\Models\Package;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;

class NavItemForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Menu item')
                    ->description('Appears in the top bar on desktop and in the slide-out menu on mobile.')
                    ->columnSpanFull()
                    ->columns(2)
                    ->schema([
                        TextInput::make('label')
                            ->label('Menu label')
                            ->required()
                            ->maxLength(60)
                            ->placeholder('Safaris'),

                        Select::make('type')
                            ->label('Style')
                            ->required()
                            ->live()
                            ->default('mega')
                            ->options([
                                'mega' => 'Dropdown panel (photo + links)',
                                'link' => 'Plain link (no dropdown)',
                            ]),

                        TextInput::make('href')
                            ->label('Links to')
                            ->maxLength(200)
                            ->placeholder('/tours')
                            ->helperText('A site path such as /tours, or a full https:// address.')
                            ->required(fn (Get $get) => $get('type') === 'link')
                            ->visible(fn (Get $get) => $get('type') === 'link')
                            ->columnSpanFull(),
                    ]),

                Section::make('Dropdown panel')
                    ->description('The photo and copy shown on the right of the dropdown.')
                    ->visible(fn (Get $get) => $get('type') === 'mega')
                    ->columnSpanFull()
                    ->columns(2)
                    ->schema([
                        FileUpload::make('image')
                            ->label('Photo')
                            ->image()
                            ->disk('site')
                            ->directory('images')
                            ->visibility('public')
                            ->imageEditor()
                            ->helperText('Leave empty for a narrower, text-only dropdown.')
                            ->columnSpanFull(),

                        TextInput::make('title')
                            ->label('Heading')
                            ->maxLength(120)
                            ->placeholder('Tanzania Safaris')
                            ->helperText('Defaults to the menu label.'),

                        TextInput::make('cta_label')
                            ->label('Button text')
                            ->maxLength(60)
                            ->placeholder('Explore safaris'),

                        Textarea::make('description')
                            ->label('Short paragraph')
                            ->rows(3)
                            ->maxLength(400)
                            ->columnSpanFull(),

                        ...static::actionFields('cta_action', 'cta_target', 'Button goes to'),
                    ]),

                Section::make('Dropdown links')
                    ->description('The list down the left of the dropdown. Also the mobile accordion.')
                    ->visible(fn (Get $get) => $get('type') === 'mega')
                    ->columnSpanFull()
                    ->schema([
                        Repeater::make('links')
                            ->hiddenLabel()
                            ->addActionLabel('Add link')
                            ->reorderable()
                            ->collapsed()
                            ->itemLabel(fn (array $state): ?string => $state['text'] ?? null)
                            ->default([])
                            ->columns(3)
                            ->schema([
                                TextInput::make('text')
                                    ->label('Link text')
                                    ->required()
                                    ->maxLength(80)
                                    ->placeholder('Machame · 7 Days'),

                                ...static::actionFields('action', 'target', 'Goes to'),
                            ]),
                    ]),

                Section::make('Publishing')
                    ->columnSpanFull()
                    ->columns(2)
                    ->schema([
                        Toggle::make('is_active')
                            ->label('Show in menu')
                            ->default(true),

                        TextInput::make('sort_order')
                            ->label('Position')
                            ->numeric()
                            ->default(0)
                            ->helperText('Lower numbers appear first. You can also drag rows on the list.'),
                    ]),
            ]);
    }

    /**
     * An action is stored as a type plus a target rather than as a snippet of
     * JavaScript, so the menu can never carry executable code. The target field
     * swaps between a picker and a free-text box depending on the type; both
     * bind to the same state key, so whichever is showing holds the value.
     *
     * @return array<int, \Filament\Forms\Components\Field>
     */
    protected static function actionFields(string $actionKey, string $targetKey, string $label): array
    {
        return [
            Select::make($actionKey)
                ->label($label)
                ->live()
                ->options(NavItem::ACTIONS)
                ->placeholder('Nothing (plain text)'),

            Select::make($targetKey)
                ->label('Target')
                ->searchable()
                ->visible(fn (Get $get) => filled($get($actionKey)) && $get($actionKey) !== 'url')
                ->required(fn (Get $get) => filled($get($actionKey)) && $get($actionKey) !== 'url')
                ->options(fn (Get $get) => match ($get($actionKey)) {
                    'category' => [
                        'safari' => 'Safaris',
                        'kilimanjaro' => 'Kilimanjaro',
                        'zanzibar' => 'Zanzibar',
                    ],
                    'package' => Package::orderBy('name')->pluck('name', 'slug')->all(),
                    'about' => NavItem::ABOUT_SECTIONS,
                    default => [],
                }),

            TextInput::make($targetKey)
                ->label('URL')
                ->maxLength(200)
                ->placeholder('/tours')
                ->helperText('A site path, or a full https:// address.')
                ->visible(fn (Get $get) => $get($actionKey) === 'url')
                ->required(fn (Get $get) => $get($actionKey) === 'url'),
        ];
    }
}
