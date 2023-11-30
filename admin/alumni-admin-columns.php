<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Admin screens customization for:
 * 
 * - Class Cohorts [custom taxonomy] (al-class)
 * - Awards [custom post type] (award)
 */

/**
 * Class Cohorts
 */

 // Headers
function efw_add_al_class_columns( $columns ) {
	unset($columns['description']);
    $columns['year'] = esc_html__( 'Grad Year' , 'efw-alumni' );
	$columns['hebrew_year'] = esc_html__( 'Hebrew Year' , 'efw-alumni' );
	$columns['panmaz_class'] = esc_html__( 'Panmaz Class'  , 'efw-alumni' );
    return $columns;
} 

add_filter( 'manage_edit-al-class_columns', 'efw_add_al_class_columns' );

// Content
function efw_custom_al_class_columns( $string, $columns, $term_id ) {
    switch ( $columns ) {
        case 'year' :
            echo esc_html( get_field("year", "al-class_" . $term_id) );
			break;
        case 'hebrew_year' :
            echo esc_html( get_field("hebrew_year", "al-class_" . $term_id) );
			break;
		case 'panmaz_class' :
            echo esc_html( get_field("panmaz_class", "al-class_" . $term_id) );
			break;
    }
}

add_action( 'manage_al-class_custom_column', 'efw_custom_al_class_columns', 10, 3 );

/**
 * Awards
 */

 // Headers
 function efw_add_award_columns( $columns ) {
	unset($columns['date']);
	$columns['menu_order'] = esc_html_x( 'Order' , 'Award Ordering' , 'efw-alumni' );
	$columns['award-recipient'] = esc_html__( 'Award Recipient' , 'efw-alumni' );
	$columns['thumbnail'] = esc_html__( 'Featured Image' , 'efw-alumni' );
    $columns['award_year'] = esc_html__( 'Award Year' , 'efw-alumni' );
    $columns['link'] = esc_html__( 'Award Link' , 'efw-alumni' );
    return $columns;
} 

add_filter( 'manage_edit-award_columns', 'efw_add_award_columns' );

// Content
function efw_custom_award_column_values( $columns, $post_id ) {
    switch ( $columns ) {
        case 'award_year' :
            echo esc_html( get_field('award_year', $post_id) );
			break;
        case 'menu_order' :
            echo get_post_field('menu_order', $post_id);
			break;
        case 'link' :
            $url = get_field('award_link', $post_id);
            $output = ( $url ? '<a href="' . esc_html( get_field('award_link', $post_id) )  . '" class="external-link" target="_blank"><span class="dashicons dashicons-admin-links"></span> '  . esc_html_x( 'Open' , 'Award Link' , 'efw-alumni' )  . '</a>' : '');
            echo $output;
			break;
		case 'award-recipient' :
			$a_id = implode( get_field('award_recipient', $post_id) );
            echo '<a href="' . get_edit_post_link( $a_id )  . '" class="alumnus-link">' . get_the_title( $a_id ) . '</a>';
			break;
		case 'thumbnail' :
            echo get_the_post_thumbnail( $post_id, array( 75, 75) );
			break;
    }
}

add_action( 'manage_award_posts_custom_column', 'efw_custom_award_column_values', 10, 3 );

// Sorting
function efw_make_award_columns_sortable($sortable_columns) {
    $sortable_columns['award_year'] = 'award_year';
    $sortable_columns['menu_order'] = 'menu_order';
    return $sortable_columns;
}
add_filter('manage_edit-award_sortable_columns', 'efw_make_award_columns_sortable');

function efw_award_columns_orderby($query) {
    if (!is_admin() || !$query->is_main_query()) {
        return;
    } else if ( function_exists( 'get_current_screen' ) ) {
        $screen = get_current_screen();
        if('award' != $screen->post_type){ 
            return;
        } 
        $orderby = $query->get('orderby');
        switch ($orderby) {
            case 'award_year':
                $query->set('meta_key', 'award_year');
                $query->set('orderby', 'meta_value');
                break;
        }
    }
}
add_action('pre_get_posts', 'efw_award_columns_orderby');