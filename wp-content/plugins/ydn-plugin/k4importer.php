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
		add_rewrite_endpoint( 'importer', EP_ROOT );
	}
	add_action( 'init', 'add_importer_endpoint' );
	function importer_template_redirect() {
		global $wp_query;
		echo("Importer! Post requests to this page will be imported.\n");
		if(!empty($_POST))
			wp_mail("akshay.nathan08@gmail.edu", "POST REQUEST", implode(",", $_POST));	
		exit;
	}
	add_action( 'template_redirect', 'importer_template_redirect' );

	function makeplugins_endpoints_activate() {
        	makeplugins_endpoints_add_endpoint();
        	flush_rewrite_rules();
	}
	register_activation_hook( __FILE__, 'makeplugins_endpoints_activate' );
 
	function makeplugins_endpoints_deactivate() {
		flush_rewrite_rules();
	}
	register_deactivation_hook( __FILE__, 'makeplugins_endpoints_deactivate' );
?>
