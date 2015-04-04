<?php
	include_once	"properties.php";

	//***************HTML CODE**************
	draw_header($module, $GLOBALS[$module]['title'],  "{$GLOBALS['site_title']} Login.");

    echo "<div id=\"body_wrapper\">";
        echo "<div id=\"body_content\">";
            echo "<div class=\"horizontal_separator\"></div>";
            echo "<div class=\"vertical_separator\"></div>";
            echo "<div id=\"primary_content\">";
				if ( $page_location == 0 ) {
					draw_login();
				} else if ( $page_location ==  1 ) {

				} else if ( $page_location ==  2 ) {
					draw_message_banner("Login Error", "Invalid Username/Password Login. Please try again!");
					
                    draw_login();
				}
                echo "<div class=\"clear\"></div>";
            echo "</div>";
            echo "<div class=\"vertical_separator\"></div>";
            echo "<div id=\"side_content\">";
				block_draw_site_stats();
				echo "<div class=\"clear\"></div>";
                echo "<div class=\"horizontal_separator\"></div>";              
                block_uni_draw_box_ad();
                echo "<div class=\"clear\"></div>";
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