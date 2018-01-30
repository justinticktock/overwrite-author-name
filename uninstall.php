<?php

//if uninstall not called from WordPress exit
if ( !defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit ();
}
	
if (is_multisite()) {

    $blogs = wp_list_pluck( wp_get_sites(), 'blog_id' );

    if ($blogs) {
        foreach($blogs as $blog) {
            switch_to_blog($blog['blog_id']);
            delete_option('overwrite_author_option');
        }
        restore_current_blog();
    }
} else {
		delete_option('overwrite_author_option');
}


?>