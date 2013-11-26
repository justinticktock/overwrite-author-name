<?php

/* Add capabilities and Flush your rewrite rules for plugin activation */
function overwrite_author_name_do_on_activation() {

    $defaults = array(
      'overwrite_post_types'    => array(),
      'selected_author'         => false,
    );
    
    $options = wp_parse_args(get_option('overwrite_author_option'), $defaults);
    
	// create the option on plugin intialisation 
    update_option('overwrite_author_option', $options); 

}

register_activation_hook( HELP_MYPLUGINNAME_PATH.'overwrite-author-name.php', 'overwrite_author_name_do_on_activation' );

?>