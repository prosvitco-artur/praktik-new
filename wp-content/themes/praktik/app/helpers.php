<?php

function get_property_post_types() {
    return [
        'apartment' => __('Apartments', 'praktik'),
        'house' => __('Houses', 'praktik'),
    ];
}

function praktik_get_pagination_params(array $options = []): array {
    $persist_keys = isset($options['persist_keys']) && is_array($options['persist_keys'])
        ? $options['persist_keys']
        : [];

    $current = max(1, (int) (get_query_var('paged') ?: get_query_var('page') ?: 1));
    $total = max(1, (int) ($GLOBALS['wp_query']->max_num_pages ?? 1));

    $query_args = [];
    if (!empty($persist_keys)) {
        foreach ($persist_keys as $key) {
            if (isset($_GET[$key]) && !is_array($_GET[$key])) {
                $query_args[$key] = sanitize_text_field($_GET[$key]);
            }
        }
    }

    return [
        'current' => $current,
        'total' => $total,
        'query_args' => $query_args,
        'end_size' => $options['end_size'] ?? 3,
        'mid_size' => $options['mid_size'] ?? 1,
    ];
}

function svg_sprite_path() {
    return get_template_directory() . '/resources/images/icons';
}

function get_svg_icons() {
    $icons_path = svg_sprite_path();
    
    if (!file_exists($icons_path)) {
        return [];
    }
    
    $icons = [];
    $files = glob($icons_path . '/*.svg');
    
    foreach ($files as $file) {
        $icon_name = basename($file, '.svg');
        if (!file_exists($file)) {
            continue;
        }
        $content = file_get_contents($file);
        
        if (preg_match('/<svg[^>]*>(.*?)<\/svg>/is', $content, $matches)) {
            $svg_content = $matches[1];
            
            if (preg_match('/<svg[^>]*viewBox=["\']([^"\']+)["\'][^>]*>/i', $content, $viewbox_matches)) {
                $viewbox = $viewbox_matches[1];
            } else {
                $viewbox = '0 0 24 24';
            }
            
            $icons[$icon_name] = [
                'content' => $svg_content,
                'viewbox' => $viewbox
            ];
        }
    }
    
    return $icons;
}

function generate_svg_sprite() {
    $icons = get_svg_icons();
    
    if (empty($icons)) {
        return '';
    }
    
    $sprite = '<svg xmlns="http://www.w3.org/2000/svg" style="display: none;">';
    
    foreach ($icons as $name => $data) {
        $sprite .= sprintf(
            '<symbol id="icon-%s" viewBox="%s">%s</symbol>',
            esc_attr($name),
            esc_attr($data['viewbox']),
            $data['content']
        );
    }
    
    $sprite .= '</svg>';
    
    return $sprite;
}

function svg_icon($name, $class = '', $attr = []) {
    $classes = ['icon', "icon-{$name}"];
    
    if ($class) {
        $classes[] = $class;
    }
    
    $attributes = [
        'class' => implode(' ', $classes),
        'aria-hidden' => 'true',
        'focusable' => 'false'
    ];
    
    $attributes = array_merge($attributes, $attr);
    
    $attr_string = '';
    foreach ($attributes as $key => $value) {
        $attr_string .= sprintf(' %s="%s"', esc_attr($key), esc_attr($value));
    }
    
    return sprintf(
        '<svg%s><use href="#icon-%s"></use></svg>',
        $attr_string,
        esc_attr($name)
    );
}

add_action('wp_footer', function() {
    echo generate_svg_sprite();
}, 999);

add_action('admin_footer', function() {
    echo generate_svg_sprite();
}, 999);
function get_property_meta($post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    
    return [
        'price' => carbon_get_post_meta($post_id, 'property_price'),
        'city' => carbon_get_post_meta($post_id, 'property_city'),
        'district' => carbon_get_post_meta($post_id, 'property_district'),
        'street' => carbon_get_post_meta($post_id, 'property_street'),
        'rooms' => carbon_get_post_meta($post_id, 'property_rooms'),
        'area' => carbon_get_post_meta($post_id, 'property_area'),
        'plot_area' => carbon_get_post_meta($post_id, 'property_plot_area'),
        'photos_count' => carbon_get_post_meta($post_id, 'property_photos_count') ?: 1,
        'floor' => carbon_get_post_meta($post_id, 'property_floor'),
        'total_floors' => carbon_get_post_meta($post_id, 'property_total_floors'),
        'year_built' => carbon_get_post_meta($post_id, 'property_year_built'),
        'condition' => carbon_get_post_meta($post_id, 'property_condition'),
        'furniture' => carbon_get_post_meta($post_id, 'property_furniture'),
        'heating' => carbon_get_post_meta($post_id, 'property_heating'),
        'parking' => carbon_get_post_meta($post_id, 'property_parking'),
        'balcony' => carbon_get_post_meta($post_id, 'property_balcony'),
        'elevator' => carbon_get_post_meta($post_id, 'property_elevator'),
    ];
}

