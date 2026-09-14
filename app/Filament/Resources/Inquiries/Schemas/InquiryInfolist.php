<?php

namespace App\Filament\Resources\Inquiries\Schemas;

use App\Models\Inquiry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class InquiryInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Request')
                    ->columns(3)
                    ->schema([
                        TextEntry::make('reference')
                            ->label('Reference')
                            ->badge()
                            ->color('warning')
                            ->copyable(),

                        TextEntry::make('status')
                            ->label('Status')
                            ->badge()
                            ->formatStateUsing(fn (string $state) => Inquiry::STATUSES[$state] ?? $state)
                            ->color(fn (string $state) => match ($state) {
                                'new' => 'warning',
                                'contacted', 'quoted' => 'info',
                                'booked' => 'success',
                                default => 'gray',
                            }),

                        TextEntry::make('created_at')
                            ->label('Received')
                            ->dateTime('j M Y, H:i'),

                        TextEntry::make('package.name')
                            ->label('Package')
                            ->placeholder('Not specified')
                            ->columnSpan(2),

                        TextEntry::make('package.price')
                            ->label('List price')
                            ->money('USD')
                            ->placeholder('—'),
                    ]),

                Section::make('Customer')
                    ->columns(3)
                    ->schema([
                        TextEntry::make('name')->label('Name'),

                        TextEntry::make('email')
                            ->label('Email')
                            ->copyable()
                            ->url(fn (Inquiry $record) => 'mailto:'.$record->email),

                        TextEntry::make('phone')
                            ->label('Phone')
                            ->placeholder('—')
                            ->copyable(),

                        TextEntry::make('start_date')
                            ->label('Preferred start')
                            ->date('j M Y')
                            ->placeholder('Flexible'),

                        TextEntry::make('group_size')
                            ->label('Group size')
                            ->suffix(' people'),

                        TextEntry::make('updated_at')
                            ->label('Last updated')
                            ->since(),
                    ]),

                Section::make('Message')
                    ->schema([
                        TextEntry::make('message')
                            ->hiddenLabel()
                            ->placeholder('No message left with this request.')
                            ->prose(),
                    ]),
            ]);
    }
}
