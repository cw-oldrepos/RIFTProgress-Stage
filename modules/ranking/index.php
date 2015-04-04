<?php
	include_once 	"properties.php";

	//***************HTML CODE************** 
	draw_header($module, $header_text, "Guild Rankings for {$GLOBALS['game_name_1']}'s fastest raid dungeon clears.");

	echo "<div id='body_wrapper'>";
		echo "<div id='body_content'>";
			echo "<div class='horizontal_separator'></div>";
			echo "<div class='vertical_separator'></div>";
			echo "<div id='primary_content'>";
				if ( isset($_GET['type']) ) {
					draw_message_banner_progress($tier_details, "", $_GET['type']."-Man ($point_system_title)", $dungeon_details, "");
				} else {
					draw_message_banner_progress($tier_details, "", "($point_system_title)", $dungeon_details, "");
				}

				draw_ranking_view($module, $active_point_system, $guild_array, $display_type);
				
				echo "<div class='clear'></div>";				
			echo "</div>";
			echo "<div class='vertical_separator'></div>";
			echo "<div id='side_content'>";
				if ( isset($raid_size) && isset($GLOBALS['global_raid_size_array'][$raid_size])) {
					if ( isset($tier_details) && $tier_details == "alltime" ) {
						block_draw_trends($module, $display_type, $active_point_system, "alltime", $dungeon_details, $guild_array, $raid_size);
					} else {
						block_draw_trends($module, $display_type, $active_point_system, $tier_details['tier'], $dungeon_details, $guild_array, $raid_size);
					}
				} else {
					if ( isset($tier_details) && $tier_details == "alltime" ) {
						block_draw_trends($module, $display_type, $active_point_system, "alltime", $dungeon_details, $guild_array, "");
					} else {
						block_draw_trends($module, $display_type, $active_point_system, $tier_details['tier'], $dungeon_details, $guild_array, "");
					}
				}

				echo "<div class='clear'></div>";
				echo "<div class='horizontal_separator'></div>";
				if ( $GLOBALS['enable_ads']['block'] == 1 ) { block_uni_draw_box_ad(); }
				block_point_systems($module, $tier_details, $dungeon_details, $display_type);
				echo "<div class='clear'></div>";
				echo "<div class='horizontal_separator'></div>";
				block_draw_views($module, $tier_details, $dungeon_details, $arg_poll);
				echo "<div class='clear'></div>";
				echo "<div class='horizontal_separator'></div>";
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