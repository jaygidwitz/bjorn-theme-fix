(function($){
$(document).ajaxComplete(function() {
   //$('.select-text-color').wpColorPicker();
});
$(document).ready(function() {

	'use strict';

	// Welcome message
	$('#mgt-welcome-message-show-steps').on('click', function(e){
		$('.mgt-welcome-message-show-steps').slideUp();
		$('.mgt-welcome-message-steps-wrapper').slideDown();
		$('div.mgt-welcome-message.updated').css('border-color', '#0091CD');
	});


	//$('.select-text-color').wpColorPicker();

	// Uploading image in content widget
	var file_frame;
	var textarea_id;
	var clicked_button;

	$('.button.upload-widget-image').live('click', function( event ){

		event.preventDefault();

		clicked_button = $(this);
		textarea_id = clicked_button.data( 'textarea_id' );

		// If the media frame already exists, reopen it.
		if ( file_frame ) {
			file_frame.open();
			return;
		}

		// Create the media frame.
		file_frame = wp.media.frames.file_frame = wp.media({
			title: $( this ).data( 'uploader_title' ),
			button: {
			text: $( this ).data( 'uploader_button_text' ),
			},
			multiple: false  // Set to true to allow multiple files to be selected
		});

		// When an image is selected, run a callback.
		file_frame.on( 'select', function() {
			// We set multiple to false so only get one image from the uploader
			var attachment = file_frame.state().get('selection').first().toJSON();

			// Do something with attachment.id and/or attachment.url here
			var content_textarea = document.getElementById(textarea_id);

			var img_html = '<img src="'+attachment.url+'" alt=""/>';
			content_textarea.innerHTML+=img_html;
 
		});

		// Finally, open the modal
		file_frame.open();
	});
	

	// Uploading bg image in content widget
	var file_frame_2;
	var input_id;
	var clicked_button_2;

	$('.button.upload-widget-bg-image').live('click', function( event ){

		event.preventDefault();

		clicked_button_2 = $(this);
		input_id = clicked_button_2.data( 'input_id' );

		// If the media frame already exists, reopen it.
		if ( file_frame_2 ) {
			file_frame_2.open();
			return;
		}

		// Create the media frame.
		file_frame_2 = wp.media.frames.file_frame = wp.media({
			title: $( this ).data( 'uploader_title' ),
			button: {
			text: $( this ).data( 'uploader_button_text' ),
			},
			multiple: false  // Set to true to allow multiple files to be selected
		});

		// When an image is selected, run a callback.
		file_frame_2.on( 'select', function() {
			// We set multiple to false so only get one image from the uploader
			var attachment = file_frame_2.state().get('selection').first().toJSON();

			// Do something with attachment.id and/or attachment.url here
			var content_input = document.getElementById(input_id);

			// If input to insert URL
			content_input.value = attachment.url;
		  
		});

		// Finally, open the modal
		file_frame_2.open();
	});

	// Functions
	function setCookie (name, value, expires, path, domain, secure) {
	      document.cookie = name + "=" + escape(value) +
	        ((expires) ? "; expires=" + expires : "") +
	        ((path) ? "; path=" + path : "") +
	        ((domain) ? "; domain=" + domain : "") +
	        ((secure) ? "; secure" : "");
	}
	function getCookie(name) {
		var cookie = " " + document.cookie;
		var search = " " + name + "=";
		var setStr = null;
		var offset = 0;
		var end = 0;
		if (cookie.length > 0) {
			offset = cookie.indexOf(search);
			if (offset != -1) {
				offset += search.length;
				end = cookie.indexOf(";", offset)
				if (end == -1) {
					end = cookie.length;
				}
				setStr = unescape(cookie.substring(offset, end));
			}
		}
		return(setStr);
	}
	
});
})(jQuery);