<?php
/**
 * Import Realtors
 * 
 * Run this file once to import realtors data
 * Usage: wp eval-file import-realtors.php
 * Or access via browser: /wp-content/themes/praktik/import-realtors.php
 */

require_once(__DIR__ . '/../../../wp-load.php');

if (!current_user_can('manage_options')) {
    die('You do not have permission to run this script.');
}

$realtors = [
    ['name' => 'Світлана Смоленцева', 'phone' => '+380 50 313 76 82', 'telegram' => 'SvetlanaS_Praktik'],
    ['name' => 'Тетяна Попович', 'phone' => '+380 95 507 43 65', 'telegram' => 'Tetiana_Popovych2'],
    ['name' => 'Валентина Лящук', 'phone' => '+380 97 190 03 90', 'telegram' => 'valentinapraktik'],
    ['name' => 'Людмила Нікітєнко', 'phone' => '+380 63 567 40 57', 'telegram' => 'Liudmyla_Nikitienko'],
    ['name' => 'Тетяна Корж', 'phone' => '+380 93 958 31 23', 'telegram' => 'Tanya_Practik'],
    ['name' => 'Вікторі Драчко', 'phone' => '+380 93 379 38 18', 'telegram' => 'Vika_dvs'],
    ['name' => 'Алла Рубановська', 'phone' => '+380 63 715 97 71', 'telegram' => 'AllaSinitha2808'],
    ['name' => 'Олена Судак', 'phone' => '+38 0967193195', 'telegram' => 'LeSudakPraktik'],
    ['name' => 'Лілія Польгуй', 'phone' => '+380 98 304 40 58', 'telegram' => 'lilia2006'],
    ['name' => 'Сергій Забіяка', 'phone' => '+380 67 704 75 72', 'telegram' => 'Serhii_Zabiiaka'],
    ['name' => 'Тетяна Коваленко', 'phone' => '+380 93 514 77 31', 'telegram' => ''],
    ['name' => 'Катерина Отчиченко', 'phone' => '+380 99 300 52 74', 'telegram' => 'Otchi666'],
    ['name' => 'Надія Веремей', 'phone' => '+380 93 796 85 21', 'telegram' => 'NadezhdaVeremey'],
    ['name' => 'Наталія Горбач', 'phone' => '+380 63 183 12 98', 'telegram' => 'NataliaCherPraktik'],
    ['name' => 'Тетяна Казимір', 'phone' => '+380 93 671 79 16', 'telegram' => 'Tanya_Kazimir'],
    ['name' => 'Юлія Коапків', 'phone' => '+380 63 278 43 86', 'telegram' => 'YuliaKlapkiv'],
    ['name' => 'Анастасія Мироненко', 'phone' => '+380 96 517 56 20', 'telegram' => ''],
];

$imported = 0;
$skipped = 0;
$errors = [];

foreach ($realtors as $realtor_data) {
    $name = trim($realtor_data['name']);
    $phone = trim($realtor_data['phone']);
    $telegram = trim($realtor_data['telegram']);
    
    // Check if realtor already exists
    $existing = get_posts([
        'post_type' => 'realtor',
        'title' => $name,
        'post_status' => 'any',
        'posts_per_page' => 1,
    ]);
    
    if (!empty($existing)) {
        $skipped++;
        continue;
    }
    
    // Create post
    $post_id = wp_insert_post([
        'post_title' => $name,
        'post_type' => 'realtor',
        'post_status' => 'publish',
    ]);
    
    if (is_wp_error($post_id)) {
        $errors[] = "Error creating {$name}: " . $post_id->get_error_message();
        continue;
    }
    
    // Set meta fields
    if (!empty($phone)) {
        carbon_set_post_meta($post_id, 'realtor_phone', $phone);
    }
    
    if (!empty($telegram)) {
        carbon_set_post_meta($post_id, 'realtor_telegram', $telegram);
    }
    
    $imported++;
}

echo "Import completed!\n";
echo "Imported: {$imported}\n";
echo "Skipped (already exists): {$skipped}\n";

if (!empty($errors)) {
    echo "Errors:\n";
    foreach ($errors as $error) {
        echo "- {$error}\n";
    }
}


