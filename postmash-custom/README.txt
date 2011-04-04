=== PostMash Custom - custom post order ===
Contributors: opperud.com
Tags: order posts, ajax, re-order, drag-and-drop, admin, manage, post, posts, reorder, reorder posts, custom post type, custom taxonomy
Requires at least: 3.0
Tested up to: 3.1
Stable tag: 1.0.3

Customize display order of posts by a simple drag-and-drop Ajax interface, now with support for custom post types and custom taxonomies.

== Description ==
Posts are usually listed in reverse chronological order as they are often used for posting regular time-orientated content.

PostMash Custom lets you customise the order your posts are listed in using a simple Ajax drag-and-drop administrative interface, both regular posts and custom post types, with filtering for both standard taxonomy and custom taxonomies. Plus it gives quick access to toggle posts between draft and published states. Particularly useful if you're using WordPress as a CMS.

<a href="http://opperud.com/postmash_custom/">Plugin home page</a>

== Installation ==

Upload to wp-content/plugins/ and activate.

To make use of this chosen order you will need to modify your template code:
Open wp-content/themes/your-theme-name/index.php and find the beginning of ‘the loop’. Which will start: `if(have_posts())`. Then add the following code directly before this:

`
<?php  
    $wp_query->set('orderby', 'menu_order');  
    $wp_query->set('order', 'ASC');  
    $wp_query->get_posts();  
?>
`


This just tells WP to get the posts ordered according to their ‘menu_order’ position. Therefore you can get the posts ordered anytime you use a function such as get_posts simply by giving it the required arguments:

`
<?php get_posts('orderby=menu_order&order=ASC'); ?>
`

Checkout the get_posts() function in the wordpress codex for more info.
Note that it says menu_order is only useful for pages, posts have a menu_order position too, it just isn’t used. postMash provides you with an iterface so that you can use it.

NEXT POST AND PREVIOUS POST LINKS

ALSO You can now use the 'Next Post' and 'Previous Post' calls in your template file 'single.php', as usual, but rather than calling items by date, it will call items using the same order as set in postMash,  using the following modified commands in place of the regular tags

`
ORIGINAL TAGS

	next_post_link(); 
	previous_post_link();

MODIFIED TAGS

	next_post_link_menu(); 
	previous_post_link_menu();
`

(Please note that, by default, 'In Same Category' is set to TRUE. You will need to edit this if you wish to disable it. All other variables passed to the function should work as normal)


OPTIONAL ADMIN INSTALLATION 

If you wish to view the posts in the Wordpress admin 'Edit Posts' panel in the same order as you have set them postMash (rather than the default 'Date Published' or 'Date Modified'), you can modify wp-admin/includes/post.php, on line 784 as follows;

`
ORIGINAL

	if ( isset($q['post_status']) && 'pending' === $q['post_status'] ) {
		$order = 'ASC';
		$orderby = 'modified';
	} elseif ( isset($q['post_status']) && 'draft' === $q['post_status'] ) {
		$order = 'DESC';
		$orderby = 'modified';
	} else {
		$order = 'DESC';
		$orderby = 'date';
	}
	
	
MODIFIED

	if ( isset($q['post_status']) && 'pending' === $q['post_status'] ) {
		$order = 'ASC';
		$orderby = 'menu_order';
	} elseif ( isset($q['post_status']) && 'draft' === $q['post_status'] ) {
		$order = 'ASC';
		$orderby = 'menu_order';
	} else {
		$order = 'ASC';
		$orderby = 'menu_order';
	}

`
== Frequently Asked Questions ==

= None of it's working =
The most likely cause is that you have another plugin which has included an incompatible javascript library onto the postMash admin page.

Try opening up your WP admin and browse to your postMash page, then take a look at the page source. Check if the prototype or scriptaculous scripts are included in the header. If so then the next step is to track down the offending plugin, which you can do by disabling each of your plugins in turn and checking when the scripts are no longer included.

= Do I need any special code in my template =
Yes, by default posts are shown in reverse chronological order, to show the posts in the order you set in postMash you will need to use different code to display your posts. Look in the install section for an example.

= Which browsers are supported =
Any good up-to-date browser should work fine. Tested in Firefox, IE7, Safari and Opera.

==Credits==

This version: Torstein Opperud, http:/opperud.com

The original postMash plugin and author: http://joelstarnes.co.uk/postMash/, 
postMash (filtered): http://postmashfiltered.wordpress.com/2009/06/19/post-mash-filtered/

== Screenshots ==

1. From the filtering screen.
2. Change order by dragging posts up/down.

==Change Log==
= 1.0 =
 - Initial Release

= 1.0.1 =
 - Fixed "not permission"-errors when filtering, added screenshots, fixed some typos & such.

= 1.0.2 =
 - corrected some info

== Upgrade Notice ==
= 1.0.1 =
 - Fixed permission errors when filtering
 - Fixed typos and urls

= 1.0.2 =
 - corrected some info

= 1.0.3 =
 - refixed permission errors when filtering
 - fixed timeout error when updating/saving

== Localization ==

Currently only available in english.