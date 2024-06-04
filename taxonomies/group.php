<?php

/**
 * Registers the `group` taxonomy,
 * for use with 'alumnus'.
 */
function group_init() {
	register_taxonomy( 'group', [ 'alumnus' ], [
		'hierarchical'          => false,
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
			'name'                       => __( 'Alumni Groups', 'efw-alumni' ),
			'singular_name'              => _x( 'Alumni Group', 'taxonomy general name', 'efw-alumni' ),
			'search_items'               => __( 'Search Alumni Groups', 'efw-alumni' ),
			'popular_items'              => __( 'Popular Alumni Groups', 'efw-alumni' ),
			'all_items'                  => __( 'All Alumni Groups', 'efw-alumni' ),
			'parent_item'                => __( 'Parent Alumni Group', 'efw-alumni' ),
			'parent_item_colon'          => __( 'Parent Alumni Group:', 'efw-alumni' ),
			'edit_item'                  => __( 'Edit Alumni Group', 'efw-alumni' ),
			'update_item'                => __( 'Update Alumni Group', 'efw-alumni' ),
			'view_item'                  => __( 'View Alumni Group', 'efw-alumni' ),
			'add_new_item'               => __( 'Add New Alumni Group', 'efw-alumni' ),
			'new_item_name'              => __( 'New Alumni Group', 'efw-alumni' ),
			'separate_items_with_commas' => __( 'Separate Alumni Groups with commas', 'efw-alumni' ),
			'add_or_remove_items'        => __( 'Add or remove Alumni Groups', 'efw-alumni' ),
			'choose_from_most_used'      => __( 'Choose from the most used Alumni Groups', 'efw-alumni' ),
			'not_found'                  => __( 'No Alumni Groups found.', 'efw-alumni' ),
			'no_terms'                   => __( 'No Alumni Groups', 'efw-alumni' ),
			'menu_name'                  => __( 'Alumni Groups', 'efw-alumni' ),
			'items_list_navigation'      => __( 'Alumni Groups list navigation', 'efw-alumni' ),
			'items_list'                 => __( 'Alumni Groups list', 'efw-alumni' ),
			'most_used'                  => _x( 'Most Used', 'group', 'efw-alumni' ),
			'back_to_items'              => __( '&larr; Back to Alumni Groups', 'efw-alumni' ),
		],
		'show_in_rest'          => true,
		'rest_base'             => 'group',
		'rest_controller_class' => 'WP_REST_Terms_Controller',
	] );

}

add_action( 'init', 'group_init' );

/**
 * Sets the post updated messages for the `group` taxonomy.
 *
 * @param  array $messages Post updated messages.
 * @return array Messages for the `group` taxonomy.
 */
function group_updated_messages( $messages ) {

	$messages['group'] = [
		0 => '', // Unused. Messages start at index 1.
		1 => __( 'Alumni Group added.', 'efw-alumni' ),
		2 => __( 'Alumni Group deleted.', 'efw-alumni' ),
		3 => __( 'Alumni Group updated.', 'efw-alumni' ),
		4 => __( 'Alumni Group not added.', 'efw-alumni' ),
		5 => __( 'Alumni Group not updated.', 'efw-alumni' ),
		6 => __( 'Alumni Groups deleted.', 'efw-alumni' ),
	];

	return $messages;
}

add_filter( 'term_updated_messages', 'group_updated_messages' );
