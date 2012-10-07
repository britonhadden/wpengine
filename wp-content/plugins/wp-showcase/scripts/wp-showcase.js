jQuery(document).ready(function($){

	$('.wp-showcase.enable-lightbox .wp-showcase-gallery a').colorbox({
		current: '{current} / {total}',
		opacity: 0.8,
		returnFocus: false,
		maxWidth: '90%',
		maxHeight: '90%',
		onComplete: function(){
			if($('#cboxTitle').html() == '') $('#cboxTitle').hide();
			else $('#cboxTitle').show();
			
			$('#cboxTitle').html(replaceURLWithHTMLLinks($('#cboxTitle').html()));
		}
	});
	
	function replaceURLWithHTMLLinks(text) {
	    var exp = /(\b(https?|ftp|file):\/\/[-A-Z0-9+&@#\/%?=~_|!:,.;]*[-A-Z0-9+&@#\/%=~_|])/ig;
	    return text.replace(exp,"<a href='$1'>$1</a>"); 
	}
	
});

