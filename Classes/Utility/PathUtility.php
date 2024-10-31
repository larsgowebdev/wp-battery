<?php

namespace Larsgowebdev\WPBattery\Utility;
class PathUtility
{
    public static string $wpbThemeSegment = 'wpb';
    public static string $directorySeparator = '/';

    public static string $blocksDir = 'blocks';
    public static string $menusDir = 'menus';
    public static string $optionsDir = 'options';
    public static string $pagesDir = 'pages';
    public static string $cf7templatesDir = 'cf7-templates';
    public static string $templatePartsDir = 'template-parts';

    public static string $acfSyncDir = 'acf-sync';

    /**
     * returns the full path to the theme directory
     *  ... NO trailing slash
     *
     * @return string
     */
    public static function getThemeDirectory(): string
    {
        return get_stylesheet_directory();
    }

    /**
     * returns the full path to the menus directory inside the wpb theme files
     * ... NO trailing slash
     *
     * @return string
     */
    public static function getMenuDirectory(): string
    {
        return self::getThemeDirectory() .
            self::$directorySeparator .
            self::$wpbThemeSegment .
            self::$directorySeparator .
            self::$menusDir;
    }

    /**
     * returns the full path to the options directory inside the wpb theme files
     * ... NO trailing slash
     *
     * @return string
     */
    public static function getOptionsDirectory(): string
    {
        return self::getThemeDirectory() .
            self::$directorySeparator .
            self::$wpbThemeSegment .
            self::$directorySeparator .
            self::$optionsDir;
    }

    /**
     * returns the full path to the acf sync directory inside the wpb theme files
     * ... NO trailing slash
     *
     * @return string
     */
    public static function getACFSyncDirectory(): string
    {
        return self::getThemeDirectory() .
            self::$directorySeparator .
            self::$wpbThemeSegment .
            self::$directorySeparator .
            self::$acfSyncDir;
    }

    /**
     * returns the full path to the blocks directory inside the wpb theme files
     * ... NO trailing slash!
     *
     * @return string
     */
    public static function getBlocksDirectory(): string
    {
        return self::getThemeDirectory() .
                self::$directorySeparator .
                self::$wpbThemeSegment .
                self::$directorySeparator .
                self::$blocksDir;
    }

    /**
     * returns the full path to the pages directory inside the wpb theme files
     * ... NO trailing slash!
     *
     * @return string
     */
    public static function getPagesDirectory(): string
    {
        return self::getThemeDirectory() .
                self::$directorySeparator .
                self::$wpbThemeSegment .
                self::$directorySeparator .
                self::$pagesDir;
    }

    /**
     * returns the full path to the pages directory inside the wpb theme files
     * ... NO trailing slash!
     *
     * @return string
     */
    public static function getTemplatePartsDirectory(): string
    {
        return self::getThemeDirectory() .
                self::$directorySeparator .
                self::$wpbThemeSegment .
                self::$directorySeparator .
                self::$templatePartsDir;
    }

    /**
     * returns the full path to the cf7 templates directory inside the wpb theme files
     *  ... NO trailing slash!
     *
     * @return string
     */
    public static function getCf7FormTemplatesDirectory(): string
    {
        return self::getThemeDirectory() .
            self::$directorySeparator .
            self::$wpbThemeSegment .
            self::$directorySeparator .
            self::$cf7templatesDir;
    }

    /**
     * returns the full path to a specific block directory inside the wpb theme files
     * ... NO trailing slash
     *
     * @param string $blockName
     * @return string
     */
    public static function getDirectoryOfBlock(string $blockName): string
    {
        return self::getBlocksDirectory() .
            self::$directorySeparator .
            $blockName;
    }

    /**
     * gets the full path of a block's block.json file
     *
     * @param string $blockName
     * @return string
     */
    public static function getBlockJsonPath(string $blockName): string
    {
        return self::getDirectoryOfBlock($blockName) .
            self::$directorySeparator .
            'block.json';
    }

    /**
     * gets the full path of a block's renderer php file
     *
     * @param string $blockName
     * @return string
     */
    public static function getBlockRendererPath(string $blockName): string
    {
        return self::getDirectoryOfBlock($blockName) .
            self::$directorySeparator .
            $blockName . '-block-renderer.php';
    }

    /**
     * gets the full path of a pages's renderer php file
     *
     * @param string $pageName
     * @return string
     */
    public static function getPageRendererPath(string $pageName): string
    {
        return self::getDirectoryOfPage($pageName) .
            self::$directorySeparator .
            $pageName . '-page-renderer.php';
    }

    /**
     * gets the full path of a block's template file
     *
     * @param string $blockName
     * @return string
     */
    public static function getBlockTemplatePath(string $blockName): string
    {
        return self::getDirectoryOfBlock($blockName) .
            self::$directorySeparator .
            $blockName . '-block-template.twig';
    }

    /**
     * returns the full path to a specific page directory inside the wpb theme files
     * ... NO trailing slash
     *
     * @param string $pageTitle
     * @return string
     */
    public static function getDirectoryOfPage(
        string $pageTitle
    ): string
    {
        return self::getPagesDirectory() .
            self::$directorySeparator .
            $pageTitle;
    }

    /**
     * gets the full path of a page's template file
     *
     * @param string $pageTitle
     * @return string
     */
    public static function getPageTemplatePath(string $pageTitle): string
    {
        return self::getDirectoryOfPage($pageTitle) .
            self::$directorySeparator .
            $pageTitle . '-page-template.twig';
    }

    /**
     * gets the temporary writable temp directory
     *
     * @return string
     */
    public static function getTemporaryDirectory(): string
    {
        return sys_get_temp_dir();
    }
}