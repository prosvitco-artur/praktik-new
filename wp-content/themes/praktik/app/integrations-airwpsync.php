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
    $meta_key = 'property_gallery';
    $raw = get_post_meta($post_id, $meta_key, true);
    if (! is_array($raw) || empty($raw)) {
        return;
    }
    // Визначаємо чи це вже масив ID, чи масив об'єктів Airtable
    $is_objects = isset($raw[0]) && is_object($raw[0]) && isset($raw[0]->url);
    if (! $is_objects) {
        return;
    }

    require_once ABSPATH . 'wp-admin/includes/admin.php';

    $ids = [];
    foreach ($raw as $media) {
        $attachment_id = 0;

        // Спроба знайти існуючий attachment за _air_wp_sync_record_id
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
            if (! empty($existing)) {
                $attachment_id = (int) $existing[0];
            }
        }

        if (! $attachment_id && ! empty($media->url)) {
            // Завантаження файлу у медіатеку
            $tmp = download_url($media->url);
            if (! is_wp_error($tmp)) {
                $filename = basename(parse_url($media->url, PHP_URL_PATH));
                if (empty(pathinfo($filename, PATHINFO_EXTENSION)) && ! empty($media->type)) {
                    $map = apply_filters('getimagesize_mimes_to_exts', [
                        'image/jpeg' => 'jpg',
                        'image/png'  => 'png',
                        'image/gif'  => 'gif',
                        'image/bmp'  => 'bmp',
                        'image/tiff' => 'tif',
                        'image/webp' => 'webp',
                    ]);
                    if (! empty($map[$media->type])) {
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
                    if (! empty($media->id)) {
                        update_post_meta($attachment_id, '_air_wp_sync_record_id', $media->id);
                    }
                }
            }
        }

        if ($attachment_id) {
            $ids[] = $attachment_id;
        }
    }

    if (! empty($ids)) {
        if (function_exists('carbon_set_post_meta')) {
            carbon_set_post_meta($post_id, $meta_key, $ids);
            delete_post_meta($post_id, $meta_key);
        } else {
            update_post_meta($post_id, $meta_key, $ids);
        }
        if (! empty($ids[0])) {
            set_post_thumbnail($post_id, (int) $ids[0]);
        }
    }
}, 20, 4);


