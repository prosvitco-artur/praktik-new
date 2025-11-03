<?php

namespace App;

use Carbon_Fields\Container;
use Carbon_Fields\Field;

add_action('carbon_fields_register_fields', function() {
    Container::make('theme_options', __('Theme Settings', 'praktik'))
        ->add_tab(__('Social Media', 'praktik'), [
            Field::make('text', 'social_telegram', __('Telegram', 'praktik'))
                ->set_help_text(__('Enter full URL (e.g., https://t.me/username)', 'praktik'))
                ->set_attribute('placeholder', 'https://t.me/'),
            
            Field::make('text', 'social_youtube', __('YouTube', 'praktik'))
                ->set_help_text(__('Enter full URL (e.g., https://youtube.com/@channel)', 'praktik'))
                ->set_attribute('placeholder', 'https://youtube.com/'),
            
            Field::make('text', 'social_instagram', __('Instagram', 'praktik'))
                ->set_help_text(__('Enter full URL (e.g., https://instagram.com/username)', 'praktik'))
                ->set_attribute('placeholder', 'https://instagram.com/'),
            
            Field::make('text', 'social_tiktok', __('TikTok', 'praktik'))
                ->set_help_text(__('Enter full URL (e.g., https://tiktok.com/@username)', 'praktik'))
                ->set_attribute('placeholder', 'https://tiktok.com/'),
            
            Field::make('text', 'social_facebook', __('Facebook', 'praktik'))
                ->set_help_text(__('Enter full URL (e.g., https://facebook.com/page)', 'praktik'))
                ->set_attribute('placeholder', 'https://facebook.com/'),
        ])
        ->add_tab( __('Review Archive', 'praktik'), [
                Field::make('image', 'review_archive_banner', __('Review Archive Banner')),
        ]);
});

