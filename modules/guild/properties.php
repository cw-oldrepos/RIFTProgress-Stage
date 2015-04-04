<?php
	$ROOT 			= dirname(dirname(dirname(__FILE__)));
	include_once 	"{$ROOT}/configuration.php";
	
	$module = "guild";
	if ( !isset($GLOBALS[$module]['set']) || $GLOBALS[$module]['set'] == 0 ) { draw_disabled_module(); exit; } 

	//***************DECLARING VARIABLES**************
	$guild_details 		= array();
	$guild_array 		= array();
	$activity_array 	= array();
	$header_text		= "";
	$header_description = "";
	//***************DECLARING VARIABLES**************

	$guild_name = $_GET['guild'];

	if ( strpos($guild_name, "-_-") > -1 ) {
		$guild_info 	= explode("-_-", $guild_name);
		$guild_name 	= trim(strtolower(str_replace("_", " ", $guild_info[0])));
		$guild_server 	= $guild_info[1];
		$guild_array 	= get_guild_details_by_name_server($guild_name, $guild_server);	
	} else {
		$guild_array 	= get_guild_details_by_name($guild_name);		
	}

	if ( count($guild_array) == 0 ) {
		$header_text = "Guild Details";
	} else if ( count($guild_array) == 1 ) {
		$guild_details 		= get_guild_details($guild_array[0]['guild_id']);
		$activity_array 	= get_recent_activity($guild_details['direct_encounter_details']);
		$header_text 		= $guild_details['name']." Guild Details";
		$header_description = $guild_details['name']." Profile at {$GLOBALS['site_title']} View guild details, progression ranking, and kill screenshots.";
	} else if ( count($guild_array) > 1 ) {
		$header_text = "Select your guild";
	}

	function block_draw_recent_activity($module, $activity_array) {
		$block_title = generate_block_title($GLOBALS[$module]['block_title'][0]);

		echo "<div class='side_block'>";
			echo "<div class='block_title'>$block_title</div>";

			if ( count ($activity_array) > 0 ) {
				$current 	= date_create();
				date_timestamp_set($current, strtotime("now"));

				for ( $count = 0; $count < count($activity_array);  $count++ ) {
					$encounter_details 	= $activity_array[$count];
					$tier_details 		= $GLOBALS['global_tier_array'][$encounter_details['tier']];
					$dungeon_details 	= $GLOBALS['global_dungeon_array'][$encounter_details['dungeon_id']];
					$encounter 			= generate_hyperlink_standing($encounter_details['encounter_name'], "encounter", "world", $encounter_details, $dungeon_details, 0, "", "");

					echo "<div class='side_block_content'>";
						echo "<div class='side_block_content_left'>"; 
							echo "<div class='small'><div class='side_title'>$encounter</div></div>";
							echo "<div class='small'><div class='small_text'>{$encounter_details['datetime']}</div></div>";
						echo "</div>";
						echo "<div class='clear'></div>";
					echo "</div>";

					if ( $count >= $GLOBALS[$module]['limit_activity'] ) break;
				}
			} else {
				echo "<div class='side_block_content'>";
					echo "No recent raid activity.";
					echo "<div class='clear'></div>";
				echo "</div>";				
			}		
		echo "</div>";
	}

	function block_draw_ranking_details($module, $guild_details) {
		$block_title = generate_block_title($GLOBALS[$module]['block_title'][1]);

		echo "<div class='side_block'>";
			echo "<div class='block_title'>$block_title</div>";

			if ( isset($guild_details['guild_id']) ) {
				foreach ( $GLOBALS['global_tier_array'] as $tier => $tier_details ) {
					$tier_title = generate_hyperlink_ranking($tier_details['title'], "tier", "prad", "world", $tier_details, "", 0, "", "");
					echo "<div class='block_subtitle'>$tier_title</div>";

					foreach ( $GLOBALS['global_dungeon_array'] as $dungeon_id => $dungeon_details ) {
						if ( $dungeon_details['tier'] == $tier && $dungeon_details['dungeon_type'] == 0 ) {
							$dungeon_name 	= generate_hyperlink_ranking($dungeon_details['name'], "dungeon", "prad", "world", $dungeon_details, $tier_details, 0, "", 22);
							$points 		= number_format($guild_details['dungeon_details'][$dungeon_id][$GLOBALS['point_system_default']]['points'], 2, ".", ",");
							$rank 			= format_ordinal($guild_details['dungeon_details'][$dungeon_id][$GLOBALS['point_system_default']]['world']['legacy_rank']);

							if ( $_SESSION['active'] == 1 ) { $rank = format_ordinal($guild_details['dungeon_details'][$dungeon_id][$GLOBALS['point_system_default']]['world']['rank']); }

							echo "<div class='side_block_content'>";
								echo "<div class='side_block_content_right'>"; 
									echo "<div class='large'><div class='large_text'>$rank</div></div>";
								echo "</div>";
								echo "<div class='side_block_content_left'>"; 
									echo "<div class='small'><div class='side_title'>$dungeon_name</div></div>";
									echo "<div class='small'><div class='small_text'>$points</div></div>";
								echo "</div>";
								echo "<div class='clear'></div>";
							echo "</div>";
						}
					}
				}
			} else {
				echo "<div class='side_block_content'>";
					echo "No ranking details found.";
					echo "<div class='clear'></div>";
				echo "</div>";	
			}
		echo "</div>";		
	}

	function draw_guild_navigation($module, $guild_details) {
		$faction 		= strtolower($guild_details['faction']);
		$faction_logo 	= get_faction_background($guild_details['faction']);
		$country_large	= get_large_image_flag($guild_details['country']);
		$faction_large 	= "{$GLOBALS['fold_guild_logos']}tmp/{$faction}_default.png";
		$name 			= "$country_large <span>{$guild_details['name']}</span>";

		if ( !isset($guild_details['overall_details']['recent_encounter']) ) unset($GLOBALS[$module]['navigation']['Recent Activity']);

		echo "<div class='guild_banner_wrapper'>";
			echo "<div class='guild_banner {$faction}_bg'>";
				echo "$name";
			echo "</div>";
			echo "<div class='guild_banner_faction' style=\"background-image:url('$faction_logo');\"></div>";	
		echo "</div>";
		echo "<div class='clear'></div>";

		echo "<div id='guild_nav'>";
			echo "<ul>";
				foreach ( $GLOBALS[$module]['navigation'] as $key => $value ) {
					echo "<li><a href='#$value'>$key</a></li>";
				}
			echo "</ul><hr>";
		echo "</div>";

		if ( $guild_details['type']  == 1 ) {
			$parent_details = $GLOBALS['global_guild_array'][$guild_details['parent']];
			$parent_details = generate_table_fields($parent_details, "");

			echo "Secondary Raid Team of {$parent_details['name']}";
		}

		echo "<div class='horizontal_separator'></div>";
	}

	function draw_guild_details($module, $guild_details) {
		$guild_logo		= get_guild_logo($guild_details['guild_id']);
		$guild_details 	= generate_table_fields($guild_details, "");

		echo "<div id='profile' class='pane_title pane'>Profile<hr></div>";
		echo "<div class='horizontal_separator'></div>";

		echo "<div class='pane_profile'>";
			echo "<table class='table_data guild_details'>";
				echo "<thead>";
				echo "</thead>";
				echo "<tbody>";
					foreach ( $GLOBALS[$module]['guild_details'] as $key => $value ) {
						$item = $guild_details[$value];
						if ( !isset($item) || strlen($item) == 0 ) $item = "N/A";

						echo "<tr>";
							echo "<th>$key</th>";
							echo "<td>$item</td>";
						echo "</tr>";	
					}
				echo "</tbody>";
			echo "</table>";
			echo "<div class='vertical_separator'></div>";
			echo "$guild_logo";
		echo "</div>";
	}

	function draw_guild_rankings($module, $guild_details) {
		$server 			= $guild_details['server'];
		$region  			= $GLOBALS['global_region_array'][$guild_details['region']]['full'];
		$world_rank 		= $guild_details['overall_details'][$GLOBALS['point_system_default']]['world']['rank'];
		$region_rank 		= $guild_details['overall_details'][$GLOBALS['point_system_default']]['region']['rank'];
		$server_rank 		= $guild_details['overall_details'][$GLOBALS['point_system_default']]['server']['rank'];
		$world_rank_ord 	= format_ordinal($world_rank);
		$region_rank_ord 	= format_ordinal($region_rank);
		$server_rank_ord 	= format_ordinal($server_rank);
		$world_trend 		= get_trend_image_large($guild_details['overall_details'][$GLOBALS['point_system_default']]['world']['trend']);
		$region_trend 		= get_trend_image_large($guild_details['overall_details'][$GLOBALS['point_system_default']]['region']['trend']);
		$server_trend 		= get_trend_image_large($guild_details['overall_details'][$GLOBALS['point_system_default']]['server']['trend']);
		$world_prev_rank 	= format_ordinal($guild_details['overall_details'][$GLOBALS['point_system_default']]['world']['prev_rank']);
		$region_prev_rank 	= format_ordinal($guild_details['overall_details'][$GLOBALS['point_system_default']]['region']['prev_rank']);
		$server_prev_rank 	= format_ordinal($guild_details['overall_details'][$GLOBALS['point_system_default']]['server']['prev_rank']);
		$points 			= number_format($guild_details['overall_details'][$GLOBALS['point_system_default']]['points'], 2, ".",",");
		$world_guild_count 	= "($world_rank/".count($GLOBALS['global_guild_array']).")";
		$region_guild_count = "($region_rank/".get_num_of_guilds_region($guild_details['region']).")";
		$server_guild_count = "($server_rank/".get_num_of_guilds_server($server).")";

		echo "<div id='rankings' class='pane_title pane'>Rankings<hr></div>";
		echo "<div class='horizontal_separator'></div>";

		echo "<div class='primary_content_small_block'>";
			echo "<div class='pane_rankings_type'>$region</div><div class='pane_rankings_count'>$region_guild_count</div>";
			echo "<div class='clear'></div>";
			echo "<div class='pane_rankings_trend'>$region_trend<span class='pane_rankings_rank'>$region_rank_ord</span></div>";
			echo "<div class='pane_rankings_previous'>Previous Rank: $region_prev_rank</div>";
		echo "</div>";
		echo "<div class='vertical_separator'></div>";
		echo "<div class='primary_content_small_block'>";
			echo "<div class='pane_rankings_type'>World</div><div class='pane_rankings_count'>$world_guild_count</div>";
			echo "<div class='clear'></div>";
			echo "<div class='pane_rankings_trend'>$world_trend<span class='pane_rankings_rank'>$world_rank_ord</span></div>";
			echo "<div class='pane_rankings_previous'>Previous Rank: $world_prev_rank</div>";
		echo "</div>";
		echo "<div class='vertical_separator'></div>";
		echo "<div class='primary_content_small_block'>";
			echo "<div class='pane_rankings_type'>$server</div><div class='pane_rankings_count'>$server_guild_count</div>";
			echo "<div class='clear'></div>";
			echo "<div class='pane_rankings_trend'>$server_trend<span class='pane_rankings_rank'>$server_rank_ord</span></div>";
			echo "<div class='pane_rankings_previous'>Previous Rank: $server_prev_rank</div>";
		echo "</div>";
	}

	function draw_guild_latest($module, $guild_details) {
		$world_rank 		= "";
		$region_rank 		= "";
		$server_rank 		= "";
		$points 			= 0.00;
		$server 			= $guild_details['server'];
		$region  			= $GLOBALS['global_region_array'][$guild_details['region']]['full'];
		
		if ( !isset($guild_details['overall_details']['recent_encounter']) ) return;

		$encounter_details 	= $guild_details['overall_details']['recent_encounter'];
		$encounter_id		= $encounter_details['encounter_id'];
		$guild_id 			= $guild_details['guild_id'];
		$image 				= "{$guild_id}-{$encounter_details['encounter_id']}";

		if ( isset($guild_details['direct_encounter_details'][$encounter_id][$GLOBALS['point_system_default']]) ) {
			$world_rank 		= format_ordinal($guild_details['direct_encounter_details'][$encounter_id][$GLOBALS['point_system_default']]['world']['rank']);
			$region_rank 		= format_ordinal($guild_details['direct_encounter_details'][$encounter_id][$GLOBALS['point_system_default']]['region']['rank']);
			$server_rank 		= format_ordinal($guild_details['direct_encounter_details'][$encounter_id][$GLOBALS['point_system_default']]['server']['rank']);
			$points 			= number_format($guild_details['direct_encounter_details'][$encounter_id][$GLOBALS['point_system_default']]['points'], 2, ".",",");
		}

		echo "<br><div id='recent' class='pane_title pane'>Recent Activity<hr></div>";
		echo "<div class='horizontal_separator'></div>";

		echo "<div class='primary_content_block'>";
			echo "<div class='pane_latest_activity'>";
				echo "<a href='{$GLOBALS['fold_guild_screenshots']}$image' data-lightbox='{latest}'><img id='latest_image' src='{$GLOBALS['fold_guild_screenshots']}$image'></a>";
			echo "</div>";
			echo "<div class='vertical_separator'></div>";
			echo "<div class='pane_latest_container'>";
				echo "<div class='pane_latest_details'>";
					echo "<p>";
						echo "<span class='large_text'>{$encounter_details['encounter_name']}</span><br>";
						echo "<span class='medium_text'>{$encounter_details['datetime']} {$encounter_details['timezone']}</span><br>";
						echo "<span class='medium_text'>World $world_rank</span><br>";
						echo "<span class='medium_text'>$region $region_rank</span><br>";
						echo "<span class='medium_text'>$server $server_rank</span><br>";
						echo "<span class='medium_text'>Quality Points: $points</span>";
					echo "</p>";
				echo "</div>";
			echo "</div>";
		echo "</div>";
	}

	function draw_guild_progression($module, $guild_details) {
		echo "<div id='progression' class='pane_title pane'>Progression<hr></div>";
		echo "<div class='horizontal_separator'></div>";
		krsort($GLOBALS['global_tier_array']);

		foreach ( $GLOBALS['global_tier_array'] as $tier => $tier_details ) {
			if ( $tier_details['encounters'] > 0 ) {				
				echo "<div id='pane_$tier' class='tier'>";

					foreach ( $GLOBALS['global_dungeon_array'] as $dungeon_id => $dungeon_details ) {
						if ( $dungeon_details['tier'] == $tier ) {
							
							draw_message_banner_dungeons($dungeon_details);

							$dungeon_array[$dungeon_id]['valid'] = 0;

							$pane_name = $dungeon_details['name'];
							$pane_name = strtolower(str_replace(" ", "_", $pane_name));
							$pane_name = strtolower(str_replace(":", "", $pane_name));

							echo "<div id='pane_$pane_name' class='dungeon'>";
								echo "<table class='table_data progression'>";
									echo "<thead>";
										echo "<tr>";
											echo "<th>Encounter</th>";
											foreach ( $GLOBALS[$module]['header_progression'] as $header => $key ) {
												echo "<th>$header</th>";	
											}
										echo "</tr>";
									echo "</thead>";
									echo "<tbody>";
										foreach ( $GLOBALS['global_encounter_array'] as $encounter_id => $encounter_details ) {
											if ( $encounter_details['dungeon_id'] == $dungeon_id ) {
												$encounter_name = $encounter_details['encounter_name'];

												echo "<tr>";
													echo "<td>".generate_hyperlink_standing($encounter_name, "encounter", "world", $encounter_details, $dungeon_details, 0, "", "")."</td>";

													foreach ( $GLOBALS[$module]['header_progression'] as $header => $key ) {
														$value = "--";

														if ( isset($guild_details['direct_encounter_details'][$encounter_id][$key]) && $guild_details['direct_encounter_details'][$encounter_id][$key] != "" ) {
															$value = $guild_details['direct_encounter_details'][$encounter_id][$key];

															if ( $header == "QP" ) $value = number_format($guild_details['direct_encounter_details'][$encounter_id][0]['points'], 2, ".", ",");
															if ( $header == "AP" ) $value = number_format($guild_details['direct_encounter_details'][$encounter_id][1]['points'], 2, ".", ",");
															if ( $header == "APF" ) $value = number_format($guild_details['direct_encounter_details'][$encounter_id][2]['points'], 2, ".", ",");
															if ( $key == "world_rank" || $key == "region_rank" || $key == "server_rank" ) $value = get_rank_medal($value);
															if ( $key == "screenshot" ) {
																if ( file_exists($_SERVER['DOCUMENT_ROOT']."{$GLOBALS['fold_guild_screenshots']}$value") ) { 
																	$value = "<a href='{$GLOBALS['fold_guild_screenshots']}$value' data-lightbox='{$dungeon_id}'>View"; 
																} else {
																	$value = "--";
																}
															}
															if ( $key == "video" ) $value = "<a href='{$value}'>View";
														}
														
														echo "<td>$value</td>";
													}
												echo "</tr>";
											}
										}
									echo "</tbody>";
								echo "</table>";
							echo "</div>";
							echo "<div class='clear'></div>";
							echo "<div class='horizontal_separator'></div>";
						}
					}
				echo "</div>";
			}
		}
	}

	function draw_guild_history($module, $guild_details) {
		$encounter_date_array = array();

		foreach ( $guild_details['direct_encounter_details'] as $encounter_id => $encounter_details ) {
			$encounter_date_array[$encounter_id] = $encounter_details['strtotime'];
		}

		asort($encounter_date_array);

		echo "<div id='timeline' class='pane_title'>Activity Timeline<hr></div>";
		echo "<div class='horizontal_separator'></div>";

		echo "<table class='table_data'>";
			echo "<thead>";
				echo "<tr>";
					foreach ( $GLOBALS[$module]['header_history'] as $header => $key ) {
						echo "<th>$header</th>";	
					}
				echo "</tr>";
			echo "</thead>";
			echo "<tbody>";
				if ( count($encounter_date_array) > 0 ) {
					$previous = 0;
					$previous_kill = "";
					$previous_kill_date = date_create();

					foreach ( $encounter_date_array as $encounter_id => $strtotime ) {
						$encounter_details 	= $guild_details['direct_encounter_details'][$encounter_id];
						$tier_details 		= $GLOBALS['global_tier_array'][$encounter_details['tier']];
						$dungeon_details 	= $GLOBALS['global_dungeon_array'][$encounter_details['dungeon_id']];

						$encounter_details['encounter_name'] 	= generate_hyperlink_standing($encounter_details['encounter_name'], "encounter", "world", $encounter_details, $dungeon_details, 0, "", "");
						$encounter_details['dungeon_name'] 		= generate_hyperlink_standing($dungeon_details['name'], "dungeon", "world", $dungeon_details, $tier_details, 0, "", "");
						$encounter_details['tier'] 				= generate_hyperlink_standing("{$tier_details['tier']}", "tier", "world", $tier_details, "", 0, "", "");

						list($encounter_details['span'], $previous_kill, $previous_kill_date) = get_kill_span($encounter_details, $previous, $previous_kill, $previous_kill_date);

						echo "<tr>";
							foreach ( $GLOBALS[$module]['header_history'] as $header => $key ) {
								$value = $encounter_details[$key];

								if ( $value == "" ) { $value = "--"; echo "<td>$value</td>"; continue; }
								if ( $header == "QP" ) $value = number_format($encounter_details[0]['points'], 2, ".", ",");
								if ( $header == "AP" ) $value = number_format($encounter_details[1]['points'], 2, ".", ",");
								if ( $header == "APF" ) $value = number_format($encounter_details[2]['points'], 2, ".", ",");
								if ( $key == "world_rank" || $key == "region_rank" || $key == "server_rank" ) $value = get_rank_medal($value);
								if ( $key == "screenshot" ) {
									if ( file_exists($_SERVER['DOCUMENT_ROOT']."{$GLOBALS['fold_guild_screenshots']}$value") ) { 
										$value = "<a href='{$GLOBALS['fold_guild_screenshots']}$value' rel='lightbox['kill_shots']' data-lightbox='{history-$encounter_id}'>View"; 
									} else {
										$value = "--";
									}

									//$value = "<a href='{$GLOBALS['fold_guild_screenshots']}$value' rel='lightbox['kill_shots']' data-lightbox='{history-$encounter_id}'>View";
								}
								if ( $key == "video" ) $value = "<a href='{$value}'>View";

								echo "<td>$value</td>";
							}
						echo "</tr>";

						if ( $previous == 0 ) $previous = 1;
					}
				} else {
					echo "<td colspan='".count($GLOBALS[$module]['header_history'])."' class='standings_tier_data'>No guild history found.</td>";
				}
			echo "</tbody>";
		echo "</table>";
	}

	function draw_guild_streams($module, $guild_details) {
		echo "<div id='streamers' class='pane_title'>Streams<hr></div>";
		echo "<div>";
			echo "<div style='background-color: rgba(17,17,17,0.5); float:left; padding-bottom:5px;'>";
			echo "<object type='application/x-shockwave-flash' height='379' width='379' id='live_embed_player_flash' data='http://www.twitch.tv/widgets/live_embed_player.swf?channel=ezakitv' bgcolor='#000000'><param name='allowFullScreen' value='true' /><param name='allowScriptAccess' value='always' /><param name='allowNetworking' value='all' /><param name='movie' value='http://www.twitch.tv/widgets/live_embed_player.swf' /><param name='flashvars' value='hostname=www.twitch.tv&channel=ezakitv&auto_play=false&start_volume=25' /></object>";
			echo "<br>Ezekial";
			echo "</div>";
			echo "<div class='vertical_separator'></div>";
			echo "<div style='background-color: rgba(17,17,17,0.5); float:left;'>";
			echo "<object type='application/x-shockwave-flash' height='379' width='379' id='live_embed_player_flash' data='http://www.twitch.tv/widgets/live_embed_player.swf?channel=ezakitv' bgcolor='#000000'><param name='allowFullScreen' value='true' /><param name='allowScriptAccess' value='always' /><param name='allowNetworking' value='all' /><param name='movie' value='http://www.twitch.tv/widgets/live_embed_player.swf' /><param name='flashvars' value='hostname=www.twitch.tv&channel=ezakitv&auto_play=false&start_volume=25' /></object>";
			echo "<br>Ezekial";
			echo "</div>";
			echo "<div class='clear'></div>";
			echo "<div class='horizontal_separator'></div>";
			echo "<div style='background-color: rgba(17,17,17,0.5); float:left;'>";
			echo "<object type='application/x-shockwave-flash' height='379' width='379' id='live_embed_player_flash' data='http://www.twitch.tv/widgets/live_embed_player.swf?channel=ezakitv' bgcolor='#000000'><param name='allowFullScreen' value='true' /><param name='allowScriptAccess' value='always' /><param name='allowNetworking' value='all' /><param name='movie' value='http://www.twitch.tv/widgets/live_embed_player.swf' /><param name='flashvars' value='hostname=www.twitch.tv&channel=ezakitv&auto_play=false&start_volume=25' /></object>";
			echo "<br>Ezekial";
			echo "</div>";
			echo "<div class='vertical_separator'></div>";
			echo "<div style='background-color: rgba(17,17,17,0.5); float:left;'>";
			echo "<object type='application/x-shockwave-flash' height='379' width='379' id='live_embed_player_flash' data='http://www.twitch.tv/widgets/live_embed_player.swf?channel=ezakitv' bgcolor='#000000'><param name='allowFullScreen' value='true' /><param name='allowScriptAccess' value='always' /><param name='allowNetworking' value='all' /><param name='movie' value='http://www.twitch.tv/widgets/live_embed_player.swf' /><param name='flashvars' value='hostname=www.twitch.tv&channel=ezakitv&auto_play=false&start_volume=25' /></object>";
			echo "<br>Ezekial";
			echo "</div>";
			echo "<div class='clear'></div>";
			echo "<div class='horizontal_separator'></div>";
			echo "<object type='application/x-shockwave-flash' height='762' width='762' id='live_embed_player_flash' data='http://www.twitch.tv/widgets/live_embed_player.swf?channel=ezakitv' bgcolor='#000000'><param name='allowFullScreen' value='true' /><param name='allowScriptAccess' value='always' /><param name='allowNetworking' value='all' /><param name='movie' value='http://www.twitch.tv/widgets/live_embed_player.swf' /><param name='flashvars' value='hostname=www.twitch.tv&channel=ezakitv&auto_play=false&start_volume=25' /></object>";
			echo "<br>Ezekial";
		echo "</div>";
	}
	
	function draw_guild_widget($module, $guild_details) {
		$guild_id 			= $guild_details['guild_id'];
		$guild_name 		= strtolower(str_replace(" ", "_", $guild_details['name']));
		$guild_server 		= strtolower(str_replace(" ", "_", $guild_details['server']));
		$address 			= $GLOBALS['host_name'];
		$current_dungeon 	= "";
		
		$type_array = array(
						"2" => "World",
						"1" => "Region",
						"0" => "Server"
		);

		echo "<div id='widget' class='pane_title'>Guild Widget<hr></div>";
		echo "<div class='horizontal_separator'></div>";
		
		echo "<div class='form_wrapper'>";
			echo "<table class='table_data form'>";
				echo "<tbody>";
					/*
					echo "<tr>";
						echo "<th>Display Ranking</th>";
						echo "<td>";
							echo "<input type='checkbox' id='rank' name='rank' value='' checked onClick=\"update_widget('$guild_name', '$guild_server', $guild_id, '$address')\">";
						echo "</td>";
					echo "</tr>";
					echo "<tr>";
						echo "<th>Display Trend Pattern</th>";
						echo "<td>";
							echo "<input type='checkbox' id='trend' name='trend' value='' checked onClick=\"update_widget('$guild_name', '$guild_server', $guild_id, '$address')\">";
						echo "</td>";
					echo "</tr>";
					echo "<tr>";
						echo "<th>Display Accumulated Points</th>";
						echo "<td>";
							echo "<input type='checkbox' id='points' name='points' value='' checked onClick=\"update_widget('$guild_name', '$guild_server', $guild_id, '$address')\">";
						echo "</td>";
					echo "</tr>";
					echo "<tr>";
						echo "<th>Ranking System</th>";
						echo "<td>";
							echo "<select class='select_short' id='system' name='system' onChange=\"update_widget('$guild_name', '$guild_server', $guild_id, '$address')\">";
								foreach ( $GLOBALS['point_system'] as $system_id => $system_title ) {
									echo "<option value='{$system_id}'>$system_title</option>";
								}
							echo "</select>";
						echo "</td>";
					echo "</tr>";
					echo "<tr>";
						echo "<th>Tier Selection</th>";
						echo "<td>";
							echo "<select class='select_short' id='tier' name='tier' onChange=\"update_widget('$guild_name', '$guild_server', $guild_id, '$address')\">";
								echo "<option value='0'>All Content</option>";

								foreach ( $GLOBALS['global_tier_array'] as $tier => $tier_details ) {
									echo "<option value='{$tier_details['tier']}'>Tier {$tier_details['tier']} ({$tier_details['title']})</option>";
								}
							echo "</select>";
						echo "</td>";
					echo "</tr>";
					echo "<tr>";
						echo "<th>Raid Size</th>";
						echo "<td>";
							echo "<select class='select_short' id='size' name='size' onChange=\"update_widget('$guild_name', '$guild_server', $guild_id, '$address')\">";
								echo "<option value='0'>All Content</option>";

								foreach ( $GLOBALS['global_raid_size_array'] as $size => $raid_size ) {
									echo "<option value='$raid_size'>{$raid_size}-Mans</option>";
								}
							echo "</select>";
						echo "</td>";
					echo "</tr>";
					*/
					echo "<tr>";
						echo "<th>Ranking Type</th>";
						echo "<td>";
							echo "<select class='select_short' id='type' name='type' onChange=\"update_widget('$guild_name', '$guild_server', $guild_id, '$address')\">";
								foreach ( $type_array as $type_id => $type ) {
									echo "<option value='{$type_id}'>$type</option>";
								}
							echo "</select>";
						echo "</td>";
					echo "</tr>";
					echo "<tr>";
						echo "<th>Dungeon Selection</th>";
						echo "<td>";
							echo "<select class='select_short' id='dungeon' name='dungeon' onChange=\"update_widget('$guild_name', '$guild_server', $guild_id, '$address')\">";
								//echo "<option value='0'>N/A</option>";

								foreach ( $GLOBALS['global_dungeon_array'] as $dungeon_id => $dungeon_details ) {
									if ( $current_dungeon == "" ) { $current_dungeon = $dungeon_id; }
									echo "<option value='{$dungeon_id}'>{$dungeon_details['name']}</option>";
								}
							echo "</select>";
						echo "</td>";
					echo "</tr>";
					echo "<tr>";
						echo "<td colspan='2' style='text-align:center;'>";
							echo "<div id='widget_display'></div>";
						echo "</td>";
					echo "</tr>";
					echo "<tr>";
						echo "<th style='vertical-align:middle;'>Output Text</th>";
						echo "<td>";
							echo "<input type='radio' name='output' value='0' onClick=\"update_output('$guild_name', '$guild_server', $guild_id, 0, '$address');\">URL\t";
							echo "<input type='radio' name='output' value='1' onClick=\"update_output('$guild_name', '$guild_server', $guild_id, 1, '$address');\">HTML\t";
							echo "<input type='radio' name='output' value='2' onClick=\"update_output('$guild_name', '$guild_server', $guild_id, 2, '$address');\" checked='checked'>BBCODE";
						echo "</td>";
					echo "</tr>";
					echo "<tr>";
					echo "<td colspan='2' style='text-align:center;'>";
							echo "<textarea id='textarea_output' style='height:25px; width:752px !important; text-align:center; font: normal 11px/1 Verdana !important;' disabled></textarea>";
							echo "<script type=\"text/javascript\">init_sig(\"$guild_name\", \"$guild_server\", \"$guild_id\", \"2\", \"widget_display\", \"$current_dungeon\", \"0\", \"1\", \"1\", \"2\", \"1\", \"0\", \"0\", \"$address\")</script>";
						echo "</td>";
					echo "</tr>";
				echo "</tbody>";
			echo "</table>";
		echo "</div>";
	}

	function draw_guild_select($guild_array) {
		echo "<div class='banner' style='background-color:#000000;'>";
			echo "<div class='banner_header'>Select Your Guild</div><br>";

			echo "<div style='text-align:center;'>";
				echo "<table class='table_content_flex' style='width:100%;'>";
					echo "<thead>"; 
						echo "<tr>";
							echo "<th>Name</th>";
							echo "<th>Server</th>";
						echo "</tr>";
					echo "</thead>";
					echo "<tbody>";
						for ( $count = 0; $count < count($guild_array); $count++ ) {
							$guild_details = $guild_array[$count];
							$name 			= $guild_details['name'];
							$server 		= $guild_details['server'];
							$faction 		= strtolower($guild_details['faction']);
							$region 		= get_image_flag($guild_details['region']);
							$country		= get_image_flag($guild_details['country']);

							echo "<tr>";
								echo "<td>".generate_hyperlink_guild($guild_details['name'], $guild_details['server'], 0, $guild_details['faction'], 20)."</td>";
								echo "<td> $region $server</td>";
							echo "</tr>";
						}
					echo "</tbody>";
				echo "</table>";
			echo "</div>";
		echo "</div><br>";		
	}

	function get_recent_activity($encounter_array) {
		$temp_array = $activity_array = array();

		foreach ( $encounter_array as $encounter_id => $encounter_details ) {
			$temp_array[$encounter_id] = $encounter_details['strtotime'];
		}

		arsort($temp_array);

		foreach ( $temp_array as $encounter_id => $timestamp ) {
			$encounter_details = $encounter_array[$encounter_id];
			array_push($activity_array, $encounter_details);
		}

		return $activity_array;
	}

	function get_kill_span($encounter_details, $previous, $previous_kill, $previous_kill_date) {
		if ( $previous ==  0 ) {
			$encounter_details['span'] = "--";

			$previous_kill = $encounter_details['strtotime'];
			date_timestamp_set($previous_kill_date, $previous_kill);

			$guild_details['span'] = "--";
		} else {
			$kill_date 	= $encounter_details['strtotime'];
			$date 		= date_create();
			date_timestamp_set($date, $kill_date);
			$diff 		= date_diff($date, $previous_kill_date);
			$result 	= $diff->format("%R%a days ago");
			$months		= $diff->m;
			$hours 		= $diff->h;
			$days 		= $diff->d;
			$minutes	= $diff->i;
			$time 		= "";

			if ( $hours > 0 && $days <= 0 && $months <= 0 ) {
				$minutes 	= number_format($minutes / 60, 0, "", "");
				$time  		= "-$hours.$minutes Hours";
			} else if ( $days > 0 && $months <= 0 ) {
				$hours 		= number_format($hours / 24, 0, "", "");
				$time  		= "-$days.$hours Days";
			} else if ( $months > 0  ) {
				$number_days 	= number_format(($kill_date - $previous_kill)  / (60 * 60 * 24), 0, "", "");
				$minutes 		= number_format($minutes / 60, 0, "", "");
				$time  			= "-$number_days.$minutes Days";
			} else {
				$time = "-$minutes Minutes";
			}

			$encounter_details['span'] = "$time";

			$previous_kill = $kill_date;
			$previous_kill_date = date_create();
			date_timestamp_set($previous_kill_date, $kill_date);						
		}

		return array($encounter_details['span'], $previous_kill, $previous_kill_date);
	}

	function get_num_of_guilds_region($region) {
		$num_of_guilds = 0;

		foreach ( $GLOBALS['global_guild_array'] as $guild_id => $guild_details) {
			if ( $guild_details['region'] == $region ) $num_of_guilds++;
		}

		return $num_of_guilds;
	}

	function get_num_of_guilds_server($server) {
		$num_of_guilds = 0;

		foreach ( $GLOBALS['global_guild_array'] as $guild_id => $guild_details) {
			if ( $guild_details['server'] == $server ) $num_of_guilds++;
		}

		return $num_of_guilds;
	}
?>