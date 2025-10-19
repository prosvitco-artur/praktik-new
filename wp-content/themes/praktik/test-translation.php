<?php
/**
 * Template Name: Translation Test
 */

// Show current locale
echo "<h2>Current Locale: " . get_locale() . "</h2>";

// Show available .mo files
$lang_dir = get_template_directory() . '/languages';
echo "<h2>Language files in: $lang_dir</h2>";
$files = glob($lang_dir . '/*.mo');
echo "<pre>";
foreach ($files as $file) {
    echo basename($file) . " - " . filesize($file) . " bytes - " . date('Y-m-d H:i:s', filemtime($file)) . "\n";
}
echo "</pre>";

// Test translation
$text = 'Back to catalog';
$translated = __($text, 'praktik');
echo "<h2>Translation Test:</h2>";
echo "Original: <strong>$text</strong><br>";
echo "Translated: <strong>$translated</strong><br>";
echo "Match: " . ($text === $translated ? "❌ NOT translated" : "✅ Translated") . "<br>";

// Check if textdomain is loaded
echo "<h2>Text Domain Check:</h2>";
global $l10n;
echo "Loaded domains: <pre>";
print_r(array_keys($l10n));
echo "</pre>";

// Expected filename
$expected = 'praktik-' . get_locale() . '.mo';
echo "<h2>Expected file: $expected</h2>";
echo "Exists: " . (file_exists($lang_dir . '/' . $expected) ? "✅ Yes" : "❌ No");

