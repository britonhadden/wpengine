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
	var_dump($atts);
	die();
}

add_shortcode('ydn-legacy-photo', 'ydn_legacy_photos_filter');

?>