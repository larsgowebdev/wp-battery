<?php

namespace Larsgowebdev\WPBattery\Renderer;

use Exception;
use Larsgowebdev\WPBattery\Utility\FileScannerUtility;
use Larsgowebdev\WPBattery\Utility\PathUtility;
use Timber\Timber;

class PageRenderer
{
    /**
     * @param string $pageTitle
     * @return void
     * @throws Exception
     */
    public static function renderPage(string $pageTitle): void
    {
        $context = Timber::context();
        $context['post'] = Timber::get_post();
        $context['options'] = self::getOptionsForTimber();
        $context['menus'] = self::getMenusForTimber();

        add_filter('timber/locations', function ($paths) use ($pageTitle) {
            $paths[] = [
                PathUtility::getDirectoryOfPage($pageTitle),
                PathUtility::getTemplatePartsDirectory(),
            ];

            return $paths;
        });

        // Include and process the page renderer file
        $pageRendererPath = PathUtility::getPageRendererPath($pageTitle);

        if (is_file($pageRendererPath)) {
            include_once($pageRendererPath);
            $renderFunctions = FileScannerUtility::getValidRenderFunctionsInFile($pageRendererPath);

            // Add filters for each render function found
            foreach ($renderFunctions as $i => $renderFunction) {
                // Add a filter to modify the content of $context with that function
                // Use priority $i to maintain order of functions
                add_filter($pageTitle . '-page-render-filter', $renderFunction, $i, 1);
            }
        }

        // Apply all filters defined in page renderer PHP file to modify $context
        $context = apply_filters($pageTitle . '-page-render-filter', $context);

        // Tell timber to render the page template file
        Timber::render(PathUtility::getPageTemplatePath($pageTitle), $context);
    }

    public static function getMenusForTimber(): array
    {
        $registeredMenus = wp_get_nav_menus();
        $timberMenus = [];
        foreach($registeredMenus as $item) {
            $identifier = $item->name;
            $timberMenus[$identifier] = Timber::get_menu( $item->name );
        }
        return $timberMenus;
    }

    public static function getOptionsForTimber(): array
    {
        return get_fields('options') ?: [];
    }
}