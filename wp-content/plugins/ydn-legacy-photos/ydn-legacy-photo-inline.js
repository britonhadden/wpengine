(function($) {
	var YDN = window.YDN || (window.YDN = {});

	function initialize() {
		console.log($(".ydn-legacy-photo img"));
		var $myModal = $("#myModal");
		$(".ydn-legacy-photo img").each(function (index, object) {
			var $object = $(object);
			$object.click( function() {
				$myModal.modal('show');
				return false;
			});
		});
	}

	$(document).ready( initialize );

} (jQuery) );

