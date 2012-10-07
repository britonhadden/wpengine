jQuery(document).ready(function($){ 

	if($('#showcase-file-uploader').length){
		// File Uploader
	    var uploader = new plupload.Uploader({
			runtimes: 'gears,html5,flash,silverlight',
			browse_button: 'pickfiles',
			container: 'showcase-file-uploader',
			max_file_size: '10mb',
			url: ajaxurl +'?action=showcase_upload&post_id='+ wp_showcase.post_id +'&nonce='+ wp_showcase.nonce,
			flash_swf_url: wp_showcase.plugin_folder +'/scripts/plupload/plupload.flash.swf',
			silverlight_xap_url: wp_showcase.plugin_folder +'/scripts/plupload/plupload.silverlight.xap',
			filters: [
				{ title:'Image files', extensions:'jpg,gif,png' }
			]
		});
	
		$('#uploadfiles').click(function(e) {
			uploader.start();
			e.preventDefault();
		});
	
		uploader.init();
	
		uploader.bind('FilesAdded', function(up, files) {
			$.each(files, function(i, file) {
				$('#filelist').append('<li id="'+ file.id +'">' + file.name + ' (' + plupload.formatSize(file.size) + ') <strong></strong></li>');
			});
	
			up.refresh(); // Reposition Flash/Silverlight
		});
	
		uploader.bind('UploadProgress', function(up, file) {
			$('#' + file.id + ' strong').html(file.percent + '%');
		});
	
		uploader.bind('Error', function(up, err) {
			$('#filelist').append('<li class="error">Error: ' + err.code + ', Message: ' + err.message + 
			(err.file ? ", File: " + err.file.name : "") + '</li>');
	
			up.refresh(); // Reposition Flash/Silverlight
		});
	
		uploader.bind('FileUploaded', function(up, file, resp) {
			$('#' + file.id).remove();
			
			var response = $.parseJSON(resp.response);
			if(response.error){
				if(response.message != undefined)
	            	alert(response.message);
	            else 
	            	alert(response.error.message);
	        } else {
	            $('#showcase-images').append('<li id="attachment-' + response.attachment_id + '">' +
	                '<img src="' + response.upload_path + '/' + response.file + '" alt="" class="attachment-thumbnail" />' +
	                '<div class="handlebar">' +
	                '<a href="#" class="remove-image" rel="' + response.attachment_id + '" title="Remove Image">Remove</a>' +
	                '<a href="#" class="edit-image" rel="' + response.attachment_id + '" title="Edit Caption">Edit</a></div></li>');
	        }
	        
	        up.refresh(); // Reposition Flash/Silverlight
	        save_order();
		});
	}
    
    // Edit Caption
    $('#showcase-images .edit-image').live('click', function(){
        var edit = $(this);
        $('#showcase_meta_link').val('');
        $('#showcase_meta_caption').val('');
        $('#showcase_meta_alt').val('');
        $('#showcase-edit-image').data('attach_id', edit.attr('rel'));
        $('#showcase-edit-image').modal();
        
        $('#showcase-edit-image strong').addClass('loading');
        $.post(ajaxurl, 
            { action:'showcase_load_meta', id:$('#showcase-edit-image').data('attach_id'), 
              nonce: wp_showcase.nonce }, 
            function(data){
                $('#showcase_meta_link').val(data.link);
                $('#showcase_meta_caption').val(data.caption);
                $('#showcase_meta_alt').val(data.alt);
                $('#showcase-edit-image strong').removeClass('loading');
            }
        , 'json');
        return false;
    });
    $('#showcase_meta_submit').live('click', function(){
        $('#showcase_meta_submit').val('Saving…');
        $.post(ajaxurl, 
            { action:'showcase_edit', id:$('#showcase-edit-image').data('attach_id'), 
              link:$('#showcase_meta_link').val(), caption:$('#showcase_meta_caption').val(),
              alt:$('#showcase_meta_alt').val(), nonce: wp_showcase.nonce }, 
            function(data){
                $('#showcase_meta_submit').val('Save Changes');
                $.modal.close();
            }
        );
    });
    
    // Remove Image
    $('#showcase-images .remove-image').live('click', function(){
        var remove = $(this);
        $.post(ajaxurl, 
            { action:'showcase_remove', data:remove.attr('rel'),
              nonce: wp_showcase.nonce }, 
            function(data){
                remove.parent().parent().fadeOut(500, function(){
                    remove.parent().parent().remove();
                });
            }
        );
        
        return false;
    });
    
    // Drag & Drop sort images
    $('#showcase-images').sortable({
    	revert: true,
        update: function(event, ui){
            save_order();
        }
    });
    
    function save_order(){
    	$.post(ajaxurl, 
            $('#showcase-images').sortable('serialize') + '&action=showcase_order_save&nonce='+ wp_showcase.nonce, 
        function(response){
            response = jQuery.parseJSON(response);
            if(response.error){
                alert(response.message);
            }
        });
    }
    
    loadImages(); // Initial load
    function loadImages(){
        $('#showcase-images').html('<li class="loading">Loading…</li>');
        $.ajax({
            url: ajaxurl, 
            type: 'POST',
            dataType: 'json',
            data: { action:'showcase_load_images', id: wp_showcase.post_id, nonce: wp_showcase.nonce,
            		source:$('select[name="showcase_settings[source]"] option:selected').val(), 
            		gallery:$('select[name="showcase_settings[source_gallery]"] option:selected').val(),
            		flickrid:$('input[name="showcase_settings[flickr_id]"]').val() }, 
            success: function(response){
                if(response.error){
                    alert(response.message);
                } else {
                    $('#showcase-images').html('');
                    for(var i in response.images){
                        var image = response.images[i];
                        var output = '<li id="attachment-' + image.id + '">' +
                            '<img src="' + image.src + '" alt="" class="attachment-thumbnail" />' +
                            '<div class="handlebar">'+
                            '<a href="#" class="remove-image" rel="' + image.id + '" title="Remove Image">Remove</a>' +
                            '<a href="#" class="edit-image" rel="' + image.id + '" title="Edit Caption">Edit</a></div></li>';
                        $('#showcase-images').append(output);
                    }
                }
            },
            error: function(response, status, error){
                alert('Error: ' + error.replace(/(<([^>]+)>)/ig,""));
            }
        });
    }
    
    // Image Source
    $('select[name="showcase_settings[source]"]').change(function(){
        if($('option:selected', this).val() == 'gallery') $('#showcase_type_gallery').show();
        else $('#showcase_type_gallery').hide();
        
        if($('option:selected', this).val() == 'flickr') $('#showcase_flickr_id').show();
        else $('#showcase_flickr_id').hide();
        
        $('#showcase_upload_box').removeClass('image-source-upload image-source-gallery image-source-flickr');
        $('#showcase_upload_box').addClass('image-source-'+ $('option:selected', this).val());
        
        loadImages();
    });
        
    $('#showcase_upload_box').addClass('image-source-'+ $('select[name="showcase_settings[source]"] option:selected').val());
    if($('select[name="showcase_settings[source]"] option:selected').val() == 'gallery') $('#showcase_type_gallery').show();
    if($('select[name="showcase_settings[source]"] option:selected').val() == 'flickr') $('#showcase_flickr_id').show();
    
    $('select[name="showcase_settings[source_gallery]"]').change(function(){
    	loadImages();
    });
    
    // Gallery Layout & Slider Settings 
    $('select[name="showcase_settings[gallery_layout]"]').change(function(){
    	if($('option:selected', this).val() != 'slider') {
    		$('#enable_image_slider').show();
    		if($('input[name="showcase_settings[show_slideshow]"]').is(':checked')) {
	    		$('tr.slider_settings').show();  
    		} else {
	    		$('tr.slider_settings').hide();  
    		} 
    	} else {
    		$('#enable_image_slider').hide();
    		$('tr.slider_settings').show();  
    	}
    });
    
    if($('select[name="showcase_settings[gallery_layout]"] option:selected').val() != 'slider') {
    	$('#enable_image_slider').show(); 
    	if($('input[name="showcase_settings[show_slideshow]"]').is(':checked')) $('tr.slider_settings').show();  
    } else {
		$('tr.slider_settings').show();	    
	}
	
	
	$('input[name="showcase_settings[show_slideshow]"]').change(function(){
		if($(this).is(':checked')) {
			$('tr.slider_settings').show();  
		} else {
			$('tr.slider_settings').hide();  
		}
	
	});
	
	//Lightbox config
	if($('select[name="showcase_settings[lightbox-config]"] option:selected').val() == 'default') {
		
		$('.lb-default').closest("tr").show();
		$('.lb-custom').closest("tr").hide();
	} else {
		
		$('.lb-default').closest("tr").hide();
	    $('.lb-custom').closest("tr").show();	 
	}
 
    $('select[name="showcase_settings[lightbox-config]"]').change(function(){
	    if($('option:selected', this).val() == 'default') {
	    	$('.lb-default').closest("tr").show();
	    	$('.lb-custom').closest("tr").hide();
	    } else {
			$('.lb-default').closest("tr").hide();
	    	$('.lb-custom').closest("tr").show();	    
		}
	});
 
});