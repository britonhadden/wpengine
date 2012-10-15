<?php
/*
Plugin Name: Unfiltered HTML
Plugin URI: 
Description: A riff off Unfiltered MU and Less Filters. Removes kses filters
Author: Daniel Bachhuber
Version: 0.1
Author URI: http://www.danielbachhuber.com/
*/

function uh_init() {
	
	// Remove the KSES filter
	remove_filter('content_save_pre', 'wp_filter_post_kses');
	remove_filter('excerpt_save_pre', 'wp_filter_post_kses');
	remove_filter('content_filtered_save_pre', 'wp_filter_post_kses');
	
}

add_action( 'init', 'uh_init', 11 );

?>