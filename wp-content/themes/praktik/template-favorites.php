<?php

$favorites_ids = get_user_favorites();

$favorites_posts = [];
if (!empty($favorites_ids)) {
    $favorites_ids_int = array_map('intval', $favorites_ids);
    $favorites_posts = get_posts([
        'post_type' => array_keys(get_property_post_types()),
        'post__in' => $favorites_ids_int,
        'posts_per_page' => -1,
        'orderby' => 'post__in',
    ]);
}

echo \Roots\view('template-favorites', [
    'favorites' => $favorites_posts,
    'has_favorites' => !empty($favorites_posts),
    'favorite_page_banner' => carbon_get_theme_option('favorite_page_banner') ?? 2845,
])->render();

