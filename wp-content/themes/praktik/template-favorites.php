<?php

$favorites_cookie = isset($_COOKIE['praktikFavorites']) ? $_COOKIE['praktikFavorites'] : '';
$favorites_ids = [];

if (!empty($favorites_cookie)) {
    $favorites_ids = array_filter(array_map('intval', explode(',', $favorites_cookie)));
}

$favorites_posts = [];
if (!empty($favorites_ids)) {
    $favorites_posts = get_posts([
        'post_type' => array_keys(get_property_post_types()),
        'post__in' => $favorites_ids,
        'posts_per_page' => -1,
        'orderby' => 'post__in',
    ]);
}

echo \Roots\view('template-favorites', [
    'favorites' => $favorites_posts,
    'has_favorites' => !empty($favorites_posts),
])->render();
