<?php
	$ROOT 			= dirname(dirname(dirname(__FILE__)));
	include_once 	"{$ROOT}/configuration.php";

	$module = "register";
	if ( !isset($GLOBALS[$module]['set']) || $GLOBALS[$module]['set'] == 0 ) { draw_disabled_module(); exit; } 

	//***************DECLARING VARIABLES**************
	$_SESSION['logged']		= false;
	$_SESSION['logging']	= true;
	$page_location			= 0; // 0 - register.php
	//***************DECLARING VARIABLES**************
	
	if ( isset($_POST) && count($_POST) > 0 ) {
		if ( isset($_POST['form_register_submit']) ) {
			$agree_score 	= isset($_POST['agree']);
			$valid_user 	= validate_user($_POST['register_username']);
			$valid_email 	= validate_email($_POST['register_email']);
			$valid_pass 	= validate_password($_POST['register_passcode1'], $_POST['register_passcode2']);
			$valid_guild 	= validate_guild($_POST['name'], $_POST['server']);

			if ( $valid_user > 0 ) { 
				$page_location = 1; // username didn't pass
			} else if ( $valid_email == 0 ) { 
				$page_location = 2; // email didn't pass // 0 - Invalid 1 - Valid
			} else if ( $valid_pass != 1 ) { 
				$page_location = 3; // passcode didn't pass 0 - No Pass Present 1 - Valid 2 - Pass Do Not Match 3 - Pass Too Short
			} else if ( !isset($_POST['agree']) ) { 
				$page_location = 4; // agreement not clicked
			} else if ( $valid_guild > 0 ) { 
				$page_location = 5; // guild dis not pass
			} else {
				$page_location = 6;
			}
		} 
	}

	function draw_register_form($module, $register_details) {
		$register_fields = array();

		if ( isset($register_details) && count($register_details) > 0 ) {
			$register_fields['register_username']	= isset($register_details['register_username']) && strlen($register_details['register_username']) > 0 ? $register_details['register_username'] : "";
			$register_fields['register_email'] 		= isset($register_details['register_email']) && strlen($register_details['register_email']) > 0 ? $register_details['register_email'] : "";
		}

		if ( isset($register_details) && count($register_details) > 0 ) {
			$register_fields['name']		= isset($register_details['name']) && strlen($register_details['name']) > 0 ? $register_details['name'] : "";
			$register_fields['country']		= isset($register_details['country']) && strlen($register_details['country']) > 0 ? $register_details['country'] : "";
			$register_fields['faction']		= isset($register_details['faction']) && strlen($register_details['faction']) > 0 ? $register_details['faction'] : "";
			$register_fields['server']		= isset($register_details['server']) && strlen($register_details['server']) > 0 ? $register_details['server'] : "";
			$register_fields['website']		= isset($register_details['website']) && strlen($register_details['website']) > 0 ? $register_details['website'] : "";
			$register_fields['leader']		= isset($register_details['leader']) && strlen($register_details['leader']) > 0 ? $register_details['leader'] : "";
			$register_fields['facebook']	= isset($register_details['facebook']) && strlen($register_details['facebook']) > 0 ? $register_details['facebook'] : "";
			$register_fields['google']		= isset($register_details['google']) && strlen($register_details['google']) > 0 ? $register_details['google'] : "";
			$register_fields['twitter']		= isset($register_details['twitter']) && strlen($register_details['twitter']) > 0 ? $register_details['twitter'] : "";
		}

		echo "<div class='form_wrapper'>";
			generate_table_form('register', $GLOBALS['page_register'], $register_fields);
		echo "</div>";

		// Reserved for Faction Logo/Guild Logo Preview
		/*echo "<div class=\"vertical_separator\"></div>";
		echo "<div class='preview_image_pane'>";
			echo "<img id='preview_logo' src='{$GLOBALS['fold_guild_logos']}tmp/default.png' alt='preview' />";
			echo "<div class=\"horizontal_separator\"></div>";
			echo "<img id='preview_faction' src='{$GLOBALS['fold_guild_logos']}tmp/dominion_default.png' alt='preview' />";
		echo "</div>";*/
		echo "<div class=\"clear\"></div>";
		echo "<div class=\"horizontal_separator\"></div>";
		draw_terms_of_service();
	}

	function draw_terms_of_service() {
		$module = "tos";

		$query = mysql_query(sprintf("SELECT *
						FROM %s
						WHERE document_id = '%s'",
						mysql_real_escape_string($GLOBALS['table_document']),
						mysql_real_escape_string('terms_of_service')
						)) or die(draw_error_page());
		$terms_of_service = mysql_fetch_array($query);

		echo "<div class='primary_content_block'>";
			echo "<article class='content_article'>";
				echo "<div class='content_article_content'>";
					echo "<h1>{$GLOBALS[$module]['title']}</h1>";
					echo "<hr><div class='clear'></div>";
					echo $terms_of_service['content'];
				echo "</div>";
			echo "</article>";
		echo "</div>";
	}

	function validate_guild($name, $server) {
 		$check = 0;
 		
 		if ( strlen(trim($name)) > 0 ) {
	 		foreach( $GLOBALS['global_guild_array'] as $guild_id => $guild_details ) {
	 			if ( strtolower($name) == strtolower($guild_details['name']) && strtolower($server) && strtolower($guild_details['server']) ) { $check++; }
	 		}
	 	} else if ( strlen(trim($name)) == 0 ) {
	 		$check = -1;
	 	}
 			
		return $check;		
	}
?>