<?php

namespace App\Filament\Pages;

use App\Models\ClimbingPrepContent;
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
 * Editable copy for the dedicated /climbing-preparation page — one row
 * (ClimbingPrepContent::current()), edited the same way HomePageContent
 * and AboutPageContent edit theirs.
 */
class ClimbingPrepPageContent extends Page
{
    protected string $view = 'filament.pages.climbing-prep-page-content';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedAcademicCap;

    protected static ?string $navigationLabel = 'Climbing Prep';

    protected static string|\UnitEnum|null $navigationGroup = 'Content';

    protected static ?int $navigationSort = 3;

    protected static ?string $title = 'Climbing Preparation page';

    /** @var array<string, mixed> */
    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill(
            ClimbingPrepContent::current()->only(array_keys(ClimbingPrepContent::defaults()))
        );
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make('ClimbingPrep')
                    ->persistTabInQueryString()
                    ->tabs([
                        Tab::make('Hero')
                            ->schema([
                                Section::make('Hero')
                                    ->schema([
                                        TextInput::make('hero_tag')->label('Eyebrow tag')->maxLength(60),
                                        TextInput::make('hero_title')->label('Heading')->required()->maxLength(200)
                                            ->helperText('HTML like <em>…</em> is allowed for the italic word.'),
                                        Textarea::make('hero_lede')->label('Subheading')->rows(3)->maxLength(500),
                                        FileUpload::make('hero_image')
                                            ->label('Hero photo')
                                            ->image()
                                            ->disk('site')
                                            ->directory('images')
                                            ->visibility('public')
                                            ->imageEditor()
                                            ->columnSpanFull(),
                                    ]),
                            ]),

                        Tab::make('Fitness & altitude')
                            ->schema([
                                Section::make('Fitness')
                                    ->schema([
                                        TextInput::make('fitness_title')->label('Heading')->maxLength(60),
                                        Repeater::make('fitness_paragraphs')
                                            ->label('Paragraphs')
                                            ->simple(Textarea::make('value')->rows(3)->required())
                                            ->addActionLabel('Add paragraph')
                                            ->reorderable(),
                                    ]),

                                Section::make('Altitude')
                                    ->schema([
                                        TextInput::make('altitude_title')->label('Heading')->maxLength(60),
                                        Repeater::make('altitude_paragraphs')
                                            ->label('Paragraphs')
                                            ->simple(Textarea::make('value')->rows(3)->required())
                                            ->addActionLabel('Add paragraph')
                                            ->reorderable(),
                                    ]),
                            ]),

                        Tab::make('Tips & FAQ')
                            ->schema([
                                Section::make('Practical tips')
                                    ->schema([
                                        TextInput::make('tips_title')->label('Heading')->maxLength(60),
                                        Repeater::make('tips')
                                            ->hiddenLabel()
                                            ->schema([
                                                TextInput::make('title')->required()->maxLength(60),
                                                Textarea::make('text')->rows(2)->required()->maxLength(300),
                                            ])
                                            ->addActionLabel('Add tip')
                                            ->reorderable()
                                            ->itemLabel(fn (array $state): ?string => $state['title'] ?? null)
                                            ->collapsible(),
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

        ClimbingPrepContent::current()->update($data);

        Notification::make()
            ->success()
            ->title('Climbing Preparation page updated')
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
                ->url('/climbing-preparation')
                ->openUrlInNewTab(),
        ];
    }
}
