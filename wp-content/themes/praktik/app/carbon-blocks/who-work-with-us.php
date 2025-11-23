<?php

use Carbon_Fields\Block;
use Carbon_Fields\Field;

Block::make('Who We WorkP With')
  ->add_fields([
    Field::make('complex', 'clients', __('Clients', 'praktik'))
      ->add_fields('client', [
        Field::make('image', 'client_logo', __('Client Logo', 'praktik')),
        Field::make('text', 'client_name', __('Client Name', 'praktik')),
        Field::make('text', 'client_text', __('Client Text', 'praktik')),
      ])
      ->set_layout('tabbed-horizontal')
      ->set_header_template('<% if (client_name) { %><%= client_name %><% } %>'),
  ])
  ->set_render_callback(function ($fields) {
    echo view('carbon-blocks.who-work-with-us', ['fields' => $fields])->render();
  });