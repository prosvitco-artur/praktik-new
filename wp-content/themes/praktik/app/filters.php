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
    return sprintf(' &hellip; <a href="%s">%s</a>', get_permalink(), __('Continued', 'sage'));
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
