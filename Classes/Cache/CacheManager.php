<?php

namespace Larsgowebdev\WPBattery\Cache;

use Larsgowebdev\WPBattery\Settings\ThemeSettings;
use Larsgowebdev\WPBattery\WPBattery;

class CacheManager
{
    private const CACHE_GROUP = 'wp_battery';
    private const CACHE_DURATION = DAY_IN_SECONDS;

    private static array $instances = [];

    /**
     * Store settings in cache
     *
     * @param string $namespace
     * @param ThemeSettings $settings
     */
    public static function storeSettings(string $namespace, ThemeSettings $settings): void
    {
        wp_cache_set(
            self::getSettingsCacheKey($namespace),
            $settings,
            self::CACHE_GROUP,
            self::CACHE_DURATION
        );
    }

    /**
     * Load settings from cache
     *
     * @param string $namespace
     * @return false|mixed
     */
    public static function getSettings(string $namespace): mixed
    {
        return wp_cache_get(
            self::getSettingsCacheKey($namespace),
            self::CACHE_GROUP
        );
    }

    /**
     * Saves a class instance
     *
     * @param string $namespace
     * @param WPBattery $instance
     */
    public static function storeInstance(string $namespace, WPBattery $instance): void
    {
        self::$instances[$namespace] = $instance;
    }

    /**
     * Gets a class instance
     *
     * @param string $namespace
     * @return WPBattery|null
     */
    public static function getInstance(string $namespace): null|WPBattery
    {
        return self::$instances[$namespace] ?? null;
    }

    /**
     * Checks if a class instance exists
     *
     * @param string $namespace
     * @return bool
     */
    public static function hasInstance(string $namespace): bool
    {
        return isset(self::$instances[$namespace]);
    }

    /**
     * Delete Cache for a namespace
     *
     * @param string $namespace
     */
    public static function clearCache(string $namespace): void
    {
        wp_cache_delete(self::getSettingsCacheKey($namespace), self::CACHE_GROUP);
        unset(self::$instances[$namespace]);
    }

    /**
     * Delete the whole cache
     */
    public static function clearAllCache(): void
    {
        foreach (self::$instances as $namespace => $instance) {
            wp_cache_delete(self::getSettingsCacheKey($namespace), self::CACHE_GROUP);
        }
        self::$instances = [];
    }

    /**
     * Generates a cache key
     *
     * @param string $namespace
     * @return string
     */
    private static function getSettingsCacheKey(string $namespace): string
    {
        return "wp_battery_settings_{$namespace}";
    }
}