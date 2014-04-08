	<?php
	/**
	Plugin Name: Overwrite Author Name
	Plugin URI: http://justinandco.com/plugins/overwrite-author-name/
	Description: Overwrite Author Name to ensure on publish a users name will be replaced, this allows the site to have a consistent authorship albeit content provided by multiple authors.
	Version: 1.5
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

	add_action( 'init', 'overwrite_author_name_active_post_types' );

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

				switch ( $post_type_name ) {
						
					case 'attachment':
						// edit_attachment hook needed for the attachment post type.
						add_action( "edit_attachment", 'overwrite_author_name' );
						break;
										
					default:			
						// Hook to all private or public post types
						add_action( 'post_updated', 'overwrite_author_name' );			
				}
			}		
		}
		
		load_plugin_textdomain('overwrite-author-text-domain', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	}

	/*
	 * The main philosophy is that draft updates/edits to post types do not enforce the author.  The author 
	 * is only enforced on 'future', 'publish' or 'private' status.
	*/
	function overwrite_author_name( ) {

		$post_id = get_the_ID();
		$post_status = get_post_status( $post_id );

		switch ( $post_status ) {
			case 'draft':
			case 'auto-draft':
			case 'pending':
			case 'inherit':
			case 'trash':
				return;
				
			case 'future':
			case 'publish':
			case 'private':
				// continue
		}

		// this is the username ID to be enforced.
		$options = get_option( 'overwrite_author_option' );  
		$enforced_author = $options[selected_author];  


		if ( $enforced_author ) {

			// this is the post types to have an enforced username.
			$author_post_types =  $options[overwrite_post_types];

			if ($parent_id = wp_is_post_revision( $post_id )) 
				$post_id = $parent_id;

			$post = get_post( $post_id );

			if (( $post->post_author != $enforced_author ) && (in_array( $post->post_type, $author_post_types ))) {
				// update the post, which calls publish_post again
				wp_update_post( array( 'ID' => $post_id, 'post_author' => $enforced_author ));

			}
		}
	}

	// Add settings page to the admin active plugin listing
	add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'overwrite_author_name_action_links' );

	function overwrite_author_name_action_links( $links ) {
		array_unshift( $links, '<a href="options-general.php?page=' . OAN_SETTINGS_PAGE . '">' . __( 'Settings', 'overwrite-author-text-domain' ) . "</a>" );
		return $links;
	}

	?>