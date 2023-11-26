<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Register custom dynamic tag groups
 *
 * @since 1.0.0
 * @param \Elementor\Core\DynamicTags\Manager $dynamic_tags_manager Elementor dynamic tags manager.
 * @return void
 */
function efw_register_dynamic_tag_groups( $dynamic_tags_manager ) {

	$dynamic_tags_manager->register_group(
		'efw-utility',
		[
			'title' => esc_html__( 'EFW Utility Tags', 'efw-alumni' )
		]
	);

}
add_action( 'elementor/dynamic_tags/register', 'efw_register_dynamic_tag_groups' );


/**
 * Register custom dynamic tags.
 *
 * Include dynamic tag files and register tag classes.
 *
 * @since 1.0.0
 * @param \Elementor\Core\DynamicTags\Manager $dynamic_tags_manager Elementor dynamic tags manager.
 * @return void
 */
function efw_register_dynamic_tags( $dynamic_tags_manager ) {

    require_once( EFW__PLUGIN_DIR . 'dynamic-tags/alumni-claiming-user-photo-dynamic-tag.php' );
    require_once( EFW__PLUGIN_DIR . 'dynamic-tags/alumni-claiming-user-dynamic-tag.php' );
    require_once( EFW__PLUGIN_DIR . 'dynamic-tags/alumni-name-formats-dynamic-tag.php' );
  
    $dynamic_tags_manager->register( new Elementor_Dynamic_Tag_Claiming_User_Current_Photo() );
    $dynamic_tags_manager->register( new Elementor_Dynamic_Tag_Claiming_User_Data() );
    $dynamic_tags_manager->register( new Elementor_Dynamic_Tag_Alumni_Name_Format() );
  
  }
  
  add_action( 'elementor/dynamic_tags/register', 'efw_register_dynamic_tags' );  