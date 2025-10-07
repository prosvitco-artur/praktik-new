<?php

function get_property_post_types() {
    return [
        'room' => __('Rooms', 'sage'),
        'apartment' => __('Apartments', 'sage'),
        'house' => __('Houses', 'sage'),
        'plot' => __('Plots', 'sage'),
        'garage' => __('Garages', 'sage'),
        'commercial' => __('Commercial', 'sage'),
        'dacha' => __('Dachas', 'sage')
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
        'price' => get_post_meta($post_id, 'property_price', true),
        'city' => get_post_meta($post_id, 'property_city', true),
        'district' => get_post_meta($post_id, 'property_district', true),
        'street' => get_post_meta($post_id, 'property_street', true),
        'rooms' => get_post_meta($post_id, 'property_rooms', true),
        'area' => get_post_meta($post_id, 'property_area', true),
        'photos_count' => get_post_meta($post_id, 'property_photos_count', true) ?: 1,
        'floor' => get_post_meta($post_id, 'property_floor', true),
        'total_floors' => get_post_meta($post_id, 'property_total_floors', true),
        'year_built' => get_post_meta($post_id, 'property_year_built', true),
        'condition' => get_post_meta($post_id, 'property_condition', true),
        'furniture' => get_post_meta($post_id, 'property_furniture', true),
        'heating' => get_post_meta($post_id, 'property_heating', true),
        'parking' => get_post_meta($post_id, 'property_parking', true),
        'balcony' => get_post_meta($post_id, 'property_balcony', true),
        'elevator' => get_post_meta($post_id, 'property_elevator', true),
    ];
}

/**
 * Форматувати ціну нерухомості
 */
function format_property_price($price) {
    if (!$price) return '';
    
    $formatted_price = number_format($price, 0, ',', ' ');
    return '$ ' . $formatted_price;
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
