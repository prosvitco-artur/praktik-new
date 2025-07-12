<?php

/**
 * Set default enable value for existing posts
 */
add_action( 'admin_init', 'set_default_enable_for_existing_posts' );

function set_default_enable_for_existing_posts() {
	$custom_post_types = [ 'room', 'apartment', 'house', 'plot', 'garage', 'commercial', 'dacha' ];
	
	foreach ( $custom_post_types as $post_type ) {
		$posts = get_posts( [
			'post_type' => $post_type,
			'numberposts' => -1,
			'post_status' => 'publish',
			'meta_query' => [
				[
					'key' => '_crb_enable',
					'compare' => 'NOT EXISTS'
				]
			]
		] );
		
		foreach ( $posts as $post ) {
			carbon_set_post_meta( $post->ID, 'crb_enable', true );
		}
	}
}

/**
 * Auto delete posts when enable field is false
 */
add_action( 'save_post', 'auto_delete_post_if_disabled', 10, 3 );

function auto_delete_post_if_disabled( $post_id, $post, $update ) {
	// Skip if this is an autosave
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	// Skip if user doesn't have permission to edit this post
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	// Only process custom post types
	$custom_post_types = [ 'room', 'apartment', 'house', 'plot', 'garage', 'commercial', 'dacha' ];
	if ( ! in_array( $post->post_type, $custom_post_types ) ) {
		return;
	}

	// Get the enable field value
	$enable = carbon_get_post_meta( $post_id, 'crb_enable' );

	// If enable is false, delete the post
	if ( $enable === false ) {
		// Remove the action to prevent infinite loop
		remove_action( 'save_post', 'auto_delete_post_if_disabled', 10 );
		
		// Delete the post
		wp_delete_post( $post_id, true );
		
		// Add a notice for the user
		add_action( 'admin_notices', function() {
			echo '<div class="notice notice-warning is-dismissible">';
			echo '<p>' . __( 'Post was automatically deleted because the "Enable" field was set to false.', 'praktik' ) . '</p>';
			echo '</div>';
		});
		
		// Re-add the action
		add_action( 'save_post', 'auto_delete_post_if_disabled', 10, 3 );
	}
}

/**
 * Add warning when enable field is unchecked
 */
add_action( 'admin_footer', 'add_enable_field_warning' );

function add_enable_field_warning() {
	$screen = get_current_screen();
	$custom_post_types = [ 'room', 'apartment', 'house', 'plot', 'garage', 'commercial', 'dacha' ];
	
	if ( $screen && in_array( $screen->post_type, $custom_post_types ) ) {
		?>
		<script type="text/javascript">
		jQuery(document).ready(function($) {
			$('input[name="carbon_fields_compact_input[crb_enable]"]').on('change', function() {
				if (!this.checked) {
					if (confirm('<?php _e( 'Warning: Unchecking the "Enable" field will automatically delete this post when you save. Are you sure you want to continue?', 'praktik' ); ?>')) {
						return true;
					} else {
						$(this).prop('checked', true);
						return false;
					}
				}
			});
		});
		</script>
		<?php
	}
}

/**
 * Hide disabled posts from frontend queries
 */
add_action( 'pre_get_posts', 'hide_disabled_posts_from_frontend' );

function hide_disabled_posts_from_frontend( $query ) {
	// Only modify frontend queries
	if ( is_admin() ) {
		return;
	}

	// Only modify queries for custom post types
	$custom_post_types = [ 'room', 'apartment', 'house', 'plot', 'garage', 'commercial', 'dacha' ];
	if ( ! $query->is_main_query() || ! in_array( $query->get( 'post_type' ), $custom_post_types ) ) {
		return;
	}

	// Add meta query to exclude disabled posts
	$meta_query = $query->get( 'meta_query' );
	if ( ! is_array( $meta_query ) ) {
		$meta_query = [];
	}

	$meta_query[] = [
		'relation' => 'OR',
		[
			'key' => '_crb_enable',
			'value' => '1',
			'compare' => '='
		],
		[
			'key' => '_crb_enable',
			'compare' => 'NOT EXISTS'
		]
	];

	$query->set( 'meta_query', $meta_query );
}

