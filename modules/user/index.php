<?php
	include_once 	"properties.php";

	//***************HTML CODE**************
	draw_header($module, $GLOBALS[$module]['title'], "Control Panel");
	
    echo "<div id=\"body_wrapper\">";
        echo "<div id=\"body_content\">";
            echo "<div class=\"horizontal_separator\"></div>";
            echo "<div class=\"vertical_separator\"></div>";
            echo "<div id=\"primary_content\">";
				if ( $display_type == 0 || $display_type == 1 ) { // Display Account Edit Details
					draw_user_edit($module, $user_details);
				}

				if ( $display_type == 2 ) { //index
					update_user_details($user_details, $_POST);
					draw_user_edit($module, $user_details);
				} 

				if ( $display_type == 3 ) { // Submit Kill, Display Kills
					$guild_details = get_updated_guild_details($_POST['guild_id']);
					draw_progression_submission($module, $guild_details);
					echo "<div class=\"clear\"></div>";
					echo "<div class=\"horizontal_separator\"></div>";
					draw_progression_edit($module, $guild_details);
				}

 				if ( $display_type == 4 ) { // Add Kill, Submit Kill, Display Kills
					update_kill_details($_POST, $_FILES, "new");

					$guild_details = get_updated_guild_details($_POST['guild_id']);
					draw_progression_submission($module, $guild_details);
					echo "<div class=\"clear\"></div>";
					echo "<div class=\"horizontal_separator\"></div>";
					draw_progression_edit($module, $guild_details);
				}

				if ( $display_type == 5 ) { //edit encounter
					$guild_details = get_updated_guild_details($_POST['guild_id']);
					draw_submission_edit_encounter($module, $guild_details, $_POST['encounter_id']);
					echo "<div class=\"clear\"></div>";
					echo "<div class=\"horizontal_separator\"></div>";
					draw_progression_edit($module, $guild_details);
				} 

				if ( $display_type == 6 ) { //index
					remove_kill_details($_POST);
					update_kill_details($_POST, $_FILES, "new");

					$guild_details = get_updated_guild_details($_POST['guild_id']);
					draw_submission_edit_encounter($module, $guild_details, $_POST['encounter_id']);
					echo "<div class=\"clear\"></div>";
					echo "<div class=\"horizontal_separator\"></div>";
					draw_progression_edit($module, $guild_details);
				}

				if ( $display_type == 7 ) { //delete encounter
					$guild_details = get_updated_guild_details($_POST['guild_id']);
					draw_submission_delete_encounter($guild_details, $_POST['encounter_id']);
					echo "<div class=\"clear\"></div>";
					echo "<div class=\"horizontal_separator\"></div>";
					draw_progression_edit($module, $guild_details);
				} 

				if ( $display_type == 8 ) { //index
					remove_kill_details($_POST);
					draw_message_banner("Success", "Your kill shot has been removed successfully! Points & Rankings will be adjusted in the next update!");

					$guild_details = get_updated_guild_details($_POST['guild_id']);
					draw_progression_submission($module, $guild_details);
					echo "<div class=\"clear\"></div>";
					echo "<div class=\"horizontal_separator\"></div>";
					draw_progression_edit($module, $guild_details);
				} 

				if ( $display_type == 9 ) { // create guild
					draw_guild_create($module, $_POST);
				}

				if ( $display_type == 10 ) {
					insert_new_guild($_POST, $_FILES);
					refresh_guild_list();
					draw_message_banner("Success", "You have created your guild succcessfully!");

					$guild_details = get_updated_guild_details($_SESSION['guild_id']);
					draw_guild_edit($module, $guild_details);
					echo "<div class=\"clear\"></div>";
					echo "<div class=\"horizontal_separator\"></div>";
					draw_progression_edit($module, $guild_details);
				}

				if ( $display_type == 11 ) { // edit guild
					$guild_details = get_updated_guild_details($_POST['guild_id']);
					draw_guild_edit($module, $guild_details);
					echo "<div class=\"clear\"></div>";
					echo "<div class=\"horizontal_separator\"></div>";
					draw_progression_edit($module, $guild_details);
				}

				if ( $display_type == 12 ) { //index
					update_guild_details($_POST, $_FILES);

					$guild_details = get_updated_guild_details($_POST['guild_id']);
					draw_guild_edit($module, $guild_details);
					echo "<div class=\"clear\"></div>";
					echo "<div class=\"horizontal_separator\"></div>";
					draw_progression_edit($module, $guild_details);
				}

				if ( $display_type == 13 ) { // delete guild
					$guild_details = get_updated_guild_details($_POST['guild_id']);
					draw_guild_delete($module, $guild_details);
				}

				if ( $display_type == 14 ) {
					remove_guild_details($_POST);
					refresh_guild_list();

					draw_message_banner("Success", "You have successfully disbanded your guild!");
					draw_user_edit($module, $user_details);
				}

				if ( $display_type == 15 ) { // create guild
					$guild_details = get_updated_guild_details($_POST['guild_id']);
					draw_raid_team_create($module, $guild_details);
				}

				if ( $display_type == 16 ) {
					$guild_details = get_updated_guild_details($_POST['guild_id']);
					insert_new_raid_team($_POST, $guild_details);
					refresh_guild_list();
					draw_message_banner("Success", "You have successfully created a new raid team!");

					$guild_details = get_updated_guild_details($_SESSION['guild_id']);
					draw_guild_edit($module, $guild_details);
					echo "<div class=\"clear\"></div>";
					echo "<div class=\"horizontal_separator\"></div>";
					draw_progression_edit($module, $guild_details);
				}

                echo "<div class=\"clear\"></div>";
            echo "</div>";
            echo "<div class=\"vertical_separator\"></div>";
            echo "<div id=\"side_content\">";
				block_draw_account_table($module, $user_details);
				echo "<div class='clear'></div>";
				echo "<div class='horizontal_separator'></div>";
				if ( $GLOBALS['enable_ads']['block'] == 1 ) { block_uni_draw_box_ad(); }
				block_draw_guild_table($module, $user_details);
				echo "<div class='clear'></div>";
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