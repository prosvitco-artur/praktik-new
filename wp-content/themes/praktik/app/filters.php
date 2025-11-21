<?php

/**
 * Theme filters.
 */

namespace App;

/**
 * Add "… Continued" to the excerpt.
 *
 * @return string
 */
add_filter('excerpt_more', function () {
    return sprintf(' &hellip; <a href="%s">%s</a>', get_permalink(), __('Continued', 'praktik'));
});

add_filter('get_the_archive_title', function ($title) {
    if (is_post_type_archive()) {
        $title = post_type_archive_title('', false);
    } elseif (is_category()) {
        $title = single_cat_title('', false);
    } elseif (is_tag()) {
        $title = single_tag_title('', false);
    } elseif (is_tax()) {
        $title = single_term_title('', false);
    } elseif (is_author()) {
        $title = get_the_author();
    } elseif (is_date()) {
        if (is_day()) {
            $title = get_the_date();
        } elseif (is_month()) {
            $title = get_the_date('F Y');
        } elseif (is_year()) {
            $title = get_the_date('Y');
        }
    }
    
    return $title;
});

add_action('pre_get_posts', function ($query) {
    if (!is_admin() && $query->is_main_query()) {
        if (is_post_type_archive()) {
            // Search query
            if (isset($_GET['search']) && !empty($_GET['search'])) {
                $query->set('s', sanitize_text_field($_GET['search']));
            }
            
            // Build meta query for filters
            $meta_query = ['relation' => 'AND'];
            
            // Price range filter
            if (isset($_GET['price_from']) && !empty($_GET['price_from'])) {
                $price_from = intval($_GET['price_from']);
                if ($price_from > 0) {
                    $meta_query[] = [
                        'key' => '_property_price',
                        'value' => $price_from,
                        'compare' => '>=',
                        'type' => 'NUMERIC'
                    ];
                }
            }
            
            if (isset($_GET['price_to']) && !empty($_GET['price_to'])) {
                $price_to = intval($_GET['price_to']);
                if ($price_to > 0) {
                    $meta_query[] = [
                        'key' => '_property_price',
                        'value' => $price_to,
                        'compare' => '<=',
                        'type' => 'NUMERIC'
                    ];
                }
            }
            
            // Area range filter
            if (isset($_GET['area_from']) && !empty($_GET['area_from'])) {
                $area_from = intval($_GET['area_from']);
                if ($area_from > 0) {
                    $meta_query[] = [
                        'key' => '_property_area',
                        'value' => $area_from,
                        'compare' => '>=',
                        'type' => 'NUMERIC'
                    ];
                }
            }
            
            if (isset($_GET['area_to']) && !empty($_GET['area_to'])) {
                $area_to = intval($_GET['area_to']);
                if ($area_to > 0) {
                    $meta_query[] = [
                        'key' => '_property_area',
                        'value' => $area_to,
                        'compare' => '<=',
                        'type' => 'NUMERIC'
                    ];
                }
            }
            
            // Rooms filter
            if (isset($_GET['rooms']) && !empty($_GET['rooms'])) {
                $rooms = explode(',', sanitize_text_field($_GET['rooms']));
                $rooms = array_map('trim', $rooms);
                
                if (!empty($rooms)) {
                    $rooms_query = ['relation' => 'OR'];
                    foreach ($rooms as $room) {
                        if ($room === '4+' || $room === '5+') {
                            $min_value = $room === '5+' ? 5 : 4;
                            $rooms_query[] = [
                                'key' => '_property_rooms',
                                'value' => $min_value,
                                'compare' => '>=',
                                'type' => 'NUMERIC'
                            ];
                        } else {
                            $room_int = intval($room);
                            if ($room_int > 0) {
                                $rooms_query[] = [
                                    'key' => '_property_rooms',
                                    'value' => $room_int,
                                    'compare' => '=',
                                    'type' => 'NUMERIC'
                                ];
                            }
                        }
                    }
                    if (count($rooms_query) > 1) {
                        $meta_query[] = $rooms_query;
                    }
                }
            }
            
            // Property type filter (new/secondary)
            if (isset($_GET['type']) && !empty($_GET['type'])) {
                $property_type = sanitize_text_field($_GET['type']);
                $meta_query[] = [
                    'key' => '_property_type',
                    'value' => $property_type,
                    'compare' => '='
                ];
            }
            
            // Apply meta query if we have filters
            if (count($meta_query) > 1) {
                $query->set('meta_query', $meta_query);
            }
            
            // Sort functionality
            $sort = isset($_GET['sort']) ? sanitize_text_field($_GET['sort']) : 'date_desc';
            
            switch ($sort) {
                case 'date_asc':
                    $query->set('orderby', 'date');
                    $query->set('order', 'ASC');
                    break;
                    
                case 'date_desc':
                    $query->set('orderby', 'date');
                    $query->set('order', 'DESC');
                    break;
                    
                case 'price_asc':
                    $query->set('meta_key', '_property_price');
                    $query->set('orderby', 'meta_value_num');
                    $query->set('order', 'ASC');
                    break;
                    
                case 'price_desc':
                    $query->set('meta_key', '_property_price');
                    $query->set('orderby', 'meta_value_num');
                    $query->set('order', 'DESC');
                    break;
                    
                case 'area_asc':
                    $query->set('meta_key', '_property_area');
                    $query->set('orderby', 'meta_value_num');
                    $query->set('order', 'ASC');
                    break;
                    
                case 'area_desc':
                    $query->set('meta_key', '_property_area');
                    $query->set('orderby', 'meta_value_num');
                    $query->set('order', 'DESC');
                    break;
                    
                default:
                    $query->set('orderby', 'date');
                    $query->set('order', 'DESC');
                    break;
            }
        }
    }
});

/**
 * Get property types for filter dropdown
 *
 * @return array
 */
function get_property_types() {
    return [
        'new' => __('New building', 'praktik'),
        'secondary' => __('Secondary market', 'praktik'),
    ];
}

/**
 * Get room counts for filter dropdown
 *
 * @return array
 */
function get_room_counts() {
    return [
        '1' => '1',
        '2' => '2',
        '3' => '3',
        '4' => '4',
        '5+' => '5+',
    ];
}

/**
 * Get area ranges for filter dropdown
 *
 * @return array
 */
function get_area_ranges() {
    return [ 20, 30, 40, 50, 60, 70, 80, 90, 100];
}

/**
 * 
 */

 function get_plot_area_ranges() {
    return [ 10, 20, 30, 40, 50, 60, 70, 80, 90, 100];
}