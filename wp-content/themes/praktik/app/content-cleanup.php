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

function delete_attachment_files($attachment_id) {
    if (!$attachment_id) {
        return false;
    }

    $attachment_id = (int) $attachment_id;
    require_once(ABSPATH . 'wp-admin/includes/file.php');
    
    $file_path = get_attached_file($attachment_id);
    
    if ($file_path && file_exists($file_path)) {
        $upload_dir = wp_upload_dir();
        $upload_basedir = $upload_dir['basedir'];
        $real_file_path = realpath($file_path);
        $real_upload_dir = realpath($upload_basedir);
        
        if ($real_file_path && $real_upload_dir && strpos($real_file_path, $real_upload_dir) === 0) {
            @unlink($file_path);
            
            $meta = wp_get_attachment_metadata($attachment_id);
            if ($meta && isset($meta['sizes']) && is_array($meta['sizes'])) {
                $file_dir = dirname($file_path);
                
                foreach ($meta['sizes'] as $size => $size_data) {
                    if (isset($size_data['file'])) {
                        $thumb_file = $file_dir . '/' . $size_data['file'];
                        if (file_exists($thumb_file)) {
                            $real_thumb_path = realpath($thumb_file);
                            if ($real_thumb_path && strpos($real_thumb_path, $real_upload_dir) === 0) {
                                @unlink($thumb_file);
                            }
                        }
                    }
                }
            }
            
            $backup_sizes = get_post_meta($attachment_id, '_wp_attachment_backup_sizes', true);
            if (is_array($backup_sizes)) {
                foreach ($backup_sizes as $size_data) {
                    if (isset($size_data['file'])) {
                        $backup_file = $file_dir . '/' . $size_data['file'];
                        if (file_exists($backup_file)) {
                            $real_backup_path = realpath($backup_file);
                            if ($real_backup_path && strpos($real_backup_path, $real_upload_dir) === 0) {
                                @unlink($backup_file);
                            }
                        }
                    }
                }
            }
        }
    }
    
    return wp_delete_attachment($attachment_id, true);
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
            delete_attachment_files($thumbnail_id);
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
                delete_attachment_files($attachment_id);
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
                    delete_attachment_files((int) $attachment_id);
                }
            }
        }
    }
});


