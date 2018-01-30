<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}


// Append new links to the Plugin admin side

add_filter( 'plugin_action_links_' . OAN_CLASS::get_instance()->plugin_file , 'oan_plugin_action_links');

function oan_plugin_action_links( $links ) {

	$overwrite_author_name = OAN_CLASS::get_instance();

	$settings_link = '<a href="options-general.php?page=' . $overwrite_author_name->menu . '">' . __( 'Settings' ) . "</a>";
	array_push( $links, $settings_link );
	return $links;
}


// add action after the settings save hook.
add_action( 'tabbed_settings_after_update', 'oan_after_settings_update' );

function oan_after_settings_update( ) {

	$overwrite_author_name = OAN_CLASS::get_instance();
	//flush_rewrite_rules();	

}



/**
 * oan_Settings class.
 *
 * Main Class which inits the CPTs and plugin
 */
class oan_Settings {
	
	// Refers to a single instance of this class.
    private static $instance = null;

/** 
	 * __construct function.
 * 
	 * @access public
	 * @return void
 */   
	private function __construct() {
	}
 
	/**
     * Creates or returns an instance of this class.
     *
     * @return   A single instance of this class.
     */
    public static function get_instance() {

		$overwrite_author_name = OAN_CLASS::get_instance();

		$config = array(
				'default_tab_key' => 'oan_general',					// Default settings tab, opened on first settings page open.
				'menu_parent' => 'options-general.php',    		// menu options page slug name.
				'menu_access_capability' => 'manage_options',    					// menu options page slug name.
				'menu' => $overwrite_author_name->menu,    					// menu options page slug name.
				'menu_title' => $overwrite_author_name->menu_title,    		// menu options page slug name.
				'page_title' => $overwrite_author_name->page_title,    		// menu options page title.
	);  

				
		$settings = 	apply_filters( 'oan_settings', 
									array(								
										'oan_general' => array(
											'title' 		=> __( 'General', 'overwrite-author-name' ),
											'description' 	=> __( 'Select options to customise the overwrite of the author name during publishing a post/page.', 'overwrite-author-name' ),
											'settings' 		=> array(		
																	array(
																		'name' 		=> 'oan_author_id',
																		'std' 		=> '0',
																		'label' 	=> __( 'Author Name to Enforce', 'overwrite-author-name' ),
																		'desc'		=> __( 'If you wish to create a contents page add a new page and select it here so that the Help Note Contents are displayed.', 'overwrite-author-name' ),
																		'type'      => 'field_wp_dropdown_users',
																		),	
																	array(
																		'name' 		=> 'oan_post_types',
																		'std' 		=> false,
																		'label' 	=> _x( 'Post Types', 'settings title for enabling the widgets for help notes.', 'overwrite-author-name' ),
																		'cb_label'  => _x( 'Enable', 'enable the setting option.', 'overwrite-author-name' ),
																		'desc'		=> __( "Only post types with 'author' support are listed.", 'overwrite-author-name' ),
																		'type'      => 'settings_field_selected_post_types'
																		),
																),
										),
									)
	);  
       
        if ( null == self::$instance ) {
            self::$instance = new Tabbed_Settings( $settings, $config );
        }
 
        return self::$instance;
 
    }
}


/**
 * oan_Settings_Additional_Methods class.
 */
class oan_Settings_Additional_Methods {

