<?php

$includes = [
	'air-table.php',
	'post_types.php',
	'translations.php',
	'carbonfields/init.php',
	'post-auto-delete.php',
];

foreach ( $includes as $include ) {
	require_once get_stylesheet_directory() . '/inc/' . $include;
}