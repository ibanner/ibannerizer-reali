<?php

/**
 * Registers the `alumnus` post type.
 */
function alumnus_init() {
	register_post_type(
		'alumnus',
		[
			'labels'                => [
				'name'                  => __( 'Alumni', 'efw-alumni' ),
				'singular_name'         => __( 'Alumnus', 'efw-alumni' ),
				'all_items'             => __( 'All Alumni', 'efw-alumni' ),
				'archives'              => __( 'Alumnus Archives', 'efw-alumni' ),
				'attributes'            => __( 'Alumnus Attributes', 'efw-alumni' ),
				'insert_into_item'      => __( 'Insert into Alumnus', 'efw-alumni' ),
				'uploaded_to_this_item' => __( 'Uploaded to this Alumnus', 'efw-alumni' ),
				'featured_image'        => _x( 'Featured Image', 'alumnus', 'efw-alumni' ),
				'set_featured_image'    => _x( 'Set featured image', 'alumnus', 'efw-alumni' ),
				'remove_featured_image' => _x( 'Remove featured image', 'alumnus', 'efw-alumni' ),
				'use_featured_image'    => _x( 'Use as featured image', 'alumnus', 'efw-alumni' ),
				'filter_items_list'     => __( 'Filter Alumni list', 'efw-alumni' ),
				'items_list_navigation' => __( 'Alumni list navigation', 'efw-alumni' ),
				'items_list'            => __( 'Alumni list', 'efw-alumni' ),
				'new_item'              => __( 'New Alumnus', 'efw-alumni' ),
				'add_new'               => __( 'Add New', 'efw-alumni' ),
				'add_new_item'          => __( 'Add New Alumnus', 'efw-alumni' ),
				'edit_item'             => __( 'Edit Alumnus', 'efw-alumni' ),
				'view_item'             => __( 'View Alumnus', 'efw-alumni' ),
				'view_items'            => __( 'View Alumni', 'efw-alumni' ),
				'search_items'          => __( 'Search Alumni', 'efw-alumni' ),
				'not_found'             => __( 'No Alumni found', 'efw-alumni' ),
				'not_found_in_trash'    => __( 'No Alumni found in trash', 'efw-alumni' ),
				'parent_item_colon'     => __( 'Parent Alumnus:', 'efw-alumni' ),
				'menu_name'             => __( 'Alumni', 'efw-alumni' ),
			],
			'public'                => true,
			'hierarchical'          => false,
			'show_ui'               => true,
			'show_in_nav_menus'     => true,
			'supports'              => [ 'title', 'thumbnail', 'author' ],
			'has_archive'           => true,
			'rewrite'               => true,
			'query_var'             => true,
			'menu_position'         => null,
			'menu_icon'             => 'dashicons-welcome-learn-more',
			'show_in_rest'          => true,
			'rest_base'             => 'alumnus',
			'rest_controller_class' => 'WP_REST_Posts_Controller',
		]
	);

}

add_action( 'init', 'alumnus_init' );

/**
 * Sets the post updated messages for the `alumnus` post type.
 *
 * @param  array $messages Post updated messages.
 * @return array Messages for the `alumnus` post type.
 */
function alumnus_updated_messages( $messages ) {
	global $post;

	$permalink = get_permalink( $post );

	$messages['alumnus'] = [
		0  => '', // Unused. Messages start at index 1.
		/* translators: %s: post permalink */
		1  => sprintf( __( 'Alumnus updated. <a target="_blank" href="%s">View Alumnus</a>', 'efw-alumni' ), esc_url( $permalink ) ),
		2  => __( 'Custom field updated.', 'efw-alumni' ),
		3  => __( 'Custom field deleted.', 'efw-alumni' ),
		4  => __( 'Alumnus updated.', 'efw-alumni' ),
		/* translators: %s: date and time of the revision */
		5  => isset( $_GET['revision'] ) ? sprintf( __( 'Alumnus restored to revision from %s', 'efw-alumni' ), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false, // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		/* translators: %s: post permalink */
		6  => sprintf( __( 'Alumnus published. <a href="%s">View Alumnus</a>', 'efw-alumni' ), esc_url( $permalink ) ),
		7  => __( 'Alumnus saved.', 'efw-alumni' ),
		/* translators: %s: post permalink */
		8  => sprintf( __( 'Alumnus submitted. <a target="_blank" href="%s">Preview Alumnus</a>', 'efw-alumni' ), esc_url( add_query_arg( 'preview', 'true', $permalink ) ) ),
		/* translators: 1: Publish box date format, see https://secure.php.net/date 2: Post permalink */
		9  => sprintf( __( 'Alumnus scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview Alumnus</a>', 'efw-alumni' ), date_i18n( __( 'M j, Y @ G:i', 'efw-alumni' ), strtotime( $post->post_date ) ), esc_url( $permalink ) ),
		/* translators: %s: post permalink */
		10 => sprintf( __( 'Alumnus draft updated. <a target="_blank" href="%s">Preview Alumnus</a>', 'efw-alumni' ), esc_url( add_query_arg( 'preview', 'true', $permalink ) ) ),
	];

	return $messages;
}

add_filter( 'post_updated_messages', 'alumnus_updated_messages' );

/**
 * Sets the bulk post updated messages for the `alumnus` post type.
 *
 * @param  array $bulk_messages Arrays of messages, each keyed by the corresponding post type. Messages are
 *                              keyed with 'updated', 'locked', 'deleted', 'trashed', and 'untrashed'.
 * @param  int[] $bulk_counts   Array of item counts for each message, used to build internationalized strings.
 * @return array Bulk messages for the `alumnus` post type.
 */
function alumnus_bulk_updated_messages( $bulk_messages, $bulk_counts ) {
	global $post;

	$bulk_messages['alumnus'] = [
		/* translators: %s: Number of Alumni. */
		'updated'   => _n( '%s Alumnus updated.', '%s Alumni updated.', $bulk_counts['updated'], 'efw-alumni' ),
		'locked'    => ( 1 === $bulk_counts['locked'] ) ? __( '1 Alumnus not updated, somebody is editing it.', 'efw-alumni' ) :
						/* translators: %s: Number of Alumni. */
						_n( '%s Alumnus not updated, somebody is editing it.', '%s Alumni not updated, somebody is editing them.', $bulk_counts['locked'], 'efw-alumni' ),
		/* translators: %s: Number of Alumni. */
		'deleted'   => _n( '%s Alumnus permanently deleted.', '%s Alumni permanently deleted.', $bulk_counts['deleted'], 'efw-alumni' ),
		/* translators: %s: Number of Alumni. */
		'trashed'   => _n( '%s Alumnus moved to the Trash.', '%s Alumni moved to the Trash.', $bulk_counts['trashed'], 'efw-alumni' ),
		/* translators: %s: Number of Alumni. */
		'untrashed' => _n( '%s Alumnus restored from the Trash.', '%s Alumni restored from the Trash.', $bulk_counts['untrashed'], 'efw-alumni' ),
	];

	return $bulk_messages;
}

add_filter( 'bulk_post_updated_messages', 'alumnus_bulk_updated_messages', 10, 2 );
