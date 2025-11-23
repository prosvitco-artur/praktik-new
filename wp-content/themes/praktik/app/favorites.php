<?php

namespace App;

add_action('wp_ajax_toggle_favorite', __NAMESPACE__ . '\\handle_toggle_favorite');
add_action('wp_ajax_nopriv_toggle_favorite', __NAMESPACE__ . '\\handle_toggle_favorite');
add_action('wp_ajax_clean_favorites', __NAMESPACE__ . '\\handle_clean_favorites');
add_action('wp_ajax_nopriv_clean_favorites', __NAMESPACE__ . '\\handle_clean_favorites');

function handle_toggle_favorite() {
    
    $post_id = isset($_POST['post_id']) ? intval($_POST['post_id']) : 0;
    
    if (!$post_id || !get_post($post_id)) {
        wp_send_json_error([
            'message' => __('Invalid post ID.', 'praktik')
        ]);
    }
    
    $favorites = clean_user_favorites();
    $post_id_str = (string) $post_id;
    $is_favorite = in_array($post_id_str, $favorites);
    
    if ($is_favorite) {
        $favorites = array_values(array_filter($favorites, function($id) use ($post_id_str) {
            return $id !== $post_id_str;
        }));
    } else {
        $favorites[] = $post_id_str;
    }
    
    $favorites = array_values(array_unique($favorites));
    
    $expires = time() + (365 * 24 * 60 * 60);
    if (empty($favorites)) {
        setcookie('praktik_favorites', '', time() - 3600, '/', '', is_ssl(), true);
        unset($_COOKIE['praktik_favorites']);
    } else {
        setcookie('praktik_favorites', json_encode($favorites), $expires, '/', '', is_ssl(), true);
        $_COOKIE['praktik_favorites'] = json_encode($favorites);
    }
    
    wp_send_json_success([
        'post_id' => $post_id,
        'is_favorite' => !$is_favorite,
        'favorites' => $favorites
    ]);
}

function handle_clean_favorites() {
    $cleaned_favorites = clean_user_favorites();
    
    wp_send_json_success([
        'favorites' => $cleaned_favorites,
        'count' => count($cleaned_favorites)
    ]);
}

