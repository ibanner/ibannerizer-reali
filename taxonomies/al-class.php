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
			'name'                       => __( 'Class Cohorts', 'efw-alumni' ),
			'singular_name'              => _x( 'Class Cohort', 'taxonomy general name', 'efw-alumni' ),
			'search_items'               => __( 'Search Class Cohorts', 'efw-alumni' ),
			'popular_items'              => __( 'Popular Class Cohorts', 'efw-alumni' ),
			'all_items'                  => __( 'All Class Cohorts', 'efw-alumni' ),
			'parent_item'                => __( 'Parent Class Cohort', 'efw-alumni' ),
			'parent_item_colon'          => __( 'Parent Class Cohort:', 'efw-alumni' ),
			'edit_item'                  => __( 'Edit Class Cohort', 'efw-alumni' ),
			'update_item'                => __( 'Update Class Cohort', 'efw-alumni' ),
			'view_item'                  => __( 'View Class Cohort', 'efw-alumni' ),
			'add_new_item'               => __( 'Add New Class Cohort', 'efw-alumni' ),
			'new_item_name'              => __( 'New Class Cohort', 'efw-alumni' ),
			'separate_items_with_commas' => __( 'Separate Class Cohorts with commas', 'efw-alumni' ),
			'add_or_remove_items'        => __( 'Add or remove Class Cohorts', 'efw-alumni' ),
			'choose_from_most_used'      => __( 'Choose from the most used Class Cohorts', 'efw-alumni' ),
			'not_found'                  => __( 'No Class Cohorts found.', 'efw-alumni' ),
			'no_terms'                   => __( 'No Class Cohorts', 'efw-alumni' ),
			'menu_name'                  => __( 'Class Cohorts', 'efw-alumni' ),
			'items_list_navigation'      => __( 'Class Cohorts list navigation', 'efw-alumni' ),
			'items_list'                 => __( 'Class Cohorts list', 'efw-alumni' ),
			'most_used'                  => _x( 'Most Used', 'al-class', 'efw-alumni' ),
			'back_to_items'              => __( '&larr; Back to Class Cohorts', 'efw-alumni' ),
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
		1 => __( 'Class Cohort added.', 'efw-alumni' ),
		2 => __( 'Class Cohort deleted.', 'efw-alumni' ),
		3 => __( 'Class Cohort updated.', 'efw-alumni' ),
		4 => __( 'Class Cohort not added.', 'efw-alumni' ),
		5 => __( 'Class Cohort not updated.', 'efw-alumni' ),
		6 => __( 'Class Cohorts deleted.', 'efw-alumni' ),
	];

	return $messages;
}

add_filter( 'term_updated_messages', 'al_class_updated_messages' );
