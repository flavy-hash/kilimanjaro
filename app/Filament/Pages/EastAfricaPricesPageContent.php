<?php

namespace App\Filament\Pages;

use App\Models\EastAfricaPriceContent;
use App\Models\Package;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\EmbeddedSchema;
use Filament\Schemas\Components\Form;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

/**
 * Editable content for the /kilimanjaro-east-african-citizens page — one row
 * (EastAfricaPriceContent::current()), edited like the other content pages.
 */
class EastAfricaPricesPageContent extends Page
{
    protected string $view = 'filament.pages.climbing-prep-page-content';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCurrencyDollar;

    protected static ?string $navigationLabel = 'East African Prices';

    protected static string|\UnitEnum|null $navigationGroup = 'Content';

    protected static ?int $navigationSort = 4;

    protected static ?string $title = 'East African Citizens prices (TZS)';

    /** @var array<string, mixed> */
    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill(
            EastAfricaPriceContent::current()->only(array_keys(EastAfricaPriceContent::defaults()))
        );
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make('EastAfricaPrices')
                    ->persistTabInQueryString()
                    ->tabs([
                        Tab::make('Prices')
                            ->schema([
                                Section::make('Prices by route')
                                    ->description('One card per route and duration. All amounts are per person, in TZS.')
                                    ->schema([
                                        Repeater::make('routes')
                                            ->hiddenLabel()
                                            ->schema([
                                                TextInput::make('route')->label('Route')->required()->maxLength(60)->placeholder('Lemosho Route'),
                                                TextInput::make('days')->label('Days')->numeric()->minValue(1)->required(),
                                                Select::make('slug')
                                                    ->label('Link to itinerary')
                                                    ->options(fn () => Package::where('category', 'kilimanjaro')->ordered()->pluck('name', 'slug')
                                                        ->mapWithKeys(fn ($name, $slug) => [$slug => "{$name} ({$slug})"]))
                                                    ->searchable()
                                                    ->placeholder('No link')
                                                    ->helperText('Optional — adds an "Itinerary" link to the card.'),
                                                ...collect(EastAfricaPriceContent::GROUPS)->map(fn ($label, $key) => TextInput::make($key)
                                                    ->label($label)
                                                    ->numeric()
                                                    ->minValue(0)
                                                    ->required()
                                                    ->suffix('TZS'))->values()->all(),
                                            ])
                                            ->columns(3)
                                            ->addActionLabel('Add route')
                                            ->reorderable()
                                            ->collapsible()
                                            ->itemLabel(fn (array $state): ?string => filled($state['route'] ?? null)
                                                ? $state['route'].' · '.($state['days'] ?? '?').' Days'
                                                : null),
                                    ]),
                            ]),

                        Tab::make('Includes')
                            ->schema([
                                Section::make('Price includes / excludes')
                                    ->columns(2)
                                    ->schema([
                                        Repeater::make('included')
                                            ->label('Price includes')
                                            ->simple(TextInput::make('value')->required()->maxLength(200))
                                            ->addActionLabel('Add item')
                                            ->reorderable(),
                                        Repeater::make('excluded')
                                            ->label('Price excludes')
                                            ->simple(TextInput::make('value')->required()->maxLength(200))
                                            ->addActionLabel('Add item')
                                            ->reorderable(),
                                    ]),
                            ]),

                        Tab::make('Page text')
                            ->schema([
                                Section::make('Hero & intro')
                                    ->schema([
                                        TextInput::make('hero_title')->label('Heading')->required()->maxLength(200),
                                        Textarea::make('hero_lede')->label('Subheading')->rows(2)->maxLength(400),
                                        FileUpload::make('hero_image')
                                            ->label('Hero photo')
                                            ->image()
                                            ->disk('site')
                                            ->directory('images')
                                            ->visibility('public')
                                            ->imageEditor(),
                                        Textarea::make('intro')->label('Intro paragraph')->rows(3),
                                    ]),

                                Section::make('Why choose us')
                                    ->schema([
                                        TextInput::make('why_title')->label('Heading')->maxLength(120),
                                        Textarea::make('why_intro')->label('Intro paragraph')->rows(3),
                                        Repeater::make('why_points')
                                            ->label('Points')
                                            ->simple(TextInput::make('value')->required()->maxLength(200))
                                            ->addActionLabel('Add point')
                                            ->reorderable(),
                                    ]),

                                Section::make('Why East African citizens climb with us')
                                    ->schema([
                                        TextInput::make('citizens_title')->label('Heading')->maxLength(120),
                                        Repeater::make('citizens_paragraphs')
                                            ->label('Paragraphs')
                                            ->simple(Textarea::make('value')->rows(3)->required())
                                            ->addActionLabel('Add paragraph')
                                            ->reorderable(),
                                        Repeater::make('reasons')
                                            ->label('Reasons')
                                            ->schema([
                                                TextInput::make('title')->required()->maxLength(120),
                                                Textarea::make('text')->rows(3)->required(),
                                            ])
                                            ->addActionLabel('Add reason')
                                            ->reorderable()
                                            ->collapsible()
                                            ->itemLabel(fn (array $state): ?string => $state['title'] ?? null),
                                    ]),

                                Section::make('Contact banner')
                                    ->columns(2)
                                    ->schema([
                                        TextInput::make('cta_title')->label('Heading')->maxLength(120)->columnSpanFull(),
                                        Textarea::make('cta_text')->label('Text')->rows(2)->columnSpanFull(),
                                        TextInput::make('whatsapp')->label('WhatsApp number')->maxLength(30),
                                        TextInput::make('email')->label('Email')->email()->maxLength(120),
                                    ]),
                            ]),
                    ]),
            ])
            ->statePath('data');
    }

    public function content(Schema $schema): Schema
    {
        return $schema
            ->components([
                Form::make([EmbeddedSchema::make('form')])
                    ->id('form')
                    ->livewireSubmitHandler('save')
                    ->footer([
                        Actions::make($this->getFormActions())
                            ->key('form-actions'),
                    ]),
            ]);
    }

    public function save(): void
    {
        EastAfricaPriceContent::current()->update($this->form->getState());

        Notification::make()
            ->success()
            ->title('East African prices updated')
            ->body('Reload the page to see the changes.')
            ->send();
    }

    /** @return array<Action> */
    protected function getFormActions(): array
    {
        return [
            Action::make('save')
                ->label('Save changes')
                ->submit('save')
                ->keyBindings(['mod+s']),
            Action::make('view')
                ->label('Open the page')
                ->color('gray')
                ->url('/kilimanjaro-east-african-citizens')
                ->openUrlInNewTab(),
        ];
    }
}
