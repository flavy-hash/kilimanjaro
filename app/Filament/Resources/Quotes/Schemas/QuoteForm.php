<?php

namespace App\Filament\Resources\Quotes\Schemas;

use App\Enums\QuoteStatus;
use App\Models\Package;
use App\Models\Quote;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;

class QuoteForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Customer')
                    ->columns(2)
                    ->schema([
                        TextInput::make('customer_name')
                            ->label('Name')
                            ->required()
                            ->maxLength(120),

                        TextInput::make('customer_email')
                            ->label('Email')
                            ->email()
                            ->required()
                            ->maxLength(160),

                        TextInput::make('customer_phone')
                            ->label('Phone / WhatsApp')
                            ->maxLength(40),

                        TextInput::make('customer_country')
                            ->label('Country')
                            ->maxLength(80),
                    ]),

                Section::make('Quote')
                    ->columns(3)
                    ->schema([
                        TextInput::make('reference')
                            ->disabled()
                            ->dehydrated(false)
                            ->placeholder('Generated automatically after saving'),

                        Select::make('status')
                            ->options(QuoteStatus::class)
                            ->required()
                            ->default(QuoteStatus::Draft),

                        DatePicker::make('valid_until')
                            ->default(now()->addDays(\App\Models\Setting::quoteValidityDays())),

                        Toggle::make('show_bank_details')
                            ->label('Show bank details on the PDF')
                            ->default(true)
                            ->columnSpanFull(),
                    ]),

                Section::make('Trip')
                    ->columns(4)
                    ->schema([
                        Select::make('package_id')
                            ->label('Product')
                            ->relationship('package', 'name')
                            ->searchable()
                            ->preload()
                            ->live()
                            ->columnSpan(2)
                            ->afterStateUpdated(function (Get $get, Set $set, ?int $state) {
                                if (! $state) {
                                    return;
                                }

                                $package = Package::find($state);

                                if (! $package) {
                                    return;
                                }

                                $set('items', Quote::itemsForPackage(
                                    $package,
                                    (int) ($get('adults') ?: 1),
                                    (int) ($get('children') ?: 0)
                                ));

                                if ($start = $get('travel_start')) {
                                    $end = Quote::travelEndFor($package, \Illuminate\Support\Carbon::parse($start));
                                    $set('travel_end', $end?->toDateString());
                                }
                            }),

                        TextInput::make('adults')
                            ->numeric()
                            ->minValue(1)
                            ->maxValue(30)
                            ->default(1)
                            ->required()
                            ->live(onBlur: true)
                            ->afterStateUpdated(function (Get $get, Set $set, ?string $state) {
                                self::refillItems($get, $set);
                            }),

                        TextInput::make('children')
                            ->label('Children (under 16)')
                            ->numeric()
                            ->minValue(0)
                            ->maxValue(30)
                            ->default(0)
                            ->live(onBlur: true)
                            ->afterStateUpdated(function (Get $get, Set $set, ?string $state) {
                                self::refillItems($get, $set);
                            }),

                        DatePicker::make('travel_start')
                            ->label('Start date')
                            ->live()
                            ->afterStateUpdated(function (Get $get, Set $set, ?string $state) {
                                if (! $state || ! $get('package_id')) {
                                    return;
                                }

                                $package = Package::find($get('package_id'));
                                $end = $package ? Quote::travelEndFor($package, \Illuminate\Support\Carbon::parse($state)) : null;
                                $set('travel_end', $end?->toDateString());
                            }),

                        DatePicker::make('travel_end')
                            ->label('End date'),
                    ]),

                Section::make('Itemised costs')
                    ->schema([
                        Repeater::make('items')
                            ->hiddenLabel()
                            ->live()
                            ->default([])
                            ->addActionLabel('Add line item')
                            ->schema([
                                TextInput::make('description')
                                    ->required()
                                    ->columnSpan(2),

                                TextInput::make('quantity')
                                    ->numeric()
                                    ->minValue(1)
                                    ->default(1)
                                    ->required(),

                                TextInput::make('unit_price')
                                    ->label('Unit price (USD)')
                                    ->numeric()
                                    ->minValue(0)
                                    ->prefix('$')
                                    ->required(),
                            ])
                            ->columns(4),
                    ]),

                Section::make('Totals')
                    ->columns(3)
                    ->schema([
                        Placeholder::make('subtotal_display')
                            ->label('Subtotal')
                            ->content(fn (Get $get) => 'US$ '.number_format(self::sumItems($get('items')), 2)),

                        TextInput::make('discount')
                            ->label('Discount (USD)')
                            ->numeric()
                            ->minValue(0)
                            ->prefix('$')
                            ->default(0)
                            ->live(onBlur: true),

                        Placeholder::make('total_display')
                            ->label('Total')
                            ->content(fn (Get $get) => 'US$ '.number_format(
                                max(0, self::sumItems($get('items')) - (float) ($get('discount') ?: 0)),
                                2
                            )),
                    ]),

                Section::make('Notes')
                    ->schema([
                        Textarea::make('notes')
                            ->hiddenLabel()
                            ->rows(3),
                    ]),
            ]);
    }

    private static function refillItems(Get $get, Set $set): void
    {
        $packageId = $get('package_id');

        if (! $packageId) {
            return;
        }

        $package = Package::find($packageId);

        if (! $package) {
            return;
        }

        $set('items', Quote::itemsForPackage(
            $package,
            (int) ($get('adults') ?: 1),
            (int) ($get('children') ?: 0)
        ));
    }

    private static function sumItems(?array $items): float
    {
        return collect($items ?? [])->sum(
            fn (array $item) => (float) ($item['quantity'] ?? 0) * (float) ($item['unit_price'] ?? 0)
        );
    }
}
