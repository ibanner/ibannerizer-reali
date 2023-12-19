<?php 

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * efw_custom_login_form_shortcode
 * 
 * Generate the required HTML for the popup login form. created mainly to set the redirect destination.
 *
 * @return mixed
 */
function efw_custom_login_form_shortcode() {

    $args = array(
      'echo'            => true,
      'label_username'  => esc_html__( 'Email', 'efw-alumni' ),
      'form_id'         => 'popup_login',
      // 'redirect'        => site_url( '/me/' ),
      'remember'        => true,
      'value_remember'  => true,
    );
  
    return wp_login_form( $args );
  
  }
  add_shortcode( 'alumni_popup_login', 'efw_custom_login_form_shortcode' );