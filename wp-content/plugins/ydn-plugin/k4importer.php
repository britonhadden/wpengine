<?php
/*
Plugin Name: K4Importer
Description: Importer for K4.
Version: 1.0
Author: Akshay Nathan
Author URI: http://URI_Of_The_Plugin_Author
License: GPL2
*/
require(dirname(__FILE__) . '/wp-load.php');
	add_action( 'plugins_loaded', init_importer );
	function init_importer() {
		echo("HELLO WORLD");
		wp_mail( "akshay.nathan@yale.edu", "Test", "Email Test" );
	}
	class k4Importer {
		function import_story($xml_string) {
			$xml = new SimpleXMLElement($xml_string);
			// Extract Title
			$title = $xml->headline->hl1->trim();
			// Extract Authors into array with primary author first
				// Split on and? get rid of whitespace and //'s
			// Extract story html
			$story = $xml->{'body.content'}->trim();
			// Extract abstract, or leave blank if theres nothing but space
			$excerpt = $xml->abstract->trim();
			// Get the category/ies
		}
	}
?>
