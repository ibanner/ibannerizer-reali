<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * efw_is_claimed_page
 * 
 * Conditional function to check if the current page is an alumni page that was claimed by a user.
 *
 * @return bool
 */

function efw_is_claimed_page() {
    $is_claimed = ( 'alumnus' == get_post_type( get_queried_object_id() ) ? has_term( 'claimed', 'group' , get_queried_object_id() ) : FALSE );
    return $is_claimed;
}

/**
 * efw_is_own_claimed_page
 * 
 * Conditional function to check if the current page is the alumni page that was claimed by the current user.
 *
 * @return bool
 */

function efw_is_own_claimed_page() {

    if ( FALSE == efw_is_claimed_page() ) {
        return FALSE;
    } else {
        
        $claiming_user = get_field( 'claiming_user' );
        do_action( 'qm/debug', $claiming_user->get('ID') ); // RBF
        do_action( 'qm/debug', get_current_user_id() ); // RBF
        if( $claiming_user->get('ID') == get_current_user_id() ){
			return TRUE;
		} else {
			return FALSE;
		}
    }
}