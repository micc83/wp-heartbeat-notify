<?php
/*
Plugin Name: Wp Hearbeat Notify (beta)
Plugin URI: https://github.com/micc83/WpDevTool
Description: Based on <strong>WordPress 3.6 heartbeat API</strong>, Wp Hearbeat Notify, display a realtime custom message to your visitor each time a new post is published with a link redirecting to it. Still in beta version, this plugin has been <strong>full tested only on WordPress 3.6-beta3</strong>.
Version: 0.0.1
Author: Alessandro Benoit
Author URI: http://codeb.it
License: http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
*/

require_once( 'core/class-my-plugin.php' );
require_once( 'core/class-wp-heartbeat-notify.php' );

global $my_plugin;

// Create the plugin and store it as a global
$my_plugin = new My_Plugin( 
	__FILE__, 
	array(
		'required'	=>	array( 
			'wordpress'	=>	'3.6' // WordPress 3.6 is required
		)
	) 
);

// Instantiate Heartbeat notifications
new Wp_Hearbeat_Notify( array(
	'context'	=>	array( 'front' ),	// This plugin is supposed to work only on the front end
	'base_url'	=>	$my_plugin->uri		// Set js and css base url
) );

// Let's hook into Post publication
add_filter ( 'publish_post', 'notify_published_post' );
function notify_published_post( $post_id ) {
	
	global $my_plugin;
	
	// That's it. Easy... isn'it?
	Wp_Hearbeat_Notify::notify( array(
		'title'		=>		__( 'New Article', $my_plugin->textdomain ),
		'content'	=>	 	__( 'There\'s a new post, why don\'t you give a look at', $my_plugin->textdomain ) . 
							' <a href="' . get_permalink( $post_id ) . '">' . get_the_title( $post_id ) . '</a>',
		'type'		=>		'update'
	) );
	
	return $post_id;
	
}
