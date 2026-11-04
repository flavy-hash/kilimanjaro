<?php

namespace App\Filament\Pages;

use App\Models\Setting;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\EmbeddedSchema;
use Filament\Schemas\Components\Form;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

/**
 * Settings behind the Instant Quote feature — who gets notified, the bank
 * details and terms printed on every quote PDF, and the two pricing rules
 * (validity window, child discount) shared by the guest flow and the admin
 * "auto-fill from product" action.
 */
class QuoteSettings extends Page
{
    protected string $view = 'filament.pages.quote-settings';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCurrencyDollar;

    protected static ?string $navigationLabel = 'Quote Settings';

    protected static string|\UnitEnum|null $navigationGroup = 'Bookings';

    protected static ?int $navigationSort = 3;

    protected static ?string $title = 'Quote settings';

    /** @var array<string, mixed> */
    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill([
            'office_emails' => collect(Setting::quoteOfficeEmails())->map(fn ($email) => ['email' => $email])->all(),
            'emails_enabled' => Setting::quoteEmailsEnabled(),
            'bank_accounts' => Setting::quoteBankAccounts(),
            'payment_instructions' => Setting::quotePaymentInstructions(),
            'validity_days' => Setting::quoteValidityDays(),
            'child_price_percent' => Setting::childPricePercent(),
            'terms' => Setting::quoteTerms(),
        ]);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Office notifications')
                    ->description('Who gets emailed a copy of every instant quote, with the PDF attached.')
                    ->schema([
                        Toggle::make('emails_enabled')
                            ->label('Send quote emails to the office')
                            ->default(true),

                        Repeater::make('office_emails')
                            ->hiddenLabel()
                            ->simple(
                                TextInput::make('email')->email()->required()
                            )
                            ->addActionLabel('Add email')
                            ->default([]),
                    ]),

                Section::make('Pricing rules')
                    ->columns(2)
                    ->schema([
                        TextInput::make('validity_days')
                            ->label('Quote validity (days)')
                            ->numeric()
                            ->minValue(1)
                            ->required(),

                        TextInput::make('child_price_percent')
                            ->label('Child price (% of adult price)')
                            ->numeric()
                            ->minValue(0)
                            ->maxValue(100)
                            ->required()
                            ->suffix('%'),
                    ]),

                Section::make('Bank accounts')
                    ->description('Shown on the PDF when a quote has "show bank details" turned on.')
                    ->schema([
                        Repeater::make('bank_accounts')
                            ->hiddenLabel()
                            ->addActionLabel('Add bank account')
                            ->default([])
                            ->columns(2)
                            ->itemLabel(fn (array $state): ?string => $state['bank'] ?? null)
                            ->schema([
                                TextInput::make('bank')->label('Bank name')->required(),
                                TextInput::make('account_name')->label('Account name')->required(),
                                TextInput::make('account_number')->label('Account number')->required(),
                                TextInput::make('branch')->label('Branch'),
                                TextInput::make('swift')->label('SWIFT / BIC'),
                            ]),
                    ]),

                Section::make('Payment instructions')
                    ->schema([
                        Textarea::make('payment_instructions')
                            ->hiddenLabel()
                            ->rows(3),
                    ]),

                Section::make('Quote terms')
                    ->description('Printed at the bottom of every quote PDF.')
                    ->schema([
                        Textarea::make('terms')
                            ->hiddenLabel()
                            ->rows(5),
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

        Setting::set('quote_emails_enabled', $data['emails_enabled'] ? '1' : '0');
        Setting::setJson('quote_office_emails', collect($data['office_emails'])->pluck('email')->filter()->values()->all());
        Setting::setJson('quote_bank_accounts', $data['bank_accounts']);
        Setting::set('quote_payment_instructions', $data['payment_instructions']);
        Setting::set('quote_validity_days', (string) $data['validity_days']);
        Setting::set('quote_child_price_percent', (string) $data['child_price_percent']);
        Setting::set('quote_terms', $data['terms']);

        Notification::make()
            ->success()
            ->title('Quote settings updated')
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
        ];
    }
}
