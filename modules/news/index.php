<?php
	include_once 	"properties.php";

	//***************HTML CODE**************
	if ( $multi_articles == 1 ) {
		draw_header($module, $GLOBALS[$module]['page_title'], $GLOBALS[$module]['description']);
	} else if ( $multi_articles == 0 ) {
		draw_header($module, $news_array[0]['title'], strip_tags($news_array[0]['content']));
	}

	echo "<div id='body_wrapper'>";
		echo "<div id='body_content'>";
			echo "<div class='horizontal_separator'></div>";
			echo "<div class='vertical_separator'></div>";
			echo "<div id='primary_content'>";
				echo $GLOBALS['images']['welcome'];
				echo "<div class='horizontal_separator'></div>";
				draw_primary_rankings($module, $guild_array, $GLOBALS[$module]['display_standing']);
				echo "<div class='horizontal_separator'></div>";
				draw_content_articles($module, $news_array);
				echo "<div class='clear'></div>";
			echo "</div>";
			echo "<div class='vertical_separator'></div>";
			echo "<div id='side_content'>";
				block_draw_top_rankings($module, $temp_guild_array, $GLOBALS['point_system_default']);
				echo "<div class='clear'></div>";
				echo "<div class='horizontal_separator'></div>";
				if ( $GLOBALS['enable_ads']['block'] == 1 ) { block_uni_draw_box_ad(); }

				block_draw_recent_raids($module, $recent_data_array, $temp_guild_array);
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