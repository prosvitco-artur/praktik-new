<?php

require_once __DIR__ . '/../../../wp-load.php';

if (!current_user_can('manage_options')) {
    wp_die('You do not have sufficient permissions to access this page.');
}

$old_text = 'Отзыв о работе АН Практик от';
$new_text = 'Відгук про роботу АН Практик від';

$args = [
    'post_type' => 'review',
    'posts_per_page' => -1,
    'post_status' => 'any',
];

$query = new WP_Query($args);
$updated = 0;
$errors = [];

if ($query->have_posts()) {
    while ($query->have_posts()) {
        $query->the_post();
        $post_id = get_the_ID();
        $current_title = get_the_title($post_id);
        
        if (strpos($current_title, $old_text) !== false) {
            $new_title = str_replace($old_text, $new_text, $current_title);
            
            $result = wp_update_post([
                'ID' => $post_id,
                'post_title' => $new_title,
            ], true);
            
            if (is_wp_error($result)) {
                $errors[] = sprintf('Помилка оновлення поста ID %d: %s', $post_id, $result->get_error_message());
            } else {
                $updated++;
            }
        }
    }
    wp_reset_postdata();
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>Оновлення title review постів</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background: #f0f0f1;
        }
        .container {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        h1 {
            margin-top: 0;
            color: #1d2327;
        }
        .success {
            color: #00a32a;
            font-weight: bold;
            margin: 20px 0;
        }
        .error {
            color: #d63638;
            margin: 10px 0;
        }
        .info {
            color: #2271b1;
            margin: 10px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Оновлення title review постів</h1>
        
        <div class="info">
            <strong>Шукаємо:</strong> <?php echo esc_html($old_text); ?><br>
            <strong>Замінюємо на:</strong> <?php echo esc_html($new_text); ?>
        </div>
        
        <?php if ($updated > 0): ?>
            <div class="success">
                ✅ Успішно оновлено <?php echo $updated; ?> постів.
            </div>
        <?php endif; ?>
        
        <?php if (!empty($errors)): ?>
            <div class="error">
                <strong>Помилки:</strong>
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?php echo esc_html($error); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
        
        <?php if ($updated === 0 && empty($errors)): ?>
            <div class="info">
                Не знайдено постів з текстом "<?php echo esc_html($old_text); ?>" в title.
            </div>
        <?php endif; ?>
        
        <p>
            <a href="<?php echo admin_url(); ?>">← Повернутися до адмін-панелі</a>
        </p>
    </div>
</body>
</html>

