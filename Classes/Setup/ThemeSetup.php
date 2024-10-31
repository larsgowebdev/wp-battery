<?php

namespace Larsgowebdev\WPBattery\Setup;

use Larsgowebdev\ViteAssetCollectorWp\Exception\ViteException;
use Larsgowebdev\ViteAssetCollectorWp\ViteAssetCollector;
use Larsgowebdev\WPBattery\Utility\PathScannerUtility;
use Larsgowebdev\WPBattery\Utility\PathUtility;
use Timber\Timber;

class ThemeSetup
{
    public static function addThemeSupport(array $themeSupport): void
    {
        foreach ($themeSupport as $feature) {
            add_theme_support($feature);
        }
    }

    public static function registerBlockJsonFiles(): void
    {
        add_action('init', function () {
            $blockJsonFiles = PathScannerUtility::scanForCompatibleFiles(
                PathUtility::getBlocksDirectory(),
                'block.json'
            );

            foreach ($blockJsonFiles as $blockJsonFile) {
                register_block_type($blockJsonFile);
            }
        });
    }

    public static function registerMenus(): void
    {
        add_action('init', function () {

            $menuFiles = PathScannerUtility::scanForCompatibleFiles(
                PathUtility::getMenuDirectory(),
                '',
                'php'
            );

            $menus = [];

            foreach ($menuFiles as $menuFile) {
                $menuFileContent = include_once($menuFile);
                if (is_array($menuFileContent)) {
                    $menus = array_merge_recursive($menus, $menuFileContent);
                }
            }

            foreach ($menus as $menuName => $menu) {
                $menuExists = wp_get_nav_menu_object($menuName);
                if (!$menuExists) {
                    $menuId = wp_create_nav_menu($menuName);
                    if (isset($menu['items']) && $menu['items']) {
                        foreach ($menu['items'] as $item) {
                            wp_update_nav_menu_item($menuId, 0, $item);
                        }
                    }
                }
            }

        });
    }

    public static function registerOptionsPages(): void
    {
        add_action('acf/init', function () {

            $options = [];

            $optionFiles = PathScannerUtility::scanForCompatibleFiles(
                PathUtility::getOptionsDirectory(),
                '',
                'php'
            );

            foreach ($optionFiles as $optionFile) {
                $optionFileContent = include_once($optionFile);
                if (is_array($optionFileContent)) {
                    $options = array_merge_recursive($options, $optionFileContent);
                }
            }

            foreach ($options as $key => $option) {
                acf_add_options_page($option);
            }

        });
    }

    public static function enableACFSync(): void
    {
        add_filter('acf/settings/save_json', function ($data) {
            return PathUtility::getACFSyncDirectory();
        });

        add_filter('acf/settings/load_json', function ($paths) {
            // remove original path (optional)
            unset($paths[0]);
            // append path
            $paths[] = PathUtility::getACFSyncDirectory();
            // return
            return $paths;
        });
    }

    public static function enableSVGUploads(): void
    {
        /* allow SVG Upload in the backend and fix appearance */
        add_filter('upload_mimes', function ($file_types) {
            $new_filetypes = array();
            $new_filetypes['svg'] = 'image/svg+xml';
            return array_merge($file_types, $new_filetypes);
        }, 10, 4);
    }

    public static function disallowAllNonACFBlocks(): void
    {
        add_filter('allowed_block_types_all',
            function ($allowed_block_types, $block_editor_context) {
                return array_keys(acf_get_block_types());
            },
            10, 2);
    }

