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
        global $wpdb;
		if ( ! isset( $wp_query->query_vars['importer'] ) )
                	return;
		echo("Importer! An endpoint to import k4 into the wordpress dbs.\n");
        if( ! empty($_POST) || ! empty($_GET))
            echo("Request received.");
        wp_mail("ydnimportarchive@gmail.com", "REQUEST!", "GET:" . implode(",", $_GET) . "POST:" . implode(",", $_POST));
		exit;
	}
	add_action( 'template_redirect', 'importer_template_redirect' );

	function makeplugins_endpoints_activate() {
        	add_importer_endpoint();
        	flush_rewrite_rules();
	}
	register_activation_hook( __FILE__, 'makeplugins_endpoints_activate' );
 
	function makeplugins_endpoints_deactivate() {
		flush_rewrite_rules();
	}
	register_deactivation_hook( __FILE__, 'makeplugins_endpoints_deactivate' );
?>
