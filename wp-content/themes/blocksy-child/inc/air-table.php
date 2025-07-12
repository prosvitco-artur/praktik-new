<?php
add_filter('airwpsync/get_wp_fields', function (array $fields) {
  foreach ($fields as $key => $field) {
    $fields[$key]['options'] = array_map(function ($option) {
      $option['enabled'] = true;
      $option['label'] = str_replace(' (Pro+ version)', '', $option['label']);
      return $option;
    }, $field['options']);
  }
  return $fields;
}, 9999, 1);