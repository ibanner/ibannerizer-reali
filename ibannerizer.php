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

require_once( EFW__PLUGIN_DIR . 'dynamic-tags/register-dynamic-tags.php' );

require_once( EFW__PLUGIN_DIR . 'widgets/register-widgets.php' );

require_once( EFW__PLUGIN_DIR . 'func/alumni-name-formatting.php' );
require_once( EFW__PLUGIN_DIR . 'func/alumni-data.php' );
require_once( EFW__PLUGIN_DIR . 'func/alumni-claiming-user.php' );
require_once( EFW__PLUGIN_DIR . 'func/class-data.php' );

require_once( EFW__PLUGIN_DIR . 'modules/custom-login.php' );
require_once( EFW__PLUGIN_DIR . 'modules/ga.php' );



require_once( EFW__PLUGIN_DIR . 'taxonomies/al-class.php' );
require_once( EFW__PLUGIN_DIR . 'taxonomies/honors.php' );
require_once( EFW__PLUGIN_DIR . 'taxonomies/group.php' );

require_once( EFW__PLUGIN_DIR . 'post-types/alumnus.php' );
require_once( EFW__PLUGIN_DIR . 'post-types/award.php' );

require_once( EFW__PLUGIN_DIR . 'shortcodes/alumni-shortcode.php' );
require_once( EFW__PLUGIN_DIR . 'shortcodes/custom-login-form.php' );
require_once( EFW__PLUGIN_DIR . 'shortcodes/class-list.php' );

require_once( EFW__PLUGIN_DIR . 'admin/alumni-admin-columns.php' );


/**
 * Plugin related files
 */

function efw_load_plugin_related_files() {
  if (is_plugin_active('relevanssi-premium/relevanssi.php')) {
    require_once( EFW__PLUGIN_DIR . 'modules/alumni-search.php' );
  }
}
add_action( 'init', 'efw_load_plugin_related_files' );



/**
 * Language files
 */

function efw_load_textdomain() {
  load_plugin_textdomain( 'efw-alumni', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
}
add_action( 'plugins_loaded', 'efw_load_textdomain' );
 


/**
 * Register scripts and styles
 */

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

}

add_action('init', 'reali_register_scripts');



/**
 * Setup ACF save point
 *
 * @param string $path
 * 
 * @return string
 */

function ibn_json_save_point( $path ) {
  $path = EFW__PLUGIN_DIR . '/acf-json';
  return $path;
}

add_filter('acf/settings/save_json', 'ibn_json_save_point');

/**
 * remove_admin_bar for non admins
 *
 * @return void
 */
function efw_remove_admin_bar_for_non_admins() {
  if ( ! current_user_can( 'manage_options' ) ) {
    show_admin_bar(false);
  }
}

add_action('after_setup_theme', 'efw_remove_admin_bar_for_non_admins');


/**
 * efw_maybe_create_classes_table
 * 
 * Initial setup of the custom DB table for stroing alumni classes data.
 *
 * @return void
 */
function efw_maybe_create_classes_table() {
  require_once ABSPATH . 'wp-admin/includes/upgrade.php';
  global $wpdb;
  $table_name = $wpdb->prefix . 'efw_classes';
	
	$charset_collate = $wpdb->get_charset_collate();

	$sql = "CREATE TABLE $table_name (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		class_id mediumint(9) NOT NULL,
		class_name varchar(9) NOT NULL,
		alumni longtext,
		PRIMARY KEY  (id)
	) $charset_collate;";

  maybe_create_table( $table_name , $sql );
}
add_action( 'plugins_loaded', 'efw_maybe_create_classes_table' );