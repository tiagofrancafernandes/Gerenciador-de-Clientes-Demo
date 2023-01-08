<?php

namespace App\Helpers;

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Collection;

class ConfigHandler
{
    protected static $allowedKeys = [
        'applicationLogoDark',
        'applicationLogo',
        'loginLogo',
        'loginLogoDark',
        'forced_2fa',
        'is_forced_2fa',
        'app.name',
        'ips',
    ];

    protected static $casts = [
        'applicationLogoDark' => 'string',
        'applicationLogo' => 'string',
        'loginLogo' => 'string',
        'loginLogoDark' => 'string',
        'forced_2fa' => 'string',
        'is_forced_2fa' => 'string',
        'app.name' => 'string',
        'ips' => 'collect',
    ];

    /**
     * function clearCache
     *
     * @param null|string $cacheKey
     *
     * @return void
     */
    public static function clearCache(null|string $cacheKey = \null): void
    {
        Cache::forget(trim((string) $cacheKey ?: 'Setting::all'));
    }

    /**
     * function all
     *
     * @param bool $updateCache = false
     *
     * @return null|Collection
     */
    public static function all(bool $updateCache = false): null|Collection
    {
        $cacheKey = 'Setting::all';

        if ($updateCache) {
            static::clearCache($cacheKey);
        }

        return Cache::remember(
            $cacheKey,
            (60 * 60) /*secs*/,
            fn () => Setting::select('key', 'value')
                ->get()
                ->each(fn ($item) => $item->fetched_at = \now())
                ->collect()
        );
    }

    /**
     * function get
     *
     * @param string $key
     * @param mixed $default
     *
     * @return mixed
     */
    public static function get(string $key, mixed $default = null): mixed
    {
        if (!trim($key) || !\in_array($key, static::$allowedKeys, true)) {
            return $default ?? \null;
        }

        return static::firstByKey($key)->value ?? $default ?? \null;
    }

    /**
     * first function
     *
     * @param ?callable $callback
     * @param $default = null
     *
     * @return null|object
     */
    public static function first(?callable $callback = null, $default = null): null|object
    {
        return static::all()->first($callback, $default);
    }

    /**
     * firstByKey function
     *
     * @param string $key
     * @param mixed $default = null
     *
     * @return null|object
     */
    public static function firstByKey(string $key, mixed $default = null): null|object
    {
        return static::first(fn ($item) => $item->key === $key, $default);
    }

    /**
     * function getDictionary
     *
     * @param string $by
     *
     * @return null|Collection
     */
    public static function getDictionary(string $by = 'key'): null|Collection
    {
        if (!trim($by) || !\in_array($by, ['key', 'value'], true)) {
            return \null;
        }

        return static::all()->keyBy($by ?: 'key');
    }

    /**
     * function castIt
     *
     * @param string $key
     * @param mixed $data
     *
     * @return mixed
     */
    public function castIt(string $key, mixed $data): mixed
    {
        if (!\in_array($key, array_keys(static::$casts), true)) {
            return $data ?? \null;
        }

        $castTo = static::$casts[$key] ?? \null;

        return $castTo ? CastMaker::castIt(
            $data,
            $castTo,
            fn ($item) => (bool) $item
        ) : $data;
    }
}
