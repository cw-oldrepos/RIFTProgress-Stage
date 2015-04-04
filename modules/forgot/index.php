<?php
	include_once 	"properties.php";
	
	//***************HTML CODE**************
	draw_header($module, $GLOBALS[$module]['title'], "Retrieve your account password.");

    echo "<div id=\"body_wrapper\">";
        echo "<div id=\"body_content\">";
            echo "<div class=\"horizontal_separator\"></div>";
            echo "<div class=\"vertical_separator\"></div>";
            echo "<div id=\"primary_content\">";
				if ( $page_location == 0 ) {
					draw_forgot_form();
				} else if ( $page_location == 1 ) {
					header("Location: {$GLOBALS['page_index']}");
					exit;
				} else if ( $page_location == 2 ) {
					draw_message_banner("Notice", "Please enter a valid email address.");
					draw_forgot_form();
				} else if ( $page_location == 3 ) {
					draw_message_banner("Notice", "Email address does not exist. Please enter another.");
					draw_forgot_form();
				} else if ( $page_location == 4 ) {
					draw_message_banner("Success", "Reset password email has been sent.");
					send_reset_email($_POST['email']);
				} else if ( $page_location == 5 ) {
					draw_message_banner("Notice", "Invalid registration key. Please try again.");
					draw_forgot_form();
				} else if ( $page_location == 6 ) {
					draw_reset_form($_GET['id'], $_GET['regkey']);
				} else if ( $page_location == 7 ) {
					$success_validation = reset_password($_POST['user_id'], $_POST['reset_passcode1']);

					if ( $success_validation == 0 ) {
						draw_message_banner("Error", "An unknown error occured. Please restart the forgot password process again.");
					} else if ( $success_validation == 1 ) {
						draw_message_banner("Success", "Password has been reset successfully!");
					}
				} else if ( $page_location == 8 ) {
					draw_message_banner("Notice", "Invalid passwords. Please try again.");
					draw_reset_form($_POST['user_id'], $_POST['confirmcode']);
				}
                echo "<div class=\"clear\"></div>";
            echo "</div>";
            echo "<div class=\"vertical_separator\"></div>";
            echo "<div id=\"side_content\">";
				block_draw_site_stats();
				echo "<div class='clear'></div>";
				echo "<div class='horizontal_separator'></div>";
				if ( $GLOBALS['enable_ads']['block'] == 1 ) { block_uni_draw_box_ad(); }
            echo "</div>";
            echo "<div class=\"vertical_separator\"></div>";
            echo "<div class=\"clear\"></div>";
        echo "</div>";
        echo "<div class=\"clear\"></div>";
    echo "</div>";

	draw_footer();
	draw_bottom();
	//***************HTML CODE**************

	mysql_close($link);
?>