<?php

/**
 * Registers the `honors` taxonomy,
 * for use with 'alumnus'.
 */
function honors_init() {
	register_taxonomy( 'honors', [ 'alumnus', 'award' ], [
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
			'name'                       => __( 'Honors', 'efw-alumni' ),
			'singular_name'              => _x( 'Honor', 'taxonomy general name', 'efw-alumni' ),
			'search_items'               => __( 'Search Honors', 'efw-alumni' ),
			'popular_items'              => __( 'Popular Honors', 'efw-alumni' ),
			'all_items'                  => __( 'All Honors', 'efw-alumni' ),
			'parent_item'                => __( 'Parent Honor', 'efw-alumni' ),
			'parent_item_colon'          => __( 'Parent Honor:', 'efw-alumni' ),
			'edit_item'                  => __( 'Edit Honor', 'efw-alumni' ),
			'update_item'                => __( 'Update Honor', 'efw-alumni' ),
			'view_item'                  => __( 'View Honor', 'efw-alumni' ),
			'add_new_item'               => __( 'Add New Honor', 'efw-alumni' ),
			'new_item_name'              => __( 'New Honor', 'efw-alumni' ),
			'separate_items_with_commas' => __( 'Separate Honors with commas', 'efw-alumni' ),
			'add_or_remove_items'        => __( 'Add or remove Honors', 'efw-alumni' ),
			'choose_from_most_used'      => __( 'Choose from the most used Honors', 'efw-alumni' ),
			'not_found'                  => __( 'No Honors found.', 'efw-alumni' ),
			'no_terms'                   => __( 'No Honors', 'efw-alumni' ),
			'menu_name'                  => __( 'Honors', 'efw-alumni' ),
			'items_list_navigation'      => __( 'Honors list navigation', 'efw-alumni' ),
			'items_list'                 => __( 'Honors list', 'efw-alumni' ),
			'most_used'                  => _x( 'Most Used', 'honors', 'efw-alumni' ),
			'back_to_items'              => __( '&larr; Back to Honors', 'efw-alumni' ),
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
		1 => __( 'Honor added.', 'efw-alumni' ),
		2 => __( 'Honor deleted.', 'efw-alumni' ),
		3 => __( 'Honor updated.', 'efw-alumni' ),
		4 => __( 'Honor not added.', 'efw-alumni' ),
		5 => __( 'Honor not updated.', 'efw-alumni' ),
		6 => __( 'Honors deleted.', 'efw-alumni' ),
	];

	return $messages;
}

add_filter( 'term_updated_messages', 'honors_updated_messages' );
