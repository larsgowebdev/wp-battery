<?php

namespace Larsgowebdev\WPBattery;

use Larsgowebdev\WPBattery\Cache\CacheManager;
use Larsgowebdev\WPBattery\Settings\ThemeSettings;
use Larsgowebdev\WPBattery\Setup\ThemeSetup;


class WPBattery
{
    protected string $themeNamespace = ''; // Will override in constructor
    protected ThemeSettings $themeSettings;
    protected bool $enableCache = true;

    public function __construct(
        string $themeNamespace,
        array $settings = [],
        bool $enableCache = true
    ) {
        $this->themeNamespace = $themeNamespace;
        $this->enableCache = $enableCache;

        // Try to load existing singleton, if caching is enabled
        if ($this->enableCache && CacheManager::hasInstance($themeNamespace)) {
            return CacheManager::getInstance($themeNamespace);
        }

        // Try to load cached settings
        if ($this->enableCache) {
            $cached_settings = CacheManager::getSettings($themeNamespace);

            if ($cached_settings !== false) {
                $this->themeSettings = $cached_settings;
            } else {
                $this->themeSettings = new ThemeSettings($settings);
                CacheManager::storeSettings($themeNamespace, $this->themeSettings);
            }
        } else {
            $this->themeSettings = new ThemeSettings($settings);
        }

        $this->processSettings($this->themeSettings);

        if ($this->enableCache) {
            CacheManager::storeInstance($themeNamespace, $this);
        }
    }

    /**
     * Gets a singleton instance
     *
     * @param string $themeNamespace
     * @param array $settings
     * @param bool $enableCache
     * @return WPBattery
     */
    public static function getInstance(
        string $themeNamespace,
        array $settings = [],
        bool $enableCache = true
    ): self {
        if ($enableCache && CacheManager::hasInstance($themeNamespace)) {
            return CacheManager::getInstance($themeNamespace);
        }
        return new self($themeNamespace, $settings, $enableCache);
    }

    /**
     * Clear cache for a given namespace
     *
     * @param string $themeNamespace
     */
    public static function clearCache(string $themeNamespace): void
    {
        CacheManager::clearCache($themeNamespace);
    }

    /**
     * Delete whole WP-Battery Cache
     */
    public static function clearAllCache(): void
    {
        CacheManager::clearAllCache();
    }

    protected function processSettings(ThemeSettings $themeSettings): void
    {
        if (!empty($themeSettings->getThemeSupport())) {
            ThemeSetup::addThemeSupport($themeSettings->getThemeSupport());
        }
        if ($themeSettings->getRegisterBlocks() === true) {
            ThemeSetup::registerBlockJsonFiles();
        }
        if ($themeSettings->getRegisterMenus() === true) {
            ThemeSetup::registerMenus();
        }
        if ($themeSettings->getRegisterOptions() === true) {
            ThemeSetup::registerOptionsPages();
        }
        if ($themeSettings->getRegisterCustomPostTypes() === true) {
            ThemeSetup::registerCustomPostTypes();
        }
        if ($themeSettings->getRegisterTaxonomies() === true) {
            ThemeSetup::registerTaxonomies();
        }
        if ($themeSettings->getEnableACFSync() === true) {
            ThemeSetup::enableACFSync();
        }
        if ($themeSettings->getAllowSVGUploads() === true) {
            ThemeSetup::enableSVGUploads();
        }
        if ($themeSettings->getDisallowNonACFBlocks() === true) {
            ThemeSetup::disallowAllNonACFBlocks();
        }
        if ($themeSettings->getDisableComments() === true) {
            ThemeSetup::disableComments();
        }
        if (!empty($themeSettings->getAddMetaTags())) {
            ThemeSetup::addMetaTags($themeSettings->getAddMetaTags());
        }

        if ($themeSettings->getEnableViteAssets() === true) {
            ThemeSetup::enableViteBuild(
                $themeSettings->getViteBuildDir(),
                $themeSettings->getViteEntryPoint(),
            );
        }

        if (!empty($themeSettings->getIncludeFrontendCSS())) {
            ThemeSetup::includeFrontendCSS(
                $themeSettings->getIncludeFrontendCSS(),
            );
        }
        if (!empty($themeSettings->getIncludeFrontendJS())) {
            ThemeSetup::includeFrontendJS(
                $themeSettings->getIncludeFrontendJS(),
            );
        }
        if (!empty($themeSettings->getIncludeAdminCSS())) {
            ThemeSetup::includeAdminCSS(
                $themeSettings->getIncludeAdminCSS(),
            );
        }
        if (!empty($themeSettings->getIncludeAdminJS())) {
            ThemeSetup::includeAdminJS(
                $themeSettings->getIncludeAdminJS(),
            );
        }
        if (!empty($themeSettings->getContactForm7Templates())) {
            ThemeSetup::renderCF7ShortcodeTemplate(
                $themeSettings->getContactForm7Templates(),
            );
        }

    }

}