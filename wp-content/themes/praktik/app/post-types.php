<?php

namespace App;

use Carbon_Fields\Container;
use Carbon_Fields\Field;

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
    $property_post_types = array_keys(get_property_post_types());
    
    Container::make('post_meta', __('Property Details', 'praktik'))
        ->where('post_type', 'IN', $property_post_types)
        ->add_tab(__('Basic Information', 'praktik'), [
            Field::make('text', 'property_price', __('Price', 'praktik'))
                ->set_attribute('type', 'number')
                ->set_attribute('step', '1')
                ->set_help_text(__('Price in USD', 'praktik')),
            
            Field::make('text', 'property_city', __('City', 'praktik')),
            
            Field::make('text', 'property_district', __('District', 'praktik')),
            
            Field::make('text', 'property_street', __('Street', 'praktik')),
            
            Field::make('text', 'property_rooms', __('Number of Rooms', 'praktik'))
                ->set_attribute('type', 'number')
                ->set_attribute('min', '0'),
            
            Field::make('text', 'property_area', __('Total Area (m²)', 'praktik'))
                ->set_attribute('type', 'number')
                ->set_attribute('step', '0.01')
                ->set_attribute('min', '0'),
        ])
        ->add_tab(__('Building Details', 'praktik'), [
            Field::make('text', 'property_floor', __('Floor', 'praktik'))
                ->set_attribute('type', 'number')
                ->set_attribute('min', '0'),
            
            Field::make('text', 'property_total_floors', __('Total Floors', 'praktik'))
                ->set_attribute('type', 'number')
                ->set_attribute('min', '1'),
            
            Field::make('text', 'property_year_built', __('Year Built', 'praktik'))
                ->set_attribute('type', 'number')
                ->set_attribute('min', '1800')
                ->set_attribute('max', date('Y')),
            
            Field::make('select', 'property_condition', __('Condition', 'praktik'))
                ->set_options([
                    '' => __('Select Condition', 'praktik'),
                    'excellent' => __('Excellent', 'praktik'),
                    'good' => __('Good', 'praktik'),
                    'fair' => __('Fair', 'praktik'),
                    'needs_renovation' => __('Needs Renovation', 'praktik'),
                ]),
            
            Field::make('select', 'property_furniture', __('Furniture', 'praktik'))
                ->set_options([
                    '' => __('Select Furniture', 'praktik'),
                    'furnished' => __('Furnished', 'praktik'),
                    'semi_furnished' => __('Semi-furnished', 'praktik'),
                    'unfurnished' => __('Unfurnished', 'praktik'),
                ]),
            
            Field::make('text', 'property_heating', __('Heating', 'praktik')),
        ])
        ->add_tab(__('Amenities', 'praktik'), [
            Field::make('checkbox', 'property_parking', __('Parking', 'praktik')),
            
            Field::make('checkbox', 'property_balcony', __('Balcony', 'praktik')),
            
            Field::make('checkbox', 'property_elevator', __('Elevator', 'praktik')),
        ])
        ->add_tab(__('Gallery', 'praktik'), [
            Field::make('media_gallery', 'property_gallery', __('Property Gallery', 'praktik'))
                ->set_help_text(__('Add property images', 'praktik')),
            
            Field::make('text', 'property_photos_count', __('Photos Count', 'praktik'))
                ->set_attribute('type', 'number')
                ->set_attribute('min', '0')
                ->set_help_text(__('Manual photo counter (optional)', 'praktik')),
        ]);
    
    Container::make('post_meta', __('Property Details', 'praktik'))
        ->where('post_type', '=', 'house')
        ->add_tab(__('Basic Information', 'praktik'), [
            Field::make('text', 'property_plot_area', __('Plot Area (m²)', 'praktik'))
                ->set_attribute('type', 'number')
                ->set_attribute('step', '0.01')
                ->set_attribute('min', '0')
                ->set_help_text(__('Area of the plot', 'praktik')),
        ]);
});

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
