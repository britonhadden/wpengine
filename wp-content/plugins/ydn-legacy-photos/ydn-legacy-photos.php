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

	if ( wp_attachment_is_image( $id ) ) {
		#$src = wp_get_attachment_image($id, medium);
		print "ID variable is $id";
		#var_dump($src);
		#var_dump($atts);
		$return_string = '<div class="inline inline-left">' . wp_get_attachment_image($id, medium) . get_media_credit_html($id) . ' </div>';
		return $return_string;
	}

}

add_shortcode('ydn-legacy-photo-inline', 'ydn_legacy_photos_filter');

?>
