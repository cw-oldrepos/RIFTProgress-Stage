<?php
	$ROOT 			= dirname(dirname(dirname(__FILE__)));
	include_once 	"{$ROOT}/configuration.php";

	$module = "ranking";
	if ( !isset($GLOBALS[$module]['set']) || $GLOBALS[$module]['set'] == 0 ) { draw_disabled_module(); exit; } 	

	//***************DECLARING VARIABLES**************
	$guild_array 			= array();
	$dungeon_details 		= array();
	$tier_details 			= array();
	$current_tier 			= ""; 
	$header_text 			= "";
	$display_type 			= "";
	$arg_display 			= "";
	$arg_tier 				= "";
	$arg_size 				= "";
	$arg_dungeon 			= "";
	$arg_poll 				= "";
	$point_system_title 	= "";
	$ranking_type 			= 0;
	$raid_size 				= 0;
	$active_point_system 	= $GLOBALS['point_system_default'];
	//***************DECLARING VARIABLES**************

	if ( isset($_POST) && count($_POST) > 0 ) {
		$arg_display 	= ( isset($_POST['display']) && strlen($_POST['display']) > 0 ? $_POST['display'] : "" );
		$arg_poll 		= ( isset($_POST['poll']) && strlen($_POST['poll']) > 0 ? $_POST['poll'] : "" );
		$arg_tier 		= ( isset($_POST['tier']) && strlen($_POST['tier']) > 0 ? $_POST['tier'] : "" );
		$arg_size 		= ( isset($_POST['type']) && strlen($_POST['type']) > 0 ? $_POST['type'] : "" );
		$arg_dungeon 	= ( isset($_POST['dungeon']) && strlen($_POST['dungeon']) > 0 ? $_POST['dungeon'] : "" );
	} else {
		$arg_display 	= ( isset($_GET['display']) && strlen($_GET['display']) > 0 ? $_GET['display'] : "" );
		$arg_poll 		= ( isset($_GET['poll']) && strlen($_GET['poll']) > 0 ? $_GET['poll'] : "" );
		$arg_tier 		= ( isset($_GET['tier']) && strlen($_GET['tier']) > 0 ? $_GET['tier'] : "" );
		$arg_size 		= ( isset($_GET['type']) && strlen($_GET['type']) > 0 ? $_GET['type'] : "" );
		$arg_dungeon 	= ( isset($_GET['dungeon']) && strlen($_GET['dungeon']) > 0 ? $_GET['dungeon'] : "" );
	}

	// Get Display (World/Region/Server)
	foreach ( $GLOBALS['view_type'] as $index => $view ) {
		if ( $arg_display == $view ) {
			$display_type = $view;
		}
	}

	// Display Fail-safe
	if ( $display_type == "" ) $display_type = "world";
	
	// Get Ranking System
	if ( strlen($arg_poll) > 0 && isset($GLOBALS['ranking']['type'][$arg_poll]) ) {
		if ( $arg_poll == 'prad' ) { $active_point_system = 0; $point_system_title = $GLOBALS['ranking']['type'][$arg_poll]; }
		if ( $arg_poll == 'pra' ) { $active_point_system = 1; $point_system_title = $GLOBALS['ranking']['type'][$arg_poll]; }
		if ( $arg_poll == 'pram' ) { $active_point_system = 2; $point_system_title = $GLOBALS['ranking']['type'][$arg_poll]; } 
	}

	// Get Tier
	if ( $arg_tier == "alltime" ) {
		$current_tier 	= "alltime";
		$tier_details   = "alltime";
		$header_text 	= "All-Time ".ucfirst($display_type)." Rankings";
		$ranking_type 	= 0;
	} else {
		$current_tier 	= strtolower(str_replace("_", " ", $arg_tier));
		$tier_details 	= get_tier_details_by_name($current_tier);
		
		if ( (!isset($tier_details) || count($tier_details) == 0) && $tier_details != "alltime"  ) {
			$current_tier = $GLOBALS['latest_tier'];
			$tier_details = $GLOBALS['global_tier_array'][$current_tier];
		}

		$header_text 	= $tier_details['title']." ".ucfirst($display_type)." Rankings";
		$ranking_type = 1;
	}

	// Get Raid Size
	if ( strlen($arg_size) > 0 ) {
		if ( isset($GLOBALS['global_raid_size_array'][$arg_size]) ) {
			$raid_size = $arg_size;

			if ( $current_tier == "alltime" ) {
				$header_text 	= "All-Time ".$raid_size."-Man ".ucfirst($display_type)." Rankings";
				$ranking_type 	= 2;
			} else if ( isset($tier_details) ) {
				$header_text 	= $tier_details['title']." ".$raid_size."-Man ".ucfirst($display_type)." Rankings";
				$ranking_type 	= 3;
			}
		}
	}

	// Get Dungeon
	if ( strlen($arg_dungeon) > 0 ) {
		$arg_dungeon 		= strtolower(str_replace("_", " ", $arg_dungeon));
		$dungeon_details 	= get_dungeon_details_by_name($arg_dungeon);

		if ( isset($dungeon_details) ) {
			$header_text 	= $dungeon_details['name']." ".ucfirst($display_type)." Rankings";
			$ranking_type 	= 4;
		}
	}

	if ( $ranking_type == 0 ) { $guild_array = get_overall_rankings($module, $GLOBALS['global_guild_array'], $active_point_system); } // All-Time
	if ( $ranking_type == 1 ) { $guild_array = get_tier_rankings($module,$GLOBALS['global_guild_array'], $active_point_system, $tier_details); } // Tier
	if ( $ranking_type == 2 ) { $guild_array = get_overall_size_rankings($module,$GLOBALS['global_guild_array'], $active_point_system, $raid_size); } // All-Time Size
	if ( $ranking_type == 3 ) { $guild_array = get_tier_size_rankings($module,$GLOBALS['global_guild_array'], $active_point_system, $tier_details, $raid_size); } // Tier Size
	if ( $ranking_type == 4 ) { $guild_array = get_dungeon_rankings($module,$GLOBALS['global_guild_array'], $active_point_system, $tier_details, $dungeon_details); } // Dungeon

	function block_draw_trends($module, $display_style, $active_point_system, $current_tier, $dungeon_details, $guild_array, $raid_size) {
		$up_array 		= array();
		$down_array 	= array();
		$new_array 		= array();
		$up_array 		= get_guild_trend($display_style, $active_point_system, $current_tier, $dungeon_details, $guild_array, $raid_size, 0);
		$down_array 	= get_guild_trend($display_style, $active_point_system, $current_tier, $dungeon_details, $guild_array, $raid_size, 1);
		$new_array 		= get_guild_trend($display_style, $active_point_system, $current_tier, $dungeon_details, $guild_array, $raid_size, 2);

		$block_title = generate_block_title($GLOBALS[$module]['block_title'][0]);

		echo "<div class='side_block'>";
			echo "<div class='block_title'>$block_title</div>";

				if ( count($up_array) > 0 || count($down_array) > 0 || count($new_array) ) {
					$count = 0;

					if ( count($up_array) > 0 ) {
						echo "<div class='block_subtitle'>{$GLOBALS[$module]['block_subtitle'][0]}</div>";

						foreach ( $up_array as $guild_id => $guild_details ) {
							$count++;

							$guild_details = generate_block_fields($active_point_system, "world", $guild_details, 15);

							echo "<div class='side_block_content'>";
								echo "<div class='side_block_content_right'>"; 
									echo "<div class='small'><div class='medium_text bold'>{$guild_details['trend']}</div></div>";
								echo "</div>";
								echo "<div class='side_block_content_left'>"; 
									echo "<div class='small'><div class='side_title'>{$guild_details['name']}</div></div>";
								echo "</div>";
								echo "<div class='clear'></div>";
							echo "</div>";

							if ( $count == $GLOBALS[$module]['limit_trend'] ) break;
						}	
					}

					$count = 0;

					if ( count($down_array) > 0 ) {
						echo "<div class='block_subtitle'>{$GLOBALS[$module]['block_subtitle'][1]}</div>";

						foreach ( $down_array as $guild_id => $guild_details ) {
							$count++;

							$guild_details = generate_block_fields($active_point_system, "world", $guild_details, 15);

							echo "<div class='side_block_content'>";
								echo "<div class='side_block_content_right'>"; 
									echo "<div class='small'><div class='medium_text bold'>{$guild_details['trend']}</div></div>";
								echo "</div>";
								echo "<div class='side_block_content_left'>"; 
									echo "<div class='small'><div class='side_title'>{$guild_details['name']}</div></div>";
								echo "</div>";
								echo "<div class='clear'></div>";
							echo "</div>";

							if ( $count == $GLOBALS[$module]['limit_trend'] ) break;
						}	
					}

					$count = 0;

					if ( count($new_array) > 0 ) {
						echo "<div class='block_subtitle'>{$GLOBALS[$module]['block_subtitle'][2]}</div>";

						foreach ( $new_array as $guild_id => $guild_details ) {
							$count++;

							$guild_details = generate_block_fields($active_point_system, "world", $guild_details, 15);

							echo "<div class='side_block_content'>";
								echo "<div class='side_block_content_right'>"; 
									echo "<div class='small'><div class='medium_text bold'>{$guild_details['trend']}</div></div>";
								echo "</div>";
								echo "<div class='side_block_content_left'>"; 
									echo "<div class='small'><div class='side_title'>{$guild_details['name']}</div></div>";
								echo "</div>";
								echo "<div class='clear'></div>";
							echo "</div>";

							if ( count($up_array) == 0 && count($down_array) == 0 ) {
								if ( $count == $GLOBALS[$module]['limit_total_trend'] ) break;
							} else {
								if ( $count == $GLOBALS[$module]['limit_trend'] ) break;
							}
						}	
					}
				} else {
					echo "<div class='side_block_content'>";
						echo "No trends currently active.";
						echo "<div class='clear'></div>";
					echo "</div>";						
				}
		echo "</div>";
	}

	function block_point_systems($module, $tier_details, $dungeon_details, $display_style) {
		$block_title = generate_block_title($GLOBALS[$module]['block_title'][1]);

		echo "<div class='side_block'>";
			echo "<div class='block_title'>$block_title</div>";
			echo "<form name='form_standings' method='POST' action='{$GLOBALS['page_ranking']}redirect'>";
				echo "<div class='side_block_content'>";
					foreach ( $GLOBALS['ranking']['type'] as $type => $title ) {
							echo "<div><input name='$type' class='data_button' type='submit' value='$title'></div>";
					}
				echo "</div>";

				if ( $tier_details == "alltime" ) { 
					echo "<input type='hidden' name='tier' value='alltime'>";

					if ( isset($_GET['type']) && strlen($_GET['type']) > 0 ) {
						echo "<input type='hidden' name='type' value='{$_GET['type']}'>";
					}
				} else {
					if ( isset($tier_details['title']) && strlen($tier_details['title']) > 0 ) {
						$tier = strtolower(str_replace(" ", "_", $tier_details['title']));

						echo "<input type='hidden' name='tier' value='$tier'>";
					}

					if ( isset($dungeon_details['name']) && strlen($dungeon_details['name']) > 0 ) {
						$dungeon_name = strtolower(str_replace(" ", "_", $dungeon_details['name']));
						$dungeon_name = str_replace("'", "", $dungeon_name);

						echo "<input type='hidden' name='dungeon' value='$dungeon_name'>";
					}

					if ( isset($_GET['type']) && strlen($_GET['type']) > 0 ) {
						echo "<input type='hidden' name='type' value='{$_GET['type']}'>";
					}
				}

				echo "<input type='hidden' name='system' value='system'>";
				echo "<input type='hidden' name='display' value='$display_style'>";
			echo "</form>";
		echo "</div>";	
	}

	function block_draw_views($module, $tier_details, $dungeon_details, $poll) {
		$block_title = generate_block_title($GLOBALS[$module]['block_title'][2]);

		echo "<div class='side_block'>";
			echo "<div class='block_title'>$block_title</div>";
			echo "<form method='POST' action='".$GLOBALS['page_ranking']."redirect'>";
				echo "<div class='side_block_content'>";
					foreach ( $GLOBALS['view_type'] as $index => $title ) {
						echo "<div><input name='$title' class='data_button' type='submit' value='View by ".ucfirst($title)."'></div>";
					}
			
					if ( $tier_details == "alltime" ) { 
						echo "<input type='hidden' name='tier' value='alltime'>";

						if ( isset($_GET['type']) && strlen($_GET['type']) > 0 ) {
							echo "<input type='hidden' name='type' value='{$_GET['type']}'>";
						}
					} else {
						if ( isset($tier_details['title']) && strlen($tier_details['title']) > 0 ) {
							$tier = strtolower(str_replace(" ", "_", $tier_details['title']));

							echo "<input type='hidden' name='tier' value='$tier'>";
						}

						if ( isset($dungeon_details['name']) && strlen($dungeon_details['name']) > 0 ) {
							$dungeon_name = strtolower(str_replace(" ", "_", $dungeon_details['name']));
							$dungeon_name = str_replace("'", "", $dungeon_name);

							echo "<input type='hidden' name='dungeon' value='$dungeon_name'>";
						}

						if ( isset($_GET['type']) && strlen($_GET['type']) > 0 ) {
							echo "<input type='hidden' name='type' value='{$_GET['type']}'>";
						}
					}

					echo "<input type='hidden' name='view' value='view'>";
					echo "<input type='hidden' name='poll' value='$poll'>";
				echo "</div>";
			echo "</form>";		
		echo "</div>";
	}

	function draw_ranked_points($module, $active_point_system, $guild_array, $type, $region_details, $server_details) {
		if ( $type == "server" ) unset($GLOBALS[$module]['header_guild']['Server']);

		$pane_name = "";
		if ( $type == "world" ) $pane_name 	= "";
		if ( $type == "region" ) $pane_name = strtolower($region_details['abbreviation']);
		if ( $type == "server" ) $pane_name = strtolower($server_details['name']);

		echo "<div class='clear'></div>";
		echo "<div id='pane_$pane_name'>";
			echo "<table class='table_data rankings'>";
				echo "<thead>";
					echo "<tr>";
						foreach ( $GLOBALS[$module]['header_guild'] as $key => $value ) {
							echo "<th>$key</th>";
						}

						foreach ( $GLOBALS[$module]['header_ranking'] as $key => $value ) {
							echo "<th>$key</th>";
						}
					echo "</tr>";
				echo "</thead>";
				echo "<tbody>";
					if ( count ($guild_array) > 0 ) {
						foreach ( $guild_array as $guild_id => $guild_details ) {
							if ( ($type == "server" && $guild_details['server'] == $server_details['name'] && $guild_details['region'] == $region_details['abbreviation']) 
								|| ($type == "region" && $guild_details['region'] == $region_details['abbreviation']) 
								|| ($type == "world") ) {
								echo "<tr>";
									$guild_details 	= generate_ranking_fields($active_point_system, $type, $guild_details, 20);
									$tooltip_table 	= $guild_details['tooltip_table'];
							
									foreach ( $GLOBALS[$module]['header_guild'] as $key => $value ) {
										$item = $guild_details[$value];

										if ( $guild_details['active'] == "Inactive" && $key == "Guild" ) { 
											echo "<td style=\"opacity:.30;\">$item</td>"; 
										} else {
											echo "<td class='activator tip-specific-class'>";
												echo "<span class='tip-item'>$item";
													if ( $value == "name" ) echo "<div class='tip tip-specific-class'>$tooltip_table</div>";
												echo "</span>";
											echo "</td>";
										}
									}

									foreach ( $GLOBALS[$module]['header_ranking'] as $key => $value ) {
										$item = $guild_details[$value];
										echo "<td class='rankings_data'>$item</td>";
									}
								echo "</tr>";
							}
						}		
					} else {
						echo "<tr>";
							echo "<td colspan='".(count($GLOBALS[$module]['header_guild']) + count($GLOBALS[$module]['header_ranking']))."' style='text-align:center'>Rankings have not been posted.</td>";
						echo "</tr>";						
					}
				echo "</tbody>";
			echo "</table>";	
		echo "</div>";
	}

	function draw_ranking_view($module, $active_point_system, $guild_array, $display_type) {
		if ( $display_type == "world" ) {
			echo "<div class='horizontal_separator'></div>";
			draw_message_banner_region_server("", "", "Rankings");
			draw_ranked_points($module, $active_point_system, $guild_array, "world", "", "");
		}

		if ( $display_type == "region" ) {
			foreach ( $GLOBALS['global_region_array'] as $region => $region_details ) {
				echo "<div class='horizontal_separator'></div>";
				draw_message_banner_region_server($region_details, "", "Rankings");
				draw_ranked_points($module, $active_point_system, $guild_array, "region", $region_details, "");
			}
		}

		if ( $display_type == "server" ) {
			foreach ( $GLOBALS['global_region_array'] as $region => $region_details ) {
				foreach ( $GLOBALS['global_server_array'] as $shard => $server_details ) {
					if ( $server_details['region'] != $region_details['abbreviation'] ) continue;

					echo "<div class='horizontal_separator'></div>";
					draw_message_banner_region_server($region_details, $server_details, "");
					draw_ranked_points($module, $active_point_system, $guild_array, "server", $region_details, $server_details);
				}
			}
		}
	}

	function create_ranking_overall_table($module, $active_point_system, $guild_array) {
		$table_array = array();

		if ( count($guild_array) > 0 ) {
			foreach ( $guild_array as $guild_id => $guild_details ) {
				$encounter_array 		= array();
				$dungeon_date_array 	= array(); 
				$dungeon_array 			= array();	
				$country 				= get_image_flag($guild_details['country']);
				$name 					= "$country <span>".generate_hyperlink_guild($guild_details['name'], $guild_details['server'], 0, $guild_details['faction'], "")."</span>";
				$output_table 			= "";

				if ( isset($guild_details['dungeon_details']) ) {
					foreach ( $guild_details['dungeon_details'] as $dungeon_id => $dungeon_details ) {
						if ( $GLOBALS['global_dungeon_array'][$dungeon_id]['dungeon_type'] == 0 && $guild_details['dungeon_details'][$dungeon_id]['complete'] > 0 ) {
							$dungeon_date_array[$dungeon_id] = $dungeon_details['recent_time'];
						}
					}
				}

				arsort($dungeon_date_array);

				$output_table .= "<div class='tooltip_title'>$name</div>";
				$output_table .= "<div class='clear'></div>";
				$output_table .= "<table class='table_data tooltip'>";
					$output_table .= "<thead>";
						$output_table .= "<tr>";
							foreach ( $GLOBALS[$module]['tooltip_alltime'] as $key => $value ) {
								$output_table .= "<th>$key</th>";
							}
						$output_table .= "</tr>";
					$output_table .= "</thead>";
					$output_table .= "<tbody>";
						if ( count($dungeon_date_array) > 0 ) {
							foreach ( $dungeon_date_array as $dungeon_id => $dungeon_details ) {
								$output_table .= "<tr>";
									foreach ( $GLOBALS[$module]['tooltip_alltime'] as $key => $value ) {
										$item = "--";

										if ( strpos($value, 'points') > -1 ) {
											$item = number_format($guild_details['dungeon_details'][$dungeon_id][$active_point_system]['points'], 2, ".", ",");
										} else {
											$item = $guild_details['dungeon_details'][$dungeon_id][$value];
										}

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
				
				$guild_array[$guild_id]['tooltip_table'] = $output_table;
			}	
		}

		return $guild_array;		
	}

	function create_ranking_overall_size_table($module, $active_point_system, $raid_size, $guild_array) {
		$table_array = array();

		if ( count($guild_array) > 0 ) {
			foreach ( $guild_array as $guild_id => $guild_details ) {
				$encounter_array 		= array();
				$dungeon_date_array 	= array(); 
				$dungeon_array 			= array();	
				$country 				= get_image_flag($guild_details['country']);
				$name 					= "$country <span>".generate_hyperlink_guild($guild_details['name'], $guild_details['server'], 0, $guild_details['faction'], "")."</span>";
				$output_table 			= "";

				if ( isset($guild_details['dungeon_details']) ) {
					foreach ( $guild_details['dungeon_details'] as $dungeon_id => $dungeon_details ) {
						if ( $GLOBALS['global_dungeon_array'][$dungeon_id]['dungeon_type'] == 0 && $GLOBALS['global_dungeon_array'][$dungeon_id]['players'] == $raid_size && $guild_details['dungeon_details'][$dungeon_id]['complete'] > 0 ) {
							$dungeon_date_array[$dungeon_id] = $dungeon_details['recent_time'];
						}
					}
				}

				arsort($dungeon_date_array);

				$output_table .= "<div class='tooltip_title'>$name</div>";
				$output_table .= "<div class='clear'></div>";
				$output_table .= "<table class='table_data tooltip'>";
					$output_table .= "<thead>";
						$output_table .= "<tr>";
							foreach ( $GLOBALS[$module]['tooltip_alltime'] as $key => $value ) {
								$output_table .= "<th>$key</th>";
							}
						$output_table .= "</tr>";
					$output_table .= "</thead>";
					$output_table .= "<tbody>";
						if ( count($dungeon_date_array) > 0 ) {
							foreach ( $dungeon_date_array as $dungeon_id => $dungeon_details ) {
								$output_table .= "<tr>";
									foreach ( $GLOBALS[$module]['tooltip_alltime'] as $key => $value ) {
										$item = "--";

										if ( strpos($value, 'points') > -1 ) {
											$item = number_format($guild_details['dungeon_details'][$dungeon_id][$active_point_system]['points'], 2, ".", ",");
										} else {
											$item = $guild_details['dungeon_details'][$dungeon_id][$value];
										}

										$output_table .= "<td>$item</td>";
									}
								$output_table .= "</tr>";	
							}
						} else {
							$output_table .= "<tr>";
								$output_table .= "<td colspan='7'>No raid progression data found.</td>";
							$output_table .= "</tr>";			
						}	
					$output_table .= "</tbody>";
				$output_table .= "</table>";
				
				$guild_array[$guild_id]['tooltip_table'] = $output_table;
			}	
		}

		return $guild_array;		
	}

	function create_ranking_tier_size_table($module, $active_point_system, $tier, $raid_size, $guild_array) {
		$table_array = array();

		if ( count($guild_array) > 0 ) {
			foreach ( $guild_array as $guild_id => $guild_details ) {
				$encounter_array 		= array();
				$encounter_date_array 	= array(); 
				$dungeon_array 			= array();
				$country 				= get_image_flag($guild_details['country']);
				$name 					= "$country <span>".generate_hyperlink_guild($guild_details['name'], $guild_details['server'], 0, $guild_details['faction'], "")."</span>";
				$output_table 			= "";

				if ( isset($guild_details['dungeon_details']) ) {
					foreach ( $guild_details['dungeon_details'] as $dungeon_id => $dungeon_details ) {
						$specific_dungeon_details = $GLOBALS['global_dungeon_array'][$dungeon_id];

						if ( $specific_dungeon_details['tier'] == $tier && $specific_dungeon_details['dungeon_type'] == 0 && $specific_dungeon_details['players'] == $raid_size ) {
							$encounter_date_array[$dungeon_id] = $dungeon_details['recent_time'];
						}
					}
				}

				arsort($encounter_date_array);

				$output_table .= "<div class='tooltip_title'>$name</div>";
				$output_table .= "<div class='clear'></div>";
				$output_table .= "<table class='table_data tooltip'>";
					$output_table .= "<thead>";
						$output_table .= "<tr>";
							foreach ( $GLOBALS[$module]['tooltip_tier'] as $key => $value ) {
								$output_table .= "<th>$key</th>";
							}
						$output_table .= "</tr>";
					$output_table .= "</thead>";
					$output_table .= "<tbody>";
						if ( count($encounter_date_array) > 0 ) {
							foreach ( $encounter_date_array as $dungeon_id => $dungeon_details ) {
								$output_table .= "<tr>";
									foreach ( $GLOBALS[$module]['tooltip_tier'] as $key => $value ) {
										$item = "--";

										if ( strpos($value, 'points') > -1 ) {
											$item = number_format($guild_details['dungeon_details'][$dungeon_id][$active_point_system]['points'], 2, ".", ",");
										} else {
											$item = $guild_details['dungeon_details'][$dungeon_id][$value];
										}

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

				$guild_array[$guild_id]['tooltip_table'] = $output_table;
			}	
		}

		return $guild_array;
	}

	function create_ranking_tier_table($module, $active_point_system, $tier, $guild_array) {
		$table_array = array();

		if ( count($guild_array) > 0 ) {
			foreach ( $guild_array as $guild_id => $guild_details ) {
				$encounter_array 		= array();
				$encounter_date_array 	= array();
				$dungeon_array 			= array();	
				$country 				= get_image_flag($guild_details['country']);
				$name 					= "$country <span>".generate_hyperlink_guild($guild_details['name'], $guild_details['server'], 0, $guild_details['faction'], "")."</span>";
				$output_table 			= "";

				if ( isset($guild_details['dungeon_details']) ) {
					foreach ( $guild_details['dungeon_details'] as $dungeon_id => $dungeon_details ) {
						$specific_dungeon_details = $GLOBALS['global_dungeon_array'][$dungeon_id];

						if ( $specific_dungeon_details['tier'] == $tier && $specific_dungeon_details['dungeon_type'] == 0 ) {
							$encounter_date_array[$dungeon_id] = $dungeon_details['recent_time'];
						}
					}
				}

				arsort($encounter_date_array);

				$output_table .= "<div class='tooltip_title'>$name</div>";
				$output_table .= "<div class='clear'></div>";
				$output_table .= "<table class='table_data tooltip'>";
					$output_table .= "<thead>";
						$output_table .= "<tr>";
							foreach ( $GLOBALS[$module]['tooltip_tier'] as $key => $value ) {
								$output_table .= "<th>$key</th>";
							}
						$output_table .= "</tr>";
					$output_table .= "</thead>";
					$output_table .= "<tbody>";
						if ( count($encounter_date_array) > 0 ) {
							foreach ( $encounter_date_array as $dungeon_id => $dungeon_details ) {
								$output_table .= "<tr>";
									foreach ( $GLOBALS[$module]['tooltip_tier'] as $key => $value ) {
										$item = "--";

										if ( strpos($value, 'points') > -1 ) {
											$item = number_format($guild_details['dungeon_details'][$dungeon_id][$active_point_system]['points'], 2, ".", ",");
										} else {
											$item = $guild_details['dungeon_details'][$dungeon_id][$value];
										}

										$output_table .= "<td>$item</td>";
									}
								$output_table .= "</tr>";
							}
						} else {
							$output_table .= "<tr>";
								$output_table .= "<td colspan='3'>No raid progression data found.</td>";
							$output_table .= "</tr>";			
						}	
					$output_table .= "</tbody>";
				$output_table .= "</table>";

				$guild_array[$guild_id]['tooltip_table'] = $output_table;
			}	
		}

		return $guild_array;
	}

	function create_ranking_dungeon_table($module, $active_point_system, $dungeon_id, $guild_array) {
		$table_array = array();

		if ( count($guild_array) > 0 ) {
			foreach ( $guild_array as $guild_id => $guild_details ) {
				$encounter_array 		= array();
				$encounter_date_array 	= array(); 
				$dungeon_array 			= array();
				$country 				= get_image_flag($guild_details['country']);
				$name 					= "$country <span>".generate_hyperlink_guild($guild_details['name'], $guild_details['server'], 0, $guild_details['faction'], "")."</span>";
				$output_table 			= "";

				if ( isset($guild_details['direct_encounter_details']) ) {
					foreach ( $guild_details['direct_encounter_details'] as $encounter_id => $encounter_details ) {
						if ( $encounter_details['dungeon_id'] == $dungeon_id && $encounter_details['mob_type'] == 0 ) {
							$encounter_date_array[$encounter_id] = $encounter_details['strtotime'];
						}
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

										if ( strpos($value, 'points') > -1 ) $item = number_format($guild_details['direct_encounter_details'][$encounter_id][$active_point_system]['points'], 2, ".", ",");

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

				//echo "Table code: $output_table<br>";
				$guild_array[$guild_id]['tooltip_table'] = $output_table;
			}	
		}

		return $guild_array;
	}

	function get_overall_rankings($module, $unsort_guild_array, $active_point_system) {
		$guild_array = $temp_array = array();

		foreach ( $unsort_guild_array as $guild_id => $guild_details ) {
			$guild_details = get_guild_details($guild_id);

			if ( $_SESSION['active'] == 1 ) {
				if ( $guild_details['type'] == 1 ) { continue; }
				if ( $guild_details['active'] == 0 ) { continue; }
			} else {
				$guild_details['overall_details'][$active_point_system]['world']['rank'] = $guild_details['overall_details'][$active_point_system]['world']['legacy_rank'];
				$guild_details['overall_details'][$active_point_system]['region']['rank'] = $guild_details['overall_details'][$active_point_system]['region']['legacy_rank'];
				$guild_details['overall_details'][$active_point_system]['server']['rank'] = $guild_details['overall_details'][$active_point_system]['server']['legacy_rank'];
			}

			if ( $guild_details['overall_details'][$active_point_system]['points'] == 0 ) continue;

			foreach ( $guild_details as $key => $value ) { $unsort_guild_array[$guild_id][$key] = $value; }
			foreach ( $guild_details['overall_details'] as $key => $value ) { $unsort_guild_array[$guild_id][$key] = $value; }

			$temp_array[$guild_id] = $guild_details['overall_details'][$active_point_system]['points'];
		}

		if ( count($temp_array) > 0 ) {
			arsort($temp_array);

			$diff = "--";
			foreach ($temp_array as $guild_id => $complete) {
				if ( $diff == "--" ) { 
					$unsort_guild_array[$guild_id][$active_point_system]['diff'] = "--";
					$diff = $complete; 
				} else { 
					$unsort_guild_array[$guild_id][$active_point_system]['diff'] = "-".($diff - $complete); 
				}

				$guild_array[$guild_id] = $unsort_guild_array[$guild_id];
			}
		}

		$guild_array = create_ranking_overall_table($module, $active_point_system, $guild_array);

		return $guild_array;		
	}

	function get_overall_size_rankings($module, $unsort_guild_array, $active_point_system, $raid_size) {
		$guild_array = $temp_array = array();

		foreach ( $unsort_guild_array as $guild_id => $guild_details ) {
			$guild_details = get_guild_details($guild_id);

			if ( $_SESSION['active'] == 1 ) {
				if ( $guild_details['type'] == 1 ) { continue; }
				if ( $guild_details['active'] == 0 ) { continue; }
			} else {
				$guild_details['direct_raid_size_details'][$raid_size][$active_point_system]['world']['rank'] = $guild_details['direct_raid_size_details'][$raid_size][$active_point_system]['world']['legacy_rank'];
				$guild_details['direct_raid_size_details'][$raid_size][$active_point_system]['region']['rank'] = $guild_details['direct_raid_size_details'][$raid_size][$active_point_system]['region']['legacy_rank'];
				$guild_details['direct_raid_size_details'][$raid_size][$active_point_system]['server']['rank'] = $guild_details['direct_raid_size_details'][$raid_size][$active_point_system]['server']['legacy_rank'];
			}

			if ( $guild_details['direct_raid_size_details'][$raid_size][$active_point_system]['points'] == 0 ) continue;

			foreach ( $guild_details as $key => $value ) { $unsort_guild_array[$guild_id][$key] = $value; }
			foreach ( $guild_details['direct_raid_size_details'][$raid_size] as $key => $value ) { $unsort_guild_array[$guild_id][$key] = $value; }

			$temp_array[$guild_id] = $guild_details['direct_raid_size_details'][$raid_size][$active_point_system]['points'];
		}

		if ( count($temp_array) > 0 ) {
			arsort($temp_array);

			$diff = "--";
			foreach ($temp_array as $guild_id => $complete) {
				if ( $diff == "--" ) { 
					$unsort_guild_array[$guild_id][$active_point_system]['diff'] = "--";
					$diff = $complete; 
				} else { 
					$unsort_guild_array[$guild_id][$active_point_system]['diff'] = "-".($diff - $complete); 
				}

				$guild_array[$guild_id] = $unsort_guild_array[$guild_id];
			}
		}

		$guild_array = create_ranking_overall_size_table($module, $active_point_system, $raid_size, $guild_array);

		return $guild_array;
	}

	function get_tier_rankings($module, $unsort_guild_array, $active_point_system, $tier_details) {
		$guild_array = $temp_array = array();
		$tier = $tier_details['tier'];

		foreach ( $unsort_guild_array as $guild_id => $guild_details ) {
			$guild_details = get_guild_details($guild_id);

			if ( $_SESSION['active'] == 1 ) {
				if ( $guild_details['type'] == 1 ) { continue; }
				if ( $guild_details['active'] == 0 ) { continue; }
			} else {
				$guild_details['tier_details'][$tier][$active_point_system]['world']['rank'] = $guild_details['tier_details'][$tier][$active_point_system]['world']['legacy_rank'];
				$guild_details['tier_details'][$tier][$active_point_system]['region']['rank'] = $guild_details['tier_details'][$tier][$active_point_system]['region']['legacy_rank'];
				$guild_details['tier_details'][$tier][$active_point_system]['server']['rank'] = $guild_details['tier_details'][$tier][$active_point_system]['server']['legacy_rank'];
			}

			if ( $guild_details['tier_details'][$tier][$active_point_system]['points'] == 0 ) continue;

			foreach ( $guild_details as $key => $value ) { $unsort_guild_array[$guild_id][$key] = $value; }
			foreach ( $guild_details['tier_details'][$tier] as $key => $value ) { $unsort_guild_array[$guild_id][$key] = $value; }


			$unsort_guild_array[$guild_id]['standing'] = $guild_details['tier_details'][$tier]['progression_overall'];
			
			$temp_array[$guild_id] = $guild_details['tier_details'][$tier][$active_point_system]['points'];
		}

		if ( count($temp_array) > 0 ) {
			arsort($temp_array);

			$diff = "--";
			foreach ($temp_array as $guild_id => $complete) {
				if ( $diff == "--" ) { 
					$unsort_guild_array[$guild_id][$active_point_system]['diff'] = "--";
					$diff = $complete; 
				} else { 
					$unsort_guild_array[$guild_id][$active_point_system]['diff'] = "-".($diff - $complete); 
				}

				$guild_array[$guild_id] = $unsort_guild_array[$guild_id];
			}
		}
		
		$guild_array = create_ranking_tier_table($module, $active_point_system, $tier, $guild_array);

		return $guild_array;
	}

	function get_tier_size_rankings($module, $unsort_guild_array, $active_point_system, $tier_details, $raid_size) {
		$guild_array = $temp_array = array();
		$tier = $tier_details['tier'];

		foreach ( $unsort_guild_array as $guild_id => $guild_details ) {
			$guild_details = get_guild_details($guild_id);

			if ( $_SESSION['active'] == 1 ) {
				if ( $guild_details['type'] == 1 ) { continue; }
				if ( $guild_details['active'] == 0 ) { continue; }
			} else {
				$guild_details['raid_size_details'][$tier][$raid_size][$active_point_system]['world']['rank'] = $guild_details['raid_size_details'][$tier][$raid_size][$active_point_system]['world']['legacy_rank'];
				$guild_details['raid_size_details'][$tier][$raid_size][$active_point_system]['region']['rank'] = $guild_details['raid_size_details'][$tier][$raid_size][$active_point_system]['region']['legacy_rank'];
				$guild_details['raid_size_details'][$tier][$raid_size][$active_point_system]['server']['rank'] = $guild_details['raid_size_details'][$tier][$raid_size][$active_point_system]['server']['legacy_rank'];
			}

			if ( $guild_details['raid_size_details'][$tier][$raid_size][$active_point_system]['points'] == 0 ) continue;

			foreach ( $guild_details as $key => $value ) { $unsort_guild_array[$guild_id][$key] = $value; }
			foreach ( $guild_details['raid_size_details'][$tier][$raid_size] as $key => $value ) { $unsort_guild_array[$guild_id][$key] = $value; }

			$unsort_guild_array[$guild_id]['standing'] = $guild_details['tier_details'][$tier]['progression_size'][$raid_size];
			
			$temp_array[$guild_id] = $guild_details['raid_size_details'][$tier][$raid_size][$active_point_system]['points']; 
		}

		if ( count($temp_array) > 0 ) {
			arsort($temp_array);

			$diff = "--";
			foreach ($temp_array as $guild_id => $complete) {
				if ( $diff == "--" ) { 
					$unsort_guild_array[$guild_id][$active_point_system]['diff'] = "--";
					$diff = $complete; 
				} else { 
					$unsort_guild_array[$guild_id][$active_point_system]['diff'] = "-".($diff - $complete); 
				}

				$guild_array[$guild_id] = $unsort_guild_array[$guild_id];
			}
		}

		$guild_array = create_ranking_tier_size_table($module, $active_point_system, $tier_details['tier'], $raid_size, $guild_array);

		return $guild_array;
	}

	function get_dungeon_rankings($module, $unsort_guild_array, $active_point_system, $tier_details, $dungeon_details) {
		$guild_array = $temp_array = array();
		$dungeon_id = $dungeon_details['dungeon_id'];

		foreach ( $unsort_guild_array as $guild_id => $guild_details ) {
			$guild_details = get_guild_details($guild_id);

			if ( $_SESSION['active'] == 1 ) {
				if ( $guild_details['type'] == 1 ) { continue; }
				if ( $guild_details['active'] == 0 ) { continue; }
			} else {
				$guild_details['dungeon_details'][$dungeon_id][$active_point_system]['world']['rank'] = $guild_details['dungeon_details'][$dungeon_id][$active_point_system]['world']['legacy_rank'];
				$guild_details['dungeon_details'][$dungeon_id][$active_point_system]['region']['rank'] = $guild_details['dungeon_details'][$dungeon_id][$active_point_system]['region']['legacy_rank'];
				$guild_details['dungeon_details'][$dungeon_id][$active_point_system]['server']['rank'] = $guild_details['dungeon_details'][$dungeon_id][$active_point_system]['server']['legacy_rank'];
			}

			if ( $guild_details['dungeon_details'][$dungeon_id][$active_point_system]['points'] == 0 ) continue;

			foreach ( $guild_details as $key => $value ) { $unsort_guild_array[$guild_id][$key] = $value; }
			foreach ( $guild_details['dungeon_details'][$dungeon_id] as $key => $value ) { $unsort_guild_array[$guild_id][$key] = $value; }

			$temp_array[$guild_id] = $guild_details['dungeon_details'][$dungeon_id][$active_point_system]['points']; 
		}

		if ( count($temp_array) > 0 ) {
			arsort($temp_array);

			$diff = "--";
			foreach ($temp_array as $guild_id => $complete) {
				if ( $diff == "--" ) { 
					$unsort_guild_array[$guild_id][$active_point_system]['diff'] = "--";
					$diff = $complete; 
				} else { 
					$unsort_guild_array[$guild_id][$active_point_system]['diff'] = "-".($diff - $complete); 
				}

				$guild_array[$guild_id] = $unsort_guild_array[$guild_id];
			}
		}

		$guild_array = create_ranking_dungeon_table($module, $active_point_system, $dungeon_id, $guild_array);

		return $guild_array;
	}

	function get_guild_trend($display_style, $active_point_system, $current_tier, $dungeon_details, $guild_array, $raid_size, $type) {
		$trend_array = $temp_array = array();
		$trend_size = $points = 0;
		$trend = "";

		foreach ( $guild_array as $guild_id => $guild_details ) {
			if ( isset($dungeon_details['dungeon_id']) ) {
				$dungeon_id = $dungeon_details['dungeon_id'];

				$trend 	= $guild_details['dungeon_details'][$dungeon_id][$active_point_system][$display_style]['trend'];
				$points = $guild_details['dungeon_details'][$dungeon_id][$active_point_system]['points'];
			} else if ( (isset($current_tier) && $current_tier == "alltime") && isset($raid_size) && strlen($raid_size) > 0 ) {
				$trend 	= $guild_details['direct_raid_size_details'][$raid_size][$active_point_system][$display_style]['trend'];
				$points = $guild_details['direct_raid_size_details'][$raid_size][$active_point_system]['points'];
			} else if ( isset($current_tier) && $current_tier == "alltime" ) {
				$trend 	= $guild_details['overall_details'][$active_point_system][$display_style]['trend'];
				$points = $guild_details['overall_details'][$active_point_system]['points'];
			}  else {
				$trend 	= $guild_details['tier_details'][$current_tier][$active_point_system][$display_style]['trend'];
				$points = $guild_details['tier_details'][$current_tier][$active_point_system]['points'];
			}

			$guild_array[$guild_id]['trend'] 	= $trend;
			$guild_array[$guild_id]['points'] 	= $points;
			
			if ( $type == 0 && $trend > 0 ) { $temp_array[$guild_id] = $trend; }
			if ( $type == 1 && $trend < 0 ) { $temp_array[$guild_id] = $trend; }
			if ( $type == 2 && $trend == "NEW" ) { $temp_array[$guild_id] = $points; }
		}

		if ( count($temp_array) > 0 ) {
			if ( $type == 0 ) arsort($temp_array);
			if ( $type == 1 ) asort($temp_array);
			if ( $type == 2 ) arsort($temp_array);

			foreach ($temp_array as $guild_id => $complete) {
				$trend_array[$guild_id] = $guild_array[$guild_id];
			}
		}

		return $trend_array;
	}	
?>