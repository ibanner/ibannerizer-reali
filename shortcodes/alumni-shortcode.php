<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

// [alumni get="something"]

add_shortcode( 'alumni', 'efw_shortcode_alumni' );

function efw_shortcode_alumni( $atts ) {
	$a = shortcode_atts( array(
		'get' => '',
	), $atts );

    $type = get_post_type();

    switch ($type) {
        case 'award':
            $aid = get_field( 'award_recipient' )[0];
            break;
        
        case 'alumnus':
            $aid = get_the_ID();
            break;
        
        case 'elementor_library':
            return '[' . $a['get'] . ']';
            break;
        
		case 'page':
            $aid = efw_get_current_user_claimed_alum_id();
            break;

        default:
            return esc_html__( 'Alumnus not found', 'efw-alumni' ) . ' (type: ' . get_post_type() . ')';
    }
	
	$has_class = get_the_terms( $aid , 'al-class');
	if ( $has_class ) {
		$class_object = $has_class[0];
		$class_id = $class_object->term_id;
	}

    $output = '';
	
	switch ($a['get']) {
        
		case "class_link":
            if ( $has_class ) {
                $class_url = get_term_link( $class_object );
    		    $output .= '<a class="class-link" href="' . esc_url( $class_url ) . '"><strong>' . 'מחזור ' . $class_object->name . '</strong></a>';
            }
    		break;
			
  		case "grad_years":
            if ( $has_class ) {
                $output .= get_field("year", "al-class_" . $class_id) . " (" . get_field( "hebrew_year" , "al-class_" . $class_id) . ")";
            }
    		break;
			
		case "nofel_page":
    		$output .= "https://yizkor.reali.org.il/nofel.asp?id=" . get_field( "nofel_id" , $aid );
    		break;
		
		case "nofel_link":
			$output = "לעמוד לזכר";
			switch ( get_field( "gender" , $aid ) ) {
				case 'female':
					$output .= "ה ";
					break;
				case 'male':
					$output .= "ו ";
					break;
				default:
					$output .= "ו.ה ";
			}
			$output .= "באתר חללי בית הספר";
    		break;
			
		case "post_title":
			$output = get_the_title( $aid );
    		break;
		
        case 'biography':
            $output = get_field( 'biography' , $aid );
            break;

		case "full_name":
			$output = efw_get_alumnus_name( get_the_ID() , 'full_current' , 1 , 1 );
    		break;
		
        case 'is_panmaz':
            $output = get_field( 'is_panmaz' , $aid );
            break;

		case "panmaz":
            if ( $has_class && get_field( 'is_panmaz' , $aid ) ) {
                $output = get_field( "panmaz_class" , "al-class_" . $class_id);
                if ( get_field( "panmaz_name" , "al-class_" . $class_id)) {
                    $output .= " (" . get_field( "panmaz_name" , "al-class_" . $class_id) . ")";
                }
            }
			break;
			
		case "panmaz_link":
            if ( $has_class && get_field( 'is_panmaz' , $aid ) ) {
                $panmaz = get_field( "panmaz_class" , "al-class_" . $class_id);
                // string replacement needed for compatability with bogrim-panmaz.co.il links
                $panmaz_url = 'https://bogrim-panmaz.co.il/class/' . urlencode(str_replace('"', '״', $panmaz)) . '/'; 
                $output = '<a class="panmaz-link" href="' . esc_url( $panmaz_url ) . '" target="_blank"><strong>' . 'מחזור ' . $panmaz . '</strong></a>';
				if ( get_field( "panmaz_name" , "al-class_" . $class_id)) {
                    $output .= ' (פלוגת "' . get_field( 'panmaz_name' , 'al-class_' . $class_id) . '")';
                }
            }
			break;
			
		case "isr_prize_pre":
			$prize = efw_get_alumnus_israel_prize( $aid );
			$output .= ($prize['pre'] ?: '');
			break;
			
		case "isr_prize":
			wp_enqueue_script('reali-js');
			$prize = efw_get_alumnus_israel_prize( $aid );
			if (!empty($prize)) {
				$output .= '<b>' . $prize['field'] . '</b> (' . $prize['year'] . ' - <span class="he-year">' . $prize['hebrew-year'] . '</span>)';
			}
			break;
			
		case "isr_prize_full":
			wp_enqueue_script('reali-js');
			$prize = efw_get_alumnus_israel_prize( $aid );
			if (!empty($prize)) {
				// TRANSLATORS: e.g. "Israel Prize Laureate for Literature in 2023"
				$format = esc_html_x( '%1$s for %2$s in %3$d' , 'Israel Prize', 'efw-alumni' );
				$output .= '<b>' . sprintf($format, $prize['pre'], $prize['field'], $prize['year'] ) . '</b> (<span class="he-year">' . $prize['hebrew-year'] . '</span>)';
			}
			break;
			
		case "class_label":
			$is_grad = has_term( 'graduates', 'group' , $aid );
			$gender = get_field( "gender" , $aid );
			if ( TRUE == has_term( 'graduates', 'group' , $aid ) ) {
				switch ( $gender ) {
					case 'male':
						$output = esc_html_x( 'Graduated in' , 'Masculine' , 'efw-alumni' );
						break;
					case 'female':
						$output = esc_html_x( 'Graduated in' , 'Feminine' , 'efw-alumni' );
						break;
					default:
					$output = esc_html_x( 'Graduated in' , 'Gender Neutral' , 'efw-alumni' );
				}
			} elseif ( TRUE == has_term( 'students', 'group' , $aid ) ) {
				switch ( $gender ) {
					case 'male':
						$output = esc_html_x( 'Studied with' , 'Masculine' , 'efw-alumni' );
						break;
					case 'female':
						$output = esc_html_x( 'Studied with' , 'Feminine' , 'efw-alumni' );
						break;
					default:
					$output = esc_html_x( 'Studied with' , 'Gender Neutral' , 'efw-alumni' );
				}
			}
			break;
		
		case "alum-permalink":
			return efw_get_current_user_claimed_alum_permalink();
            break;
		
		default:
            break;
	}

    return $output;
}