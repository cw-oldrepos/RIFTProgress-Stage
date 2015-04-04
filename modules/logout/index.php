<?php
	include_once	"properties.php";

	//***************HTML CODE**************
	draw_header($module, $GLOBALS[$module]['title'],  $GLOBALS['site_title']." Login.");

	echo "<div id='body_wrapper'>";
		echo "<div id='body_content'>";
			echo "<div class='horizontal_separator'></div>";
			echo "<div class='vertical_separator'></div>";
			echo "<div id='primary_content'>";
				if ( !(isset($_SESSION['user']) && isset($_SESSION['userID']) ) ) {
					echo "<div class=\"banner\" style=\"background-color:#000000;\">";
						echo "<div class=\"banner_header\">Logout</div><br>";
						echo "<div class=\"banner_text\">You have successfully been logged out!</div><br>";
					echo "</div><br>";
				}
				echo "<div class='clear'></div>";
			echo "</div>";
			echo "<div class='vertical_separator'></div>";
			echo "<div id='side_content'>";
				block_draw_site_stats();
				echo "<div class='clear'></div>";
				echo "<div class='horizontal_separator'></div>";
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
	//***************HTML CODE**************

	mysql_close($dblink);
?>