/**
 * Форматувати ціну нерухомості
 */
function format_property_price($price) {
    if (!$price) return '';
    
    $formatted_price = number_format($price, 0, ',', ' ');
    $currency_symbol = apply_filters('praktik_currency_symbol', '$');
    return $currency_symbol . ' ' . $formatted_price;
}

/**
 * Get property type label in current language
 */
function get_property_type_label($post_type) {
    $types = [
        'room' => __('Room', 'praktik'),
        'apartment' => __('Apartment', 'praktik'),
        'house' => __('House', 'praktik'),
        'plot' => __('Plot', 'praktik'),
        'garage' => __('Garage', 'praktik'),
        'commercial' => __('Commercial Property', 'praktik'),
        'dacha' => __('Dacha', 'praktik')
    ];
    
    return $types[$post_type] ?? ucfirst($post_type);
}

function get_property_gallery($post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    
    $gallery_ids = carbon_get_post_meta($post_id, 'property_gallery');
    
    if (!is_array($gallery_ids) || empty($gallery_ids)) {
        return [];
    }
    
    $gallery = [];
    
    foreach ($gallery_ids as $image_id) {
        if ($image_id) {
            $image_data = [
                'id' => $image_id,
                'url' => wp_get_attachment_image_url($image_id, 'large'),
                'thumbnail' => wp_get_attachment_image_url($image_id, 'medium'),
                'alt' => get_post_meta($image_id, '_wp_attachment_image_alt', true),
                'title' => get_the_title($image_id),
            ];
            
            if ($image_data['url']) {
                $gallery[] = $image_data;
            }
        }
    }
    
    return $gallery;
}

/**
 * Отримати кількість зображень в галереї
 */
function get_property_gallery_count($post_id = null) {
    $gallery = get_property_gallery($post_id);
    return count($gallery);
}

function get_social_links() {
    return [
        'telegram' => carbon_get_theme_option('social_telegram'),
        'youtube' => carbon_get_theme_option('social_youtube'),
        'instagram' => carbon_get_theme_option('social_instagram'),
        'tiktok' => carbon_get_theme_option('social_tiktok'),
        'facebook' => carbon_get_theme_option('social_facebook'),
    ];
}

function has_social_links() {
    $social_links = get_social_links();
    return !empty(array_filter($social_links));
}

function get_sort_options() {
    return [
        'date_desc' => __('Newest First', 'praktik'),
        'date_asc' => __('Oldest First', 'praktik'),
        'price_desc' => __('Price: High to Low', 'praktik'),
        'price_asc' => __('Price: Low to High', 'praktik'),
    ];
}

function get_current_sort() {
    return $_GET['sort'] ?? 'date_desc';
}

function get_sort_label($sort_key) {
    $options = get_sort_options();
    return $options[$sort_key] ?? $options['date_desc'];
}

function get_user_favorites() {
    $is_share_page = get_query_var('favorites_share');
    
    $allow_cookie = true;
    
    $session_id = null;
    if ($allow_cookie) {
        $session_id = isset($_COOKIE['praktik_favorites_session_id']) ? sanitize_text_field($_COOKIE['praktik_favorites_session_id']) : '';
    }
    
    return \App\get_stored_favorites($session_id, $allow_cookie);
}

function get_user_favorites_count() {
    $favorites = get_user_favorites();
    return count($favorites);
}

function get_property_area_range($post_type = null) {
    global $wpdb;
    if (!function_exists('get_property_post_types')) {
        return ['min' => 0, 'max' => 1000];
    }
    
    if ($post_type !== null) {
        if (is_array($post_type)) {
            $property_post_types = $post_type;
        } else {
            $property_post_types = [$post_type];
        }
    } else {
        $property_post_types = array_keys(get_property_post_types());
    }
    
    if (empty($property_post_types)) {
        return ['min' => 0, 'max' => 1000];
    }
    
    $post_types_sanitized = array_map('esc_sql', $property_post_types);
    $post_types_string = "'" . implode("','", $post_types_sanitized) . "'";
    
    $query = "
        SELECT pm.meta_value
        FROM {$wpdb->postmeta} pm
        INNER JOIN {$wpdb->posts} p ON pm.post_id = p.ID
        WHERE pm.meta_key = '_property_area'
        AND p.post_type IN ({$post_types_string})
        AND p.post_status = 'publish'
        AND pm.meta_value != ''
        AND pm.meta_value IS NOT NULL
        AND TRIM(pm.meta_value) != ''
    ";
    
    $results = $wpdb->get_col($query);
    
    if ($wpdb->last_error) {
        return ['min' => 0, 'max' => 1000];
    }
    
    if (empty($results)) {
        return ['min' => 0, 'max' => 1000];
    }
    
    $values = [];
    foreach ($results as $value) {
        $value = trim($value);
        if ($value === '' || $value === null) {
            continue;
        }
        $numeric_value = floatval($value);
        if ($numeric_value > 0) {
            $values[] = $numeric_value;
        }
    }
    
    if (empty($values)) {
        return ['min' => 0, 'max' => 1000];
    }
    
    $min = floor(min($values));
    $max = ceil(max($values));
    
    if ($min === 0 && $max === 0) {
        $max = 1000;
    }

    
    return [
        'min' => 0,
        'max' => (int)$max
    ];
}

