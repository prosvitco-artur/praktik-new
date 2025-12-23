<?php

add_action('admin_menu', function() {
    add_management_page(
        __('Очищення медіафайлів', 'praktik'),
        __('Очищення медіа', 'praktik'),
        'manage_options',
        'praktik-media-cleanup',
        'praktik_media_cleanup_page'
    );
});

function praktik_media_cleanup_page() {
    if (!current_user_can('manage_options')) {
        wp_die(__('У вас немає прав доступу до цієї сторінки.', 'praktik'));
    }

    $action = isset($_GET['action']) ? sanitize_text_field($_GET['action']) : '';
    $nonce = isset($_GET['_wpnonce']) ? sanitize_text_field($_GET['_wpnonce']) : '';
    
    if ($action === 'scan' && wp_verify_nonce($nonce, 'scan_orphaned_media')) {
        $orphaned_files = praktik_scan_orphaned_media();
        update_option('praktik_orphaned_media', $orphaned_files, false);
        $message = sprintf(__('Знайдено %d сирітських файлів.', 'praktik'), count($orphaned_files));
    } elseif ($action === 'cleanup' && wp_verify_nonce($nonce, 'cleanup_orphaned_media')) {
        $orphaned_files = get_option('praktik_orphaned_media', []);
        $deleted = praktik_delete_orphaned_files($orphaned_files);
        delete_option('praktik_orphaned_media');
        $message = sprintf(__('Видалено %d файлів.', 'praktik'), $deleted);
    } else {
        $orphaned_files = get_option('praktik_orphaned_media', []);
        $message = '';
    }

    ?>
    <div class="wrap">
        <h1><?php echo esc_html__('Очищення медіафайлів', 'praktik'); ?></h1>
        
        <?php if ($message): ?>
            <div class="notice notice-success is-dismissible">
                <p><?php echo esc_html($message); ?></p>
            </div>
        <?php endif; ?>
        
        <div class="card">
            <h2><?php echo esc_html__('Сирітські медіафайли', 'praktik'); ?></h2>
            <p><?php echo esc_html__('Ця утиліта знаходить та видаляє файли в директорії uploads, які не мають відповідних записів у базі даних WordPress.', 'praktik'); ?></p>
            
            <p>
                <strong><?php echo esc_html__('Знайдено файлів:', 'praktik'); ?></strong> 
                <?php echo count($orphaned_files); ?>
            </p>
            
            <?php if (!empty($orphaned_files)): ?>
                <details style="margin: 20px 0;">
                    <summary style="cursor: pointer; font-weight: bold; margin-bottom: 10px;">
                        <?php echo esc_html__('Показати список файлів', 'praktik'); ?>
                    </summary>
                    <ul style="max-height: 400px; overflow-y: auto; list-style: disc; margin-left: 20px;">
                        <?php foreach (array_slice($orphaned_files, 0, 100) as $file): ?>
                            <li><?php echo esc_html($file); ?></li>
                        <?php endforeach; ?>
                        <?php if (count($orphaned_files) > 100): ?>
                            <li><em><?php echo sprintf(esc_html__('... та ще %d файлів', 'praktik'), count($orphaned_files) - 100); ?></em></li>
                        <?php endif; ?>
                    </ul>
                </details>
            <?php endif; ?>
            
            <p>
                <a href="<?php echo esc_url(wp_nonce_url(admin_url('tools.php?page=praktik-media-cleanup&action=scan'), 'scan_orphaned_media')); ?>" 
                   class="button button-primary">
                    <?php echo esc_html__('Сканувати файли', 'praktik'); ?>
                </a>
                
                <?php if (!empty($orphaned_files)): ?>
                    <a href="<?php echo esc_url(wp_nonce_url(admin_url('tools.php?page=praktik-media-cleanup&action=cleanup'), 'cleanup_orphaned_media')); ?>" 
                       class="button button-secondary"
                       onclick="return confirm('<?php echo esc_js(__('Ви впевнені? Це видалить всі знайдені файли безповоротно!', 'praktik')); ?>');">
                        <?php echo esc_html__('Видалити знайдені файли', 'praktik'); ?>
                    </a>
                <?php endif; ?>
            </p>
        </div>
    </div>
    <?php
}

