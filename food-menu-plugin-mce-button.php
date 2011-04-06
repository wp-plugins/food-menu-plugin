<?php
/*
	Add Dishes button to TinyMCE editor
*/


require_once("food-menu-plugin-dishes-list.php");
require_once("food-menu-plugin-courses-list.php");


if ( ! defined( 'ABSPATH' ) )
	die( "Can't load this file directly" );

class InsertButton
{
	function __construct($button_name) {
		$this->button_name = $button_name;
		add_action( 'admin_init', array( $this, 'action_admin_init' ) );
	}
	
	function action_admin_init() {
		// only hook up these filters if we're in the admin panel, and the current user has permission
		// to edit posts and pages
		if ( current_user_can( 'edit_posts' ) && current_user_can( 'edit_pages' ) ) {
			add_filter( 'mce_buttons', array( $this, 'filter_mce_button' ) );
			add_filter( 'mce_external_plugins', array( $this, 'filter_mce_plugin' ) );
		}
	}
	
	function filter_mce_button( $buttons ) {
		// add a separation before our button, here our button's id is "dish_button"
		array_push( $buttons, '|', $this->button_name.'_button' );
		return $buttons;
	}
	
	function filter_mce_plugin( $plugins ) {
		// this plugin file will work the magic of our button
		$plugins[$this->button_name] = plugin_dir_url( __FILE__ ) . $this->button_name.'_plugin.js';
		return $plugins;
	}
}

$insertDish = new InsertButton('dish');
$insertCourse = new InsertButton('course');