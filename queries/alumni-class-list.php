<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Update the query to use specific post types.
 *
 * @since 1.0.0
 * @param \WP_Query $query The WordPress query instance.
 */
function efw_alumni_class_list_query( $query ) {
	$query->set( 'posts_per_page', 999 );
    global $wp_query;
    do_action( 'qm/debug', 'wp_query' . ': ' . print_r($wp_query,true) ); // RBF
}
// add_action( 'elementor/query/class_list_query', 'efw_alumni_class_list_query' ); // RBF