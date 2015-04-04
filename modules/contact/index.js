function pageLoad() {}

$(document).ready(function(){
	var form 		= $("#form_report");
	var fname		= $("#textbox_fname");
	var lname		= $("#textbox_lname");
	var email 		= $("#textbox_email");
	var message 	= $("#textarea_message");

	fname.keyup(validateFName);
	lname.keyup(validateLName);
	email.keyup(validateEmail);
	message.keyup(validateMessage);

	function validateMessage() {
		if ( message.val().length < 10 ) {
			message.addClass("error");
			return false;
		} else {			
			message.removeClass("error");
			return true;
		}
	}

	function validateFName() {
		if ( fname.val().length < 4 ) {
			fname.addClass("error");
			return false;
		} else {
			fname.removeClass("error");
			return true;
		}
	}

	function validateLName() {
		if ( lname.val().length < 4 ) {
			lname.addClass("error");
			return false;
		} else {
			lname.removeClass("error");
			return true;
		}
	}

	function validateEmail() {
		var a 		= $("#textbox_email").val();
		var filter 	= /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;

		if ( filter.test(a) ) {
			email.removeClass("error");
			return true;
		} else {
			email.addClass("error");
			return false;
		}
	}

	form.submit(function(){
		if ( validateFName() && validateLName() && validateEmail() && validateMessage() )
			return true
		else
			return false;
	});
});