function praktik_scan_orphaned_media() {
    global $wpdb;
    
    $upload_dir = wp_upload_dir();
    $upload_basedir = $upload_dir['basedir'];
    $orphaned_files = [];
    
    if (!is_dir($upload_basedir)) {
        return $orphaned_files;
    }
    
    $attachments = $wpdb->get_col("SELECT ID FROM {$wpdb->posts} WHERE post_type = 'attachment'");
    $attachment_files = [];
    
    foreach ($attachments as $attachment_id) {
        $file_path = get_attached_file($attachment_id);
        if ($file_path) {
            $attachment_files[] = $file_path;
            
            $meta = wp_get_attachment_metadata($attachment_id);
            if ($meta && isset($meta['sizes']) && is_array($meta['sizes'])) {
                $file_dir = dirname($file_path);
                foreach ($meta['sizes'] as $size_data) {
                    if (isset($size_data['file'])) {
                        $thumb_file = $file_dir . '/' . $size_data['file'];
                        if (file_exists($thumb_file)) {
                            $attachment_files[] = $thumb_file;
                        }
                    }
                }
            }
        }
    }
    
    $attachment_files = array_map('realpath', array_filter($attachment_files, 'file_exists'));
    $attachment_files = array_map('strtolower', $attachment_files);
    
    $iterator = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($upload_basedir, RecursiveDirectoryIterator::SKIP_DOTS),
        RecursiveIteratorIterator::SELF_FIRST
    );
    
    foreach ($iterator as $file) {
        if ($file->isFile()) {
            $file_path = $file->getRealPath();
            $file_path_lower = strtolower($file_path);
            
            if (strpos($file_path, $upload_basedir) === 0) {
                if (!in_array($file_path_lower, $attachment_files)) {
                    $orphaned_files[] = str_replace($upload_basedir . '/', '', $file_path);
                }
            }
        }
    }
    
    return $orphaned_files;
}

function praktik_delete_orphaned_files($files) {
    $upload_dir = wp_upload_dir();
    $upload_basedir = $upload_dir['basedir'];
    $deleted_count = 0;
    $empty_dirs = [];
    
    foreach ($files as $file) {
        $file_path = $upload_basedir . '/' . $file;
        $real_file_path = realpath($file_path);
        $real_upload_dir = realpath($upload_basedir);
        
        if ($real_file_path && $real_upload_dir && strpos($real_file_path, $real_upload_dir) === 0) {
            if (file_exists($real_file_path) && is_file($real_file_path)) {
                if (@unlink($real_file_path)) {
                    $deleted_count++;
                    $dir = dirname($real_file_path);
                    if (!in_array($dir, $empty_dirs)) {
                        $empty_dirs[] = $dir;
                    }
                }
            }
        }
    }
    
    foreach ($empty_dirs as $dir) {
        if (is_dir($dir) && $dir !== $real_upload_dir) {
            praktik_remove_empty_dirs($dir, $real_upload_dir);
        }
    }
    
    return $deleted_count;
}

function praktik_remove_empty_dirs($dir, $base_dir) {
    if (!is_dir($dir) || $dir === $base_dir) {
        return;
    }
    
    $files = array_diff(scandir($dir), ['.', '..']);
    
    if (empty($files)) {
        @rmdir($dir);
        praktik_remove_empty_dirs(dirname($dir), $base_dir);
    }
}

if (defined('WP_CLI') && WP_CLI && class_exists('WP_CLI')) {
    WP_CLI::add_command('praktik media cleanup', function($args, $assoc_args) {
        WP_CLI::line('Сканування сирітських файлів...');
        $orphaned_files = praktik_scan_orphaned_media();
        
        if (empty($orphaned_files)) {
            WP_CLI::success('Сирітські файли не знайдені.');
            return;
        }
        
        WP_CLI::line(sprintf('Знайдено %d сирітських файлів.', count($orphaned_files)));
        
        if (isset($assoc_args['dry-run'])) {
            WP_CLI::line('Режим перевірки (dry-run). Файли не будуть видалені.');
            foreach (array_slice($orphaned_files, 0, 20) as $file) {
                WP_CLI::line('  - ' . $file);
            }
            if (count($orphaned_files) > 20) {
                WP_CLI::line(sprintf('  ... та ще %d файлів', count($orphaned_files) - 20));
            }
            return;
        }
        
        if (!isset($assoc_args['yes'])) {
            WP_CLI::confirm('Видалити всі знайдені файли?');
        }
        
        $deleted = praktik_delete_orphaned_files($orphaned_files);
        WP_CLI::success(sprintf('Видалено %d файлів.', $deleted));
    });
}

