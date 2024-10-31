<?php

namespace Larsgowebdev\WPBattery\Renderer;

use Exception;
use Larsgowebdev\WPBattery\Utility\FileScannerUtility;
use Larsgowebdev\WPBattery\Utility\PathUtility;
use Timber\Timber;
use WP_Block;

class BlockRenderer
{
    /**
     * Render callback to prepare and display a registered block using Timber.
     *
     * @param array $attributes The block attributes.
     * @param string $content The block content.
     * @param bool $isPreview Whether the block is being rendered for editing preview.
     * @param int $post_id The current post being edited or viewed.
     * @param WP_Block|null $wp_block The block instance (since WP 5.5).
     * @return   void
     * @throws Exception
     */
    public static function renderACFBlock(
        array $attributes, string $content = '', bool $isPreview = false, int $post_id = 0, WP_Block $wp_block = null
    ): void
    {
        // Create the slug of the block using the name property in the block.json.
        $slug = str_replace( 'acf/', '', $attributes['name'] );

        $context = Timber::context();

        // Store block attributes.
        $context['attributes'] = $attributes;

        // Store field values. These are the fields from your ACF field group for the block.
        $context['fields'] = get_fields();

        // Store whether the block is being rendered in the editor or on the frontend.
        $context['isPreview'] = $isPreview;
        if ($isPreview) {
            $context['previewFieldObjects'] = get_field_objects();
        }

        // add the block directory as a template source
        /*Timber::$dirname = [
            PathUtility::getDirectoryOfBlock($slug),
            PathUtility::getTemplatePartsDirectory(),
        ];*/

        add_filter('timber/locations', function ($paths) use ($slug) {
            $paths[] = [
                PathUtility::getDirectoryOfBlock($slug),
                PathUtility::getTemplatePartsDirectory(),
            ];
            return $paths;
        });




        // include the block renderer file
        $blockRendererPath = PathUtility::getBlockRendererPath($slug);
        if (is_file($blockRendererPath)) {
            include_once($blockRendererPath);
            $renderFunctions = FileScannerUtility::getValidRenderFunctionsInFile($blockRendererPath);
            foreach ($renderFunctions as $i => $renderFunction) {
                // add a filter to modify the content of $context with that dynamic function
                add_filter( $slug . '-render-filter', $renderFunction, $i, 4 );
            }
        }

        // have all filters defined in block renderer php file modify $context
        $context = apply_filters( $slug . '-render-filter', $context );

        // tell timber to render a block's template file
        Timber::render(PathUtility::getBlockTemplatePath($slug), $context);
    }
}