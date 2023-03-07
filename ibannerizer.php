<?php
/**
 * Plugin Name:     IBannerizer - Reali Alumni
 * Plugin URI:      https://ibanner.co.il
 * Description:     A simple plugin to help me manage my client sites.
 * Author:          Itay Banner
 * Author URI:      https://ibanner.co.il
 * Text Domain:     ibannerizer
 * Domain Path:     /languages
 * Version:         0.1.0
 *
 * @package         Ibannerizer
 */

define( 'IBANNERIZER__PLUGIN_DIR', plugin_dir_path( __FILE__ ) );

require_once( IBANNERIZER__PLUGIN_DIR . 'custom_login.php' );
require_once( IBANNERIZER__PLUGIN_DIR . 'modules/ga.php' );

require_once( IBANNERIZER__PLUGIN_DIR . 'taxonomies/al-class.php' );
require_once( IBANNERIZER__PLUGIN_DIR . 'taxonomies/honors.php' );
require_once( IBANNERIZER__PLUGIN_DIR . 'taxonomies/group.php' );

require_once( IBANNERIZER__PLUGIN_DIR . 'post-types/alumnus.php' );

add_filter('acf/settings/save_json', 'ibn_json_save_point');
 
function ibn_json_save_point( $path ) {
    
    // update path
    $path = IBANNERIZER__PLUGIN_DIR . '/acf-json';
        
    // return
    return $path;
    
}

function reali_register_scripts() {
	wp_register_script(
      'gematriya',
      plugin_dir_url( __FILE__ ) . "/lib/gematriya.js",
      ['jquery'],
      '2.0.0',
      true
    );
	
	wp_register_script(
      'reali-js',
      plugin_dir_url( __FILE__ ) . "/js/script.js",
      ['jquery', 'gematriya'],
      '1.0.0',
      true
    );
	
//     wp_enqueue_script(
//         'he-code',
//         'https://unpkg.com/he-date@1.2.2/HeDate.js'
//     );

}

add_action('init', 'reali_register_scripts');


/**
 * Register Claiming User Current Photo Dynamic Tag.
 *
 * Include dynamic tag file and register tag class.
 *
 * @since 1.0.0
 * @param \Elementor\Core\DynamicTags\Manager $dynamic_tags_manager Elementor dynamic tags manager.
 * @return void
 */
function register_claiming_user_photo_dynamic_tag( $dynamic_tags_manager ) {

	require_once( IBANNERIZER__PLUGIN_DIR . 'dynamic-tags/alumni-claiming-user-photo-dynamic-tag.php' );

	$dynamic_tags_manager->register( new Elementor_Dynamic_Tag_Claiming_User_Current_Photo() );

}

add_action( 'elementor/dynamic_tags/register', 'register_claiming_user_photo_dynamic_tag' );



/**
 * Register Claiming User Data Dynamic Tag.
 *
 * Include dynamic tag file and register tag class.
 *
 * @since 1.0.0
 * @param \Elementor\Core\DynamicTags\Manager $dynamic_tags_manager Elementor dynamic tags manager.
 * @return void
 */
function register_claiming_user_data_dynamic_tag( $dynamic_tags_manager ) {

	require_once( IBANNERIZER__PLUGIN_DIR . 'dynamic-tags/alumni-claiming-user-dynamic-tag.php' );

	$dynamic_tags_manager->register( new Elementor_Dynamic_Tag_Claiming_User_Data() );

}

add_action( 'elementor/dynamic_tags/register', 'register_claiming_user_data_dynamic_tag' );

/**
 * ibn_associate_claiming_user_to_alumnus
 *
 * @param int $alumnus_id
 * @param int $user_id
 * @param string $current_f_name
 * @param string $current_l_name
 * 
 * @return mixed
 */
function ibn_associate_claiming_user_to_alumnus ( $alumnus_id , $user_id , $current_f_name , $current_l_name ) {
  if ( ! $alumnus_id ) {

    return false;

  } else {

    if( ! has_term( 'claimed', 'group' ) ) {
      wp_set_post_terms( get_the_ID(), 'claimed' , 'group', true );
    }

    ibn_set_claiming_user_names( $alumnus_id , $user_id , $current_f_name , $current_l_name );

    return update_field( 'claiming_user' , wp_get_current_user() );

  }
}

/**
 * ibn_set_claiming_user_names
 *
 * @param int $alumnus_id
 * @param int $user_id
 * @param string $current_f_name
 * @param string $current_l_name
 * 
 * @return mixed
 */
function ibn_set_claiming_user_names ( $alumnus_id , $user_id , $current_f_name , $current_l_name ) {
  
  if ( ! $alumnus_id || ! $user_id ) {

    return false;

  } else {

    $alumnus_f_name = get_field( 'f_name_heb' , $alumnus_id );
    $alumnus_l_name = get_field( 'l_name_heb' , $alumnus_id );

    $args = array(
      'ID' => $user_id,
      'display_name' => $alumnus_f_name . ' ' . $alumnus_l_name,
      'nickname' => $alumnus_f_name . ' ' . $alumnus_l_name,
      'alum_id' => $alumnus_id,
      'about_alumni' => 'FOO',
      'first_name' => $alumnus_f_name,
      'last_name' => $alumnus_l_name,
    );

    if ( $current_f_name ) { $args['first_name'] = $current_f_name; }
    if ( $current_l_name ) { $args['last_name'] = $current_l_name; }

    return wp_update_user( $args );

  }
  
}