    public static function disableComments(): void
    {
        add_action('admin_init', function () {
            // Redirect any user trying to access comments page
            global $pagenow;

            if ($pagenow === 'edit-comments.php') {
                wp_safe_redirect(admin_url());
                exit;
            }

            // Remove comments metabox from dashboard
            remove_meta_box('dashboard_recent_comments', 'dashboard', 'normal');

            // Disable support for comments and trackbacks in post types
            foreach (get_post_types() as $post_type) {
                if (post_type_supports($post_type, 'comments')) {
                    remove_post_type_support($post_type, 'comments');
                    remove_post_type_support($post_type, 'trackbacks');
                }
            }
        });

        // Close comments on the front-end
        add_filter('comments_open', '__return_false', 20, 2);
        add_filter('pings_open', '__return_false', 20, 2);

        // Hide existing comments
        add_filter('comments_array', '__return_empty_array', 10, 2);

        // Remove comments page in menu
        add_action('admin_menu', function () {
            remove_menu_page('edit-comments.php');
        });

        // Remove comments links from admin bar
        add_action('init', function () {
            if (is_admin_bar_showing()) {
                remove_action('admin_bar_menu', 'wp_admin_bar_comments_menu', 60);
            }
        });
    }

    public static function enableViteBuild($buildDir = 'build', $entryPoint = './vite.entry.js'): void
    {

        try {
            $viteAssetCollector = new ViteAssetCollector(
                manifest: '/' . $buildDir . '/.vite/manifest.json',
                entry: $entryPoint,
            );
            $viteAssetCollector->injectViteAssets();
        } catch (ViteException $e) {
            // The error handler will automatically handle this appropriately based on the environment
        }
    }

    public static function includeFrontendCSS(array $includes): void
    {
        add_action('wp_enqueue_scripts', function () use ($includes) {
            foreach ($includes as $handle => $include) {

                if (!isset($include['path'])) {
                    continue;
                }

                wp_enqueue_style(
                    handle: $handle,
                    src: $include['path'],
                    deps: $include['dependencies'] ?? [],
                    ver: $include['version'] ?? false,
                    media: $include['media'] ?? 'all'
                );
            }
        });
    }

    public static function includeFrontendJS(array $includes): void
    {
        add_action('wp_enqueue_scripts', function () use ($includes) {
            foreach ($includes as $handle => $include) {

                if (!isset($include['path'])) {
                    continue;
                }

                wp_enqueue_script(
                    handle: $handle,
                    src: $include['path'],
                    deps: $include['dependencies'] ?? [],
                    ver: $include['version'] ?? false,
                    args: $include['args'] ?? []
                );
            }
        });
    }

    public static function includeAdminCSS(array $includes): void
    {
        add_action('admin_enqueue_scripts', function () use ($includes) {
            foreach ($includes as $handle => $include) {
                if (!isset($include['path'])) {
                    continue;
                }

                wp_enqueue_style(
                    handle: $handle,
                    src: $include['path'],
                    deps: $include['dependencies'] ?? [],
                    ver: $include['version'] ?? false,
                    media: $include['media'] ?? 'all'
                );
            }
        });
    }

    public static function includeAdminJS(array $includes): void
    {
        add_action('admin_enqueue_scripts', function () use ($includes) {
            foreach ($includes as $handle => $include) {

                if (!isset($include['path'])) {
                    continue;
                }

                wp_enqueue_script(
                    handle: $handle,
                    src: $include['path'],
                    deps: $include['dependencies'] ?? [],
                    ver: $include['version'] ?? false,
                    args: $include['args'] ?? []
                );
            }
        });
    }

    public static function renderCF7ShortcodeTemplate(array $formTemplates): void
    {
        add_filter( 'wpcf7_contact_form_properties', function( $properties, ?\WPCF7_ContactForm $contactForm ) use ( $formTemplates ) {
            $cf7Hash = $contactForm->hash();

            if (!isset($cf7Hash)  || !isset($formTemplates[$cf7Hash])) {
                // hash not found
                return $properties;
            }

            $fullTemplatePath = PathUtility::getCf7FormTemplatesDirectory() . '/' . $formTemplates[$cf7Hash];

            if ( file_exists($fullTemplatePath)) {
                $output = Timber::compile($fullTemplatePath);

                // Override the 'form' property with the content of the file
                $properties['form'] = $output;
            }

            return $properties;
        }, 10, 2 );
    }
}