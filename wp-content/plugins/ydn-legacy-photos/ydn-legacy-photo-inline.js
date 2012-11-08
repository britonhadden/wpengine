(function($) {
	var YDN = window.YDN || (window.YDN = {});

	function initialize() {
		console.log($(".ydn-legacy-photo img"));
		var $ydnPhotoModal = $("#ydn-photo-modal");
		var $ydnPhotoModalPhoto = $ydnPhotoModal.find('.modal-photo');
		var $ydnPhotoModalCaption = $ydnPhotoModal.find('.modal-caption');
		$(".ydn-legacy-photo img").each(function (index, object) {
			var $object = $(object);
			var image_large = $object.data('image-large');
			var image_caption = $object.attr('alt');
			var image_html = '<img src="' + image_large + '" >';
			// console.log(image_caption);
			$object.click( function() {
				$ydnPhotoModalPhoto.html(image_html);
				$ydnPhotoModalCaption.html(image_caption);
				$ydnPhotoModal.modal('show');
				return false;
			});
		});
	}

	$(document).ready( initialize );

} (jQuery) );

