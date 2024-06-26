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
    $attachment_id = '';
    if ( is_array($entry_fields[23][ 'value_raw' ]) ) {
        $attachment_id = intval( $entry_fields[23]['value_raw'][0][ 'attachment_id' ]);
    }
    update_field('current_photo', $attachment_id , 'user_' . get_current_user_id() );
}
add_action( 'wpforms_process_complete_75669', 'efw_wpf_on_user_photo_save', 10, 4 );

/**
 * efw_wpf_after_profile_edit
 * 
 * Wrapper function for handling user submitted details.
 *
 * @link  https://wpforms.com/developers/wpforms_process_complete/
 *
 * @param array  $fields    Sanitized entry field values/properties.
 * @param array  $entry     Original $_POST global.
 * @param array  $form_data Form data and settings.
 * @param int    $entry_id  Entry ID. Will return 0 if entry storage is disabled or using WPForms Lite.
 */

function efw_wpf_after_profile_edit( $fields, $entry, $form_data, $entry_id ) {
    efw_purge_alum_page_cache();
}
add_action( 'wpforms_process_complete_75305', 'efw_wpf_after_profile_edit', 10, 4 );

/**
 * efw_purge_alum_page_cache
 * 
 * Forces a cache purge for the current user's associated alumnus page. 
 * 
 * @link https://docs.wp-rocket.me/article/93-rocketcleanpost
 *
 * @return void
 */

 function efw_purge_alum_page_cache() {
    $alum_id = efw_get_current_user_claimed_alum_id();
    if ( function_exists('rocket_clean_post') ) {
        rocket_clean_post( $alum_id );
    }
}

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

      $fields_to_update = array (
        'claiming_user' => wp_get_current_user(),
        'claiming_user_f_name' => $current_f_name,
        'claiming_user_l_name' => $current_l_name,
      );

      foreach ($fields_to_update as $field => $value) {
        $field_object = get_field_object($field);
        if ($field_object) {
            update_field($field, $value);
        } else {
            error_log("Field {$field} does not exist for alumnus ID {$aid}.");
        }
    }
        wp_update_post( array( 'ID' => get_the_ID() ) );
    }
}

/**
 * efw_set_alumnus_current_names
 *
 * @param int $alumnus_id
 * @param string $current_f_name
 * @param string $current_l_name
 * 
 * @return int|WP_Error The post ID on success. The value 0 or WP_Error on failure.
 */
function efw_set_alumnus_current_names( $alumnus_id, $current_f_name , $current_l_name ) {
    update_post_meta( $alumnus_id, 'claiming_user_f_name', $current_f_name );
    update_post_meta( $alumnus_id, 'claiming_user_l_name', $current_l_name );
    return wp_update_post( array( 'ID' => $alumnus_id ) ); // Required for reindexing the post
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
        'nickname' => '',
        'alum_id' => $alumnus_id,
        'first_name' => $alumnus_f_name,
        'last_name' => $alumnus_l_name,
        );

        if ( $current_f_name ) { $args['first_name'] = $current_f_name; }
        if ( $current_l_name ) { $args['last_name'] = $current_l_name; }

        return wp_update_user( $args );

    }

}
  

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

    $fields_to_clear = array (
        'claiming_user',
        'claiming_user_f_name',
        'claiming_user_l_name',
      );

      foreach ($fields_to_clear as $field) {
        $field_object = get_field_object($field, $aid);
        if ($field_object) {
            update_field($field, '', $aid);
        } else {
            error_log("Field {$field} does not exist for alumnus ID {$aid}.");
        }
    }
    wp_update_post( array( 'ID' => $aid ) );
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
    if ( 0 == $has_consented ) {
        $properties['inputs'][1]['default'] = 0;
        $properties['inputs'][2]['default'] = 1;
    }
    return $properties;
}
add_filter( 'wpforms_field_properties_select', 'efw_wpf_filter_email_consent_value', 10, 3 );


/**
 * efw_has_user_shared_details
 *
 * @param int $user_id
 * 
 * @return bool 1 if consented to email or shared any other detail, 0 if neither was shared.
 */
function efw_has_user_shared_details( $user_id ) {
    $email_consent = get_field('email_consent', 'user_' . $user_id );
    if ( 0 != $email_consent ) {
        return 1; 
    } 
    $contact_fields = array(
        'phone_num',
        'website_url',
        'facebook_url',
        'twitter_url',
        'linkedin_url',
        'instagram_url',
    );
    foreach ($contact_fields as $slug) {
        $field = get_field( $slug , 'user_' . $user_id );
        if ( !empty($field) ) {
            return 1;
        }
    }
    return 0;
}

/**
 * Disable the email address suggestion.
 *
 * @link  https://wpforms.com/developers/how-to-disable-the-email-suggestion-on-the-email-form-field/
 */
add_filter( 'wpforms_mailcheck_enabled', '__return_false' );