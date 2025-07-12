<?php

/**
 * Load theme translations
 */
function praktik_load_theme_textdomain() {
	load_theme_textdomain( 'praktik', get_template_directory() . '/languages' );
}
add_action( 'after_setup_theme', 'praktik_load_theme_textdomain' ); 