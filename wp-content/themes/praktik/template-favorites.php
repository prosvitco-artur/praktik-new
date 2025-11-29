<?php

$favorites_ids = get_user_favorites();

$favorites_posts = [];
if (!empty($favorites_ids)) {
    $favorites_ids_int = array_map('intval', $favorites_ids);
    
    $sort = isset($_GET['sort']) ? sanitize_text_field($_GET['sort']) : 'date_desc';
    
    $query_args = [
        'post_type' => array_keys(get_property_post_types()),
        'post__in' => $favorites_ids_int,
        'posts_per_page' => -1,
    ];
    
    switch ($sort) {
        case 'date_asc':
            $query_args['orderby'] = 'date';
            $query_args['order'] = 'ASC';
            break;
            
        case 'date_desc':
            $query_args['orderby'] = 'date';
            $query_args['order'] = 'DESC';
            break;
            
        case 'price_asc':
            $query_args['meta_key'] = '_property_price';
            $query_args['orderby'] = 'meta_value_num';
            $query_args['order'] = 'ASC';
            break;
            
        case 'price_desc':
            $query_args['meta_key'] = '_property_price';
            $query_args['orderby'] = 'meta_value_num';
            $query_args['order'] = 'DESC';
            break;
            
        default:
            $query_args['orderby'] = 'date';
            $query_args['order'] = 'DESC';
            break;
    }
    
    $favorites_posts = get_posts($query_args);
}

echo \Roots\view('template-favorites', [
    'favorites' => $favorites_posts,
    'has_favorites' => !empty($favorites_posts),
    'favorite_page_banner' => carbon_get_theme_option('favorite_page_banner') ?? 2845,
])->render();

