<?php

// create post types room, apartment, house, plot, garage, commercial, dacha

add_action( 'init', function () {
	foreach ( get_created_post_types() as $post_type => $config ) {
		register_post_type( $post_type, [
			'labels' => $config['labels'],
			'public' => true,
			'has_archive' => true,
			'rewrite' => [
				'slug' => $config['slug'],
			],
			'supports' => [
				'title',
				'editor',
				'thumbnail',
				'excerpt',
				'custom-fields',
			],
			'menu_icon' => $config['icon'],
			'menu_position' => 5,
			'show_in_rest' => true,
			'show_in_menu' => true,
			'capability_type' => 'post',
			'hierarchical' => false,
			'query_var' => true,
			'can_export' => true,
		] );
	}

	// Disable Gutenberg for custom post types
	add_filter( 'use_block_editor_for_post_type', function( $use_block_editor, $post_type ) {
		$custom_post_types = [ 'room', 'apartment', 'house', 'plot', 'garage', 'commercial', 'dacha' ];
		
		if ( in_array( $post_type, $custom_post_types ) ) {
			return false;
		}
		
		return $use_block_editor;
	}, 10, 2 );
} );


function get_created_post_types() : array {
	return[
		'room' => [
			'labels' => [
				'name' => __( 'Rooms', 'praktik' ),
				'singular_name' => __( 'Room', 'praktik' ),
			],
			'slug' => 'room',
			'icon' => 'dashicons-admin-home',
		],
		'apartment' => [
			'labels' => [
				'name' => __( 'Apartments', 'praktik' ),
				'singular_name' => __( 'Apartment', 'praktik' ),
			],
			'slug' => 'apartment',
			'icon' => 'dashicons-building',
		],
		'house' => [
			'labels' => [
				'name' => __( 'Houses', 'praktik' ),
				'singular_name' => __( 'House', 'praktik' ),
			],
			'slug' => 'house',
			'icon' => 'dashicons-admin-multisite',
		],
		'plot' => [
			'labels' => [
				'name' => __( 'Plots', 'praktik' ),
				'singular_name' => __( 'Plot', 'praktik' ),
			],
			'slug' => 'plot',
			'icon' => 'dashicons-location',
		],
		'garage' => [
			'labels' => [
				'name' => __( 'Garages', 'praktik' ),
				'singular_name' => __( 'Garage', 'praktik' ),
			],
			'slug' => 'garage',
			'icon' => 'dashicons-car',
		],
		'commercial' => [
			'labels' => [
				'name' => __( 'Commercial', 'praktik' ),
				'singular_name' => __( 'Commercial', 'praktik' ),
			],
			'slug' => 'commercial',
			'icon' => 'dashicons-store',
		],
		'dacha' => [
			'labels' => [
				'name' => __( 'Dachas', 'praktik' ),
				'singular_name' => __( 'Dacha', 'praktik' ),
			],
			'slug' => 'dacha',
			'icon' => 'dashicons-admin-site',
		],
	];
}