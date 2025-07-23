<?php


add_action('wp_insert_post_data', function ($data, $postarr) {
  if ('airwpsync-connection' !== $postarr['post_type']) {
    return $data;
  }

  if ($data['post_status'] === 'draft') {
    $data['post_status'] = 'publish';
    return $data;
  }

  return $data;
}, 99, 2);


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

add_filter( 'airwpsync/get_post_types', function( $post_types ) {

  foreach ( $post_types as &$post_type ) {
      if ( $post_type['value'] !== 'custom' ) {
          $post_type['enabled'] = true;
          $post_type['label'] = preg_replace( '/\s*\(Pro\+\s*version\)/', '', $post_type['label'] );
      }
  }
  return $post_types;
}, 9999, 1);