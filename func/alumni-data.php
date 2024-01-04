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