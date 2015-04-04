<?php
	include_once 	"properties.php";

	log_entry(0, "Starting Weekly Raid Report...");

	$lastweek_array 	= array();
	$current_day 		= strtotime("now");
	$str_current_day 	= date('m/d/Y', $current_day);
	$last_day 			= strtotime("-7 days", $current_day);
	$str_last_day 		= date('m/d/Y', $last_day);
	$week_number  		= (int)date('W', $last_day);
	$dbdate 			= date("Y-m-d", $current_day)." 00:00:00";

	foreach ( $GLOBALS['global_guild_array'] as $guild_id  => $guild_details ) {
		$GLOBALS['global_guild_array'][$guild_id] = get_guild_details($guild_id);
	}

	foreach ( $GLOBALS['global_tier_array'] as $tier  => $tier_details ) {
		foreach ( $GLOBALS['global_dungeon_array'] as $dungeon_id  => $dungeon_details ) {
			if ( $dungeon_details['tier'] != $tier ) { continue; }

			foreach ( $GLOBALS['global_encounter_array'] as $encounter_id  => $encounter_details ) {
				if ( $encounter_details['dungeon_id'] != $dungeon_id ) { continue; }

				foreach ( $GLOBALS['global_guild_array'] as $guild_id  => $guild_details ) {
					//$guild_details = get_guild_details($guild_id);

					$GLOBALS['global_guild_array'][$guild_id] = $guild_details;

					if ( !isset($guild_details['direct_encounter_details'][$encounter_id]) ) { continue; }

					if ( $guild_details['direct_encounter_details'][$encounter_id]['strtotime'] >= $last_day && $guild_details['direct_encounter_details'][$encounter_id]['strtotime'] <= $current_day ) {
						$lastweek_array[$encounter_id][$guild_id] = $guild_details['direct_encounter_details'][$encounter_id]['strtotime']; 
					}
				}				
			}
		}
	}

	krsort($lastweek_array);

	foreach ( $lastweek_array as $encounter_id => $guild_id_array ) {
		asort($guild_id_array);
		$lastweek_array[$encounter_id] = $guild_id_array;
	}

	$news_details 				= array();
	$news_details['content'] 	= "";
	$news_details['title'] 		= "Raiding Report: $str_last_day - $str_current_day";
	$news_details['editor'] 	= "News Bot";
	$news_details['type']		= 0;

	foreach ( $GLOBALS['global_raid_size_array'] as $size => $raid_size ) {
		foreach ( $lastweek_array as $encounter_id => $guild_id_array ) {
			$encounter_details 		= $GLOBALS['global_encounter_array'][$encounter_id];

			if ( $encounter_details['players'] != $raid_size ) { continue; }

			$dungeon_details 		= $GLOBALS['global_dungeon_array'][$encounter_details['dungeon_id']];
			$hyperlink_encounter	= generate_hyperlink_standing($encounter_details['name'], "encounter", "world", $encounter_details, $dungeon_details, 0, "", "");

			$news_details['content'] .= "<h3>{$hyperlink_encounter} (".count($guild_id_array).")</h3><br>";
			$news_details['content'] .= "<ul>";

				foreach ( $guild_id_array as $guild_id => $time ) {
					$guild_details 	= generate_table_fields($GLOBALS['global_guild_array'][$guild_id], "");
					
					$news_details['content'] .= "<li>{$guild_details['name']} @ {$guild_details['direct_encounter_details'][$encounter_id]['datetime']}</li>";
				}

			$news_details['content'] .= "</ul>";
			$news_details['content'] .= "<br>";
		}
	}

	draw_header("", "", "");

	echo "<div id='body_wrapper'>";
		echo "<div id='body_content'>";
			echo "<div class='horizontal_separator'></div>";
			echo "<div class='vertical_separator'></div>";
			echo "<div id='primary_content'>";
				echo "<article class='content_article'>";
					echo "<div class='content_article_content'>";
						echo "<div class='news_type_container'>";
							if ( $news_details['type'] == 0 ) { echo $GLOBALS['images']['icon_news']; }
							if ( $news_details['type'] == 1 ) { echo $GLOBALS['images']['icon_patch']; }
						echo "</div>";
						echo "<div class='news_header_container'>";
							echo "<h1>{$news_details['title']}</h1>";
							//echo "<span>Posted by: {$news_details['editor']} </span>";
						echo "</div>";
						echo "<hr><div class='clear'></div>";
						echo "<p>{$news_details['content']}</p>";
					echo "</div>";
				echo "</article>";
				echo "<div class='clear'></div>";
			echo "</div>";
			echo "<div class='vertical_separator'></div>";
			echo "<div id='side_content'>";

				echo "<div class='clear'></div>";
			echo "</div>";
			echo "<div class='vertical_separator'></div>";
			echo "<div class='clear'></div>";
		echo "</div>";
		echo "<div class='clear'></div>";
	echo "</div>";

	if ( count($lastweek_array) > 0 ) {
		$query = mysql_query(sprintf(
			"INSERT INTO %s
			 (title, content, added_by, published, type, date_added)
			 values('%s','%s','%s','%s','%s', '%s')",
			 mysql_real_escape_string($GLOBALS['table_news']),
			 mysql_real_escape_string($news_details['title']),
			 mysql_real_escape_string($news_details['content']),
			 mysql_real_escape_string($news_details['editor']),
			 mysql_real_escape_string("1"),
			 mysql_real_escape_string($news_details['type']),
			 mysql_real_escape_string($dbdate)
			 )) or die(log_entry(3, mysql_error()));

		log_entry(0, "Posting to Google+.");
		create_post_google($news_details['title'], 1);

		log_entry(0, "Posting to Facebook.");
		create_post_facebook($news_details['title'], 1);

		log_entry(0, "Posting to Twitter.");
		create_post_twitter($news_details['title'], 1);
	}

	log_entry(0, "Weekly Raid Report Script Complete!");
?>
