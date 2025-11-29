<?php

function is_attachment_used_in_other_posts($attachment_id, $exclude_post_id) {
    if (!$attachment_id) {
        return false;
    }

    $attachment_id = (int) $attachment_id;
    $exclude_post_id = (int) $exclude_post_id;

    $used_as_thumbnail = get_posts([
        'fields' => 'ids',
        'post_type' => 'any',
        'post_status' => 'any',
        'posts_per_page' => 1,
        'post__not_in' => [$exclude_post_id],
        'meta_query' => [[
            'key' => '_thumbnail_id',
            'value' => $attachment_id,
            'compare' => '=',
        ]],
    ]);

    if (!empty($used_as_thumbnail)) {
        return true;
    }

    $used_in_gallery = get_posts([
        'fields' => 'ids',
        'post_type' => 'any',
        'post_status' => 'any',
        'posts_per_page' => 1,
        'post__not_in' => [$exclude_post_id],
        'meta_query' => [[
            'key' => 'property_gallery',
            'value' => serialize(strval($attachment_id)),
            'compare' => 'LIKE',
        ]],
    ]);

    if (!empty($used_in_gallery)) {
        return true;
    }

    if (function_exists('carbon_get_post_meta')) {
        $all_posts = get_posts([
            'fields' => 'ids',
            'post_type' => 'any',
            'post_status' => 'any',
            'posts_per_page' => -1,
            'post__not_in' => [$exclude_post_id],
        ]);

        foreach ($all_posts as $other_post_id) {
            $gallery = carbon_get_post_meta($other_post_id, 'property_gallery');
            if (is_array($gallery) && in_array($attachment_id, $gallery)) {
                return true;
            }
        }
    }

    return false;
}

add_action('before_delete_post', function ($post_id) {
    $post = get_post($post_id);
    if (! $post || $post->post_type === 'attachment') {
        return;
    }

    $thumbnail_id = get_post_thumbnail_id($post_id);
    if ($thumbnail_id && !is_attachment_used_in_other_posts($thumbnail_id, $post_id)) {
        $attachment = get_post($thumbnail_id);
        if ($attachment && ($attachment->post_parent == $post_id || !$attachment->post_parent)) {
            wp_delete_attachment($thumbnail_id, true);
        }
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
            if (!is_attachment_used_in_other_posts($attachment_id, $post_id)) {
                wp_delete_attachment($attachment_id, true);
            }
        }
    }

    $gallery_ids = [];
    if (function_exists('carbon_get_post_meta')) {
        $gallery = carbon_get_post_meta($post_id, 'property_gallery');
        if (is_array($gallery) && ! empty($gallery)) {
            $gallery_ids = $gallery;
        }
    } else {
        $meta = get_post_meta($post_id, 'property_gallery', true);
        if (is_array($meta) && ! empty($meta)) {
            $gallery_ids = $meta;
        }
    }

    if (!empty($gallery_ids)) {
        foreach ($gallery_ids as $attachment_id) {
            if ($attachment_id && !is_attachment_used_in_other_posts($attachment_id, $post_id)) {
                $attachment = get_post((int) $attachment_id);
                if ($attachment && ($attachment->post_parent == $post_id || !$attachment->post_parent)) {
                    wp_delete_attachment((int) $attachment_id, true);
                }
            }
        }
    }
});


