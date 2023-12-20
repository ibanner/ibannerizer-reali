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
 * efw_wpf_on_user_photo_save
 * 
 * Handle user's current photo form actions: deletes the previous photo if existing, and associates the new one if uploaded.
 *
 * @link  https://wpforms.com/developers/wpforms_process_complete/
 *
 * @param array  $fields    Sanitized entry field values/properties.
 * @param array  $entry     Original $_POST global.
 * @param array  $form_data Form data and settings.
 * @param int    $entry_id  Entry ID. Will return 0 if entry storage is disabled or using WPForms Lite.
 */
 
 function efw_wpf_on_user_photo_save( $fields, $entry, $form_data, $entry_id ) {
    $previous_aid = get_field('current_photo', 'user_' . get_current_user_id() );
    if ( isset( $previous_aid ) ) {
        wp_delete_attachment( $previous_aid , TRUE );
    }
    $entry = wpforms()->entry->get( $entry_id );
    $entry_fields = json_decode( $entry->fields, true );
    // error_log('entry_fields: ' . print_r($entry_fields,true)); //RBF
    $attachment_id = '';
    if ( is_array($entry_fields[23][ 'value_raw' ]) ) {
        $attachment_id = intval( $entry_fields[23]['value_raw'][0][ 'attachment_id' ]);
    }
    update_field('current_photo', $attachment_id , 'user_' . get_current_user_id() );
}
add_action( 'wpforms_process_complete_75669', 'efw_wpf_on_user_photo_save', 10, 4 );


/**
 * efw_wpf_update_user_email_consent
 * 
 * Handle user's consent to display their email in the alumnus page
 *
 * @link  https://wpforms.com/developers/wpforms_process_complete/
 *
 * @param array  $fields    Sanitized entry field values/properties.
 * @param array  $entry     Original $_POST global.
 * @param array  $form_data Form data and settings.
 * @param int    $entry_id  Entry ID. Will return 0 if entry storage is disabled or using WPForms Lite.
 */
 
 function efw_wpf_update_user_email_consent( $fields, $entry, $form_data, $entry_id ) { 
    $entry = wpforms()->entry->get( $entry_id );
    $entry_fields = json_decode( $entry->fields, true );
    $consent = ( 1 == $entry_fields[20][ 'value_raw' ] ?: 0 );
    update_field('email_consent', $consent , 'user_' . get_current_user_id() );
}
add_action( 'wpforms_process_complete_75305', 'efw_wpf_update_user_email_consent', 10, 4 );


/**
 * Run shortcodes in WPForms' confirmation messages. Required for the user photo form.
 *
 * @link   https://wpforms.com/developers/how-to-display-shortcodes-inside-the-confirmation-message/
 */
 
 function efw_wpforms_do_shortcodes_in_confirmation( $content ) {
     
    return do_shortcode( $content );
}
 
add_filter( 'wpforms_process_smart_tags', 'efw_wpforms_do_shortcodes_in_confirmation', 12, 1 );

/**
 * Show values in Dropdown, checkboxes, and Multiple Choice.
 *
 * @link https://wpforms.com/developers/add-field-values-for-dropdown-checkboxes-and-multiple-choice-fields/
 */
  
 add_filter( 'wpforms_fields_show_options_setting', '__return_true' );

 /**
 * Register the Smart Tag so it will be available to select in the form builder.
 *
 * @link   https://wpforms.com/developers/how-to-create-a-custom-smart-tag/
 */
 
function efw_register_wpforms_smarttags( $tags ) {
 
    // Key is the tag, item is the tag name.
    $tags[ 'current_aid' ] = 'Current Photo ID';
 
    return $tags;
}
add_filter( 'wpforms_smart_tags', 'efw_register_wpforms_smarttags', 10, 1 );
 
/**
 * Process the Smart Tag.
 *
 * @link   https://wpforms.com/developers/how-to-create-a-custom-smart-tag/
 */
 
function efw_process_wpforms_smarttags( $content, $tag ) {
     if ( 'current_aid' === $tag ) {
        $current_aid = ( get_field('current_photo', 'user_' . get_current_user_id() ) ?: FALSE );
        $content = str_replace( '{current_aid}', $current_aid , $content );
    }
    return $content;
}
add_filter( 'wpforms_smart_tag_process', 'efw_process_wpforms_smarttags', 10, 2 );


add_action( 'delete_user', 'efw_disconnect_claiming_user' );

/**
 * efw_disconnect_claiming_user
 *
 * @param int $user_id      The user ID to be disconnected from the associated alumnus
 * 
 * @return void 
 */
function efw_disconnect_claiming_user( $user_id ) {
    $aid = efw_get_user_claimed_alum_id( $user_id );
    if ( TRUE == has_term( 'claimed', 'group' , $aid ) ) {
        wp_remove_object_terms($aid, 'claimed', 'group');
    }
    update_field('claiming_user', '', $aid );
}


/**
 * efw_wpf_filter_email_consent_value
 *
 * @link https://wpforms.com/developers/wpforms_field_properties/
 */
  
 function efw_wpf_filter_email_consent_value( $properties, $field, $form_data ) {
    if ( absint( $form_data[ 'id' ] ) !== 75305 ) {
        return $properties;
    }
    $has_consented = get_field('email_consent', 'user_' . get_current_user_id() );
    error_log('has_consented: ' . $has_consented ); //RBF
    error_log('properties before: ' . print_r($properties['inputs'][1],true)); //RBF
    if ( 1 == $has_consented ) {
        $properties['inputs'][1]['container']['class'][2] = 'wpforms-selected';
        $properties['inputs'][1]['default'] = 1;

    }
    error_log('properties after: ' . print_r($properties['inputs'][1],true)); //RBF
    
    return $properties;
}
add_filter( 'wpforms_field_properties_checkbox', 'efw_wpf_filter_email_consent_value', 10, 3 );
