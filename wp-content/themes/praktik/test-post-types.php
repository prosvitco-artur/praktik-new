<?php
/**
 * Test file for post types
 * Access this file directly to test post types registration
 */

// Load WordPress
require_once('../../../wp-load.php');

// Check if post types are registered
$post_types = get_post_types(['public' => true], 'objects');

echo '<h1>Post Types Test</h1>';
echo '<h2>Registered Post Types:</h2>';
echo '<ul>';

foreach ($post_types as $post_type => $object) {
    echo '<li>';
    echo '<strong>' . $post_type . '</strong>: ' . $object->labels->name;
    echo '<br>Archive URL: ' . get_post_type_archive_link($post_type);
    echo '<br>Has Archive: ' . ($object->has_archive ? 'Yes' : 'No');
    echo '</li>';
}

echo '</ul>';

// Test specific post types
$test_types = ['room', 'apartment', 'house', 'plot', 'garage', 'commercial', 'dacha'];

echo '<h2>Test Post Types:</h2>';
echo '<ul>';

foreach ($test_types as $type) {
    echo '<li>';
    echo '<strong>' . $type . '</strong>: ';
    echo post_type_exists($type) ? 'EXISTS' : 'NOT FOUND';
    echo ' | Archive: ' . get_post_type_archive_link($type);
    echo '</li>';
}

echo '</ul>';

// Flush rewrite rules
echo '<h2>Actions:</h2>';
echo '<p><a href="?flush=1">Flush Rewrite Rules</a></p>';

if (isset($_GET['flush'])) {
    flush_rewrite_rules();
    echo '<p>Rewrite rules flushed!</p>';
}
?> 