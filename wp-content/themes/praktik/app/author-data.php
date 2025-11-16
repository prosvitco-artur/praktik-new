<?php

use Carbon_Fields\Container;
use Carbon_Fields\Field;

add_action('carbon_fields_register_fields', function () {
    Container::make('user_meta', 'Author Data')
        ->add_fields([
            Field::make('text', 'author_phone', __('Phone', 'praktik')),
            Field::make('image', 'author_photo', __('Photo', 'praktik'))
                ->set_value_type( 'url' ),

            Field::make('text', 'author_telegram', __('Telegram URL', 'praktik')),
            Field::make('text', 'author_viber', __('Viber URL', 'praktik')),
            Field::make('text', 'author_instagram', __('Instagram URL', 'praktik')),
        ]);
});