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

/**
 * This will fire at the very end of a (successful) form entry.
 *
 * @link  https://wpforms.com/developers/wpforms_process_complete/
 *
 * @param array  $fields    Sanitized entry field values/properties.
 * @param array  $entry     Original $_POST global.
 * @param array  $form_data Form data and settings.
 * @param int    $entry_id  Entry ID. Will return 0 if entry storage is disabled or using WPForms Lite.
 */
 
 function efw_on_user_photo_save( $fields, $entry, $form_data, $entry_id ) {

    $previous_aid = get_field('current_photo', 'user_' . get_current_user_id() );
    if ( isset( $previous_aid ) ) {
        wp_delete_attachment( $previous_aid , TRUE );
    }
     
    // Get the full entry object
    $entry = wpforms()->entry->get( $entry_id );
    
    // Fields are in JSON, so we decode to an array
    $entry_fields = json_decode( $entry->fields, true );
 
    // Save changes
    $aid = $entry_fields[23]['value_raw'][0][ 'attachment_id' ];
    if ( $aid ) {
        update_field('current_photo', $aid , 'user_' . get_current_user_id() );
    }
}
add_action( 'wpforms_process_complete_75669', 'efw_on_user_photo_save', 10, 4 );


/**
 * Run shortcodes in WPForms' confirmation messages. Required for the user photo form.
 *
 * @link   https://wpforms.com/developers/how-to-display-shortcodes-inside-the-confirmation-message/
 */
 
 function efw_wpforms_do_shortcodes_in_confirmation( $content ) {
     
    return do_shortcode( $content );
}
 
add_filter( 'wpforms_process_smart_tags', 'efw_wpforms_do_shortcodes_in_confirmation', 12, 1 );