<?php

$share_token = get_query_var('favorites_share');
$shared_favorites_ids = \App\get_shared_favorites($share_token);

if (empty($shared_favorites_ids)) {
    global $wp_query;
    $wp_query->set_404();
    status_header(404);
    include(get_404_template());
    exit;
}

$favorites_posts = [];
if (!empty($shared_favorites_ids)) {
    $favorites_ids_int = array_map('intval', $shared_favorites_ids);
    
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

echo \Roots\view('template-favorites-share', [
    'favorites' => $favorites_posts,
    'has_favorites' => !empty($favorites_posts),
    'favorite_page_banner' => carbon_get_theme_option('favorite_page_banner') ?? 2845,
    'share_token' => $share_token,
])->render();

