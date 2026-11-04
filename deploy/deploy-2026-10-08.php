<?php
/**
 * One-shot deploy script for the 2026-10-08 batch (About/Home content + PDF download).
 * Run: php deploy-2026-10-08.php [--dry]
 * Writes each file below (full contents, with a .bak backup of anything overwritten),
 * skipping any file that already matches exactly. Composer deps + migrate + cache clear
 * are separate manual steps printed at the end.
 */

$root = __DIR__;
$DRY = in_array('--dry', $argv, true);
if ($DRY) echo "
*** DRY RUN - nothing will be written ***
";

$files = [
    'database/migrations/2026_10_08_090000_add_heritage_sections_to_about_content_table.php' => <<<'KILI_DEPLOY_EOF'
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('about_content', function (Blueprint $table) {
            $table->string('generations_tag')->nullable();
            $table->string('generations_title')->nullable();
            $table->json('generations')->nullable();
            $table->text('generations_closing')->nullable();

            $table->string('do_tag')->nullable();
            $table->string('do_title')->nullable();
            $table->json('do_items')->nullable();

            $table->string('safety_tag')->nullable();
            $table->string('safety_title')->nullable();
            $table->text('safety_lede')->nullable();
            $table->json('safety_points')->nullable();

            $table->string('locally_owned_title')->nullable();
            $table->text('locally_owned_text')->nullable();

            $table->text('closing_text')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('about_content', function (Blueprint $table) {
            $table->dropColumn([
                'generations_tag', 'generations_title', 'generations', 'generations_closing',
                'do_tag', 'do_title', 'do_items',
                'safety_tag', 'safety_title', 'safety_lede', 'safety_points',
                'locally_owned_title', 'locally_owned_text',
                'closing_text',
            ]);
        });
    }
};
KILI_DEPLOY_EOF
,
    'resources/views/pdf/trip.blade.php' => <<<'KILI_DEPLOY_EOF'
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>{{ $trip['name'] }} · Perfect Kilimanjaro</title>
<style>
  @page { margin: 28px 36px; }
  body { font-family: 'Helvetica', 'Arial', sans-serif; color: #1a1a1a; font-size: 12px; line-height: 1.5; }

  .header { border-bottom: 2px solid #ff7a2f; padding-bottom: 10px; margin-bottom: 18px; }
  .header .brand { font-size: 13px; font-weight: bold; color: #ff7a2f; text-transform: uppercase; letter-spacing: 1px; }
  .header h1 { font-size: 22px; margin: 6px 0 2px; }
  .header .sub { font-size: 12px; color: #555; }

  h2.section { font-size: 14px; border-bottom: 1px solid #ddd; padding-bottom: 4px; margin: 20px 0 10px; color: #1a1a1a; }

  table.facts { width: 100%; border-collapse: collapse; margin-bottom: 4px; }
  table.facts td { padding: 5px 8px; border: 1px solid #e3e3e3; font-size: 11.5px; }
  table.facts td.label { width: 32%; color: #666; background: #faf7f3; }

  table.price { width: 100%; border-collapse: collapse; }
  table.price th, table.price td { padding: 6px 8px; border: 1px solid #e3e3e3; font-size: 11.5px; text-align: left; }
  table.price th { background: #ff7a2f; color: #fff; }

  ul.plain { margin: 0; padding-left: 16px; }
  ul.plain li { margin-bottom: 5px; }

  .cols { width: 100%; }
  .col { width: 48%; display: inline-block; vertical-align: top; }
  .col.left { margin-right: 2%; }

  .day { margin-bottom: 12px; padding-bottom: 10px; border-bottom: 1px solid #eee; }
  .day .day-title { font-weight: bold; font-size: 12.5px; margin-bottom: 3px; }
  .day .day-meta { color: #888; font-size: 10.5px; margin-bottom: 4px; }

  .quote { background: #faf7f3; border-left: 3px solid #ff7a2f; padding: 10px 14px; font-style: italic; margin: 14px 0; }

  .cover { width: 100%; height: 220px; object-fit: cover; margin-bottom: 14px; }

  .stay { margin-bottom: 14px; }
  .stay-photo { width: 150px; height: 100px; object-fit: cover; float: left; margin-right: 14px; }
  .stay-name { font-weight: bold; font-size: 12.5px; }
  .stay-meta { color: #888; font-size: 10.5px; margin-bottom: 4px; }
  .stay-note { font-size: 11.5px; }
  .clear { clear: both; }

  .gallery { width: 100%; }
  .gal-item { width: 31%; margin: 0 3% 10px 0; display: inline-block; vertical-align: top; }
  .gal-item img { width: 100%; height: 95px; object-fit: cover; }

  .footer { margin-top: 24px; padding-top: 10px; border-top: 1px solid #ddd; font-size: 10px; color: #888; text-align: center; }
</style>
</head>
<body>

  <div class="header">
    <div class="brand">Perfect Kilimanjaro</div>
    <h1>{{ $trip['name'] }}</h1>
    <div class="sub">
      @if (filled($trip['nickname'])) {{ $trip['nickname'] }} &middot; @endif
      {{ $trip['days'] }} Days
      @if (filled($trip['location'])) &middot; {{ $trip['location'] }} @endif
    </div>
  </div>

  @if (filled($trip['image'] ?? null))
    <img class="cover" src="{{ public_path($trip['image']) }}" alt="{{ $trip['name'] }}">
  @endif

  @if (filled($trip['quote']))
    <div class="quote">{{ $trip['quote'] }}</div>
  @endif

  @if (filled($trip['overview'] ?? $trip['blurb'] ?? null))
    <p>{{ $trip['overview'] ?? $trip['blurb'] }}</p>
  @endif

  <h2 class="section">Trip facts</h2>
  <table class="facts">
    <tr><td class="label">Duration</td><td>{{ $trip['days'] }} Days &middot; {{ $trip['days'] - 1 }} Nights</td></tr>
    @if (filled($trip['group'])) <tr><td class="label">Group size</td><td>{{ $trip['group'] }}</td></tr> @endif
    @if (filled($trip['difficulty'])) <tr><td class="label">Difficulty</td><td>{{ $trip['difficulty'] }}</td></tr> @endif
    @if (filled($trip['best_time'])) <tr><td class="label">Best time</td><td>{{ $trip['best_time'] }}</td></tr> @endif
    @if (filled($trip['start_point'])) <tr><td class="label">Starts &amp; ends</td><td>{{ $trip['start_point'] }}</td></tr> @endif
    @if (filled($trip['accommodation'])) <tr><td class="label">Accommodation</td><td>{{ $trip['accommodation'] }}</td></tr> @endif
  </table>

  @if (filled($trip['price_tiers'] ?? null))
    <h2 class="section">Pricing (per person)</h2>
    <table class="price">
      <tr><th>Group size</th><th>Total (USD)</th></tr>
      @foreach ($trip['price_tiers'] as $tier)
        <tr><td>{{ $tier['label'] }}</td><td>${{ number_format($tier['price']) }}</td></tr>
      @endforeach
    </table>
  @elseif (filled($trip['price'] ?? null))
    <h2 class="section">Pricing</h2>
    <p>From <strong>${{ number_format($trip['price']) }}</strong> per person.</p>
  @endif

  @if (filled($trip['highlights'] ?? null))
    <h2 class="section">Highlights</h2>
    <ul class="plain">
      @foreach ($trip['highlights'] as $item)
        <li>{{ $item }}</li>
      @endforeach
    </ul>
  @endif

  @if (filled($trip['itinerary'] ?? null))
    <h2 class="section">Day-by-day itinerary</h2>
    @foreach ($trip['itinerary'] as $i => $day)
      <div class="day">
        <div class="day-title">Day {{ $i + 1 }}: {{ $day['t'] }}</div>
        @if (filled($day['elev'] ?? null) || filled($day['distance'] ?? null) || filled($day['time'] ?? null))
          <div class="day-meta">
            {{ collect([$day['elev'] ?? null, $day['distance'] ?? null, $day['time'] ?? null])->filter()->implode(' · ') }}
          </div>
        @endif
        <div>{{ $day['d'] }}</div>
      </div>
    @endforeach
  @endif

  @if (filled($trip['stays'] ?? null))
    <h2 class="section">Where you'll stay</h2>
    @foreach ($trip['stays'] as $stay)
      <div class="stay">
        @if (filled($stay['image'] ?? null))
          <img class="stay-photo" src="{{ public_path($stay['image']) }}" alt="{{ $stay['name'] }}">
        @endif
        <div class="stay-name">{{ $stay['name'] }}</div>
        <div class="stay-meta">
          {{ collect([$stay['class'] ?? null, $stay['place'] ?? null, filled($stay['stars'] ?? null) ? $stay['stars'].'-star' : null])->filter()->implode(' · ') }}
        </div>
        @if (filled($stay['note'] ?? null))
          <div class="stay-note">{{ $stay['note'] }}</div>
        @endif
      </div>
      <div class="clear"></div>
    @endforeach
  @endif

  @if (filled($trip['gallery'] ?? null))
    <h2 class="section">Gallery</h2>
    <div class="gallery">
      @foreach (array_slice($trip['gallery'], 0, 9) as $i => $g)
        <div class="gal-item">
          <img src="{{ public_path($g) }}" alt="{{ $trip['name'] }} photo {{ $i + 1 }}">
        </div>
      @endforeach
    </div>
  @endif

  @if (filled($trip['included'] ?? null) || filled($trip['excluded'] ?? null))
    <h2 class="section">What's included</h2>
    <div class="cols">
      <div class="col left">
        @if (filled($trip['included'] ?? null))
          <ul class="plain">
            @foreach ($trip['included'] as $item)
              <li>{{ $item }}</li>
            @endforeach
          </ul>
        @endif
      </div>
      <div class="col">
        @if (filled($trip['excluded'] ?? null))
          <ul class="plain">
            @foreach ($trip['excluded'] as $item)
              <li>{{ $item }}</li>
            @endforeach
          </ul>
        @endif
      </div>
    </div>
  @endif

  <div class="footer">
    perfectkilimanjaro.com &middot; WhatsApp +255 752 967 222 &middot; Generated {{ now()->format('d M Y') }}
  </div>

</body>
</html>
KILI_DEPLOY_EOF
,
    'app/Filament/Pages/AboutPageContent.php' => <<<'KILI_DEPLOY_EOF'
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

                                Section::make('Our heritage / story')
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

                                Section::make('Three generations')
                                    ->description('The family history timeline — grandfather, father, Priscus, and so on.')
                                    ->schema([
                                        TextInput::make('generations_tag')->label('Eyebrow tag')->maxLength(60),
                                        TextInput::make('generations_title')->label('Heading')->required()->maxLength(200)
                                            ->helperText('HTML like <em>…</em> is allowed for the italic word.'),
                                        Repeater::make('generations')
                                            ->hiddenLabel()
                                            ->schema([
                                                TextInput::make('name')->label('Name / role')->required()->maxLength(80),
                                                Textarea::make('text')->rows(3)->required()->maxLength(600),
                                            ])
                                            ->addActionLabel('Add generation')
                                            ->reorderable()
                                            ->itemLabel(fn (array $state): ?string => $state['name'] ?? null)
                                            ->collapsible(),
                                        Textarea::make('generations_closing')->label('Closing line')->rows(2)->maxLength(300),
                                    ]),

                                Section::make('What we do')
                                    ->description('Shown as a short list of trip types.')
                                    ->schema([
                                        TextInput::make('do_tag')->label('Eyebrow tag')->maxLength(60),
                                        TextInput::make('do_title')->label('Heading')->required()->maxLength(200)
                                            ->helperText('HTML like <em>…</em> is allowed for the italic word.'),
                                        Repeater::make('do_items')
                                            ->hiddenLabel()
                                            ->schema([
                                                TextInput::make('title')->required()->maxLength(60),
                                                Textarea::make('text')->rows(2)->required()->maxLength(300),
                                            ])
                                            ->addActionLabel('Add item')
                                            ->reorderable()
                                            ->itemLabel(fn (array $state): ?string => $state['title'] ?? null)
                                            ->collapsible(),
                                    ]),

                                Section::make('How we keep you safe')
                                    ->schema([
                                        TextInput::make('safety_tag')->label('Eyebrow tag')->maxLength(60),
                                        TextInput::make('safety_title')->label('Heading')->required()->maxLength(200)
                                            ->helperText('HTML like <em>…</em> is allowed for the italic word.'),
                                        Textarea::make('safety_lede')->label('Subheading')->rows(2)->maxLength(400),
                                        Repeater::make('safety_points')
                                            ->label('Points')
                                            ->simple(Textarea::make('value')->rows(2)->required())
                                            ->addActionLabel('Add point')
                                            ->reorderable(),
                                    ]),

                                Section::make('What we believe')
                                    ->description('Shown as a row of short belief statements.')
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

                                Section::make('100% Locally Owned')
                                    ->schema([
                                        TextInput::make('locally_owned_title')->label('Heading')->required()->maxLength(200),
                                        Textarea::make('locally_owned_text')->label('Text')->rows(3)->maxLength(600),
                                    ]),

                                Section::make('Closing line')
                                    ->description('A short line shown just above the bottom banner.')
                                    ->schema([
                                        Textarea::make('closing_text')->hiddenLabel()->rows(2)->maxLength(400),
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
KILI_DEPLOY_EOF
,
    'app/Filament/Pages/HomePageContent.php' => <<<'KILI_DEPLOY_EOF'
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
                        Tab::make('Hero')
                            ->schema([
                                Section::make('Hero video & content')
                                    ->description('The full-bleed video background at the very top of the homepage.')
                                    ->schema([
                                        FileUpload::make('hero_video')
                                            ->label('Background video')
                                            ->disk('site')
                                            ->directory('videos')
                                            ->visibility('public')
                                            ->acceptedFileTypes(['video/mp4'])
                                            ->maxSize(102400)
                                            ->helperText('MP4, ideally under ~30MB and no audio needed — it autoplays muted on loop (max 100MB). Leave blank to fall back to the photo below.')
                                            ->columnSpanFull(),
                                        FileUpload::make('hero_poster')
                                            ->label('Fallback / poster photo')
                                            ->image()
                                            ->disk('site')
                                            ->directory('images')
                                            ->visibility('public')
                                            ->imageEditor()
                                            ->helperText('Shown while the video loads, and instead of it if none is set.')
                                            ->columnSpanFull(),
                                        TextInput::make('hero_region')->label('Region badge')->maxLength(60),
                                        TextInput::make('hero_headline')->label('Headline')->required()->maxLength(100),
                                        TextInput::make('hero_subtitle')->label('Subtitle')->maxLength(150),
                                    ]),

                                Section::make('Stat band')
                                    ->description('The four numbers under the hero video.')
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
                                        FileUpload::make('intro_image')
                                            ->label('Photo (shown beside the text)')
                                            ->image()
                                            ->disk('site')
                                            ->directory('images')
                                            ->visibility('public')
                                            ->imageEditor()
                                            ->columnSpanFull(),
                                        Repeater::make('intro_paragraphs')
                                            ->label('Paragraphs')
                                            ->simple(
                                                Textarea::make('value')->rows(3)->required()
                                            )
                                            ->addActionLabel('Add paragraph')
                                            ->reorderable()
                                            ->helperText('<strong>…</strong> is allowed for bold route/park names.'),
                                        TextInput::make('intro_cta_label')
                                            ->label('Button text')
                                            ->maxLength(60)
                                            ->helperText('Leave blank to hide the button. Links to the Tours page.'),
                                    ]),
                            ]),

                        Tab::make('Why us')
                            ->schema([
                                Section::make('"Why Perfect Kilimanjaro" section')
                                    ->description('Shown right after "Who we are".')
                                    ->schema([
                                        TextInput::make('why_tag')->label('Eyebrow tag')->maxLength(60),
                                        TextInput::make('why_title')->label('Heading')->required()->maxLength(200)
                                            ->helperText('HTML like <em>…</em> is allowed for the italic word.'),
                                        Textarea::make('why_lede')->label('Subheading')->rows(2)->maxLength(400),
                                    ]),

                                Section::make('Reasons')
                                    ->schema([
                                        Repeater::make('why_items')
                                            ->hiddenLabel()
                                            ->schema([
                                                FileUpload::make('image')
                                                    ->label('Picture')
                                                    ->image()
                                                    ->disk('site')
                                                    ->directory('images/why')
                                                    ->visibility('public')
                                                    ->imageEditor()
                                                    ->helperText('Small photo shown on this card. Leave blank to show the default icon instead.')
                                                    ->columnSpanFull(),
                                                TextInput::make('title')->required()->maxLength(60),
                                                Textarea::make('text')->rows(2)->required()->maxLength(300),
                                            ])
                                            ->addActionLabel('Add reason')
                                            ->reorderable()
                                            ->itemLabel(fn (array $state): ?string => $state['title'] ?? null)
                                            ->collapsible(),
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

                        Tab::make('Video')
                            ->schema([
                                Section::make('"Take a glimpse" section')
                                    ->description('Shown below the activities. Hidden from the site entirely until a video ID is set.')
                                    ->schema([
                                        TextInput::make('video_tag')->label('Eyebrow tag')->maxLength(60),
                                        TextInput::make('video_title')->label('Heading')->required()->maxLength(200),
                                        TextInput::make('video_youtube_id')
                                            ->label('YouTube video ID')
                                            ->maxLength(20)
                                            ->helperText('The id from the video\'s URL — e.g. for youtube.com/watch?v=dQw4w9WgXcQ, that\'s "dQw4w9WgXcQ".'),
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
KILI_DEPLOY_EOF
,
    'app/Http/Controllers/TripController.php' => <<<'KILI_DEPLOY_EOF'
<?php

namespace App\Http\Controllers;

use App\Models\Package;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Response;
use Illuminate\View\View;

class TripController extends Controller
{
    public function show(string $trip): View
    {
        $package = Package::published()->with('stays')->where('slug', $trip)->first();

        abort_unless($package, 404);

        $related = Package::published()
            ->ordered()
            ->where('category', $package->category)
            ->whereKeyNot($package->getKey())
            ->take(3)
            ->get()
            ->map
            ->toTripArray()
            ->all();

        return view('trip', [
            'trip' => $package->toTripArray(),
            'related' => $related,
            'trips' => Package::forSite(),
        ]);
    }

    public function pdf(string $trip): Response
    {
        $package = Package::published()->with('stays')->where('slug', $trip)->first();

        abort_unless($package, 404);

        $pdf = Pdf::loadView('pdf.trip', ['trip' => $package->toTripArray()])
            ->setPaper('a4');

        return $pdf->download($trip.'.pdf');
    }
}
KILI_DEPLOY_EOF
,
    'app/Models/AboutContent.php' => <<<'KILI_DEPLOY_EOF'
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Editable copy for the dedicated /about and /team pages. A singleton like
 * HomeContent: one row, fetched through current(), with defaults() as the
 * fallback so the pages render sensibly before an admin has touched them.
 */
class AboutContent extends Model
{
    protected $table = 'about_content';

    protected $guarded = ['id'];

    protected $casts = [
        'story_paragraphs' => 'array',
        'statements' => 'array',
        'values' => 'array',
        'team_members' => 'array',
        'generations' => 'array',
        'do_items' => 'array',
        'safety_points' => 'array',
    ];

    public static function current(): self
    {
        return static::query()->first() ?? static::create(static::defaults());
    }

    /** @return array<string, mixed> */
    public static function defaults(): array
    {
        return [
            'about_hero_tag' => 'About us',
            'about_hero_title' => 'A small team, <em>run on the ground</em>',
            'about_hero_lede' => "Perfect Kilimanjaro is a small Arusha-based team running every safari, climb and Zanzibar trip in-house — not a booking platform reselling someone else's itinerary.",
            'about_hero_image' => 'images/hero-kilimanjaro.jpg',

            'story_title' => 'Our heritage',
            'story_heading' => 'Born on the Slopes of <em>Kilimanjaro</em>',
            'story_paragraphs' => [
                'Perfect Kilimanjaro is more than a tour company. It is the story of the Ngatambaku Mtui family, a Chagga family from Marangu Village, on the slopes of Kilimanjaro, whose lives have been tied to this mountain for three generations. Guiding is not just our profession. It is our inheritance.',
                'We come from Marangu, where the oldest route on the mountain begins and where the first recorded ascent in 1889 found its guide. Hans Meyer reached the summit with the help of Yohani Kinyala Lauwo, a young Chagga man from Marangu. Our people have lived at the foot of this mountain for centuries. We farmed its slopes, prayed at its waterfalls, and guided those who came to climb it. Kilimanjaro was never a mystery to us. It was home.',
            ],

            'statements' => [
                [
                    'title' => 'Our mission',
                    'text' => 'To run every Tanzania trip — safari, Kilimanjaro or Zanzibar — ourselves, at a fair price, with local guides and crew who are paid properly and treated as the experts they are.',
                ],
                [
                    'title' => 'Our vision',
                    'text' => "To be the operator Tanzanian guides recommend to their own families — proof that a locally-run team can compete with foreign-owned agencies without cutting corners on pay or safety.",
                ],
            ],

            'generations_tag' => 'Three generations',
            'generations_title' => 'Three Generations, <em>One Mountain</em>',
            'generations' => [
                [
                    'name' => 'Our grandfather',
                    'text' => 'Began guiding in the 1970s, when there were no tour operators, no websites, and no bookings. When visitors arrived at the park headquarters and asked who could take them up the mountain, the word went out to the villages, and men from Marangu who had known these trails all their lives stepped forward. He had no certificate and no agency, only his reputation and his knowledge of the mountain.',
                ],
                [
                    'name' => 'Our father, Peter Dominic Mtui',
                    'text' => 'Became a certified guide in 1983 and spent more than 30 years on Kilimanjaro. He learned the way great guides learn, by carrying loads, watching, and listening. His first lead climb came in a week of unusually heavy snow, when many guides turned back. He did not. Over the years, clients from Switzerland, Germany, and beyond kept returning and asking for him by name, writing letters to a parish in Marangu, which he walked to collect. That was the beginning of structured tourism on Kilimanjaro.',
                ],
                [
                    'name' => 'Priscus Peter Mtui, our owner and lead guide',
                    'text' => 'First summited at 14. At 17 he began as a porter, carrying luggage on more than 100 trips over seven years, before becoming a certified lead guide in 2012. He has now summited Kilimanjaro more than 350 times, every one of them leading a group. He has also cycled across Tanzania, pioneered the first Kilimanjaro biking expeditions in the early 2000s, and even led cyclists to the summit. His brother Augustine also guides on the mountain.',
                ],
            ],
            'generations_closing' => 'Together, our family has guided more than 1,000 groups to the summit.',

            'do_tag' => 'What we do',
            'do_title' => 'Every way to <em>see Tanzania</em>',
            'do_items' => [
                [
                    'title' => 'Kilimanjaro climbs',
                    'text' => 'All major routes, including the Daytime Summit option, with experienced local guides.',
                ],
                [
                    'title' => 'Safari adventures',
                    'text' => 'Midrange and luxury safaris, migration tours, and honeymoons.',
                ],
                [
                    'title' => 'Cycling expeditions',
                    'text' => 'From scenic tours around the mountain to summit challenges.',
                ],
                [
                    'title' => 'Beach and holiday extensions',
                    'text' => 'To Zanzibar and beyond — every journey tailored to your needs and your budget.',
                ],
            ],

            'safety_tag' => 'Safety',
            'safety_title' => 'How We Keep <em>You Safe</em>',
            'safety_lede' => 'Our motto has never changed: to take every client to the top of Africa, safely.',
            'safety_points' => [
                'Every guide is Wilderness First Responder (WFR) certified.',
                'Health checks begin on Day Zero, at the hotel in Moshi, and continue every morning and evening on the mountain.',
                'We use the Lake Louise Score and measure oxygen saturation and heart rate at every camp.',
                'We carry emergency oxygen and first-aid kits on every climb. Oxygen is for emergencies and the descent, not for going up.',
                'If your oxygen saturation falls below 75% and you show other altitude symptoms such as headache, nausea, dizziness, or extreme fatigue, we will bring you down to a lower elevation. No summit certificate is worth your life.',
                'When we say summit, we mean Uhuru Peak.',
            ],

            'values_tag' => 'What we believe',
            'values_heading' => 'What <em>We Believe</em>',
            'values' => [
                [
                    'title' => 'Your porters are people, not equipment',
                    'text' => 'Every porter and crew member is paid fair wages, eats well, and sleeps under proper shelter. They are the backbone of every successful climb.',
                ],
                [
                    'title' => 'Every climber deserves respect',
                    'text' => 'East African climbers receive the same guide, the same food, and the same care as every international guest, at the fees they actually qualify for. This mountain belongs to all of us.',
                ],
                [
                    'title' => 'Honest advice over easy sales',
                    'text' => 'We will tell you when a shorter route lowers your chances, which gear you should never rent, and what a climb really costs, even when it is not what you hoped to hear.',
                ],
                [
                    'title' => 'The mountain deserves respect',
                    'text' => 'Kilimanjaro is not a trophy. It is a living ecosystem and a sacred landmark for the Tanzanian people.',
                ],
            ],

            'locally_owned_title' => '100% Locally Owned',
            'locally_owned_text' => "Perfect Kilimanjaro is a Tanzanian, family-owned company with no income outflow. Everything is run in Tanzania, by Tanzanian guides, so every dollar you spend supports local guides, porters, cooks, drivers, and communities. Tourism makes up nearly 20% of Tanzania's economy, and traveling with us directly helps strengthen it.",

            'closing_text' => 'When you climb with us, you walk with fifty years of unbroken family knowledge, and you are never just a booking number. You are our guest on our mountain.',

            'about_cta_title' => 'Want to meet <em>the team?</em>',
            'about_cta_text' => 'See who plans and leads every trip, from our guides to our office crew in Arusha.',

            'team_hero_tag' => 'Our team',
            'team_hero_title' => 'The people <em>behind every trip</em>',
            'team_hero_lede' => 'A dozen safari guides, six Kilimanjaro crew leads, and a small Zanzibar-based team — all Tanzanian, all full-time, and all paid above the standard park-wage rates.',
            'team_members' => [],

            'team_cta_title' => 'Ready to plan <em>your trip?</em>',
            'team_cta_text' => "Tell us your dates, budget and what you want to see — we'll match you with the right guide and itinerary.",
        ];
    }
}
KILI_DEPLOY_EOF
,
    'app/Models/HomeContent.php' => <<<'KILI_DEPLOY_EOF'
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Editable copy for the public homepage. A singleton: exactly one row,
 * fetched through current(). Falls back to the site's original hardcoded
 * copy via defaults() so nothing on the page changes until an admin edits it.
 */
class HomeContent extends Model
{
    protected $table = 'home_content';

    protected $guarded = ['id'];

    protected $casts = [
        'hero_stats' => 'array',
        'intro_paragraphs' => 'array',
        'why_items' => 'array',
        'activities' => 'array',
        'faqs' => 'array',
    ];

    public static function current(): self
    {
        return static::query()->first() ?? static::create(static::defaults());
    }

    /** @return array<string, mixed> */
    public static function defaults(): array
    {
        return [
            'hero_video' => null,
            'hero_poster' => 'images/hero-kilimanjaro.jpg',
            'hero_region' => 'Kilimanjaro Region',
            'hero_headline' => 'Mount Kilimanjaro',
            'hero_subtitle' => "Africa's rooftop, five climate zones up",

            'hero_stats' => [
                ['num' => '3', 'suffix' => '', 'label' => 'Ways to explore'],
                ['num' => '180', 'suffix' => '+', 'label' => 'Trips run'],
                ['num' => '4.9', 'suffix' => '★', 'label' => 'Average rating'],
                ['num' => '<1', 'suffix' => 'hr', 'label' => 'Reply time'],
            ],

            'intro_tag' => 'Who we are',
            'intro_title' => 'Ready to conquer <em>Mount Kilimanjaro?</em>',
            'intro_image' => 'images/gal-kili-climbers.jpg',
            'intro_cta_label' => 'Plan Your Adventure Today',
            'intro_paragraphs' => [
                'Three generations. One family. Over 1,000 successful summits led to the top of Africa\'s highest peak.',
                'Since the 1970s, the Ngatambaku Mtui family has guided climbers up Kilimanjaro — starting with our grandfather, Dominic Mtui, continued by our father, Peter Mtui, and now led by Priscus, who has personally guided over 350 successful summits. Guiding isn\'t our business. It\'s our heritage.',
            ],

            'why_tag' => 'Why choose us',
            'why_title' => 'Why <em>Perfect Kilimanjaro</em>',
            'why_lede' => "What actually sets a family-run, in-house operator apart from a booking platform reselling someone else's itinerary.",
            'why_items' => [
                [
                    'title' => 'Three Generations of Kilimanjaro Guides',
                    'text' => "Local guides have been at the heart of Kilimanjaro since Hans Meyer's first ascent in 1889, and our family has guided here since the 1970s. Priscus, our owner and guide, first climbed at 14, worked as a porter for seven years, and has now summited more than 350 times.",
                    'image' => 'images/team/01M33YK3T3WAP2HQ22R6XW5M2R.webp',
                ],
                [
                    'title' => 'Safety First, Every Day',
                    'text' => 'Every guide is Wilderness First Responder (WFR) certified. We check your health every morning and evening with the Lake Louise Score and oxygen saturation readings. We carry emergency oxygen on every climb, and helicopter rescue is available with your travel insurance.',
                    'image' => 'images/gal-kili-summit.jpg',
                ],
                [
                    'title' => '100% Locally Owned',
                    'text' => 'There is no income outflow. Everything is run in Tanzania, with Tanzanian guides, so your money supports local jobs, families, and communities.',
                    'image' => 'images/gal-kili-climbers.jpg',
                ],
                [
                    'title' => 'Pioneers of Biking Adventures in Tanzania',
                    'text' => 'We were the first to introduce Kilimanjaro biking expeditions in the early 2000s, from scenic rides around the mountain to cycling all the way to the summit.',
                    'image' => 'images/hero-kilimanjaro.jpg',
                ],
            ],

            'pillars_tag' => 'What we run',
            'pillars_title' => 'Three trips. <em>One local team.</em>',
            'pillars_lede' => 'No third party brokers every itinerary below is planned and led by our own Tanzanian guides and crews.',

            'routes_tag' => 'Kilimanjaro routes',
            'routes_title' => 'Pick your <em>path up the mountain</em>',
            'routes_lede' => "Six ways up Africa's highest peak — trace each one's camps, gates and the way back down. We guide Machame, Lemosho and Marangu ourselves; the rest are here for reference.",

            'equip_title' => 'Kilimanjaro equipment list',
            'equip_description' => 'Everything you need to pack for the mountain — layer by layer, from rainforest trailhead to summit night at −15°C.',
            'equip_pdf' => 'documents/kilimanjaro-equipment-checklist.pdf',

            'trips_tag' => 'Trips & pricing',
            'trips_title' => 'Pick your <em>adventure</em>',
            'trips_lede' => 'All prices are per person, land cost only — park fees, guides, camps and meals on the trip included.',

            'activities_tag' => 'On the mountain',
            'activities_title' => 'Four moments that define <em>the climb</em>',
            'activities_lede' => 'The things climbers remember most, from the gate to Uhuru Peak.',
            'activities' => [
                [
                    'image' => 'images/hero-kilimanjaro.jpg',
                    'tag' => 'Kilimanjaro',
                    'eyebrow' => 'Day one to five',
                    'title' => 'Five climate zones',
                    'text' => "From rainforest at the gate through moorland and alpine desert to the arctic summit zone — each day on the mountain looks like a different country.",
                ],
                [
                    'image' => 'images/gal-kili-climbers.jpg',
                    'tag' => 'Barranco Wall',
                    'eyebrow' => 'The scramble',
                    'title' => 'Hands-on-rock terrain',
                    'text' => 'A short, exposed scramble up the Barranco Wall — the most talked-about hour on the mountain, and one every guide has climbed hundreds of times.',
                ],
                [
                    'image' => 'images/stay-kili.jpg',
                    'tag' => 'Camp life',
                    'eyebrow' => 'Every evening',
                    'title' => 'Camp life on the mountain',
                    'text' => "Hot meals cooked by your own crew, tents pitched before you arrive, and card games under more stars than you've ever seen.",
                ],
                [
                    'image' => 'images/gal-kili-summit.jpg',
                    'tag' => 'Uhuru Peak',
                    'eyebrow' => 'Midnight ascent',
                    'title' => 'The summit push',
                    'text' => 'A midnight climb through the dark to reach Uhuru Peak for sunrise — the single moment every route on the mountain is built around.',
                ],
            ],

            'video_tag' => 'Watch first',
            'video_title' => 'Take a glimpse into <em>the mountain</em>',
            'video_youtube_id' => null,

            'about_tag' => 'About us',
            'about_title' => 'Run by the people <em>on the ground</em>',
            'about_lede' => "Perfect Kilimanjaro is a small Arusha-based team, not a booking platform reselling someone else's itinerary.",
            'about_story_title' => 'Our story',
            'about_story_text' => 'Started by guides who spent years leading trips for foreign-owned operators and wanted travelers to see more of what they were actually paying for. We plan every itinerary in-house and work only with crews we know personally.',
            'about_team_title' => 'Our team',
            'about_team_text' => 'A dozen safari guides, six Kilimanjaro crew leads, and a small Zanzibar-based team — all Tanzanian, all full-time, and all paid above the standard park-wage rates.',
            'faqs' => [
                [
                    'question' => 'Do I need a visa to visit Tanzania?',
                    'answer' => 'Most nationalities can get an e-visa online before arrival, or a visa on arrival at Kilimanjaro International Airport. We send exact instructions once your trip is booked.',
                ],
                [
                    'question' => "When's the best time to see the migration?",
                    'answer' => 'River crossings typically run July–October in the northern Serengeti; calving season is January–March in the south. We track current herd positions and adjust your route accordingly.',
                ],
                [
                    'question' => 'Are these trips suitable for families?',
                    'answer' => "Yes — safaris and Zanzibar trips work well for all ages. Kilimanjaro climbs are better suited to kids 12+ who've done some hiking before.",
                ],
            ],

            'cta_title' => 'Not sure which trip <em>fits you?</em>',
            'cta_text' => "Tell us your dates, budget and what you want to see — we'll match you to the right itinerary and send a day-by-day plan.",
        ];
    }
}
KILI_DEPLOY_EOF
,
    'resources/views/about.blade.php' => <<<'KILI_DEPLOY_EOF'
@extends('layouts.site')

@section('title', 'About Us · Perfect Kilimanjaro')
@section('meta_description', Str::limit(strip_tags($about->about_hero_lede), 150))
@section('og_image', asset($about->about_hero_image))

@section('content')

<header class="trip-hero" id="top" style="--pc:var(--sun)">
  <div class="trip-hero-bg" style="background-image:url('{{ asset($about->about_hero_image) }}')"></div>
  <div class="trip-hero-overlay"></div>

  <div class="trip-hero-inner">
    <nav class="breadcrumb">
      <a href="{{ route('home') }}">Home</a>
      <span class="sep">/</span>
      <span class="current">About</span>
    </nav>

    <h1>{!! $about->about_hero_title !!}</h1>
    <p class="hero-lede">{{ $about->about_hero_lede }}</p>
  </div>
</header>

<section>
  <div class="wrap" style="max-width:900px">
    <div class="tag">{{ $about->story_title }}</div>
    <h2 class="section-title">{!! $about->story_heading !!}</h2>
    <div class="intro-copy">
      @foreach ($about->story_paragraphs as $paragraph)
        <p>{{ $paragraph }}</p>
      @endforeach
    </div>

    @if (filled($about->statements))
      <div class="about-grid">
        @foreach ($about->statements as $statement)
          <div class="about-block">
            <h3>{{ $statement['title'] }}</h3>
            <p>{{ $statement['text'] }}</p>
          </div>
        @endforeach
      </div>
    @endif
  </div>
</section>

@if (filled($about->generations))
<section style="padding-top:0">
  <div class="wrap" style="max-width:900px">
    <div class="tag">{{ $about->generations_tag }}</div>
    <h2 class="section-title">{!! $about->generations_title !!}</h2>

    <div class="compare-list">
      @foreach ($about->generations as $generation)
        <div class="compare-item">
          <div>
            <div class="compare-title">{{ $generation['name'] }}</div>
            <p>{{ $generation['text'] }}</p>
          </div>
        </div>
      @endforeach
    </div>

    @if (filled($about->generations_closing))
      <p style="margin-top:20px;font-weight:600">{{ $about->generations_closing }}</p>
    @endif
  </div>
</section>
@endif

@if (filled($about->do_items))
<section style="padding-top:0">
  <div class="wrap">
    <div class="tag">{{ $about->do_tag }}</div>
    <h2 class="section-title">{!! $about->do_title !!}</h2>

    <div class="about-grid">
      @foreach ($about->do_items as $item)
        <div class="about-block">
          <h3>{{ $item['title'] }}</h3>
          <p>{{ $item['text'] }}</p>
        </div>
      @endforeach
    </div>
  </div>
</section>
@endif

@if (filled($about->safety_points))
<section style="padding-top:0">
  <div class="wrap" style="max-width:900px">
    <div class="tag">{{ $about->safety_tag }}</div>
    <h2 class="section-title">{!! $about->safety_title !!}</h2>
    @if (filled($about->safety_lede))
      <p class="section-lede">{{ $about->safety_lede }}</p>
    @endif

    <div class="hl-list" style="margin-top:20px">
      @foreach ($about->safety_points as $point)
        <div class="hl-item">
          <span class="hl-icon">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
          </span>
          <span>{{ $point }}</span>
        </div>
      @endforeach
    </div>
  </div>
</section>
@endif

@if (filled($about->values))
<section style="padding-top:0">
  <div class="wrap">
    <div class="tag">{{ $about->values_tag }}</div>
    <h2 class="section-title" style="margin-bottom:40px">{!! $about->values_heading !!}</h2>
    <div class="trust-bar">
      @foreach ($about->values as $value)
        <div class="trust-item">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
          <strong>{{ $value['title'] }}</strong>
          <span>{{ $value['text'] }}</span>
        </div>
      @endforeach
    </div>
  </div>
</section>
@endif

@if (filled($about->locally_owned_text))
<section style="padding-top:0">
  <div class="wrap" style="max-width:900px">
    <h2 class="section-title">{{ $about->locally_owned_title }}</h2>
    <p>{{ $about->locally_owned_text }}</p>
  </div>
</section>
@endif

@if (filled($about->closing_text))
<section style="padding-top:0">
  <div class="wrap" style="max-width:900px">
    <p class="section-lede" style="font-size:17px">{{ $about->closing_text }}</p>
  </div>
</section>
@endif

<div class="cta-band">
  <div>
    <h3>{!! $about->about_cta_title !!}</h3>
    <p>{{ $about->about_cta_text }}</p>
  </div>
  <div class="cta-band-right">
    <a class="btn btn-fill" style="justify-content:center;padding:16px" href="{{ route('team') }}">
      Meet the team
      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
    </a>
  </div>
</div>

@endsection
KILI_DEPLOY_EOF
,
    'resources/views/layouts/site.blade.php' => <<<'KILI_DEPLOY_EOF'
<!DOCTYPE html>
<html lang="en" data-theme="{{ ($siteTheme ?? 'dark') === 'system' ? '' : ($siteTheme ?? 'dark') }}">
<head>
<script>
  /* Resolve "follow each visitor's device" before the first paint, so a light
     visitor never sees a flash of the dark palette (or the reverse). */
  (function () {
    var el = document.documentElement;
    if (!el.getAttribute('data-theme')) {
      el.setAttribute('data-theme',
        window.matchMedia('(prefers-color-scheme: light)').matches ? 'light' : 'dark');
    }
  })();
</script>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
@php
  $seoTitle = trim($__env->yieldContent('title', 'Perfect Kilimanjaro · Tanzania Safaris, Kilimanjaro & Zanzibar'));
  $seoDescription = trim($__env->yieldContent('meta_description', 'Locally owned safari, Kilimanjaro and Zanzibar trips out of Arusha, Tanzania.'));
  $seoImage = trim($__env->yieldContent('og_image', asset('images/hero-kilimanjaro.jpg')));
  $seoType = trim($__env->yieldContent('og_type', 'website')) ?: 'website';
@endphp
<title>{{ $seoTitle }}</title>
<meta name="description" content="{{ $seoDescription }}">
<link rel="canonical" href="{{ url()->current() }}">
<meta name="csrf-token" content="{{ csrf_token() }}">

<!-- Favicons -->
<link rel="icon" href="/favicon.ico" sizes="any">
<link rel="icon" href="/favicon-32x32.png" type="image/png" sizes="32x32">
<link rel="icon" href="/favicon-16x16.png" type="image/png" sizes="16x16">
<link rel="apple-touch-icon" href="/apple-touch-icon.png">
<link rel="manifest" href="/site.webmanifest">
<meta name="theme-color" content="#ff7a2f">

<!-- Open Graph / Facebook -->
<meta property="og:type" content="{{ $seoType }}">
<meta property="og:site_name" content="Perfect Kilimanjaro">
<meta property="og:url" content="{{ url()->current() }}">
<meta property="og:title" content="{{ $seoTitle }}">
<meta property="og:description" content="{{ $seoDescription }}">
<meta property="og:image" content="{{ $seoImage }}">
<meta property="og:locale" content="en_US">

<!-- Twitter Card -->
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{ $seoTitle }}">
<meta name="twitter:description" content="{{ $seoDescription }}">
<meta name="twitter:image" content="{{ $seoImage }}">

<!-- Organization structured data, sitewide, so Google can attribute reviews,
     contact details and sitelinks to one consistent entity across every
     page rather than re-deriving it per URL. -->
@php
  $reviewStats = \App\Models\Review::siteAggregate();
  $orgLd = [
      '@context' => 'https://schema.org',
      '@type' => 'TravelAgency',
      'name' => 'Perfect Kilimanjaro',
      'url' => route('home'),
      'logo' => asset('apple-touch-icon.png'),
      'image' => asset('images/hero-kilimanjaro.jpg'),
      'description' => 'Locally owned safari, Kilimanjaro and Zanzibar trips out of Arusha, Tanzania.',
      'telephone' => '+255752967222',
      'email' => 'hello@safiri.co.tz',
      'address' => [
          '@type' => 'PostalAddress',
          'addressLocality' => 'Arusha',
          'addressCountry' => 'TZ',
      ],
      'areaServed' => 'Tanzania',
  ];
  if ($reviewStats['count'] > 0) {
      $orgLd['aggregateRating'] = [
          '@type' => 'AggregateRating',
          'ratingValue' => $reviewStats['average'],
          'reviewCount' => $reviewStats['count'],
      ];
  }
@endphp
<script type="application/ld+json">{!! json_encode($orgLd, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Syne:wght@600;700;800&family=Instrument+Serif:ital@0;1&family=Space+Grotesk:wght@300;400;500;600&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
<style>
  *{margin:0;padding:0;box-sizing:border-box}
  html{scroll-behavior:smooth}
  /* Dark is the default palette. The light palette below overrides only the
     tokens that must flip, so every rule downstream is written once.
     --on-media stays white in both themes: it labels text and buttons that sit
     on top of photographs, which are dark regardless of the page theme. */
  :root, :root[data-theme="dark"]{
    --void:#05070d;
    --deep:#0a0f1a;
    --ink:#111826;
    --glow:#ff7a2f;
    --glow-soft:#ffb070;
    --sun:#ffd66b;
    --ice:#e8f1ff;
    --dim:#8a9bb5;
    --line:rgba(232,241,255,.08);
    --line-hi:rgba(232,241,255,.2);
    --glass:rgba(255,255,255,.04);
    --glass-2:rgba(255,255,255,.05);
    --glass-3:rgba(255,255,255,.08);
    --glass-4:rgba(255,255,255,.12);
    --glass-5:rgba(255,255,255,.18);
    --panel:linear-gradient(160deg,rgba(255,255,255,.05),rgba(255,255,255,.01));
    --panel-hi:linear-gradient(160deg,rgba(255,255,255,.055),rgba(255,255,255,.015));
    --scrim:rgba(5,7,13,.75);
    --scrim-soft:rgba(5,7,13,.6);
    --scrim-solid:rgba(5,7,13,.9);
    --band:linear-gradient(135deg,#1a0f2a,#0a0f1a 60%);
    --drawer:linear-gradient(180deg,#0a0f1a,#05070d);
    --panel-solid:rgba(10,15,26,.98);
    --hero-wash:linear-gradient(180deg,rgba(3,4,10,.15) 0%,rgba(3,4,10,.5) 55%,rgba(3,4,10,.95) 100%),
      linear-gradient(100deg,rgba(3,4,10,.65) 0%,rgba(3,4,10,.1) 45%,rgba(3,4,10,.4) 100%);
    --trip-wash:linear-gradient(180deg,rgba(3,4,10,.8) 0%,rgba(3,4,10,.3) 38%,rgba(3,4,10,.96) 100%),
      linear-gradient(90deg,rgba(3,4,10,.7) 0%,rgba(3,4,10,.05) 65%);
    --dot-idle:rgba(255,255,255,.3);
    --shadow:rgba(0,0,0,.6);
    --shadow-deep:rgba(0,0,0,.85);
    --on-media:#fff;
    --on-accent:#1a0a00;
    --on-pc:#0a0f1a;
    --grain:.35;
    --c-safari:#e0b04a;
    --c-kili:#ff7a2f;
    --c-zanzibar:#33c7c2;
    --c-east-africa:#5b8def;
    --c-biking:#6fcf6a;
    --accent:var(--c-safari);
  }

  :root[data-theme="light"]{
    --void:#f6f8fc;
    --deep:#eceff6;
    --ink:#ffffff;
    --glow:#d8541a;
    --glow-soft:#b8481a;
    --sun:#e0a017;
    --ice:#0e1624;
    --dim:#596880;
    --line:rgba(14,22,36,.11);
    --line-hi:rgba(14,22,36,.2);
    --glass:rgba(14,22,36,.035);
    --glass-2:rgba(14,22,36,.045);
    --glass-3:rgba(14,22,36,.07);
    --glass-4:rgba(14,22,36,.1);
    --glass-5:rgba(14,22,36,.14);
    --panel:linear-gradient(160deg,#ffffff,#f9fbfe);
    --panel-hi:linear-gradient(160deg,#ffffff,#f7fafd);
    --scrim:rgba(255,255,255,.82);
    --scrim-soft:rgba(255,255,255,.72);
    --scrim-solid:rgba(255,255,255,.94);
    --band:linear-gradient(135deg,#fff4ec,#eef2f8 60%);
    --drawer:linear-gradient(180deg,#ffffff,#f2f5fa);
    --panel-solid:rgba(255,255,255,.98);
    --hero-wash:linear-gradient(180deg,rgba(255,255,255,.62) 0%,rgba(255,255,255,.34) 48%,rgba(255,255,255,.95) 100%),
      linear-gradient(100deg,rgba(255,255,255,.72) 0%,rgba(255,255,255,.12) 45%,rgba(255,255,255,.45) 100%);
    --trip-wash:linear-gradient(180deg,rgba(255,255,255,.8) 0%,rgba(255,255,255,.4) 38%,rgba(255,255,255,.96) 100%),
      linear-gradient(90deg,rgba(255,255,255,.78) 0%,rgba(255,255,255,.1) 65%);
    --dot-idle:rgba(14,22,36,.25);
    --shadow:rgba(14,22,36,.14);
    --shadow-deep:rgba(14,22,36,.18);
    --grain:0;
    --on-pc:#ffffff;
    --c-safari:#9a6f0e;
    --c-kili:#d8541a;
    --c-zanzibar:#0f7b77;
    --c-east-africa:#2f5fc4;
    --c-biking:#2f8f3f;
  }

  /* These sit directly on an unmodified photograph, so they keep the dark-theme
     tokens even when the page is light — light text and glass are still the
     readable choice there. The heroes are not in this list: their gradient wash
     turns light too, so they follow the page palette instead. */
  :root[data-theme="light"] .trip-tag-overlay,
  :root[data-theme="light"] .gal-zoom,
  :root[data-theme="light"] .lightbox{
    --ice:#e8f1ff;
    --dim:#bcc8db;
    --line:rgba(232,241,255,.14);
    --line-hi:rgba(232,241,255,.28);
    --glass:rgba(255,255,255,.06);
    --glass-2:rgba(255,255,255,.07);
    --glass-3:rgba(255,255,255,.1);
    --glass-4:rgba(255,255,255,.14);
    --glass-5:rgba(255,255,255,.2);
    --scrim-soft:rgba(5,7,13,.6);
    --glow:#ff7a2f;
    --glow-soft:#ffb070;
    --sun:#ffd66b;
    --c-safari:#e0b04a;
    --c-kili:#ff7a2f;
    --c-zanzibar:#33c7c2;
    --c-east-africa:#5b8def;
    --c-biking:#6fcf6a;
    --accent:#e0b04a;
  }

  /* The stats band overlaps the foot of the hero photo, so a translucent white
     composites to grey. Near-solid keeps it reading as white. */
  :root[data-theme="light"] .hero-stats{
    --scrim-soft:rgba(255,255,255,.97);
  }
  body{
    font-family:'Space Grotesk',sans-serif;
    background:var(--void);
    color:var(--ice);
    overflow-x:hidden;
    -webkit-font-smoothing:antialiased;
  }
  .serif{font-family:'Instrument Serif',serif;font-style:italic}
  .syne{font-family:'Syne',sans-serif}
  .mono{font-family:'JetBrains Mono',monospace}
  svg{display:block}

  body::before{
    content:"";position:fixed;inset:0;pointer-events:none;z-index:100;opacity:var(--grain);
    background-image:url("data:image/svg+xml,%3Csvg viewBox='0 0 200 200' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='3'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)' opacity='0.5'/%3E%3C/svg%3E");
    mix-blend-mode:overlay;
  }

  #nav{
    position:fixed;top:0;left:0;right:0;z-index:60;
    display:flex;align-items:center;justify-content:space-between;
    padding:20px 5vw;transition:.4s cubic-bezier(.4,0,.2,1);
  }
  #nav.solid{
    background:var(--scrim);backdrop-filter:blur(20px) saturate(180%);
    -webkit-backdrop-filter:blur(20px) saturate(180%);
    border-bottom:1px solid var(--line);padding:14px 5vw;
  }
  .brand{display:flex;align-items:center;gap:12px;cursor:pointer;text-decoration:none}
  .brand-mark{
    width:36px;height:36px;border-radius:50%;position:relative;
    background:radial-gradient(circle at 30% 30%,var(--sun),var(--glow) 60%,#7a2e00);
    box-shadow:0 0 24px rgba(255,122,47,.5),inset 0 0 12px rgba(255,255,255,.3);
    display:flex;align-items:center;justify-content:center;flex-shrink:0;
  }
  .brand-mark::after{
    content:"";position:absolute;inset:-6px;border-radius:50%;
    border:1px solid rgba(255,122,47,.3);animation:pulse 3s ease-in-out infinite;
  }
  @keyframes pulse{0%,100%{transform:scale(1);opacity:.6}50%{transform:scale(1.15);opacity:0}}
  .brand-name{font-family:'Syne',sans-serif;font-size:20px;font-weight:700;letter-spacing:-.02em;color:var(--ice);white-space:nowrap}
  .brand-name span{color:var(--glow)}
  /* Two-word wordmark: step it down before it can crowd the nav links or,
     on a phone, the "Plan my trip" button. */
  @media(max-width:1240px){ .brand-name{font-size:18px} }
  @media(max-width:560px){ .brand-name{font-size:16px} }
  @media(max-width:430px){
    .brand{gap:9px}
    /* Below this the one-line wordmark pushes the hamburger off-screen, so in
       the bar only it stacks to two lines. min-content breaks it at the space. */
    #nav .brand-name{font-size:15px;white-space:normal;width:min-content;line-height:1.04}
  }
  .nav-links{display:flex;align-items:center;gap:6px;position:relative}
  .nav-item{position:static}
  .nav-top{
    display:flex;align-items:center;gap:6px;
    color:var(--dim);text-decoration:none;font-size:14px;font-weight:400;
    cursor:pointer;transition:.2s;letter-spacing:.01em;padding:10px 14px;
  }
  .nav-top{white-space:nowrap}
  .nav-top svg{transition:.25s;opacity:.6}
  .nav-item:hover .nav-top{color:var(--ice)}
  .nav-item:hover .nav-top svg{transform:rotate(180deg);opacity:1}

  .dropdown-panel{
    position:absolute;top:calc(100% + 4px);left:50%;
    transform:translateX(-50%) translateY(6px);width:700px;
    background:var(--panel-solid);border:1px solid var(--line);border-radius:20px;
    padding:24px;opacity:0;pointer-events:none;transition:.22s ease;
    backdrop-filter:blur(20px);box-shadow:0 24px 60px -20px var(--shadow);z-index:70;
    display:grid;grid-template-columns:170px 1fr 190px;gap:24px;
  }
  .dropdown-panel.no-photo{grid-template-columns:170px 1fr;width:460px}
  .nav-item:hover .dropdown-panel{opacity:1;pointer-events:auto;transform:translateX(-50%) translateY(0)}

  .dropdown-links{display:flex;flex-direction:column;gap:1px}
  .dropdown-links a{
    display:block;padding:9px 10px;border-radius:8px;color:var(--dim);
    font-size:12px;font-weight:600;letter-spacing:.03em;text-transform:uppercase;
    text-decoration:none;transition:.15s;cursor:pointer;
  }
  .dropdown-links a:first-child{color:var(--glow-soft)}
  .dropdown-links a:hover{background:rgba(255,122,47,.08);color:var(--ice)}

  .dropdown-content{
    display:flex;flex-direction:column;justify-content:center;
    border-left:1px solid var(--line);border-right:1px solid var(--line);padding:0 24px;
  }
  .dropdown-panel.no-photo .dropdown-content{border-right:none}
  .dropdown-content h4{font-family:'Syne',sans-serif;font-weight:700;font-size:21px;letter-spacing:-.02em;margin-bottom:10px;color:var(--ice)}
  .dropdown-content p{color:var(--dim);font-size:13px;line-height:1.6;margin-bottom:18px}
  .dropdown-cta{
    display:inline-flex;align-items:center;gap:8px;width:fit-content;
    padding:11px 18px;border-radius:100px;border:1px solid var(--line);
    background:var(--glass);color:var(--ice);font-size:12px;font-weight:500;
    letter-spacing:.04em;text-transform:uppercase;text-decoration:none;cursor:pointer;transition:.2s;
  }
  .dropdown-cta:hover{background:rgba(255,122,47,.1);border-color:rgba(255,122,47,.35);color:var(--glow-soft)}

  .dropdown-photo{border-radius:14px;overflow:hidden;background:var(--ink);cursor:pointer}
  .dropdown-photo img{width:100%;height:100%;object-fit:cover;display:block;min-height:180px}

  .nav-right{display:flex;align-items:center;gap:12px}
  /* The wordmark leaves the bar tight on a phone, so the CTA must never wrap
     into a blob — it shortens to "Plan" instead. */
  .nav-cta{white-space:nowrap;flex-shrink:0}
  .nav-cta-short{display:none}
  @media(max-width:560px){
    .nav-cta{padding:10px 16px;font-size:13px}
    .nav-cta-long{display:none}
    .nav-cta-short{display:inline}
  }
  .hamburger{
    display:none;width:40px;height:40px;border-radius:50%;flex-shrink:0;
    background:var(--glass);border:1px solid var(--line);color:var(--ice);
    align-items:center;justify-content:center;cursor:pointer;transition:.2s;
  }
  .hamburger:hover{background:var(--glass-3)}

  /* Raised from 1080px: the longer wordmark leaves too little room for six
     links between roughly 1080 and 1200, where they used to crowd it. */
  @media(max-width:1199px){
    .nav-links{display:none}
    .hamburger{display:flex}
  }

  .mnav-group{border-bottom:1px solid var(--line)}
  .mnav-group:first-child{border-top:1px solid var(--line)}
  .mnav-head{
    width:100%;background:none;border:none;color:var(--ice);
    font-family:'Syne',sans-serif;font-weight:600;font-size:15px;
    padding:16px 2px;display:flex;justify-content:space-between;align-items:center;cursor:pointer;
  }
  .mnav-head svg{transition:.25s;color:var(--dim)}
  .mnav-head.open svg{transform:rotate(180deg)}
  .mnav-body{max-height:0;overflow:hidden;transition:.3s ease}
  .mnav-body.open{max-height:260px;padding-bottom:10px}
  .mnav-body a{
    display:block;padding:9px 2px 9px 12px;color:var(--dim);
    font-size:13.5px;text-decoration:none;cursor:pointer;transition:.15s;
  }
  .mnav-body a:hover{color:var(--glow-soft)}
  .mnav-link{
    display:flex;align-items:center;padding:16px 2px;color:var(--ice);
    font-family:'Syne',sans-serif;font-weight:600;font-size:15px;text-decoration:none;
  }
  .mnav-link:hover{color:var(--glow-soft)}

  .btn{
    border:none;cursor:pointer;font-family:'Space Grotesk',sans-serif;
    font-weight:500;font-size:14px;letter-spacing:.01em;
    padding:13px 24px;border-radius:100px;transition:.3s;
    display:inline-flex;align-items:center;gap:8px;text-decoration:none;
  }
  .btn-fill{
    background:linear-gradient(135deg,var(--sun),var(--glow));
    color:#1a0a00;box-shadow:0 8px 30px rgba(255,122,47,.35);
  }
  .btn-fill:hover{transform:translateY(-2px);box-shadow:0 12px 40px rgba(255,122,47,.5)}
  .btn-line{
    background:var(--glass);color:var(--ice);
    border:1px solid var(--line);backdrop-filter:blur(10px);
  }
  .btn-line:hover{background:var(--glass-3);border-color:var(--line-hi)}
  .nav .btn-fill{padding:10px 20px;font-size:13px}

  .hero{
    min-height:100vh;position:relative;overflow:hidden;
    display:flex;flex-direction:column;justify-content:flex-end;
    padding:0 5vw 100px;
  }
  .hero-video{
    position:absolute;inset:0;width:100%;height:100%;object-fit:cover;
  }
  .hero-overlay{
    position:absolute;inset:0;z-index:1;pointer-events:none;
    background:var(--hero-wash);
  }

  .hero-inner{position:relative;z-index:3;max-width:1400px;margin:0 auto;width:100%}
  .hero-eyebrow{
    display:inline-flex;align-items:center;gap:10px;
    font-family:'JetBrains Mono',monospace;font-size:12px;
    color:var(--glow-soft);letter-spacing:.15em;text-transform:uppercase;
    padding:8px 16px;border-radius:100px;
    background:rgba(255,122,47,.08);border:1px solid rgba(255,122,47,.25);
    backdrop-filter:blur(10px);margin-bottom:18px;
    animation:fadeUp .8s ease both;
  }

  .hero h1{
    font-family:'Syne',sans-serif;font-weight:700;
    font-size:clamp(36px,6vw,84px);line-height:1;max-width:14ch;
    letter-spacing:-.04em;margin-bottom:14px;
    animation:fadeUp .9s .1s ease both;
  }
  .hero h1 em{
    font-family:'Instrument Serif',serif;font-style:italic;font-weight:400;
    background:linear-gradient(135deg,var(--sun),var(--glow) 60%,#ff9d5c);
    -webkit-background-clip:text;background-clip:text;color:transparent;
    display:inline-block;
  }
  .hero-lede{
    font-size:clamp(15px,1.3vw,18px);color:var(--dim);max-width:54ch;
    line-height:1.5;margin-bottom:28px;font-weight:300;
    animation:fadeUp 1s .2s ease both;
  }
  .hero-lede strong{color:var(--ice);font-weight:500}
  .hero-price-row{
    display:flex;align-items:center;justify-content:space-between;gap:20px;
    max-width:440px;margin-bottom:28px;flex-wrap:wrap;
    animation:fadeUp 1s .25s ease both;
  }
  .hero-rating{display:flex;align-items:center;gap:7px;font-size:14px;color:var(--ice)}
  .hero-rating svg{color:var(--sun);flex-shrink:0}
  .hero-rating .count{color:var(--dim);font-weight:300}
  .hero-price{font-family:'Syne',sans-serif;font-weight:700;font-size:22px;color:var(--ice);text-align:right}
  .hero-price small{
    display:block;font-family:'Space Grotesk',sans-serif;font-size:10px;color:var(--dim);
    font-weight:400;text-transform:uppercase;letter-spacing:.08em;margin-top:2px;
  }
  .hero-ctas{display:flex;gap:14px;flex-wrap:wrap;align-items:center;animation:fadeUp 1s .3s ease both}
  .hero-icon-btn{
    width:48px;height:48px;border-radius:50%;flex-shrink:0;
    display:flex;align-items:center;justify-content:center;
    background:var(--glass);border:1px solid var(--line);backdrop-filter:blur(10px);
    color:var(--ice);cursor:pointer;transition:.25s;
  }
  .hero-icon-btn:hover{background:var(--glass-4);border-color:var(--line-hi)}
  @keyframes fadeUp{from{opacity:0;transform:translateY(30px)}to{opacity:1;transform:translateY(0)}}

  .hero-stats{
    position:absolute;bottom:0;left:0;right:0;z-index:4;
    display:grid;grid-template-columns:repeat(4,1fr);
    border-top:1px solid var(--line);
    background:var(--scrim-soft);backdrop-filter:blur(20px);
  }
  .stat{padding:22px 5vw;border-right:1px solid var(--line);transition:.3s}
  .stat:last-child{border-right:none}
  .stat:hover{background:rgba(255,122,47,.05)}
  .stat-num{
    font-family:'Syne',sans-serif;font-weight:700;font-size:clamp(22px,2.2vw,34px);
    letter-spacing:-.03em;color:var(--ice);line-height:1;
  }
  .stat-num em{font-family:'Instrument Serif',serif;font-style:italic;
    color:var(--glow);font-weight:400;font-size:.7em;margin-left:2px}
  .stat-label{font-family:'JetBrains Mono',monospace;font-size:10.5px;
    color:var(--dim);letter-spacing:.11em;text-transform:uppercase;margin-top:8px}

  section{padding:120px 5vw;position:relative}
  .wrap{max-width:1400px;margin:0 auto}
  .reveal{opacity:0;transform:translateY(24px);transition:opacity .7s ease,transform .7s ease}
  .reveal.in{opacity:1;transform:translateY(0)}

  .tag{
    display:inline-flex;align-items:center;gap:8px;
    font-family:'JetBrains Mono',monospace;font-size:11px;
    color:var(--glow-soft);letter-spacing:.18em;text-transform:uppercase;
    margin-bottom:24px;
  }
  .tag::before{content:"";width:24px;height:1px;background:var(--glow)}

  h2.section-title{
    font-family:'Syne',sans-serif;font-weight:700;
    font-size:clamp(34px,4.6vw,64px);line-height:1.04;
    letter-spacing:-.03em;margin-bottom:20px;max-width:22ch;
  }
  h2.section-title em{
    font-family:'Instrument Serif',serif;font-style:italic;font-weight:400;
    color:var(--glow-soft);
  }
  .section-lede{color:var(--dim);font-size:16.5px;line-height:1.65;max-width:58ch;font-weight:300}

  /* intro / positioning statement, right under the hero */
  #intro{padding-top:88px;padding-bottom:88px}
  /* Guaranteed gutter: the section's own 5vw padding shrinks away at medium
     widths where the column nearly fills it, so the text can end up hugging
     the edge. This keeps a floor of breathing room no matter the viewport. */
  /* "Ready to conquer" intro — photo beside the story, not a centered
     single column, so the family-heritage copy reads as an editorial
     feature rather than another stat-and-text block. */
  .intro-split{display:grid;grid-template-columns:1.3fr 0.85fr;gap:64px;align-items:center}
  .intro-split-media{border-radius:24px;overflow:hidden;aspect-ratio:4/5;box-shadow:0 30px 70px -30px rgba(0,0,0,.6)}
  .intro-split-media img{width:100%;height:100%;object-fit:cover;display:block}
  .intro-split-text .section-title{margin-bottom:22px}
  .intro-split-text .btn{margin-top:8px}
  /* Paragraphs run together as one continuous block — no gap between them
     beyond the normal line-height, so it reads as a single passage rather
     than separate chunks. */
  .intro-copy{color:var(--dim);font-size:16px;line-height:1.8;font-weight:300;margin-bottom:28px}
  .intro-copy p{margin:0 0 20px}
  .intro-copy p:last-child{margin-bottom:0}
  .intro-copy strong{color:var(--ice);font-weight:500}

  @media(max-width:900px){
    .intro-split{grid-template-columns:1fr;gap:32px}
    .intro-split-media{aspect-ratio:16/10;order:-1}
  }

  /* /blog/{post} — rich text body from the admin's editor */
  .article-body{color:var(--dim);font-size:16px;line-height:1.85}
  .article-body h2{font-family:'Syne',sans-serif;font-weight:700;font-size:26px;color:var(--ice);margin:44px 0 16px;letter-spacing:-.02em}
  .article-body h3{font-family:'Syne',sans-serif;font-weight:600;font-size:20px;color:var(--ice);margin:32px 0 12px}
  .article-body p{margin:0 0 20px}
  .article-body a{color:var(--glow-soft)}
  .article-body ul,.article-body ol{margin:0 0 20px;padding-left:22px}
  .article-body li{margin-bottom:8px}
  .article-body img{width:100%;border-radius:16px;margin:28px 0}
  .article-body blockquote{
    margin:28px 0;padding:4px 24px;border-left:3px solid var(--glow);
    color:var(--ice);font-family:'Instrument Serif',serif;font-style:italic;font-size:19px;
  }
  .article-body strong{color:var(--ice);font-weight:600}

  /* /team page — team member grid */
  .team-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(230px,1fr));gap:24px}
  .team-card{
    background:var(--panel);border:1px solid var(--line);border-radius:18px;padding:26px 22px;text-align:center;
  }
  .team-photo{
    width:88px;height:88px;border-radius:50%;object-fit:cover;margin:0 auto 16px;display:block;
  }
  .team-photo-fallback{
    display:flex;align-items:center;justify-content:center;
    background:linear-gradient(135deg,var(--sun),var(--glow));color:#1a0a00;
    font-family:'Syne',sans-serif;font-weight:700;font-size:26px;
  }
  .team-name{font-family:'Syne',sans-serif;font-weight:700;font-size:16px;margin-bottom:3px}
  .team-role{color:var(--glow-soft);font-size:12px;font-family:'JetBrains Mono',monospace;letter-spacing:.04em;margin-bottom:12px}
  .team-bio{color:var(--dim);font-size:13.5px;line-height:1.6}

  /* pillars */
  .pillars-grid{
    display:grid;grid-template-columns:repeat(4,1fr);gap:20px;margin-top:64px;
  }
  .pillar{
    --pc:var(--c-safari);
    padding:36px 30px;border-radius:24px;position:relative;overflow:hidden;cursor:pointer;
    background:var(--panel);
    border:1px solid var(--line);backdrop-filter:blur(10px);
    transition:.4s cubic-bezier(.2,.8,.3,1);
  }
  .pillar:hover{transform:translateY(-6px);border-color:color-mix(in srgb,var(--pc) 45%,transparent);
    box-shadow:0 30px 70px -30px color-mix(in srgb,var(--pc) 55%,transparent)}
  .pillar-icon{
    width:52px;height:52px;border-radius:16px;margin-bottom:24px;
    background:color-mix(in srgb,var(--pc) 16%,transparent);
    border:1px solid color-mix(in srgb,var(--pc) 30%,transparent);
    display:flex;align-items:center;justify-content:center;color:var(--pc);
  }
  .pillar h3{font-family:'Syne',sans-serif;font-weight:600;font-size:23px;letter-spacing:-.02em;margin-bottom:12px}
  .pillar p{color:var(--dim);font-size:14.5px;line-height:1.65;margin-bottom:22px}
  .pillar-foot{display:flex;justify-content:space-between;align-items:center;padding-top:20px;border-top:1px solid var(--line)}
  .pillar-from{font-family:'JetBrains Mono',monospace;font-size:12px;color:var(--dim)}
  .pillar-from b{color:var(--ice);font-size:15px;font-family:'Syne',sans-serif}
  .pillar-arrow{
    width:36px;height:36px;border-radius:50%;background:var(--glass-2);
    border:1px solid var(--line);display:flex;align-items:center;justify-content:center;
    color:var(--ice);transition:.3s;
  }
  .pillar:hover .pillar-arrow{background:var(--pc);color:#1a0a00;border-color:transparent;transform:rotate(-45deg)}

  /* Kilimanjaro route explorer — an illustrated topographic-style map with an
     animated path per route, on its own dark "backdrop" card so the parchment
     map reads the same way in either site theme. Waypoint positions and route
     copy are fixed reference content (approximate, per the on-map disclaimer)
     rather than pulled from booking data — a deliberately different, more
     editorial treatment than the rest of the site. */
  #routes{padding-top:0}
  .route-explorer{
    margin-top:36px;border-radius:28px;padding:20px;
    background:linear-gradient(160deg,#1c231d,#141a15);border:1px solid var(--line);
  }
  .route-stats-row{display:flex;gap:10px;flex-wrap:wrap;margin-bottom:14px}
  .route-stat-pill{
    background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.12);
    border-radius:100px;padding:8px 16px;font-size:12.5px;color:#d7dccf;
    display:flex;flex-direction:column;min-width:88px;
  }
  .route-stat-pill b{font-family:'Syne',sans-serif;font-size:17px;color:#f2efe4;font-weight:700}

  .route-explorer-panel{display:grid;grid-template-columns:220px minmax(0,1fr);gap:20px;align-items:start}

  .route-nav{display:flex;flex-direction:column;gap:8px}
  .route-nav button{
    all:unset;cursor:pointer;display:flex;align-items:center;gap:10px;box-sizing:border-box;
    padding:13px 16px;border-radius:10px;background:rgba(255,255,255,.04);
    border:1px solid rgba(255,255,255,.08);color:#c7cdbe;
    font-family:'Space Grotesk',sans-serif;font-size:14px;font-weight:500;
    transition:background .18s ease,border-color .18s ease,color .18s ease,transform .18s ease;
  }
  .route-nav button:hover{background:rgba(255,255,255,.08);transform:translateX(2px)}
  .route-nav button .dot{width:7px;height:7px;border-radius:50%;background:#5c6656;flex-shrink:0}
  .route-nav button[aria-selected="true"]{
    background:linear-gradient(100deg,var(--c-kili),#a8431a);border-color:transparent;color:#fff;
  }
  .route-nav button[aria-selected="true"] .dot{background:var(--sun);box-shadow:0 0 0 3px rgba(255,214,107,.28)}
  .route-nav button .sub{margin-left:auto;font-size:11px;opacity:.75;font-weight:400}

  .route-map-card{
    background:#e9e5d6;border-radius:18px;padding:14px 14px 0;position:relative;overflow:hidden;
    box-shadow:0 30px 60px -25px rgba(0,0,0,.6);
  }
  /* Capped rather than filling the column width — the full-size map needed
     more scrolling than a visitor would give it — but not so capped that the
     camp labels stop being readable; legibility wins over fitting one screen
     exactly. height:auto below the cap still lets it shrink on narrow screens. */
  .route-map-card svg{display:block;width:auto;height:min(64vh,560px);max-width:100%;margin:0 auto}
  .route-map-legend{
    position:absolute;top:16px;right:18px;background:rgba(255,255,255,.78);backdrop-filter:blur(4px);
    border:1px solid rgba(0,0,0,.08);border-radius:10px;padding:7px 11px;font-size:10.5px;
    color:#5b6157;display:flex;gap:12px;font-family:'Space Grotesk',sans-serif;
  }
  .route-map-legend span{display:flex;align-items:center;gap:6px}
  .route-map-legend svg{width:18px;height:8px;flex-shrink:0}

  .route-desc-card{background:#e3ded0;border-radius:14px;padding:16px 20px 18px;margin:12px 0 0;color:#20261f}
  .route-desc-card h3{
    font-family:'Syne',sans-serif;font-weight:700;font-size:18px;margin:0 0 3px;
    display:flex;align-items:center;gap:8px;
  }
  .route-desc-card h3 svg{width:15px;height:15px;flex-shrink:0;color:#3f6b4a}
  .route-desc-meta{font-size:12px;color:#5b6157;margin-bottom:9px;font-family:'JetBrains Mono',monospace}
  .route-desc-card p{font-size:13.5px;line-height:1.55;margin:0 0 10px;color:#3a4136;max-width:64ch}
  .route-view{
    display:inline-flex;align-items:center;gap:6px;color:#8a4423;text-decoration:none;
    font-size:13.5px;font-weight:600;
  }
  .route-view:hover{color:#5b2f13}
  .route-note{color:var(--dim);font-size:11px;margin:10px 0 0;font-style:italic}

  /* Referenced directly inside the JS-generated SVG string, so these names
     stay fixed rather than following the site's usual --token pattern. */
  .marker-label{font-family:'Space Grotesk',sans-serif;font-size:12.5px;font-weight:600;fill:#20261f}
  .marker-elev{font-family:'Space Grotesk',sans-serif;font-size:10.5px;fill:#5b6157}
  .marker-label-dim,.marker-elev-dim{fill:#9aa091}
  .peak-label{font-family:'Syne',sans-serif;font-weight:700;font-size:15.5px;fill:#20261f}

  .equip-banner{
    margin-top:20px;border-radius:28px;padding:32px 40px;position:relative;overflow:hidden;
    background:linear-gradient(135deg,#151d2c,var(--void));border:1px solid var(--line);
    display:flex;align-items:center;gap:32px;
  }
  .equip-glow{
    position:absolute;top:-60px;right:-40px;width:280px;height:280px;border-radius:50%;
    background:radial-gradient(circle,rgba(255,122,47,.3),transparent 70%);pointer-events:none;
  }
  .equip-icon{
    flex-shrink:0;width:96px;height:120px;background:#f7f5ec;border-radius:12px 12px 12px 2px;
    box-shadow:0 18px 34px -12px rgba(0,0,0,.55);position:relative;
    display:flex;flex-direction:column;align-items:center;justify-content:center;gap:8px;
    color:var(--deep);transform:rotate(-3deg);
  }
  .equip-icon::before{
    content:"";position:absolute;top:0;right:0;width:16px;height:16px;
    background:#e4e0d0;border-radius:0 12px 0 12px;
  }
  .equip-icon-label{font-family:'JetBrains Mono',monospace;font-size:11px;font-weight:700;letter-spacing:.1em}
  .equip-body{position:relative;min-width:0}
  .equip-body h3{font-family:'Syne',sans-serif;font-weight:700;font-size:clamp(20px,2.2vw,26px);color:var(--sun);margin-bottom:8px}
  .equip-body p{color:var(--dim);font-size:14px;line-height:1.6;max-width:52ch;margin-bottom:20px}
  .equip-download{font-family:'JetBrains Mono',monospace;font-size:12px;letter-spacing:.1em;text-transform:uppercase}

  @media(max-width:640px){
    .equip-banner{flex-direction:column;align-items:flex-start;padding:26px}
  }

  @media(max-width:860px){
    .route-explorer-panel{grid-template-columns:1fr}
    .route-nav{flex-direction:row;overflow-x:auto;gap:6px;padding-bottom:2px}
    .route-nav button{white-space:nowrap}
    .route-nav button .sub{display:none}
    /* The height cap above assumes a wide column with room to spare; on a
       narrow single-column layout max-width:100% would fight it and shrink
       the map far more than intended. Filling the width and following the
       aspect ratio reads better here — the page is already a long scroll.
       Scoped to the map itself (#kiliMap), not ".route-map-card svg" — that
       broader selector also matches the small Ascent/Descent legend icons
       and, being later in the cascade, would stretch them full-width too. */
    #kiliMap{width:100%;height:auto}
  }

  /* trips / tabs */
  .trips-head{display:flex;justify-content:space-between;align-items:flex-end;gap:30px;flex-wrap:wrap;margin-bottom:40px}
  .tabs{display:flex;gap:8px;padding:6px;border-radius:100px;background:var(--glass);border:1px solid var(--line);width:fit-content}
  .tab{
    border:none;background:none;color:var(--dim);font-family:'Space Grotesk',sans-serif;
    font-size:13.5px;font-weight:500;padding:10px 20px;border-radius:100px;cursor:pointer;
    transition:.3s;display:flex;align-items:center;gap:8px;
  }
  .tab[data-active="true"]{background:var(--accent);color:#1a0a00;box-shadow:0 6px 20px color-mix(in srgb,var(--accent) 50%,transparent)}
  .tab:not([data-active="true"]):hover{color:var(--ice)}

  .trips-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:20px}
  .trip{
    --pc:var(--accent);
    position:relative;border-radius:24px;padding:0;
    background:var(--panel);
    border:1px solid var(--line);backdrop-filter:blur(10px);
    display:flex;flex-direction:column;overflow:hidden;
    transition:.4s cubic-bezier(.2,.8,.3,1);cursor:pointer;
    animation:fadeUp .5s ease both;color:inherit;text-decoration:none;
  }
  .trip::after{
    content:"";position:absolute;inset:0;border-radius:24px;pointer-events:none;
    background:radial-gradient(circle at var(--mx,50%) var(--my,50%),color-mix(in srgb,var(--pc) 18%,transparent),transparent 40%);
    opacity:0;transition:opacity .3s;z-index:2;
  }
  .trip:hover::after{opacity:1}
  .trip:hover{transform:translateY(-6px);border-color:color-mix(in srgb,var(--pc) 40%,transparent);
    box-shadow:0 30px 70px -30px color-mix(in srgb,var(--pc) 45%,transparent)}

  .trip-media{position:relative;aspect-ratio:16/11;overflow:hidden;flex-shrink:0}
  .trip-media img{width:100%;height:100%;object-fit:cover;display:block;transition:.6s cubic-bezier(.2,.8,.3,1)}
  .trip:hover .trip-media img{transform:scale(1.06)}
  .trip-tag-overlay{
    position:absolute;top:14px;left:14px;padding:6px 12px;border-radius:100px;
    background:var(--scrim-soft);backdrop-filter:blur(8px);border:1px solid var(--glass-4);
    color:#fff;font-family:'JetBrains Mono',monospace;font-size:10px;letter-spacing:.1em;text-transform:uppercase;
  }
  .trip-rating-overlay{
    position:absolute;top:14px;right:14px;padding:6px 11px;border-radius:100px;
    background:rgba(255,255,255,.94);color:#1a0a00;
    font-family:'Syne',sans-serif;font-weight:700;font-size:12px;
    display:flex;align-items:center;gap:4px;
  }
  .trip-rating-overlay svg{color:#ff9d00}

  .trip-body{padding:26px;display:flex;flex-direction:column;flex-grow:1}
  .trip-nick{font-family:'Instrument Serif',serif;font-style:italic;font-size:14.5px;color:var(--pc);margin-bottom:8px}
  .trip-name{font-family:'Syne',sans-serif;font-weight:600;font-size:22px;letter-spacing:-.02em;line-height:1.15;margin-bottom:16px}
  .trip-metrics{display:flex;gap:8px;flex-wrap:wrap;margin-bottom:16px}
  .metric{
    font-family:'JetBrains Mono',monospace;font-size:11px;padding:6px 10px;border-radius:8px;
    background:var(--glass-2);border:1px solid var(--line);color:var(--dim);letter-spacing:.03em;
  }
  .metric.accent{color:var(--pc);border-color:color-mix(in srgb,var(--pc) 35%,transparent);background:color-mix(in srgb,var(--pc) 10%,transparent)}
  .trip-blurb{color:var(--dim);font-size:14px;line-height:1.6;margin-bottom:20px;flex-grow:1}
  .trip-list{list-style:none;margin-bottom:24px;display:flex;flex-direction:column;gap:9px}
  .trip-list li{font-size:13px;color:var(--ice);display:flex;gap:10px;align-items:flex-start}
  .trip-list li::before{
    content:"";flex-shrink:0;width:14px;height:14px;margin-top:2px;border-radius:50%;
    background:color-mix(in srgb,var(--pc) 16%,transparent);border:1px solid var(--pc);
    background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8' viewBox='0 0 24 24' fill='none' stroke='%23ffffff' stroke-width='4' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='M20 6 9 17l-5-5'/%3E%3C/svg%3E");
    background-repeat:no-repeat;background-position:center;background-size:8px;
  }
  .trip-foot{display:flex;justify-content:space-between;align-items:center;padding-top:20px;border-top:1px solid var(--line)}
  .trip-price{font-family:'Syne',sans-serif;font-weight:700;font-size:26px;letter-spacing:-.03em;line-height:1}
  .trip-price small{display:block;font-family:'Space Grotesk',sans-serif;font-size:10.5px;color:var(--dim);font-weight:400;margin-top:6px;letter-spacing:.1em;text-transform:uppercase}
  .trip-reviews{font-size:11.5px;color:var(--dim);margin-top:6px}
  .trip-view-btn{
    display:inline-flex;align-items:center;gap:6px;flex-shrink:0;
    padding:10px 16px;border-radius:100px;
    background:var(--glass-2);border:1px solid var(--line);
    color:var(--ice);font-family:'Space Grotesk',sans-serif;font-size:12.5px;font-weight:500;
    cursor:pointer;transition:.25s;
  }
  .trip:hover .trip-view-btn{background:var(--pc);color:var(--on-pc);border-color:transparent}
  .trip.flash{border-color:var(--pc);box-shadow:0 0 0 4px color-mix(in srgb,var(--pc) 30%,transparent),0 30px 70px -30px color-mix(in srgb,var(--pc) 45%,transparent);animation:flashPulse .6s ease 2}
  @keyframes flashPulse{50%{transform:translateY(-6px)}}

  /* trip detail page — full-bleed hero */
  .trip-hero{
    position:relative;min-height:560px;display:flex;align-items:flex-end;
    padding:140px 5vw 64px;overflow:hidden;
  }
  .trip-hero-bg{position:absolute;inset:0;background-size:cover;background-position:center}
  .trip-hero-overlay{
    position:absolute;inset:0;
    background:var(--trip-wash);
  }
  .trip-hero-inner{position:relative;z-index:3;max-width:1400px;margin:0 auto;width:100%}
  .breadcrumb{
    display:flex;align-items:center;gap:9px;flex-wrap:wrap;margin-bottom:22px;
    font-family:'JetBrains Mono',monospace;font-size:11px;letter-spacing:.1em;text-transform:uppercase;
  }
  .breadcrumb a{color:var(--dim);text-decoration:none;transition:.2s}
  .breadcrumb a:hover{color:var(--glow-soft)}
  .breadcrumb .sep{color:var(--dim);opacity:.45}
  .breadcrumb .current{color:var(--ice)}
  .trip-hero h1{
    font-family:'Syne',sans-serif;font-weight:700;
    font-size:clamp(32px,4.8vw,64px);line-height:1.02;letter-spacing:-.03em;
    margin-bottom:20px;max-width:20ch;
  }
  .trip-hero-badges{display:flex;align-items:center;gap:10px;flex-wrap:wrap;margin-bottom:28px}
  .hero-badge{
    display:inline-flex;align-items:center;gap:7px;padding:8px 14px;border-radius:100px;
    background:var(--glass-3);border:1px solid var(--glass-5);
    backdrop-filter:blur(10px);-webkit-backdrop-filter:blur(10px);
    font-size:12.5px;color:var(--ice);
  }
  .hero-badge svg{flex-shrink:0;opacity:.85}
  .hero-badge.star svg{color:var(--sun);opacity:1}
  .trip-hero-foot{display:flex;align-items:center;gap:28px;flex-wrap:wrap}
  .trip-hero-price{font-family:'Syne',sans-serif;font-weight:700;font-size:34px;letter-spacing:-.03em;line-height:1}
  .trip-hero-price small{
    display:block;font-family:'Space Grotesk',sans-serif;font-size:10.5px;color:var(--dim);
    font-weight:400;margin-top:7px;letter-spacing:.1em;text-transform:uppercase;
  }

  /* trip detail page — article body */
  .article{max-width:1180px;margin:0 auto}
  .art-section{margin-bottom:56px}
  .art-section > h2{
    font-family:'Syne',sans-serif;font-weight:700;font-size:clamp(22px,2.4vw,30px);
    letter-spacing:-.02em;margin-bottom:18px;display:flex;align-items:center;gap:11px;
  }
  .art-section > h2 svg{color:var(--pc);flex-shrink:0}
  .art-section p{color:var(--dim);font-size:15.5px;line-height:1.8}
  .hl-list{display:grid;grid-template-columns:1fr 1fr;gap:12px}
  .hl-item{
    display:flex;align-items:flex-start;gap:13px;padding:16px 18px;border-radius:14px;
    background:var(--glass);border:1px solid var(--line);font-size:14px;color:var(--ice);line-height:1.5;
  }
  .hl-icon{
    flex-shrink:0;width:26px;height:26px;border-radius:50%;margin-top:-1px;
    background:color-mix(in srgb,var(--pc) 14%,transparent);
    border:1px solid color-mix(in srgb,var(--pc) 35%,transparent);
    display:flex;align-items:center;justify-content:center;color:var(--pc);
  }
  .incl-grid{display:grid;grid-template-columns:1fr 1fr;gap:20px}
  .incl-col{padding:26px;border-radius:18px;background:var(--glass);border:1px solid var(--line)}
  .incl-col h3{
    font-family:'Syne',sans-serif;font-weight:600;font-size:16px;margin-bottom:16px;
    display:flex;align-items:center;gap:9px;
  }
  .incl-col ul{list-style:none;display:flex;flex-direction:column;gap:11px}
  .incl-col li{font-size:13.8px;line-height:1.55;display:flex;gap:11px;align-items:flex-start}
  .incl-col li::before{
    content:"";flex-shrink:0;width:15px;height:15px;margin-top:2px;border-radius:50%;
    background-repeat:no-repeat;background-position:center;background-size:9px;
  }
  .incl-yes h3{color:#5dd39e}
  .incl-yes li{color:var(--ice)}
  .incl-yes li::before{
    background-color:rgba(93,211,158,.14);border:1px solid rgba(93,211,158,.55);
    background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='9' height='9' viewBox='0 0 24 24' fill='none' stroke='%235dd39e' stroke-width='4' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='M20 6 9 17l-5-5'/%3E%3C/svg%3E");
  }
  .incl-no h3{color:#ff7f7f}
  .incl-no li{color:var(--dim)}
  .incl-no li::before{
    background-color:rgba(255,127,127,.1);border:1px solid rgba(255,127,127,.4);
    background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='9' height='9' viewBox='0 0 24 24' fill='none' stroke='%23ff7f7f' stroke-width='4' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='M18 6 6 18M6 6l12 12'/%3E%3C/svg%3E");
  }
  /* itinerary accordion */
  .itin-list{display:flex;flex-direction:column;gap:12px}
  .itin-item{
    border:1px solid var(--line);border-radius:16px;background:var(--glass);
    overflow:hidden;transition:border-color .25s,background .25s;
  }
  .itin-item.open{
    border-color:color-mix(in srgb,var(--pc) 32%,transparent);
    background:var(--glass-2);
  }
  .itin-head{
    width:100%;display:flex;align-items:center;gap:16px;padding:17px 20px;
    background:none;border:none;cursor:pointer;text-align:left;color:var(--ice);
    font-family:'Space Grotesk',sans-serif;
  }
  .itin-num{
    flex-shrink:0;width:38px;height:38px;border-radius:50%;
    background:color-mix(in srgb,var(--pc) 16%,transparent);
    border:1px solid color-mix(in srgb,var(--pc) 38%,transparent);
    display:flex;align-items:center;justify-content:center;
    font-family:'Syne',sans-serif;font-weight:700;font-size:14px;color:var(--pc);
  }
  .itin-title{flex:1;font-family:'Syne',sans-serif;font-weight:600;font-size:16px;letter-spacing:-.01em;line-height:1.35}
  .itin-chev{flex-shrink:0;color:var(--dim);transition:transform .3s,color .3s}
  .itin-item.open .itin-chev{transform:rotate(180deg);color:var(--pc)}
  /* 0fr→1fr animates to the content's real height, whatever its length */
  .itin-body{display:grid;grid-template-rows:0fr;transition:grid-template-rows .32s ease}
  .itin-item.open .itin-body{grid-template-rows:1fr}
  .itin-body-inner{overflow:hidden}
  .itin-content{padding:17px 20px 20px 74px;border-top:1px solid var(--line)}
  .itin-content p{color:var(--dim);font-size:14.5px;line-height:1.75;margin-bottom:15px}
  .itin-meta{display:flex;gap:26px;flex-wrap:wrap}
  .itin-meta span{font-size:13px;color:var(--ice)}
  .itin-meta .k{
    font-family:'JetBrains Mono',monospace;font-size:10px;letter-spacing:.15em;
    text-transform:uppercase;color:var(--dim);margin-right:8px;
  }
  .itin-meta .elev{color:var(--pc)}
  .itin-note{
    display:flex;align-items:flex-start;gap:10px;margin-top:14px;padding:13px 16px;border-radius:12px;
    font-size:13.5px;line-height:1.6;color:var(--ice);
    background:color-mix(in srgb,var(--pc) 8%,transparent);border:1px solid color-mix(in srgb,var(--pc) 24%,transparent);
  }
  .itin-note svg{flex-shrink:0;margin-top:2px;color:var(--pc)}

  /* opening pull-quote */
  .trip-quote{
    position:relative;padding:26px 30px;border-radius:18px;background:var(--glass);
    border:1px solid var(--line);border-left:3px solid var(--pc);
  }
  .trip-quote svg{position:absolute;top:20px;left:26px;color:var(--pc)}
  .trip-quote p{
    color:var(--ice);font-size:15.5px;line-height:1.8;font-style:italic;
    white-space:pre-line;margin-bottom:12px;
  }
  .trip-quote cite{color:var(--dim);font-size:13px;font-style:normal}

  /* recommended-for chips */
  .chip-row{display:flex;flex-wrap:wrap;gap:10px}
  .chip{
    padding:9px 16px;border-radius:999px;font-size:13.5px;color:var(--ice);
    background:var(--glass);border:1px solid var(--line);
  }

  /* duration comparison */
  .compare-list{display:flex;flex-direction:column;gap:14px;margin-top:20px}
  .compare-item{
    display:flex;gap:16px;padding:18px 20px;border-radius:16px;
    background:var(--glass);border:1px solid var(--line);
  }
  .compare-medal{
    flex-shrink:0;width:34px;height:34px;border-radius:50%;
    display:flex;align-items:center;justify-content:center;
  }
  .compare-medal--gold{background:rgba(255,196,64,.16);border:1px solid rgba(255,196,64,.5);color:#ffc440}
  .compare-medal--silver{background:rgba(192,197,206,.16);border:1px solid rgba(192,197,206,.5);color:#c0c5ce}
  .compare-medal--bronze{background:rgba(205,127,50,.16);border:1px solid rgba(205,127,50,.5);color:#cd7f32}
  .compare-title{
    font-family:'Syne',sans-serif;font-weight:700;font-size:15.5px;color:var(--ice);
    display:flex;align-items:center;gap:10px;flex-wrap:wrap;margin-bottom:6px;
  }
  .compare-rate{
    font-family:'JetBrains Mono',monospace;font-size:11px;letter-spacing:.03em;color:var(--pc);
    padding:3px 9px;border-radius:999px;background:color-mix(in srgb,var(--pc) 14%,transparent);
    border:1px solid color-mix(in srgb,var(--pc) 34%,transparent);
  }
  .compare-item p{margin:0;font-size:14px}
  .choose-grid{display:grid;grid-template-columns:1fr 1fr;gap:20px;margin-top:22px}
  .choose-col{padding:22px 24px;border-radius:16px;border:1px solid var(--line);background:var(--glass)}
  .choose-col h3{
    font-family:'Syne',sans-serif;font-weight:600;font-size:15px;margin-bottom:14px;
    display:flex;align-items:center;gap:9px;
  }
  .choose-yes h3 svg{color:#5dd39e}
  .choose-no h3 svg{color:#ffb648}
  .choose-col ul{list-style:none;display:flex;flex-direction:column;gap:9px}
  .choose-col li{font-size:13.8px;line-height:1.55;color:var(--ice);padding-left:16px;position:relative}
  .choose-col li::before{content:"–";position:absolute;left:0;color:var(--dim)}
  .choose-yes{border-color:color-mix(in srgb,#5dd39e 30%,var(--line))}
  .choose-no{border-color:color-mix(in srgb,#ffb648 30%,var(--line))}

  /* pricing / tipping tables */
  .price-table-wrap{overflow-x:auto;border-radius:16px;border:1px solid var(--line)}
  .price-table{width:100%;border-collapse:collapse;font-size:13.8px;min-width:420px}
  .price-table th,.price-table td{padding:14px 18px;text-align:left;white-space:nowrap}
  .price-table thead th{
    font-family:'JetBrains Mono',monospace;font-size:10.5px;letter-spacing:.1em;text-transform:uppercase;
    color:var(--dim);background:var(--glass-2);border-bottom:1px solid var(--line);
  }
  .price-table tbody td{color:var(--ice);border-bottom:1px solid var(--line)}
  .price-table tbody tr:last-child td{border-bottom:none}
  .price-table tbody tr:nth-child(even){background:var(--glass)}

  /* East African (TZS) price cards */
  .tzs-grid{display:grid;grid-template-columns:repeat(2,1fr);gap:20px}
  .tzs-card{border-radius:18px;border:1px solid var(--line);background:var(--glass);overflow:hidden}
  .tzs-card-head{display:flex;justify-content:space-between;align-items:center;gap:12px;padding:18px 20px}
  .tzs-route{font-family:'Syne',sans-serif;font-weight:700;font-size:17px;color:var(--ice)}
  .tzs-days{font-size:13px;color:var(--dim);margin-top:2px}
  .tzs-link{display:inline-flex;align-items:center;gap:6px;font-size:13px;color:var(--pc);text-decoration:none;white-space:nowrap}
  .tzs-link:hover{text-decoration:underline}
  .tzs-table{min-width:0}
  .tzs-table td:last-child,.tzs-table th:last-child{text-align:right}
  .tzs-table thead th{border-top:1px solid var(--line)}
  @media(max-width:800px){ .tzs-grid{grid-template-columns:1fr} }

  /* optional extras */
  .extras-list{list-style:none;display:flex;flex-direction:column;gap:11px}
  .extras-list li{
    font-size:14px;line-height:1.6;color:var(--ice);padding:14px 18px;border-radius:14px;
    background:var(--glass);border:1px solid var(--line);
  }

  /* closing note */
  .closing-note{
    padding:30px 32px;border-radius:18px;background:var(--glass);border:1px solid var(--line);
  }
  .closing-note p{color:var(--ice);font-size:15px;line-height:1.85;margin-bottom:16px}
  .closing-sign{color:var(--dim)!important;font-size:14px!important;margin-top:8px}
  .closing-sign em{font-style:italic}

  .pricing-note{
    padding:28px;border-radius:18px;
    background:linear-gradient(135deg,rgba(255,122,47,.1),rgba(255,122,47,.02));
    border:1px solid rgba(255,122,47,.22);
  }
  .pricing-note p{margin-bottom:20px}

  /* homepage "Why Perfect Kilimanjaro" — icon cards in a 2-col grid */
  .why-grid{display:grid;grid-template-columns:repeat(2,1fr);gap:20px;margin-top:40px}
  .why-card{background:var(--panel);border:1px solid var(--line);border-radius:20px;padding:28px}
  .why-card-icon{width:52px;height:52px;border-radius:50%;display:flex;align-items:center;justify-content:center;margin-bottom:18px}
  .why-card-image{width:52px;height:52px;border-radius:50%;object-fit:cover;margin-bottom:18px}
  .why-card h3{font-family:'Syne',sans-serif;font-weight:700;font-size:17px;margin-bottom:8px}
  .why-card p{color:var(--dim);font-size:14px;line-height:1.65}

  @media(max-width:700px){
    .why-grid{grid-template-columns:1fr}
  }
  .trip-cta-band{
    padding:48px;border-radius:26px;text-align:center;
    background:radial-gradient(ellipse at 50% 0%,rgba(255,122,47,.22),transparent 60%),var(--band);
    border:1px solid rgba(255,122,47,.2);
  }
  .trip-cta-band h3{font-family:'Syne',sans-serif;font-weight:700;font-size:clamp(24px,2.8vw,34px);letter-spacing:-.02em;margin-bottom:14px}
  .trip-cta-band p{color:var(--dim);font-size:15px;line-height:1.7;max-width:52ch;margin:0 auto 28px}
  .trip-cta-band .btn-row{display:flex;gap:14px;justify-content:center;flex-wrap:wrap}

  /* two-column layout with sticky booking card */
  .trip-layout{display:grid;grid-template-columns:minmax(0,1fr) 370px;gap:56px;align-items:start}
  .trip-main{min-width:0}
  .trip-aside{position:sticky;top:104px}
  .book-card{
    padding:30px;border-radius:22px;
    background:var(--panel-hi);
    border:1px solid var(--line);backdrop-filter:blur(10px);
    box-shadow:0 30px 70px -40px var(--shadow-deep);
  }
  .book-from{
    font-family:'JetBrains Mono',monospace;font-size:10px;letter-spacing:.18em;
    text-transform:uppercase;color:var(--dim);margin-bottom:10px;
  }
  .book-price{font-family:'Syne',sans-serif;font-weight:700;font-size:42px;letter-spacing:-.03em;line-height:1}
  .book-note{
    color:var(--dim);font-size:12.5px;line-height:1.6;
    margin-top:12px;padding-bottom:20px;border-bottom:1px solid var(--line);
  }
  .spec-row{
    display:flex;justify-content:space-between;align-items:baseline;gap:16px;
    padding:13px 0;border-bottom:1px solid var(--line);font-size:13.5px;
  }
  .spec-row:last-of-type{border-bottom:none;padding-bottom:4px}
  .spec-row .k{color:var(--dim);white-space:nowrap}
  .spec-row .v{color:var(--ice);font-weight:500;text-align:right}
  .price-tiers{margin-top:14px;padding-top:14px;border-top:1px solid var(--line)}
  .price-tiers-title{font-size:11.5px;text-transform:uppercase;letter-spacing:.04em;color:var(--dim);margin-bottom:2px}
  .price-tiers .spec-row{padding:9px 0;font-size:13px}
  .book-actions{display:flex;flex-direction:column;gap:11px;margin-top:22px}
  .book-actions .btn{width:100%;justify-content:center;padding:15px 20px}
  .book-fine{font-size:11.5px;color:var(--dim);text-align:center;margin-top:16px;line-height:1.55}

  /* itinerary day meta */
  /* where you'll stay */
  .stay-list{display:flex;flex-direction:column;gap:16px}
  .stay-count{
    font-family:'JetBrains Mono',monospace;font-size:10px;letter-spacing:.12em;
    text-transform:uppercase;color:var(--dim);padding:5px 10px;border-radius:100px;
    background:var(--glass);border:1px solid var(--line);margin-left:2px;
  }
  .stay-card{
    display:grid;grid-template-columns:320px 1fr;
    border:1px solid var(--line);border-radius:20px;overflow:hidden;background:var(--glass);
  }
  .stay-photo{position:relative;min-height:250px}
  .stay-photo img{width:100%;height:100%;object-fit:cover;display:block}
  .stay-photo .trip-tag-overlay{top:14px;left:14px}
  .stay-body{padding:26px 28px}
  .stay-name{font-family:'Syne',sans-serif;font-weight:700;font-size:21px;letter-spacing:-.02em;margin-bottom:7px}
  .stay-stars{color:var(--sun);font-size:13px;letter-spacing:3px;margin-bottom:14px}
  .stay-stars .off{color:var(--line)}
  .stay-note{color:var(--dim);font-size:14px;line-height:1.65;margin:14px 0 16px}
  .amen{display:flex;gap:8px;flex-wrap:wrap}
  .amen span{
    display:inline-flex;align-items:center;gap:6px;padding:6px 11px;border-radius:8px;
    background:var(--glass-2);border:1px solid var(--line);
    font-size:11.5px;color:var(--dim);
  }
  .amen span svg{color:var(--pc);flex-shrink:0}

  /* gallery */
  .gallery-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:14px}
  .gal-item{
    position:relative;border-radius:16px;overflow:hidden;aspect-ratio:4/3;
    cursor:pointer;border:1px solid var(--line);background:var(--ink);
  }
  .gal-item img{width:100%;height:100%;object-fit:cover;display:block;transition:.55s cubic-bezier(.2,.8,.3,1)}
  .gal-item:hover img{transform:scale(1.07)}
  .gal-zoom{
    position:absolute;right:12px;bottom:12px;width:34px;height:34px;border-radius:50%;
    background:var(--scrim-soft);border:1px solid var(--line-hi);backdrop-filter:blur(8px);
    display:flex;align-items:center;justify-content:center;color:#fff;opacity:0;transition:.3s;
  }
  .gal-item:hover .gal-zoom{opacity:1}

  .lightbox{
    position:fixed;inset:0;z-index:300;background:rgba(3,4,10,.95);
    backdrop-filter:blur(10px);-webkit-backdrop-filter:blur(10px);
    opacity:0;pointer-events:none;transition:.3s;
    display:flex;align-items:center;justify-content:center;padding:6vw;
  }
  .lightbox.open{opacity:1;pointer-events:auto}
  .lightbox img{max-width:100%;max-height:84vh;border-radius:14px;display:block}
  .lb-btn{
    position:absolute;width:46px;height:46px;border-radius:50%;cursor:pointer;
    background:var(--glass-3);border:1px solid var(--line-hi);
    color:#fff;display:flex;align-items:center;justify-content:center;transition:.25s;
  }
  .lb-btn:hover{background:var(--glass-5)}
  .lb-close{top:26px;right:26px}
  .lb-prev{left:3vw;top:50%;transform:translateY(-50%)}
  .lb-next{right:3vw;top:50%;transform:translateY(-50%)}
  .lb-count{
    position:absolute;bottom:26px;left:50%;transform:translateX(-50%);
    font-family:'JetBrains Mono',monospace;font-size:12px;color:var(--dim);letter-spacing:.1em;
  }


  /* about + faq */
  .about-grid{display:grid;grid-template-columns:repeat(2,1fr);gap:20px;margin:56px 0}
  .about-block{padding:28px;border-radius:20px;background:var(--glass);border:1px solid var(--line)}
  .about-block h3{font-family:'Syne',sans-serif;font-weight:600;font-size:18px;margin-bottom:10px}
  .about-block p{color:var(--dim);font-size:14.5px;line-height:1.7}
  .faq-list{display:flex;flex-direction:column;gap:10px;max-width:800px}
  .faq-item{border:1px solid var(--line);border-radius:16px;background:var(--glass);overflow:hidden}
  .faq-q{
    width:100%;text-align:left;background:none;border:none;color:var(--ice);
    font-family:'Space Grotesk',sans-serif;font-size:15px;font-weight:500;
    padding:18px 20px;display:flex;justify-content:space-between;align-items:center;
    gap:16px;cursor:pointer;
  }
  .faq-q svg{transition:.3s;flex-shrink:0;color:var(--dim)}
  .faq-item.open .faq-q svg{transform:rotate(180deg)}
  .faq-a{max-height:0;overflow:hidden;transition:.3s ease;padding:0 20px}
  .faq-item.open .faq-a{max-height:200px;padding-bottom:18px}
  .faq-a p{color:var(--dim);font-size:14px;line-height:1.65}

  /* trust bar */
  .trust-bar{display:grid;grid-template-columns:repeat(4,1fr);gap:1px;background:var(--line);border-radius:20px;overflow:hidden;border:1px solid var(--line)}
  .trust-item{background:var(--deep);padding:28px 24px;display:flex;flex-direction:column;gap:10px}
  .trust-item svg{color:var(--glow-soft)}
  .trust-item strong{font-family:'Syne',sans-serif;font-size:15px;font-weight:600}
  .trust-item span{color:var(--dim);font-size:13px;line-height:1.5}

  /* testimonials */
  .reviews-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:20px;margin-top:56px}
  .review{
    padding:28px;border-radius:22px;background:var(--glass);border:1px solid var(--line);backdrop-filter:blur(10px);
  }
  .review-stars{color:var(--sun);font-size:14px;letter-spacing:2px;margin-bottom:16px}
  .review p{color:var(--ice);font-size:14.5px;line-height:1.7;margin-bottom:20px;opacity:.9}
  .review-who{display:flex;align-items:center;gap:12px}
  .review-avatar{
    width:38px;height:38px;border-radius:50%;background:linear-gradient(135deg,var(--sun),var(--glow));
    display:flex;align-items:center;justify-content:center;font-family:'Syne',sans-serif;font-weight:700;color:#1a0a00;font-size:14px;flex-shrink:0;
  }
  .review-who strong{display:block;font-size:13.5px}
  .review-who span{color:var(--dim);font-size:12px}
  .section-more-link{
    display:inline-flex;align-items:center;gap:6px;margin-top:32px;
    color:var(--glow-soft);text-decoration:none;font-size:14px;font-weight:500;transition:.2s;
  }
  .section-more-link:hover{color:var(--glow);gap:9px}

  /* /reviews page: a flat dark header (no photo — a deliberately calmer,
     content-first page rather than another destination hero), a full-width
     stat band sitting flush under it, then a two-column review grid. */
  .reviews-hero{padding:150px 5vw 44px;background:var(--void)}
  .reviews-hero-inner{
    display:flex;justify-content:space-between;align-items:flex-end;gap:24px;flex-wrap:wrap;
    max-width:1400px;margin:0 auto;
  }
  .reviews-hero .tag{margin-bottom:0}
  .reviews-hero h1{margin:14px 0 10px}
  .reviews-hero .section-lede{margin-bottom:0}

  .review-stat-band{background:var(--panel);border-top:1px solid var(--line);border-bottom:1px solid var(--line)}
  .review-stat-band-inner{
    max-width:1400px;margin:0 auto;padding:36px 5vw;
    display:flex;align-items:center;justify-content:center;gap:48px;flex-wrap:wrap;
  }
  .review-summary-score{text-align:center;padding-right:44px;border-right:1px solid var(--line)}
  .review-summary-score .num{font-family:'Syne',sans-serif;font-weight:800;font-size:52px;line-height:1;color:var(--ice)}
  .review-summary-score .stars{color:var(--sun);font-size:16px;letter-spacing:3px;margin:8px 0 6px}
  .review-summary-score .count{color:var(--dim);font-size:12.5px}
  .review-stat-metrics{display:flex;gap:44px;flex-wrap:wrap}
  .review-stat-metric{text-align:center;min-width:120px}
  .review-stat-metric .label{
    font-family:'JetBrains Mono',monospace;text-transform:uppercase;letter-spacing:.08em;
    font-size:10.5px;color:var(--dim);margin-bottom:10px;
  }
  .review-stat-metric .num{font-family:'Syne',sans-serif;font-weight:800;font-size:32px;color:var(--ice);margin-bottom:10px}
  .review-bar-track{height:6px;border-radius:100px;background:var(--glass-3);overflow:hidden}
  .review-bar-fill{height:100%;border-radius:100px;background:linear-gradient(90deg,var(--sun),var(--glow))}

  .review-list{display:grid;grid-template-columns:repeat(2,1fr);gap:20px;margin-bottom:0}
  .review-card{padding:28px;border-radius:20px;background:var(--glass);border:1px solid var(--line)}
  .review-card-head{display:flex;justify-content:space-between;align-items:flex-start;gap:16px;margin-bottom:16px;flex-wrap:wrap}
  .review-card-who{display:flex;align-items:center;gap:12px}
  .review-card-meta{text-align:right}
  .review-card-meta .stars{color:var(--sun);font-size:13px;letter-spacing:2px}
  .review-card-meta .date{color:var(--dim);font-size:11.5px;font-family:'JetBrains Mono',monospace;margin-top:5px}
  .review-card h3{font-family:'Syne',sans-serif;font-weight:600;font-size:17px;margin-bottom:8px}
  .review-card p{color:var(--dim);font-size:14.5px;line-height:1.7;margin-bottom:14px}
  .review-card-sub{display:flex;gap:16px;flex-wrap:wrap;font-size:12px;color:var(--dim);margin-bottom:6px}
  .review-card-sub b{color:var(--ice)}
  .review-card-trip{
    display:inline-flex;align-items:center;gap:6px;padding:6px 13px;border-radius:100px;
    background:var(--glass-2);border:1px solid var(--line);color:var(--glow-soft);
    font-size:11.5px;text-decoration:none;margin-top:8px;transition:.2s;
  }
  .review-card-trip:hover{border-color:var(--glow);color:var(--glow)}
  .review-card-photo{margin-top:16px;border-radius:14px;max-width:240px;width:100%;display:block}

  /* the write-a-review drawer reuses .overlay/.drawer (the same pattern as
     the booking form and the mobile menu), just under its own id */
  .review-drawer .drawer h3{font-size:26px}
  .star-picker{display:flex;gap:8px}
  .star-picker button{all:unset;cursor:pointer;color:var(--line-hi);transition:.15s;line-height:1}
  .star-picker button svg{width:26px;height:26px}
  .star-picker button.active,.star-picker button:hover{color:var(--sun)}
  .review-form-note{color:var(--dim);font-size:12.5px;margin-top:-8px;margin-bottom:20px}

  /* top activities — alternating image/text rows, flipping side each row */
  .activity-rows{display:flex;flex-direction:column;gap:72px;margin-top:56px}
  .activity-row{display:grid;grid-template-columns:0.85fr 1.15fr;gap:56px;align-items:center}
  /* order flips which element sits in which track without flipping the
     tracks' own widths, so without this the image would land in the wider
     1.15fr track on every even row and end up visibly bigger than on odd
     rows. Flipping grid-template-columns to match keeps the image's share
     (0.85 of the row) — and so its rendered size — the same every row. */
  .activity-row:nth-child(even){grid-template-columns:1.15fr 0.85fr}
  .activity-row:nth-child(even) .activity-media{order:2}
  .activity-row:nth-child(even) .activity-text{order:1}
  .activity-media{position:relative;border-radius:20px;overflow:hidden;aspect-ratio:4/3;background:var(--ink)}
  .activity-media img{width:100%;height:100%;object-fit:cover;display:block;transition:.6s cubic-bezier(.2,.8,.3,1)}
  .activity-row:hover .activity-media img{transform:scale(1.05)}
  .activity-eyebrow{font-family:'JetBrains Mono',monospace;font-size:11px;letter-spacing:.14em;text-transform:uppercase;color:var(--glow-soft);margin-bottom:12px}
  .activity-text h3{font-family:'Syne',sans-serif;font-weight:700;font-size:clamp(22px,2.4vw,30px);letter-spacing:-.02em;margin-bottom:14px}
  .activity-text p{color:var(--dim);font-size:15px;line-height:1.75;max-width:46ch}

  /* homepage "glimpse" video — a click-to-load YouTube facade rather than an
     embedded iframe on first paint, so the page isn't loading YouTube's
     player for visitors who never press play. */
  #video{padding-top:0}
  .video-card{
    position:relative;margin-top:8px;border-radius:24px;overflow:hidden;aspect-ratio:16/9;
    background:#000;box-shadow:0 30px 70px -30px rgba(0,0,0,.7);cursor:pointer;
  }
  .video-thumb{width:100%;height:100%;object-fit:cover;display:block}
  .video-scrim{position:absolute;inset:0;background:linear-gradient(180deg,rgba(3,4,10,.55) 0%,rgba(3,4,10,.1) 32%,rgba(3,4,10,.7) 100%)}
  .video-channel{
    position:absolute;top:18px;left:18px;display:flex;align-items:center;gap:10px;
    background:rgba(5,7,13,.55);backdrop-filter:blur(8px);-webkit-backdrop-filter:blur(8px);
    padding:6px 16px 6px 6px;border-radius:100px;border:1px solid rgba(255,255,255,.14);
  }
  .video-channel-text{display:flex;flex-direction:column;line-height:1.3}
  .video-channel-text strong{font-family:'Syne',sans-serif;font-size:13.5px;color:#fff}
  .video-channel-text span{font-size:10.5px;color:var(--dim)}
  .video-play{
    all:unset;position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);
    width:74px;height:74px;border-radius:50%;cursor:pointer;
    background:linear-gradient(135deg,var(--sun),var(--glow));color:#1a0a00;
    display:flex;align-items:center;justify-content:center;box-shadow:0 10px 40px rgba(255,122,47,.5);
    transition:.25s;
  }
  .video-play svg{margin-left:4px}
  .video-card:hover .video-play{transform:translate(-50%,-50%) scale(1.08)}
  .video-yt-badge{
    position:absolute;bottom:18px;right:18px;display:flex;align-items:center;gap:7px;
    background:rgba(5,7,13,.6);backdrop-filter:blur(8px);color:#fff;
    font-family:'Space Grotesk',sans-serif;font-size:12.5px;font-weight:500;
    padding:8px 14px;border-radius:100px;border:1px solid rgba(255,255,255,.14);
  }
  .video-card-playing{cursor:default}
  .video-card-playing iframe{position:absolute;inset:0;width:100%;height:100%;border:0}

  /* cta band */
  .cta-band{
    margin:0 5vw 120px;padding:70px 56px;border-radius:32px;
    background:radial-gradient(ellipse at 80% 30%,rgba(255,122,47,.25),transparent 55%),var(--band);
    border:1px solid rgba(255,122,47,.2);position:relative;overflow:hidden;
    display:grid;grid-template-columns:1.4fr 1fr;gap:56px;align-items:center;
  }
  .cta-band::before{
    content:"";position:absolute;top:-50%;right:-10%;width:500px;height:500px;border-radius:50%;
    background:radial-gradient(circle,rgba(255,214,107,.15),transparent 60%);filter:blur(40px);
  }
  .cta-band h3{font-family:'Syne',sans-serif;font-weight:700;font-size:clamp(28px,3.2vw,48px);line-height:1.08;letter-spacing:-.03em;margin-bottom:18px;position:relative}
  .cta-band h3 em{font-family:'Instrument Serif',serif;font-style:italic;font-weight:400;color:var(--glow-soft)}
  .cta-band p{color:var(--dim);font-size:15.5px;line-height:1.65;max-width:46ch;position:relative}
  .cta-band-right{position:relative;display:flex;flex-direction:column;gap:14px}
  .cta-stat{display:flex;align-items:center;gap:16px;padding:16px 18px;border-radius:16px;background:var(--glass);border:1px solid var(--line);backdrop-filter:blur(10px)}
  .cta-stat-icon{width:42px;height:42px;border-radius:12px;background:linear-gradient(135deg,rgba(255,214,107,.2),rgba(255,122,47,.2));border:1px solid rgba(255,122,47,.3);display:flex;align-items:center;justify-content:center;flex-shrink:0;color:var(--glow-soft)}
  .cta-stat-txt strong{display:block;font-size:14.5px;font-weight:500;margin-bottom:2px}
  .cta-stat-txt span{font-size:12.5px;color:var(--dim)}

  footer{padding:70px 5vw 36px;border-top:1px solid var(--line);position:relative}
  .footer-grid{max-width:1400px;margin:0 auto;display:grid;grid-template-columns:2fr 1fr 1fr;gap:56px;padding-bottom:52px;border-bottom:1px solid var(--line)}
  .footer-brand .brand{margin-bottom:20px}
  .footer-brand p{color:var(--dim);font-size:14px;line-height:1.7;max-width:38ch}
  .footer-col h4{font-family:'JetBrains Mono',monospace;font-size:11px;color:var(--dim);letter-spacing:.15em;text-transform:uppercase;margin-bottom:18px;font-weight:400}
  .footer-col a,.footer-col p{display:block;color:var(--ice);text-decoration:none;font-size:14px;line-height:2;cursor:pointer;transition:.2s;opacity:.8}
  .footer-col a:hover{color:var(--glow-soft);opacity:1;transform:translateX(4px)}
  .footer-social{display:flex;gap:10px;margin-top:16px}
  .footer-social a{
    width:34px;height:34px;border-radius:50%;background:var(--glass);border:1px solid var(--line);
    display:flex;align-items:center;justify-content:center;transform:none!important;
  }
  .footer-social a:hover{background:rgba(255,122,47,.12);border-color:rgba(255,122,47,.3)}
  .footer-bottom{max-width:1400px;margin:36px auto 0;display:flex;justify-content:space-between;align-items:center;font-family:'JetBrains Mono',monospace;font-size:11px;color:var(--dim);letter-spacing:.08em;flex-wrap:wrap;gap:14px}

  /* whatsapp float */
  .wa-float{
    position:fixed;bottom:26px;right:26px;z-index:70;width:56px;height:56px;border-radius:50%;
    background:linear-gradient(135deg,#3fd67a,#1fa855);display:flex;align-items:center;justify-content:center;
    color:#052e13;box-shadow:0 10px 30px rgba(31,168,85,.45);cursor:pointer;transition:.3s;text-decoration:none;
  }
  .wa-float::after{content:"";position:absolute;inset:-6px;border-radius:50%;border:1px solid rgba(63,214,122,.4);animation:pulse 2.4s ease-in-out infinite}
  .wa-float:hover{transform:scale(1.08)}

  /* language switcher float — stacked directly above .wa-float, same corner */
  .lang-float-wrap{position:fixed;bottom:96px;right:26px;z-index:70}
  .lang-float{
    all:unset;cursor:pointer;width:56px;height:56px;border-radius:50%;
    background:linear-gradient(135deg,var(--sun),var(--glow));display:flex;align-items:center;justify-content:center;
    color:#1a0a00;box-shadow:0 10px 30px rgba(255,122,47,.4);transition:.3s;box-sizing:border-box;
  }
  .lang-float:hover{transform:scale(1.08)}
  .lang-panel{
    position:absolute;bottom:calc(100% + 12px);right:0;width:200px;padding:10px;border-radius:16px;
    background:var(--panel-solid);border:1px solid var(--line);box-shadow:0 24px 60px -20px var(--shadow-deep);
    opacity:0;pointer-events:none;transform:translateY(8px);transition:.2s;
  }
  .lang-float-wrap.open .lang-panel{opacity:1;pointer-events:auto;transform:translateY(0)}
  .lang-panel-title{
    font-family:'JetBrains Mono',monospace;font-size:10px;letter-spacing:.14em;text-transform:uppercase;
    color:var(--dim);padding:6px 10px 8px;
  }
  .lang-opt{
    all:unset;cursor:pointer;display:flex;align-items:center;gap:10px;width:100%;box-sizing:border-box;
    padding:10px;border-radius:10px;color:var(--ice);font-family:'Space Grotesk',sans-serif;font-size:14px;transition:.15s;
  }
  .lang-opt:hover{background:var(--glass-3)}
  .lang-opt.active{background:rgba(255,122,47,.12);color:var(--glow-soft)}
  .lang-flag{font-size:17px;line-height:1}

  /* Suppresses Google's own translate banner/toolbar and body offset —
     only our custom floating panel above should be visible as UI. */
  .goog-te-banner-frame,.goog-te-gadget-icon,body>.skiptranslate{display:none!important}
  body{top:0!important}

  /* drawer / modal */
  .overlay{
    position:fixed;inset:0;z-index:200;background:rgba(3,4,10,.85);backdrop-filter:blur(10px);
    opacity:0;pointer-events:none;transition:.4s;display:flex;justify-content:flex-end;
  }
  .overlay.open{opacity:1;pointer-events:auto}
  .drawer{
    width:min(520px,100vw);height:100%;overflow-y:auto;background:var(--drawer);
    border-left:1px solid var(--line);padding:40px 40px;position:relative;
    transform:translateX(100%);transition:.5s cubic-bezier(.2,.8,.3,1);
  }
  .overlay.open .drawer{transform:translateX(0)}
  .drawer-close{
    position:absolute;top:24px;right:24px;width:40px;height:40px;border-radius:50%;
    background:var(--glass-2);border:1px solid var(--line);color:var(--ice);cursor:pointer;
    display:flex;align-items:center;justify-content:center;transition:.2s;
  }
  .drawer-close:hover{background:var(--glass-4);transform:rotate(90deg)}
  .drawer h3{font-family:'Syne',sans-serif;font-weight:700;font-size:30px;letter-spacing:-.02em;margin:16px 0 8px}
  .drawer h3 em{font-family:'Instrument Serif',serif;font-style:italic;font-weight:400;color:var(--glow-soft)}
  .drawer .sub{color:var(--dim);font-size:14px;line-height:1.6;margin-bottom:32px}
  .field{margin-bottom:20px}
  .field label{display:block;font-family:'JetBrains Mono',monospace;font-size:11px;color:var(--dim);letter-spacing:.1em;text-transform:uppercase;margin-bottom:8px}
  .field select,.field input,.field textarea{
    width:100%;background:var(--glass);border:1px solid var(--line);color:var(--ice);
    padding:14px 16px;border-radius:12px;font-size:15px;font-family:'Space Grotesk',sans-serif;transition:.2s;
  }
  .field textarea{resize:vertical;min-height:110px;line-height:1.6}
  .field select:focus,.field input:focus,.field textarea:focus{outline:none;border-color:var(--glow);background:rgba(255,122,47,.05);box-shadow:0 0 0 4px rgba(255,122,47,.1)}
  .field-row{display:flex;gap:14px}
  .field-row .field{flex:1}
  .price-box{padding:20px 22px;border-radius:16px;margin:26px 0;background:linear-gradient(135deg,rgba(255,122,47,.12),rgba(255,122,47,.03));border:1px solid rgba(255,122,47,.25);display:flex;justify-content:space-between;align-items:center}
  .price-box-label{font-size:13px;color:var(--dim)}
  .price-box-amt{font-family:'Syne',sans-serif;font-weight:700;font-size:27px;letter-spacing:-.02em}
  .submit-btn{
    width:100%;padding:17px;border-radius:100px;border:none;background:linear-gradient(135deg,var(--sun),var(--glow));
    color:#1a0a00;font-family:'Space Grotesk',sans-serif;font-size:15px;font-weight:600;cursor:pointer;
    box-shadow:0 10px 30px rgba(255,122,47,.4);transition:.3s;margin-top:8px;
  }
  .submit-btn:hover{transform:translateY(-2px);box-shadow:0 14px 40px rgba(255,122,47,.55)}
  .submit-btn:disabled{opacity:.6;cursor:wait;transform:none}
  .form-error{
    color:#ff9d9d;font-size:13px;line-height:1.5;margin-bottom:12px;
    padding:12px 14px;border-radius:10px;
    background:rgba(255,127,127,.09);border:1px solid rgba(255,127,127,.3);
  }
  .confirm{text-align:center;padding:50px 0;display:none}
  .confirm.show{display:block;animation:fadeUp .5s ease}
  .confirm-icon{
    width:78px;height:78px;border-radius:50%;margin:0 auto 22px;background:radial-gradient(circle,rgba(255,122,47,.2),rgba(255,122,47,.05));
    border:1px solid rgba(255,122,47,.4);display:flex;align-items:center;justify-content:center;box-shadow:0 0 40px rgba(255,122,47,.3);
  }
  .confirm h3{font-size:27px;margin-bottom:10px}
  .confirm .sub{margin-bottom:26px}
  .confirm-ref{
    font-family:'JetBrains Mono',monospace;font-size:21px;color:var(--glow-soft);letter-spacing:.1em;margin-bottom:28px;
    padding:16px;border-radius:12px;background:rgba(255,122,47,.08);border:1px dashed rgba(255,122,47,.4);
  }

  @media(max-width:1024px){
    .pillars-grid{grid-template-columns:repeat(2,1fr)}
    .trips-grid{grid-template-columns:repeat(2,1fr)}
    .trust-bar{grid-template-columns:repeat(2,1fr)}
    .reviews-grid{grid-template-columns:1fr}
    .review-list{grid-template-columns:1fr}
    .review-stat-band-inner{flex-direction:column;gap:28px}
    .review-summary-score{border-right:none;padding-right:0;padding-bottom:28px;border-bottom:1px solid var(--line)}
    .review-stat-metrics{gap:32px}
    .review-card-head{flex-direction:column}
    .review-card-meta{text-align:left}
    .reviews-hero{padding-top:120px}
    .reviews-hero-inner{align-items:flex-start}
    .activity-row{gap:32px}
    .cta-band{grid-template-columns:1fr;padding:56px 36px}
    .footer-grid{grid-template-columns:1fr 1fr;gap:36px}
    .hero-stats{grid-template-columns:repeat(2,1fr)}
    .stat:nth-child(2){border-right:none}
    .stat:nth-child(1),.stat:nth-child(2){border-bottom:1px solid var(--line)}
    .about-grid{grid-template-columns:1fr}
    .incl-grid{grid-template-columns:1fr}
    .choose-grid{grid-template-columns:1fr}
    .stay-card{grid-template-columns:1fr}
    .stay-photo{min-height:220px}
    .trip-layout{grid-template-columns:1fr;gap:40px}
    .trip-aside{position:static;order:-1}
    .hl-list{grid-template-columns:1fr}
  }
  /* bottom nav (mobile) */
  .bottom-nav{
    display:none;grid-template-columns:repeat(6,1fr);
    position:fixed;bottom:0;left:0;right:0;z-index:65;
    background:var(--scrim-solid);backdrop-filter:blur(20px) saturate(180%);
    -webkit-backdrop-filter:blur(20px) saturate(180%);
    border-top:1px solid var(--line);
    padding:8px 2px calc(6px + env(safe-area-inset-bottom));
  }
  .bn-item{
    display:flex;flex-direction:column;align-items:center;gap:4px;
    color:var(--dim);text-decoration:none;cursor:pointer;background:none;border:none;
    font-family:'JetBrains Mono',monospace;font-size:9.5px;letter-spacing:.03em;
    text-transform:uppercase;padding:6px 0;border-radius:12px;transition:.2s;
  }
  .bn-item svg{width:19px;height:19px;transition:.2s}
  .bn-item.active{color:var(--glow)}
  .bn-item:active{background:var(--glass-3)}

  @media(max-width:640px){
    .pillars-grid{grid-template-columns:1fr}
    .trips-grid{grid-template-columns:1fr}
    /* Alternating left/right only makes sense side-by-side — stacked on a
       phone, every row reads text-then-image regardless of its position.
       The even-row column-width override that keeps images the same size
       on desktop (".activity-row:nth-child(even)") is more specific than a
       plain ".activity-row", so it must be named again here to actually be
       overridden on mobile rather than silently winning over this block. */
    .activity-rows{gap:40px}
    .activity-row,
    .activity-row:nth-child(even){grid-template-columns:1fr;gap:18px}
    .activity-row .activity-text{order:1}
    .activity-row .activity-media{order:2}
    .hero{padding-bottom:180px}
    section{padding:90px 5vw}
    .footer-grid{grid-template-columns:1fr}
    .drawer{padding:32px 24px}
    .trips-head{align-items:flex-start}
    .tabs{width:100%;overflow-x:auto}
    body{padding-bottom:66px}
    .bottom-nav{display:grid}
    .wa-float{bottom:80px;right:18px;width:50px;height:50px}
    .lang-float-wrap{bottom:142px;right:18px}
    .lang-float{width:50px;height:50px}
    .trip-hero{min-height:460px;padding:120px 5vw 48px}
    .trip-hero-foot{gap:18px}
    .incl-col,.pricing-note{padding:22px}
    .trip-cta-band{padding:36px 24px}
    .art-section{margin-bottom:44px}
    .gallery-grid{grid-template-columns:repeat(2,1fr);gap:10px}
    .stay-body{padding:22px}
    .book-card{padding:24px}
    .book-price{font-size:36px}
    .itin-head{padding:15px 16px;gap:13px}
    .itin-num{width:34px;height:34px;font-size:13px}
    .itin-title{font-size:15px}
    .itin-content{padding:15px 16px 18px 16px}
    .itin-meta{gap:8px 20px}
    .lb-prev{left:10px}
    .lb-next{right:10px}
  }
</style>
@stack('styles')
</head>
<body>

<nav id="nav">
  <a class="brand" href="{{ route('home') }}">
    <div class="brand-mark">
      <svg width="14" height="14" viewBox="0 0 24 24" fill="#1a0a00"><path d="m8 3 4 8 5-5 5 15H2L8 3z"/></svg>
    </div>
    <div class="brand-name notranslate">Perfect Kilimanjaro<span>.</span></div>
  </a>
  <div class="nav-links" id="navLinks"></div>
  <div class="nav-right">
    <button class="btn btn-fill nav-cta" onclick="openBooking()">
      <span class="nav-cta-long">Plan my trip</span><span class="nav-cta-short">Plan</span>
      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
    </button>
    <button class="hamburger" onclick="openMobileNav()" aria-label="Open menu">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M3 6h18M3 12h18M3 18h18"/></svg>
    </button>
  </div>
</nav>

@yield('content')

<footer id="footer">
  <div class="footer-grid">
    <div class="footer-brand">
      <a class="brand" href="{{ route('home') }}">
        <div class="brand-mark">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="#1a0a00"><path d="m8 3 4 8 5-5 5 15H2L8 3z"/></svg>
        </div>
        <div class="brand-name notranslate">Perfect Kilimanjaro<span>.</span></div>
      </a>
      <p>Locally owned safari, Kilimanjaro and Zanzibar trips, run out of Arusha, Tanzania.</p>
      <div class="footer-social">
        <a href="#" aria-label="Instagram"><svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="2" width="20" height="20" rx="5"/><circle cx="12" cy="12" r="4"/><circle cx="17.5" cy="6.5" r="1"/></svg></a>
        <a href="#" aria-label="Facebook"><svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/></svg></a>
        <a href="#" aria-label="TikTok"><svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 12a4 4 0 1 0 4 4V4a5 5 0 0 0 5 5"/></svg></a>
      </div>
    </div>
    <div class="footer-col">
      <h4>Destinations</h4>
      <a onclick="jumpTrip('safari','northern-circuit')">Serengeti</a>
      <a onclick="jumpTrip('safari','northern-circuit')">Ngorongoro</a>
      <a onclick="goTab('kilimanjaro')">Kilimanjaro</a>
      <a onclick="goTab('zanzibar')">Zanzibar</a>
    </div>
    <div class="footer-col">
      <h4>Experiences</h4>
      <a onclick="goTab('safari')">Wildlife Safaris</a>
      <a onclick="goTab('kilimanjaro')">Mountain Treks</a>
      <a onclick="jumpTrip('zanzibar','zanzibar-beach')">Beach Escapes</a>
      <a onclick="jumpTrip('zanzibar','zanzibar-dhow')">Cultural Tours</a>
    </div>
    <div class="footer-col">
      <h4>Company</h4>
      <a href="{{ route('tours') }}">All Tours</a>
      <a href="{{ route('home') }}#about">About Us</a>
      <a onclick="openBooking()">Plan Your Trip</a>
      <a href="https://wa.me/255752967222" target="_blank" rel="noopener">Contact</a>
      <a href="#">Privacy Policy</a>
    </div>
  </div>
  <div class="footer-bottom">
    <div>© 2026 Perfect Kilimanjaro. All rights reserved. · hello@safiri.co.tz · +255 752 967 222</div>
    <div>Arusha, Tanzania · 3°22'S · 36°41'E</div>
  </div>
</footer>

<a class="wa-float" href="https://wa.me/255752967222" target="_blank" rel="noopener" aria-label="Chat on WhatsApp">
  <svg width="26" height="26" viewBox="0 0 24 24" fill="currentColor"><path d="M12.01 2C6.48 2 2 6.48 2 12c0 1.85.5 3.58 1.36 5.07L2 22l5.06-1.33A9.94 9.94 0 0 0 12.01 22C17.52 22 22 17.52 22 12S17.52 2 12.01 2zm5.5 14.14c-.23.65-1.14 1.19-1.87 1.35-.5.11-1.15.19-3.35-.72-2.82-1.17-4.63-4.03-4.77-4.22-.14-.19-1.15-1.53-1.15-2.92 0-1.39.73-2.07.99-2.35.23-.25.5-.32.67-.32h.48c.15 0 .36-.06.56.43l.79 1.9c.09.19.15.42.03.67-.12.25-.18.4-.36.6l-.45.52c-.14.14-.29.29-.13.57.15.28.68 1.12 1.46 1.81 1 .89 1.82 1.17 2.11 1.3.23.1.51.08.68-.1l.62-.72c.24-.28.5-.24.79-.14l1.77.83c.28.14.48.21.55.33.07.12.07.7-.16 1.35z"/></svg>
</a>

{{-- Language switcher — drives Google's client-side website-translate engine
     (see #lang-translate-script below) so every page, including database
     content, is covered without hand-translating every string. --}}
<div class="lang-float-wrap" id="langFloatWrap">
  <button class="lang-float" id="langFloatBtn" aria-label="Change language" aria-haspopup="true" aria-expanded="false">
    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M2 12h20M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10Z"/></svg>
  </button>
  <div class="lang-panel notranslate" id="langPanel" role="menu">
    <div class="lang-panel-title">Language</div>
    <button class="lang-opt" role="menuitem" data-lang="en">
      <span class="lang-flag">🇬🇧</span><span>English</span>
    </button>
    <button class="lang-opt" role="menuitem" data-lang="sw">
      <span class="lang-flag">🇹🇿</span><span>Kiswahili</span>
    </button>
    <button class="lang-opt" role="menuitem" data-lang="zh-CN">
      <span class="lang-flag">🇨🇳</span><span>中文</span>
    </button>
    <button class="lang-opt" role="menuitem" data-lang="fr">
      <span class="lang-flag">🇫🇷</span><span>Français</span>
    </button>
    <button class="lang-opt" role="menuitem" data-lang="it">
      <span class="lang-flag">🇮🇹</span><span>Italiano</span>
    </button>
    <button class="lang-opt" role="menuitem" data-lang="es">
      <span class="lang-flag">🇪🇸</span><span>Español</span>
    </button>
    <button class="lang-opt" role="menuitem" data-lang="ar">
      <span class="lang-flag">🇸🇦</span><span>العربية</span>
    </button>
  </div>
</div>
<div id="google_translate_element" class="notranslate" style="display:none"></div>

<nav class="bottom-nav" id="bottomNav">
  <button class="bn-item active" data-key="home" onclick="bnGo(this,()=>window.location.href='{{ route('home') }}')">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9.5 12 3l9 6.5V21a1 1 0 0 1-1 1h-5v-7H9v7H4a1 1 0 0 1-1-1Z"/></svg>
    <span>Home</span>
  </button>
  <button class="bn-item" data-key="safari" onclick="bnGo(this,()=>goTab('safari'))">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="10" r="3"/><path d="M12 21s7-7.5 7-12a7 7 0 1 0-14 0c0 4.5 7 12 7 12Z"/></svg>
    <span>Safaris</span>
  </button>
  <button class="bn-item" data-key="southern" onclick="bnGo(this,()=>jumpTrip('safari','southern-circuit'))">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="m14.5 9.5-1.8 4.7-4.7 1.8 1.8-4.7z"/></svg>
    <span>Southern</span>
  </button>
  <button class="bn-item" data-key="kilimanjaro" onclick="bnGo(this,()=>goTab('kilimanjaro'))">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m8 3 4 8 5-5 5 15H2L8 3z"/></svg>
    <span>Climbing</span>
  </button>
  <button class="bn-item" data-key="zanzibar" onclick="bnGo(this,()=>goTab('zanzibar'))">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12c2-2 4-2 6 0s4 2 6 0 4-2 6 0M2 18c2-2 4-2 6 0s4 2 6 0 4-2 6 0"/></svg>
    <span>Zanzibar</span>
  </button>
  <button class="bn-item" data-key="contact" onclick="bnGo(this,()=>window.location.href='{{ route('home') }}#footer')">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
    <span>Contact</span>
  </button>
</nav>

<div class="lightbox" id="lightbox" onclick="if(event.target===this)closeLightbox()">
  <button class="lb-btn lb-close" onclick="closeLightbox()" aria-label="Close">
    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M18 6 6 18M6 6l12 12"/></svg>
  </button>
  <button class="lb-btn lb-prev" onclick="lightboxStep(-1)" aria-label="Previous image">
    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
  </button>
  <button class="lb-btn lb-next" onclick="lightboxStep(1)" aria-label="Next image">
    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg>
  </button>
  <img id="lightboxImg" src="" alt="">
  <div class="lb-count" id="lightboxCount"></div>
</div>

<div class="overlay" id="mobileNavOverlay" onclick="if(event.target===this)closeMobileNav()">
  <div class="drawer" style="width:min(380px,100vw)">
    <button class="drawer-close" onclick="closeMobileNav()">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M18 6 6 18M6 6l12 12"/></svg>
    </button>
    <div class="brand" style="margin-bottom:32px">
      <div class="brand-mark">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="#1a0a00"><path d="m8 3 4 8 5-5 5 15H2L8 3z"/></svg>
      </div>
      <div class="brand-name notranslate">Perfect Kilimanjaro<span>.</span></div>
    </div>
    <div id="mobileNavList"></div>
    <button class="btn btn-fill" style="width:100%;justify-content:center;margin-top:24px" onclick="closeMobileNav();openBooking()">
      Plan my trip
      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
    </button>
  </div>
</div>

<div class="overlay" id="overlay" onclick="if(event.target===this)closeBooking()">
  <div class="drawer">
    <button class="drawer-close" onclick="closeBooking()">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M18 6 6 18M6 6l12 12"/></svg>
    </button>

    <div id="drawerForm">
      <div class="tag" style="margin-bottom:8px">Booking</div>
      <h3>Plan your <em>trip</em></h3>
      <p class="sub">Tell us who's coming and when — we'll confirm availability by email within one business day.</p>

      <div class="field">
        <label>Choose trip</label>
        <select id="tripSelect" onchange="onTripChange()"></select>
      </div>

      <div class="price-box">
        <div><div class="price-box-label" id="priceLabel">per person</div></div>
        <div class="price-box-amt" id="priceAmt">$0</div>
      </div>

      <form id="bookingForm" onsubmit="submitBooking(event)">
        <div class="field"><label>Full name</label><input name="name" required placeholder="Your name"></div>
        <div class="field-row">
          <div class="field"><label>Email</label><input name="email" required type="email" placeholder="you@email.com"></div>
          <div class="field"><label>Phone</label><input name="phone" placeholder="+255 ..."></div>
        </div>
        <div class="field-row">
          <div class="field"><label>Start date</label><input name="start_date" required type="date"></div>
          <div class="field"><label>Group size</label><input name="group_size" required type="number" min="1" value="1"></div>
        </div>
        <p class="form-error" id="bookingError" hidden></p>
        <button class="submit-btn" type="submit" id="bookingSubmit">Request this trip →</button>
      </form>
    </div>

    <div class="confirm" id="confirmView">
      <div class="confirm-icon">
        <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="#ff7a2f" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
      </div>
      <h3>Request received</h3>
      <p class="sub">We'll email your itinerary and payment details within one business day.</p>
      <div class="confirm-ref" id="confirmRef">SAF-XXXXX</div>
      <button class="btn btn-line" onclick="closeBooking()">Close</button>
    </div>
  </div>
</div>

<script>
const HOME_URL = "{{ route('home') }}";

const CATS = {
  safari:      { label: "Safaris",     singular: "safari",      color: "var(--c-safari)",   prefix: "SAF" },
  kilimanjaro: { label: "Kilimanjaro", singular: "Kilimanjaro",  color: "var(--c-kili)",     prefix: "KIL" },
  zanzibar:    { label: "Zanzibar",    singular: "Zanzibar",     color: "var(--c-zanzibar)", prefix: "ZNZ" },
  east_africa: { label: "East Africa", singular: "East Africa",  color: "var(--c-east-africa)", prefix: "EAF" },
  biking:      { label: "Biking",      singular: "biking",      color: "var(--c-biking)",   prefix: "BIK" }
};

const TRIPS = @json(collect($trips ?? [])->map(function ($t) {
    $t['image'] = asset($t['image']);
    return $t;
})->values());

const NAV = @json($navItems ?? []);

/* Menu entries are stored as data — an action type plus a target — never as
   code, so nothing an admin types in the panel can be executed. These turn a
   stored action into a click handler, and anything unrecognised is left inert. */
function navAction(a){
  if (!a || !a.type) return null;
  switch (a.type) {
    case 'category': return () => goTab(a.target);
    case 'package':  return () => a.category
      ? jumpTrip(a.category, a.target)
      : (window.location.href = '/' + a.target);
    case 'about':    return () => goAbout(a.target === 'about' ? null : a.target);
    case 'url':      return () => { window.location.href = a.target; };
    default: return null;
  }
}

const NAV_ACTIONS = [];
function navHandler(a, closeDrawer){
  let fn = navAction(a);
  if (!fn) return '';
  if (closeDrawer) { const go = fn; fn = () => { go(); closeMobileNav(); }; }
  return `onclick="NAV_ACTIONS[${NAV_ACTIONS.push(fn) - 1}]()"`;
}

function esc(s){
  return String(s ?? '').replace(/[&<>"']/g, c =>
    ({ '&':'&amp;', '<':'&lt;', '>':'&gt;', '"':'&quot;', "'":'&#39;' })[c]);
}

document.getElementById('navLinks').innerHTML = NAV.map(g => {
  if (g.href) {
    return `<div class="nav-item"><a class="nav-top" href="${esc(g.href)}">${esc(g.label)}</a></div>`;
  }
  const cta = navHandler(g.ctaAction);
  return `
  <div class="nav-item">
    <a class="nav-top">${esc(g.label)}
      <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
    </a>
    <div class="dropdown-panel${g.image ? '' : ' no-photo'}">
      <div class="dropdown-links">
        ${(g.links || []).map(l => `<a ${navHandler(l.action)}>${esc(l.text)}</a>`).join('')}
      </div>
      <div class="dropdown-content">
        <h4>${esc(g.title)}</h4>
        ${g.desc ? `<p>${esc(g.desc)}</p>` : ''}
        ${g.cta ? `<a class="dropdown-cta" ${cta}>${esc(g.cta)}
          <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
        </a>` : ''}
      </div>
      ${g.image ? `<div class="dropdown-photo" ${cta}><img src="${esc(g.image)}" alt="${esc(g.title)}" loading="lazy"></div>` : ''}
    </div>
  </div>
`;
}).join('');

function renderMobileNav(){
  document.getElementById('mobileNavList').innerHTML = NAV.map((g,i) => {
    if (g.href) {
      return `<div class="mnav-group"><a class="mnav-link" href="${esc(g.href)}">${esc(g.label)}</a></div>`;
    }
    return `
    <div class="mnav-group">
      <button class="mnav-head" data-idx="${i}" onclick="toggleMNav(${i})">
        ${esc(g.label)}
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
      </button>
      <div class="mnav-body" id="mnavBody${i}">
        ${(g.links || []).map(l => `<a ${navHandler(l.action, true)}>${esc(l.text)}</a>`).join('')}
      </div>
    </div>
  `;
  }).join('');
}
renderMobileNav();

function toggleMNav(i){
  document.getElementById('mnavBody'+i).classList.toggle('open');
  document.querySelector('.mnav-head[data-idx="'+i+'"]').classList.toggle('open');
}

const mobileNavOverlay = document.getElementById('mobileNavOverlay');
function openMobileNav(){ mobileNavOverlay.classList.add('open'); document.body.style.overflow = 'hidden'; }
function closeMobileNav(){ mobileNavOverlay.classList.remove('open'); document.body.style.overflow = ''; }

let activeCat = "kilimanjaro";

function setTab(cat){
  activeCat = cat;
  if (typeof renderTrips === 'function') renderTrips();
  bnSetActive(cat);
}
function goTab(cat){
  if (typeof renderTrips === 'function') { setTab(cat); scrollToId('trips'); }
  else { window.location.href = HOME_URL + '?tab=' + cat + '#trips'; }
}
function jumpTrip(category, tripId){
  if (typeof renderTrips === 'function') {
    setTab(category);
    scrollToId('trips');
    if(!tripId) return;
    setTimeout(() => {
      const card = document.querySelector(`.trip[data-id="${tripId}"]`);
      if(!card) return;
      card.scrollIntoView({behavior:'smooth', block:'center'});
      card.classList.add('flash');
      setTimeout(() => card.classList.remove('flash'), 1500);
    }, 350);
  } else {
    window.location.href = HOME_URL + '?tab=' + category + (tripId ? '&trip=' + tripId : '') + '#trips';
  }
}
function goAbout(anchor){
  if (document.getElementById('about')) { scrollToId(anchor || 'about'); }
  else { window.location.href = HOME_URL + '#' + (anchor || 'about'); }
}

function toggleFaq(btn){ btn.parentElement.classList.toggle('open'); }
function toggleItin(btn){ btn.parentElement.classList.toggle('open'); }

/* Click-to-load YouTube facade shared by the homepage's "glimpse" video and
   each package page's video — only one #videoCard exists per page. */
function playPageVideo(){
  const card = document.getElementById('videoCard');
  const id = card.dataset.yt;
  card.outerHTML = `<div class="video-card video-card-playing"><iframe src="https://www.youtube.com/embed/${id}?autoplay=1&rel=0" title="Video" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe></div>`;
}

/* ---- gallery lightbox ---- */
let lbImages = [];
let lbIndex = 0;
const lightboxEl = document.getElementById('lightbox');

function openLightbox(images, index){
  lbImages = images;
  lbIndex = index;
  renderLightbox();
  lightboxEl.classList.add('open');
  document.body.style.overflow = 'hidden';
}
function closeLightbox(){
  lightboxEl.classList.remove('open');
  document.body.style.overflow = '';
}
function lightboxStep(delta){
  if(!lbImages.length) return;
  lbIndex = (lbIndex + delta + lbImages.length) % lbImages.length;
  renderLightbox();
}
function renderLightbox(){
  document.getElementById('lightboxImg').src = lbImages[lbIndex];
  document.getElementById('lightboxCount').textContent = `${lbIndex + 1} / ${lbImages.length}`;
}
document.addEventListener('keydown', e => {
  if(!lightboxEl.classList.contains('open')) return;
  if(e.key === 'Escape') closeLightbox();
  if(e.key === 'ArrowLeft') lightboxStep(-1);
  if(e.key === 'ArrowRight') lightboxStep(1);
});

function bnSetActive(key){
  document.querySelectorAll('.bn-item').forEach(el => el.classList.toggle('active', el.dataset.key === key));
}
function bnGo(el, action){ action(); bnSetActive(el.dataset.key); }

const bnTop = document.getElementById('top');
const bnFooter = document.getElementById('footer');
if (bnTop) { new IntersectionObserver(es => es.forEach(e => { if (e.isIntersecting) bnSetActive('home'); }), { threshold:.5 }).observe(bnTop); }
if (bnFooter) { new IntersectionObserver(es => es.forEach(e => { if (e.isIntersecting) bnSetActive('contact'); }), { threshold:.15 }).observe(bnFooter); }

function scrollToId(id){ document.getElementById(id)?.scrollIntoView({behavior:'smooth'}); }

function trackMouse(e, el){
  const r = el.getBoundingClientRect();
  el.style.setProperty('--mx', ((e.clientX-r.left)/r.width*100)+'%');
  el.style.setProperty('--my', ((e.clientY-r.top)/r.height*100)+'%');
}

const nav = document.getElementById('nav');
window.addEventListener('scroll', () => nav.classList.toggle('solid', window.scrollY > 60), {passive:true});

const revealTargets = document.querySelectorAll('section .wrap, .cta-band');
revealTargets.forEach(el => el.classList.add('reveal'));
// threshold must stay 0: sections taller than the viewport can never satisfy a
// percentage threshold, which would leave them permanently hidden.
const io = new IntersectionObserver(entries => {
  entries.forEach(e => { if(e.isIntersecting){ e.target.classList.add('in'); io.unobserve(e.target); } });
}, { threshold: 0, rootMargin: '0px 0px -60px 0px' });
revealTargets.forEach(el => io.observe(el));

document.getElementById('tripSelect').innerHTML = Object.entries(CATS).filter(([key]) => TRIPS.some(t => t.category === key)).map(([key,c]) => `
  <optgroup label="${c.label}">
    ${TRIPS.filter(t=>t.category===key).map(t=>`<option value="${t.id}">${t.name} · ${t.days}d · $${t.price.toLocaleString()}</option>`).join('')}
  </optgroup>
`).join('');

const overlay = document.getElementById('overlay');
const tripSelect = document.getElementById('tripSelect');
const drawerForm = document.getElementById('drawerForm');
const confirmView = document.getElementById('confirmView');
const confirmRef = document.getElementById('confirmRef');
const priceLabel = document.getElementById('priceLabel');
const priceAmt = document.getElementById('priceAmt');

function openBooking(tripId){
  tripSelect.value = tripId || TRIPS.find(t=>t.category===activeCat)?.id || TRIPS[0].id;
  onTripChange();
  drawerForm.style.display = 'block';
  confirmView.classList.remove('show');
  overlay.classList.add('open');
  document.body.style.overflow = 'hidden';
}
function closeBooking(){
  overlay.classList.remove('open');
  document.body.style.overflow = '';
}
function onTripChange(){
  const t = TRIPS.find(x => x.id === tripSelect.value);
  if(!t) return;
  priceLabel.textContent = `${t.days}-day ${CATS[t.category].singular} trip · per person`;
  priceAmt.textContent = '$' + t.price.toLocaleString();
}
async function submitBooking(e){
  e.preventDefault();
  const form = document.getElementById('bookingForm');
  const btn = document.getElementById('bookingSubmit');
  const err = document.getElementById('bookingError');
  const data = Object.fromEntries(new FormData(form).entries());
  data.trip = tripSelect.value;

  err.hidden = true;
  btn.disabled = true;
  btn.textContent = 'Sending…';

  try {
    const res = await fetch('{{ route('inquiries.store') }}', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
      },
      body: JSON.stringify(data),
    });
    if(!res.ok) throw new Error('Request failed');
    const json = await res.json();
    confirmRef.textContent = json.reference;
    drawerForm.style.display = 'none';
    confirmView.classList.add('show');
    form.reset();
  } catch (_) {
    err.textContent = "Couldn't send that just now — please try again, or message us on WhatsApp.";
    err.hidden = false;
  } finally {
    btn.disabled = false;
    btn.textContent = 'Request this trip →';
  }
}

/* ---- Language switcher ----
   Drives Google's client-side website-translate engine (loaded below via
   googleTranslateElementInit) rather than hand-translated strings, so every
   page — including package, review and admin-edited homepage content pulled
   from the database — is covered without maintaining per-string translations. */
const LANG_NAMES = { en:'English', sw:'Kiswahili', 'zh-CN':'中文', fr:'Français', it:'Italiano', es:'Español', ar:'العربية' };

function currentGoogTransLang(){
  const m = document.cookie.match(/googtrans=\/en\/([a-zA-Z-]+)/);
  return m ? m[1] : 'en';
}

function markActiveLangOpt(){
  const active = currentGoogTransLang();
  document.querySelectorAll('.lang-opt').forEach(btn => {
    btn.classList.toggle('active', btn.dataset.lang === active);
  });
}

function setSiteLanguage(lang){
  if (lang === 'en') {
    document.cookie = 'googtrans=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;';
    document.cookie = 'googtrans=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;domain=' + location.hostname + ';';
    location.reload();
    return;
  }

  document.cookie = 'googtrans=/en/' + lang + '; path=/';

  const combo = document.querySelector('select.goog-te-combo');
  if (combo) {
    combo.value = lang;
    combo.dispatchEvent(new Event('change'));
    markActiveLangOpt();
  } else {
    // Translate widget script hasn't finished loading yet — the cookie above
    // is enough for it to pick the language up once it initializes on reload.
    location.reload();
  }
}

const langFloatWrap = document.getElementById('langFloatWrap');
const langFloatBtn = document.getElementById('langFloatBtn');
if (langFloatBtn) {
  langFloatBtn.addEventListener('click', () => {
    const open = langFloatWrap.classList.toggle('open');
    langFloatBtn.setAttribute('aria-expanded', open ? 'true' : 'false');
  });
  document.querySelectorAll('.lang-opt').forEach(btn => {
    btn.addEventListener('click', () => setSiteLanguage(btn.dataset.lang));
  });
  document.addEventListener('click', (e) => {
    if (!langFloatWrap.contains(e.target)) {
      langFloatWrap.classList.remove('open');
      langFloatBtn.setAttribute('aria-expanded', 'false');
    }
  });
  markActiveLangOpt();
}

function googleTranslateElementInit(){
  new google.translate.TranslateElement({
    pageLanguage: 'en',
    includedLanguages: 'sw,zh-CN,fr,it,es,ar',
    autoDisplay: false,
  }, 'google_translate_element');
}
</script>
<script src="https://translate.google.com/translate_a/element.js?cb=googleTranslateElementInit" async></script>
@stack('scripts')
</body>
</html>
KILI_DEPLOY_EOF
,
    'resources/views/partials/why-grid.blade.php' => <<<'KILI_DEPLOY_EOF'
{{-- Icon-card grid for "why choose us" style sections. Expects $items as an
     array of {title, text, image?}. Icons/colors cycle by position rather
     than being picked per item, so callers stay a plain title+text list.
     When an item has an image, it's shown in place of the icon. --}}
@php
  $whyIcons = [
    ['bg' => 'var(--glow)', 'svg' => '<path d="M4 21v-7M4 10V3M12 21v-9M12 8V3M20 21v-5M20 12V3"/><circle cx="4" cy="13" r="2"/><circle cx="12" cy="10" r="2"/><circle cx="20" cy="14" r="2"/>'],
    ['bg' => 'var(--c-safari)', 'svg' => '<path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 1 0-7.78 7.78L12 21.23l8.84-8.84a5.5 5.5 0 0 0 0-7.78z"/>'],
    ['bg' => 'var(--c-zanzibar)', 'svg' => '<rect x="3" y="7" width="18" height="12" rx="2"/><path d="M8 7V5a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>'],
    ['bg' => 'var(--sun)', 'svg' => '<path d="M3 9.5 12 3l9 6.5V21a1 1 0 0 1-1 1h-5v-7H9v7H4a1 1 0 0 1-1-1Z"/>'],
    ['bg' => 'var(--c-biking)', 'svg' => '<path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>'],
  ];
@endphp
<div class="why-grid">
  @foreach ($items as $i => $item)
    @php($icon = $whyIcons[$i % count($whyIcons)])
    <div class="why-card">
      @if (filled($item['image'] ?? null))
        <img class="why-card-image" src="{{ asset($item['image']) }}" alt="{{ $item['title'] }}" loading="lazy">
      @else
        <div class="why-card-icon" style="background:color-mix(in srgb, {{ $icon['bg'] }} 18%, transparent); color:{{ $icon['bg'] }}">
          <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">{!! $icon['svg'] !!}</svg>
        </div>
      @endif
      <h3>{{ $item['title'] }}</h3>
      <p>{{ $item['text'] }}</p>
    </div>
  @endforeach
</div>
KILI_DEPLOY_EOF
,
    'resources/views/trip.blade.php' => <<<'KILI_DEPLOY_EOF'
@extends('layouts.site')

@section('title', $trip['name'] . ' · Perfect Kilimanjaro')
@section('meta_description', Str::limit($trip['blurb'], 150))
@section('og_image', asset($trip['image']))

@php
  $catMeta = [
    'safari' => ['label' => 'Safaris', 'color' => 'var(--c-safari)', 'noun' => 'safari', 'adj' => 'safari'],
    'kilimanjaro' => ['label' => 'Kilimanjaro', 'color' => 'var(--c-kili)', 'noun' => 'climb', 'adj' => 'Kilimanjaro'],
    'zanzibar' => ['label' => 'Zanzibar', 'color' => 'var(--c-zanzibar)', 'noun' => 'trip', 'adj' => 'Zanzibar'],
    'east_africa' => ['label' => 'East Africa', 'color' => 'var(--c-east-africa)', 'noun' => 'trip', 'adj' => 'East Africa'],
    'biking' => ['label' => 'Biking', 'color' => 'var(--c-biking)', 'noun' => 'ride', 'adj' => 'biking'],
  ][$trip['category']];
  $sectionWord = $catMeta['label'] === 'Safaris' ? 'Safari' : $catMeta['label'];
  $galleryUrls = array_map(fn ($g) => asset($g), $trip['gallery']);

  $tripLd = [
    '@context' => 'https://schema.org',
    '@type' => 'TouristTrip',
    'name' => $trip['name'],
    'description' => $trip['blurb'],
    'image' => asset($trip['image']),
    'url' => url($trip['id']),
    'offers' => [
      '@type' => 'Offer',
      'price' => $trip['price'],
      'priceCurrency' => 'USD',
      'availability' => 'https://schema.org/InStock',
      'url' => url($trip['id']),
    ],
  ];
  if (!empty($trip['rating']) && !empty($trip['reviews'])) {
    $tripLd['aggregateRating'] = [
      '@type' => 'AggregateRating',
      'ratingValue' => $trip['rating'],
      'reviewCount' => $trip['reviews'],
    ];
  }

  $breadcrumbLd = [
    '@context' => 'https://schema.org',
    '@type' => 'BreadcrumbList',
    'itemListElement' => [
      ['@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => route('home')],
      ['@type' => 'ListItem', 'position' => 2, 'name' => 'Tours', 'item' => route('tours')],
      ['@type' => 'ListItem', 'position' => 3, 'name' => $catMeta['label'], 'item' => route('tours').'?category='.$trip['category']],
      ['@type' => 'ListItem', 'position' => 4, 'name' => $trip['name'], 'item' => url($trip['id'])],
    ],
  ];
@endphp

@section('content')

<script type="application/ld+json">{!! json_encode($tripLd, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
<script type="application/ld+json">{!! json_encode($breadcrumbLd, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>

{{-- ── Hero ───────────────────────────────────────────── --}}
<header class="trip-hero" id="top" style="--pc:{{ $catMeta['color'] }}">
  <div class="trip-hero-bg" style="background-image:url('{{ asset($trip['image']) }}')"></div>
  <div class="trip-hero-overlay"></div>

  <div class="trip-hero-inner">
    <nav class="breadcrumb">
      <a href="{{ route('home') }}">Home</a>
      <span class="sep">/</span>
      <a href="{{ route('tours') }}">Tours</a>
      <span class="sep">/</span>
      <a href="{{ route('tours') }}?category={{ $trip['category'] }}">{{ $catMeta['label'] }}</a>
      <span class="sep">/</span>
      <span class="current">{{ $trip['name'] }}</span>
    </nav>

    <h1>{{ $trip['days'] }}-Day {{ $trip['name'] }}</h1>
    <p class="hero-lede">{{ $trip['blurb'] }}</p>

    <div class="trip-hero-badges">
      <span class="hero-badge">
        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="10" r="3"/><path d="M12 21s7-7.5 7-12a7 7 0 1 0-14 0c0 4.5 7 12 7 12Z"/></svg>
        {{ $trip['location'] }}
      </span>
      <span class="hero-badge star">
        <svg width="13" height="13" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87L18.18 21 12 17.77 5.82 21 7 14.14l-5-4.87 6.91-1.01L12 2z"/></svg>
        {{ $trip['rating'] }} · {{ number_format($trip['reviews']) }} reviews
      </span>
    </div>

    <div class="trip-hero-foot">
      <div class="trip-hero-price">
        ${{ number_format($trip['price']) }}
        <small>From, per person</small>
      </div>
      <button class="btn btn-fill" style="padding:16px 28px" onclick="openBooking('{{ $trip['id'] }}')">
        Book your {{ $catMeta['noun'] }}
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
      </button>
    </div>
  </div>
</header>

{{-- ── Article body ───────────────────────────────────── --}}
<section>
  <div class="wrap">
    <div class="article" style="--pc:{{ $catMeta['color'] }}">
     <div class="trip-layout">
      <div class="trip-main">

      {{-- Opening quote --}}
      @if (filled($trip['quote'] ?? null))
      <div class="art-section">
        <blockquote class="trip-quote">
          <svg width="22" height="22" viewBox="0 0 24 24" fill="currentColor" opacity=".35"><path d="M7.17 6C4.87 6 3 7.87 3 10.17c0 2.3 1.87 4.17 4.17 4.17.34 0 .67-.04.98-.12-.4 1.5-1.5 2.7-3.15 3.4v2.3c3.5-.8 6-3.7 6-7.2V10.17C11 7.87 9.13 6 6.83 6h.34zm10 0c-2.3 0-4.17 1.87-4.17 4.17 0 2.3 1.87 4.17 4.17 4.17.34 0 .67-.04.98-.12-.4 1.5-1.5 2.7-3.15 3.4v2.3c3.5-.8 6-3.7 6-7.2V10.17C21 7.87 19.13 6 16.83 6h.34z"/></svg>
          <p>{{ $trip['quote'] }}</p>
          @if ($trip['category'] === 'kilimanjaro')
            <cite>— Priscus Peter Mtui, Lead Guide &amp; Owner, Perfect Kilimanjaro</cite>
          @endif
        </blockquote>
      </div>
      @endif

      {{-- Recommended for --}}
      @if (filled($trip['recommended_for'] ?? null))
      <div class="art-section">
        <h2>
          <svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/></svg>
          Recommended for
        </h2>
        <div class="chip-row">
          @foreach ($trip['recommended_for'] as $who)
            <span class="chip">{{ $who }}</span>
          @endforeach
        </div>
      </div>
      @endif

      {{-- Overview --}}
      <div class="art-section">
        <h2>
          <svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M12 16v-4M12 8h.01"/></svg>
          Overview
        </h2>
        <p>{{ $trip['overview'] }}</p>
      </div>

      {{-- Highlights --}}
      @if (filled($trip['highlights']))
      <div class="art-section">
        <h2>
          <svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87L18.18 21 12 17.77 5.82 21 7 14.14l-5-4.87 6.91-1.01L12 2z"/></svg>
          {{ $sectionWord }} highlights
        </h2>
        <div class="hl-list">
          @foreach ($trip['highlights'] as $h)
            <div class="hl-item">
              <span class="hl-icon">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="m14.5 9.5-1.8 4.7-4.7 1.8 1.8-4.7z"/></svg>
              </span>
              <span>{{ $h }}</span>
            </div>
          @endforeach
        </div>
      </div>

      @endif

      {{-- Duration comparison --}}
      @if (filled($trip['route_comparison_intro'] ?? null) || filled($trip['route_comparison_tiers'] ?? null))
      <div class="art-section">
        <h2>
          <svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 3v18M7 3v18M3 8h4M3 16h4M17 8h4M17 16h4"/></svg>
          How this version compares
        </h2>
        @if (filled($trip['route_comparison_intro'] ?? null))
          <p>{{ $trip['route_comparison_intro'] }}</p>
        @endif

        @if (filled($trip['route_comparison_tiers'] ?? null))
          <div class="compare-list">
            @foreach ($trip['route_comparison_tiers'] as $tier)
              <div class="compare-item">
                @if (filled($tier['medal'] ?? null))
                  <span class="compare-medal compare-medal--{{ $tier['medal'] }}">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="8" r="6"/><path d="M15.5 13.5 17 22l-5-3-5 3 1.5-8.5"/></svg>
                  </span>
                @endif
                <div>
                  <div class="compare-title">
                    {{ $tier['title'] }}
                    @if (filled($tier['rate'] ?? null))<span class="compare-rate">{{ $tier['rate'] }}</span>@endif
                  </div>
                  <p>{{ $tier['description'] }}</p>
                </div>
              </div>
            @endforeach
          </div>
        @endif

        @if (filled($trip['choose_this_if'] ?? null) || filled($trip['choose_other_if'] ?? null))
          <div class="choose-grid">
            @if (filled($trip['choose_this_if'] ?? null))
              <div class="choose-col choose-yes">
                <h3>
                  <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
                  Choose this version if
                </h3>
                <ul>
                  @foreach ($trip['choose_this_if'] as $reason)<li>{{ $reason }}</li>@endforeach
                </ul>
              </div>
            @endif
            @if (filled($trip['choose_other_if'] ?? null))
              <div class="choose-col choose-no">
                <h3>
                  <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 9v4M12 17h.01M10.3 3.9 1.8 18a2 2 0 0 0 1.7 3h17a2 2 0 0 0 1.7-3L13.7 3.9a2 2 0 0 0-3.4 0Z"/></svg>
                  Choose {{ $trip['choose_other_label'] ?? 'a different version' }} instead if
                </h3>
                <ul>
                  @foreach ($trip['choose_other_if'] as $reason)<li>{{ $reason }}</li>@endforeach
                </ul>
              </div>
            @endif
          </div>
        @endif
      </div>
      @endif

      {{-- Itinerary --}}
      @if (filled($trip['itinerary']))
      <div class="art-section">
        <h2>
          <svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="4" y="5" width="16" height="16" rx="2"/><path d="M8 3v4M16 3v4M4 10h16"/></svg>
          Detailed itinerary
        </h2>
        <div class="itin-list">
          @foreach ($trip['itinerary'] as $i => $day)
            <div class="itin-item open">
              <button class="itin-head" onclick="toggleItin(this)" aria-expanded="true">
                <span class="itin-num">{{ $i + 1 }}</span>
                <span class="itin-title">{{ $day['t'] }}</span>
                <svg class="itin-chev" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
              </button>
              <div class="itin-body">
                <div class="itin-body-inner">
                  <div class="itin-content">
                    <p>{{ $day['d'] }}</p>
                    <div class="itin-meta">
                      @isset($day['elev'])
                        <span class="elev"><span class="k">Elevation</span>{{ $day['elev'] }}</span>
                      @endisset
                      @isset($day['distance'])
                        <span><span class="k">Distance</span>{{ $day['distance'] }}</span>
                      @endisset
                      @isset($day['time'])
                        <span><span class="k">Hiking time</span>{{ $day['time'] }}</span>
                      @endisset
                      @isset($day['vegetation'])
                        <span><span class="k">Vegetation</span>{{ $day['vegetation'] }}</span>
                      @endisset
                      @isset($day['difficulty'])
                        <span><span class="k">Difficulty</span>{{ $day['difficulty'] }}</span>
                      @endisset
                      <span><span class="k">Stay</span>{{ $day['stay'] }}</span>
                      <span><span class="k">Meals</span>{{ $day['meals'] }}</span>
                    </div>
                    @if (filled($day['note'] ?? null))
                      <div class="itin-note">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4Z"/></svg>
                        <span>{{ $day['note'] }}</span>
                      </div>
                    @endif
                  </div>
                </div>
              </div>
            </div>
          @endforeach
        </div>
      </div>

      @endif

      {{-- Route map (Kilimanjaro routes only) --}}
      @if ($trip['category'] === 'kilimanjaro')
      <div class="art-section" id="tripRouteMapSection" style="display:none">
        <h2>
          <svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 20l-5.5-2.5V4L9 6.5m0 13.5 6-2.5m-6 2.5V6.5m6 11L20.5 20V6.5L15 4m0 13.5V4m0 0L9 6.5"/></svg>
          Route map
        </h2>
        <div class="route-explorer" style="margin-top:0">
          <div class="route-stats-row" id="tripRouteStats"></div>
          <div class="route-map-card">
            <div class="route-map-legend">
              <span><svg viewBox="0 0 20 8"><line x1="0" y1="4" x2="20" y2="4" stroke="#20261f" stroke-width="2" stroke-dasharray="3 3"/></svg>Ascent</span>
              <span><svg viewBox="0 0 20 8"><line x1="0" y1="4" x2="20" y2="4" stroke="#3f6b4a" stroke-width="2" stroke-dasharray="3 3"/></svg>Descent</span>
            </div>
            <svg id="tripKiliMap" viewBox="60 150 680 510" xmlns="http://www.w3.org/2000/svg"></svg>
          </div>
          <p class="route-note">Elevations and camp order are approximate — your booked itinerary is the one that governs.</p>
        </div>
      </div>
      @endif

      {{-- Where you'll stay --}}
      @if (filled($trip['stays']))
      <div class="art-section">
        <h2>
          <svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 20h20M4 20V10l8-6 8 6v10M9 20v-6h6v6"/></svg>
          Where you'll stay
          @if (count($trip['stays']) > 1)
            <span class="stay-count">{{ count($trip['stays']) }} properties</span>
          @endif
        </h2>
        <div class="stay-list">
          @foreach ($trip['stays'] as $stay)
            <div class="stay-card">
              <div class="stay-photo">
                <img src="{{ asset($stay['image'] ?: $trip['image']) }}" alt="{{ $stay['name'] }}" loading="lazy">
                @if ($stay['class'])
                  <span class="trip-tag-overlay">{{ $stay['class'] }}</span>
                @endif
              </div>
              <div class="stay-body">
                <div class="stay-name">{{ $stay['name'] }}</div>
                <div class="stay-stars">
                  @for ($s = 1; $s <= 5; $s++)
                    <span class="{{ $s <= $stay['stars'] ? '' : 'off' }}">★</span>
                  @endfor
                </div>
                @if ($stay['place'] || $trip['accommodation'])
                  <div class="trip-metrics" style="margin-bottom:0">
                    @if ($stay['place'])<span class="metric accent">{{ $stay['place'] }}</span>@endif
                    @if ($trip['accommodation'])<span class="metric">{{ $trip['accommodation'] }}</span>@endif
                  </div>
                @endif
                @if ($stay['note'])
                  <p class="stay-note">{{ $stay['note'] }}</p>
                @endif
                @if (filled($stay['amenities']))
                  <div class="amen">
                    @foreach ($stay['amenities'] as $a)
                      <span>
                        <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
                        {{ $a }}
                      </span>
                    @endforeach
                  </div>
                @endif
              </div>
            </div>
          @endforeach
        </div>
      </div>
      @endif

      {{-- Gallery --}}
      @if (filled($trip['gallery']))
      <div class="art-section">
        <h2>
          <svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="m21 15-5-5L5 21"/></svg>
          {{ $sectionWord }} gallery
        </h2>
        <div class="gallery-grid">
          @foreach ($trip['gallery'] as $i => $g)
            <div class="gal-item" onclick="openLightbox({{ Js::from($galleryUrls) }}, {{ $i }})">
              <img src="{{ asset($g) }}" alt="{{ $trip['name'] }} photo {{ $i + 1 }}" loading="lazy">
              <span class="gal-zoom">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="7"/><path d="m20 20-3.5-3.5M11 8v6M8 11h6"/></svg>
              </span>
            </div>
          @endforeach
        </div>
      </div>

      @endif

      {{-- Video --}}
      @if (filled($trip['video_youtube_id']))
      <div class="art-section">
        <h2>
          <svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="m10 8 6 4-6 4Z"/></svg>
          Watch first
        </h2>
        <div class="video-card" id="videoCard" data-yt="{{ $trip['video_youtube_id'] }}" onclick="playPageVideo()">
          <img class="video-thumb" src="https://img.youtube.com/vi/{{ $trip['video_youtube_id'] }}/maxresdefault.jpg" alt="{{ $trip['name'] }} video" loading="lazy">
          <div class="video-scrim"></div>
          <div class="video-channel">
            <div class="brand-mark">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="#1a0a00"><path d="m8 3 4 8 5-5 5 15H2L8 3z"/></svg>
            </div>
            <div class="video-channel-text">
              <strong class="notranslate">Perfect Kilimanjaro</strong>
              <span>Tanzania Safaris, Kilimanjaro &amp; Zanzibar</span>
            </div>
          </div>
          <button class="video-play" aria-label="Play video">
            <svg width="26" height="26" viewBox="0 0 24 24" fill="currentColor"><path d="M8 5v14l11-7z"/></svg>
          </button>
          <span class="video-yt-badge">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M23.5 6.2a3 3 0 0 0-2.1-2.1C19.5 3.5 12 3.5 12 3.5s-7.5 0-9.4.6A3 3 0 0 0 .5 6.2 31 31 0 0 0 0 12a31 31 0 0 0 .5 5.8 3 3 0 0 0 2.1 2.1c1.9.6 9.4.6 9.4.6s7.5 0 9.4-.6a3 3 0 0 0 2.1-2.1A31 31 0 0 0 24 12a31 31 0 0 0-.5-5.8ZM9.6 15.5v-7l6.3 3.5-6.3 3.5Z"/></svg>
            Watch on YouTube
          </span>
        </div>
      </div>
      @endif

      {{-- Complete pricing --}}
      @if (filled($trip['price_tiers'] ?? null))
      <div class="art-section">
        <h2>
          <svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M14.8 9a2.8 2.8 0 0 0-2.8-1.5c-1.7 0-3 1-3 2.3 0 3.3 6 1.6 6 4.9 0 1.3-1.3 2.3-3 2.3a2.8 2.8 0 0 1-2.8-1.5M12 6v1.3M12 16.7V18"/></svg>
          Complete pricing
        </h2>
        <div class="price-table-wrap">
          <table class="price-table">
            <thead>
              <tr>
                <th>Group size</th>
                @if (collect($trip['price_tiers'])->contains(fn ($t) => filled($t['park_fees'] ?? null)))
                  <th>Park fees</th>
                  <th>Other costs</th>
                @endif
                <th>Total per person</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($trip['price_tiers'] as $tier)
                <tr>
                  <td>{{ $tier['label'] }}</td>
                  @if (collect($trip['price_tiers'])->contains(fn ($t) => filled($t['park_fees'] ?? null)))
                    <td>${{ number_format($tier['park_fees'] ?? 0) }}</td>
                    <td>${{ number_format($tier['other_costs'] ?? 0) }}</td>
                  @endif
                  <td><strong>${{ number_format($tier['price']) }}</strong></td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
      @endif

      {{-- Included / not included --}}
      @if (filled($trip['included']) || filled($trip['excluded']))
      <div class="art-section">
        <div class="incl-grid">
          <div class="incl-col incl-yes">
            <h3>
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
              What's included
            </h3>
            <ul>
              @foreach ($trip['included'] as $item)
                <li>{{ $item }}</li>
              @endforeach
            </ul>
          </div>
          <div class="incl-col incl-no">
            <h3>
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18M6 6l12 12"/></svg>
              What's not included
            </h3>
            <ul>
              @foreach ($trip['excluded'] as $item)
                <li>{{ $item }}</li>
              @endforeach
            </ul>
          </div>
        </div>
      </div>

      @endif

      {{-- Tipping guide --}}
      @if (filled($trip['tipping_table'] ?? null))
      <div class="art-section">
        <h2>
          <svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/></svg>
          Tipping guide
        </h2>
        <div class="price-table-wrap">
          <table class="price-table">
            <thead>
              <tr><th>Crew member</th><th>Recommended tip per day</th></tr>
            </thead>
            <tbody>
              @foreach ($trip['tipping_table'] as $row)
                <tr><td>{{ $row['role'] }}</td><td>{{ $row['tip'] }}</td></tr>
              @endforeach
            </tbody>
          </table>
        </div>
        <p class="route-note">Tipping is not mandatory but is highly appreciated and depends on your satisfaction with the service.</p>
      </div>
      @endif

      {{-- Optional extras --}}
      @if (filled($trip['optional_extras'] ?? null))
      <div class="art-section">
        <h2>
          <svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14M5 12h14"/></svg>
          Optional extras
        </h2>
        <ul class="extras-list">
          @foreach ($trip['optional_extras'] as $extra)
            <li>{{ $extra }}</li>
          @endforeach
        </ul>
      </div>
      @endif

      {{-- Closing note --}}
      @if (filled($trip['closing_note'] ?? null))
      <div class="art-section">
        <h2>
          <svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4Z"/></svg>
          A note from Priscus
        </h2>
        <div class="closing-note">
          @foreach (explode("\n\n", $trip['closing_note']) as $para)
            <p>{{ $para }}</p>
          @endforeach
          @if ($trip['category'] === 'kilimanjaro')
            <p class="closing-sign">
              — Priscus Peter Mtui<br>
              <em>Lead Guide &amp; Owner, Perfect Kilimanjaro</em><br>
              The Ngatambaku Mtui Family · Marangu Village · Moshi, Tanzania
            </p>
          @endif
        </div>
      </div>
      @endif

      {{-- Why book --}}
      <div class="art-section">
        <h2>
          <svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 1 0-7.78 7.78L12 21.23l8.84-8.84a5.5 5.5 0 0 0 0-7.78z"/></svg>
          Why book with Perfect Kilimanjaro
        </h2>
        @include('partials.why-grid', ['items' => [
          ['title' => 'Tailor-made, not off the shelf', 'text' => 'Every itinerary is adjusted to your dates, pace and budget — this page is a starting point, not a fixed package.'],
          ['title' => 'Tanzanian guides, on staff', 'text' => 'Local guides who have worked these parks and routes for years, employed by us rather than subcontracted per trip.'],
          ['title' => 'Private departures', 'text' => 'Your vehicle and your group. We never add strangers to fill seats or combine bookings to cut our costs.'],
          ['title' => "Stays we've slept in", 'text' => 'Camps and lodges chosen from first-hand stays, not booked blind off a wholesale list.'],
          ['title' => 'One thread, start to finish', 'text' => 'The same WhatsApp conversation from your first question to the day you fly home — usually answered within the hour.'],
        ]])
      </div>

      </div>{{-- /.trip-main --}}

      {{-- Booking card --}}
      <aside class="trip-aside">
        <div class="book-card">
          <div class="book-from">From</div>
          <div class="book-price">${{ number_format($trip['price']) }}</div>
          <p class="book-note">per person sharing, excluding international flights</p>

          @if (!empty($trip['price_tiers']))
            <div class="price-tiers">
              <div class="price-tiers-title">Price per person by group size</div>
              @foreach ($trip['price_tiers'] as $tier)
                <div class="spec-row">
                  <span class="k">{{ $tier['label'] }}</span>
                  <span class="v">${{ number_format($tier['price']) }}</span>
                </div>
              @endforeach
            </div>
          @endif

          <div class="spec-row">
            <span class="k">Duration</span>
            <span class="v">{{ $trip['days'] }} Days · {{ $trip['days'] - 1 }} Nights</span>
          </div>
          <div class="spec-row">
            <span class="k">Group size</span>
            <span class="v">{{ $trip['group'] }}</span>
          </div>
          <div class="spec-row">
            <span class="k">Difficulty</span>
            <span class="v">{{ $trip['difficulty'] }}</span>
          </div>
          <div class="spec-row">
            <span class="k">Best time</span>
            <span class="v">{{ $trip['best_time'] }}</span>
          </div>
          <div class="spec-row">
            <span class="k">Starts &amp; ends</span>
            <span class="v">{{ $trip['start_point'] }}</span>
          </div>

          <div class="book-actions">
            <button class="btn btn-fill" onclick="openBooking('{{ $trip['id'] }}')">
              Book this adventure
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
            </button>
            <a class="btn btn-line" href="https://wa.me/255752967222" target="_blank" rel="noopener">Ask a question</a>
            <a class="btn btn-line" href="{{ route('trip.pdf', $trip['id']) }}">
              Download PDF
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 15V3M6 15l6 6 6-6M4 21h16"/></svg>
            </a>
          </div>

          <p class="book-fine">No payment taken online — we confirm availability first.</p>
        </div>
      </aside>
     </div>{{-- /.trip-layout --}}

      {{-- Final CTA --}}
      <div class="art-section">
        <div class="trip-cta-band">
          <h3>Tell us your dates and group size</h3>
          <p>We'll confirm availability, send an exact per-person quote and a day-by-day plan within one business day. No deposit needed to get a quote.</p>
          <div class="btn-row">
            <button class="btn btn-fill" style="padding:16px 28px" onclick="openBooking('{{ $trip['id'] }}')">
              Book this {{ $catMeta['noun'] }}
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
            </button>
            <a class="btn btn-line" href="https://wa.me/255752967222" target="_blank" rel="noopener">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M12.01 2C6.48 2 2 6.48 2 12c0 1.85.5 3.58 1.36 5.07L2 22l5.06-1.33A9.94 9.94 0 0 0 12.01 22C17.52 22 22 17.52 22 12S17.52 2 12.01 2zm5.5 14.14c-.23.65-1.14 1.19-1.87 1.35-.5.11-1.15.19-3.35-.72-2.82-1.17-4.63-4.03-4.77-4.22-.14-.19-1.15-1.53-1.15-2.92 0-1.39.73-2.07.99-2.35.23-.25.5-.32.67-.32h.48c.15 0 .36-.06.56.43l.79 1.9c.09.19.15.42.03.67-.12.25-.18.4-.36.6l-.45.52c-.14.14-.29.29-.13.57.15.28.68 1.12 1.46 1.81 1 .89 1.82 1.17 2.11 1.3.23.1.51.08.68-.1l.62-.72c.24-.28.5-.24.79-.14l1.77.83c.28.14.48.21.55.33.07.12.07.7-.16 1.35z"/></svg>
              Ask on WhatsApp
            </a>
          </div>
        </div>
      </div>

    </div>
  </div>
</section>

{{-- ── Related ────────────────────────────────────────── --}}
@if (count($related))
<section style="padding-top:0">
  <div class="wrap">
    <div class="tag">More to explore</div>
    <h2 class="section-title" style="margin-bottom:20px">Other <em>{{ $catMeta['adj'] }}</em> trips</h2>
    <div class="trips-grid" style="--accent: {{ $catMeta['color'] }}">
      @foreach ($related as $r)
        <a class="trip" href="{{ url($r['id']) }}">
          <div class="trip-media">
            <img src="{{ asset($r['image']) }}" alt="{{ $r['name'] }}" loading="lazy">
            <span class="trip-tag-overlay">{{ $catMeta['label'] }}</span>
            <span class="trip-rating-overlay">
              <svg width="11" height="11" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87L18.18 21 12 17.77 5.82 21 7 14.14l-5-4.87 6.91-1.01L12 2z"/></svg>
              {{ $r['rating'] }}
            </span>
          </div>
          <div class="trip-body">
            <div class="trip-nick">{{ $r['nickname'] }}</div>
            <div class="trip-name">{{ $r['name'] }}</div>
            <div class="trip-metrics">
              <span class="metric">{{ $r['days'] }} days</span>
              <span class="metric">{{ $r['group'] }}</span>
              <span class="metric">{{ $r['difficulty'] }}</span>
            </div>
            <p class="trip-blurb">{{ $r['blurb'] }}</p>
            <div class="trip-foot">
              <div>
                <div class="trip-price">${{ number_format($r['price']) }}<small>per person</small></div>
                <div class="trip-reviews">{{ number_format($r['reviews']) }} reviews</div>
              </div>
              <span class="trip-view-btn">
                View
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
              </span>
            </div>
          </div>
        </a>
      @endforeach
    </div>
  </div>
</section>
@endif

@if ($trip['category'] === 'kilimanjaro')
@push('scripts')
@include('partials.kili-map-data')
<script>
(function(){
  const mapEl = document.getElementById('tripKiliMap');
  const route = KILI_ROUTE_DEFS.find(r => r.id === @json($trip['id']));
  if (!mapEl || !route) return;

  document.getElementById('tripRouteMapSection').style.display = '';
  document.getElementById('tripRouteStats').innerHTML = `
    <div class="route-stat-pill">Duration<b>${route.days}</b></div>
    <div class="route-stat-pill">Difficulty<b style="color:${kiliDifficultyColor(route.difficulty)}">${route.difficulty}</b></div>
    <div class="route-stat-pill">Camps<b>${route.ascent.length + route.descent.length - 2}</b></div>`;

  mapEl.innerHTML = kiliBuildMapSvg(route);
  kiliAnimateRoutePaths(mapEl);
})();
</script>
@endpush
@endif

@endsection
KILI_DEPLOY_EOF
,
    'routes/web.php' => <<<'KILI_DEPLOY_EOF'
<?php

use App\Http\Controllers\BlogController;
use App\Http\Controllers\InquiryController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\TripController;
use App\Models\AboutContent;
use App\Models\ClimbingPrepContent;
use App\Models\EastAfricaPriceContent;
use App\Models\HomeContent;
use App\Models\Package;
use App\Models\Post;
use App\Models\Review;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome', [
        'trips' => Package::forSite(),
        'featuredReviews' => Review::approved()->featured()->with('package')->latest('stayed_at')->take(3)->get(),
        'home' => HomeContent::current(),
    ]);
})->name('home');

Route::get('/tours', function () {
    return view('tours', ['trips' => Package::forSite()]);
})->name('tours');

// Declared before the catch-all "/{trip}" route below, which would
// otherwise swallow "/reviews" (and "/about", "/team") as if they were
// package slugs.
Route::get('/reviews', [ReviewController::class, 'index'])->name('reviews.index');
Route::post('/reviews', [ReviewController::class, 'store'])->name('reviews.store');

Route::get('/about', function () {
    return view('about', ['about' => AboutContent::current()]);
})->name('about');

Route::get('/team', function () {
    return view('team', ['about' => AboutContent::current()]);
})->name('team');

Route::get('/climbing-preparation', function () {
    return view('climbing-preparation', [
        'prep' => ClimbingPrepContent::current(),
        'equipPdf' => HomeContent::current()->equip_pdf,
    ]);
})->name('climbing-prep');

Route::get('/kilimanjaro-east-african-citizens', function () {
    return view('east-africa-prices', [
        'page' => EastAfricaPriceContent::current(),
        'groups' => EastAfricaPriceContent::GROUPS,
        'liveSlugs' => Package::published()->pluck('slug')->all(),
    ]);
})->name('east-africa-prices');

Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/{post}', [BlogController::class, 'show'])->name('blog.show');

Route::post('/inquiries', [InquiryController::class, 'store'])->name('inquiries.store');

Route::get('/robots.txt', function () {
    $lines = [
        'User-agent: *',
        'Disallow: /admin',
        '',
        'Sitemap: '.route('sitemap'),
    ];

    return response(implode("\n", $lines))->header('Content-Type', 'text/plain');
});

Route::get('/sitemap.xml', function () {
    $staticPages = [
        ['url' => route('home'), 'priority' => '1.0', 'changefreq' => 'weekly'],
        ['url' => route('tours'), 'priority' => '0.9', 'changefreq' => 'weekly'],
        ['url' => route('reviews.index'), 'priority' => '0.6', 'changefreq' => 'weekly'],
        ['url' => route('about'), 'priority' => '0.5', 'changefreq' => 'monthly'],
        ['url' => route('team'), 'priority' => '0.5', 'changefreq' => 'monthly'],
        ['url' => route('climbing-prep'), 'priority' => '0.6', 'changefreq' => 'monthly'],
        ['url' => route('east-africa-prices'), 'priority' => '0.7', 'changefreq' => 'monthly'],
        ['url' => route('blog.index'), 'priority' => '0.7', 'changefreq' => 'weekly'],
    ];

    $packages = Package::published()->ordered()->get(['slug', 'updated_at'])->map(fn ($p) => [
        'url' => url($p->slug),
        'lastmod' => $p->updated_at?->toAtomString(),
        'priority' => '0.8',
        'changefreq' => 'monthly',
    ]);

    $posts = Post::published()->ordered()->get(['slug', 'updated_at'])->map(fn ($p) => [
        'url' => route('blog.show', $p->slug),
        'lastmod' => $p->updated_at?->toAtomString(),
        'priority' => '0.6',
        'changefreq' => 'monthly',
    ]);

    $urls = collect($staticPages)->concat($packages)->concat($posts);

    return response()
        ->view('sitemap', ['urls' => $urls])
        ->header('Content-Type', 'application/xml');
})->name('sitemap');

Route::get('/{trip}/pdf', [TripController::class, 'pdf'])
    ->where('trip', '[a-z0-9-]+')
    ->name('trip.pdf');

Route::get('/{trip}', [TripController::class, 'show'])
    ->where('trip', '[a-z0-9-]+')
    ->name('trip.show');
KILI_DEPLOY_EOF
,
];


echo "\n" . count($files) . " file(s) to check\n";
$changed = 0;
foreach ($files as $rel => $content) {
    $path = "$root/$rel";
    $isNew = ! file_exists($path);

    if (! $isNew && file_get_contents($path) === $content) {
        echo "  SKIP    (already up to date) $rel\n";
        continue;
    }

    $changed++;
    echo ($isNew ? "  NEW     " : "  WRITE   ") . "$rel\n";

    if (! $DRY) {
        @mkdir(dirname($path), 0755, true);
        if (! $isNew) {
            copy($path, $path . '.bak-' . date('Ymd-His'));
        }
        file_put_contents($path, $content);
    }
}

echo "\n$changed file(s) " . ($DRY ? "would be " : "") . "written.\n";
echo "\nNext (run these yourself, not part of this script):\n";
echo "  composer require barryvdh/laravel-dompdf:^3.1\n";
echo "  php artisan migrate --force\n";
echo "  php artisan config:clear && php artisan route:clear && php artisan view:clear && php artisan cache:clear\n";