	/**
	 * field_help_notes_post_types_option 
	 *
	 * @param array of arguments to pass the option name to render the form field.
	 * @access public
	 * @return void
	 */
	public function field_help_notes_post_types_option( array $args  ) {
    
		$option   = $args['option'];

		//  loop through the site roles and create a custom post for each
		global $wp_roles;
		$overwrite_author_name = OAN_CLASS::get_instance();
		$value = get_option( $option['name'] );
		
		if ( ! isset( $wp_roles ) )
		$wp_roles = new WP_Roles();

		$roles = $wp_roles->get_names(); 
		?><ul><?php 
		asort( $roles );
		foreach( $roles as $role_key=>$role_name )
		{
			$id = sanitize_key( $role_key );

			$post_type_name = $overwrite_author_name->clean_post_type_name( $role_key );
			$role_active = $this->oan_role_active( $role_key, (array) $value )
	
			// Render the output  
			?> 
			<li><label>
			<input type='checkbox'  
				id="<?php echo esc_html( "help_notes_{$id}" ) ; ?>" 
				name="<?php echo esc_html( $option['name'] ); ?>[][<?php echo esc_html( $role_key ) ; ?>]"
				value="<?php echo esc_attr( $post_type_name )	; ?>"<?php checked( $role_active ); ?>
			>
			<?php echo esc_html( $role_name ) . " <br/>"; ?>	
			</label></li>
			<?php 
		}?></ul><?php 
		if ( ! empty( $option['desc'] ))
			echo ' <p class="description">' . esc_html( $option['desc'] ) . '</p>';		
} 


/**
	 * field_help_notes_taxonomy_option 
	 *
	 * @param array of arguments to pass the option name to render the form field.
	 * @access public
	 * @return void
 */
	public function field_help_notes_taxonomy_options( array $args  ) {
		$option   = $args['option'];
		
		//  loop through the site roles and create a custom post for each
		global $wp_roles;
		$overwrite_author_name = OAN_CLASS::get_instance();
		$value = get_option( $option['name'] );
		
		if ( ! isset( $wp_roles ) )
		$wp_roles = new WP_Roles();

		$roles = $wp_roles->get_names(); 
		?><ul><?php 
		asort( $roles );
		foreach( $roles as $role_key=>$role_name )
		{
			$id = sanitize_key( $role_key );
			
			$post_type_name = $overwrite_author_name->clean_post_type_name( $role_key );
			$role_active = $this->oan_role_active( $role_key, (array) $value )

	// Render the output  
	?> 
			<li><label>
			<input type='checkbox'  
				id="<?php echo esc_html( "help_notes_{$id}" ) ; ?>" 
				name="<?php echo esc_html( $option['name'] ); ?>[][<?php echo esc_html( $role_key ) ; ?>]"
				value="<?php echo esc_attr( $post_type_name )	; ?>"<?php checked( $role_active ); ?>
			>
			<?php echo esc_html( $role_name ) . " <br/>"; ?>	
			</label></li>
			<?php 
		}?></ul><?php 
		if ( ! empty( $option['desc'] ))
			echo ' <p class="description">' . esc_html( $option['desc'] ) . '</p>';		
	}
	
		/**
		 * field_page_select_list_option 
		 *
		 * @param array of arguments to pass the option name to render the form field.
		 * @access public
		 * @return void
		 */
		public function field_wp_dropdown_users( array $args  ) {

			$option	= $args['option'];
			
			?><label for="<?php echo $option['name']; ?>"><?php 
			wp_dropdown_users( array( 
				
									'show_option_none' => _x( "- None -", 'text for no selection', 'overwrite-author-name'), 
									'name' => $option['name'],
                                'orderby ' => 'display_name', 
                                'echo'          => 1,
									'selected'     => get_option( $option['name'] ),
									'id'         	=> 'setting-' . $option['name'],
									'option_none_value' => '0', 
									
            				    )); ?>
			</label>

			
	<?php 
			if ( ! empty( $option['desc'] ))
				echo ' <p class="description">' . esc_html( $option['desc'] ) . '</p>';		
}


/**
 * Renders settings field for Post Types
 */
		public function settings_field_selected_post_types( array $args  ) {

			$option	= $args['option'];
			$value = get_option( $option['name'] );
	  
    /* Only add the meta box if the current user has the 'restrict_content' capability. */
	if ( current_user_can( 'manage_options' ) ) {

		/* Get all available public post types. */
		$post_types = get_post_types( array( 'public' => true ), 'objects' );

        /* Loop through each post type, adding to the settings */
        foreach ( $post_types as $post_type ) {
           
           if (post_type_supports( $post_type->name, 'author' )) {
               // Render the output  
            	?> 
        		<input 
        			type='checkbox'  
        			id="<?php echo $post_type->name ; ?>" 
							name="<?php echo $option['name']; ?>[]"  
							value="<?php echo $post_type->name; ?>"<?php checked( in_array( $post_type->name, (array) $value )); ?>
        		</input>
                
        	    <?php echo $post_type->labels->name." (". $post_type->name .") <br />";		
           }
        }
        
				?><p>			
				<?php
			if ( ! empty( $option['desc'] ))
				echo ' <p class="description">' . esc_html( $option['desc'] ) . '</p>';		
			}
		}
	
	/**
	 * oan_role_active 
	 *
	 * @param $role current role and $active_helpnote_roles array of active help notes.
	 * @access public
	 * @return void
	 */
	public function oan_role_active( $role, $active_helpnote_roles ) {

		foreach ($active_helpnote_roles as $active_role=>$active_posttype) {
				if (! empty($active_posttype["$role"])) {
					return true;
				}
		}
		return false;
    }
}


// Include the Tabbed_Settings class.
require_once( dirname( __FILE__ ) . '/class-tabbed-settings.php' );

// Create new tabbed settings object for this plugin..
// and Include additional functions that are required.
oan_Settings::get_instance()->registerHandler( new oan_Settings_Additional_Methods() );

	

?>