<?php
	include_once 	"properties.php";

	//***************HTML CODE**************
	draw_header($module, $header_text, $header_description);

	echo "<div id='body_wrapper'>";
		echo "<div id='body_content'>";
			echo "<div class='horizontal_separator'></div>";
			echo "<div class='vertical_separator'></div>";
			echo "<div id='primary_content'>";
				if ( count($guild_array) == 0 ) {
					draw_message_banner("Guild Not Found", "The guild you have requested is no longer active or does not exist!");
				} else if ( count($guild_array) == 1 ) {
					echo "<span id='guild_name' style='display:none;'>".$guild_details['name']."</span>";
					draw_guild_navigation($module, $guild_details);
					echo "<div class='clear'></div>";
					echo "<div class='horizontal_separator'></div>";
					draw_guild_details($module, $guild_details);
					echo "<div class='clear'></div>";
					echo "<div class='horizontal_separator'></div>";
					//draw_guild_rankings($module, $guild_details);
					//echo "<div class='clear'></div>";
					//echo "<div class='horizontal_separator'></div>";
					//draw_guild_latest($module, $guild_details);
					//echo "<div class='clear'></div>";
					//echo "<div class='horizontal_separator'></div>";
					draw_guild_progression($module, $guild_details);
					echo "<div class='clear'></div>";
					draw_guild_history($module, $guild_details);
					echo "<div class='clear'></div>";
					echo "<div class='horizontal_separator'></div>";
					//draw_guild_streams($module, $guild_details);
					//echo "<div class='clear'></div>";
					draw_guild_widget($module, $guild_details);
				} else if ( count($guild_array) > 1 ) {
					draw_guild_select($guild_array);
				}
				echo "<div class='clear'></div>";
			echo "</div>";
			echo "<div class='vertical_separator'></div>";
			echo "<div id='side_content'>";
				block_draw_recent_activity($module, $activity_array);
				echo "<div class='clear'></div>";
				echo "<div class='horizontal_separator'></div>";
				if ( $GLOBALS['enable_ads']['block'] == 1 ) { block_uni_draw_box_ad(); }
				block_draw_ranking_details($module, $guild_details);
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