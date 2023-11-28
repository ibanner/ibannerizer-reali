<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * efw_get_alumnus_name
 *
 * @param int $post             Alumnus post id
 * @param string $format        Defaults to 'full_current', to include current names
 * @param int $nickname         Whether to include the nickname. defaults to 0
 * @param int $rip              Whether to include the RIP suffix for fallen alumni. defaults to 0
 * 
 * @return string
 */

function efw_get_alumnus_name( $post_id , $format = 'full_current' , $nickname = 0 , $rip = 0 ) {

    $aid = ( 'alumnus' == get_post_type() ? $post_id : 0 );
    $aid = ( 'award' == get_post_type() ? get_field( 'award_recipient' )[0] : $post_id );

    $fields = [
        'f_name_heb',
        'l_name_heb',
        'current_f_name',
        'current_l_name',
        'nickname',
    ];

    $parts = [];

    foreach ( $fields as $slug ) {
        $parts[$slug] = get_field($slug);
    }

    $is_fallen = ( 1 == get_field('is_fallen') ? TRUE : FALSE );
    

    $output = '';

    if ( $parts ) {

        $output .= $parts['f_name_heb'] . ' ';
        $output .= ( 'full_current' == $format && $parts['current_f_name'] ?  '(' . $parts['current_f_name'] . ') ' : '');
        $output .= $parts['l_name_heb'] . ' ';
        $output .= (  'full_current' == $format && $parts['current_l_name'] ?  '(' . $parts['current_l_name'] . ') ' : '');
        $output .= ( 1 == $nickname && $parts['nickname'] ?  '<span class="nickname">(' . $parts['nickname'] . ') </span>' : '');
        $output .= ( 1 == $rip && $is_fallen ?  '<span class="rip">' . esc_html__( 'RIP', 'efw-alumni' ) . '</span>' : '');
        
    }
    
    return $output;
}