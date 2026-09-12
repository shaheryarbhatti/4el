<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

/**
 * Setting model = one key/value row.
 *
 * You almost never use this model directly. Instead use the global helper:
 *
 *      setting('site_name')                 // read
 *      setting('site_name', 'My Store')     // read with fallback default
 *      Setting::put('site_name', 'My Store') // write
 *
 * Values are cached so reading a setting does NOT hit the DB every time.
 */
class Setting extends Model
{
    protected $fillable = ['key', 'value', 'group'];

    // Cache key that stores the whole settings map (key => value).
    public const CACHE_KEY = 'app_settings_map';

    /**
     * Return an associative array of every setting: ['key' => 'value', ...].
     * Cached forever until a setting is saved (see booted()).
     */
    public static function all(...$args)
    {
        return Cache::rememberForever(self::CACHE_KEY, function () {
            return static::query()->pluck('value', 'key')->toArray();
        });
    }

    /**
     * Read a single setting value, with an optional default fallback.
     */
    public static function get(string $key, $default = null)
    {
        return static::all()[$key] ?? $default;
    }

    /**
     * Create or update a setting, then clear the cache.
     */
    public static function put(string $key, $value, string $group = 'general'): void
    {
        static::updateOrCreate(['key' => $key], ['value' => $value, 'group' => $group]);
        Cache::forget(self::CACHE_KEY);
    }

    /**
     * Whenever a setting is saved or deleted, wipe the cache so the next
     * read reflects the change.
     */
    protected static function booted(): void
    {
        static::saved(fn () => Cache::forget(self::CACHE_KEY));
        static::deleted(fn () => Cache::forget(self::CACHE_KEY));
    }
}
