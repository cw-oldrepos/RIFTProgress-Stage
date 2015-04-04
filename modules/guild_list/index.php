<?php
	include_once 	"properties.php";
	
	//***************HTML CODE**************
	draw_header($module, $GLOBALS[$module]['title'], $GLOBALS['game_name_1']." guild listing.");

	echo "<div id='body_wrapper'>";
		echo "<div id='body_content'>";
			echo "<div class='horizontal_separator'></div>";
			echo "<div class='vertical_separator'></div>";
			echo "<div id='primary_content'>";
				echo "<div id='update_guild_content' class='popup_content'>";
					echo "<div class='popup_title'>{$GLOBALS['quick']['title']}</div>";
					echo "<div class='popup_form'>";
						draw_submit_popup();
					echo "</div>";
				echo "</div>";

				if ( isset($_POST["form_update_guild_submit"]) ) {
					send_update_request($_POST);
				}

				draw_guild_listing($module);
			echo "</div>";
			echo "<div class='vertical_separator'></div>";
			echo "<div id='side_content'>";
				block_draw_details($module, $guild_stats);
				echo "<div class='clear'></div>";
				echo "<div class='horizontal_separator'></div>";
				block_draw_update($module);
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