<?php

namespace App;

add_action('wp_ajax_toggle_favorite', __NAMESPACE__ . '\\handle_toggle_favorite');
add_action('wp_ajax_nopriv_toggle_favorite', __NAMESPACE__ . '\\handle_toggle_favorite');
add_action('wp_ajax_get_favorites', __NAMESPACE__ . '\\handle_get_favorites');
add_action('wp_ajax_nopriv_get_favorites', __NAMESPACE__ . '\\handle_get_favorites');

function get_favorites_storage_key($session_id = null) {
    if (is_user_logged_in()) {
        return 'user_favorites_' . get_current_user_id();
    }
    
    if (empty($session_id)) {
        $session_id = isset($_POST['session_id']) ? sanitize_text_field($_POST['session_id']) : '';
    }
    
    if (empty($session_id)) {
        $session_id = isset($_COOKIE['praktik_session_id']) ? sanitize_text_field($_COOKIE['praktik_session_id']) : '';
    }
    
    if (empty($session_id)) {
        return null;
    }
    
    return 'guest_favorites_' . md5($session_id);
}

function get_stored_favorites($session_id = null) {
    if (is_user_logged_in()) {
        $favorites = get_user_meta(get_current_user_id(), 'praktik_favorites', true);
        if (!is_array($favorites)) {
            return [];
        }
        return array_map('strval', $favorites);
    }
    
    $key = get_favorites_storage_key($session_id);
    if (empty($key)) {
        return [];
    }
    
    $favorites = get_transient($key);
    if (!is_array($favorites)) {
        return [];
    }
    
    return array_map('strval', $favorites);
}

function save_favorites($favorites, $session_id = null) {
    $favorites = array_values(array_unique(array_map('strval', $favorites)));
    
    if (is_user_logged_in()) {
        update_user_meta(get_current_user_id(), 'praktik_favorites', $favorites);
    } else {
        $key = get_favorites_storage_key($session_id);
        if (!empty($key)) {
            set_transient($key, $favorites, 365 * 24 * 60 * 60);
        }
    }
    
    return $favorites;
}

function handle_get_favorites() {
    $session_id = isset($_POST['session_id']) ? sanitize_text_field($_POST['session_id']) : null;
    
    if (!is_user_logged_in() && !empty($session_id) && !isset($_COOKIE['praktik_session_id'])) {
        setcookie('praktik_session_id', $session_id, time() + (365 * 24 * 60 * 60), '/', '', is_ssl(), true);
    }
    
    $favorites = get_stored_favorites($session_id);
    
    wp_send_json_success([
        'favorites' => $favorites,
        'count' => count($favorites)
    ]);
}

function handle_toggle_favorite() {
    $post_id = isset($_POST['post_id']) ? intval($_POST['post_id']) : 0;
    $session_id = isset($_POST['session_id']) ? sanitize_text_field($_POST['session_id']) : null;
    
    if (!$post_id || !get_post($post_id)) {
        wp_send_json_error([
            'message' => __('Invalid post ID.', 'praktik')
        ]);
    }
    
    if (!is_user_logged_in() && !empty($session_id) && !isset($_COOKIE['praktik_session_id'])) {
        setcookie('praktik_session_id', $session_id, time() + (365 * 24 * 60 * 60), '/', '', is_ssl(), true);
    }
    
    $favorites = get_stored_favorites($session_id);
    $post_id_str = (string) $post_id;
    $is_favorite = in_array($post_id_str, $favorites);
    
    if ($is_favorite) {
        $favorites = array_values(array_filter($favorites, function($id) use ($post_id_str) {
            return $id !== $post_id_str;
        }));
    } else {
        $favorites[] = $post_id_str;
    }
    
    $favorites = save_favorites($favorites, $session_id);
    
    wp_send_json_success([
        'post_id' => $post_id,
        'is_favorite' => !$is_favorite,
        'favorites' => $favorites,
        'count' => count($favorites)
    ]);
}

