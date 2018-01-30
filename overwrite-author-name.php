<?php
/**
Plugin Name: Overwrite Author Name
Plugin URI: http://justinandco.com/plugins/overwrite-author-name/
Description: Overwrite Author Name to ensure on publish a users name will be replaced, this allows the site to have a consistent authorship albeit content provided by multiple authors.
Version: 2.0
Author: Justin Fletcher
Author URI: http://justinandco.com
Text Domain: overwrite-author-name
License: GPLv2 or later
*/
 
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * SSL_Settings class.
 */
class OAN_CLASS {
    
	
	// Refers to a single instance of this class.
    private static $instance = null;
	
    public	 $plugin_full_path;
	public   $plugin_file = 'overwrite-author-name/overwrite-author-name.php';
	
	// Settings page slug	
    public	 $menu = 'oan_settings.php';
	
	// Settings Admin Menu Title
    public	 $menu_title = 'Overwrite Author';
	
	// Settings Page Title
    public	 $page_title = 'Overwrite Author Name';
	
	
	/**
	 * __construct function.
	 *
	 * @access public
	 * @return void
	 */	 
	private function __construct() {
	
		$this->plugin_full_path = plugin_dir_path(__FILE__) . 'overwrite-author-name.php' ;

		// Set the constants needed by the plugin.
		add_action( 'plugins_loaded', array( $this, 'constants' ), 1 );
		
		/* Load the functions files. */
		add_action( 'plugins_loaded', array( $this, 'includes' ), 2 );
				
		add_action( 'init', array( $this, 'overwrite_author_name_active_post_types' ));


	}
			

	/**
	 * Defines constants used by the plugin.
	 *
	 * @return void
	 */
	function constants() {

		// Define constants
		define( 'OAN_PLUGINNAME_PATH', plugin_dir_path(__FILE__) );
		define( 'OAN_SETTINGS_PAGE', 'overwrite-author-name-settings');

		// Define constants
		define( 'OAN_MYPLUGINNAME_PATH', plugin_dir_path(__FILE__) );
		define( 'OAN_PLUGIN_DIR', trailingslashit( plugin_dir_path( OAN_PLUGINNAME_PATH )));
		define( 'OAN_MENU_PAGE', 'oan_settings.php');
		
		// admin prompt constants
		define( 'OAN_PROMPT_DELAY_IN_DAYS', 30);
		define( 'OAN_PROMPT_ARGUMENT', 'rbhn_hide_notice');

	}

	/**
	 * Loads the initial files needed by the plugin.
	 *
	 * @return void
	 */
	function includes() {

		// settings 
		require_once( OAN_PLUGINNAME_PATH . 'includes/settings.php' ); 
	}

	// this function makes all posts published by a single user name as defined on the settings page.
	public function overwrite_author_name_active_post_types() {

		// this is the username ID to be enforced.
		$enforced_author = get_option('oan_author_id');  
		 
		if ( $enforced_author ) {
		
			// this is the post types to have an enforced user-name.
			$author_post_types = get_option('oan_post_types');  

			foreach( $author_post_types as $post_type_name )
			{

				switch ( $post_type_name ) {
						
					case 'attachment':
						// edit_attachment hook needed for the attachment post type.
						add_action( "edit_attachment",  array( $this, 'overwrite_author_name' ) );

						break;
										
					default:			
						// Hook to all private or public post types
						
						add_action( 'post_updated', array( $this, 'overwrite_author_name' ) );			
				}
			}		
		}

		load_plugin_textdomain('overwrite-author-name', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	}

	/*
	 * The main philosophy is that draft updates/edits to post types do not enforce the author.  The author 
	 * is only enforced on 'future', 'publish' or 'private' status.
	*/
	public function overwrite_author_name( $post_id ) {
	
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
		$enforced_author = get_option('oan_author_id'); 


		if ( $enforced_author ) {

			// this is the post types to have an enforced username.
			$author_post_types =  get_option('oan_post_types');

			if ($parent_id = wp_is_post_revision( $post_id )) 
				$post_id = $parent_id;

			$post = get_post( $post_id );

			if (( $post->post_author != $enforced_author ) && (in_array( $post->post_type, $author_post_types ))) {
				// update the post, which calls publish_post again
				wp_update_post( array( 'ID' => $post_id, 'post_author' => $enforced_author ));

			}
		}
	}

	public function overwrite_author_name_action_links( $links ) {
		array_unshift( $links, '<a href="options-general.php?page=' . OAN_SETTINGS_PAGE . '">' . __( 'Settings', 'overwrite-author-name' ) . "</a>" );
		return $links;
	}

			


	/**
     * Creates or returns an instance of this class.
     *
     * @return   A single instance of this class.
     */
    public static function get_instance() {
	
        if ( null == self::$instance ) {
            self::$instance = new self;
        }

        return self::$instance;
		
	}
}

// Create new tabbed settings object for this plugin..
// and Include additional functions that are required.
OAN_CLASS::get_instance();


?>