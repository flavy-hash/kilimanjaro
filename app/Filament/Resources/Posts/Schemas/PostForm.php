<?php

namespace App\Filament\Resources\Posts\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class PostForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Post')
                    ->columns(2)
                    ->schema([
                        TextInput::make('title')
                            ->required()
                            ->maxLength(200)
                            ->live(onBlur: true)
                            ->afterStateUpdated(function (Get $get, Set $set, ?string $state) {
                                if (filled($state) && blank($get('slug'))) {
                                    $set('slug', Str::slug($state));
                                }
                            })
                            ->placeholder('Five Climate Zones: What Kilimanjaro Actually Feels Like')
                            ->columnSpanFull(),

                        TextInput::make('slug')
                            ->required()
                            ->maxLength(200)
                            ->unique(ignoreRecord: true)
                            ->rules(['regex:/^[a-z0-9-]+$/'])
                            ->helperText(fn (?string $state) => 'Page will live at /blog/'.($state ?: 'your-slug'))
                            ->columnSpanFull(),

                        TextInput::make('author_name')
                            ->label('Author')
                            ->default('Perfect Kilimanjaro')
                            ->maxLength(120),

                        DateTimePicker::make('published_at')
                            ->label('Publish date')
                            ->default(now())
                            ->helperText('Posts with a future date stay hidden until then.'),

                        Textarea::make('excerpt')
                            ->rows(2)
                            ->maxLength(400)
                            ->helperText('Shown on the blog listing and used as the meta description.')
                            ->columnSpanFull(),

                        FileUpload::make('cover_image')
                            ->image()
                            ->disk('site')
                            ->directory('images/blog')
                            ->visibility('public')
                            ->imageEditor()
                            ->required()
                            ->columnSpanFull(),
                    ]),

                Section::make('Body')
                    ->schema([
                        RichEditor::make('body')
                            ->hiddenLabel()
                            ->required()
                            ->columnSpanFull(),
                    ]),

                Section::make('Publishing')
                    ->columns(2)
                    ->schema([
                        Toggle::make('is_published')
                            ->label('Published')
                            ->default(true),
                    ]),
            ]);
    }
}
