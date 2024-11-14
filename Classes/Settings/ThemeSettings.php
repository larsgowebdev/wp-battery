<?php

namespace Larsgowebdev\WPBattery\Settings;

class ThemeSettings
{
    protected bool $registerBlocks = true;
    protected bool $registerMenus = true;
    protected bool $registerOptions = true;
    protected bool $registerCustomPostTypes = true;
    protected bool $registerTaxonomies = true;
    protected bool $enableACFSync = true;
    protected bool $enableViteAssets = true;
    protected bool $allowSVGUploads = true;
    protected bool $disallowNonACFBlocks = true;
    protected bool $disableComments = true;
    protected string $viteBuildDir = 'build';
    protected string $viteEntryPoint = './vite.entry.js';
    protected array $themeSupport = [];
    protected array $includeFrontendCSS = [];
    protected array $includeFrontendJS = [];
    protected array $includeAdminCSS = [];
    protected array $includeAdminJS = [];
    protected array $contactForm7Templates = [];
    protected array $addMetaTags = [];

    public function __construct(array $settings)
    {
        $this->registerBlocks
            = (bool) ($settings['registerBlocks'] ?? $this->registerBlocks);
        $this->registerMenus
            = (bool) ($settings['registerMenus'] ?? $this->registerMenus);
        $this->registerOptions
            = (bool) ($settings['registerOptions'] ?? $this->registerOptions);
        $this->registerCustomPostTypes
            = (bool) ($settings['registerCustomPostTypes'] ?? $this->registerCustomPostTypes);
        $this->registerTaxonomies
            = (bool) ($settings['registerTaxonomies'] ?? $this->registerTaxonomies);
        $this->enableACFSync
            = (bool) ($settings['enableACFSync'] ?? $this->enableACFSync);
        $this->themeSupport
            = (array) ($settings['themeSupport'] ?? $this->themeSupport);
        $this->disallowNonACFBlocks
            = (bool) ($settings['disallowNonACFBlocks'] ?? $this->disallowNonACFBlocks);
        $this->disableComments
            = (bool) ($settings['disableComments'] ?? $this->disableComments);
        $this->allowSVGUploads
            = (bool) ($settings['allowSVGUploads'] ?? $this->allowSVGUploads);
        
        // Vite Configurations
        $this->enableViteAssets
            = (bool) ($settings['enableViteAssets'] ?? $this->enableViteAssets);
        $this->viteBuildDir
            = (string) ($settings['viteBuildDir'] ?? $this->viteBuildDir);
        $this->viteEntryPoint
            = (string) ($settings['viteEntryPoint'] ?? $this->viteEntryPoint);
        
        // Enqueue additional assets
        $this->includeFrontendCSS
            = (array) ($settings['includeFrontendCSS'] ?? $this->includeFrontendCSS);
        $this->includeFrontendJS
            = (array) ($settings['includeFrontendJS'] ?? $this->includeFrontendJS);
        $this->includeAdminCSS
            = (array) ($settings['includeAdminCSS'] ?? $this->includeAdminCSS);
        $this->includeAdminJS
            = (array) ($settings['includeAdminJS'] ?? $this->includeAdminJS);
        $this->contactForm7Templates
            = (array) ($settings['contactForm7Templates'] ?? $this->contactForm7Templates);
        $this->addMetaTags
            = (array) ($settings['addMetaTags'] ?? $this->addMetaTags);
    }

    public function getRegisterBlocks(): bool
    {
        return $this->registerBlocks;
    }

    public function setRegisterBlocks(bool $registerBlocks): void
    {
        $this->registerBlocks = $registerBlocks;
    }

    public function getRegisterMenus(): bool
    {
        return $this->registerMenus;
    }

    public function setRegisterMenus(bool $registerMenus): void
    {
        $this->registerMenus = $registerMenus;
    }

    public function getRegisterOptions(): bool
    {
        return $this->registerOptions;
    }

