<?php

namespace App\Filament\Pages;

use App\Models\HomeContent;
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
 * Editable copy for the public homepage — one row (HomeContent::current()),
 * edited here the same way Appearance edits the single theme setting.
 * Section layout/graphics stay in the blade view; only their text and the
 * activity photos are editable here.
 */
class HomePageContent extends Page
{
    protected string $view = 'filament.pages.home-page-content';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedHome;

    protected static ?string $navigationLabel = 'Homepage';

    protected static string|\UnitEnum|null $navigationGroup = 'Content';

    protected static ?int $navigationSort = 0;

    protected static ?string $title = 'Homepage content';

    /** @var array<string, mixed> */
    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill(
            HomeContent::current()->only(array_keys(HomeContent::defaults()))
        );
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make('Homepage')
                    ->persistTabInQueryString()
                    ->tabs([
                        Tab::make('Hero stats')
                            ->schema([
                                Section::make('Stat band')
                                    ->description('The four numbers under the hero slider.')
                                    ->schema([
                                        Repeater::make('hero_stats')
                                            ->hiddenLabel()
                                            ->schema([
                                                TextInput::make('num')->label('Number')->required()->maxLength(10),
                                                TextInput::make('suffix')->label('Suffix')->maxLength(10)
                                                    ->helperText('e.g. +, ★, hr — optional.'),
                                                TextInput::make('label')->label('Label')->required()->maxLength(40),
                                            ])
                                            ->columns(3)
                                            ->minItems(4)
                                            ->maxItems(4)
                                            ->addable(false)
                                            ->deletable(false)
                                            ->reorderable(false),
                                    ]),
                            ]),

                        Tab::make('Who we are')
                            ->schema([
                                Section::make('Intro section')
                                    ->schema([
                                        TextInput::make('intro_tag')->label('Eyebrow tag')->maxLength(60),
                                        TextInput::make('intro_title')->label('Heading')->required()->maxLength(200)
                                            ->helperText('HTML like <em>…</em> is allowed for the italic word.'),
                                        Repeater::make('intro_paragraphs')
                                            ->label('Paragraphs')
                                            ->simple(
                                                Textarea::make('value')->rows(3)->required()
                                            )
                                            ->addActionLabel('Add paragraph')
                                            ->reorderable()
                                            ->helperText('<strong>…</strong> is allowed for bold route/park names.'),
                                    ]),
                            ]),

                        Tab::make('Routes & pillars')
                            ->schema([
                                Section::make('"What we run" section')
                                    ->schema([
                                        TextInput::make('pillars_tag')->label('Eyebrow tag')->maxLength(60),
                                        TextInput::make('pillars_title')->label('Heading')->required()->maxLength(200),
                                        Textarea::make('pillars_lede')->label('Subheading')->rows(2)->maxLength(400),
                                    ]),

                                Section::make('"Kilimanjaro routes" section')
                                    ->description('The map itself is not editable here — only its heading text.')
                                    ->schema([
                                        TextInput::make('routes_tag')->label('Eyebrow tag')->maxLength(60),
                                        TextInput::make('routes_title')->label('Heading')->required()->maxLength(200),
                                        Textarea::make('routes_lede')->label('Subheading')->rows(2)->maxLength(400),
                                    ]),

                                Section::make('Equipment checklist banner')
                                    ->schema([
                                        TextInput::make('equip_title')->label('Heading')->required()->maxLength(120),
                                        Textarea::make('equip_description')->label('Description')->rows(2)->maxLength(300),
                                        FileUpload::make('equip_pdf')
                                            ->label('Checklist PDF')
                                            ->disk('site')
                                            ->directory('documents')
                                            ->visibility('public')
                                            ->acceptedFileTypes(['application/pdf'])
                                            ->openable()
                                            ->downloadable()
                                            ->required()
                                            ->helperText('The file behind the "Download checklist" button on the homepage.'),
                                    ]),
                            ]),

                        Tab::make('Trips & pricing')
                            ->schema([
                                Section::make('Section heading')
                                    ->schema([
                                        TextInput::make('trips_tag')->label('Eyebrow tag')->maxLength(60),
                                        TextInput::make('trips_title')->label('Heading')->required()->maxLength(200),
                                        Textarea::make('trips_lede')->label('Subheading')->rows(2)->maxLength(400),
                                    ]),
                            ]),

                        Tab::make('Activities')
                            ->schema([
                                Section::make('Section heading')
                                    ->schema([
                                        TextInput::make('activities_tag')->label('Eyebrow tag')->maxLength(60),
                                        TextInput::make('activities_title')->label('Heading')->required()->maxLength(200),
                                        Textarea::make('activities_lede')->label('Subheading')->rows(2)->maxLength(400),
                                    ]),

                                Section::make('The four rows')
                                    ->schema([
                                        Repeater::make('activities')
                                            ->hiddenLabel()
                                            ->schema([
                                                FileUpload::make('image')
                                                    ->image()
                                                    ->disk('site')
                                                    ->directory('images/activities')
                                                    ->visibility('public')
                                                    ->imageEditor()
                                                    ->required()
                                                    ->columnSpanFull(),
                                                TextInput::make('tag')->label('Overlay tag')->required()->maxLength(40),
                                                TextInput::make('eyebrow')->required()->maxLength(40),
                                                TextInput::make('title')->required()->maxLength(60),
                                                Textarea::make('text')->rows(3)->required()->maxLength(400)
                                                    ->columnSpanFull(),
                                            ])
                                            ->columns(3)
                                            ->minItems(4)
                                            ->maxItems(4)
                                            ->addable(false)
                                            ->deletable(false)
                                            ->reorderable(false)
                                            ->itemLabel(fn (array $state): ?string => $state['title'] ?? null)
                                            ->collapsible(),
                                    ]),
                            ]),

                        Tab::make('About & FAQ')
                            ->schema([
                                Section::make('Section heading')
                                    ->schema([
                                        TextInput::make('about_tag')->label('Eyebrow tag')->maxLength(60),
                                        TextInput::make('about_title')->label('Heading')->required()->maxLength(200),
                                        Textarea::make('about_lede')->label('Subheading')->rows(2)->maxLength(400),
                                    ]),

                                Section::make('Story & team')
                                    ->columns(2)
                                    ->schema([
                                        TextInput::make('about_story_title')->label('"Our story" title')->maxLength(60),
                                        TextInput::make('about_team_title')->label('"Our team" title')->maxLength(60),
                                        Textarea::make('about_story_text')->label('"Our story" text')->rows(4),
                                        Textarea::make('about_team_text')->label('"Our team" text')->rows(4),
                                    ]),

                                Section::make('FAQ')
                                    ->schema([
                                        Repeater::make('faqs')
                                            ->hiddenLabel()
                                            ->schema([
                                                TextInput::make('question')->required()->maxLength(200)->columnSpanFull(),
                                                Textarea::make('answer')->rows(2)->required()->columnSpanFull(),
                                            ])
                                            ->addActionLabel('Add question')
                                            ->reorderable()
                                            ->itemLabel(fn (array $state): ?string => $state['question'] ?? null)
                                            ->collapsible(),
                                    ]),
                            ]),

                        Tab::make('Call to action')
                            ->schema([
                                Section::make('Bottom banner')
                                    ->schema([
                                        TextInput::make('cta_title')->label('Heading')->required()->maxLength(200),
                                        Textarea::make('cta_text')->label('Text')->rows(2)->maxLength(400),
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

        HomeContent::current()->update($data);

        Notification::make()
            ->success()
            ->title('Homepage content updated')
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
