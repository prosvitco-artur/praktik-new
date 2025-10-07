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

/**
 * Додати мета бокс для нерухомості
 */
add_action('add_meta_boxes', function () {
    $property_post_types = array_keys(get_property_post_types());
    
    foreach ($property_post_types as $post_type) {
        add_meta_box(
            'property_details',
            __('Property Details', 'praktik'),
            'App\render_property_meta_box',
            $post_type,
            'normal',
            'high'
        );
    }
});

/**
 * Рендер мета боксу для нерухомості
 */
function render_property_meta_box($post) {
    wp_nonce_field('property_meta_box', 'property_meta_box_nonce');
    
    $meta_fields = [
        'property_price' => __('Price', 'praktik'),
        'property_city' => __('City', 'praktik'),
        'property_district' => __('District', 'praktik'),
        'property_street' => __('Street', 'praktik'),
        'property_rooms' => __('Number of Rooms', 'praktik'),
        'property_area' => __('Total Area (m²)', 'praktik'),
        'property_floor' => __('Floor', 'praktik'),
        'property_total_floors' => __('Total Floors', 'praktik'),
        'property_year_built' => __('Year Built', 'praktik'),
        'property_condition' => __('Condition', 'praktik'),
        'property_furniture' => __('Furniture', 'praktik'),
        'property_heating' => __('Heating', 'praktik'),
        'property_parking' => __('Parking', 'praktik'),
        'property_balcony' => __('Balcony', 'praktik'),
        'property_elevator' => __('Elevator', 'praktik'),
        'property_photos_count' => __('Photos Count', 'praktik'),
    ];
    
    echo '<table class="form-table">';
    
    foreach ($meta_fields as $field_key => $field_label) {
        $value = get_post_meta($post->ID, $field_key, true);
        
        echo '<tr>';
        echo '<th scope="row"><label for="' . esc_attr($field_key) . '">' . esc_html($field_label) . '</label></th>';
        echo '<td>';
        
        if ($field_key === 'property_condition') {
            $conditions = [
                'excellent' => __('Excellent', 'praktik'),
                'good' => __('Good', 'praktik'),
                'fair' => __('Fair', 'praktik'),
                'needs_renovation' => __('Needs Renovation', 'praktik'),
            ];
            
            echo '<select name="' . esc_attr($field_key) . '" id="' . esc_attr($field_key) . '">';
            echo '<option value="">' . __('Select Condition', 'praktik') . '</option>';
            
            foreach ($conditions as $condition_value => $condition_label) {
                $selected = selected($value, $condition_value, false);
                echo '<option value="' . esc_attr($condition_value) . '"' . $selected . '>' . esc_html($condition_label) . '</option>';
            }
            
            echo '</select>';
        } elseif ($field_key === 'property_furniture') {
            $furniture_options = [
                'furnished' => __('Furnished', 'praktik'),
                'semi_furnished' => __('Semi-furnished', 'praktik'),
                'unfurnished' => __('Unfurnished', 'praktik'),
            ];
            
            echo '<select name="' . esc_attr($field_key) . '" id="' . esc_attr($field_key) . '">';
            echo '<option value="">' . __('Select Furniture', 'praktik') . '</option>';
            
            foreach ($furniture_options as $furniture_value => $furniture_label) {
                $selected = selected($value, $furniture_value, false);
                echo '<option value="' . esc_attr($furniture_value) . '"' . $selected . '>' . esc_html($furniture_label) . '</option>';
            }
            
            echo '</select>';
        } elseif (in_array($field_key, ['property_parking', 'property_balcony', 'property_elevator'])) {
            $checked = checked($value, '1', false);
            echo '<input type="checkbox" name="' . esc_attr($field_key) . '" id="' . esc_attr($field_key) . '" value="1"' . $checked . '>';
            echo '<label for="' . esc_attr($field_key) . '">' . __('Yes', 'praktik') . '</label>';
        } else {
            $input_type = in_array($field_key, ['property_price', 'property_area', 'property_floor', 'property_total_floors', 'property_year_built', 'property_photos_count']) ? 'number' : 'text';
            echo '<input type="' . esc_attr($input_type) . '" name="' . esc_attr($field_key) . '" id="' . esc_attr($field_key) . '" value="' . esc_attr($value) . '" class="regular-text" />';
        }
        
        echo '</td>';
        echo '</tr>';
    }
    
    echo '</table>';
}

/**
 * Збереження мета полів нерухомості
 */
add_action('save_post', function ($post_id) {
    $property_post_types = array_keys(get_property_post_types());
    
    if (!in_array(get_post_type($post_id), $property_post_types)) {
        return;
    }
    
    if (!isset($_POST['property_meta_box_nonce']) || !wp_verify_nonce($_POST['property_meta_box_nonce'], 'property_meta_box')) {
        return;
    }
    
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }
    
    $meta_fields = [
        'property_price',
        'property_city',
        'property_district',
        'property_street',
        'property_rooms',
        'property_area',
        'property_floor',
        'property_total_floors',
        'property_year_built',
        'property_condition',
        'property_furniture',
        'property_heating',
        'property_parking',
        'property_balcony',
        'property_elevator',
        'property_photos_count',
    ];
    
    foreach ($meta_fields as $field) {
        if (isset($_POST[$field])) {
            $value = sanitize_text_field($_POST[$field]);
            
            if (in_array($field, ['property_parking', 'property_balcony', 'property_elevator'])) {
                $value = isset($_POST[$field]) ? '1' : '0';
            }
            
            update_post_meta($post_id, $field, $value);
        } else {
            if (in_array($field, ['property_parking', 'property_balcony', 'property_elevator'])) {
                update_post_meta($post_id, $field, '0');
            }
        }
    }
});
