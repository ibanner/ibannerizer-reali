<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

function efw_get_award( int $award_id ) {
    if ( 'award' != get_post_type( $award_id )  ) {
        return;
    }

    $alum_id = get_field( 'award_recipient' , $award_id )[0];

    $award_data = array(
        'award_recipient' => $alum_id,
        'award_name' => get_field( 'award_name' , $award_id ),
        'award_year' => get_field( 'award_year' , $award_id ),
        'award_link' => get_field( 'award_link' , $award_id ),
        'recipient_prefix' => efw_get_alumnus_recipient_prefix( $alum_id ),
    );

   
    if ( 0 < $award_data['award_year'] ) {
        $award_data['award_heb_year'] = $award_data['award_year'] + 3761 - 5000;
    
    }

    return $award_data;
}

function efw_get_alumnus_recipient_prefix( $alum_id ) {
    switch ( get_field( 'gender' , $alum_id ) ) {
        case 'female':
            return esc_html_x( 'Laureate' , 'Feminine' , 'efw-alumni' );
            break;
        case 'male':
            return esc_html_x( 'Laureate' , 'Masculine' , 'efw-alumni' );
            break;
        default:
        return esc_html_x( 'Laureate' , 'Gender Neutral' , 'efw-alumni' );
    }
}