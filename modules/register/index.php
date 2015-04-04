<?php
	include_once 	"properties.php";
	require_once	"recaptchalib.php";
	
	//***************HTML CODE**************
	draw_header($module, $GLOBALS[$module]['title'], $GLOBALS[$module]['title']." Form.");
    echo "<div id=\"body_wrapper\">";
        echo "<div id=\"body_content\">";
            echo "<div class=\"horizontal_separator\"></div>";
            echo "<div class=\"vertical_separator\"></div>";
            echo "<div id=\"primary_content\">";
				if ( $page_location == 0 ) {
					draw_register_form($module, $_POST);
				}

				if ( $page_location == 1 ) {
					draw_message_banner("Notice", "Invalid username. Please select another.");
					draw_register_form($module, $_POST);
				}

				if ( $page_location == 2 ) {
					draw_message_banner("Notice", "Please enter a valid email address.");
					draw_register_form($module, $_POST);
				}

				if ( $page_location == 3 ) {
					draw_message_banner("Notice", "Invalid Password");
					draw_register_form($module, $_POST);
				}

				if ( $page_location == 4 ) {
					draw_message_banner("Notice", "Please agree to the Terms of Service!");
					draw_register_form($module, $_POST);
				} 

				if ( $page_location == 5 ) {
					draw_message_banner("Notice", "Invalid Guild Details");
					draw_register_form($module, $_POST);
				} 

				if ( $page_location == 6 ) {
					register_user($_POST['register_username'], $_POST['register_email'], $_POST['register_passcode1']);

					$user_details 			= validate_user_pass_details($_POST['register_username'], $_POST['register_passcode1']);
					$_SESSION['user_id']	= $user_details['user_id'];
					$_SESSION['user']		= $user_details['username'];
					$_SESSION['email']		= $user_details['email'];
					$_SESSION['logged']		= true;
					$_SESSION['logging']	= false;

					if ( $valid_guild == 0 ) { insert_new_guild($_POST, $_FILES); }
					draw_message_banner("Success", "User Registration and successful!");
				} 

				echo "<div class='clear'></div>";
			echo "</div>";
            echo "<div class=\"vertical_separator\"></div>";
            echo "<div id=\"side_content\">";
				block_draw_site_stats();
				echo "<div class='clear'></div>";
				echo "<div class='horizontal_separator'></div>";
				if ( $GLOBALS['enable_ads']['block'] == 1 ) { block_uni_draw_box_ad(); }
				block_draw_newest_guilds();
				echo "<div class='clear'></div>";
				echo "<br>";
            echo "</div>";
            echo "<div class=\"vertical_separator\"></div>";
            echo "<div class=\"clear\"></div>";
        echo "</div>";
        echo "<div class=\"clear\"></div>";
    echo "</div>";

	draw_footer();
	draw_bottom();
	//***************HTML CODE**************

	mysql_close($dblink);
?>