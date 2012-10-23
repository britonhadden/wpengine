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
	$image_small = wp_get_attachment_image_src( $id, medium );
	$image_large = wp_get_attachment_image_src( $id, large );
	$image_credit = get_media_credit_html($id);
	$image_caption = get_post($id)->post_excerpt;

	if ( wp_attachment_is_image( $id ) ) {
		$return_string = "<div id=\"ydn-legacy-photo-inline-{$id}\" class=\"inline inline-left ydn-legacy-photo\"><a href=\"{$attachment_link}\"><img alt=\"{$image_caption}\"src=\"{$image_small[0]}\" data-image-large=\"{$image_large[0]}\" ></a>
		<div class=\"photo-credit\">{$image_credit}</div><div class=\"caption\">{$image_caption}</div></div>";
		return $return_string;
	}

}

add_shortcode('ydn-legacy-photo-inline', 'ydn_legacy_photos_filter');

function ydn_legacy_photos_modal() {
    if ( is_singular()):
    ?>
		<div class="modal hide" id="ydn-photo-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-body">
				<div class="modal-close">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				</div>
				<div class="modal-photo">
				</div>
			</div>
			<div class="modal-caption">
				
			</div>
		</div>
    <?php
    endif;
}
add_action('wp_footer', 'ydn_legacy_photos_modal');

?>
