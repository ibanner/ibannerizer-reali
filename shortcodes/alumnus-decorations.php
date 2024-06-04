<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

function efw_shortcode_decorations() {
	
	$rows = get_field('service_decorations_list');
	if( $rows ) {
		
		wp_enqueue_script('reali-js');
		
		echo '<ul class="decorations">';
		foreach( $rows as $row ) {
			$year = $row['decoration year'];
			$he_year_num = intval($year) + 3761 - 5000;
			$decor = $row['decoration name'];
			$rank = '';
			$story = '';
			$url = $row['decoration_link'];
			
			if ($url) {
				$story = ' - <a href="' . $url . '" target="_blank" rel="nofollow">' . 'לסיפור המעשה' . '</a>';
			}
			
			if ($row['decoration_rank']) {
				$rank = ', בדרגת ' . $row['decoration_rank'];
			}
			
			echo '<li class="single-decoration">';
				echo '<b>' . $decor . '</b> (' . $year . ', <span class="he-year">' . $he_year_num . '</span>' . $rank . $story .')';
			echo '</li>';
		}
		echo '</ul>';
	}
}

add_shortcode( 'alumnus_decorations', 'efw_shortcode_decorations' );