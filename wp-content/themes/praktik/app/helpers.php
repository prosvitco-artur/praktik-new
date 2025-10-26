<?php

function get_property_post_types() {
    return [
        'room' => __('Rooms', 'praktik'),
        'apartment' => __('Apartments', 'praktik'),
        'house' => __('Houses', 'praktik'),
        'plot' => __('Plots', 'praktik'),
        'garage' => __('Garages', 'praktik'),
        'commercial' => __('Commercial', 'praktik'),
        'dacha' => __('Dachas', 'praktik')
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
