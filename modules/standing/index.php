<?php
	include_once 	"properties.php";
	
	//***************HTML CODE**************
	draw_header($module, $header_text, $GLOBALS['game_name_1']." raiding guild standings.");

	echo "<div id='body_wrapper'>";
		echo "<div id='body_content'>";
			echo "<div class='horizontal_separator'></div>";
			echo "<div class='vertical_separator'></div>";
			echo "<div id='primary_content'>";
				if ( isset($GLOBALS['global_raid_size_array'][$raid_size])  ) {
					draw_message_banner_progress($tier_details, ucfirst($display_type), $raid_size."-Mans", $dungeon_details, $encounter_details);
				} else {
					draw_message_banner_progress($tier_details, ucfirst($display_type), "", $dungeon_details, $encounter_details);
				}

				if ( $standings_type == 0 ) draw_overall_standing_view($module, $guild_array, $display_type);
				if ( $standings_type == 1 ) draw_tier_standing_view($module, $guild_array, $tier_details, $display_type);
				if ( $standings_type == 2 ) draw_overall_size_standing_view($module, $guild_array, $raid_size, $display_type);
				if ( $standings_type == 3 ) draw_tier_size_standing_view($module, $guild_array, $tier_details, $raid_size, $display_type);
				if ( $standings_type == 4 ) draw_standing_dungeon_view($module, $guild_array, $dungeon_details, $display_type);
				if ( $standings_type == 5 ) draw_standing_encounter_view($module, $guild_array, $encounter_details, $display_type);
			echo "</div>";
			echo "<div class='vertical_separator'></div>";
			echo "<div id='side_content'>";
				if ( $standings_type == 5 ) {
					block_draw_encounter_details($module, $encounter_details, $dungeon_details);
					echo "<div class='clear'></div>";
					echo "<div class='horizontal_separator'></div>";
				}

				block_draw_views($module, $tier_details, $dungeon_details, $encounter_details);
				echo "<div class='clear'></div>";
				echo "<div class='horizontal_separator'></div>";
				if ( $GLOBALS['enable_ads']['block'] == 1 ) { block_uni_draw_box_ad(); }
				block_draw_global_glossary($module);
				echo "<div class='clear'></div>";
			echo "</div>";
			echo "<div class='vertical_separator'></div>";
			echo "<div class='clear'></div>";
		echo "</div>";
		echo "<div class='clear'></div>";
	echo "</div>";

	draw_footer();
	draw_bottom();
	//***************HTML CODE**************

	mysql_close($dblink);
?>