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

function validate_favorites($favorites) {
    if (empty($favorites) || !is_array($favorites)) {
        return [];
    }
    
    $valid_favorites = [];
    
    foreach ($favorites as $post_id) {
        $post_id = intval($post_id);
        
        if ($post_id <= 0) {
            continue;
        }
        
        $post = get_post($post_id);
        
        if (!$post || $post->post_status !== 'publish') {
            continue;
        }
        
        $valid_favorites[] = (string) $post_id;
    }
    
    return array_values(array_unique($valid_favorites));
}

function get_stored_favorites($session_id = null) {
    if (is_user_logged_in()) {
        $favorites = get_user_meta(get_current_user_id(), 'praktik_favorites', true);
        if (!is_array($favorites)) {
            return [];
        }
        $favorites = array_map('strval', $favorites);
    } else {
        $key = get_favorites_storage_key($session_id);
        if (empty($key)) {
            return [];
        }
        
        $favorites = get_transient($key);
        if (!is_array($favorites)) {
            return [];
        }
        
        $favorites = array_map('strval', $favorites);
    }
    
    $validated = validate_favorites($favorites);
    
    if (count($validated) !== count($favorites)) {
        save_favorites($validated, $session_id);
    }
    
    return $validated;
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

function generate_favorites_share_token($favorites_ids) {
    if (empty($favorites_ids)) {
        return null;
    }
    
    sort($favorites_ids);
    $favorites_string = implode(',', $favorites_ids);
    $token = md5($favorites_string . time() . wp_generate_password(12, false));
    
    $share_data = [
        'favorites' => $favorites_ids,
        'created' => time(),
    ];
    
    set_transient('favorites_share_' . $token, $share_data, 30 * 24 * 60 * 60);
    
    return $token;
}

function get_shared_favorites($token) {
    $share_data = get_transient('favorites_share_' . $token);
    return $share_data ? $share_data['favorites'] : null;
}

add_action('wp_ajax_generate_favorites_share', __NAMESPACE__ . '\\handle_generate_favorites_share');
add_action('wp_ajax_nopriv_generate_favorites_share', __NAMESPACE__ . '\\handle_generate_favorites_share');

function handle_generate_favorites_share() {
    check_ajax_referer('praktik_ajax', 'nonce', false);
    
    $session_id = isset($_POST['session_id']) ? sanitize_text_field($_POST['session_id']) : null;
    $favorites = get_stored_favorites($session_id);
    
    if (empty($favorites)) {
        wp_send_json_error([
            'message' => __('No favorites to share.', 'praktik')
        ]);
    }
    
    $token = generate_favorites_share_token($favorites);
    
    if (!$token) {
        wp_send_json_error([
            'message' => __('Failed to generate share link.', 'praktik')
        ]);
    }
    
    $share_url = home_url('/favorites/share/' . $token);
    
    wp_send_json_success([
        'token' => $token,
        'url' => $share_url,
    ]);
}

