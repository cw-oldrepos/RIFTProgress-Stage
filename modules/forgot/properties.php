<?php
	$ROOT 			= dirname(dirname(dirname(__FILE__)));
	include_once 	"{$ROOT}/configuration.php";
	
	$module = "forgot";
	if ( !isset($GLOBALS[$module]['set']) || $GLOBALS[$module]['set'] == 0 ) { draw_disabled_module(); exit; } 

	//***************DECLARING VARIABLES**************
	$page_location = 0;
	//***************DECLARING VARIABLES**************

	if ( isset($_SESSION) && isset($_SESSION['user']) ) {
		$page_location = 1;
	} else if ( isset($_GET['id']) && isset($_GET['regkey']) ) {
		$found_user = 0;
		$found_user = search_for_user_confirm($_GET['id'], $_GET['regkey']);

		if ( $found_user == 0 ) {
			$page_location = 5;
		} else if ( $found_user == 1 ) {
			$page_location = 6;
		}
	} else {
		if ( isset($_POST) && isset($_POST['form_forgot_submit']) ) {
			$score_validation = validate_email($_POST['email']);

			if ( $score_validation == 0 ) {
				$page_location = 2;
			} else if ( $score_validation == 1 ) {
				$found_user = search_for_user_by_email($_POST['email']);

				if ( $found_user == 0 ) { $page_location = 3; } 
				if ( $found_user == 1 ) { $page_location = 4; }
			}
		} else if ( isset($_POST) && isset($_POST['form_reset_submit']) ) {
			if ( validate_password($_POST['reset_passcode1'], $_POST['reset_passcode2']) == 1 ) {
				$page_location = 7;
			} else {
				$page_location = 8;
			}
		}
	}

	function draw_forgot_form() {
		echo "<div class='form_wrapper'>";
			generate_table_form('forgot', $GLOBALS['page_forgot'], "");
		echo "</div>";
	}

	function draw_reset_form($user_id, $register_key) {
		$form_details['user_id'] 		= $user_id;
		$form_details['confirmcode'] 	= $register_key;

		echo "<div class='form_wrapper'>";
			generate_table_form('reset', $GLOBALS['page_forgot'], $form_details);
		echo "</div>";
	}

	function send_reset_email($email) {
		$user_details = get_user_details_by_email($email);

		$email_headers 	= "From: {$GLOBALS['email_admin']}\r\n";
		$email_headers .= "Reply-To: {$GLOBALS['email_admin']}\r\n";
		$email_headers .= "Return-Path: \r\n";
		$email_headers .= "CC: \r\n";
		$email_headers .= "BCC: \r\n";
		$email_subject 	= "Password Recovery";
		$confirm_code 	= md5(uniqid(rand(), true));

		$user_id 		= $user_details['user_id'];
		$email_address 	= $user_details['email'];
		//$email_link 	= "$GLOBALS{['host_name']}/modules/forgot/forgot.php?id=$user_id&regkey=$confirm_code";
		$email_link 	= "{$GLOBALS['host_name']}{$GLOBALS['page_forgot']}$user_id/$confirm_code";
		$email_message 	= "Dear Registered User,\n\nIn order to reset your password, please click the following link to complete your password recovery process!\n\n$email_link";
		
		set_confirm_code($user_details['user_id'], $confirm_code);
		mail($email_address, $email_subject, $email_message, $email_headers);		
	}
?>