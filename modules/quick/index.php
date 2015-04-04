<?php
	include_once 	"properties.php";

	//***************HTML CODE**************
	draw_header($module, $GLOBALS[$module]['title'], $GLOBALS[$module]['title']);
    echo "<div id=\"body_wrapper\">";
        echo "<div id=\"body_content\">";
            echo "<div class=\"horizontal_separator\"></div>";
            echo "<div class=\"vertical_separator\"></div>";
            echo "<div id=\"primary_content\">";
				if ( isset($_POST["form_encounter_popup_add_submit"]) ) {
					update_kill_details($_POST, $_FILES, "new");
				}

                echo "<div class=\"clear\"></div>";
            echo "</div>";
            echo "<div class=\"vertical_separator\"></div>";
            echo "<div id=\"side_content\">";
                block_draw_site_stats();
                echo "<div class='clear'></div>";
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