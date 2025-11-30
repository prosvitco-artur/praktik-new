<?php

add_filter('airwpsync/get_post_types', function ($post_types) {
    return array_map(function ($post_type) {
        $post_type['enabled'] = true;
        return $post_type;
    }, $post_types);
});

add_filter('airwpsync/get_wp_fields', function ($fields, $module) {
    if ($module === 'post' && isset($fields['post']['options'])) {
        foreach ($fields['post']['options'] as &$option) {
            if ($option['value'] === 'postmeta::custom_field') {
                $option['enabled'] = true;
                $option['label'] = __('Custom Field...', 'praktik');
            }
        }
    }
    return $fields;
}, 20, 2);

add_filter('wp_insert_post_data', function ($data, $postarr) {
    if (($postarr['post_type'] ?? '') !== 'airwpsync-connection') {
        return $data;
    }
    $requestedStatus = isset($_POST['post_status']) ? $_POST['post_status'] : ($data['post_status'] ?? '');
    if ($requestedStatus === 'publish') {
        $data['post_status'] = 'publish';
    }
    return $data;
}, 100, 2);

add_filter('redirect_post_location', function ($location, $post_id) {
    $post = get_post($post_id);
    if ($post && $post->post_type === 'airwpsync-connection') {
        $location = remove_query_arg('display_max_connection', $location);
    }
    return $location;
}, 100, 2);

/**
 * Після імпорту: конвертація масиву об'єктів Airtable (attachments)
 * у масив ID медіа для мета-поля property_gallery (Carbon Fields media_gallery).
 */
add_action('airwpsync/import_record_after', function ($importer, $fields, $record, $post_id) {
    require_once ABSPATH . 'wp-admin/includes/admin.php';
    require_once ABSPATH . 'wp-admin/includes/image.php';

    $thumbnail_id = 0;
    $gallery_ids = [];

    $thumbnail_meta_key = '_thumbnail_id';
    $thumbnail_raw = get_post_meta($post_id, $thumbnail_meta_key, true);
    
    if (!empty($thumbnail_raw)) {
        if (is_object($thumbnail_raw) && isset($thumbnail_raw->url)) {
            $thumbnail_id = process_airtable_media($thumbnail_raw, $post_id);
        } elseif (is_numeric($thumbnail_raw)) {
            $thumbnail_id = (int) $thumbnail_raw;
        }
    }

    $meta_key = 'property_gallery';
    $raw = get_post_meta($post_id, $meta_key, true);
    
    if (is_array($raw) && !empty($raw)) {
        $is_objects = isset($raw[0]) && is_object($raw[0]) && isset($raw[0]->url);
        
        if ($is_objects) {
            foreach ($raw as $media) {
                $attachment_id = process_airtable_media($media, $post_id);
                if ($attachment_id) {
                    $gallery_ids[] = $attachment_id;
                    if (!$thumbnail_id) {
                        $thumbnail_id = $attachment_id;
                    }
                }
            }
        } else {
            $gallery_ids = array_map('intval', $raw);
            if (!$thumbnail_id && !empty($gallery_ids[0])) {
                $thumbnail_id = $gallery_ids[0];
            }
        }
    }

    if (!empty($gallery_ids)) {
        if (function_exists('carbon_set_post_meta')) {
            carbon_set_post_meta($post_id, $meta_key, $gallery_ids);
            delete_post_meta($post_id, $meta_key);
        } else {
            update_post_meta($post_id, $meta_key, $gallery_ids);
        }
    }

    if ($thumbnail_id && !get_post_thumbnail_id($post_id)) {
        set_post_thumbnail($post_id, $thumbnail_id);
        update_post_meta($post_id, '_thumbnail_id', $thumbnail_id);
    }
    
    $property_post_types = array_keys(\get_property_post_types());
    $post_type = get_post_type($post_id);
    
    if (in_array($post_type, $property_post_types)) {
        assign_realtor_to_property($post_id);
    }
}, 20, 4);

