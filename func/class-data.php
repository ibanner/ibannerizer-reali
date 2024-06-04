<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$table_name = $wpdb->prefix . 'efw_classes';

function efw_on_save_class_term( $class_id ) {
    efw_maybe_create_class_row( $class_id );
    efw_update_alumni_classdata( $class_id );
}

add_action( 'saved_al-class', 'efw_on_save_class_term', 10 );

function efw_action_save_alumnus_post( $post_ID, $post, $update ) {
    do_action( 'qm/debug', __FILE__ . " | " . __LINE__ ); // RBF
    
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
        return;
    
	if ( 'alumnus' !== $post->post_type ) {
		return;
	}

    if ( !current_user_can( 'edit_post', $post_ID ) )
        return;

    $has_class = get_the_terms( $post_ID , 'al-class');

    if ( $has_class ) {
        $class_object = $has_class[0];
        $class_id = $class_object->term_id;
        return efw_update_alumni_classdata( $class_id );
    } else {
        return;
    }

}

add_action( 'save_post', 'efw_action_save_alumnus_post', 10, 3 );


function efw_update_alumni_classdata( $class_id ) {
    require_once ABSPATH . 'wp-admin/includes/upgrade.php';
    global $wpdb, $table_name;

    $args = array(
        'tax_query' => array(
            array(
                'taxonomy' => 'al-class',
                'field'    => 'term_id',
                'terms'    => $class_id,
            )
            ),
        'post_type'     => 'alumnus',
        'numberposts'   =>  -1,
        'fields'        => 'ids',
        'orderby'       => 'title',
        'order'         => 'ASC',
      );

    $alumni = [];
    
    $alumni['ids'] = get_posts( $args );
    $alumni['count'] = sizeof($alumni['ids']);
    foreach ($alumni['ids'] as $aid) {
        $name = efw_get_alumnus_name( $aid , 'full_current' , 0 , 0 , 0 );
        $is_grad = has_term( 'graduates', 'group' , $aid );
        $is_fallen = has_term( 'fallen', 'group' , $aid );
        $flags = '';
        $flags .= ( ! $is_grad ? ' non-grad' : '') ;
        $flags .= ( $is_fallen ? ' fallen' : '') ;
        
        $alumni['data'][$name]['alum_id'] = $aid;
        $alumni['data'][$name]['flags'] = $flags;
    }
    
    $wpdb->update(
        $table_name,
        array(
            'alumni' => serialize( $alumni ),
        ),
        array( 'class_id' => $class_id ),
        array( '%s' ),
        array( '%d' ),
    );
    $wpdb->print_error();
}

function efw_maybe_create_class_row( $class_id ){
    require_once ABSPATH . 'wp-admin/includes/upgrade.php';
    global $wpdb, $table_name;
    $classdata = $wpdb->get_row( "SELECT * FROM $table_name WHERE class_id = $class_id" );
    $wpdb->print_error();
    if (! $classdata ) {
        $class = get_term( $class_id , 'al-class' );
        $wpdb->insert(
            $table_name,
            array(
                'class_id' => $class_id,
                'class_name' => $class->name,
            ),
            array( '%d', '%s' )
        );
    }
    return $classdata;
}

function efw_get_class_alumnidata( $class_id ) {
    if ( ! intval($class_id) > 0 ) {
        return NULL;
    }
    require_once ABSPATH . 'wp-admin/includes/upgrade.php';
    global $wpdb;
    $table_name = $wpdb->prefix . 'efw_classes';
    $sql = 'SELECT alumni, class_name FROM '. $table_name . ' WHERE class_id = ' . $class_id;
    $alumni_raw = $wpdb->get_row( $sql, ARRAY_A );
    return $alumni_raw;
}