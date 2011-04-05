<?php 
/*
Plugin Name: Food Menu Plugin
Plugin URI: http://www.octaviansolutions.co.uk/
Description: Plugin allows to add restaurant menu
Version: 1.3
Author: Oksamyta
Author URI: http://sev-scs.com.ua
License: 
*/

/* Install and activate postMash plugin */
require_once("food-menu-plugin-postmash.php");

/* Include custom post type and taxonomies */
require_once("food-menu-plugin-custom.php");

/* Include front-end layouts */
require_once("food-menu-plugin-layouts.php");

/* Include shortcodes */
require_once("food-menu-plugin-shortcodes.php");

/* Create InsertDish button in TinyMCE (WYSIWYG) editor  */
require_once("food-menu-plugin-mce-button.php");

/* Add defaul stylesheet for Dishes*/
add_action('wp_print_styles', 'add_dish_styles');
function add_dish_styles()
{
	wp_register_style('dish_style', WP_PLUGIN_URL.'/food-menu-plugin/dish_style.css');
	wp_enqueue_style('dish_style');
}

/* Add plugin information page */
add_action('admin_menu', 'food_menu_plugin_info_page');
function food_menu_plugin_info_page()
{
	// add page to Settings menu block
	add_submenu_page('edit.php?post_type=dish', 'Restaurant Menu Info', 'Dashboard', 'administrator', 'food-menu-plugin-info', 'food_menu_plugin_info');
}

/* Show information page of Restaurant Menu plugin */
function food_menu_plugin_info()
{?>
	<div class="wrap">
    	<h2>Restaurant Menu Info</h2>
        <a href="http://octaviansolutions.co.uk/" target="_blank"><img src="http://octaviansolutions.co.uk/wp-content/themes/venture/images/logo.png" title="Octavian Solutions" style="float:right;" /></a>
        <br />
        <a href="http://octavianclients.co.uk/foodmenuplugin/">Plugin Homepage</a>
        <br  /><br />
        Support Link
        <div style="clear:both;"></div>
        <?php show_food_menu_plugin_feed() ?>
    </div>
<?php }

// get RSS Feed Data
function get_food_menu_plugin_feed()
{
	$items = array();
	if(function_exists('fetch_feed')) 
	{
		include_once(ABSPATH . WPINC . '/feed.php');               // include the required file
		$feed = fetch_feed('http://octavianclients.co.uk/foodmenuplugin/category/latest/feed'); // specify the source feed
	
		$limit = $feed->get_item_quantity(7); // specify number of items
		$items = $feed->get_items(0, $limit); // create an array of items
	}
	
	return $items;
}

// Show Rss Feed
function show_food_menu_plugin_feed()
{
	$items = get_food_menu_plugin_feed();?>
	
    <div class="postbox " id="dashboard_primary" style="margin-top:20px;">
	<h3 style="margin:0; padding:5px;"><span>Octavian Solutions Feed</span></h3>
	<div class="inside" style="padding:10px;">
		<div class="rss-widget">

        <?php if (empty($items)) : ?>
            <p>The feed is either empty or unavailable.</p>
        
        <?php else: ?>
        	<ul>
        	<?php foreach ($items as $item) : ?>
			<li>
                <a href="<?php echo $item->get_permalink(); ?>" title="<?php echo $item->get_date('j F Y @ g:i a'); ?>" class="rsswidget" style="text-decoration:none; font:bold 16px 'Times New Roman', Times, serif;">
				   <?php echo $item->get_title(); ?>
                </a>
                <span class="rss-date" style="font-size:10px;"><?php echo $item->get_date('j F Y @ g:i a'); ?></span>
                <div class="rssSummary">
                	<?php echo substr($item->get_description(), 0, 200); ?> 
					<a href="<?php echo $item->get_permalink(); ?>" title="<?php echo $item->get_date('j F Y @ g:i a'); ?>" >[...]</a>
                </div>
            </li>
			<?php endforeach;?>
            </ul>
        <?php endif ?>
		</div>
	</div>
</div>
<?php }

?>