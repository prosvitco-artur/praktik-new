<?php

/**
 * Plugin modifications.
 */

namespace App;

/*
add_filter('wp_insert_post_data', 'remove_air_wp_sync_connection_limit', 5, 2);

function remove_air_wp_sync_connection_limit($data, $postarr) {
    if ('airwpsync-connection' !== $postarr['post_type']) {
        return $data;
    }
    return $data;
}

// Alternative: Hook into the specific class method
add_action('init', 'remove_air_wp_sync_admin_connection_limit');

function remove_air_wp_sync_admin_connection_limit() {
    // Remove the wp_insert_post_data method from the class
    remove_action('wp_insert_post_data', array('Air_WP_Sync_Free\Air_WP_Sync_Admin_Connection', 'wp_insert_post_data'), 10);
    
    // Add our own version without the limit
    add_action('wp_insert_post_data', 'custom_wp_insert_post_data', 10, 2);
}

function custom_wp_insert_post_data($data, $postarr) {
    if ('airwpsync-connection' !== $postarr['post_type']) {
        return $data;
    }
    
    // Only update the slug, skip the connection limit check
    if (array_key_exists('post_title', $data)) {
        $data['post_name'] = sanitize_title($data['post_title']);
    }
    
    return $data;
}

// Remove the admin notice about connection limit
add_action('admin_init', 'remove_air_wp_sync_admin_notices');

function remove_air_wp_sync_admin_notices() {
    // Remove the admin_notices action from the original class
    remove_action('admin_notices', array('Air_WP_Sync_Free\Air_WP_Sync_Admin_Connection', 'admin_notices'), 10);
}

// Remove the redirect location modification
add_action('admin_init', 'remove_air_wp_sync_redirect_location');

function remove_air_wp_sync_redirect_location() {
    // Remove the redirect_post_location action from the original class
    remove_action('redirect_post_location', array('Air_WP_Sync_Free\Air_WP_Sync_Admin_Connection', 'redirect_post_location'), 10);
} 

*/

// public function wp_insert_post_data( $data, $postarr ) {
//   if ( 'airwpsync-connection' !== $postarr['post_type'] ) {
//     return $data;
//   }

//   // Limit active importers.
//   $post_id = $postarr['ID'] ?? 0;

//   if ( ! isset( $data['post_status'] ) || 'publish' === $data['post_status'] ) {
//     $importers = get_posts(
//       array(
//         'post_type'      => 'airwpsync-connection',
//         'post_status'    => 'publish',
//         'posts_per_page' => -1,
//         'post__not_in'   => array( $post_id ),
//       )
//     );
//     if ( count( $importers ) > 0 ) {
//       $data['post_status']          = 'draft';
//       $this->display_max_connection = true;
//     }
//   }

//   // Update slug.
//   if ( array_key_exists( 'post_title', $data ) ) {
//     $data['post_name'] = sanitize_title( $data['post_title'] );
//   }

//   return $data;
// }