/**
 * Add Enable column to admin lists
 */
add_filter( 'manage_posts_columns', 'add_enable_column' );
add_filter( 'manage_pages_columns', 'add_enable_column' );

function add_enable_column( $columns ) {
	$screen = get_current_screen();
	$custom_post_types = [ 'room', 'apartment', 'house', 'plot', 'garage', 'commercial', 'dacha' ];
	
	if ( $screen && in_array( $screen->post_type, $custom_post_types ) ) {
		$columns['enable'] = __( 'Enable', 'praktik' );
	}
	
	return $columns;
}

/**
 * Display Enable column content
 */
add_action( 'manage_posts_custom_column', 'display_enable_column', 10, 2 );
add_action( 'manage_pages_custom_column', 'display_enable_column', 10, 2 );

function display_enable_column( $column, $post_id ) {
	if ( $column === 'enable' ) {
		$enable = carbon_get_post_meta( $post_id, 'crb_enable' );
		$status = $enable ? '✅' : '❌';
		$text = $enable ? __( 'Enabled', 'praktik' ) : __( 'Disabled', 'praktik' );
		echo '<span title="' . $text . '">' . $status . '</span>';
	}
}

/**
 * Add quick toggle for enable field
 */
add_action( 'admin_footer', 'add_quick_toggle_script' );

function add_quick_toggle_script() {
	$screen = get_current_screen();
	$custom_post_types = [ 'room', 'apartment', 'house', 'plot', 'garage', 'commercial', 'dacha' ];
	
	if ( $screen && in_array( $screen->post_type, $custom_post_types ) ) {
		?>
		<script type="text/javascript">
		jQuery(document).ready(function($) {
			$('.column-enable span').click(function() {
				var $span = $(this);
				var postId = $span.closest('tr').find('.check-column input').val();
				var currentStatus = $span.text().includes('✅');
				var newStatus = !currentStatus;
				
				$.ajax({
					url: ajaxurl,
					type: 'POST',
					data: {
						action: 'toggle_enable_status',
						post_id: postId,
						enable: newStatus,
						nonce: '<?php echo wp_create_nonce( 'toggle_enable_nonce' ); ?>'
					},
					success: function(response) {
						if (response.success) {
							if (newStatus) {
								$span.html('✅').attr('title', '<?php _e( 'Enabled', 'praktik' ); ?>');
							} else {
								$span.html('❌').attr('title', '<?php _e( 'Disabled', 'praktik' ); ?>');
							}
						} else {
							alert('Error: ' + response.data);
						}
					},
					error: function() {
						alert('<?php _e( 'Error occurred while updating status.', 'praktik' ); ?>');
					}
				});
			});
			
			$('.column-enable span').css('cursor', 'pointer');
		});
		</script>
		<?php
	}
}

/**
 * AJAX handler for toggling enable status
 */
add_action( 'wp_ajax_toggle_enable_status', 'handle_toggle_enable_status' );

function handle_toggle_enable_status() {
	// Verify nonce
	if ( ! wp_verify_nonce( $_POST['nonce'], 'toggle_enable_nonce' ) ) {
		wp_die( __( 'Security check failed.', 'praktik' ) );
	}
	
	$post_id = intval( $_POST['post_id'] );
	$enable = (bool) $_POST['enable'];
	
	// Check permissions
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		wp_die( __( 'You do not have permission to edit this post.', 'praktik' ) );
	}
	
	// Update the field
	carbon_set_post_meta( $post_id, 'crb_enable', $enable );
	
	// If disabling, delete the post
	if ( ! $enable ) {
		wp_delete_post( $post_id, true );
		wp_send_json_success( __( 'Post has been disabled and deleted.', 'praktik' ) );
	} else {
		wp_send_json_success( __( 'Post has been enabled.', 'praktik' ) );
	}
} 