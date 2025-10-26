<?php

use Carbon_Fields\Block;
use Carbon_Fields\Field;

Block::make(__('How We Work', 'praktik'))
    ->add_fields([
        Field::make('complex', 'steps', __('Steps', 'praktik'))
            ->add_fields('step', [
                Field::make('text', 'step_title', __('Step Title', 'praktik')),
                Field::make('text', 'step_description', __('Step Description', 'praktik')),
            ])
            ->set_layout('tabbed-horizontal')
            ->set_header_template('<% if (step_title) { %><%= step_title %><% } %>'),
    ])
    ->set_render_callback(function($fields) {
        echo view('carbon-blocks.how-we-work', ['fields' => $fields])->render();
    });

