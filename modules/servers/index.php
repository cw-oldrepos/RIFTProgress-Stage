<?php
	include_once 	"properties.php";

	//***************HTML CODE**************
	draw_header($module, "$server_page Server Progression - $header_text", "$server_page Server Raid Progress Tracking.");
	
	echo "<div id='body_wrapper'>";
		echo "<div id='body_content'>";
			echo "<div class='horizontal_separator'></div>";
			echo "<div class='vertical_separator'></div>";
			echo "<div id='primary_content'>";
				draw_message_banner_progress($tier_details, "", "", "", "", 0);
				echo "<div class='horizontal_separator'></div>";
				draw_server_standings($module, $tier_details, $guild_array);
				echo "<div class='clear'></div>";				
			echo "</div>";
			echo "<div class='vertical_separator'></div>";
			echo "<div id='side_content'>";
				block_draw_server_details($module, $server_details);
				echo "<div class='clear'></div>";
				echo "<div class='horizontal_separator'></div>";
				if ( $GLOBALS['enable_ads']['block'] == 1 ) { block_uni_draw_box_ad(); }
				block_draw_server_progress($module, $tier_details, $server_details, $guild_array);
				echo "<div class='clear'></div>";
				echo "<div class='horizontal_separator'></div>";
				block_draw_tier_selection($module, $GLOBALS['global_tier_array'], strtolower($server_details['name']));
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