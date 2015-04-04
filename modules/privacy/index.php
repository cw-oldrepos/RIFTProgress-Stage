<?php
	include_once 	"properties.php";

	//***************HTML CODE**************
	draw_header($module, $GLOBALS[$module]['title'], $GLOBALS['game_name_1']." Progress ".$GLOBALS[$module]['title']." Document.");
	
	echo "<div id='body_wrapper'>";
		echo "<div id='body_content'>";
			echo "<div class='horizontal_separator'></div>";
			echo "<div class='vertical_separator'></div>";
			echo "<div id='primary_content'>";
				draw_privacy_policy($module);
				echo "<div class='clear'></div>";
			echo "</div>";
			echo "<div class='vertical_separator'></div>";
			echo "<div id='side_content'>";
				block_draw_site_stats();
				echo "<div class='clear'></div>";
				echo "<div class='horizontal_separator'></div>";
				if ( $GLOBALS['enable_ads']['block'] == 1 ) { block_uni_draw_box_ad(); }
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