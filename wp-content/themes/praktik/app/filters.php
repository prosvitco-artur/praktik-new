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
        if (is_post_type_archive() && isset($_GET['search']) && !empty($_GET['search'])) {
            $query->set('s', sanitize_text_field($_GET['search']));
        }
    }
});

/**
 * Get property categories for filter dropdown
 *
 * @return array
 */
function get_property_categories() {
    return [
        'apartments' => __('Квартири', 'praktik'),
        'houses' => __('Будинки', 'praktik'),
        'commercial' => __('Комерційна нерухомість', 'praktik'),
        'land' => __('Земельні ділянки', 'praktik'),
    ];
}

/**
 * Get property types for filter dropdown
 *
 * @return array
 */
function get_property_types() {
    return [
        'new' => __('Новобудова', 'praktik'),
        'secondary' => __('Вторинний ринок', 'praktik'),
        'rent' => __('Оренда', 'praktik'),
    ];
}

/**
 * Get room counts for filter dropdown
 *
 * @return array
 */
function get_room_counts() {
    return [
        'all' => __('Всі', 'praktik'),
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
    return [
        '0-20' => __('До 20 м²', 'praktik'),
        '20-50' => __('20-50 м²', 'praktik'),
        '50-100' => __('50-100 м²', 'praktik'),
        '100-150' => __('100-150 м²', 'praktik'),
        '150+' => __('Понад 150 м²', 'praktik'),
    ];
}

/**
 * Get price ranges for filter dropdown
 *
 * @return array
 */
function get_price_ranges() {
    return [
        '0-50000' => __('До $50,000', 'praktik'),
        '50000-100000' => __('$50,000 - $100,000', 'praktik'),
        '100000-200000' => __('$100,000 - $200,000', 'praktik'),
        '200000-500000' => __('$200,000 - $500,000', 'praktik'),
        '500000+' => __('Понад $500,000', 'praktik'),
    ];
}
