<?php
	$ROOT 			= dirname(dirname(dirname(__FILE__)));
	include_once 	"{$ROOT}/configuration.php";

	$module = "news";
	if ( !isset($GLOBALS[$module]['set']) || $GLOBALS[$module]['set'] == 0 ) { draw_disabled_module(); exit; } 

	//***************DECLARING VARIABLES**************
	$news_array			= array();
	$guild_array 		= array();
	$recent_data_array 	= array();
	$temp_array 		= array();
	$temp_guild_array 	= array();
	//***************DECLARING VARIABLES**************
	$multi_articles = 1;
	
	$query = get_news_articles($GLOBALS[$module]['limit_news']);
	while ($row = mysql_fetch_array($query)) { 
		$row['date_added'] = format_date($row['date_added'], 'm-d-Y H:i');
		array_push($news_array, $row); 
	}

	if ( isset($_GET['article']) ) {
		$article_title = $_GET['article'];

		$article_title = strtolower(str_replace("_"," ", $article_title)); 
		$article_title = strtolower(str_replace("poundsign","#", $article_title)); 

		$query = get_specific_news_article($article_title);

		if ( mysql_num_rows($query) > 0 ) {
			$news_array = array();

			while ($row = mysql_fetch_array($query)) { 
				$row['date_added'] = format_date($row['date_added'], 'm-d-Y H:i');
				array_push($news_array, $row); 
			}	

			$multi_articles = 0;
		}	
	}

	foreach ( $GLOBALS['global_guild_array'] as $guild_id => $guild_details ) {
		$guild_details = get_guild_details($guild_id);
		$temp_guild_array[$guild_id] = $guild_details;

		foreach ( $guild_details['direct_encounter_details'] as $encounter_id => $encounter_details ) {
			$temp_array["{$guild_id}|{$encounter_id}"] = $encounter_details['strtotime'];	
		}
	}

	arsort($temp_array);
	foreach ( $temp_array as $key => $timestamp ) {
		$id_array = explode("|", $key);
		$guild_id = $id_array[0];

		array_push($recent_data_array, $key);
		if ( count($recent_data_array) == $GLOBALS[$module]['limit_recent'] ) break;
	}

	if ( $GLOBALS[$module]['display_standing'] == 0 ) {
		foreach ( $GLOBALS['global_tier_array'] as $tier => $tier_details ) {
			if ( count($guild_array) == 2 ) break;

			$guild_array[$tier] = get_tier_standings($module, $temp_guild_array, $GLOBALS['global_tier_array'][$tier]);
		}
	} else if ( $GLOBALS[$module]['display_standing'] == 1 ) {
		foreach ( $GLOBALS['raid_size'] as $raid_size => $size ) {
			if ( count($guild_array) == 2 ) break;

			$guild_array[$raid_size] = get_tier_size_standings($module, $temp_guild_array, $GLOBALS['global_tier_array'][$GLOBALS['latest_tier']], $raid_size);
		}
	} else if ( $GLOBALS[$module]['display_standing'] == 2 ) {
		foreach ( $GLOBALS['global_region_array'] as $region => $region_details ) {
			if ( count($guild_array) == 2 ) break;

			$guild_array[$region] = get_region_size_standings($module, $temp_guild_array, $GLOBALS['global_tier_array'][$GLOBALS['latest_tier']], $GLOBALS['raid_size_array'][0], $region);
		}
	} else if ( $GLOBALS[$module]['display_standing'] == 3 ) {
		foreach ( $GLOBALS['global_region_array'] as $region => $region_details ) {
			if ( count($guild_array) == 2 ) break;

			$guild_array[$region] = get_region_size_standings($module, $temp_guild_array, $GLOBALS['global_tier_array'][$GLOBALS['latest_tier']], $GLOBALS['raid_size_array'][1], $region);
		}
	} else if ( $GLOBALS[$module]['display_standing'] == 4 ) {
		krsort($GLOBALS['global_dungeon_array']);

		$list_array = $GLOBALS['global_dungeon_array'];
		
		foreach ( $GLOBALS['global_dungeon_array'] as $dungeon_id => $dungeon_details ) { 
			if ( count($guild_array) == 2 ) break;

			$guild_array[$dungeon_id] = get_dungeon_standings($module, $temp_guild_array, $dungeon_details);
		}
	}
	
	function block_draw_top_rankings($module, $guild_array, $point_system) {
		$block_title = generate_block_title($GLOBALS[$module]['block_title'][0]);

		$num_of_encounters = $GLOBALS['global_tier_array'][$GLOBALS['latest_tier']]['encounters'];

		if ( $num_of_encounters > 0 )  {
			$points_array = $points_dungeon_array = $points_size_array = array();
			$dungeon_count = 0;

			foreach ( $guild_array as $guild_id => $guild_details ) {
				if ( isset($guild_details['tier_details'][$GLOBALS['latest_tier']][$point_system]['points']) && $guild_details['tier_details'][$GLOBALS['latest_tier']]['complete'] > 0 ) {
					//if ( $guild_details['tier_details'][$GLOBALS['latest_tier']][$point_system]['points'] == 0.00 ) { continue; }

					if ( $_SESSION['active'] == 1 ) {
						if ( $guild_details['type'] == 1 ) { continue; }
						if ( $guild_details['active'] == 0 ) { continue; }
					}
					
					$points_array[$guild_id] = $guild_details['tier_details'][$GLOBALS['latest_tier']][$point_system]['points'];

					foreach ( $GLOBALS['global_dungeon_array'] as $dungeon_id => $dungeon_details ) {
						if ( isset($guild_details['dungeon_details'][$dungeon_id][$point_system]['points']) && $guild_details['dungeon_details'][$dungeon_id]['complete'] > 0 ) {
							$points_dungeon_array[$dungeon_id][$guild_id] 	= $guild_details['dungeon_details'][$dungeon_id][$point_system]['points'];
						}
					}

					foreach ( $GLOBALS['global_raid_size_array'] as $size => $raid_size ) {
						if ( isset($guild_details['raid_size_details'][$GLOBALS['latest_tier']][$raid_size][$point_system]['points']) && $guild_details['raid_size_details'][$GLOBALS['latest_tier']][$raid_size]['complete'] > 0 ) {
							$points_size_array[$raid_size][$guild_id] = $guild_details['raid_size_details'][$GLOBALS['latest_tier']][$raid_size][$point_system]['points'];
						}
					}
				}
			}

			foreach ( $GLOBALS['global_dungeon_array'] as $dungeon_id => $dungeon_details ) {
				if ( $dungeon_details['tier'] == $GLOBALS['latest_tier'] ) {
					$type_array[$dungeon_details['players']] = $dungeon_details['players'];
				}
			}

			foreach ( $GLOBALS['global_dungeon_array'] as $dungeon_id => $dungeon_details ) {
				if ( $dungeon_details['tier'] == $GLOBALS['latest_tier'] && $dungeon_details['mobs'] > 0 ) {
					if ( !isset($points_dungeon_array[$dungeon_id]) ) $points_dungeon_array[$dungeon_id] = array();
					$dungeon_count++;
				}
			}

			foreach ( $GLOBALS['global_raid_size_array'] as $size => $raid_size ) {
				if ( !isset($points_size_array[$raid_size]) ) $points_size_array[$raid_size] = array();
			}

			$column_tier_width = (100 / (count($type_array) + 1))."%";
			$column_dung_width = (100 / ($dungeon_count + 0))."%";
		}

		echo "<div class='side_block'>";
			echo "<div class='block_title'>$block_title</div>";

			echo "<div>";
				
				echo "<div id='all' class='block_subtitle click'>{$GLOBALS['global_tier_array'][$GLOBALS['latest_tier']]['title']}</div>";

				$pane_name = "all_block";
				echo "<div name='pane_rank' class='visible' id='$pane_name'>";
					if ( isset($points_array) && count($points_array) > 0 ) {
						arsort($points_array);
						
						$rank = 0;
						foreach ( $points_array as $guild_id => $points ) {
							$guild_details 		= $guild_array[$guild_id];
							$country 			= get_image_flag($guild_details['country']);
							$points 			= number_format($points, 2,".",",");
							$progress_string 	= $guild_details['tier_details'][$GLOBALS['latest_tier']]['progression_overall'];
							$trend 				= $guild_details['tier_details'][$GLOBALS['latest_tier']][$point_system]['world']['trend'];
							$image 				= get_trend_image($trend);
							$name 				= "$country <span>".generate_hyperlink_guild($guild_details['name'], $guild_details['server'], 0, $guild_details['faction'], 20)."</span>";

							$rank++;

							if ( $rank > $GLOBALS[$module]['limit_ranks'] ) break;

							block_draw_ranking_type($name, $rank, $image, $points, $progress_string);
						}
					} else {
						echo "<div class='side_block_content'>";
							echo "Rankings have not been posted";
						echo "</div>";	
					}
				echo "</div>";
				
				foreach ( $type_array as $type => $raid_size ) {
					echo "<div id='$raid_size' class='block_subtitle click'>$raid_size-Mans</div>";

					$pane_name = "{$raid_size}_block";
					echo "<div name='pane_rank' class='collapse' id='$pane_name'>";
						if ( isset($points_size_array[$raid_size]) && count($points_size_array[$raid_size]) > 0 ) {
							arsort($points_size_array[$raid_size]);

							$rank = 0;
							foreach ( $points_size_array[$raid_size] as $guild_id => $points ) {
								$guild_details = $guild_array[$guild_id];

								$country 			= get_image_flag($guild_details['country']);
								$points 			= number_format($points, 2,".",",");
								$trend 				= $guild_details['raid_size_details'][$GLOBALS['latest_tier']][$raid_size][$point_system]['world']['trend'];
								$progress_string 	= $guild_details['tier_details'][$GLOBALS['latest_tier']]['progression_size'][$raid_size];
								$image 				= get_trend_image($trend);
								$name 				= "$country <span>".generate_hyperlink_guild($guild_details['name'], $guild_details['server'], 0, $guild_details['faction'], 20)."</span>";
								$rank++;

								if ( $rank > $GLOBALS[$module]['limit_ranks'] ) break;

								block_draw_ranking_type($name, $rank, $image, $points, $progress_string);
							}	
						} else {
							echo "<div class='side_block_content'>";
								echo "Rankings have not been posted";
							echo "</div>";
						}
					echo "</div>";
				}
				

				$dungeon_set = 0;

				krsort($GLOBALS['global_dungeon_array']);
				foreach ( $GLOBALS['global_dungeon_array'] as $dungeon_id => $dungeon_details ) {
					if ( $dungeon_details['tier'] == $GLOBALS['latest_tier'] && $dungeon_details['mobs'] > 0 ) {
						$abbreviation = strtolower($dungeon_details['abbreviation']);

						echo "<div id='$abbreviation' class='block_subtitle click'>{$dungeon_details['name']}</div>";

						$pane_name = "{$abbreviation}_block";
						$class 	= "collapse";
						//if ( $dungeon_set == 0 ) { $class = "visible"; $dungeon_set = 1; }

						echo "<div name='pane_rank' class='{$class}' id='$pane_name'>";
							if ( isset($points_dungeon_array[$dungeon_id]) && count ($points_dungeon_array[$dungeon_id]) > 0 ) {
								arsort($points_dungeon_array[$dungeon_id]);

								$rank = 0;
								foreach ( $points_dungeon_array[$dungeon_id] as $guild_id => $points ) { 
									$guild_details = $guild_array[$guild_id];
									$country 			= get_image_flag($guild_details['country']);
									$points 			= number_format($points, 2,".",",");
									$trend 				= $guild_details['dungeon_details'][$dungeon_id][$point_system]['world']['trend'];
									$progress_string 	= "{$guild_details['dungeon_details'][$dungeon_id]['standing']} {$GLOBALS['global_dungeon_array'][$dungeon_id]['abbreviation']}";
									$image 				= get_trend_image($trend);
									$name 				= "$country <span>".generate_hyperlink_guild($guild_details['name'], $guild_details['server'], 0, $guild_details['faction'], 20)."</span>";
									$rank++;

									if ( $rank > $GLOBALS[$module]['limit_ranks'] ) break;

									block_draw_ranking_type($name, $rank, $image, $points, $progress_string);
								}		
							} else {
								echo "<div class='side_block_content'>";
									echo "Rankings have not been posted";
								echo "</div>";		
							}
						echo "</div>";
					}
				}
			echo "</div>";
		echo "</div>";
	}

	function block_draw_recent_raids($module, $recent_array, $guild_array) {
		$block_title = generate_block_title($GLOBALS[$module]['block_title'][1]);

		echo "<div class='side_block'>";
			echo "<div class='block_title'>$block_title</div>";

			if ( count ($recent_array) > 0 ) {
				$current = date_create();
				date_timestamp_set($current, strtotime("now"));

				for ( $count = 0; $count < count($recent_array);  $count++ ) {
					$id_array 				= explode("|", $recent_array[$count]);
					$encounter_id 			= $id_array[1];
					$guild_id 				= $id_array[0];
					$guild_details 			= $guild_array[$guild_id];
					$encounter_details 		= $guild_details['direct_encounter_details'][$encounter_id];
					$encounter				= short_name($encounter_details['encounter_name'], 22);
					$country	 			= get_image_flag($guild_details['country']);
					$name 					= "$country <span>".generate_hyperlink_guild($guild_details['name'], $guild_details['server'], 0, $guild_details['faction'], 15)."</span>";
					$time 					= format_date("{$encounter_details['date']} {$encounter_details['time']}", 'm/d H:i');

					echo "<div class='side_block_content'>";
						echo "<div class='side_block_content_right'>"; 
							echo "<div class='large'><div class='small_text'>$time</div></div>";
						echo "</div>";
						echo "<div class='side_block_content_left'>"; 
							echo "<div class='small'><div class='side_title'>$name</div></div>";
							echo "<div class='small'><div class='small_text'>$encounter</div></div>";
						echo "</div>";
						echo "<div class='clear'></div>";
					echo "</div>";					
				}									
			}
		echo "</div>";
	}

	function block_draw_ranking_type($name, $rank, $image, $points, $progress_string) {
		echo "<div class='side_block_content'>";
			echo "<div class='side_block_content_right'>"; 
				echo "<div class='large'><div class='large_text'>$image $rank</div></div>";
			echo "</div>";
			echo "<div class='side_block_content_left'>"; 
				echo "<div class='small'><div class='side_title'>$name</div></div>";
				echo "<div class='small'><div class='data'><points>$points</points> <progression>$progress_string</progression></div></div>";
			echo "</div>";
			echo "<div class='clear'></div>";
		echo "</div>";
	}

	function draw_primary_rankings($module, $guild_array, $display) {
		echo "<div>";
			$table_count = 0;

			$list_array 			= array();
			$title_array 			= array();
			$latest_tier_details 	= $GLOBALS['global_tier_array'][$GLOBALS['latest_tier']];

			if ( $display == 0 ) { 
				$list_array = $GLOBALS['global_tier_array'];

				foreach ( $GLOBALS['global_tier_array'] as $tier => $tier_details ) { 
					$title_array[$list_type] = "Tier {$tier_details['tier']} Top {$GLOBALS[$module]['limit_standing']} World Guilds";  
				} 
			}

			if ( $display == 1 ) { 
				$list_array = $GLOBALS['global_raid_size_array']; 

				foreach ( $GLOBALS['global_raid_size_array'] as $index => $raid_size ) { 
					$title_array[$raid_size] = "Tier {$latest_tier_details['tier']} {$raid_size}-Mans Top {$GLOBALS[$module]['limit_standing']} World Guilds";  
				} 
			}

			if ( $display == 2 ) {
				$raid_size 	= $GLOBALS['raid_size_array'][0]; 
				$list_array = $GLOBALS['global_region_array'];
				foreach ( $GLOBALS['global_region_array'] as $region => $region_details ) { 
					$title_array[$region] = "Tier {$latest_tier_details['tier']} {$raid_size}-Mans Top {$GLOBALS[$module]['limit_standing']} {$region} Guilds";  
				}  
			}

			if ( $display == 3 ) {
				$raid_size 	= $GLOBALS['raid_size_array'][1];  
				$list_array = $GLOBALS['global_region_array'];
				foreach ( $GLOBALS['global_region_array'] as $region => $region_details ) { 
					$title_array[$region] = "Tier {$latest_tier_details['tier']} {$raid_size}-Mans Top {$GLOBALS[$module]['limit_standing']} {$region} Guilds";  
				}  
			}

			if ( $display == 4 ) {
				$list_array = $GLOBALS['global_dungeon_array'];
				foreach ( $GLOBALS['global_dungeon_array'] as $dungeon_id => $dungeon_details ) { 
					$title_array[$dungeon_id] = "{$dungeon_details['name']} Top {$GLOBALS[$module]['limit_standing']} World Guilds";  
				}  
			}

			foreach ( $list_array as $list_type => $list_details ) {
				$title = "{$title_array[$list_type]}";
				$table_count++;

				echo "<div class='primary_content_half_block'>";
					echo "<div class='primary_content_title'>$title</div>";
					//echo "Spreadsheet View";
					echo "<div class='spreadsheet_popup'>Spreadsheet View</div>";
					draw_spreadsheet_popup($list_details, $guild_array[$list_type]);
					echo "<table class='table_data news_standings'>";
						echo "<thead>";
							echo "<tr>";
								foreach ( $GLOBALS[$module]['header_standing'] as $key => $value ) {
									echo "<th>$key</th>";
								}
							echo "</tr>";
						echo "</thead>";
						echo "<tbody>";
							if ( count($guild_array[$list_type]) > 0 ) {
								$rank = 1;

								foreach ( $guild_array[$list_type] as $guild_id => $guild_details ) {
									if ( $rank > $GLOBALS[$module]['limit_standing'] ) break;

									$guild_details['rank'] 	= $rank;
									$guild_details 			= generate_table_fields($guild_details, 15);
									$rank++;

									echo "<tr>";
										foreach ( $GLOBALS[$module]['header_standing'] as $key => $value ) {
											$item = $guild_details[$value];

											if ( $value == "name" ) { echo "<td>$item</td>"; continue; }

											echo "<td class='news_standings'>$item</td>";
										}
									echo "</tr>";
								}
							} else {
								echo "<tr>";
									echo "<td colspan='".count($GLOBALS[$module]['header_standing'])."' class='table_data_empty'>";
										echo "No one is cool/hardcore/badass enough to be here.";
									echo "</td>";
								echo "</tr>";
							}
						echo "</tbody>";
					echo "</table>";
				echo "</div>";

				if ( $table_count == 2 ) break;

				echo "<div class='vertical_separator'></div>";
			}				

			echo "<div class='clear'></div>";
		echo "</div>";
	}

	function draw_content_articles($module, $news_array) {
		echo "<div class='primary_content_block'>";
			for ( $count = 0; $count < count($news_array); $count++ ) {
				$news_title			= $news_array[$count]['title'];
				$news_date			= $news_array[$count]['date_added'];
				$news_poster 		= $news_array[$count]['added_by'];
				$news_content		= $news_array[$count]['content'];
				$news_type			= $news_array[$count]['type'];
				$hyperlink_title 	= strtolower(str_replace(" ","_", $news_title));
				$hyperlink_title 	= strtolower(str_replace("#","poundsign", $hyperlink_title)); //%23
				$hyperlink 			= $GLOBALS['page_news'].$hyperlink_title;
				$news_title 		= generate_hyperlink($hyperlink, $news_title, 0, "", "");

				echo "<article class='content_article'>";
					echo "<div class='content_article_content'>";
						echo "<div class='news_type_container'>";
							if ( $news_type == 0 ) { echo $GLOBALS['images']['icon_news']; }
							if ( $news_type == 1 ) { echo $GLOBALS['images']['icon_patch']; }
						echo "</div>";
						echo "<div class='news_header_container'>";
							echo "<h1>$news_title</h1>";
							echo "<span>Posted by: $news_poster $news_date</span>";
						echo "</div>";
						echo "<hr><div class='clear'></div>";
						echo "<p>$news_content</p>";
					echo "</div>";
				echo "</article>";
				echo "<div class='clear'></div><br>";
			}

			echo "<div class='clear'></div>";
		echo "</div>";
	}

	function draw_spreadsheet_popup($dungeon_details, $guild_array) {
		echo "<div class='spreadsheet_content'>";
			echo "<div class='popup_title'>". $dungeon_details['name']. " Standings Spreadsheet View</div>";
			echo "<a class='spreadsheet_popup_close' href='#'>Close</a>";
			echo "<div style=\"position:absolute !important; float:left !important; width:auto !important;\">";
				echo "<table class='table_data standings_tier spreadsheet'\">";
					echo "<thead>";
						echo "<tr>";
							foreach ( $GLOBALS['standing']['header_guild'] as $key => $value ) {
								echo "<th>$key</th>";
							}

							foreach ( $GLOBALS['global_encounter_array'] as $encounter_id => $encounter_details ) {
								if ( $encounter_details['dungeon_id'] != $dungeon_details['dungeon_id'] ) { continue; }

								echo "<th>" . $encounter_details['encounter_short_name'] . "</th>";
								$num_of_encounters++;
								$dungeon_encounter_array[$encounter_id] = $encounter_details;
							}
						echo "</tr>";
					echo "</thead>";
					echo "<tbody>";
							if ( count($guild_array) > 0 ) {
								foreach ( $guild_array as $guild_id => $guild_details ) {
									echo "<tr>";
										$guild_details = generate_table_fields($guild_details, 20);
									
										foreach ( $GLOBALS['standing']['header_guild'] as $key => $value ) {
											$item = $guild_details[$value];

											echo "<td>$item</td>";							
										}

										foreach ( $dungeon_encounter_array as $encounter_id => $encounter_details ) {
											if ( isset($guild_details['direct_encounter_details'][$encounter_id]) ) {
												$hyperlink 	= $GLOBALS['fold_guild_screenshots']."/".$guild_details['direct_encounter_details'][$encounter_id]['screenshot'];
												$text 		= $guild_details['direct_encounter_details'][$encounter_id]['datetime'];
												$value 		= $guild_id . "|" . $encounter_id;

												if ( file_exists($_SERVER['DOCUMENT_ROOT'].$hyperlink) ) { $text = "<a class='test_click' value='$value'>$text</a>"; } //href='$hyperlink' rel='lightbox['kill_shots']'

												echo "<td>$text</td>";
											} else {
												echo "<td>--</td>";
											}
										}
									echo "</tr>";
								}
							} else {
								echo "<td colspan='".($num_of_encounters+1)."' class='standings_tier_data'>No guild data found.</td>";
							}		
					echo "</tbody>";
				echo "</table>";
			echo "</div>";
		echo "</div>";
	}

	function get_region_size_standings($module, $unsort_guild_array, $tier_details, $raid_size, $region) {
		$guild_array = $temp_array = array();
		$tier = $tier_details['tier'];

		foreach ( $unsort_guild_array as $guild_id => $guild_details ) {
			if ( $_SESSION['active'] == 1 ) {
				if ( $guild_details['type'] == 1 ) { continue; }
				if ( $guild_details['active'] == 0 ) { continue; }
			}

			if ( $guild_details['region'] != $region ) continue;			
			if ( $guild_details['raid_size_details'][$tier][$raid_size]['complete'] == 0 ) continue;

			foreach ( $guild_details as $key => $value ) { $unsort_guild_array[$guild_id][$key] = $value; }
			foreach ( $guild_details['raid_size_details'][$tier][$raid_size] as $key => $value ) { $unsort_guild_array[$guild_id][$key] = $value; }

			$name 		= $guild_details['name'];
			$complete	= $guild_details['raid_size_details'][$tier][$raid_size]['complete'];
			$datetime 	= $guild_details['raid_size_details'][$tier][$raid_size]['recent_time'];

			$temp_array[$complete][$guild_id] = $guild_details['raid_size_details'][$tier][$raid_size]['recent_time'];
		}

		if ( count($temp_array) > 0 ) {
			krsort($temp_array);

			foreach ($temp_array as $score => $temp_guild_array) {
				asort($temp_guild_array);

				foreach ($temp_guild_array as $guild_id => $complete) {
					$guild_array[$guild_id] = $unsort_guild_array[$guild_id];
				}
			}
		}

		return $guild_array;
	}

	function get_tier_size_standings($module, $unsort_guild_array, $tier_details, $raid_size) {
		$guild_array = $temp_array = array();
		$tier = $tier_details['tier'];

		foreach ( $unsort_guild_array as $guild_id => $guild_details ) {
			if ( $_SESSION['active'] == 1 ) {
				if ( $guild_details['type'] == 1 ) { continue; }
				if ( $guild_details['active'] == 0 ) { continue; }
			}

			if ( $guild_details['raid_size_details'][$tier][$raid_size]['complete'] == 0 ) continue;

			$guild_details['raid_size_details'][$tier][$raid_size]['standing'] = $guild_details['tier_details'][$tier]['progression_size'][$raid_size];

			foreach ( $guild_details as $key => $value ) { $unsort_guild_array[$guild_id][$key] = $value; }
			foreach ( $guild_details['raid_size_details'][$tier][$raid_size] as $key => $value ) { $unsort_guild_array[$guild_id][$key] = $value; }

			$name 		= $guild_details['name'];
			$complete	= $guild_details['raid_size_details'][$tier][$raid_size]['complete'];
			$datetime 	= $guild_details['raid_size_details'][$tier][$raid_size]['recent_time'];

			$temp_array[$complete][$guild_id] = $guild_details['raid_size_details'][$tier][$raid_size]['recent_time'];
		}

		if ( count($temp_array) > 0 ) {
			krsort($temp_array);

			foreach ($temp_array as $score => $temp_guild_array) {
				asort($temp_guild_array);

				foreach ($temp_guild_array as $guild_id => $complete) {
					$guild_array[$guild_id] = $unsort_guild_array[$guild_id];
				}
			}
		}

		return $guild_array;
	}

	function get_tier_standings($module, $unsort_guild_array, $tier_details) {
		$guild_array = $temp_array = array();
		$tier = $tier_details['tier'];

		foreach ( $unsort_guild_array as $guild_id => $guild_details ) {
			if ( $_SESSION['active'] == 1 ) {
				if ( $guild_details['type'] == 1 ) { continue; }
				if ( $guild_details['active'] == 0 ) { continue; }
			}

			if ( $guild_details['tier_details'][$tier]['complete'] == 0 ) continue;

			foreach ( $guild_details as $key => $value ) { $unsort_guild_array[$guild_id][$key] = $value; }
			foreach ( $guild_details['tier_details'][$tier] as $key => $value ) { $unsort_guild_array[$guild_id][$key] = $value; }

			$name 		= $guild_details['name'];
			$complete	= $guild_details['tier_details'][$tier]['complete'];
			$datetime 	= $guild_details['tier_details'][$tier]['recent_time'];

			$temp_array[$complete][$guild_id] = $guild_details['tier_details'][$tier]['recent_time'];
		}

		if ( count($temp_array) > 0 ) {
			krsort($temp_array);

			foreach ($temp_array as $score => $temp_guild_array) {
				asort($temp_guild_array);

				foreach ($temp_guild_array as $guild_id => $complete) {
					$guild_array[$guild_id] = $unsort_guild_array[$guild_id];
				}
			}
		}

		return $guild_array;
	}
	
	function get_dungeon_standings($module, $unsort_guild_array, $dungeon_details) {
		$guild_array 	= $temp_array = array();
		$dungeon_id 	= $dungeon_details['dungeon_id'];
		$final_encounter = $dungeon_details['final_encounter']; 

		foreach ( $unsort_guild_array as $guild_id => $guild_details ) {
			if ( $_SESSION['active'] == 1 ) {
				if ( $guild_details['type'] == 1 ) { continue; }
				if ( $guild_details['active'] == 0 ) { continue; }
			}
	
			if ( $guild_details['dungeon_details'][$dungeon_id]['complete'] == 0 ) continue;
	
			foreach ( $guild_details as $key => $value ) { $unsort_guild_array[$guild_id][$key] = $value; }
			foreach ( $guild_details['dungeon_details'][$dungeon_id] as $key => $value ) { $unsort_guild_array[$guild_id][$key] = $value; }
	
			$name 		= $guild_details['name'];
			$complete	= $guild_details['dungeon_details'][$dungeon_id]['complete'];
			$datetime 	= $guild_details['dungeon_details'][$dungeon_id]['recent_time'];
	
			if ( isset($guild_details['direct_encounter_details'][$final_encounter]) ) { $complete = $dungeon_details['mobs']; }

			$temp_array[$complete][$guild_id] = $guild_details['dungeon_details'][$dungeon_id]['recent_time'];
		}
	
		if ( count($temp_array) > 0 ) {
			krsort($temp_array);
	
			foreach ($temp_array as $score => $temp_guild_array) {
				asort($temp_guild_array);
	
				foreach ($temp_guild_array as $guild_id => $complete) {
					$guild_array[$guild_id] = $unsort_guild_array[$guild_id];
				}
			}
		}
	
		return $guild_array;
	}
?>