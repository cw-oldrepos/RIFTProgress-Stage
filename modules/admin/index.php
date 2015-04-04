<?php
	include_once 	"properties.php";

	//***************START-HTML CODE**************
	draw_header("admin", "Admin Panel", "");
	
	/*echo "<div id='body_wrapper'>";
		echo "<div id='body_content'>";
			echo "<div class='content_separator'><br></div>";
			echo "<div id='content_center'>";
				if ( isset($_SESSION['admin']) && $_SESSION['admin'] == "1"){
					draw_forms($GLOBALS['global_encounter_array'], $GLOBALS['global_guild_array'], $GLOBALS['global_dungeon_array'], $GLOBALS['global_tier_array'], $GLOBALS['global_server_array'], $GLOBALS['global_country_array'], $GLOBALS['faction']);
				} else {
					if ( $admin_set == 2 ) {
						draw_message_banner("Notice", "Invalid Admin User/Pass combo!");
					}

					echo "<div style='width:100%;'>";
						echo "<form id='loginform2' action='".$GLOBALS['page_admin']."' method='POST'>";
							echo "<table class='table_form'>";
								echo "<thead>";
									echo "<tr>";
										echo "<th colspan='2'>Administrator Login</th>";
									echo "</tr>";
								echo "</thead>";
								echo "<tbody>";
									echo "<tr>";
										echo "<th>Username</th>";
										echo "<td><input class='textbox_default' type='text' name='admin_name'></td>";
									echo "</tr>";
									echo "<tr>";
										echo "<th>Password</th>";
										echo "<td><input class='textbox_default' type='password' name='admin_pass'></td>";
									echo "</tr>";
									echo "<tr>";
										echo "<th colspan='2' style='text-align:center;'>";
											echo "<input class='data_button' type='submit' name='admin_login' value='Submit'>";
										echo "</th>";
									echo "</tr>";
								echo "</tbody>";
							echo "</table>";
						echo "</form>";
					echo "</div>";		
				}

			echo "<div class='clear'></div>";
				echo "<div class='clear'></div>";
			echo "</div>";
			echo "<div class='content_separator'><br></div>";
			echo "<div class='content_sidebars'>";
				block_uni_draw_large_box_ad();
				echo "<div class='clear'></div>";
				echo "<br>";
				block_uni_draw_large_box_ad();
				echo "<div class='clear'></div>";
			echo "</div>";
			echo "<div class='content_separator'><br></div>";
		echo "</div>";
		echo "<div class='clear'></div>";
	echo "<br></div>";*/

	echo "<div id='body_wrapper'>";
		echo "<div id='body_content'>";
			echo "<div class='horizontal_separator'></div>";
			echo "<div class='vertical_separator'></div>";
			echo "<div id='primary_content'>";
				if ( isset($_SESSION['admin']) && $_SESSION['admin'] == "1"){
					draw_forms();
				} else {
					if ( $admin_set == 2 ) {
						draw_message_banner("Notice", "Invalid Admin User/Pass combo!");
					}

					echo "<div class='form_wrapper'>";
						echo "<form id='loginform2' action='{$GLOBALS['page_admin']}' method='POST'>";
							echo "<table class='table_data form'>";
								echo "<thead>";
									echo "<tr>";
										echo "<th colspan='2'>Administrator Login</th>";
									echo "</tr>";
								echo "</thead>";
								echo "<tbody>";
									echo "<tr>";
										echo "<th>Username</th>";
										echo "<td><input type='text' name='admin_name'></td>";
									echo "</tr>";
									echo "<tr>";
										echo "<th>Password</th>";
										echo "<td><input type='password' name='admin_pass'></td>";
									echo "</tr>";
									echo "<tr>";
										echo "<th colspan='2' style='text-align:center;'>";
											echo "<input type='submit' name='admin_login' value='Submit'>";
										echo "</th>";
									echo "</tr>";
								echo "</tbody>";
							echo "</table>";
						echo "</form>";
					echo "</div>";		
				}

				echo "<div class='clear'></div>";
			echo "</div>";
			echo "<div class='vertical_separator'></div>";
			echo "<div id='side_content'>";
				block_uni_draw_box_ad();
				echo "<div class='clear'></div>";
			echo "</div>";
			echo "<div class='vertical_separator'></div>";
			echo "<div class='clear'></div>";
		echo "</div>";
		echo "<div class='clear'></div>";
	echo "</div>";

	draw_footer();
	draw_bottom();
	//***************START-HTML CODE**************

	mysql_close($dblink);
?>
