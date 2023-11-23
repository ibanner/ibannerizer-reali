<?php

/**
 * Registers the `award` post type.
 */
function award_init() {
	register_post_type(
		'award',
		[
			'labels'                => [
				'name'                  => __( 'Awards', 'ibn' ),
				'singular_name'         => __( 'Award', 'ibn' ),
				'all_items'             => __( 'All Awards', 'ibn' ),
				'archives'              => __( 'Award Archives', 'ibn' ),
				'attributes'            => __( 'Award Attributes', 'ibn' ),
				'insert_into_item'      => __( 'Insert into Award', 'ibn' ),
				'uploaded_to_this_item' => __( 'Uploaded to this Award', 'ibn' ),
				'featured_image'        => _x( 'Featured Image', 'award', 'ibn' ),
				'set_featured_image'    => _x( 'Set featured image', 'award', 'ibn' ),
				'remove_featured_image' => _x( 'Remove featured image', 'award', 'ibn' ),
				'use_featured_image'    => _x( 'Use as featured image', 'award', 'ibn' ),
				'filter_items_list'     => __( 'Filter Awards list', 'ibn' ),
				'items_list_navigation' => __( 'Awards list navigation', 'ibn' ),
				'items_list'            => __( 'Awards list', 'ibn' ),
				'new_item'              => __( 'New Award', 'ibn' ),
				'add_new'               => __( 'Add New', 'ibn' ),
				'add_new_item'          => __( 'Add New Award', 'ibn' ),
				'edit_item'             => __( 'Edit Award', 'ibn' ),
				'view_item'             => __( 'View Award', 'ibn' ),
				'view_items'            => __( 'View Awards', 'ibn' ),
				'search_items'          => __( 'Search Awards', 'ibn' ),
				'not_found'             => __( 'No Awards found', 'ibn' ),
				'not_found_in_trash'    => __( 'No Awards found in trash', 'ibn' ),
				'parent_item_colon'     => __( 'Parent Award:', 'ibn' ),
				'menu_name'             => __( 'Awards', 'ibn' ),
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
		1  => sprintf( __( 'Award updated. <a target="_blank" href="%s">View Award</a>', 'ibn' ), esc_url( $permalink ) ),
		2  => __( 'Custom field updated.', 'ibn' ),
		3  => __( 'Custom field deleted.', 'ibn' ),
		4  => __( 'Award updated.', 'ibn' ),
		/* translators: %s: date and time of the revision */
		5  => isset( $_GET['revision'] ) ? sprintf( __( 'Award restored to revision from %s', 'ibn' ), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false, // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		/* translators: %s: post permalink */
		6  => sprintf( __( 'Award published. <a href="%s">View Award</a>', 'ibn' ), esc_url( $permalink ) ),
		7  => __( 'Award saved.', 'ibn' ),
		/* translators: %s: post permalink */
		8  => sprintf( __( 'Award submitted. <a target="_blank" href="%s">Preview Award</a>', 'ibn' ), esc_url( add_query_arg( 'preview', 'true', $permalink ) ) ),
		/* translators: 1: Publish box date format, see https://secure.php.net/date 2: Post permalink */
		9  => sprintf( __( 'Award scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview Award</a>', 'ibn' ), date_i18n( __( 'M j, Y @ G:i', 'ibn' ), strtotime( $post->post_date ) ), esc_url( $permalink ) ),
		/* translators: %s: post permalink */
		10 => sprintf( __( 'Award draft updated. <a target="_blank" href="%s">Preview Award</a>', 'ibn' ), esc_url( add_query_arg( 'preview', 'true', $permalink ) ) ),
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
		'updated'   => _n( '%s Award updated.', '%s Awards updated.', $bulk_counts['updated'], 'ibn' ),
		'locked'    => ( 1 === $bulk_counts['locked'] ) ? __( '1 Award not updated, somebody is editing it.', 'ibn' ) :
						/* translators: %s: Number of Awards. */
						_n( '%s Award not updated, somebody is editing it.', '%s Awards not updated, somebody is editing them.', $bulk_counts['locked'], 'ibn' ),
		/* translators: %s: Number of Awards. */
		'deleted'   => _n( '%s Award permanently deleted.', '%s Awards permanently deleted.', $bulk_counts['deleted'], 'ibn' ),
		/* translators: %s: Number of Awards. */
		'trashed'   => _n( '%s Award moved to the Trash.', '%s Awards moved to the Trash.', $bulk_counts['trashed'], 'ibn' ),
		/* translators: %s: Number of Awards. */
		'untrashed' => _n( '%s Award restored from the Trash.', '%s Awards restored from the Trash.', $bulk_counts['untrashed'], 'ibn' ),
	];

	return $bulk_messages;
}

add_filter( 'bulk_post_updated_messages', 'award_bulk_updated_messages', 10, 2 );
