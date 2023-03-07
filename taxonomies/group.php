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
			'name'                       => __( 'Alumni Groups', 'ibn' ),
			'singular_name'              => _x( 'Alumni Group', 'taxonomy general name', 'ibn' ),
			'search_items'               => __( 'Search Alumni Groups', 'ibn' ),
			'popular_items'              => __( 'Popular Alumni Groups', 'ibn' ),
			'all_items'                  => __( 'All Alumni Groups', 'ibn' ),
			'parent_item'                => __( 'Parent Alumni Group', 'ibn' ),
			'parent_item_colon'          => __( 'Parent Alumni Group:', 'ibn' ),
			'edit_item'                  => __( 'Edit Alumni Group', 'ibn' ),
			'update_item'                => __( 'Update Alumni Group', 'ibn' ),
			'view_item'                  => __( 'View Alumni Group', 'ibn' ),
			'add_new_item'               => __( 'Add New Alumni Group', 'ibn' ),
			'new_item_name'              => __( 'New Alumni Group', 'ibn' ),
			'separate_items_with_commas' => __( 'Separate Alumni Groups with commas', 'ibn' ),
			'add_or_remove_items'        => __( 'Add or remove Alumni Groups', 'ibn' ),
			'choose_from_most_used'      => __( 'Choose from the most used Alumni Groups', 'ibn' ),
			'not_found'                  => __( 'No Alumni Groups found.', 'ibn' ),
			'no_terms'                   => __( 'No Alumni Groups', 'ibn' ),
			'menu_name'                  => __( 'Alumni Groups', 'ibn' ),
			'items_list_navigation'      => __( 'Alumni Groups list navigation', 'ibn' ),
			'items_list'                 => __( 'Alumni Groups list', 'ibn' ),
			'most_used'                  => _x( 'Most Used', 'group', 'ibn' ),
			'back_to_items'              => __( '&larr; Back to Alumni Groups', 'ibn' ),
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
		1 => __( 'Alumni Group added.', 'ibn' ),
		2 => __( 'Alumni Group deleted.', 'ibn' ),
		3 => __( 'Alumni Group updated.', 'ibn' ),
		4 => __( 'Alumni Group not added.', 'ibn' ),
		5 => __( 'Alumni Group not updated.', 'ibn' ),
		6 => __( 'Alumni Groups deleted.', 'ibn' ),
	];

	return $messages;
}

add_filter( 'term_updated_messages', 'group_updated_messages' );
