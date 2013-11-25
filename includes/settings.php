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

    if ( get_option( 'overwrite_author_update_request' )) {
        
            update_option( 'overwrite_author_update_request', '' );
            //help_do_on_activation();
            
    }


	register_setting(  
		'overwrite_author_option_group',  		// A settings group 
		'overwrite_author_option'   ,  		 
		'sanitize_overwrite_author_option'  	
	);  


    // General Settings..
    
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


} // end overwrite_plugin_intialize_options()

function overwrite_author_general_section_callback() {  


    echo '<p>Select the User name to overwrite the author during any post/page save. </p>'; 


} // end help_note_post_types_section_callback  


function settings_field_selected_author() {
	// First, we read the option collection  
	$options = get_option('overwrite_author_option');  

	// Render the output  
	?> 


    <form action="<?php bloginfo('url'); ?>" method="get">
    <?php wp_dropdown_users(array('name' => 'author',
                                'orderby ' => 'display_name', 
                                'echo'          => 1,
                				'selected'     => $options['selected_author'],
            					'name'          => 'overwrite_author_option[selected_author]'
            				    )); ?>
    </form>
    


	<?php
}

function sanitize_overwrite_author_option( $settings ) {  

	// set the flag to flush the Permalink rules on save of the settings.
	update_option( 'overwrite_author_update_request', '1' );

    
	// option must be safe
	//$settings['help_note_post_types'] = isset( $settings['help_note_post_types'] ) ? (array) $settings['help_note_post_types'] : array();


	return $settings;
	
}




?>