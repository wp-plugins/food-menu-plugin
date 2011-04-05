<?php

/*
	Add postMash plugin 
	Autoinstall and activation.
	PostMash will not be deactivated while os restaurant menu is active
*/


add_action('init', 'add_postmash');

function add_postmash()
{
	if(!file_exists(ABSPATH.'wp-content/plugins/postmash-custom'))
	{
		copy_directory(ABSPATH.'wp-content/plugins/food-menu-plugin/postmash-custom', ABSPATH.'wp-content/plugins/postmash-custom');	
	}
	run_activate_plugin(ABSPATH.'wp-content/plugins/postmash-custom/postMash.php');
}


/* Function to copy directory from source to destination */
function copy_directory( $source, $destination ) {
	if ( is_dir( $source ) ) {
		@mkdir( $destination );
		$directory = dir( $source );
		while ( FALSE !== ( $readdirectory = $directory->read() ) ) {
			if ( $readdirectory == '.' || $readdirectory == '..' ) {
				continue;
			}
			$PathDir = $source . '/' . $readdirectory; 
			if ( is_dir( $PathDir ) ) {
				copy_directory( $PathDir, $destination . '/' . $readdirectory );
				continue;
			}
			copy( $PathDir, $destination . '/' . $readdirectory );
		}
 
		$directory->close();
	}else {
		copy( $source, $destination );
	}
}

/* Activate plugin via code */
function run_activate_plugin( $plugin ) {
    $current = get_option( 'active_plugins' );
    $plugin = plugin_basename( trim( $plugin ) );

    if ( !in_array( $plugin, $current ) ) {
        $current[] = $plugin;
        sort( $current );
        do_action( 'activate_plugin', trim( $plugin ) );
        update_option( 'active_plugins', $current );
        do_action( 'activate_' . trim( $plugin ) );
        do_action( 'activated_plugin', trim( $plugin) );
    }

    return null;
}

?>