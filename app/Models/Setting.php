<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    protected $primaryKey = 'key';

    protected $keyType = 'string';

    public $incrementing = false;

    protected $guarded = [];

    /** Theme choices offered in the admin, keyed by what gets stored. */
    public const THEMES = [
        'dark' => 'Dark',
        'light' => 'Light',
        'system' => "Follow each visitor's device",
    ];

    public const DEFAULT_THEME = 'light';

    public static function get(string $key, mixed $default = null): mixed
    {
        // Read on every public page view, so it is cached until a write clears it.
        return Cache::rememberForever(
            "setting:$key",
            fn () => static::query()->whereKey($key)->value('value')
        ) ?? $default;
    }

    public static function set(string $key, mixed $value): void
    {
        static::updateOrCreate(['key' => $key], ['value' => $value]);

        Cache::forget("setting:$key");
    }

    /**
     * The theme the public site should render in. 'system' is resolved in the
     * browser, so it is passed through untouched.
     */
    public static function siteTheme(): string
    {
        $theme = static::get('site_theme', static::DEFAULT_THEME);

        return array_key_exists($theme, static::THEMES) ? $theme : static::DEFAULT_THEME;
    }

    /**
     * `value` is a plain text column, so arrays are kept as a JSON string and
     * decoded here rather than relying on an Eloquent cast (which would also
     * have to apply to every other, non-JSON setting sharing this table).
     */
    public static function setJson(string $key, array $value): void
    {
        static::set($key, json_encode($value));
    }

    protected static function getJson(string $key, array $default): array
    {
        $raw = static::get($key);

        if (! $raw) {
            return $default;
        }

        $decoded = json_decode($raw, true);

        return is_array($decoded) ? $decoded : $default;
    }

    /** @return array<int, string> */
    public static function quoteOfficeEmails(): array
    {
        return static::getJson('quote_office_emails', config('quotes.office_emails'));
    }

    public static function quoteEmailsEnabled(): bool
    {
        $value = static::get('quote_emails_enabled');

        return $value === null ? config('quotes.enquiry_emails_enabled') : $value === '1';
    }

    /** @return array<int, array{bank: string, account_name: string, account_number: string, branch: ?string, swift: ?string}> */
    public static function quoteBankAccounts(): array
    {
        return static::getJson('quote_bank_accounts', config('quotes.bank_accounts'));
    }

    public static function quotePaymentInstructions(): string
    {
        return (string) static::get('quote_payment_instructions', config('quotes.payment_instructions'));
    }

    public static function quoteValidityDays(): int
    {
        return (int) static::get('quote_validity_days', config('quotes.validity_days'));
    }

    public static function childPricePercent(): int
    {
        return (int) static::get('quote_child_price_percent', config('quotes.child_price_percent'));
    }

    public static function quoteTerms(): string
    {
        return (string) static::get('quote_terms', config('quotes.terms'));
    }
}
