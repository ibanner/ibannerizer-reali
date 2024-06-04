<?php

/**
 * Registers the `award` post type.
 */
function award_init() {
	register_post_type(
		'award',
		[
			'labels'                => [
				'name'                  => __( 'Awards', 'efw-alumni' ),
				'singular_name'         => __( 'Award', 'efw-alumni' ),
				'all_items'             => __( 'All Awards', 'efw-alumni' ),
				'archives'              => __( 'Award Archives', 'efw-alumni' ),
				'attributes'            => __( 'Award Attributes', 'efw-alumni' ),
				'insert_into_item'      => __( 'Insert into Award', 'efw-alumni' ),
				'uploaded_to_this_item' => __( 'Uploaded to this Award', 'efw-alumni' ),
				'featured_image'        => _x( 'Featured Image', 'award', 'efw-alumni' ),
				'set_featured_image'    => _x( 'Set featured image', 'award', 'efw-alumni' ),
				'remove_featured_image' => _x( 'Remove featured image', 'award', 'efw-alumni' ),
				'use_featured_image'    => _x( 'Use as featured image', 'award', 'efw-alumni' ),
				'filter_items_list'     => __( 'Filter Awards list', 'efw-alumni' ),
				'items_list_navigation' => __( 'Awards list navigation', 'efw-alumni' ),
				'items_list'            => __( 'Awards list', 'efw-alumni' ),
				'new_item'              => __( 'New Award', 'efw-alumni' ),
				'add_new'               => __( 'Add New', 'efw-alumni' ),
				'add_new_item'          => __( 'Add New Award', 'efw-alumni' ),
				'edit_item'             => __( 'Edit Award', 'efw-alumni' ),
				'view_item'             => __( 'View Award', 'efw-alumni' ),
				'view_items'            => __( 'View Awards', 'efw-alumni' ),
				'search_items'          => __( 'Search Awards', 'efw-alumni' ),
				'not_found'             => __( 'No Awards found', 'efw-alumni' ),
				'not_found_in_trash'    => __( 'No Awards found in trash', 'efw-alumni' ),
				'parent_item_colon'     => __( 'Parent Award:', 'efw-alumni' ),
				'menu_name'             => __( 'Awards', 'efw-alumni' ),
			],
			'public'                => true,
			'hierarchical'          => false,
			'show_ui'               => true,
			'show_in_nav_menus'     => true,
			'supports'              => [ 'title', 'thumbnail', 'page-attributes' ],
			'has_archive'           => true,
			'rewrite'               => true,
			'query_var'             => true,
			'menu_position'         => null,
			'menu_icon'             => 'dashicons-awards',
			'show_in_rest'          => true,
			'rest_base'             => 'award',
			'rest_controller_class' => 'WP_REST_Posts_Controller',
		]
	);

}

add_action( 'init', 'award_init' );

/**
 * Sets the post updated messages for the `award` post type.
 *
 * @param  array $messages Post updated messages.
 * @return array Messages for the `award` post type.
 */
function award_updated_messages( $messages ) {
	global $post;

	$permalink = get_permalink( $post );

	$messages['award'] = [
		0  => '', // Unused. Messages start at index 1.
		/* translators: %s: post permalink */
		1  => sprintf( __( 'Award updated. <a target="_blank" href="%s">View Award</a>', 'efw-alumni' ), esc_url( $permalink ) ),
		2  => __( 'Custom field updated.', 'efw-alumni' ),
		3  => __( 'Custom field deleted.', 'efw-alumni' ),
		4  => __( 'Award updated.', 'efw-alumni' ),
		/* translators: %s: date and time of the revision */
		5  => isset( $_GET['revision'] ) ? sprintf( __( 'Award restored to revision from %s', 'efw-alumni' ), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false, // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		/* translators: %s: post permalink */
		6  => sprintf( __( 'Award published. <a href="%s">View Award</a>', 'efw-alumni' ), esc_url( $permalink ) ),
		7  => __( 'Award saved.', 'efw-alumni' ),
		/* translators: %s: post permalink */
		8  => sprintf( __( 'Award submitted. <a target="_blank" href="%s">Preview Award</a>', 'efw-alumni' ), esc_url( add_query_arg( 'preview', 'true', $permalink ) ) ),
		/* translators: 1: Publish box date format, see https://secure.php.net/date 2: Post permalink */
		9  => sprintf( __( 'Award scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview Award</a>', 'efw-alumni' ), date_i18n( __( 'M j, Y @ G:i', 'efw-alumni' ), strtotime( $post->post_date ) ), esc_url( $permalink ) ),
		/* translators: %s: post permalink */
		10 => sprintf( __( 'Award draft updated. <a target="_blank" href="%s">Preview Award</a>', 'efw-alumni' ), esc_url( add_query_arg( 'preview', 'true', $permalink ) ) ),
	];

	return $messages;
}

add_filter( 'post_updated_messages', 'award_updated_messages' );

/**
 * Sets the bulk post updated messages for the `award` post type.
 *
 * @param  array $bulk_messages Arrays of messages, each keyed by the corresponding post type. Messages are
 *                              keyed with 'updated', 'locked', 'deleted', 'trashed', and 'untrashed'.
 * @param  int[] $bulk_counts   Array of item counts for each message, used to build internationalized strings.
 * @return array Bulk messages for the `award` post type.
 */
function award_bulk_updated_messages( $bulk_messages, $bulk_counts ) {
	global $post;

	$bulk_messages['award'] = [
		/* translators: %s: Number of Awards. */
		'updated'   => _n( '%s Award updated.', '%s Awards updated.', $bulk_counts['updated'], 'efw-alumni' ),
		'locked'    => ( 1 === $bulk_counts['locked'] ) ? __( '1 Award not updated, somebody is editing it.', 'efw-alumni' ) :
						/* translators: %s: Number of Awards. */
						_n( '%s Award not updated, somebody is editing it.', '%s Awards not updated, somebody is editing them.', $bulk_counts['locked'], 'efw-alumni' ),
		/* translators: %s: Number of Awards. */
		'deleted'   => _n( '%s Award permanently deleted.', '%s Awards permanently deleted.', $bulk_counts['deleted'], 'efw-alumni' ),
		/* translators: %s: Number of Awards. */
		'trashed'   => _n( '%s Award moved to the Trash.', '%s Awards moved to the Trash.', $bulk_counts['trashed'], 'efw-alumni' ),
		/* translators: %s: Number of Awards. */
		'untrashed' => _n( '%s Award restored from the Trash.', '%s Awards restored from the Trash.', $bulk_counts['untrashed'], 'efw-alumni' ),
	];

	return $bulk_messages;
}

add_filter( 'bulk_post_updated_messages', 'award_bulk_updated_messages', 10, 2 );
