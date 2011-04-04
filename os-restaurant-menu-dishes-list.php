<?php 

add_action('wp_ajax_get_dishes_list', 'get_dishes_list');
function get_dishes_list()
{
	$args = array(
				'post_type' => 'dish', 
				'numberposts' => -1, 
	);
	$dishes = get_posts($args);
	if(!empty($dishes))
	{
		$output .= '<select name="dish" id="dish">';
		foreach($dishes as $dish)
		{
			$output .= '<option value='.$dish->ID.' title="'.$dish->post_title.'">'.$dish->post_title.'</option>';
		}
		$output .= '</select>';
		die($output);
	}
	else
	{
		die("-2");
	}
}
?>