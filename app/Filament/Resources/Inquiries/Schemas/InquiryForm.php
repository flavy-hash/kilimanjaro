<?php

namespace App\Filament\Resources\Inquiries\Schemas;

use App\Models\Inquiry;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class InquiryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Enquiry')
                    ->columns(2)
                    ->schema([
                        TextInput::make('reference')
                            ->label('Reference')
                            ->disabled()
                            ->dehydrated(false)
                            ->placeholder('Generated on submit'),

                        Select::make('status')
                            ->label('Status')
                            ->options(Inquiry::STATUSES)
                            ->default('new')
                            ->required()
                            ->native(false),

                        Select::make('package_id')
                            ->label('Package')
                            ->relationship('package', 'name')
                            ->searchable()
                            ->preload()
                            ->columnSpanFull(),
                    ]),

                Section::make('Customer')
                    ->columns(2)
                    ->schema([
                        TextInput::make('name')->required()->maxLength(120),
                        TextInput::make('email')->email()->required()->maxLength(160),
                        TextInput::make('phone')->tel()->maxLength(40),

                        DatePicker::make('start_date')
                            ->label('Preferred start')
                            ->native(false),

                        TextInput::make('group_size')
                            ->label('Group size')
                            ->numeric()
                            ->minValue(1)
                            ->default(1),
                    ]),

                Section::make('Notes')
                    ->schema([
                        Textarea::make('message')
                            ->label('Message / internal notes')
                            ->rows(4),
                    ]),
            ]);
    }
}
