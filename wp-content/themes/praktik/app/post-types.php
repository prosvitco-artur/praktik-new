<?php

/**
 * Custom Post Types Registration
 */

namespace App;

/**
 * Register custom post types for real estate
 */
add_action('init', function () {
    // Room Post Type
    register_post_type('room', [
        'labels' => [
            'name' => __('Rooms', 'sage'),
            'singular_name' => __('Room', 'sage'),
            'add_new' => __('Add New Room', 'sage'),
            'add_new_item' => __('Add New Room', 'sage'),
            'edit_item' => __('Edit Room', 'sage'),
            'new_item' => __('New Room', 'sage'),
            'view_item' => __('View Room', 'sage'),
            'search_items' => __('Search Rooms', 'sage'),
            'not_found' => __('No rooms found', 'sage'),
            'not_found_in_trash' => __('No rooms found in trash', 'sage'),
        ],
        'public' => true,
        'has_archive' => true,
        'supports' => ['title', 'editor', 'thumbnail', 'excerpt'],
        'menu_icon' => 'dashicons-admin-home',
        'rewrite' => ['slug' => 'rooms', 'with_front' => false],
        'show_in_rest' => true,
        'capability_type' => 'post',
        'hierarchical' => false,
        'menu_position' => 5,
    ]);

    // Apartment Post Type
    register_post_type('apartment', [
        'labels' => [
            'name' => __('Apartments', 'sage'),
            'singular_name' => __('Apartment', 'sage'),
            'add_new' => __('Add New Apartment', 'sage'),
            'add_new_item' => __('Add New Apartment', 'sage'),
            'edit_item' => __('Edit Apartment', 'sage'),
            'new_item' => __('New Apartment', 'sage'),
            'view_item' => __('View Apartment', 'sage'),
            'search_items' => __('Search Apartments', 'sage'),
            'not_found' => __('No apartments found', 'sage'),
            'not_found_in_trash' => __('No apartments found in trash', 'sage'),
        ],
        'public' => true,
        'has_archive' => true,
        'supports' => ['title', 'editor', 'thumbnail', 'excerpt'],
        'menu_icon' => 'dashicons-building',
        'rewrite' => ['slug' => 'apartments', 'with_front' => false],
        'show_in_rest' => true,
        'capability_type' => 'post',
        'hierarchical' => false,
        'menu_position' => 5,
    ]);

    // House Post Type
    register_post_type('house', [
        'labels' => [
            'name' => __('Houses', 'sage'),
            'singular_name' => __('House', 'sage'),
            'add_new' => __('Add New House', 'sage'),
            'add_new_item' => __('Add New House', 'sage'),
            'edit_item' => __('Edit House', 'sage'),
            'new_item' => __('New House', 'sage'),
            'view_item' => __('View House', 'sage'),
            'search_items' => __('Search Houses', 'sage'),
            'not_found' => __('No houses found', 'sage'),
            'not_found_in_trash' => __('No houses found in trash', 'sage'),
        ],
        'public' => true,
        'has_archive' => true,
        'supports' => ['title', 'editor', 'thumbnail', 'excerpt'],
        'menu_icon' => 'dashicons-admin-home',
        'rewrite' => ['slug' => 'houses', 'with_front' => false],
        'show_in_rest' => true,
        'capability_type' => 'post',
        'hierarchical' => false,
        'menu_position' => 5,
    ]);

    // Plot Post Type
    register_post_type('plot', [
        'labels' => [
            'name' => __('Plots', 'sage'),
            'singular_name' => __('Plot', 'sage'),
            'add_new' => __('Add New Plot', 'sage'),
            'add_new_item' => __('Add New Plot', 'sage'),
            'edit_item' => __('Edit Plot', 'sage'),
            'new_item' => __('New Plot', 'sage'),
            'view_item' => __('View Plot', 'sage'),
            'search_items' => __('Search Plots', 'sage'),
            'not_found' => __('No plots found', 'sage'),
            'not_found_in_trash' => __('No plots found in trash', 'sage'),
        ],
        'public' => true,
        'has_archive' => true,
        'supports' => ['title', 'editor', 'thumbnail', 'excerpt'],
        'menu_icon' => 'dashicons-location',
        'rewrite' => ['slug' => 'plots', 'with_front' => false],
        'show_in_rest' => true,
        'capability_type' => 'post',
        'hierarchical' => false,
        'menu_position' => 5,
    ]);

    // Garage Post Type
    register_post_type('garage', [
        'labels' => [
            'name' => __('Garages', 'sage'),
            'singular_name' => __('Garage', 'sage'),
            'add_new' => __('Add New Garage', 'sage'),
            'add_new_item' => __('Add New Garage', 'sage'),
            'edit_item' => __('Edit Garage', 'sage'),
            'new_item' => __('New Garage', 'sage'),
            'view_item' => __('View Garage', 'sage'),
            'search_items' => __('Search Garages', 'sage'),
            'not_found' => __('No garages found', 'sage'),
            'not_found_in_trash' => __('No garages found in trash', 'sage'),
        ],
        'public' => true,
        'has_archive' => true,
        'supports' => ['title', 'editor', 'thumbnail', 'excerpt'],
        'menu_icon' => 'dashicons-car',
        'rewrite' => ['slug' => 'garages', 'with_front' => false],
        'show_in_rest' => true,
        'capability_type' => 'post',
        'hierarchical' => false,
        'menu_position' => 5,
    ]);

    // Commercial Post Type
    register_post_type('commercial', [
        'labels' => [
            'name' => __('Commercial', 'sage'),
            'singular_name' => __('Commercial Property', 'sage'),
            'add_new' => __('Add New Commercial Property', 'sage'),
            'add_new_item' => __('Add New Commercial Property', 'sage'),
            'edit_item' => __('Edit Commercial Property', 'sage'),
            'new_item' => __('New Commercial Property', 'sage'),
            'view_item' => __('View Commercial Property', 'sage'),
            'search_items' => __('Search Commercial Properties', 'sage'),
            'not_found' => __('No commercial properties found', 'sage'),
            'not_found_in_trash' => __('No commercial properties found in trash', 'sage'),
        ],
        'public' => true,
        'has_archive' => true,
        'supports' => ['title', 'editor', 'thumbnail', 'excerpt'],
        'menu_icon' => 'dashicons-store',
        'rewrite' => ['slug' => 'commercial', 'with_front' => false],
        'show_in_rest' => true,
        'capability_type' => 'post',
        'hierarchical' => false,
        'menu_position' => 5,
    ]);

    // Dacha Post Type
    register_post_type('dacha', [
        'labels' => [
            'name' => __('Dachas', 'sage'),
            'singular_name' => __('Dacha', 'sage'),
            'add_new' => __('Add New Dacha', 'sage'),
            'add_new_item' => __('Add New Dacha', 'sage'),
            'edit_item' => __('Edit Dacha', 'sage'),
            'new_item' => __('New Dacha', 'sage'),
            'view_item' => __('View Dacha', 'sage'),
            'search_items' => __('Search Dachas', 'sage'),
            'not_found' => __('No dachas found', 'sage'),
            'not_found_in_trash' => __('No dachas found in trash', 'sage'),
        ],
        'public' => true,
        'has_archive' => true,
        'supports' => ['title', 'editor', 'thumbnail', 'excerpt'],
        'menu_icon' => 'dashicons-admin-home',
        'rewrite' => ['slug' => 'dachas', 'with_front' => false],
        'show_in_rest' => true,
        'capability_type' => 'post',
        'hierarchical' => false,
        'menu_position' => 5,
    ]);
    
    // Flush rewrite rules after registering post types
    flush_rewrite_rules();
});

/**
 * Ensure archive pages work correctly
 */
add_action('template_redirect', function () {
    if (is_post_type_archive()) {
        // Debug information
        if (current_user_can('administrator')) {
            error_log('Archive page accessed: ' . get_post_type());
        }
    }
}); 