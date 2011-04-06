<?php
/*

	Shortcodes for Restaurant Menu

*/

// Dish short code
add_shortcode('dish', 'show_dish_shortcode');
function show_dish_shortcode($atts)
{
	$output = '';
	if($dish_id = (int)$atts['dish_id'])
	{
		$dish = get_post($dish_id);
		if($dish)
		{
			$output  = '<div class="dish">';
			$output .= '<h3 class="dish-title"><a href="'.get_permalink($dish_id).'">'.$dish->post_title.'</a></h3>';
			$output .= get_dish_meta('course', $dish->ID);
			$output .= $dish->post_content;
			$output .= get_dish_meta('ingredient', $dish_id);
			$output .= get_dish_meta('post_tag', $dish_id);
			$output .= get_dish_price($dish_id);
			$output .= '</div>';
			return $output;
		}
		else
			return '<p>Dish with id '.$dish_id.' was not found</p>';
	}
	else
		return '<p>Please, specify dish_id in short code.</p>';
}

// Course shortcode
add_shortcode('course', 'show_couse_shortcode');
function show_couse_shortcode($atts)
{
	$output = '';
	if($course_id = $atts['course_id'])
	{
		// get term to show its name and url
		$term = get_term( $course_id, 'course' );
		if($term)
		{
			$output .= '<div class="course">';
			$output .= '<h2 class="course-title"><a href="'.get_term_link($term, 'course').'">'.$term->name.'</a></h2>';
			$args = array(
				'tax_query' => array(
					array(
						'taxonomy' => 'course',
						'field' => 'id',
						'terms' => $course_id,
					)
				),
				'orderby' => 'menu_order',
				'order' => 'ASC',
				'nopaging' => true,
			);
			$dishes = new WP_Query( $args );
			if(!empty($dishes->posts))
			{
				foreach($dishes->posts as $d)
					$output .= show_dish_shortcode(array('dish_id' => $d->ID));
			}
			else
				$output .= '<p>No dishes in this course.</p>';
			$output .= '</div>';
			return $output;
		}
		else
			return '<p>No Course with id '.$course_id.' found.</p>';
	}
	else
		return '<p>Please, specify course_id in shortcode</p>';
}

?>