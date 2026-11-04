<?php

namespace Tests\Feature;

use App\Enums\QuoteSource;
use App\Enums\QuoteStatus;
use App\Filament\Resources\Quotes\Pages\EditQuote;
use App\Mail\NewQuoteReceived;
use App\Models\Inquiry;
use App\Models\Package;
use App\Models\Quote;
use App\Models\Setting;
use App\Models\User;
use App\Notifications\NewInstantQuote;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\URL;
use Livewire\Livewire;
use Tests\TestCase;

class QuoteTest extends TestCase
{
    use RefreshDatabase;

    private function validPayload(Package $package, array $overrides = []): array
    {
        return array_merge([
            'package' => $package->slug,
            'name' => 'Jane Traveler',
            'email' => 'jane@example.com',
            'phone' => '+1 555 0100',
            'country' => 'United States',
            'travel_start' => now()->addMonth()->toDateString(),
            'adults' => 2,
            'children' => 0,
        ], $overrides);
    }

    public function test_guest_can_request_a_quote(): void
    {
        $package = Package::factory()->create(['price' => 1000, 'days' => 6, 'name' => 'Machame Route']);

        $response = $this->post(route('quotes.store'), $this->validPayload($package));

        $response->assertSessionHasNoErrors();
        $response->assertRedirect();

        $quote = Quote::first();

        $this->assertNotNull($quote);
        $this->assertSame(QuoteSource::Website, $quote->source);
        $this->assertSame(QuoteStatus::Sent, $quote->status);
        $this->assertNotNull($quote->reference);
        $this->assertStringStartsWith('QT-'.now()->year.'-', $quote->reference);
        $this->assertSame(2, $quote->adults);
        $this->assertCount(1, $quote->items);
        $this->assertSame(2000.0, $quote->total());

        $result = session('quote_result');
        $this->assertNotNull($result);
        $this->assertSame($quote->reference, $result['reference']);
        $this->assertNotEmpty($result['pdf_url']);
    }

    public function test_child_pricing_uses_configured_percentage_and_end_date_is_computed(): void
    {
        Setting::set('quote_child_price_percent', '50');

        $package = Package::factory()->create(['price' => 1000, 'days' => 6]);
        $start = now()->addMonth()->startOfDay();

        $this->post(route('quotes.store'), $this->validPayload($package, [
            'adults' => 2,
            'children' => 1,
            'travel_start' => $start->toDateString(),
        ]))->assertSessionHasNoErrors();

        $quote = Quote::first();

        $this->assertCount(2, $quote->items);
        $this->assertSame(1, $quote->items[1]['quantity']);
        $this->assertSame(500.0, (float) $quote->items[1]['unit_price']);
        $this->assertSame(2000.0 + 500.0, $quote->total());

        $this->assertNotNull($quote->travel_end);
        $this->assertSame(
            $start->copy()->addDays(5)->toDateString(),
            $quote->travel_end->toDateString()
        );
    }

    public function test_travel_date_in_the_past_is_rejected(): void
    {
        $package = Package::factory()->create(['price' => 1000]);

        $response = $this->post(route('quotes.store'), $this->validPayload($package, [
            'travel_start' => now()->subDay()->toDateString(),
        ]));

        $response->assertSessionHasErrorsIn('quote', ['travel_start']);
        $this->assertSame(0, Quote::count());
    }

    public function test_missing_required_fields_are_rejected(): void
    {
        $package = Package::factory()->create(['price' => 1000]);

        $response = $this->post(route('quotes.store'), [
            'package' => $package->slug,
        ]);

        $response->assertSessionHasErrorsIn('quote', ['name', 'email', 'travel_start', 'adults']);
        $this->assertSame(0, Quote::count());
    }

    public function test_honeypot_field_rejects_the_request(): void
    {
        $package = Package::factory()->create(['price' => 1000]);

        $response = $this->post(route('quotes.store'), $this->validPayload($package, [
            'website' => 'http://spam.example',
        ]));

        $response->assertSessionHasErrorsIn('quote');
        $this->assertSame(0, Quote::count());
    }

    public function test_product_without_a_price_returns_404(): void
    {
        $package = Package::factory()->create(['price' => 0]);

        $response = $this->post(route('quotes.store'), $this->validPayload($package));

        $response->assertNotFound();
        $this->assertSame(0, Quote::count());
    }

    public function test_unpublished_product_returns_404(): void
    {
        $package = Package::factory()->unpublished()->create(['price' => 1000]);

        $response = $this->post(route('quotes.store'), $this->validPayload($package));

        $response->assertNotFound();
        $this->assertSame(0, Quote::count());
    }

    public function test_pdf_link_works_only_when_signed(): void
    {
        $quote = Quote::factory()->create();

        $signedUrl = URL::temporarySignedRoute('quotes.pdf', now()->addDays(7), ['quote' => $quote->id]);

        $this->get($signedUrl)->assertOk();

        $this->get(route('quotes.pdf', $quote))->assertForbidden();
    }

    public function test_pdf_link_fails_when_tampered(): void
    {
        $quote = Quote::factory()->create();

        $signedUrl = URL::temporarySignedRoute('quotes.pdf', now()->addDays(7), ['quote' => $quote->id]);
        $tampered = $signedUrl.'&adults=99';

        $this->get($tampered)->assertForbidden();
    }

