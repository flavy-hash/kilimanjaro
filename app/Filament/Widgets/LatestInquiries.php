<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\Inquiries\InquiryResource;
use App\Models\Inquiry;
use Filament\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;

class LatestInquiries extends TableWidget
{
    protected static ?string $heading = 'Latest inquiries';

    protected static ?int $sort = 2;

    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(fn (): Builder => Inquiry::query()->with('package')->latest())
            ->paginated([5, 10, 25])
            ->defaultPaginationPageOption(5)
            ->columns([
                TextColumn::make('reference')
                    ->label('Ref')
                    ->badge()
                    ->color('warning'),

                TextColumn::make('name')
                    ->label('Customer')
                    ->weight('semibold')
                    ->description(fn (Inquiry $record) => $record->email),

                TextColumn::make('package.name')
                    ->label('Package')
                    ->placeholder('— not specified'),

                TextColumn::make('start_date')
                    ->label('Start')
                    ->date('j M Y')
                    ->placeholder('—'),

                TextColumn::make('group_size')
                    ->label('Pax')
                    ->alignCenter(),

                TextColumn::make('status')
                    ->badge()
                    ->formatStateUsing(fn (string $state) => Inquiry::STATUSES[$state] ?? $state)
                    ->color(fn (string $state) => match ($state) {
                        'new' => 'warning',
                        'contacted', 'quoted' => 'info',
                        'booked' => 'success',
                        default => 'gray',
                    }),

                TextColumn::make('created_at')
                    ->label('Received')
                    ->since(),
            ])
            ->recordActions([
                Action::make('open')
                    ->label('Open')
                    ->icon('heroicon-m-pencil-square')
                    ->url(fn (Inquiry $record) => InquiryResource::getUrl('edit', ['record' => $record])),
            ])
            ->emptyStateHeading('No inquiries yet')
            ->emptyStateDescription('Booking requests from the website will show up here.');
    }
}
