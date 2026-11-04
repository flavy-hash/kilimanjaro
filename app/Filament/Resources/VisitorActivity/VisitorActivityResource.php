<?php

namespace App\Filament\Resources\VisitorActivity;

use App\Filament\Resources\VisitorActivity\Pages\ListVisitorActivities;
use App\Models\VisitorActivity as VisitorActivityModel;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

/**
 * Read-only report over VisitorActivity — see that model's docblock for the
 * privacy guarantees (no cookies, no IPs, no names, daily-rotating visitor
 * id, bots/DNT/signed-in staff excluded, 180-day retention).
 */
class VisitorActivityResource extends Resource
{
    protected static ?string $model = VisitorActivityModel::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedChartBar;

    protected static ?string $navigationLabel = 'Visitor Activity';

    protected static ?string $modelLabel = 'visit';

    protected static ?string $pluralModelLabel = 'Visitor Activity';

    protected static string|\UnitEnum|null $navigationGroup = 'Insights';

    protected static ?int $navigationSort = 1;

    public static function canCreate(): bool
    {
        return false;
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->heading('Visitor Activity')
            ->description(
                'Anonymous statistics: page, package viewed, device type, country and the site a '.
                'visitor came from. No cookies, no IP addresses, no names. The visitor ID is a code '.
                'that changes every day, so it only groups one visitor\'s pages on one day. Visitors '.
                'who send "Do Not Track" or "Global Privacy Control", bots and signed-in staff are '.
                'never recorded. Entries are deleted after '.VisitorActivityModel::RETENTION_DAYS.' days. '.
                'IP geolocation by DB-IP.'
            )
            ->columns([
                TextColumn::make('visited_at')
                    ->label('Time')
                    ->dateTime('M j, H:i')
                    ->sortable(),

                TextColumn::make('page_label')
                    ->label('Page')
                    ->badge()
                    ->color('gray')
                    ->description(fn (VisitorActivityModel $record) => $record->page_path),

                TextColumn::make('package_slug')
                    ->label('Package viewed')
                    ->placeholder('—'),

                IconColumn::make('device_type')
                    ->label('Device')
                    ->icon(fn (?string $state) => match ($state) {
                        'mobile' => 'heroicon-o-device-phone-mobile',
                        'tablet' => 'heroicon-o-device-tablet',
                        default => 'heroicon-o-computer-desktop',
                    })
                    ->color('gray'),

                TextColumn::make('country_name')
                    ->label('Country')
                    ->formatStateUsing(fn (VisitorActivityModel $record) => trim(
                        self::flagEmoji($record->country_code).' '.($record->country_name ?? 'Unknown')
                    ))
                    ->placeholder('Unknown'),

                TextColumn::make('referrer_host')
                    ->label('Came from')
                    ->placeholder('Direct / unknown')
                    ->color('gray'),
            ])
            ->filters([
                SelectFilter::make('device_type')
                    ->label('Device')
                    ->options([
                        'desktop' => 'Desktop',
                        'mobile' => 'Mobile',
                        'tablet' => 'Tablet',
                    ]),

                SelectFilter::make('country_code')
                    ->label('Country')
                    ->options(fn () => VisitorActivityModel::query()
                        ->whereNotNull('country_code')
                        ->distinct()
                        ->orderBy('country_name')
                        ->pluck('country_name', 'country_code')
                        ->all()),
            ])
            ->defaultSort('visited_at', 'desc')
            ->recordActions([])
            ->toolbarActions([])
            ->emptyStateHeading('No visits recorded yet')
            ->emptyStateDescription('Page views from the public site will start showing up here.');
    }

    private static function flagEmoji(?string $countryCode): string
    {
        if (! $countryCode || strlen($countryCode) !== 2) {
            return '';
        }

        $code = strtoupper($countryCode);

        return mb_chr(127397 + ord($code[0])).mb_chr(127397 + ord($code[1]));
    }

    public static function getPages(): array
    {
        return [
            'index' => ListVisitorActivities::route('/'),
        ];
    }
}
