<?php

/**
 * Отримати масив пост тайпів нерухомості з перекладами
 */
function get_property_post_types() {
    return [
        'room' => __('Rooms', 'sage'),
        'apartment' => __('Apartments', 'sage'),
        'house' => __('Houses', 'sage'),
        'plot' => __('Plots', 'sage'),
        'garage' => __('Garages', 'sage'),
        'commercial' => __('Commercial', 'sage'),
        'dacha' => __('Dachas', 'sage')
    ];
}
