<?php

namespace App\Fields;

use Carbon_Fields\Container;
use Carbon_Fields\Field;

/**
 * Register Carbon Fields for realtor post type
 */
class RealtorFields {
    /**
     * Register all realtor fields
     */
    public static function register() {
        Container::make('post_meta', __('Realtor Information', 'praktik'))
            ->where('post_type', '=', 'realtor')
            ->add_fields([
                Field::make('text', 'realtor_name', __('Name', 'praktik'))
                    ->set_help_text(__('Name of the realtor', 'praktik')),
                
                Field::make('image', 'realtor_photo', __('Photo', 'praktik'))
                    ->set_value_type( 'url' )
                    ->set_help_text(__('Photo of the realtor', 'praktik')),
                
                Field::make('text', 'realtor_phone', __('Phone', 'praktik'))
                    ->set_help_text(__('Phone number of the realtor', 'praktik')),
                
                Field::make('text', 'realtor_telegram', __('Telegram', 'praktik'))
                    ->set_help_text(__('Telegram username (without @)', 'praktik')),
                Field::make('text', 'realtor_viber', __('Viber', 'praktik'))
                    ->set_help_text(__('Viber username (without @)', 'praktik')),
                Field::make('text', 'realtor_instagram', __('Instagram', 'praktik'))
                    ->set_help_text(__('Instagram username (without @)', 'praktik')),
                    
            ]);
    }
}


