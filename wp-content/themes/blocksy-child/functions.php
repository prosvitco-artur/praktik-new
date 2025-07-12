<?php

if (! defined('WP_DEBUG')) {
	die( 'Direct access forbidden.' );
}

include_once get_stylesheet_directory() . '/inc/init.php';

add_action( 'wp_enqueue_scripts', function () {
	wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );
});