function process_airtable_media($media, $post_id) {
    $attachment_id = 0;

    if (isset($media->id)) {
        $existing = get_posts([
            'fields' => 'ids',
            'post_type' => 'attachment',
            'post_status' => 'any',
            'posts_per_page' => 1,
            'meta_query' => [[
                'key' => '_air_wp_sync_record_id',
                'value' => $media->id,
            ]],
        ]);
        if (!empty($existing)) {
            $attachment_id = (int) $existing[0];
        }
    }

    if (!$attachment_id && !empty($media->url)) {
        $tmp = download_url($media->url);
        if (!is_wp_error($tmp)) {
            $filename = basename(parse_url($media->url, PHP_URL_PATH));
            if (empty(pathinfo($filename, PATHINFO_EXTENSION)) && !empty($media->type)) {
                $map = apply_filters('getimagesize_mimes_to_exts', [
                    'image/jpeg' => 'jpg',
                    'image/png'  => 'png',
                    'image/gif'  => 'gif',
                    'image/bmp'  => 'bmp',
                    'image/tiff' => 'tif',
                    'image/webp' => 'webp',
                ]);
                if (!empty($map[$media->type])) {
                    $filename = sanitize_file_name(($media->filename ?? 'image') . '.' . $map[$media->type]);
                }
            }
            $file_array = [
                'name' => $filename,
                'tmp_name' => $tmp,
            ];
            $post_data = [
                'post_title' => $media->filename ?? $filename,
                'post_parent' => $post_id,
            ];
            $result = media_handle_sideload($file_array, 0, null, $post_data);
            if (is_wp_error($result)) {
                @unlink($tmp);
            } else {
                $attachment_id = (int) $result;
                if (!empty($media->id)) {
                    update_post_meta($attachment_id, '_air_wp_sync_record_id', $media->id);
                }
                wp_generate_attachment_metadata($attachment_id, get_attached_file($attachment_id));
            }
        }
    }

    return $attachment_id;
}

/**
 * Призначити ріелтора до property під час імпорту
 * Шукає ріелтора по title в post type 'realtor'
 */
function assign_realtor_to_property($post_id) {
    $property_post_types = array_keys(\get_property_post_types());
    $post_type = get_post_type($post_id);
    
    if (!in_array($post_type, $property_post_types)) {
        return;
    }
    
    $realtor_meta_key = 'property_realtor';
    $realtor_raw = get_post_meta($post_id, $realtor_meta_key, true);
    
    if (empty($realtor_raw)) {
        return;
    }
    
    $realtor_id = null;
    
    if (is_numeric($realtor_raw)) {
        $realtor_id = (int) $realtor_raw;
    } elseif (is_string($realtor_raw)) {
        $realtor_title = trim($realtor_raw);
        
        if (!empty($realtor_title)) {
            global $wpdb;
            
            $realtor_title_escaped = $wpdb->esc_like($realtor_title);
            
            $realtor_post = $wpdb->get_row($wpdb->prepare(
                "SELECT ID, post_title FROM {$wpdb->posts} 
                WHERE post_type = 'realtor' 
                AND post_status = 'publish' 
                AND post_title = %s 
                LIMIT 1",
                $realtor_title
            ));
            
            if ($realtor_post) {
                $realtor_id = (int) $realtor_post->ID;
            } else {
                $realtors_fuzzy = get_posts([
                    'post_type' => 'realtor',
                    'post_status' => 'publish',
                    'posts_per_page' => -1,
                    'orderby' => 'title',
                    'order' => 'ASC',
                ]);
                
                foreach ($realtors_fuzzy as $realtor) {
                    $realtor_title_normalized = mb_strtolower(trim($realtor_title));
                    $realtor_post_title_normalized = mb_strtolower(trim($realtor->post_title));
                    
                    if ($realtor_title_normalized === $realtor_post_title_normalized ||
                        stripos($realtor->post_title, $realtor_title) !== false || 
                        stripos($realtor_title, $realtor->post_title) !== false) {
                        $realtor_id = (int) $realtor->ID;
                        break;
                    }
                }
            }
        }
    }
    
    if ($realtor_id) {
        if (function_exists('carbon_set_post_meta')) {
            carbon_set_post_meta($post_id, $realtor_meta_key, $realtor_id);
        } else {
            update_post_meta($post_id, $realtor_meta_key, $realtor_id);
        }
    }
}


