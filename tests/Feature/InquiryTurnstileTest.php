<?php

namespace Tests\Feature;

use App\Models\Inquiry;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class InquiryTurnstileTest extends TestCase
{
    use RefreshDatabase;

    private const VERIFY_URL = 'https://challenges.cloudflare.com/turnstile/v0/siteverify';

    protected function setUp(): void
    {
        parent::setUp();
        Mail::fake();
    }

    private function payload(array $overrides = []): array
    {
        return array_merge([
            'name' => 'Jane Traveler',
            'email' => 'jane@example.com',
            'start_date' => now()->addMonth()->toDateString(),
            'group_size' => 2,
        ], $overrides);
    }

    private function enableTurnstile(): void
    {
        config([
            'services.turnstile.site_key' => 'site-key',
            'services.turnstile.secret_key' => 'secret-key',
        ]);
    }

    public function test_booking_works_without_turnstile_keys(): void
    {
        Http::fake();

        $this->postJson(route('inquiries.store'), $this->payload())->assertOk();

        $this->assertSame(1, Inquiry::count());
        Http::assertNothingSent();
    }

    public function test_booking_with_a_valid_token_is_saved(): void
    {
        $this->enableTurnstile();
        Http::fake([self::VERIFY_URL => Http::response(['success' => true])]);

        $this->postJson(route('inquiries.store'), $this->payload(['cf-turnstile-response' => 'good-token']))
            ->assertOk()
            ->assertJsonStructure(['reference']);

        $this->assertSame(1, Inquiry::count());
        Http::assertSent(fn (Request $request) => $request->url() === self::VERIFY_URL
            && $request['secret'] === 'secret-key'
            && $request['response'] === 'good-token');
    }

    public function test_booking_without_a_token_is_rejected(): void
    {
        $this->enableTurnstile();
        Http::fake();

        $this->postJson(route('inquiries.store'), $this->payload())
            ->assertUnprocessable()
            ->assertJsonValidationErrors('cf-turnstile-response');

        $this->assertSame(0, Inquiry::count());
        Http::assertNothingSent();
    }

    public function test_booking_with_a_rejected_token_is_not_saved(): void
    {
        $this->enableTurnstile();
        Http::fake([self::VERIFY_URL => Http::response(['success' => false, 'error-codes' => ['invalid-input-response']])]);

        $this->postJson(route('inquiries.store'), $this->payload(['cf-turnstile-response' => 'bot-token']))
            ->assertUnprocessable()
            ->assertJsonValidationErrors('cf-turnstile-response');

        $this->assertSame(0, Inquiry::count());
    }

    public function test_booking_goes_through_when_cloudflare_is_unreachable(): void
    {
        $this->enableTurnstile();
        Http::fake([self::VERIFY_URL => fn () => throw new \Illuminate\Http\Client\ConnectionException('timeout')]);

        $this->postJson(route('inquiries.store'), $this->payload(['cf-turnstile-response' => 'any-token']))
            ->assertOk();

        $this->assertSame(1, Inquiry::count());
    }

    public function test_booking_form_shows_the_widget_only_when_configured(): void
    {
        $this->get(route('home'))->assertDontSee('id="bookingTurnstile"', false);

        $this->enableTurnstile();

        $this->get(route('home'))->assertSee('id="bookingTurnstile"', false);
    }
}
