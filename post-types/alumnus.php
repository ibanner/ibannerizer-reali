<?php

/**
 * Registers the `alumnus` post type.
 */
function alumnus_init() {
	register_post_type(
		'alumnus',
		[
			'labels'                => [
				'name'                  => __( 'Alumni', 'ibn' ),
				'singular_name'         => __( 'Alumnus', 'ibn' ),
				'all_items'             => __( 'All Alumni', 'ibn' ),
				'archives'              => __( 'Alumnus Archives', 'ibn' ),
				'attributes'            => __( 'Alumnus Attributes', 'ibn' ),
				'insert_into_item'      => __( 'Insert into Alumnus', 'ibn' ),
				'uploaded_to_this_item' => __( 'Uploaded to this Alumnus', 'ibn' ),
				'featured_image'        => _x( 'Featured Image', 'alumnus', 'ibn' ),
				'set_featured_image'    => _x( 'Set featured image', 'alumnus', 'ibn' ),
				'remove_featured_image' => _x( 'Remove featured image', 'alumnus', 'ibn' ),
				'use_featured_image'    => _x( 'Use as featured image', 'alumnus', 'ibn' ),
				'filter_items_list'     => __( 'Filter Alumni list', 'ibn' ),
				'items_list_navigation' => __( 'Alumni list navigation', 'ibn' ),
				'items_list'            => __( 'Alumni list', 'ibn' ),
				'new_item'              => __( 'New Alumnus', 'ibn' ),
				'add_new'               => __( 'Add New', 'ibn' ),
				'add_new_item'          => __( 'Add New Alumnus', 'ibn' ),
				'edit_item'             => __( 'Edit Alumnus', 'ibn' ),
				'view_item'             => __( 'View Alumnus', 'ibn' ),
				'view_items'            => __( 'View Alumni', 'ibn' ),
				'search_items'          => __( 'Search Alumni', 'ibn' ),
				'not_found'             => __( 'No Alumni found', 'ibn' ),
				'not_found_in_trash'    => __( 'No Alumni found in trash', 'ibn' ),
				'parent_item_colon'     => __( 'Parent Alumnus:', 'ibn' ),
				'menu_name'             => __( 'Alumni', 'ibn' ),
			],
			'public'                => true,
			'hierarchical'          => false,
			'show_ui'               => true,
			'show_in_nav_menus'     => true,
			'supports'              => [ 'title', 'editor', 'thumbnail' ],
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
		1  => sprintf( __( 'Alumnus updated. <a target="_blank" href="%s">View Alumnus</a>', 'ibn' ), esc_url( $permalink ) ),
		2  => __( 'Custom field updated.', 'ibn' ),
		3  => __( 'Custom field deleted.', 'ibn' ),
		4  => __( 'Alumnus updated.', 'ibn' ),
		/* translators: %s: date and time of the revision */
		5  => isset( $_GET['revision'] ) ? sprintf( __( 'Alumnus restored to revision from %s', 'ibn' ), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false, // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		/* translators: %s: post permalink */
		6  => sprintf( __( 'Alumnus published. <a href="%s">View Alumnus</a>', 'ibn' ), esc_url( $permalink ) ),
		7  => __( 'Alumnus saved.', 'ibn' ),
		/* translators: %s: post permalink */
		8  => sprintf( __( 'Alumnus submitted. <a target="_blank" href="%s">Preview Alumnus</a>', 'ibn' ), esc_url( add_query_arg( 'preview', 'true', $permalink ) ) ),
		/* translators: 1: Publish box date format, see https://secure.php.net/date 2: Post permalink */
		9  => sprintf( __( 'Alumnus scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview Alumnus</a>', 'ibn' ), date_i18n( __( 'M j, Y @ G:i', 'ibn' ), strtotime( $post->post_date ) ), esc_url( $permalink ) ),
		/* translators: %s: post permalink */
		10 => sprintf( __( 'Alumnus draft updated. <a target="_blank" href="%s">Preview Alumnus</a>', 'ibn' ), esc_url( add_query_arg( 'preview', 'true', $permalink ) ) ),
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
		'updated'   => _n( '%s Alumnus updated.', '%s Alumni updated.', $bulk_counts['updated'], 'ibn' ),
		'locked'    => ( 1 === $bulk_counts['locked'] ) ? __( '1 Alumnus not updated, somebody is editing it.', 'ibn' ) :
						/* translators: %s: Number of Alumni. */
						_n( '%s Alumnus not updated, somebody is editing it.', '%s Alumni not updated, somebody is editing them.', $bulk_counts['locked'], 'ibn' ),
		/* translators: %s: Number of Alumni. */
		'deleted'   => _n( '%s Alumnus permanently deleted.', '%s Alumni permanently deleted.', $bulk_counts['deleted'], 'ibn' ),
		/* translators: %s: Number of Alumni. */
		'trashed'   => _n( '%s Alumnus moved to the Trash.', '%s Alumni moved to the Trash.', $bulk_counts['trashed'], 'ibn' ),
		/* translators: %s: Number of Alumni. */
		'untrashed' => _n( '%s Alumnus restored from the Trash.', '%s Alumni restored from the Trash.', $bulk_counts['untrashed'], 'ibn' ),
	];

	return $bulk_messages;
}

add_filter( 'bulk_post_updated_messages', 'alumnus_bulk_updated_messages', 10, 2 );
