<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

function efw_add_elementor_widget_categories( $elements_manager ) {

	$elements_manager->add_category(
		'efw-widgets',
		[
			'title' => esc_html__( 'Custom Widgets by Effective Web', 'efw-alumni' ),
			'icon' => 'fa fa-plug',
		]
	);
}
add_action( 'elementor/elements/categories_registered', 'efw_add_elementor_widget_categories' );


function efw_register_widgets( $widgets_manager ) {

	require_once( EFW__PLUGIN_DIR . '/widgets/alumni-search-results.php' );

	$widgets_manager->register( new \EFW_Alumni_Search_Results() );

}
add_action( 'elementor/widgets/register', 'efw_register_widgets' );