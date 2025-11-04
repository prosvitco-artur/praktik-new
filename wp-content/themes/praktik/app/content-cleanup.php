<?php

add_action('before_delete_post', function ($post_id) {
    $post = get_post($post_id);
    if (! $post || $post->post_type === 'attachment') {
        return;
    }
    $attachments = get_children([
        'post_parent' => $post_id,
        'post_type' => 'attachment',
        'post_status' => 'any',
        'posts_per_page' => -1,
        'fields' => 'ids',
    ]);
    if (! empty($attachments)) {
        foreach ($attachments as $attachment_id) {
            wp_delete_attachment($attachment_id, true);
        }
    }

    if (function_exists('carbon_get_post_meta')) {
        $gallery = carbon_get_post_meta($post_id, 'property_gallery');
        if (is_array($gallery) && ! empty($gallery)) {
            foreach ($gallery as $attachment_id) {
                if ($attachment_id) {
                    wp_delete_attachment((int) $attachment_id, true);
                }
            }
        }
    } else {
        $meta = get_post_meta($post_id, 'property_gallery', true);
        if (is_array($meta) && ! empty($meta)) {
            foreach ($meta as $attachment_id) {
                if ($attachment_id) {
                    wp_delete_attachment((int) $attachment_id, true);
                }
            }
        }
    }
});


