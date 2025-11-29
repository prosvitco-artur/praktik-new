<?php

namespace App;

use Carbon_Fields\Container;
use Carbon_Fields\Field;

// Load field definitions
require_once __DIR__ . '/fields/property-field-options.php';
require_once __DIR__ . '/fields/property-fields.php';

add_action('after_setup_theme', function() {
    \Carbon_Fields\Carbon_Fields::boot();
});

add_action('init', function () {
    $post_types = get_property_post_types();
    
    foreach ($post_types as $post_type => $label) {
        $args = [
            'labels' => [
                'name' => $label,
                'singular_name' => $label,
                'menu_name' => $label,
                'name_admin_bar' => $label,
                'add_new' => __('Add new', 'praktik'),
                'add_new_item' => sprintf(__('Add new %s', 'praktik'), $label),
                'new_item' => sprintf(__('New %s', 'praktik'), $label),
                'edit_item' => sprintf(__('Edit %s', 'praktik'), $label),
                'view_item' => sprintf(__('View %s', 'praktik'), $label),
                'all_items' => sprintf(__('All %s', 'praktik'), $label),
                'search_items' => sprintf(__('Search %s', 'praktik'), $label),
                'parent_item_colon' => sprintf(__('Parent %s:', 'praktik'), $label),
                'not_found' => sprintf(__('Not found %s', 'praktik'), $label),
                'not_found_in_trash' => sprintf(__('Not found %s in trash', 'praktik'), $label),
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
    
    $review_labels = [
        'name' => __('Reviews', 'praktik'),
        'singular_name' => __('Review', 'praktik'),
        'menu_name' => __('Reviews', 'praktik'),
        'name_admin_bar' => __('Review', 'praktik'),
        'add_new' => __('Add new', 'praktik'),
        'add_new_item' => __('Add new Review', 'praktik'),
        'new_item' => __('New Review', 'praktik'),
        'edit_item' => __('Edit Review', 'praktik'),
        'view_item' => __('View Review', 'praktik'),
        'all_items' => __('All Reviews', 'praktik'),
        'search_items' => __('Search Reviews', 'praktik'),
        'parent_item_colon' => __('Parent Reviews:', 'praktik'),
        'not_found' => __('No reviews found', 'praktik'),
        'not_found_in_trash' => __('No reviews found in trash', 'praktik'),
    ];
    
    register_post_type('review', [
        'labels' => $review_labels,
        'public' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'show_in_nav_menus' => false,
        'show_in_admin_bar' => true,
        'show_in_rest' => false,
        'query_var' => true,
        'rewrite' => [
            'slug' => 'reviews',
            'with_front' => false,
        ],
        'capability_type' => 'post',
        'has_archive' => true,
        'hierarchical' => false,
        'menu_position' => 6,
        'menu_icon' => 'dashicons-star-filled',
        'supports' => [
            'title',
            'editor',
            'author',
            'thumbnail',
            'excerpt',
            'revisions',
        ],
        'taxonomies' => [],
    ]);
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

add_action('carbon_fields_register_fields', function() {
    // dump('register fields');
    \App\Fields\PropertyFields::register();
});

/**
 * Auto-generate property code on save
 */
add_action('save_post', function($post_id) {
    $property_post_types = array_keys(get_property_post_types());
    $post_type = get_post_type($post_id);
    
    if (!in_array($post_type, $property_post_types)) {
        return;
    }
    
    // Skip autosave and revisions
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    
    if (wp_is_post_revision($post_id)) {
        return;
    }
    
    // Generate code if not exists
    $existing_code = carbon_get_post_meta($post_id, 'property_code');
    if (empty($existing_code)) {
        // Generate code: POST_TYPE-YYYYMMDD-XXXX
        $prefix = strtoupper(substr($post_type, 0, 1));
        $date = date('Ymd');
        $random = str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
        $code = sprintf('%s-%s-%s', $prefix, $date, $random);
        
        carbon_set_post_meta($post_id, 'property_code', $code);
    }
}, 10, 1);

/**
 * Фільтрація архіву відгуків за пошуком і датами
 */
add_action('pre_get_posts', function ($query) {
    if (is_admin() || !$query->is_main_query()) {
        return;
    }

    if ($query->is_post_type_archive('review')) {
        $search = isset($_GET['search']) ? sanitize_text_field($_GET['search']) : '';
        $date_from = isset($_GET['date_from']) ? sanitize_text_field($_GET['date_from']) : '';
        $date_to = isset($_GET['date_to']) ? sanitize_text_field($_GET['date_to']) : '';

        if ($search !== '') {
            $query->set('s', $search);
        }

        $date_query = [];
        if ($date_from) {
            $date_query['after'] = $date_from;
        }
        if ($date_to) {
            $date_query['before'] = $date_to;
            $date_query['inclusive'] = true;
        }
        if (!empty($date_query)) {
            $query->set('date_query', [$date_query]);
        }
    }
});

/**
 * Автоматична заміна старого тексту в title review постів
 */
add_action('save_post', function($post_id) {
    $post_type = get_post_type($post_id);
    
    if ($post_type !== 'review') {
        return;
    }
    
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    
    if (wp_is_post_revision($post_id)) {
        return;
    }
    
    $old_text = 'Отзыв о работе АН Практик от';
    $new_text = 'Відгук про роботу АН Практик від';
    
    $current_title = get_the_title($post_id);
    
    if (strpos($current_title, $old_text) !== false) {
        $new_title = str_replace($old_text, $new_text, $current_title);
        
        remove_action('save_post', __FUNCTION__);
        wp_update_post([
            'ID' => $post_id,
            'post_title' => $new_title,
        ]);
        add_action('save_post', __FUNCTION__);
    }
}, 10, 1);
