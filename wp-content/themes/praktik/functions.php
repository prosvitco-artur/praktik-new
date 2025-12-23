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

collect(
    [
        'setup',
        'filters',
        'helpers',
        'post-types',
        'theme-options',
        'integrations-airwpsync',
        'content-cleanup',
        'media-cleanup',
        'favorites',
    ])
    ->each(function ($file) {
        if (! locate_template($file = "app/{$file}.php", true, true)) {
            wp_die(
                /* translators: %s is replaced with the relative file path */
                sprintf(__('Error locating <code>%s</code> for inclusion.', 'praktik'), $file)
            );
        }
    });

add_action('carbon_fields_register_fields', function() {
    $blocks_path = get_template_directory() . '/app/carbon-blocks';
    
    if (is_dir($blocks_path)) {
        $blocks = glob($blocks_path . '/*.php');
        
        foreach ($blocks as $block) {
            require_once $block;
        }
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

add_action('init', function() {
    if (!class_exists('Air_WP_Sync_Free\Air_WP_Sync')) {
        return;
    }
    
    $importers = apply_filters('airwpsync/get_importers', []);
    
    if (empty($importers)) {
        return;
    }
    
    foreach ($importers as $importer) {
        if (!is_object($importer) || !method_exists($importer, 'infos') || !method_exists($importer, 'cron') || !method_exists($importer, 'config')) {
            continue;
        }
        
        $importer_id = $importer->infos()->get('id');
        if (!$importer_id) {
            continue;
        }
        
        $sync_type = $importer->config()->get('scheduled_sync.type');
        $sync_recurrence = $importer->config()->get('scheduled_sync.recurrence');
        
        $needs_update = false;
        $post = get_post($importer_id);
        
        if ($post && $post->post_type === 'airwpsync-connection') {
            $config = json_decode($post->post_content, true);
            
            if (!is_array($config)) {
                $config = [];
            }
            
            if (!isset($config['scheduled_sync'])) {
                $config['scheduled_sync'] = [];
            }
            
            if ($config['scheduled_sync']['type'] !== 'cron' || $config['scheduled_sync']['recurrence'] !== 'airwpsync_tenminutes') {
                $config['scheduled_sync']['type'] = 'cron';
                $config['scheduled_sync']['recurrence'] = 'airwpsync_tenminutes';
                $needs_update = true;
            }
            
            if ($needs_update) {
                wp_update_post([
                    'ID' => $importer_id,
                    'post_content' => wp_json_encode($config)
                ]);
            }
        }
        
        $schedule_slug = 'air_wp_sync_importer_' . $importer_id;
        $next_scheduled = wp_next_scheduled($schedule_slug);
        
        if (false === $next_scheduled || $needs_update) {
            if ($next_scheduled) {
                wp_clear_scheduled_hook($schedule_slug);
            }
            
            $callback = [$importer, 'cron'];
            
            if (!has_action($schedule_slug, $callback)) {
                add_action($schedule_slug, $callback);
            }
            
            wp_schedule_event(time(), 'airwpsync_tenminutes', $schedule_slug);
        }
    }
}, 200);

remove_image_size( 'thumbnail' );
remove_image_size( 'medium' );
remove_image_size( 'large' );