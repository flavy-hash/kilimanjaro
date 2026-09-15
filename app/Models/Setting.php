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

    public const DEFAULT_THEME = 'dark';

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
}
