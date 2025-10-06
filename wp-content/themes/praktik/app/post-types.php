<?php

namespace App;

/**
 * Реєстрація пост тайпів нерухомості
 */
add_action('init', function () {
    $post_types = get_property_post_types();
    
    foreach ($post_types as $post_type => $label) {
        $args = [
            'labels' => [
                'name' => $label,
                'singular_name' => $label,
                'menu_name' => $label,
                'name_admin_bar' => $label,
                'add_new' => __('Add new', 'sage'),
                'add_new_item' => sprintf(__('Add new %s', 'sage'), $label),
                'new_item' => sprintf(__('New %s', 'sage'), $label),
                'edit_item' => sprintf(__('Edit %s', 'sage'), $label),
                'view_item' => sprintf(__('View %s', 'sage'), $label),
                'all_items' => sprintf(__('All %s', 'sage'), $label),
                'search_items' => sprintf(__('Search %s', 'sage'), $label),
                'parent_item_colon' => sprintf(__('Parent %s:', 'sage'), $label),
                'not_found' => sprintf(__('Not found %s', 'sage'), $label),
                'not_found_in_trash' => sprintf(__('Not found %s in trash', 'sage'), $label),
            ],
            'public' => true,
            'publicly_queryable' => true,
            'show_ui' => true,
            'show_in_menu' => true,
            'show_in_nav_menus' => true,
            'show_in_admin_bar' => true,
            'show_in_rest' => false,
            'query_var' => true,
            'rewrite' => [
                'slug' => $post_type,
                'with_front' => false,
            ],
            'capability_type' => 'post',
            'has_archive' => true,
            'hierarchical' => false,
            'menu_position' => 5,
            'menu_icon' => 'dashicons-admin-home',
            'supports' => [
                'title',
                'editor',
                'author',
                'thumbnail',
                'excerpt',
                'comments',
                'custom-fields',
                'revisions',
            ],
            'taxonomies' => ['category', 'post_tag'],
        ];
        
        register_post_type($post_type, $args);
    }
});

/**
 * Відключити Gutenberg для пост-тайпів нерухомості
 */
add_filter('use_block_editor_for_post_type', function ($use_block_editor, $post_type) {
    $property_post_types = array_keys(get_property_post_types());
    
    if (in_array($post_type, $property_post_types)) {
        return false;
    }
    
    return $use_block_editor;
}, 10, 2);

/**
 * Додати пост тайпи нерухомості до головного запиту
 */
add_action('pre_get_posts', function ($query) {
    if (!is_admin() && $query->is_main_query()) {
        if (is_home() || is_front_page()) {
            $post_types = array_keys(get_property_post_types());
            $post_types[] = 'post';
            $query->set('post_type', $post_types);
        }
    }
});

/**
 * Додати пост тайпи до пошуку
 */
add_filter('pre_get_posts', function ($query) {
    if (!is_admin() && $query->is_search()) {
        $post_types = array_keys(get_property_post_types());
        $post_types[] = 'post';
        $query->set('post_type', $post_types);
    }
    return $query;
});
