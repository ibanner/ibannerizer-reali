<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

add_shortcode( 'alumni', 'efw_shortcode_alumni' );

function efw_shortcode_alumni( $atts ) {
	$a = shortcode_atts( array(
		'get' => '',
	), $atts );
	
	$terms = get_the_terms( get_the_ID(), 'al-class');
	if ($terms) {
		$class_object = $terms[0];
		$class_id = $class_object->term_id;
	}
	
	switch ($a['get']) {
		case "class_link":
			$class_url = get_term_link( $class_object );
    		return '<a class="class-link" href="' . esc_url( $class_url ) . '">' . 'מחזור ' . $class_object->name . '</a>';
    		break;
			
  		case "grad_years":
    		return get_field("year", "al-class_" . $class_id) . " (" . get_field("hebrew_year", "al-class_" . $class_id) . ")";
    		break;
			
		case "nofel_page":
    		return "https://yizkor.reali.org.il/nofel.asp?id=" . get_field("nofel_id");
    		break;
		
		case "nofel_link":
			$nofel_link = "לעמוד לזכר";
			switch (get_field("gender")) {
				case 'female':
					$nofel_link .= "ה ";
					break;
				case 'male':
					$nofel_link .= "ו ";
					break;
				default:
					$nofel_link .= "ו.ה ";
			}
			$nofel_link .= "באתר חללי בית הספר";
    		return $nofel_link;
    		break;
			
		case "post_title":
			$post_title = get_the_title();
			return $post_title;
    		break;
		
		case "full_name":
			$full_name = get_field('f_name_heb') . ' ';
			$full_name .= ( get_field('current_f_name') ?  '(' . get_field('current_f_name') . ') ' : '');
			$full_name .= get_field('l_name_heb') . ' ';
			$full_name .= ( get_field('current_l_name') ?  '(' . get_field('current_l_name') . ') ' : '');
			$full_name .= ( get_field('nickname') ?  '<span class="nickname">(' . get_field('nickname') . ')</span>' : '');
			$full_name .= ( 1 == get_field('is_fallen') ?  ' <span class="rip">ז"ל</span>' : '');
    		return $full_name;
    		break;
			
		case "panmaz":
			$panmaz = get_field("panmaz_class", "al-class_" . $class_id);
			if (get_field("panmaz_name", "al-class_" . $class_id)) {
				$panmaz .= " (" . get_field("panmaz_name", "al-class_" . $class_id) . ")";
			}
			return $panmaz;
			break;
			
		case "panmaz_link":
			$panmaz = get_field("panmaz_class", "al-class_" . $class_id);
			$panmaz_link = 'https://bogrim-panmaz.co.il/class/' . urlencode(str_replace('"', '״', $panmaz)) . '/'; // string replacement needed for compatability with bogrim-panmaz.co.il links
			return $panmaz_link;
			break;
			
		case "isr_prize_pre":
			switch (get_field("gender")) {
				case 'female':
					$isr_prize_pre = "כלת פרס ישראל";
					break;
				case 'male':
					$isr_prize_pre = "חתן פרס ישראל";
					break;
				default:
					$isr_prize_pre = "פרס ישראל";
			}
			return $isr_prize_pre;
			break;
			
		case "isr_prize_info":
			$isr_prize_info = get_field("israel_prize_field") . ', '  . get_field("israel_prize_year");
			if (get_field("more_info")) {
				$isr_prize_info .= " - <a href='" . get_field("more_info") . "'>נימוקי ועדת הפרס</a>";
			}
			return $isr_prize_info;
			break;
			
		case "isr_prize_full":
			switch (get_field("gender")) {
				case 'female':
					$isr_prize = "כלת פרס ישראל ל";
					break;
				case 'male':
					$isr_prize = "חתן פרס ישראל ל";
					break;
				default:
					$isr_prize = "פרס ישראל ל";
			}
			$isr_prize_full .= get_field("israel_prize_field") . ' לשנת '  . get_field("israel_prize_year");
			
			return $isr_prize;
			break;
			
		case "teacher_label":
			$teacher_label = "שימש";
			switch (get_field("gender")) {
				case 'female':
					$teacher_label .= "ה";
					break;
				default:
					$teacher_label .= ".ה";
			}
			$teacher_label .= " מורה בבית הספר הריאלי";
    		return $teacher_label;
    		break;
					
		case "alum_gender":
			$alum_gender = "בוגר";
			switch (get_field("gender")) {
				case 'male':
					break;
				case 'female':
					$alum_gender .= "ת";
					break;
				default:
					$alum_gender .= ".ת";
			}
    		return $alum_gender;
    		break;
			
		default:
    		return get_field($a['get'], "al-class_" . $class_id);
	}
}