<?php

// Create a second level settings page
add_action('admin_menu', 'register_overwrite_author_name_settings_page');

function register_overwrite_author_name_settings_page() {
    add_submenu_page( 'options-general.php', 'Overwrite Author Name', 'Overwrite Author', 'manage_options', OAN_SETTINGS_PAGE, 'overwrite_author_settings_page_callback' ); 
}

function overwrite_author_settings_page_callback( $args = '' ) {
        extract( wp_parse_args( $args, array(
            'title'       => __( 'Overwrite Author Settings' ),
            'options_group' => 'overwrite_author_option_group',
            'options_key' => 'overwrite_author_option'
        ) ) );
        ?>
        <div id="<?php echo $options_key; ?>" class="wrap">
            <?php screen_icon( 'options-general' ); ?>
    		<h2><?php echo esc_html( $title ); ?></h2>
			<form method="post" action="options.php">
				<?php
					settings_fields( 'overwrite_author_option_group' );
					do_settings_sections( OAN_SETTINGS_PAGE );
					submit_button();
				?>
			</form>
		</div>
		<?php
	}  // end settings_page_content  

/** 
 * Initializes the plugin's option by registering the Sections, 
 * Fields, and Settings. 
 * 
 * This function is registered with the 'admin_init' hook. 
 */   
 
add_action('admin_init', 'overwrite_plugin_intialize_options' );  

function overwrite_plugin_intialize_options() {  

	register_setting(  
		'overwrite_author_option_group',  		// A settings group 
		'overwrite_author_option'   ,  		 
		'sanitize_overwrite_author_option'  	
	);  

     add_settings_section(
		'overwrite_author_general',        			    
		'General',            	                  
		'overwrite_author_general_section_callback',  	   
		OAN_SETTINGS_PAGE      					  
	);  
       
    add_settings_field(   
		'selected_author',                 	
		'Enforce Author:',             				
		'settings_field_selected_author', 
		OAN_SETTINGS_PAGE,   						
		'overwrite_author_general'  						
	); 

    add_settings_field(   
    	'selected_post_types',                 	
		'Enforce Post Types:',             				
		'settings_field_selected_post_types', 
		OAN_SETTINGS_PAGE,   						
		'overwrite_author_general'  						
	); 
    
} // end overwrite_plugin_intialize_options()

function overwrite_author_general_section_callback() {  

    echo '<p>Select the User name to overwrite the author during any post/page save. </p>'; 
	
} 

/**
 * Renders settings field for Post Types
 */
function settings_field_selected_author() {
	// First, we read the option collection  
	$options = get_option('overwrite_author_option');  

	// Render the output  
	?> 
    <form action="<?php bloginfo('url'); ?>" method="get">
    <?php wp_dropdown_users(array(
                                'show_option_none' => __( "- None -" ), 
								'name' => 'author',
                                'orderby ' => 'display_name', 
                                'echo'          => 1,
                				'selected'     => $options['selected_author'],
            					'name'          => 'overwrite_author_option[selected_author]'
            				    )); ?>
    </form>
	<?php 
}

/**
 * Renders settings field for Post Types
 */
function settings_field_selected_post_types() {

    // First, we read the option collection  
	$options = get_option('overwrite_author_option');  
	  
    /* Only add the meta box if the current user has the 'restrict_content' capability. */
	if ( current_user_can( 'manage_options' ) ) {

		/* Get all available public post types. */
		$post_types = get_post_types( array( 'public' => true ), 'objects' );
        
        /* Loop through each post type, adding to the settings */
        foreach ( $post_types as $post_type ) {
           
           // Render the output  
        	?> 
    		<input 
    			type='checkbox'  
    			id="<?php echo $post_type->name ; ?>" 
    			name="overwrite_author_option[overwrite_post_types][]"  
    			value="<?php echo $post_type->name; ?>"<?php checked( in_array( $post_type->name, (array) $options['overwrite_post_types']) ); ?>
    		</input>
            
    	    <?php echo $post_type->labels->name." (". $post_type->name .") <br />";		

        }
    }
}

function sanitize_overwrite_author_option( $settings ) {  

	// option must be safe
	$settings['overwrite_post_types'] = isset( $settings['overwrite_post_types'] ) ? (array) $settings['overwrite_post_types'] : array();

	return $settings;
	
}

?>