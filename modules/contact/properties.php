<?php
	$ROOT 			= dirname(dirname(dirname(__FILE__)));
	include_once 	"{$ROOT}/configuration.php";

	$module = "contact";
	if ( !isset($GLOBALS[$module]['set']) || $GLOBALS[$module]['set'] == 0 ) { draw_disabled_module(); exit; } 

	//***************DECLARING VARIABLES**************
	$page_location = 0;
	//***************DECLARING VARIABLES**************

	if ( isset($_POST['form_contact_submit']) ) {
		if ( !isset($_POST['contact']) || trim($_POST['contact']) == "-" || trim($_POST['contact']) == "" || strlen(trim($_POST['contact'])) == 0 ) { 
			$page_location = 3; 
		} else if ( !isset($_POST['email']) || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) || validate_email($_POST['email']) == 0 ) {
			$page_location = 4; 
		} else if ( validate_message($_POST['message']) ) {
			$email_headers 	= "From: {$GLOBALS['email_admin']}\r\n";
			$email_headers .= "Reply-To: \r\n";
			$email_headers .= "Return-Path: \r\n";
			$email_headers .= "CC: \r\n";
			$email_headers .= "BCC: \r\n";
			$email_subject 	= "{$GLOBALS['site_title_short']}-{$_POST['contact']}";
			$email_address 	= $GLOBALS['email_admin'];
			$email_message 	= "Dear Site Administrator,\n\n{$_POST['email']} has some feedback for you!\n\n{$_POST['message']}";
			mail($email_address, $email_subject, $email_message, $email_headers);
			$page_location = 1;
		} else {
			$page_location = 2;
		}
	}

	function draw_report_form($module) {
		echo "<div class='form_wrapper'>";
			generate_table_form($module, $GLOBALS['page_contact'], "");
		echo "</div>";
	}

	function validate_message($message) {
		if ( strlen($message) < 10 ) { //if it's NOT valid
			return false;
		} else {
			return true; //if it's valid
		}	
	}
?>