    public function test_office_email_and_admin_notification_are_sent(): void
    {
        Mail::fake();
        Notification::fake();
        User::factory()->create();

        $package = Package::factory()->create(['price' => 1000]);

        $this->post(route('quotes.store'), $this->validPayload($package))
            ->assertSessionHasNoErrors();

        $quote = Quote::first();

        Mail::assertSent(NewQuoteReceived::class, fn ($mail) => $mail->quote->is($quote));
        Notification::assertSentTo(User::all(), NewInstantQuote::class);
    }

    public function test_guest_still_gets_the_quote_if_mail_sending_throws(): void
    {
        Mail::shouldReceive('to')->andThrow(new \Exception('SMTP is down'));

        $package = Package::factory()->create(['price' => 1000]);

        $response = $this->post(route('quotes.store'), $this->validPayload($package));

        $response->assertSessionHasNoErrors();
        $this->assertNotNull(session('quote_result'));
        $this->assertSame(1, Quote::count());
    }

    public function test_admin_can_create_a_quote_and_picking_a_product_autofills_items(): void
    {
        $this->actingAs(User::factory()->create());

        $package = Package::factory()->create(['price' => 1200, 'days' => 7, 'name' => 'Lemosho Route']);

        Livewire::test(\App\Filament\Resources\Quotes\Pages\CreateQuote::class)
            ->fillForm([
                'customer_name' => 'Ada Admin',
                'customer_email' => 'ada@example.com',
                'package_id' => $package->id,
                'adults' => 2,
                'children' => 0,
            ])
            ->assertFormSet([
                'items' => Quote::itemsForPackage($package, 2, 0),
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $quote = Quote::first();

        $this->assertNotNull($quote);
        $this->assertSame(QuoteSource::Admin, $quote->source);
        $this->assertCount(1, $quote->items);
        $this->assertSame(2400.0, $quote->total());
    }

    public function test_create_quote_page_prefills_from_an_inquiry(): void
    {
        $this->actingAs(User::factory()->create());

        $package = Package::factory()->create(['price' => 900, 'days' => 5]);

        $inquiry = Inquiry::create([
            'reference' => 'SAF-00001',
            'package_id' => $package->id,
            'name' => 'Ivy Inquirer',
            'email' => 'ivy@example.com',
            'phone' => '+44 7000 000000',
            'start_date' => now()->addMonth()->toDateString(),
            'group_size' => 3,
            'status' => 'new',
        ]);

        $component = Livewire::test(\App\Filament\Resources\Quotes\Pages\CreateQuote::class, ['inquiry' => $inquiry->id])
            ->assertSet('inquiry', $inquiry->id)
            ->assertFormSet([
                'customer_name' => 'Ivy Inquirer',
                'customer_email' => 'ivy@example.com',
                'customer_phone' => '+44 7000 000000',
                'package_id' => $package->id,
                'adults' => 3,
            ]);

        // The repeater keys each row by a UUID rather than a plain index, and
        // Livewire's numeric inputs round-trip as floats, so compare values
        // loosely rather than the exact array shape/types.
        $this->assertEquals(
            Quote::itemsForPackage($package, 3, 0),
            array_values($component->get('data.items'))
        );

        // Regression: the inquiry-prefill path once replaced the whole form
        // state with a partial array, so fields it didn't mention (like
        // "children") never got their schema default and were saved as
        // null, violating the column's NOT NULL constraint.
        $component->call('create')->assertHasNoFormErrors();

        $quote = Quote::first();
        $this->assertNotNull($quote);
        $this->assertSame(0, $quote->children);
    }

    public function test_admin_can_download_pdf_and_email_customer(): void
    {
        $this->actingAs(User::factory()->create());
        Mail::fake();

        $quote = Quote::factory()->create();

        Livewire::test(EditQuote::class, ['record' => $quote->getRouteKey()])
            ->callAction('emailCustomer')
            ->assertHasNoActionErrors();

        Mail::assertSent(NewQuoteReceived::class, fn ($mail) => $mail->quote->is($quote));

        $this->assertSame(QuoteStatus::Sent, $quote->fresh()->status);
    }

    public function test_admin_download_pdf_action_streams_a_file_without_erroring(): void
    {
        // Regression: this action returning a plain dompdf Response (instead
        // of response()->streamDownload()) made Livewire try to JSON-encode
        // raw PDF bytes as part of its AJAX payload, which blew up with
        // "Malformed UTF-8 characters" the first time this ran in a browser.
        $this->actingAs(User::factory()->create());

        $quote = Quote::factory()->create();

        Livewire::test(EditQuote::class, ['record' => $quote->getRouteKey()])
            ->callAction('download')
            ->assertHasNoActionErrors()
            ->assertFileDownloaded($quote->pdfFilename());

        Livewire::test(\App\Filament\Resources\Quotes\Pages\ListQuotes::class)
            ->callTableAction('download', $quote)
            ->assertHasNoTableActionErrors()
            ->assertFileDownloaded($quote->pdfFilename());
    }
}
