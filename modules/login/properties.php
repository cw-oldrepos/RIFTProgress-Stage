<?php
	$ROOT 			= dirname(dirname(dirname(__FILE__)));
	include_once 	"{$ROOT}/configuration.php";

	$module = "login";
	if ( !isset($GLOBALS[$module]['set']) || $GLOBALS[$module]['set'] == 0 ) { echo draw_disabled_module(); exit; }
	
	//***************DECLARING VARIABLES**************
	$_SESSION['logged']		= false;
	$_SESSION['logging']	= true;
	$user_details 			= array();
	$page_location			= 0;
	//***************DECLARING VARIABLES**************
	
	if ( isset($_POST['form_login_submit']) ) {
		$num_of_users = get_num_of_users($_POST['login_username'], $_POST['login_passcode']);
		
		if ( $num_of_users == 1 ) {
			$user_details = validate_user_pass_details($_POST['login_username'], $_POST['login_passcode']);

			$_SESSION['user_id']	= $user_details['user_id'];
			$_SESSION['user']		= $user_details['username'];
			$_SESSION['email']		= $user_details['email'];
			$_SESSION['logged']		= true;
			$_SESSION['logging']	= false;

			$page_location = 1;

			header("Location: ".$GLOBALS['page_user']);
			exit;
		} else {
			$page_location = 2;
		}		

		unset($_POST['form_login_submit']);
	} else {
		if ( $_SESSION['logged'] ) { // User is logged in already
			$page_location = 1;
		} else if ( $_SESSION['logging'] && !(isset($_POST['userlogin'])) ) { // User is now attempting to log in
			$page_location = 0;
		} else if ( isset($_POST['userlogin']) && isset($_POST['passlogin']) && $_SESSION['logging'] ) {
			$num_of_users = find_user($_POST['userlogin'], $_POST['passlogin']);

			if ( $num_of_users == 1 ) {
				$user_info = get_user_info($_POST['userlogin'], $_POST['passlogin']);

				$_SESSION['user_id']	= $user_info['user_id'];
				$_SESSION['user']		= $user_info['username'];
				$_SESSION['email']		= $user_details['email'];
				$_SESSION['logged']		= true;
				$_SESSION['logging']	= false;

				$page_location = 1;

				header("Location: ".$CONFIG['page_user']);
				exit;
			} else {
				$page_location = 2;
			}
		}
	}

	function draw_login() {
		echo "<div class='form_wrapper'>";
			generate_table_form('login', $GLOBALS['page_login'], "");
		echo "</div>";
	}
?>