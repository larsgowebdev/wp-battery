# WP-Battery

WP-Battery is a WordPress MU-Plugin that makes WordPress theme development easier. 
It combines Advanced Custom Fields Pro, Timber, and WordPress Block Editor into one simple setup tool. 
The plugin handles common theme tasks through file-based configuration and a simple API, reducing boilerplate code and speeding up development.

Blocks, pages, menus and options can be added following simple conventions. Adding them is even easier and fastest with our recommended companion Plugin:
[WP-Battery CLI](https://github.com/larsgowebdev/wp-battery-cli)

## Features

- **Twig Templates**:
    - Leverage the power and beauty of [Twig](https://twig.symfony.com/) in your WordPress, thanks to the amazing [Timber](https://github.com/timber/timber)!
    - Templates can be used for blocks and pages 
- **ACF Pro Blocks**:
    - Create your own [ACF Pro Blocks](https://www.advancedcustomfields.com/resources/blocks/) easily
    - optionally: disable core-blocks and limit yourself to your own ACF Pro Blocks
- **Supercharging ACF**:
    - Automatically synchronize all [ACF Pro](https://www.advancedcustomfields.com/pro/) field groups you create into your theme directory
- **Menus and Option Pages**:
    - Automatic registration of WP menus and [ACF Pro Options Pages](https://www.advancedcustomfields.com/resources/options-page/)
    - Simple file-based configuration
- **Custom Post Type and Taxonomy Registration**:
    - Register your custom post types and taxonomies from separate configuration files
    - Simplified registration based on structure and convention
- **Enable [Vite.js](https://vite.dev/) for your WordPress frontend**:
    - WP-Battery is ready for [Vite-Asset-Collector-WP](https://github.com/larsgowebdev/vite-asset-collector-wp)
- **Asset Management**:
    - Separate frontend and admin CSS/JS inclusion
    - Option to include block specific CSS/JS
- **Theme Support Management**: Easily enable WordPress theme features
- **WordPress Cleanup**: Don't copy-paste the same snippets into your functions.php over and over. WP-Battery can:
    - Add theme support
    - Remove comments
    - Enable SVG uploads
    - ... and much more in the future
- **Contact Form 7 Integration**: 
    - Custom file-based template support

## Requirements

- WordPress >= 6.5
- PHP 8.0+
- Timber >= 2.0
- Advanced Custom Fields PRO >= 6.3

### Additional support for:

- Contact Form 7

## Installation

In a WordPress composer based setup with Bedrock, this plugin will be automatically symlinked into your web/app/mu-plugins directory, and become available in your WP.

```bash
composer require larsgowebdev/wp-battery
```

Create a new instance of WP-Battery in your theme's `functions.php`

## Usage

Initialize the plugin in your theme's `functions.php`:

```php
$wpBattery = new WPBattery(
    themeNamespace: 'your-theme-name',
    settings: [
        // Your configuration here
    ]
);
```

## Setting up your own blocks, pages & more

> 💡 **Development Tip**  
> Consider using [WP-Battery CLI](https://github.com/larsgowebdev/wp-battery-cli) to automate the creation of components and folder structure:
> ```bash
> wp init-wpb              # Creates the full directory structure
> wp create-wpb-block      # Creates new blocks
> wp create-wpb-page       # Creates page templates
> wp create-wpb-menu       # Creates menu configurations
> wp create-wpb-options    # Creates options pages
> ```

Start by creating a `wpb` folder in your theme directory. This folder will contain all your templates, blocks, and configurations.

### Directory Structure
```
wpb/
├── acf-sync/              # ACF field group JSON files
├── blocks/               # Block templates and renderers
├── cf7-templates/        # Contact Form 7 templates
├── menus/                # Menu configurations
├── options/              # ACF options pages
├── pages/                # Page templates
└── template-parts/       # Reusable template parts
```

> 💡 **Quick Start (recommended)**  
> Initialize this entire structure automatically:
> ```bash
> wp init-wpb
> ```


### Creating Blocks

> 💡 **Quick Start (recommended)**  
> Create blocks quickly using [WP-Battery CLI](https://github.com/larsgowebdev/wp-battery-cli):
> ```bash
> wp create-wpb-block --name=project-info --title="Project Info"
> ```

#### Create WPB blocks manually

If you need to add or modify blocks manually or just want to dig deeper:

1. Create a folder `blocks` in your `wpb` directory
2. For each block, create a new folder (e.g., `project-info`)
3. Add required files:

##### block.json

```json
{
  "name": "acf/project-info",
  "title": "Project Info",
  "description": "Project Info",
  "category": "common",
  "icon": "visibility",
  "keywords": ["project-info"],
  "acf": {
    "mode": "preview",
    "renderCallback": ["Larsgowebdev\\WPBattery\\Renderer\\BlockRenderer", "renderACFBlock"]
  }
}
```

##### block-template.twig
Create `project-info-block-template.twig`:
```twig
{% block content %}
   <!-- put your template content here -->
{% endblock %}
```

##### block-renderer.php (Optional)
Create `project-info-block-renderer.php` if you need additional data processing:

```php
<?php

function render_project_info($context)
{
    $context['foo'] = 'bar';
    return $context;
}

// You can have multiple render functions - they'll be executed in order
function render_project_info_additional($context)
{
    $context['foo2'] = 'bar2';
    return $context;
}
```
> $context will be made available in your block's twig template

### Creating Page Templates

> 💡 **Quick Start (recommended)**  
> Create page templates easily using [WP-Battery CLI](https://github.com/larsgowebdev/wp-battery-cli):
> ```bash
> wp create-wpb-page --name=standard
> ```

#### In-Depth: Create WPB pages manually

If you need to add or modify pages manually or just want to dig deeper:

1. Create a folder `pages` in your `wpb` directory
2. For each template type, create a new folder (e.g., `standard`)
3. Add required files:

##### page-template.twig
Create `standard-page-template.twig`:
```twig
<!doctype html>
<html {{ site.language_attributes }}>
<title>{{ wp_title }} | {{ site.name }}</title>

{{ function('wp_head') }}

<body class="{{ body_class }}">
    {% block header %}
        {% include 'pages/header.twig' %}
    {% endblock %}

    {% block content %}
        {{post.content}}
    {% endblock %}

    {% block footer %}
        {% include 'pages/footer.twig' %}
    {% endblock %}

    {{ function('wp_footer') }}
</body>
</html>
```

##### page-renderer.php (Optional)
Create `standard-page-renderer.php` if you need additional data processing:
```php
<?php

function render_standard($context)
{
    $context['foo'] = 'bar';
    return $context;
}

// You can have multiple render functions - they'll be executed in order
function render_standard2($context)
{
    $context['foo2'] = 'bar2';
    return $context;
}
```

> $context will be made available in your page's twig template

### Registering Menus

> 💡 **Quick Start (recommended)**   
> Create menu configurations quickly using [WP-Battery CLI](https://github.com/larsgowebdev/wp-battery-cli):
> ```bash
> wp create-wpb-menu --name=main
> ```

#### In-Depth: Register menus manually

If you need to add or modify menus manually or just want to dig deeper:

1. Create a folder `menus` in your `wpb` directory
2. Add PHP files for each menu configuration:

Menu configuration files return an associative array defining the menu structure:
- the menu name/ identifier as *key*
- under the key *items*, you can predefine items always added to the menu
- see [wp_create_nav_menu()](https://developer.wordpress.org/reference/functions/wp_create_nav_menu/) and [wp_update_nav_menu_item()](https://developer.wordpress.org/reference/functions/wp_update_nav_menu_item/)

```php
<?php
// menus/main-menu.php
return [
    'Menu Name' => [
        'items' => [
            [
                'menu-item-title' => 'New Menu Item Title',
                'menu-item-url' => 'http://example.com/new-url',
            ],
        ],
    ],
];
```

### Registering ACF Options Pages

> 💡 **Quick Start (recommended)**  
> Create options pages easily using [WP-Battery CLI](https://github.com/larsgowebdev/wp-battery-cli):
> ```bash
> wp create-wpb-options --name=site-settings
> ```

#### In-Depth: Register option pages manually

If you need to add or modify menus manually or just want to dig deeper:

1. Create a folder `options` in your `wpb` directory
2. Add PHP files for each options page:

Options configuration files return an associative array defining the ACF options page.
- the option page identifier as *key*
- the configuration as *value* - internally, [acf_add_options_page()](https://www.advancedcustomfields.com/resources/acf_add_options_page/) is used

```php
<?php
// options/site-settings.php
return [
    'identifier' => [
        'page_title' => 'Page Title',
        'menu_title' => 'Menu Title',
        'menu_slug'  => 'menu-slug',
        'capability' => 'edit_posts',
        'position'   => '25',
        'redirect'   => false
    ]
];
```

### Registering Custom Post Types

> 💡 **Quick Start (recommended)**  
> Create custom post types easily using [WP-Battery CLI](https://github.com/larsgowebdev/wp-battery-cli):
> ```bash
> wp create-wpb-post-type --name=product --namespace=my-theme
> ```

#### In-Depth: Register custom post types manually

If you need to add or modify post types manually or just want to dig deeper:

1. Create a folder `post-types` in your `wpb` directory
2. Add PHP files for each custom post type

Custom Post Types configuration files return an associative array:
- the post type name as *key* (in this example 'product')
- the configuration as *value* - consult the [WordPress Post Type API](https://developer.wordpress.org/plugins/post-types/registering-custom-post-types/) for that

```php
<?php
// post-types/post-type-product.php
return [
    'product' => [
        'label'               => __( 'Products', 'my-theme'),
        'description'         => __( 'Products offered in the store', 'my-theme'),
        'labels'              => [
            'name'                => _x( 'Products', 'Post Type General Name', 'my-theme'),
        ],
        // Features this CPT supports in Post Editor
        'supports'            => [
            'title', 'editor', 'excerpt', 'author', 'thumbnail', 'comments', 'revisions', 'custom-fields', 'page-attributes'
        ],
        // You can associate this CPT with a taxonomy or custom taxonomy.
        'taxonomies'          => [
            'product-category', 'product-location'
        ],
        'hierarchical'        => true,
        'public'              => true,
        'show_ui'             => true,
        'show_in_nav_menus'   => true,
        'show_in_admin_bar'   => true,
        'capability_type'     => 'post',
        // read the WP developer docs for more options
    ],
];
```

### Registering Custom Taxonomies

> 💡 **Quick Start (recommended)**  
> Create custom post types easily using [WP-Battery CLI](https://github.com/larsgowebdev/wp-battery-cli):
> ```bash
> wp create-wpb-taxonomy --name=product-category --namespace=my-theme --post-type=product
> ```

#### In-Depth: Register taxonomies manually

If you need to add or modify post types manually or just want to dig deeper:

1. Create a folder `taxonomies` in your `wpb` directory
2. Add PHP files for each custom taxonomy you want to add

Taxonomy configuration files return an associative array:
- the taxonomy name as *key* (in this example 'product-category')
- the configuration array as *value* - make sure to consult the WordPress API documentation for [register_taxonomy()](https://developer.wordpress.org/reference/functions/register_taxonomy/)
- **important**: To connect a taxonomy with a post-type, the array item 'for_post_types' is required, which will be converted to the second parameter for register_taxonomy (Object type or array of object types with which the taxonomy should be associated.)

```php
<?php
// taxonomies/taxonomy-product-category.php
return [
    'product-category' => [
        'labels' => [
            'name' => _x( 'Product Categories', 'my-theme' ),
        ],
        'hierarchical' => true,
        'show_ui' => true,
        'public' => true,
        'show_in_rest' => true,
        'show_admin_column' => true,
        'query_var' => true,
        // consult the WP docs on register_taxonomy() for more options.
        // 
        // for_post_types is not part of the WordPress API,
        // but is required for WP-Battery's taxonomy registration mechanism
        'for_post_types' => [
            'product'
        ],
    ],
];
```

---

## Additional Features explained

### Managing Template Partials

1. Create a folder `template-parts` in your `wpb` directory
2. Organize partials in subfolders (e.g., `blocks/`, `pages/`)
3. Reference partials in your templates:

```twig
{% block header %}
    {% include 'pages/header.twig' %}
{% endblock %}
```

### ACF Sync

When `enableACFSync` is enabled in your configuration:
- ACF field group changes are synchronized to JSON files in the `acf-sync` directory
- One JSON file per field group
- **Important**: In production environments, changes deployed via code must be synchronized manually

### Contact Form 7 Templates

This enables you to keep your CF7 templates in files.

1. Create a folder `cf7-templates` in your `wpb` directory
2. Add your Contact Form 7 template files here
3. Reference them in your configuration using the form hash:

```php
'contactForm7Templates' => [
    '48899ec' => 'contact-form.twig',
]
```

---

## Configuration Reference

#### Full Configuration Example

Here's a comprehensive example showing all available options:

```php
$wpBattery = new WPBattery(
    themeNamespace: 'my-theme',
    settings: [
        'themeSupport' => [
            'menus',
            'post-thumbnails',
            'editor-styles',
            'responsive-embeds',
            'align-wide',
        ],
        'registerBlocks' => true,
        'registerMenus' => true,
        'registerOptions' => true,
        'enableACFSync' => true,
        'allowSVGUploads' => true,
        'disallowNonACFBlocks' => true,
        'disableComments' => true,
        'enableViteAssets' => true,
        'viteBuildDir' => 'build',
        'viteEntryPoint' => 'frontend/vite.entry.js',
        'includeFrontendCSS' => [
            'main-style' => [
                'path' => get_stylesheet_directory_uri() . '/assets/css/main.css',
                'dependencies' => [],
                'version' => '1.0.0',
                'media' => 'all'
            ]
        ],
        'includeFrontendJS' => [
            'main-script' => [
                'path' => get_stylesheet_directory_uri() . '/assets/js/main.js',
                'dependencies' => ['jquery'],
                'version' => '1.0.0',
                'args' => ['strategy' => 'defer']
            ]
        ],
        'includeAdminCSS' => [
            'admin-style' => [
                'path' => get_stylesheet_directory_uri() . '/assets/admin/admin.css',
            ]
        ],
        'includeAdminJS' => [
            'admin-script' => [
                'path' => get_stylesheet_directory_uri() . '/assets/admin/admin.js',
                'dependencies' => ['wp-blocks'],
            ]
        ],
        'contactForm7Templates' => [
            '48899ec' => 'contact-form.twig',
        ],
        'addMetaTags' => [
            'viewport' => 'width=device-width, initial-scale=1, shrink-to-fit=no',
        ],
    ],
    enableCache: true
);
```

### Settings explained

#### Theme Support (`themeSupport`)
Array of WordPress theme features to enable. Common options include:
```php
'themeSupport' => [
    'menus',                // Enable WordPress menus
    'post-thumbnails',      // Enable featured images
    'editor-styles',        // Enable block editor styles
    'responsive-embeds',    // Enable responsive embedded content
    'align-wide',          // Enable wide/full alignment for blocks
    'custom-logo',         // Enable custom logo support
    // etc...
]
```

#### Block Registration (`registerBlocks`)
When enabled, automatically registers all block.json files found in your theme's blocks directory.
```php
'registerBlocks' => true    // Scans and registers all block.json files
```

#### Menu Registration (`registerMenus`)
When enabled, processes menu configuration files from your theme's menu directory.
```php
'registerMenus' => true     // Registers menus from PHP configuration files
```

#### ACF Options Pages (`registerOptions`)
Automatically registers ACF options pages from configuration files.
```php
'registerOptions' => true   // Creates ACF options pages from config files
```

#### ACF Sync (`enableACFSync`)
Enables ACF field synchronization using PHP files.
```php
'enableACFSync' => true    // Enables ACF JSON sync functionality
```

#### SVG Upload Support (`allowSVGUploads`)
Enables SVG file uploads in the WordPress media library.
```php
'allowSVGUploads' => true  // Allows SVG file uploads
```

#### ACF-Only Blocks (`disallowNonACFBlocks`)
Restricts the block editor to only show ACF blocks.
```php
'disallowNonACFBlocks' => true  // Hides all non-ACF blocks
```

#### Comment System (`disableComments`)
Completely removes WordPress comment functionality.
```php
'disableComments' => true   // Removes all comment functionality
```

#### Vite.js Integration (`enableViteAssets`, `viteBuildDir`, `viteEntryPoint`)
Configure Vite.js asset building:
```php
'enableViteAssets' => true,
'viteBuildDir' => 'build',              // Directory for compiled assets
'viteEntryPoint' => 'frontend/vite.entry.js'  // Main entry point
```

#### Frontend Asset Inclusion
Include CSS and JavaScript files in the frontend:
```php
'includeFrontendCSS' => [
    'main-style' => [
        'path' => get_stylesheet_directory_uri() . '/assets/css/main.css',
        'dependencies' => [],
        'version' => '1.0.0',
        'media' => 'all'
    ]
],
'includeFrontendJS' => [
    'main-script' => [
        'path' => get_stylesheet_directory_uri() . '/assets/js/main.js',
        'dependencies' => ['jquery'],
        'version' => '1.0.0',
        'args' => ['strategy' => 'defer']
    ]
]
```

#### Admin Asset Inclusion
Include CSS and JavaScript files in the admin area:
```php
'includeAdminCSS' => [
    'admin-style' => [
        'path' => get_stylesheet_directory_uri() . '/assets/admin/admin.css',
        'dependencies' => [],
        'version' => '1.0.0',
        'media' => 'all'
    ]
],
'includeAdminJS' => [
    'admin-script' => [
        'path' => get_stylesheet_directory_uri() . '/assets/admin/admin.js',
        'dependencies' => ['wp-blocks'],
        'version' => '1.0.0',
        'args' => []
    ]
]
```

#### Contact Form 7 Templates (`contactForm7Templates`)
Map Contact Form 7 forms to Twig templates:
```php
'contactForm7Templates' => [
    '48899ec' => 'contact-form.twig',  // Form hash => template path
]
```

#### Additional Head Meta Tags (`addMetaTags`)
Provide additional meta tags for wp_head as an array (key = tag name, value = tag content).
```php
'addMetaTags' => [
    'viewport' => 'width=device-width, initial-scale=1, shrink-to-fit=no',
],
```

## Cache Management

The plugin includes a caching system for better performance. You can manage it using:

```php
// Clear cache for specific theme
WPBattery::clearCache('theme-namespace');

// Clear all WP-Battery cache
WPBattery::clearAllCache();
```

## License

This project is licensed under the GPL-2.0 License - see the [LICENSE](LICENSE) file for details.