<?php
/**
Plugin Name: Overwrite Author Name
Plugin URI: http://justinandco.com/plugins/overwrite-author-name/
Description: Overwrite Author Name to ensure on publish a users name will be replaced, this allows the site to have a consistent authorship albeit content provided by multiple authors.
Version: 1.4
Author: Justin Fletcher
Author URI: http://justinandco.com
License: GPLv2 or later
*/

// Define constants
define( 'OAN_PLUGINNAME_PATH', plugin_dir_path(__FILE__) );
define( 'OAN_SETTINGS_PAGE', 'overwrite-author-name-settings');

/* Includes... */

// register 
require_once( OAN_PLUGINNAME_PATH . 'includes/register.php' );  

// settings 
require_once( OAN_PLUGINNAME_PATH . 'includes/settings.php' );  

add_action('init', 'overwrite_author_name_active_post_types');

// this function makes all posts published by a single user name as defined on the settings page.
function overwrite_author_name_active_post_types() {
    
	// this is the username ID to be enforced.
    $options = get_option('overwrite_author_option');  
    $enforced_author = $options['selected_author'];  
   
    if ( $enforced_author ) {
    
        // this is the post types to have an enforced username.
	    $author_post_types =  $options['overwrite_post_types'];

		foreach( $author_post_types as $post_type_name )
		{
			if ( $post_type_name != "attachment" ) {
			
				// add to the published post or page actions
				add_action("publish_{$post_type_name}", 'overwrite_author_name');
				
				// scheduled posts ...add to the action for post is transitioned from 'future' to 'publish' status
				add_action('publish_future_post', 'overwrite_author_name');
				
			} else {
			
				// edit_attachment hook needed for the attachment post type.
				add_action("edit_attachment", 'overwrite_author_name');
				
			}	
		}
	}
	
	load_plugin_textdomain('overwrite-author-text-domain', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
}

function overwrite_author_name($post_id) {
    
    // this is the username ID to be enforced.
    $options = get_option('overwrite_author_option');  
    $enforced_author = $options[selected_author];  
   
    if ( $enforced_author ) {
    
        // this is the post types to have an enforced username.
	    $author_post_types =  $options[overwrite_post_types];
		
		if ($parent_id = wp_is_post_revision($post_id)) 
			$post_id = $parent_id;
		
		$post = get_post( $post_id );

		if (( $post->post_author != $enforced_author ) && (in_array($post->post_type, $author_post_types))) {
			// update the post, which calls publish_post again
			wp_update_post(array('ID' => $post_id, 'post_author' => $enforced_author));
			
		}
	}
}

// Add settings page to the admin acitve plugin listing
add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'overwrite_author_name_action_links' );

function overwrite_author_name_action_links( $links ) {
    array_unshift( $links, '<a href="options-general.php?page=' . OAN_SETTINGS_PAGE . '">' . __( 'Settings', 'overwrite-author-text-domain' ) . "</a>" );
    return $links;
}

?>