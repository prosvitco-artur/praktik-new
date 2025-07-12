<?php

/**
 * Debug functions for post types
 */

namespace App;

/**
 * Debug function to check if post types are registered
 */
add_action('wp_footer', function () {
    if (current_user_can('administrator')) {
        echo '<!-- Debug: Post Types -->';
        echo '<!-- Registered post types: ' . implode(', ', get_post_types(['public' => true])) . ' -->';
        echo '<!-- Room post type exists: ' . (post_type_exists('room') ? 'yes' : 'no') . ' -->';
        echo '<!-- Archive URL: ' . get_post_type_archive_link('room') . ' -->';
    }
});

/**
 * Add admin notice for post types
 */
add_action('admin_notices', function () {
    if (isset($_GET['post_type_debug'])) {
        $post_types = get_post_types(['public' => true], 'objects');
        echo '<div class="notice notice-info">';
        echo '<h3>Registered Post Types:</h3>';
        echo '<ul>';
        foreach ($post_types as $post_type => $object) {
            echo '<li><strong>' . $post_type . '</strong>: ' . $object->labels->name . '</li>';
        }
        echo '</ul>';
        echo '</div>';
    }
});

/**
 * Add admin bar menu for debugging
 */
add_action('admin_bar_menu', function ($wp_admin_bar) {
    if (current_user_can('administrator')) {
        $wp_admin_bar->add_menu([
            'id' => 'debug-post-types',
            'title' => 'Debug Post Types',
            'href' => add_query_arg('post_type_debug', '1', admin_url()),
        ]);
        
        $wp_admin_bar->add_menu([
            'id' => 'flush-rewrite',
            'title' => 'Flush Rewrite Rules',
            'href' => add_query_arg('flush_rewrite', '1', admin_url()),
        ]);
    }
});

/**
 * Handle flush rewrite action
 */
add_action('admin_init', function () {
    if (isset($_GET['flush_rewrite']) && current_user_can('administrator')) {
        flush_rewrite_rules();
        wp_redirect(remove_query_arg('flush_rewrite'));
        exit;
    }
}); 