<?php
	include_once 	"properties.php";

	//***************HTML CODE**************
	draw_header($module, $GLOBALS[$module]['title'], "Contact ".$GLOBALS['site_title']." Site Administration.");

    echo "<div id=\"body_wrapper\">";
        echo "<div id=\"body_content\">";
            echo "<div class=\"horizontal_separator\"></div>";
            echo "<div class=\"vertical_separator\"></div>";
            echo "<div id=\"primary_content\">";
				if ( $page_location == 0 ) {
					draw_message_banner("Contact Us", "Have any issue or request for our website? Feel free to drop us a message here!");
					echo "<div class=\"horizontal_separator\"></div>";
					draw_report_form($module, $_POST);
				} else if ( $page_location == 1 ) {
					draw_message_banner("Thank You!", "Your feedback has been submitted! If inquiring a response, we will try and respond to your request as soon as possible!");
				} else if ( $page_location == 2 ) {
			    	draw_message_banner("Notice", "Please leave a message before sending feedback!");
			    	echo "<div class=\"horizontal_separator\"></div>";
			    	draw_report_form($module, $_POST);    	
				} else if ( $page_location == 3 ) {
			    	draw_message_banner("Notice", "Please tell us what kind of feedback you are leaving!");
			    	echo "<div class=\"horizontal_separator\"></div>";
			    	draw_report_form($module, $_POST);    	
				} else if ( $page_location == 4 ) {
			    	draw_message_banner("Notice", "Please enter a valid email!");
			    	echo "<div class=\"horizontal_separator\"></div>";
			    	draw_report_form($module, $_POST);    	
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

	mysql_close($link);
?>