<?php
/**
 * Plugin Name:     EFW Utility Plugin - Reali Alumni
 * Plugin URI:      https://effective-web.co.il
 * Description:     Utility plugin for Hebrew Reali School alumni website
 * Author:          Itay Banner
 * Author URI:      https://effective-web.co.il
 * Text Domain:     efw-alumni
 * Domain Path:     /languages
 * Version:         0.1.1
 *
 * @package         efw-alumni
 */

define( 'EFW__PLUGIN_DIR', plugin_dir_path( __FILE__ ) );

require_once( EFW__PLUGIN_DIR . 'custom_login.php' );
require_once( EFW__PLUGIN_DIR . 'dynamic-tags/register-dynamic-tags.php' );
require_once( EFW__PLUGIN_DIR . 'widgets/register-widgets.php' );
require_once( EFW__PLUGIN_DIR . 'modules/ga.php' );

require_once( EFW__PLUGIN_DIR . 'taxonomies/al-class.php' );
require_once( EFW__PLUGIN_DIR . 'taxonomies/honors.php' );
require_once( EFW__PLUGIN_DIR . 'taxonomies/group.php' );

require_once( EFW__PLUGIN_DIR . 'post-types/alumnus.php' );
require_once( EFW__PLUGIN_DIR . 'post-types/award.php' );

require_once( EFW__PLUGIN_DIR . 'shortcodes/alumni-shortcode.php' );

add_filter('acf/settings/save_json', 'ibn_json_save_point');
 
function ibn_json_save_point( $path ) {
    
    // update path
    $path = EFW__PLUGIN_DIR . '/acf-json';
        
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

add_filter( 'relevanssi_hits_filter', 'efw_search_result_test' );

function efw_search_result_test( $hits ) {
  
    static $nested_list = array();
    $classes_present = array();
    $sorted_list = array();

    if ( ! empty( $hits ) ) {
      
        foreach ( $hits[0] as $hit ) {
          $class = wp_get_post_terms($hit->ID,'al-class')[0];
          $sorting_string = $class->name . ' ' . $hit->post_title;
          $classes_present[$class->term_id] = $class->name;

          if ( ! in_array($class, $classes_present) ) {
            $classes_present[$class->term_id] = $class->name;
          }

          $nested_list[$class->name][] = $hit->ID;

          $sorted_list[$sorting_string] = $hit; // Not really sorted yet
        }
        ksort($nested_list);
        ksort($sorted_list); // Now the list should be sorted
        $hits[0] = $sorted_list;
    }
    $_SESSION['_nested_list'] = $nested_list;
    return $hits;
}