    public function setRegisterOptions(bool $registerOptions): void
    {
        $this->registerOptions = $registerOptions;
    }

    public function getRegisterCustomPostTypes(): bool
    {
        return $this->registerCustomPostTypes;
    }

    public function setRegisterCustomPostTypes(bool $registerCustomPostTypes): void
    {
        $this->registerCustomPostTypes = $registerCustomPostTypes;
    }

    public function getRegisterTaxonomies(): bool
    {
        return $this->registerTaxonomies;
    }

    public function setRegisterTaxonomies(bool $registerTaxonomies): void
    {
        $this->registerTaxonomies = $registerTaxonomies;
    }

    public function getEnableACFSync(): bool
    {
        return $this->enableACFSync;
    }

    public function setEnableACFSync(bool $enableACFSync): void
    {
        $this->enableACFSync = $enableACFSync;
    }

    public function getEnableViteAssets(): bool
    {
        return $this->enableViteAssets;
    }

    public function setEnableViteAssets(bool $enableViteAssets): void
    {
        $this->enableViteAssets = $enableViteAssets;
    }

    public function getAllowSVGUploads(): bool
    {
        return $this->allowSVGUploads;
    }

    public function setAllowSVGUploads(bool $allowSVGUploads): void
    {
        $this->allowSVGUploads = $allowSVGUploads;
    }

    public function getDisallowNonACFBlocks(): bool
    {
        return $this->disallowNonACFBlocks;
    }

    public function setDisallowNonACFBlocks(bool $disallowNonACFBlocks): void
    {
        $this->disallowNonACFBlocks = $disallowNonACFBlocks;
    }

    public function getDisableComments(): bool
    {
        return $this->disableComments;
    }

    public function setDisableComments(bool $disableComments): void
    {
        $this->disableComments = $disableComments;
    }

    public function getViteBuildDir(): string
    {
        return $this->viteBuildDir;
    }

    public function setViteBuildDir(string $viteBuildDir): void
    {
        $this->viteBuildDir = $viteBuildDir;
    }

    public function getViteEntryPoint(): string
    {
        return $this->viteEntryPoint;
    }

    public function setViteEntryPoint(string $viteEntryPoint): void
    {
        $this->viteEntryPoint = $viteEntryPoint;
    }

    public function getThemeSupport(): array
    {
        return $this->themeSupport;
    }

    public function setThemeSupport(array $themeSupport): void
    {
        $this->themeSupport = $themeSupport;
    }

    public function getIncludeFrontendCSS(): array
    {
        return $this->includeFrontendCSS;
    }

    public function setIncludeFrontendCSS(array $includeFrontendCSS): void
    {
        $this->includeFrontendCSS = $includeFrontendCSS;
    }

    public function getIncludeFrontendJS(): array
    {
        return $this->includeFrontendJS;
    }

    public function setIncludeFrontendJS(array $includeFrontendJS): void
    {
        $this->includeFrontendJS = $includeFrontendJS;
    }

    public function getIncludeAdminCSS(): array
    {
        return $this->includeAdminCSS;
    }

    public function setIncludeAdminCSS(array $includeAdminCSS): void
    {
        $this->includeAdminCSS = $includeAdminCSS;
    }

    public function getIncludeAdminJS(): array
    {
        return $this->includeAdminJS;
    }

    public function setIncludeAdminJS(array $includeAdminJS): void
    {
        $this->includeAdminJS = $includeAdminJS;
    }

    public function getContactForm7Templates(): array
    {
        return $this->contactForm7Templates;
    }

    public function setContactForm7Templates(array $contactForm7Templates): void
    {
        $this->contactForm7Templates = $contactForm7Templates;
    }

    public function getAddMetaTags(): array
    {
        return $this->addMetaTags;
    }

    public function setAddMetaTags(array $addMetaTags): void
    {
        $this->addMetaTags = $addMetaTags;
    }
}