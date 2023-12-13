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
        if( $claiming_user->get('ID') == get_current_user_id() ){
			return TRUE;
		} else {
			return FALSE;
		}
    }
}

/**
 * efw_get_user_claimed_alum_id
 *
 * @param int $user_id
 * 
 * @return int the user's alum id, or null if we can't find one
 */
 function efw_get_user_claimed_alum_id( $user_id ) {
    $aid = get_field( 'alum_id' , 'user_' . $user_id );
    return ( $aid ?: '' );
}

/**
 * efw_get_current_user_claimed_alum_id
 *
 * @return int the current user's alum id, or null if we can't find one
 */
function efw_get_current_user_claimed_alum_id() {
    $aid = efw_get_user_claimed_alum_id( get_current_user_ID() );
    return ( $aid ?: '' );
}

/**
 * efw_get_user_claimed_alum_permalink
 *
 * @param int $user_id
 * 
 * @return string the user's alum permalink, or the homepage if we can't find the alum_id
 */
function efw_get_user_claimed_alum_permalink( $user_id ) {
    $aid = efw_get_user_claimed_alum_id( $user_id );
    return ( $aid ? esc_url( get_permalink( $aid ) ) : site_url() );
}

/**
 * efw_get_current_user_claimed_alum_permalink
 *
 * @return string the current user's alum permalink, or the homepage if we can't find the alum_id
 */
function efw_get_current_user_claimed_alum_permalink() {
    return efw_get_user_claimed_alum_permalink( get_current_user_ID() );
}