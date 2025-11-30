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
    ['title' => 'Смоленцева Світлана', 'name' => 'Світлана Смоленцева', 'phone' => '+380 50 313 76 82', 'telegram' => 'SvetlanaS_Praktik'],
    ['title' => 'Попович Тетяна', 'name' => 'Тетяна Попович', 'phone' => '+380 95 507 43 65', 'telegram' => 'Tetiana_Popovych2'],
    ['title' => 'Лящук Валентина', 'name' => 'Валентина Лящук', 'phone' => '+380 97 190 03 90', 'telegram' => 'valentinapraktik'],
    ['title' => 'Нікітєнко Людмила', 'name' => 'Людмила Нікітєнко', 'phone' => '+380 63 567 40 57', 'telegram' => 'Liudmyla_Nikitienko'],
    ['title' => 'Корж Тетяна', 'name' => 'Тетяна Корж', 'phone' => '+380 93 958 31 23', 'telegram' => 'Tanya_Practik'],
    ['title' => 'Драчко Вікторія', 'name' => 'Вікторі Драчко', 'phone' => '+380 93 379 38 18', 'telegram' => 'Vika_dvs'],
    ['title' => 'Рубановська Алла', 'name' => 'Алла Рубановська', 'phone' => '+380 63 715 97 71', 'telegram' => 'AllaSinitha2808'],
    ['title' => 'Судак Олена', 'name' => 'Олена Судак', 'phone' => '+38 0967193195', 'telegram' => 'LeSudakPraktik'],
    ['title' => 'Польгуй Лілія', 'name' => 'Лілія Польгуй', 'phone' => '+380 98 304 40 58', 'telegram' => 'lilia2006'],
    ['title' => 'Забіяка Сергій', 'name' => 'Сергій Забіяка', 'phone' => '+380 67 704 75 72', 'telegram' => 'Serhii_Zabiiaka'],
    ['title' => 'Коваленко Тетяна', 'name' => 'Тетяна Коваленко', 'phone' => '+380 93 514 77 31', 'telegram' => ''],
    ['title' => 'Отчиченко Катерина', 'name' => 'Катерина Отчиченко', 'phone' => '+380 99 300 52 74', 'telegram' => 'Otchi666'],
    ['title' => 'Веремей Надія', 'name' => 'Надія Веремей', 'phone' => '+380 93 796 85 21', 'telegram' => 'NadezhdaVeremey'],
    ['title' => 'Наталія Горбач', 'name' => 'Наталія Горбач', 'phone' => '+380 63 183 12 98', 'telegram' => 'NataliaCherPraktik'],
    ['title' => 'Казимір Тетяна', 'name' => 'Тетяна Казимір', 'phone' => '+380 93 671 79 16', 'telegram' => 'Tanya_Kazimir'],
    ['title' => 'Коапків Юлія', 'name' => 'Юлія Коапків', 'phone' => '+380 63 278 43 86', 'telegram' => 'YuliaKlapkiv'],
    ['title' => 'Мироненко Анастасія', 'name' => 'Анастасія Мироненко', 'phone' => '+380 96 517 56 20', 'telegram' => ''],
];

$imported = 0;
$skipped = 0;
$errors = [];

foreach ($realtors as $realtor_data) {
    $title = trim($realtor_data['title']);
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
        'post_title' => $title,
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
    if (!empty($name)) {
        carbon_set_post_meta($post_id, 'realtor_name', $name);
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


