<?php 

add_action('wp_ajax_get_courses_list', 'get_courses_list');
function get_courses_list()
{
	$args = array(
    'orderby'                  => 'name',
    'order'                    => 'ASC',
    'hide_empty'               => 0,
    'hierarchical'             => 0,
    'taxonomy'                 => 'course'
	);
	$courses = get_categories($args);

	if(!empty($courses))
	{
		$output .= '<select name="course" id="course">';
		foreach($courses as $cours)
		{
			$output .= '<option value='.$cours->term_id.' title="'.$cours->name.'">'.$cours->name.'</option>';
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