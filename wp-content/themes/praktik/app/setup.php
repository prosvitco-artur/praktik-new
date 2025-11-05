<?php

/**
 * Theme setup.
 */

namespace App;

use function Roots\bundle;

/**
 * Register the theme assets.
 *
 * @return void
 */
add_action('wp_enqueue_scripts', function () {
    bundle('app')->enqueue();
    
    wp_localize_script('app', 'praktikAjax', [
        'ajaxurl' => admin_url('admin-ajax.php'),
    ]);
}, 100);

/**
 * Register the theme assets with the block editor.
 *
 * @return void
 */
add_action('enqueue_block_editor_assets', function () {
    bundle('editor')->enqueue();
}, 100);

/**
 * Register the initial theme setup.
 *
 * @return void
 */
add_action('after_setup_theme', function () {
    /**
     * Enable features from the Soil plugin if activated.
     *
     * @link https://roots.io/plugins/soil/
     */
    add_theme_support('soil', [
        'clean-up',
        'nav-walker',
        'nice-search',
        'relative-urls',
    ]);

    /**
     * Disable full-site editing support.
     *
     * @link https://wptavern.com/gutenberg-10-5-embeds-pdfs-adds-verse-block-color-options-and-introduces-new-patterns
     */
    remove_theme_support('block-templates');

    /**
     * Register the navigation menus.
     *
     * @link https://developer.wordpress.org/reference/functions/register_nav_menus/
     */
    register_nav_menus([
        'primary_navigation' => __('Primary Navigation', 'praktik'),
    ]);

    /**
     * Disable the default block patterns.
     *
     * @link https://developer.wordpress.org/block-editor/developers/themes/theme-support/#disabling-the-default-block-patterns
     */
    remove_theme_support('core-block-patterns');

    /**
     * Enable plugins to manage the document title.
     *
     * @link https://developer.wordpress.org/reference/functions/add_theme_support/#title-tag
     */
    add_theme_support('title-tag');

    /**
     * Enable post thumbnail support.
     *
     * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
     */
    add_theme_support('post-thumbnails');

    /**
     * Enable custom logo support.
     *
     * @link https://developer.wordpress.org/themes/functionality/custom-logo/
     */
    add_theme_support('custom-logo', [
        'height'      => 60,
        'width'       => 200,
        'flex-height' => true,
        'flex-width'  => true,
        'header-text' => ['site-title', 'site-description'],
    ]);

    /**
     * Enable responsive embed support.
     *
     * @link https://developer.wordpress.org/block-editor/how-to-guides/themes/theme-support/#responsive-embedded-content
     */
    add_theme_support('responsive-embeds');

    /**
     * Enable HTML5 markup support.
     *
     * @link https://developer.wordpress.org/reference/functions/add_theme_support/#html5
     */
    add_theme_support('html5', [
        'caption',
        'comment-form',
        'comment-list',
        'gallery',
        'search-form',
        'script',
        'style',
    ]);

}, 20);


add_action('customize_register', function ($wp_customize) {
    $wp_customize->add_setting('footer_logo', [
        'default' => '',
        'sanitize_callback' => 'absint',
    ]);

    $wp_customize->add_control(new \WP_Customize_Media_Control($wp_customize, 'footer_logo', [
        'label' => __('Footer Logo', 'praktik'),
        'section' => 'title_tagline',
        'mime_type' => 'image',
        'priority' => 9,
    ]));
});

add_shortcode('property_search_form', function () {
    return view('forms.property-search')->render();
});

add_shortcode('reviews_archive', function () {
    return view('shortcodes.reviews-archive')->render();
});

add_action('init', function () {
    add_rewrite_rule('^favorites/?$', 'index.php?favorites=1', 'top');
});

add_filter('query_vars', function ($vars) {
    $vars[] = 'favorites';
    return $vars;
});

add_filter('template_include', function ($template) {
    if (get_query_var('favorites')) {
        return get_theme_file_path('template-favorites.php');
    }
    
    if (is_singular('review')) {
        global $wp_query;
        $wp_query->set_404();
        status_header(404);
        return get_404_template();
    }
    
    return $template;
});

add_action('after_switch_theme', function () {
    flush_rewrite_rules();
});

add_filter('upload_mimes', function ($mimes) {
    $mimes['svg'] = 'image/svg+xml';
    return $mimes;
});

add_filter('wp_check_filetype_and_ext', function ($data, $file, $filename, $mimes) {
    $filetype = wp_check_filetype($filename, $mimes);
    return [
        'ext'             => $filetype['ext'],
        'type'            => $filetype['type'],
        'proper_filename' => $data['proper_filename']
    ];
}, 10, 4);
