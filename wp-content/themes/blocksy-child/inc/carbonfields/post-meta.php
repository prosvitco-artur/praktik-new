<?php

use Carbon_Fields\Container;
use Carbon_Fields\Field;

foreach ( get_created_post_types() as $post_type => $config ) {
	Container::make( 'post_meta', $post_type )
		->where( 'post_type', '=', $post_type )
		->add_fields( [
			Field::make( 'text', 'crb_price', 'Price' ),
			Field::make( 'text', 'crb_image_link', 'Image Link' ),
			Field::make( 'checkbox', 'crb_enable', 'Enable' )
				->set_default_value( true )
				->set_help_text( 'Enable or disable this post' ),
		] );
}