/**
 * Отримати мінімальне та максимальне значення ціни з meta полів
 *
 * @param string|array|null $post_type Конкретний тип посту або масив типів. Якщо null, використовує всі типи постів нерухомості.
 * @return array ['min' => int, 'max' => int]
 */
function get_property_price_range($post_type = null) {
    global $wpdb;
    
    if (!function_exists('get_property_post_types')) {
        return ['min' => 0, 'max' => 1000000];
    }
    
    if ($post_type !== null) {
        if (is_array($post_type)) {
            $property_post_types = $post_type;
        } else {
            $property_post_types = [$post_type];
        }
    } else {
        $property_post_types = array_keys(get_property_post_types());
    }
    
    if (empty($property_post_types)) {
        return ['min' => 0, 'max' => 1000000];
    }
    
    $post_types_sanitized = array_map('esc_sql', $property_post_types);
    $post_types_string = "'" . implode("','", $post_types_sanitized) . "'";
    
    $query = "
        SELECT pm.meta_value
        FROM {$wpdb->postmeta} pm
        INNER JOIN {$wpdb->posts} p ON pm.post_id = p.ID
        WHERE pm.meta_key = '_property_price'
        AND p.post_type IN ({$post_types_string})
        AND p.post_status = 'publish'
        AND pm.meta_value != ''
        AND pm.meta_value IS NOT NULL
        AND TRIM(pm.meta_value) != ''
    ";
    
    $results = $wpdb->get_col($query);
    
    if ($wpdb->last_error) {
        return ['min' => 0, 'max' => 1000000];
    }
    
    if (empty($results)) {
        return ['min' => 0, 'max' => 1000000];
    }
    
    $values = [];
    foreach ($results as $value) {
        $value = trim($value);
        if ($value === '' || $value === null) {
            continue;
        }
        $numeric_value = floatval($value);
        if ($numeric_value > 0) {
            $values[] = $numeric_value;
        }
    }
    
    if (empty($values)) {
        return ['min' => 0, 'max' => 1000000];
    }
    
    $min = floor(min($values));
    $max = ceil(max($values));
    
    if ($min === 0 && $max === 0) {
        $max = 1000000;
    }
    
    return [
        'min' => 0,
        'max' => (int)$max
    ];
}

/**
 * Отримати мінімальне та максимальне значення площі ділянки з meta полів
 *
 * @return array ['min' => int, 'max' => int]
 */
function get_property_plot_area_range() {
    global $wpdb;
    
    $query = "
        SELECT pm.meta_value
        FROM {$wpdb->postmeta} pm
        INNER JOIN {$wpdb->posts} p ON pm.post_id = p.ID
        WHERE pm.meta_key = '_property_plot_area'
        AND p.post_type = 'house'
        AND p.post_status = 'publish'
        AND pm.meta_value != ''
        AND pm.meta_value IS NOT NULL
        AND TRIM(pm.meta_value) != ''
    ";
    
    $results = $wpdb->get_col($query);
    
    if ($wpdb->last_error) {
        return ['min' => 0, 'max' => 1000];
    }
    
    if (empty($results)) {
        return ['min' => 0, 'max' => 1000];
    }
    
    $values = [];
    foreach ($results as $value) {
        $value = trim($value);
        if ($value === '' || $value === null) {
            continue;
        }
        $numeric_value = floatval($value);
        if ($numeric_value > 0) {
            $values[] = $numeric_value;
        }
    }
    
    if (empty($values)) {
        return ['min' => 0, 'max' => 1000];
    }
    
    $min = floor(min($values));
    $max = ceil(max($values));
    
    if ($min === 0 && $max === 0) {
        $max = 1000;
    }
    
    return [
        'min' => 0,
        'max' => (int)$max
    ];
}