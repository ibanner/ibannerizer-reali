<?php

/**
 * Registers the `honors` taxonomy,
 * for use with 'alumnus'.
 */
function honors_init() {
	register_taxonomy( 'honors', [ 'alumnus' ], [
		'hierarchical'          => true,
		'public'                => true,
		'show_in_nav_menus'     => true,
		'show_ui'               => true,
		'show_admin_column'     => true,
		'query_var'             => true,
		'rewrite'               => true,
		'capabilities'          => [
			'manage_terms' => 'edit_posts',
			'edit_terms'   => 'edit_posts',
			'delete_terms' => 'edit_posts',
			'assign_terms' => 'edit_posts',
		],
		'labels'                => [
			'name'                       => __( 'Honors', 'ibn' ),
			'singular_name'              => _x( 'Honor', 'taxonomy general name', 'ibn' ),
			'search_items'               => __( 'Search Honors', 'ibn' ),
			'popular_items'              => __( 'Popular Honors', 'ibn' ),
			'all_items'                  => __( 'All Honors', 'ibn' ),
			'parent_item'                => __( 'Parent Honor', 'ibn' ),
			'parent_item_colon'          => __( 'Parent Honor:', 'ibn' ),
			'edit_item'                  => __( 'Edit Honor', 'ibn' ),
			'update_item'                => __( 'Update Honor', 'ibn' ),
			'view_item'                  => __( 'View Honor', 'ibn' ),
			'add_new_item'               => __( 'Add New Honor', 'ibn' ),
			'new_item_name'              => __( 'New Honor', 'ibn' ),
			'separate_items_with_commas' => __( 'Separate Honors with commas', 'ibn' ),
			'add_or_remove_items'        => __( 'Add or remove Honors', 'ibn' ),
			'choose_from_most_used'      => __( 'Choose from the most used Honors', 'ibn' ),
			'not_found'                  => __( 'No Honors found.', 'ibn' ),
			'no_terms'                   => __( 'No Honors', 'ibn' ),
			'menu_name'                  => __( 'Honors', 'ibn' ),
			'items_list_navigation'      => __( 'Honors list navigation', 'ibn' ),
			'items_list'                 => __( 'Honors list', 'ibn' ),
			'most_used'                  => _x( 'Most Used', 'honors', 'ibn' ),
			'back_to_items'              => __( '&larr; Back to Honors', 'ibn' ),
		],
		'show_in_rest'          => true,
		'rest_base'             => 'honors',
		'rest_controller_class' => 'WP_REST_Terms_Controller',
	] );

}

add_action( 'init', 'honors_init' );

/**
 * Sets the post updated messages for the `honors` taxonomy.
 *
 * @param  array $messages Post updated messages.
 * @return array Messages for the `honors` taxonomy.
 */
function honors_updated_messages( $messages ) {

	$messages['honors'] = [
		0 => '', // Unused. Messages start at index 1.
		1 => __( 'Honor added.', 'ibn' ),
		2 => __( 'Honor deleted.', 'ibn' ),
		3 => __( 'Honor updated.', 'ibn' ),
		4 => __( 'Honor not added.', 'ibn' ),
		5 => __( 'Honor not updated.', 'ibn' ),
		6 => __( 'Honors deleted.', 'ibn' ),
	];

	return $messages;
}

add_filter( 'term_updated_messages', 'honors_updated_messages' );
