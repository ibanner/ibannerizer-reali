<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * efw_prepare_search_results
 * 
 * Group search results by class cohorts.
 *
 * @param array $hits
 * 
 * @return array
 */

function efw_prepare_search_results( $hits ) {
  
    $sorted_list = array();

    if ( ! empty( $hits ) ) {
      
        foreach ( $hits[0] as $hit ) {
          $class_id = wp_get_post_terms($hit->ID,'al-class')[0]->term_id;
          $class_year = (get_field('year' , 'al-class_' . $class_id) ?: 9999 );
          $sorting_string = $class_year . ' ' . $hit->post_title . ' ' . $hit->ID;
          $sorted_list[$sorting_string] = $hit; // Not really sorted yet
        }
        ksort($sorted_list); // Now the list should be sorted
        $hits[0] = $sorted_list;
    }

    $_SESSION['_sorted_list'] = $sorted_list;
    return $hits;
}

add_filter( 'relevanssi_hits_filter', 'efw_prepare_search_results' );




/**
 * rlv_adjust_words
 * 
 * Familiarize Relevanssi with alternative transcriptions in Hebrew
 *
 * @param array $replacements
 * 
 * @return array
 */

function rlv_adjust_words( $replacements ) {
  
  $replacements['וו'] = 'ו';
	$replacements['יי'] = 'י';
  $replacements['סון'] = 'זון';
	$replacements['שטיין'] = 'שטין';
	$replacements['ph'] = 'v';
	$replacements['nn'] = 'n';
  
  return $replacements;
}

add_filter( 'relevanssi_punctuation_filter', 'rlv_adjust_words' );




/**
 * efw_redirect_single_result
 * 
 * When there's only one search result, redirect to it without going through the search results page. 
 *
 * @return void
 */

function efw_redirect_single_result() {
    if (is_search()) {
        global $wp_query;
        if ($wp_query->found_posts == 1) {
            wp_redirect( get_permalink( $wp_query->posts['0']->ID ) );
        }
    }
}

// Comment this to deactivate the redirect:
// add_action('template_redirect', 'efw_redirect_single_result');


/**
 * Tweaks
 */

// Familiarize Relevanssi with Hebrew alphabet
add_filter( 'relevanssi_didyoumean_alphabet', function() { return 'אבגדהוזחטיכלמנעפצקרשתםןףךץ'; } );


// Add shortcode for "Did You Mean..." suggestions
add_shortcode( 'rlv_didyoumean', function() {
  $didyoumean = '';
  if ( function_exists( 'relevanssi_didyoumean' ) ) {
    $didyoumean = relevanssi_didyoumean(
      get_search_query( false ),
      '<div id="rlv-didyoumean"><p>' . esc_html__( 'Did you mean:', 'efw-alumni' ) . ' ',
      '?</p></div',
      5,
      false
    );
  }
  return $didyoumean;
} );
