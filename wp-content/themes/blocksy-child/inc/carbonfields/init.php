<?php

add_action( 'after_setup_theme', 'crb_load' );
function crb_load() {

	$vendor_autoload = get_stylesheet_directory() . '/vendor/autoload.php';
	if ( file_exists( $vendor_autoload ) ) {
		require_once( $vendor_autoload );
		\Carbon_Fields\Carbon_Fields::boot();
	} else {
		error_log( 'Vendor autoload file not found: ' . $vendor_autoload );
	}
}

add_action( 'carbon_fields_register_fields', 'crb_attach_post_meta' );
function crb_attach_post_meta() {
	require_once get_stylesheet_directory() . '/inc/carbonfields/post-meta.php';
}