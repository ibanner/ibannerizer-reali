<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * efw_get_alumnus_israel_prize
 * 
 * Gather all data regarding the Israel Prize for a recipient alumnus.
 *
 * @param int $alum_id
 * 
 * @return array { 
 *     pre: string, // Localized, Gender Appropriate, "Israel Prize Laureate" prefix
 *     field: string, // The Israel Prize Field
 *     year: int, // Year of prize (Gregorian)
 *     hebrew-year: int, // Year of prize (Hebrew - numeric value)
 *     url: string, // URL to the prize committee announcement if available
 * }
 */
function efw_get_alumnus_israel_prize( int $alum_id ) {
    switch ( get_field( 'gender' , $alum_id ) ) {
        case 'female':
            $pre = esc_html_x( 'Israel Prize Laureate' , 'Feminine' , 'efw-alumni' );
            break;
        case 'male':
            $pre = esc_html_x( 'Israel Prize Laureate' , 'Masculine' , 'efw-alumni' );
            break;
        default:
            $pre = esc_html_x( 'Israel Prize Laureate' , 'Gender Neutral' , 'efw-alumni' );
    }
    $year = intval ( get_field('israel_prize_year' , $alum_id ) );
    $prize = [
        'pre' => $pre,
        'field' => get_field("israel_prize_field" , $alum_id ),
        'year' => $year,
        'hebrew-year' => $year + 3761 - 5000,
        'url' => ( esc_url(get_field('more_info' , $alum_id )) ?: '' ),
    ];
    return $prize;
}
 
/**
 * efw_add_taxonomy_class
 * 
 * Filter the Alumni list in al-class 
 *
 * @param string $classes
 * 
 * @return string Modified class string for Alumni
 */
function efw_add_taxonomy_class( $classes ){
    if( is_singular() ) {
        global $post;
		
		// Add honors terms to body classes
        $taxonomy_terms = get_the_terms($post->ID, 'honors'); // change to your taxonomy
        if ( $taxonomy_terms ) {
            foreach ( $taxonomy_terms as $taxonomy_term ) {
            $classes[] = 'honors-' . $taxonomy_term->slug;
            }
        }
		
		// Add "Deceased" to body classes
		$is_fallen = get_field( 'is_fallen' );
		if ( 1 == $is_fallen ) {
            $classes[] = 'deceased';
        }
    }
    return $classes;
}

add_filter( 'body_class', 'efw_add_taxonomy_class' );


/**
 * efw_order_alumni_by_name
 *
 * @param mixed $query Main WP Query
 * 
 * @return void
 */
function efw_order_alumni_by_name( $query ) { 
    if ( $query->is_tax('al-class') && $query->is_main_query() ) { 
      $query->set( 'orderby', 'title' ); 
      $query->set( 'order', 'ASC' ); 
    } 
 }
 add_action( 'pre_get_posts', 'efw_order_alumni_by_name' );


/**
 * efw_custom_page_title
 *
 * @param mixed $title
 * 
 * @return string the altered page title
 */
function efw_custom_page_title( $title ) {

    // set sure alumni page titles to the full current name
    if ( is_singular('alumnus') ) {

        $has_class = get_the_terms( get_the_ID() , 'al-class');
        $class = '';
        if ( $has_class ) {
            $class_object = $has_class[0];
            $class = ' - מחזור ' . $class_object->name;
        }
        $title = efw_get_alumnus_name( get_the_ID() , 'full_current' , 1 , 1 , 1 ) . $class . ' | ' . get_bloginfo( 'name' );
    }
    return strip_tags($title);
}
add_filter( 'pre_get_document_title', 'efw_custom_page_title' );

/**
 * efw_update_fallen_status
 *
 * @param int $post_id  The `alumnus` post id that was just saved.
 * 
 * @return void
 */

 function efw_update_fallen_status($post_id) {
    // Verify this is not an autosave
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    // Verify the post type is 'alumnus'
    if ('alumnus' !== get_post_type($post_id)) {
        return;
    }

    // Verify the user has permission to edit the post
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    // Get the value of the ACF field 'is_fallen'
    $is_fallen = get_field('is_fallen', $post_id);

    // Check if the term exists
    $term_exists = term_exists('fallen', 'group');

    // Get the term ID
    $term_id = is_array($term_exists) ? $term_exists['term_id'] : $term_exists;

    if ($is_fallen) {
        // If 'is_fallen' is true, attach the term
        wp_set_object_terms($post_id, (int) $term_id, 'group', true);
    } else {
        // If 'is_fallen' is false, remove the term
        wp_remove_object_terms($post_id, (int) $term_id, 'group');
    }
}
add_action('save_post', 'efw_update_fallen_status');
