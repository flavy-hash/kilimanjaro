<?php

namespace App\Filament\Pages;

use App\Models\AboutContent;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
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
 * Editable copy for the dedicated /about and /team pages — one row
 * (AboutContent::current()), edited the same way HomePageContent edits the
 * homepage's copy.
 */
class AboutPageContent extends Page
{
    protected string $view = 'filament.pages.about-page-content';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUserGroup;

    protected static ?string $navigationLabel = 'About & Team';

    protected static string|\UnitEnum|null $navigationGroup = 'Content';

    protected static ?int $navigationSort = 2;

    protected static ?string $title = 'About & Team pages';

    /** @var array<string, mixed> */
    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill(
            AboutContent::current()->only(array_keys(AboutContent::defaults()))
        );
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make('AboutTeam')
                    ->persistTabInQueryString()
                    ->tabs([
                        Tab::make('About page')
                            ->schema([
                                Section::make('Hero')
                                    ->schema([
                                        TextInput::make('about_hero_tag')->label('Eyebrow tag')->maxLength(60),
                                        TextInput::make('about_hero_title')->label('Heading')->required()->maxLength(200)
                                            ->helperText('HTML like <em>…</em> is allowed for the italic word.'),
                                        Textarea::make('about_hero_lede')->label('Subheading')->rows(3)->maxLength(500),
                                        FileUpload::make('about_hero_image')
                                            ->label('Hero photo')
                                            ->image()
                                            ->disk('site')
                                            ->directory('images')
                                            ->visibility('public')
                                            ->imageEditor()
                                            ->columnSpanFull(),
                                    ]),

                                Section::make('Our story')
                                    ->schema([
                                        TextInput::make('story_title')->label('Eyebrow tag')->maxLength(60),
                                        TextInput::make('story_heading')->label('Heading')->required()->maxLength(200)
                                            ->helperText('HTML like <em>…</em> is allowed for the italic word.'),
                                        Repeater::make('story_paragraphs')
                                            ->label('Paragraphs')
                                            ->simple(Textarea::make('value')->rows(3)->required())
                                            ->addActionLabel('Add paragraph')
                                            ->reorderable(),
                                    ]),

                                Section::make('Mission, vision & more')
                                    ->description('Add as many statements as you like — Our Mission, Our Vision, and so on.')
                                    ->schema([
                                        Repeater::make('statements')
                                            ->hiddenLabel()
                                            ->schema([
                                                TextInput::make('title')->required()->maxLength(60)->placeholder('Our mission'),
                                                Textarea::make('text')->rows(3)->required()->maxLength(500),
                                            ])
                                            ->addActionLabel('Add statement')
                                            ->reorderable()
                                            ->itemLabel(fn (array $state): ?string => $state['title'] ?? null)
                                            ->collapsible(),
                                    ]),

                                Section::make('What we do differently')
                                    ->description('Shown as a row of short value statements.')
                                    ->schema([
                                        TextInput::make('values_tag')->label('Eyebrow tag')->maxLength(60),
                                        TextInput::make('values_heading')->label('Heading')->required()->maxLength(200)
                                            ->helperText('HTML like <em>…</em> is allowed for the italic word.'),
                                        Repeater::make('values')
                                            ->hiddenLabel()
                                            ->schema([
                                                TextInput::make('title')->required()->maxLength(60),
                                                Textarea::make('text')->rows(2)->required()->maxLength(300),
                                            ])
                                            ->addActionLabel('Add value')
                                            ->reorderable()
                                            ->itemLabel(fn (array $state): ?string => $state['title'] ?? null)
                                            ->collapsible(),
                                    ]),

                                Section::make('Bottom banner')
                                    ->description('The call-to-action band at the foot of the About page.')
                                    ->schema([
                                        TextInput::make('about_cta_title')->label('Heading')->required()->maxLength(200),
                                        Textarea::make('about_cta_text')->label('Text')->rows(2)->maxLength(400),
                                    ]),
                            ]),

                        Tab::make('Our team page')
                            ->schema([
                                Section::make('Hero')
                                    ->schema([
                                        TextInput::make('team_hero_tag')->label('Eyebrow tag')->maxLength(60),
                                        TextInput::make('team_hero_title')->label('Heading')->required()->maxLength(200),
                                        Textarea::make('team_hero_lede')->label('Subheading')->rows(3)->maxLength(500),
                                    ]),

                                Section::make('Team members')
                                    ->description('Leave the photo blank to show initials instead.')
                                    ->schema([
                                        Repeater::make('team_members')
                                            ->hiddenLabel()
                                            ->schema([
                                                FileUpload::make('photo')
                                                    ->image()
                                                    ->disk('site')
                                                    ->directory('images/team')
                                                    ->visibility('public')
                                                    ->imageEditor()
                                                    ->columnSpanFull(),
                                                TextInput::make('name')->required()->maxLength(60),
                                                TextInput::make('role')->required()->maxLength(80),
                                                Textarea::make('bio')->rows(2)->maxLength(300)->columnSpanFull(),
                                            ])
                                            ->columns(2)
                                            ->addActionLabel('Add team member')
                                            ->reorderable()
                                            ->itemLabel(fn (array $state): ?string => $state['name'] ?? null)
                                            ->collapsible()
                                            ->default([]),
                                    ]),

                                Section::make('Bottom banner')
                                    ->description('The call-to-action band at the foot of the Team page.')
                                    ->schema([
                                        TextInput::make('team_cta_title')->label('Heading')->required()->maxLength(200),
                                        Textarea::make('team_cta_text')->label('Text')->rows(2)->maxLength(400),
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
        $data = $this->form->getState();

        AboutContent::current()->update($data);

        Notification::make()
            ->success()
            ->title('About & Team pages updated')
            ->body('Reload the pages to see the changes.')
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
            Action::make('viewAbout')
                ->label('Open About page')
                ->color('gray')
                ->url('/about')
                ->openUrlInNewTab(),
            Action::make('viewTeam')
                ->label('Open Team page')
                ->color('gray')
                ->url('/team')
                ->openUrlInNewTab(),
        ];
    }
}
