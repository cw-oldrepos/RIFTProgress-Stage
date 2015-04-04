<?php
	$ROOT 			= dirname(dirname(dirname(__FILE__)));
	include_once 	"{$ROOT}/configuration.php";

	$module = "servers";
	if ( !isset($GLOBALS[$module]['set']) || $GLOBALS[$module]['set'] == 0 ) { draw_disabled_module(); exit; } 

	//***************DECLARING VARIABLES**************
	$guild_array 		= array();
	$server_details 	= array();
	$tier_select_array 	= array();
	$tier_details 		= array();
	$server_page 		= "";
	$header_text 		= "";
	//***************DECLARING VARIABLES**************

	$server_name 	= strtolower(str_replace(" ", "-", $_GET['server']));
	$server_details = get_server_details_by_name($server_name);
	$server_page 	= $server_details['name'];

	if ( !isset($server_details['server_id']) ) header("Location: ".$GLOBALS['page_news']);

	if ( isset($_GET['tier']) ) {
		$tier_details = get_tier_details_by_name($_GET['tier']);
	} else {
		$tier_details = get_tier_details($GLOBALS['latest_tier']);
	}

	if ( !isset($tier_details['tier']) ) $tier_details = get_tier_details($GLOBALS['latest_tier']);

	$guild_array = get_current_standings_per_dungeon($tier_details, $server_details, $GLOBALS['global_guild_array']);
	$server_details['num_of_guilds'] 	= $guild_array['num_of_guilds'];
	$server_details['num_of_rf'] 		= $guild_array['num_of_rf'];
	$server_details['num_of_wf'] 		= $guild_array['num_of_wf'];
	$header_text 						= $tier_details['title'];

	function block_draw_server_details($module, $server_details) {
		$block_title = generate_block_title($GLOBALS[$module]['block_title'][0]);

		echo "<div class='side_block'>";
			echo "<div class='block_title'>$block_title</div>";
				$server_region 				= $server_details['region'];
				$server_country				= get_image_flag($server_details['region']);
				$server_details['full'] 	= "$server_country <span>{$server_details['name']}</span>";
				$server_details['region'] 	= $GLOBALS['global_region_array'][$server_region]['full'];

				foreach ($GLOBALS[$module]['block_details'] as $header => $value) {
					$item = $server_details[$value];

					echo "<div class='side_block_content'>";
						echo "<div class='side_block_content_right'>"; 
							echo "<div class='small'><div class='medium_text'>$item</div></div>";
						echo "</div>";
						echo "<div class='side_block_content_left'>"; 
							echo "<div class='small'><div class='side_title'>$header</div></div>";
						echo "</div>";
						echo "<div class='clear'></div>";
					echo "</div>";
				}
		echo "</div>";
	}

	function block_draw_server_progress($module, $tier_details, $server_details, $guild_array) {
		$block_title = generate_block_title($GLOBALS[$module]['block_title'][1]);

		$guild_array = get_tier_standings($GLOBALS['global_guild_array'], $server_details, $tier_details);

		echo "<div class='side_block'>";
			echo "<div class='block_title'>$block_title</div>";

			if ( count($guild_array) > 0 ) {
				$rank = 0;

				foreach ( $guild_array as $guild_id => $guild_details ) {
					$rank++;

					$guild_details 		= generate_block_fields($GLOBALS['point_system_default'], "world", $guild_details, 20);
					$progress_string 	= $guild_details['tier_details'][$tier_details['tier']]['progression_overall'];

					echo "<div class='side_block_content'>";
						echo "<div class='side_block_content_right'>"; 
							echo "<div class='large'><div class='large_text'>$rank</div></div>";
						echo "</div>";
						echo "<div class='side_block_content_left'>"; 
							echo "<div class='small'><div class='side_title'>{$guild_details['name']}</div></div>";
							echo "<div class='small'><div class='small_text'>$progress_string</div></div>";
						echo "</div>";
						echo "<div class='clear'></div>";
					echo "</div>";

					if ( $rank == $GLOBALS[$module]['limit_progress'] ) break;	
				}							
			} else {
				echo "<div class='side_block_content'>";
					echo "No guild data for this server.";
					echo "<div class='clear'></div>";
				echo "</div>";	
			}		
		echo "</div>";
	}

	function block_draw_tier_selection($module, $tier_array, $server_name) {
		$block_title = generate_block_title($GLOBALS[$module]['block_title'][2]);

		echo "<div class='side_block'>";
			echo "<div class='block_title'>$block_title</div>";
			echo "<div class='side_block_content'>";
				echo "<form method='POST' action='{$GLOBALS['page_servers']}redirect'>";
					echo "<select name='tier' class='select_short'>";
						foreach ( $tier_array as $tier => $tier_details ) {
							$text = "Tier {$tier_details['tier']} - {$tier_details['title']}";

							echo "<option value='".strtolower(str_replace(" ", "_", $tier_details['title']))."'>$text</option>";
						}
					echo "</select>";
					echo "<input type='hidden' name='server' value='$server_name'>";
					echo "<div class='clear'></div>";
					echo "<input class='data_button' type='submit' value='Select Tier'>";	
				echo "</form>";
			echo "</div>";
		echo "</div>";
	}

	function draw_server_standings($module, $tier_details, $unsort_guild_array) {
		foreach ( $GLOBALS['global_dungeon_array'] as $dungeon_id => $dungeon_details ) {
			if ( $dungeon_details['tier'] == $tier_details['tier'] ) {
				$guild_array = array();

				if ( isset($unsort_guild_array[$dungeon_id]) ) {
					$guild_array = $unsort_guild_array[$dungeon_id];
				}

				echo "<div class='horizontal_separator'></div>";

				draw_message_banner_dungeons($dungeon_details);

				$tooltip_table = create_progress_dungeon_table($module, $dungeon_details, $guild_array);

				$pane_name 	= $dungeon_details['name'];
				$pane_name 	= strtolower(str_replace(" ", "_", $pane_name));
				$pane_name 	= strtolower(str_replace(":", "", $pane_name));
				
				echo "<div class='clear'></div>";
				echo "<div id='pane_$pane_name'>";
					echo "<table class='table_data servers'>";
						echo "<thead>";
							echo "<tr>";
								foreach ( $GLOBALS[$module]['header_guild'] as $key => $value ) {
									echo "<th>$key</th>";
								}

								foreach ( $GLOBALS['global_encounter_array'] as $encounter_id => $encounter_details ) {
									if ( $encounter_details['mob_type'] == 0 && $encounter_details['dungeon_id'] == $dungeon_details['dungeon_id'] ) {
										echo "<th>".$encounter_details['encounter_short_name']."</th>";
									}
								}
							echo "</tr>";
						echo "</thead>";		
						echo "<tbody>";
							if ( count($guild_array) > 0 ) {
								foreach ( $guild_array as $guild_id => $guild_details ) {
									echo "<tr>";
										$guild_details = generate_table_fields($guild_details, 20);

										foreach ( $GLOBALS[$module]['header_guild'] as $key => $value ) {
											$item = $guild_details[$value];

											echo "<td class='activator tip-specific-class'>";
												echo "<span class='tip-item'>$item";
													echo "<div class='tip tip-specific-class'>$tooltip_table[$guild_id]</div>";
												echo "</span>";
											echo "</td>";											
										}

										foreach ( $GLOBALS['global_encounter_array'] as $encounter_id => $encounter_details ) {
											if ( $encounter_details['mob_type'] == 0 && $encounter_details['dungeon_id'] == $dungeon_details['dungeon_id'] ) {
												$encounter_date 	= "";
												$screenshot_link 	= "";

												if ( isset($guild_details['mob_progress'][$encounter_id]) ) {
													if ( isset($guild_details['mob_progress'][$encounter_id]['datetime']) && strlen($guild_details['mob_progress'][$encounter_id]['datetime']) > 0 ) {
														$screenshot 	= $guild_details['mob_progress'][$encounter_id]['screenshot'];
														$encounter_date = $guild_details['mob_progress'][$encounter_id]['datetime'];	
														$encounter_date = "<a href='{$GLOBALS['fold_guild_screenshots']}$screenshot' data-lightbox='{$guild_id}'>$encounter_date";
													} else {
														$encounter_date = $guild_details['mob_progress'][$encounter_id]['datetime'];	
													}
												} else {
													$encounter_date = "--";
												}

												echo "<td class='servers_data'>$encounter_date</td>";
											}
										}
									echo "</tr>";
								}
							} else {
								echo "<tr>";
									echo "<td colspan='".($dungeon_details['mobs']+count($GLOBALS[$module]['header_guild']))."' class='servers_data'>No guild data found.</td>";
								echo "</tr>";
							}		
						echo "</tbody>";
					echo "</table>";
				echo "</div>";
			}
		}
	}

	function get_current_standings_per_dungeon($tier_details, $server_details, $guild_array) {
		$final_data_array = $stat_array = $guild_count_array = array();
		$stat_array['region_first'] = 0;
		$stat_array['world_first'] = 0;

		foreach ( $GLOBALS['global_dungeon_array'] as $dungeon_id => $dungeon_details ) {
			if ( $dungeon_details['tier'] != $tier_details['tier'] ) continue;

			$final_array = $data_array = $mob_array = $mob_order_array = $mob_sort_array = array();

			foreach ( $GLOBALS['global_encounter_array'] as $encounter_id => $encounter_details ) {
				if ( $encounter_details['dungeon_id'] == $dungeon_details['dungeon_id'] && $encounter_details['mob_type'] == 0 ) {
					$mob_order_array[$encounter_id] = $encounter_details['mob_order'];
				}
			}

			arsort($mob_order_array);

			foreach ( $guild_array as $guild_id => $guild_details ) {
				$guild_details = get_guild_details($guild_id);

				if ( $_SESSION['active'] == 1 ) {
					if ( $guild_details['type'] == 1 ) { continue; }
					if ( $guild_details['active'] == 0 ) { continue; }
				}

				if ( $guild_details['server'] == $server_details['name'] ) {

					foreach ( $guild_details['direct_encounter_details'] as $encounter_id => $encounter_details ) {
						if ( $encounter_details['dungeon_id'] == $dungeon_details['dungeon_id'] ) {
							$mob_array[$encounter_id][$guild_id]			= $encounter_details['strtotime'] ; 
							$encounter_details['datetime']  				= convert_date($encounter_details['strtotime'], 'm-d-Y');
							$guild_details['mob_progress'][$encounter_id] 	= $encounter_details;

							if ( $encounter_details['region_rank'] == 1 ) $stat_array['region_first']++;
							if ( $encounter_details['world_rank'] == 1 ) $stat_array['world_first']++;
						}
					}

					$guild_count_array[$guild_id] = $guild_id;
					$final_array[$guild_id] = $guild_details;
				}
			}		

			foreach ( $mob_array as $mob_id => $mob_data_array ) {
				asort($mob_data_array);
				$mob_sort_array[$mob_id] = $mob_data_array;
			}

			foreach ( $mob_order_array as $mob_id => $mob_data_array ) {
				if ( count($mob_data_array) > 0 ) {
					if ( !isset($mob_sort_array[$mob_id]) ) continue;
					$sort_array = $mob_sort_array[$mob_id];

					foreach ( $sort_array as $guild_id => $guild_details ) {
						if ( !isset($final_data_array[$guild_id]) ) {
							$final_data_array[$dungeon_id][$guild_id] = $final_array[$guild_id];
						}
					}
				}
			}
		}

		$final_data_array['num_of_rf'] 		= $stat_array['region_first'];
		$final_data_array['num_of_wf'] 		= $stat_array['world_first'];
		$final_data_array['num_of_guilds'] 	= count($guild_count_array);

		return $final_data_array;
	}

	function create_progress_dungeon_table($module, $dungeon_details, $guild_array) {
		$table_array = array();

		if ( count($guild_array) > 0 ) {
			foreach ( $guild_array as $guild_id => $guild_details ) {
				$encounter_array 		= array();
				$encounter_date_array 	= array();
				$dungeon_array 			= array();	
				$country 				= get_image_flag($guild_details['country']);
				$name 					= "$country <span>".generate_hyperlink_guild($guild_details['name'], $guild_details['server'], 0, $guild_details['faction'], "")."</span>";
				$output_table 			= "";

				foreach ( $guild_details['direct_encounter_details'] as $encounter_id => $encounter_details ) {
					if ( $encounter_details['dungeon_id'] == $dungeon_details['dungeon_id'] && $encounter_details['mob_type'] == 0 ) {
						$encounter_date_array[$encounter_id] = $encounter_details['strtotime'];
					}
				}

				arsort($encounter_date_array);

				$output_table .= "<div class='tooltip_title'>$name</div>";
				$output_table .= "<div class='clear'></div>";
				$output_table .= "<table class='table_data tooltip'>";
					$output_table .= "<thead>";
						$output_table .= "<tr>";
							foreach ( $GLOBALS[$module]['tooltip_dungeon'] as $key => $value ) {
								$output_table .= "<th>$key</th>";
							}
						$output_table .= "</tr>";
					$output_table .= "</thead>";
					$output_table .= "<tbody>";
						if ( count($encounter_date_array) > 0 ) {
							foreach ( $encounter_date_array as $encounter_id => $encounter_details ) {
								$output_table .= "<tr>";
									foreach ( $GLOBALS[$module]['tooltip_dungeon'] as $key => $value ) {
										$item = $guild_details['direct_encounter_details'][$encounter_id][$value];

										if ( strpos($value, 'rank') > -1 ) $item = get_rank_medal($guild_details['direct_encounter_details'][$encounter_id][$value]);

										$output_table .= "<td>$item</td>";
									}
								$output_table .= "</tr>";	
							}
						} else {
							$output_table .= "<tr>";
								$output_table .= "<td colspan='6'>No raid progression data found.</td>";
							$output_table .= "</tr>";			
						}	
					$output_table .= "</tbody>";
				$output_table .= "</table>";
				
				$table_array[$guild_id] = $output_table;			
			}	
		}

		return $table_array;
	}

	function get_tier_standings($unsort_guild_array, $server_details, $tier_details) {
		$guild_array = $temp_array = array();
		$tier = $tier_details['tier'];

		foreach ( $unsort_guild_array as $guild_id => $guild_details ) {
			if ( $guild_details['server'] == $server_details['name'] ) {
				$guild_details = get_guild_details($guild_id);

				if ( $_SESSION['active'] == 1 ) {
					if ( $guild_details['type'] == 1 ) { continue; }
					if ( $guild_details['active'] == 0 ) { continue; }
				}	

				if ( $guild_details['tier_details'][$tier]['complete'] == 0 ) continue; 
				foreach ( $guild_details as $key => $value ) { $unsort_guild_array[$guild_id][$key] = $value; }
				foreach ( $guild_details['tier_details'][$tier] as $key => $value ) { $unsort_guild_array[$guild_id][$key] = $value; }

				$num_name 	= $num_date	= 0;
				$name 		= $guild_details['name'];
				$complete	= $guild_details['tier_details'][$tier]['complete'];
				$datetime 	= $guild_details['tier_details'][$tier]['recent_time'];

				$temp_array[$complete][$guild_id] = $guild_details['tier_details'][$tier]['recent_time'];
			}
		}

		if ( count($temp_array) > 0 ) {
			krsort($temp_array);

			foreach ($temp_array as $score => $temp_guild_array) {
				asort($temp_guild_array);

				foreach ($temp_guild_array as $guild_id => $complete) {
					array_push($guild_array, $unsort_guild_array[$guild_id]);
				}
			}
		}

		return $guild_array;
	}
?>