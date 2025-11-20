<?php

namespace App;

add_action('wp_ajax_toggle_favorite', __NAMESPACE__ . '\\handle_toggle_favorite');
add_action('wp_ajax_nopriv_toggle_favorite', __NAMESPACE__ . '\\handle_toggle_favorite');

function handle_toggle_favorite() {
    
    $post_id = isset($_POST['post_id']) ? intval($_POST['post_id']) : 0;
    
    if (!$post_id || !get_post($post_id)) {
        wp_send_json_error([
            'message' => __('Invalid post ID.', 'praktik')
        ]);
    }
    
    $favorites = get_user_favorites();
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
    
    wp_send_json_success([
        'post_id' => $post_id,
        'is_favorite' => !$is_favorite,
        'favorites' => $favorites
    ]);
}

