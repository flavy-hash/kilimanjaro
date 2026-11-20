<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Verifies a Cloudflare Turnstile token with Cloudflare's siteverify API.
 *
 * Does nothing until TURNSTILE_SECRET_KEY is set, so local development and
 * tests work without keys. If Cloudflare itself can't be reached the request
 * is let through and logged: losing a real customer's booking to a Cloudflare
 * outage costs more than the odd bot slipping past during one.
 */
class Turnstile implements ValidationRule
{
    public const FIELD = 'cf-turnstile-response';

    private const VERIFY_URL = 'https://challenges.cloudflare.com/turnstile/v0/siteverify';

    /** Run even when the token is missing — a bot simply leaving it out must still fail. */
    public bool $implicit = true;

    public function __construct(private ?string $ip = null) {}

    public static function enabled(): bool
    {
        return filled(config('services.turnstile.secret_key'));
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! static::enabled()) {
            return;
        }

        if (! is_string($value) || $value === '') {
            $fail('Please complete the verification check.');

            return;
        }

        try {
            $result = Http::asForm()->timeout(8)->post(self::VERIFY_URL, array_filter([
                'secret' => config('services.turnstile.secret_key'),
                'response' => $value,
                'remoteip' => $this->ip,
            ]))->json();
        } catch (\Throwable $e) {
            Log::warning('Turnstile verification unavailable; allowing request', ['error' => $e->getMessage()]);

            return;
        }

        if (! ($result['success'] ?? false)) {
            $fail('Verification failed. Please try the check again.');
        }
    }
}
