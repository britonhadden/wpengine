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
	$image_large = wp_get_attachment_image_src( $id, 'modal-photo' );
	$image_credit = get_media_credit_html($id);
	$image_caption = get_post($id)->post_excerpt;

<<<<<<< HEAD
	if (wp_attachment_is_image($id)) {
		$return_string = '<div class="inline inline-left">' . wp_get_attachment_image($id, medium) . get_media_credit_html($id) . ' </div>';
=======
	if ( wp_attachment_is_image( $id ) ) {
<<<<<<< HEAD
		$return_string = '<div id="ydn-legacy-photo-inline-' . $id . '" class="inline inline-left ydn-legacy-photo"><a href="' . $attachment_link . '">' . wp_get_attachment_image($id, medium) . '</a>
		<div class="photo-credit">' . get_media_credit_html($id) . '</div><div class="caption">' . get_post($id)->post_excerpt . ' </div></div>';
>>>>>>> c159d39f7ad2ce721e59ad783119bd9d248cf25c
=======
		$return_string = "<div id=\"ydn-legacy-photo-inline-{$id}\" class=\"inline inline-left ydn-legacy-photo\"><a href=\"{$attachment_link}\"><img alt=\"{$image_caption}\"src=\"{$image_small[0]}\" data-image-large=\"{$image_large[0]}\" ></a>
		<div class=\"photo-credit\">{$image_credit}</div><div class=\"caption\">{$image_caption}</div></div>";
>>>>>>> 549c5414e4ebf56734bcc063e7cea5e400b8447f
		return $return_string;
	}

}

add_shortcode('ydn-legacy-photo-inline', 'ydn_legacy_photos_filter');

function ydn_legacy_photos_modal() {
    if (is_singular('post')):
    ?>
		<div class="modal hide" id="ydn-photo-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-body">
				<!-- <div class="modal-close">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				</div> -->
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

function ydn_legacy_photos_enqueue_scripts() {
  if (is_singular('post')) {
    wp_enqueue_script( 'ydn-legacy-photo-inline', plugins_url() . '/ydn-legacy-photos/ydn-legacy-photo-inline.js');
  }
}
add_action('wp_enqueue_scripts','ydn_legacy_photos_enqueue_scripts');

?>
