<?php
/* Plugin Name: YDN Legacy Photos
Plugin URI: http://yaledailynews.com
Description: This plugin uses WordPress shortcodes to replace legacy photo markers with actual photos.
Version: 1.0
Author: Earl Lee & Michael DiScala
License: GPL2
*/

/**
 * This function replaces photos.
 **/

function ydn_legacy_photos_filter($atts) {
	$id = $atts[id];

	$src = wp_get_attachment_image_src($id);
	#print "ID variable is $id";
	var_dump($src);
	#var_dump($atts);
	$return_string = '<img src="' . $src(0) . '" />';
	return return_string;
	die();

}

add_shortcode('ydn-legacy-photos', 'ydn_legacy_photos_filter');

?>