var fadeDuration = 300;

$(function() {
	$('#login_popup').click(
		function() {
			$('#popup_wrapper').fadeToggle(fadeDuration, function() {} );
			$('#login_content').fadeToggle(fadeDuration, function() {} );
			$('#fade').fadeToggle(fadeDuration, function() {} );
			return false; 
		}
	);

	$('#quick_popup').click(
		function() {
			$('#popup_wrapper').fadeToggle(fadeDuration, function() {} );
			$('#quick_content').fadeToggle(fadeDuration, function() {} );
			$('#fade').fadeToggle(fadeDuration, function() {} );
			return false; 
		}
	);

	$('#contact_popup').click(
		function() {
			$('#popup_wrapper').fadeToggle(fadeDuration, function() {} );
			$('#contact_content').fadeToggle(fadeDuration, function() {} );
			$('#fade').fadeToggle(fadeDuration, function() {} );
			return false; 
		}
	);

	$('#active_popup').click(
		function() {
			$('#popup_wrapper').fadeToggle(fadeDuration, function() {} ).delay(1000).fadeToggle(fadeDuration, function() {} );
			$('#active_content').fadeToggle(fadeDuration, function() {} ).delay(1000).fadeToggle(fadeDuration, function() {} );
			$('#fade').fadeToggle(fadeDuration, function() { change_text(); } ).delay(1000).fadeToggle(fadeDuration, function() { load_page(); });

			return false; 
		}
	);

	$('#logout_popup').click(
		function() {
			$('#popup_wrapper').fadeToggle(fadeDuration, function() {} );
			$('#logout_content').fadeToggle(fadeDuration, function() {} );
			$('#fade').fadeToggle(fadeDuration, function() {} );
			return false; 
		}
	);

	$('#login_popup_close').click(
		function() {
			$('#popup_wrapper').fadeToggle(fadeDuration, function() {} );
			$('#login_content').fadeToggle(fadeDuration, function() {} );
			$('#fade').fadeToggle(fadeDuration, function() {} );
			return false; 
		}
	);

	$('#encounter_popup_add_popup_close').click(
		function() {
			$('#popup_wrapper').fadeToggle(fadeDuration, function() {} );
			$('#quick_content').fadeToggle(fadeDuration, function() {} );
			$('#fade').fadeToggle(fadeDuration, function() {} );
			return false; 
		}
	);

	$('#contact_popup_close').click(
		function() {
			$('#popup_wrapper').fadeToggle(fadeDuration, function() {} );
			$('#contact_content').fadeToggle(fadeDuration, function() {} );
			$('#fade').fadeToggle(fadeDuration, function() {} );
			return false; 
		}
	);

	$('#logout_popup_close').click(
		function() {
			$('#popup_wrapper').fadeToggle(fadeDuration, function() {} );
			$('#logout_content').fadeToggle(fadeDuration, function() {} );
			$('#fade').fadeToggle(fadeDuration, function() {} );
			return false; 
		}
	);

	$('#body_wrapper').mouseover(
		function() {
			$('.tip-item').each(function() {
				$(this).css("position", "relative");
			});

			//$('.backdrop').each(function() {
				//$(this).css('display', 'none');
				//$(this).children().('display', 'none');
			//}
		}
	);

	$('#nav_wrapper').mouseover(
		function() {
			var count = 0;
			$('.tip-item').each(function() {
				if ( count == 30 ) { return false; }

				$(this).css("position", "static");
				count++;
			});
		}
	);

	$('.dropdown').hover(
		function() {
			var item_width 	= $(this).css('width');
			var item_height = $(this).css('height');
			$(this).css('background-color', '#000000');
			$(this).children('.backdrop').css('width', item_width);
			$(this).children('.backdrop').css('height', item_height);
			$(this).children('.backdrop').toggle();

			var item_height = $(this).children('ul').css('width');
			$(this).children('ul').css('width', item_height); // Add Smoothness
			$(this).children('ul').toggle();
		}, function() {
	    	$(this).css('background-color', 'transparent');
	    	$(this).children('.backdrop').toggle();
	    	$(this).children('ul').toggle();
	  	}
	);

	$('.spreadsheet_popup_button').click(
		function() {
			$(".spreadsheet_content").fadeToggle(fadeDuration, function() {} );
			$('#fade').fadeToggle(fadeDuration, function() {} );
			return false; 
		}
	);

	$('.spreadsheet_popup').click(
		function() {
			$(this).parent().children(".spreadsheet_content").fadeToggle(fadeDuration, function() {} );
			$('#fade').fadeToggle(fadeDuration, function() {} );
			return false; 
		}
	);

	$('.spreadsheet_popup_close').click(
		function() {
			$(this).parent().fadeToggle(fadeDuration, function() {} );
			$('#fade').fadeToggle(fadeDuration, function() {} );
			return false; 
		}
	);
});

function load_page() {
	window.location.href = document.URL;
}

function change_text() {
	var address = $('#host').text();
	var text 	= $('#active_text').text();
	var value 	= 0;

	if ( text == 'Legacy' ) {
		value = 1;		
	} else if ( text == 'Active' ) {
		value = 0;
	}

	jQuery('#div_active').load(address+'/session_write.php?active='+value);
}