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
	return "<img src=\"http://yaledailynews.com/wp-content/uploads/2012/09/ct-state-capitol-creative-commons-300x225.jpg\" />";
	var_dump($atts);
	die();

}

add_shortcode('ydn-legacy-photos', 'ydn_legacy_photos_filter');

?>