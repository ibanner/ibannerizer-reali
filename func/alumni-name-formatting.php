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
 * @param int $user_override    Whether to override the original alumnus name with the claiming user name. defaults to 0
 * 
 * @return string
 */

function efw_get_alumnus_name( $post_id , $format = 'full_current' , $nickname = 0 , $rip = 0 , $user_override = 0 ) {

    $output = '';

    $aid = ( 'alumnus' == get_post_type( $post_id ) ? $post_id : 0 );
    $aid = ( 'award' == get_post_type( $post_id ) ? get_field( 'award_recipient' , $post_id )[0] : $post_id );

    $fields = [
        'f_name_heb',
        'l_name_heb',
        'current_f_name',
        'current_l_name',
        'nickname',
    ];

    $parts = [];

    foreach ( $fields as $slug ) {
        $parts[$slug] = ( get_field( $slug , $aid ) ? : null );
    }

    do_action( 'qm/debug', 'parts current_last_name' . ': ' . $parts['current_l_name'] ); // RBF

    if ( 1 == $user_override ) {
        $claiming_user = get_field( 'claiming_user' , $aid );
        if ( $claiming_user ) {
            if ( is_object( $claiming_user ) && ! $claiming_user->has_prop( 'first_name' ) ) {
                $uid = $claiming_user->ID;
                $claiming_user = get_userdata( $uid );
            } elseif ( is_string( $claiming_user ) ) {
                $uid = $claiming_user;
                $claiming_user = get_userdata( $claiming_user );
            }

            $user_has_cf_name = ( 1 != empty($claiming_user->first_name) && $parts['f_name_heb'] != $claiming_user->first_name ? 1 : 0 );
            $user_has_cl_name = ( 1 != empty($claiming_user->last_name) && $parts['l_name_heb'] != $claiming_user->last_name ? 1 : 0 );
            do_action( 'qm/debug', 'user_has_cl_name' . ': ' . $user_has_cl_name ); // RBF
            do_action( 'qm/debug', 'last_name isset' . ': ' . empty($claiming_user->last_name) ); // RBF

            if ( 1 == $user_has_cf_name && $parts['current_f_name'] != $claiming_user->first_name ) {
                $parts['current_f_name'] = $claiming_user->first_name;
            }
            if ( 1 == $user_has_cl_name && $parts['current_l_name'] != $claiming_user->last_name  ) {
                $parts['current_l_name'] = $claiming_user->last_name;
            }
        }
    }

    $is_fallen = ( 1 == get_field('is_fallen' , $aid) ? TRUE : FALSE );

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