<?php

namespace App\Filament\Pages;

use App\Models\Setting;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\Radio;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\EmbeddedSchema;
use Filament\Schemas\Components\Form;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

/**
 * Site-wide appearance. The theme chosen here is what every visitor sees on the
 * public site — it is not a per-admin browser preference.
 */
class Appearance extends Page
{
    protected string $view = 'filament.pages.appearance';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedSwatch;

    protected static ?string $navigationLabel = 'Appearance';

    protected static string|\UnitEnum|null $navigationGroup = 'Content';

    protected static ?int $navigationSort = 1;

    protected static ?string $title = 'Appearance';

    /** @var array<string, mixed> */
    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill([
            'site_theme' => Setting::siteTheme(),
        ]);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Website theme')
                    ->description('Applies to the public website for every visitor. The admin panel keeps its own light/dark switch in the top-right menu.')
                    ->schema([
                        Radio::make('site_theme')
                            ->hiddenLabel()
                            ->options(Setting::THEMES)
                            ->descriptions([
                                'dark' => 'The original look — dark background, warm accents.',
                                'light' => 'Light background with the same warm accents.',
                                'system' => 'Each visitor gets light or dark to match their own phone or computer setting.',
                            ])
                            ->required()
                            ->default(Setting::DEFAULT_THEME),
                    ]),
            ])
            ->statePath('data');
    }

    /**
     * Wraps the form so its submit handler and footer actions render the way
     * Filament's own settings-style pages do.
     */
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
        $data = $this->form->getState();

        Setting::set('site_theme', $data['site_theme']);

        Notification::make()
            ->success()
            ->title('Website theme updated')
            ->body('Reload the public site to see it.')
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
                ->label('Open the site')
                ->color('gray')
                ->url(url('/'))
                ->openUrlInNewTab(),
        ];
    }
}
