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
	$attachment_link = get_attachment_link($id);

	if ( wp_attachment_is_image( $id ) ) {
		$return_string = '<div id="ydn-legacy-photo-inline-' . $id . '" class="inline inline-left ydn-legacy-photo"><a href="' . $attachment_link . '">' . wp_get_attachment_image($id, medium) . '</a>
		<div class="photo-credit">' . get_media_credit_html($id) . '</div><div class="caption">' . get_post($id)->post_excerpt . ' </div></div>';
		return $return_string;
	}

}

add_shortcode('ydn-legacy-photo-inline', 'ydn_legacy_photos_filter');

function ydn_legacy_photos_modal() {
    if ( is_singular()):
    ?>
		<div class="modal hide" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			
			<div class="modal-body ydn-photo-modal">
				<div class="modal-close">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				</div>
				<div class="modal-photo">
					<img src="http://yaledailynews.com/wp-content/uploads/2012/10/food.jpg">
				</div>
			</div>
			<div class="modal-footer">
				<p>Let's pretend I was a caption.</p>
			</div>
		</div>
    <?php
    endif;
}
add_action('wp_footer', 'ydn_legacy_photos_modal');

?>
