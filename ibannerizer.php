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

require_once( IBANNERIZER__PLUGIN_DIR . 'post-types/alumnus.php' );