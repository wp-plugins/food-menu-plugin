<?php
/* Add custom post type Dish */
add_action( 'init', 'create_dish_post_types' );
function create_dish_post_types()
{
	$args = array(
					'label' 	=> 'Dishes',
					'labels' 	=> array(
											'name' 	=> _x('Dishes', 'custom post type name'),
											'singular_name' => _x('Dishes', 'custom post type name'),
											'add_new_item'	=> 'Add New Dish',
											'edit_item'		=> __('Edit Dish'),
											'new_item'		=> __('New Dish'),
											'view_item'		=> __('View Dish'),
											'search_items'	=> __('Search Dishes'),
											'not_found'		=> __('No Dishes found'),
											'not_found_in_trash' => __('No Dishes found in Trash'),
											'menu_name'		=> __('Dishes'),
											),
					'public'	=> true,
					'supports'	=> array('title', 'editor', 'thumbnail'),
					'register_meta_box_cb'	=> 'add_dish_metaboxes',
					'taxonomies'	=> array('course', 'ingredient', 'post_tag'),
					'has_archive'	=> 'dishes',	
					'rewrite' => array('slug' => 'dish'),
					);
	register_post_type('dish', $args);
}

/* Add meta boxes to post type dish */
function add_dish_metaboxes()
{
	add_meta_box( 'price', __('Price'), 'edit_price_box', 'dish', 'normal');	
}

/* this function prints edit box for price */
function edit_price_box()
{
	global $post;
	$price = get_post_meta($post->ID, 'dish_price', true);
	$price = !$price ? '' : $price;
	?>
	<label for="dish_price"><?php _e('Dish Price:')?></label>
    <input type="text" id="dish_price" name="dish_price" value="<?php echo $price; ?>" size="25" />
    <small><?php _e('(Price example: £14.75)')?></small>
<?php }

/* Save post meta boxes data  */
add_action('save_post', 'dish_save_metaboxes_data');
function dish_save_metaboxes_data()
{
	// verify if this is an auto save routine. 
	// If it is our form has not been submitted, so we dont want to do anything
	if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) 
	  return $post_id;
	  
	// verify if this is dish post type and current user can edit it
	if('dish' == $_POST['post_type'] && current_user_can('edit_post'))
	{
		$price = $_POST['dish_price'];
		$post_id = $_POST['post_ID'];
		if(!update_post_meta($post_id, 'dish_price', $price)) 
			add_post_meta($post_id, 'dish_price', $price, true);
	}
	else
		return $post_id;
		
}

/* Add taxonomies Courses(like category), Ingredients(like tag) */
add_action( 'init', 'create_dish_taxonomies', 0 );
function create_dish_taxonomies()
{
	// Courses
	$labels = array(
					'name' 			=> _x( 'Courses', 'taxonomy general name' ),
					'singular_name' => _x( 'Course', 'taxonomy singular name' ),
					'search_items' 	=>  __( 'Search Courses' ),
					'all_items' 	=> __( 'All Courses' ),
					'parent_item' 	=> __( 'Parent Course' ),
					'parent_item_colon' => __( 'Parent Course:' ),
					'edit_item' 	=> __( 'Edit Course' ), 
					'update_item' 	=> __( 'Update Course' ),
					'add_new_item' 	=> __( 'Add New Course' ),
					'new_item_name' => __( 'New Course Name' ),
					'menu_name' 	=> __( 'Courses' ),
	);
	
	register_taxonomy('course', array('dish'), array(
														'hierarchical' 	=> true,
														'labels' 		=> $labels,
														'show_ui' 		=> true,
														'query_var' 	=> true,
														'rewrite' 		=> array( 'slug' => 'course' ),
														)
	);
	
	// Ingredients
	$labels = array(
    'name' 				=> _x( 'Ingredients', 'taxonomy general name' ),
    'singular_name'	 	=> _x( 'Ingredient', 'taxonomy singular name' ),
    'search_items' 		=>  __( 'Search Ingredients' ),
    'popular_items' 	=> __( 'Popular Ingredients' ),
    'all_items' 		=> __( 'All Ingredients' ),
    'parent_item' 		=> null,
    'parent_item_colon' => null,
    'edit_item' 		=> __( 'Edit Ingredient' ), 
    'update_item' 		=> __( 'Update Ingredient' ),
    'add_new_item' 		=> __( 'Add New Ingredient' ),
    'new_item_name' 	=> __( 'New Ingredient Name' ),
    'separate_items_with_commas' 	=> __( 'Separate ingredients with commas' ),
    'add_or_remove_items' 			=> __( 'Add or remove ingredients' ),
    'choose_from_most_used' 		=> __( 'Choose from the most used ingredients' ),
    'menu_name' 		=> __( 'Ingredients' ),
  ); 

  register_taxonomy('ingredient','dish',array(
    'hierarchical' 		=> false,
    'labels' 			=> $labels,
    'show_ui' 			=> true,
	'show_in_nav_menus'	=> false,
    'query_var' 		=> true,
    'rewrite' 			=> array( 'slug' => 'ingredient' ),
  ));

}
