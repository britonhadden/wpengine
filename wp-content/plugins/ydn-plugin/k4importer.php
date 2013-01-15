<?php
/*
Plugin Name: K4Importer
Description: Importer for K4.
Version: 1.0
Author: Akshay Nathan
Author URI: http://URI_Of_The_Plugin_Author
License: GPL2
*/
	function add_importer_endpoint() {
		add_rewrite_endpoint( 'json', EP_ROOT );
	}
	add_action( 'init', 'add_importer_endpoint' );
	function importer_template_redirect() {
		print_r( $wp_query->query_vars );
	}
	add_action( 'template_redirect', 'importer_template_redirect' );
?>
