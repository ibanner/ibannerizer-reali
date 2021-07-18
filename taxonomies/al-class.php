<?php

/**
 * Registers the `al_class` taxonomy,
 * for use with 'alumnus'.
 */
function al_class_init() {
	register_taxonomy( 'al-class', [ 'alumnus' ], [
		'hierarchical'          => false,
		'public'                => true,
		'show_in_nav_menus'     => true,
		'show_ui'               => true,
		'show_admin_column'     => false,
		'query_var'             => true,
		'rewrite'               => true,
		'capabilities'          => [
			'manage_terms' => 'edit_posts',
			'edit_terms'   => 'edit_posts',
			'delete_terms' => 'edit_posts',
			'assign_terms' => 'edit_posts',
		],
		'labels'                => [
			'name'                       => __( 'Class Cohorts', 'ibn' ),
			'singular_name'              => _x( 'Class Cohort', 'taxonomy general name', 'ibn' ),
			'search_items'               => __( 'Search Class Cohorts', 'ibn' ),
			'popular_items'              => __( 'Popular Class Cohorts', 'ibn' ),
			'all_items'                  => __( 'All Class Cohorts', 'ibn' ),
			'parent_item'                => __( 'Parent Class Cohort', 'ibn' ),
			'parent_item_colon'          => __( 'Parent Class Cohort:', 'ibn' ),
			'edit_item'                  => __( 'Edit Class Cohort', 'ibn' ),
			'update_item'                => __( 'Update Class Cohort', 'ibn' ),
			'view_item'                  => __( 'View Class Cohort', 'ibn' ),
			'add_new_item'               => __( 'Add New Class Cohort', 'ibn' ),
			'new_item_name'              => __( 'New Class Cohort', 'ibn' ),
			'separate_items_with_commas' => __( 'Separate Class Cohorts with commas', 'ibn' ),
			'add_or_remove_items'        => __( 'Add or remove Class Cohorts', 'ibn' ),
			'choose_from_most_used'      => __( 'Choose from the most used Class Cohorts', 'ibn' ),
			'not_found'                  => __( 'No Class Cohorts found.', 'ibn' ),
			'no_terms'                   => __( 'No Class Cohorts', 'ibn' ),
			'menu_name'                  => __( 'Class Cohorts', 'ibn' ),
			'items_list_navigation'      => __( 'Class Cohorts list navigation', 'ibn' ),
			'items_list'                 => __( 'Class Cohorts list', 'ibn' ),
			'most_used'                  => _x( 'Most Used', 'al-class', 'ibn' ),
			'back_to_items'              => __( '&larr; Back to Class Cohorts', 'ibn' ),
		],
		'show_in_rest'          => true,
		'rest_base'             => 'al-class',
		'rest_controller_class' => 'WP_REST_Terms_Controller',
	] );

}

add_action( 'init', 'al_class_init' );

/**
 * Sets the post updated messages for the `al_class` taxonomy.
 *
 * @param  array $messages Post updated messages.
 * @return array Messages for the `al_class` taxonomy.
 */
function al_class_updated_messages( $messages ) {

	$messages['al-class'] = [
		0 => '', // Unused. Messages start at index 1.
		1 => __( 'Class Cohort added.', 'ibn' ),
		2 => __( 'Class Cohort deleted.', 'ibn' ),
		3 => __( 'Class Cohort updated.', 'ibn' ),
		4 => __( 'Class Cohort not added.', 'ibn' ),
		5 => __( 'Class Cohort not updated.', 'ibn' ),
		6 => __( 'Class Cohorts deleted.', 'ibn' ),
	];

	return $messages;
}

add_filter( 'term_updated_messages', 'al_class_updated_messages' );
