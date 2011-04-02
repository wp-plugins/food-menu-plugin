<?php
/*

 Change front-end layout of dishes archive and single dish pages
 Framework: Genesis

*/

add_action('get_header', 'dishes_remove_meta');
function dishes_remove_meta()
{
	// remove date, time, post meta from dishes archive page
	// add dish price
	if(is_post_type_archive('dish') or is_tax('course') or is_tax('ingredient'))
	{
		remove_action('genesis_before_post_content', 'genesis_post_info');
		remove_action('genesis_loop', 'genesis_do_loop');
		remove_action('genesis_after_post_content', 'genesis_post_meta');
		add_action('genesis_loop', 'dish_reorder');
		add_action('genesis_after_post_content', 'show_dish_price');
	}

	// remove date, time, post-meta	from single dish page
	// add dish ingredients, courses, price
	if(is_singular('dish'))
	{
		remove_action('genesis_before_post_content', 'genesis_post_info');
		remove_action('genesis_after_post_content', 'genesis_post_meta');
		add_action('genesis_before_post_content', 'show_dish_course');
		add_action('genesis_after_post_content', 'show_all_dish_meta');
		add_action('genesis_after_post_content', 'show_dish_price');
	}
}

// reorder dishes
function dish_reorder()
{
	global $wp_query, $loop_counter;
	$loop_counter = 0;
	
	/** save the original query **/
	$orig_query = $wp_query;

	$wp_query->set('orderby', 'menu_order');
    $wp_query->set('order', 'ASC');
	$wp_query->get_posts();	
	
	if ( have_posts() ) : while ( have_posts() ) : the_post();
	
	genesis_before_post();
?>
	<div <?php post_class(); ?>>
                    
		<?php genesis_before_post_title(); ?>
		<?php genesis_post_title(); ?>
		<?php genesis_after_post_title(); ?>

		<div class="entry-content">
			<?php genesis_post_content(); ?>
		</div><!-- end .entry-content -->
		<?php genesis_after_post_content(); ?> 

	</div><!-- end .postclass -->
<?php
	
	genesis_after_post();
	$loop_counter++;

	endwhile; /** end of one post **/
	genesis_after_endwhile();

	else : /** if no posts exist **/
	genesis_loop_else();
	endif; /** end loop **/
	
	/** restore original query **/
	$wp_query = $orig_query; wp_reset_query();

}

// layout to show dish price
function show_dish_price()
{
	global $post;
	echo '<div class="dish-price"><p>'.get_post_meta($post->ID, 'dish_price', true).'</p></div>';
	
}

// layout to show dish price
function get_dish_price($post_id)
{
	return '<div class="dish-price"><p>'.get_post_meta($post_id, 'dish_price', true).'</p></div>';
}


// show dish course
function show_dish_course()
{
	echo get_dish_meta('course');
}

// show dish ingredients, tags
function show_all_dish_meta()
{
	echo get_dish_meta('ingredient');
	echo get_dish_meta('post_tag');
}

// get dish ingredient
function get_dish_meta($taxonomy_name, $post_id = false)
{
	if(!$post_id)
	{	
		global $post;
		$post_id = $post->ID;
	}
	
	// get and show dish ingredients
	$terms = wp_get_object_terms($post_id, $taxonomy_name);

	if(!empty($terms))
	{
		return show_dish_meta($terms, $taxonomy_name);
	}
}

// show dish meta
function show_dish_meta($terms, $taxonomy_name)
{
	$output = '<div class="dish-'.$taxonomy_name.'">';
	foreach($terms as $t) 
	{
		$output .= '<a href="'.get_term_link($t, 'ingredient').'">'.$t->name.'</a>';
		if(next($terms) == true)
			$output .= ", "	;
	}
	$output .= '</div>';
	return $output;
}

