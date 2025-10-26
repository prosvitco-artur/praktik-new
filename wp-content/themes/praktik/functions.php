<?php

/*
|--------------------------------------------------------------------------
| Register The Auto Loader
|--------------------------------------------------------------------------
|
| Composer provides a convenient, automatically generated class loader for
| our theme. We will simply require it into the script here so that we
| don't have to worry about manually loading any of our classes later on.
|
*/

if (! file_exists($composer = __DIR__.'/vendor/autoload.php')) {
    wp_die(__('Error locating autoloader. Please run <code>composer install</code>.', 'praktik'));
}

require $composer;

/*
|--------------------------------------------------------------------------
| Register The Bootloader
|--------------------------------------------------------------------------
|
| The first thing we will do is schedule a new Acorn application container
| to boot when WordPress is finished loading the theme. The application
| serves as the "glue" for all the components of Laravel and is
| the IoC container for the system binding all of the various parts.
|
*/

try {
    \Roots\bootloader();
} catch (Throwable $e) {
    wp_die(
        __('You need to install Acorn to use this theme.', 'praktik'),
        '',
        [
            'link_url' => 'https://docs.roots.io/acorn/2.x/installation/',
            'link_text' => __('Acorn Docs: Installation', 'praktik'),
        ]
    );
}

/*
|--------------------------------------------------------------------------
| Register Sage Theme Files
|--------------------------------------------------------------------------
|
| Out of the box, Sage ships with categorically named theme files
| containing common functionality and setup to be bootstrapped with your
| theme. Simply add (or remove) files from the array below to change what
| is registered alongside Sage.
|
*/

collect(['setup', 'filters', 'helpers', 'post-types', 'theme-options'])
    ->each(function ($file) {
        if (! locate_template($file = "app/{$file}.php", true, true)) {
            wp_die(
                /* translators: %s is replaced with the relative file path */
                sprintf(__('Error locating <code>%s</code> for inclusion.', 'praktik'), $file)
            );
        }
    });

/*
|--------------------------------------------------------------------------
| Enable Sage Theme Support
|--------------------------------------------------------------------------
|
| Once our theme files are registered and available for use, we are almost
| ready to boot our application. But first, we need to signal to Acorn
| that we will need to initialize the necessary service providers built in
| for Sage when booting.
|
*/

add_theme_support('sage');

/*
|--------------------------------------------------------------------------
| Load Theme Text Domain
|--------------------------------------------------------------------------
|
| Load the theme text domain for translations.
|
*/

add_action('after_setup_theme', function () {
    load_theme_textdomain('praktik', get_template_directory() . '/languages');
});

add_filter('https_ssl_verify', '__return_false');
add_filter('https_local_ssl_verify', '__return_false');
add_filter('http_request_args', function ($args, $url) {
    $args['sslverify'] = false;
    $args['timeout'] = 30;
    return $args;
}, 10, 2);

add_filter('airwpsync/get_post_types', function ($post_types) {
    return array_map(function ($post_type) {
        $post_type['enabled'] = true;
        return $post_type;
    }, $post_types);
});

add_filter('airwpsync/get_wp_fields', function ($fields, $module) {
    if ($module === 'post' && isset($fields['post']['options'])) {
        foreach ($fields['post']['options'] as &$option) {
            if ($option['value'] === 'postmeta::custom_field') {
                $option['enabled'] = true;
                $option['label'] = __('Custom Field...', 'praktik');
            }
        }
    }
    return $fields;
}, 20, 2);