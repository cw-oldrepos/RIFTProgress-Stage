<?php
	$ROOT 			= dirname(dirname(dirname(__FILE__)));
	include_once 	"{$ROOT}/configuration.php";
	
	$module = "standing";
	if ( !isset($GLOBALS[$module]['set']) || $GLOBALS[$module]['set'] == 0 ) { draw_disabled_module(); exit; } 

	//***************DECLARING VARIABLES**************
	$dungeon_details 	= array();
	$encounter_details 	= array();
	$order_region_array = array();
	$order_server_array = array();
	$display_type 		= array();
	$current_tier 		= array();
	$header_text 		= array();
	$raid_size 			= "";
	$arg_display 		= "";
	$arg_tier 			= "";
	$arg_size 			= "";
	$arg_dungeon 		= "";
	$arg_encounter 		= "";
	$total_world 		= 0;
	$standings_type 	= 0;
	//***************DECLARING VARIABLES**************

	if ( isset($_POST) && count($_POST) > 0 ) {
		$arg_display 	= ( isset($_POST['display']) && strlen($_POST['display']) > 0 ? $_POST['display'] : "" );
		$arg_tier 		= ( isset($_POST['tier']) && strlen($_POST['tier']) > 0 ? $_POST['tier'] : "" );
		$arg_size 		= ( isset($_POST['type']) && strlen($_POST['type']) > 0 ? $_POST['type'] : "" );
		$arg_dungeon 	= ( isset($_POST['dungeon']) && strlen($_POST['dungeon']) > 0 ? $_POST['dungeon'] : "" );
		$arg_encounter 	= ( isset($_POST['encounter']) && strlen($_POST['encounter']) > 0 ? $_POST['encounter'] : "" );
	} else {
		$arg_display 	= ( isset($_GET['display']) && strlen($_GET['display']) > 0 ? $_GET['display'] : "" );
		$arg_tier 		= ( isset($_GET['tier']) && strlen($_GET['tier']) > 0 ? $_GET['tier'] : "" );
		$arg_size 		= ( isset($_GET['type']) && strlen($_GET['type']) > 0 ? $_GET['type'] : "" );
		$arg_dungeon 	= ( isset($_GET['dungeon']) && strlen($_GET['dungeon']) > 0 ? $_GET['dungeon'] : "" );
		$arg_encounter 	= ( isset($_GET['encounter']) && strlen($_GET['encounter']) > 0 ? $_GET['encounter'] : "" );		
	}
		//print_r($_GET);
	// Get Display (World/Region/Server)
	foreach ( $GLOBALS['view_type'] as $index => $view ) {
		if ( $arg_display == $view ) {
			$display_type = $view;
		}
	}

	// Display Fail-safe
	if ( $display_type == "" ) $display_type = "world";
	
	// Get Tier
	if ( $arg_tier == "alltime" ) {
		$current_tier 	= "alltime";
		$tier_details   = "alltime";
		$header_text 	= "All-Time ".ucfirst($display_type)." Standings";
		$standings_type = 0;
	} else {
		$current_tier = strtolower(str_replace("_", " ", $arg_tier));
		$tier_details = get_tier_details_by_name($current_tier);
		
		if ( (!isset($tier_details) || count($tier_details) == 0) && $tier_details != "alltime" ) {
			$current_tier = $GLOBALS['latest_tier'];
			$tier_details = $GLOBALS['global_tier_array'][$current_tier];
		}

		$header_text 	= $tier_details['title']." ".ucfirst($display_type)." Standings";
		$standings_type = 1;
	}

	// Get Raid Size
	if ( strlen($arg_size) > 0 ) {
		if ( isset($GLOBALS['global_raid_size_array'][$arg_size]) ) {
			$raid_size = $arg_size;
			if ( $current_tier == "alltime" ) {
				$header_text 	= "All-Time ".$raid_size."-Man ".ucfirst($display_type)." Standings";
				$standings_type = 2;
			} else if ( isset($tier_details) ) {
				$header_text 	= $tier_details['title']." ".$raid_size."-Man ".ucfirst($display_type)." Standings";
				$standings_type = 3;
			}
		}
	}

	// Get Dungeon
	if ( strlen($arg_dungeon) > 0 ) {
		$arg_dungeon 		= strtolower(str_replace("_", " ", $arg_dungeon));
		$dungeon_details 	= get_dungeon_details_by_name($arg_dungeon);

		if ( isset($dungeon_details) ) {
			$header_text 	= $dungeon_details['name']." ".ucfirst($display_type)." Standings";
			$standings_type = 4;
		}
	}

	// Get Encounter
	if ( strlen($arg_encounter) > 0 ) {
		$arg_encounter 		= strtolower(str_replace("_", " ", $arg_encounter));
		$encounter_details 	= get_encounter_details_by_name($arg_encounter);

		if ( isset($encounter_details) ) {
			$header_text 			= $encounter_details['encounter_name']." ".ucfirst($display_type)." Standings";
			$encounter_details 		= get_encounter_details($encounter_details['encounter_id']);
			$standings_type 		= 5;
		}
	}

	if ( $standings_type == 0 )	{ $guild_array = get_overall_standings($GLOBALS['global_guild_array']); } // All-Time
	if ( $standings_type == 1 ) { $guild_array = get_tier_standings($GLOBALS['global_guild_array'], $tier_details); } // Tier
	if ( $standings_type == 2 ) { $guild_array = get_overall_size_standings($GLOBALS['global_guild_array'], $raid_size); } // All-Time Size
	if ( $standings_type == 3 ) { $guild_array = get_tier_size_standings($GLOBALS['global_guild_array'], $tier_details, $raid_size); } // Tier Size
	if ( $standings_type == 4 ) { $guild_array = get_dungeon_standings($GLOBALS['global_guild_array'], $dungeon_details); } // Dungeon
	if ( $standings_type == 5 ) { $guild_array = get_encounter_standings($GLOBALS['global_guild_array'], $encounter_details); } // Encounter

	$order_server_array = get_server_strength($tier_details, $order_server_array, $guild_array);
	$order_region_array = get_region_strength($tier_details, $order_region_array, $guild_array);
	$total_world 		= get_total_world($tier_details, $guild_array, $order_region_array, $order_server_array);

	function block_draw_encounter_details($module, $encounter_details, $dungeon_details) {
		$block_title = generate_block_title($GLOBALS[$module]['block_title'][0]);

		echo "<div class='side_block'>";
			echo "<div class='block_title'>$block_title</div>";

			foreach ($GLOBALS[$module]['block_encounter'] as $header => $value) {
				$item = $encounter_details[$value];

				echo "<div class='side_block_content'>";					
					if ( $value == "first_kill_guild" || $value == "recent_kill_guild" ) {
						if ( isset($item) && strlen($item) > 0 && $item != "--" ) {
							$guild_details 		= $GLOBALS['global_guild_array'][$item];
							$name 				= $guild_details['name'];
							$faction 			= strtolower($guild_details['faction']);
							$country 			= get_image_flag($guild_details['country']);
							$name 				= short_name($name, 12);
							$name  				= "$country <span>".generate_hyperlink_guild($guild_details['name'], $guild_details['server'], 0, $faction, 10)."</span>";

							echo "<div class='side_block_content_left'>"; 
								echo "<div class='small'><div class='side_title'>$header</div></div>";
							echo "</div>";
							echo "<div class='side_block_content_right'>"; 
								echo "<div class='small'><div class='small_text'>$name</div></div>";
							echo "</div>";
						} else {
							echo "<div class='side_block_content_left'>"; 
								echo "<div class='small'><div class='side_title'>$header</div></div>";
							echo "</div>";
							echo "<div class='side_block_content_right'>"; 
								echo "<div class='small'><div class='medium_text'>N/A</div></div>";
							echo "</div>";
						}
					} else if ( $value == "num_of_kills" ) { 
						foreach ( $encounter_details[$value] as $region => $region_kills ) {
							if ( $region == "total" ) {
								echo "<div class='side_block_content_left'>"; 
									echo "<div class='small'><div class='side_title'>WW # of Kills</div></div>";
								echo "</div>";
							} else {
								echo "<div class='side_block_content_left'>"; 
									echo "<div class='small'><div class='side_title'>$region # of Kills</div></div>";
								echo "</div>";
							}
							
							echo "<div class='side_block_content_right'>"; 
								echo "<div class='small'><div class='medium_text'>$region_kills</div></div>";
							echo "</div>";
							echo "<div class='clear'></div>";
						}
					} else {
							echo "<div class='side_block_content_right'>"; 
								echo "<div class='small'><div class='medium_text'>$item</div></div>";
							echo "</div>";
							echo "<div class='side_block_content_left'>"; 
								echo "<div class='small'><div class='side_title'>$header</div></div>";
							echo "</div>";
							echo "<div class='clear'></div>";
					}
					echo "<div class='clear'></div>";
				echo "</div>";
			}
		echo "</div>";
	}

	function block_draw_strength($module, $order_server_array, $order_region_array, $total_world) {
		$block_title = generate_block_title($GLOBALS[$module]['block_title'][1]);

		echo "<div class='side_block'>";
			echo "<div class='block_title'>$block_title</div>";
			echo "<div class='block_subtitle'>{$GLOBALS[$module]['block_subtitle'][0]}</div>";

			foreach (  $order_server_array as $server => $server_average ) {
				$server_details 	= $GLOBALS['global_server_array'][$server];
				$country 			= $server_details['country'];
				$region 			= $server_details['region'];

				if ( !isset($GLOBALS['global_region_array'][$region]['count']) || $GLOBALS['global_region_array'][$region]['count'] == 0 ) $GLOBALS['global_region_array'][$region]['count'] = 1;

				$server_average 	= number_format($server_average, 2, ".", ",");
				$region_average 	= number_format(($GLOBALS['global_region_array'][$region]['score'] / $GLOBALS['global_region_array'][$region]['count']), 2, ".",",");
				$server_diff	 	= number_format(($server_average - $total_world), 2, ".",",");
				$flag 				= get_image_flag($country);
				$server 			= "$flag <span>".generate_hyperlink_server($server, "world", 0, "", "")."</span>";

				if ( $server_average == 0.00 ) 	$server_diff = "0.00";
				if ( $server_diff > 0.00 ) 		$server_diff = "+$server_diff";

				echo "<div class='side_block_content'>";
					echo "<div class='side_block_content_right'>"; 
						echo "<div class='small'><div class='medium_text'>$server_average ($server_diff)</div></div>";
					echo "</div>";
					echo "<div class='side_block_content_left'>"; 
						echo "<div class='small'><div class='side_title'>$server</div></div>";
					echo "</div>";
					echo "<div class='clear'></div>";
				echo "</div>";
			}	

			echo "<div class='block_subtitle'>{$GLOBALS[$module]['block_subtitle'][1]}</div>";

			foreach ( $order_region_array as $region => $region_average ) {
				$region_details 	= $GLOBALS['global_region_array'][$region];
				$name 				= $region_details['full'];
				$abbrev 			= $region_details['abbreviation'];
				$region_average 	= number_format($region_average, 2, ".", ",");
				$region_diff		= number_format(($region_average - $total_world), 2, ".",",");
				$flag 				= get_image_flag($abbrev);
				$region 			= "$flag <span>$name</span>";

				if ( $region_average == 0.00 ) 	$region_diff = "0.00";
				if ( $region_diff > 0.00 ) 		$region_diff = "+$region_diff";

				echo "<div class='side_block_content'>";
					echo "<div class='side_block_content_right'>"; 
						echo "<div class='small'><div class='medium_text'>$region_average ($region_diff)</div></div>";
					echo "</div>";
					echo "<div class='side_block_content_left'>"; 
						echo "<div class='small'><div class='side_title'>$region</div></div>";
					echo "</div>";
					echo "<div class='clear'></div>";
				echo "</div>";
			}
			echo "<div class='clear'></div>";
		echo "</div>";		
	}

	function block_draw_views($module, $tier_details, $dungeon_details, $encounter_details) {
		$block_title = generate_block_title($GLOBALS[$module]['block_title'][2]);

		echo "<div class='side_block'>";
			echo "<div class='block_title'>$block_title</div>";
			echo "<form name='form_standings' method='POST' action='{$GLOBALS['page_standing']}redirect'>";
				echo "<div class='side_block_content'>";
					echo "<div><input class='spreadsheet_popup_button data_button' type='submit' value='View as Spreadsheet'></div>";

					foreach ( $GLOBALS['view_type'] as $index => $title ) {
						echo "<div><input name='$title' class='data_button' type='submit' value='View by ".ucfirst($title)."'></div>";				
					}

					if ( $tier_details == "alltime" ) {
						echo "<input type='hidden' name='tier' value='alltime'>";

						if ( isset($_GET['type']) && strlen($_GET['type']) > 0 ) {
							echo "<input type='hidden' name='type' value='".$_GET['type']."'>";
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

						if ( isset($encounter_details['name']) && strlen($encounter_details['name']) > 0 ) {
							$mob = strtolower(str_replace(" ", "_", $encounter_details['name']));
							
							echo "<input type='hidden' name='mob' value='$mob'>";
						}

						if ( isset($_GET['type']) && strlen($_GET['type']) > 0 ) {
							echo "<input type='hidden' name='type' value='".$_GET['type']."'>";
						}
					}
				echo "</div>";
			echo "</form>";
			echo "<div class='clear'></div>";
		echo "</div>";				
	}

	function draw_tier_guild($module, $guild_array, $type, $region_details, $server_details, $tooltip_table) {
		if ( $type == "server" ) unset($GLOBALS[$module]['header_guild']['Server']);

		$pane_name = "";
		if ( $type == "world" ) $pane_name 	= "";
		if ( $type == "region" ) $pane_name = strtolower($region_details['abbreviation']);
		if ( $type == "server" ) $pane_name = strtolower($server_details['name']);	

		echo "<div class='clear'></div>";
		echo "<div id='pane_$pane_name'>";
			echo "<table class='table_data standings_tier'>";
				echo "<thead>";
					echo "<tr>";
						foreach ( $GLOBALS[$module]['header_guild'] as $key => $value ) {
							echo "<th>$key</th>";
						}

						foreach ( $GLOBALS[$module]['header_standing'] as $key => $value ) {
							echo "<th>$key</th>";
						}
					echo "</tr>";
				echo "</thead>";
				echo "<tbody>";
					if ( count($guild_array) > 0 ) {
						foreach ( $guild_array as $guild_id => $guild_details ) {
							if ( ($type == "server" && $guild_details['server'] == $server_details['name'] && $guild_details['region'] == $region_details['abbreviation']) 
								|| ($type == "region" && $guild_details['region'] == $region_details['abbreviation']) 
								|| ($type == "world") ) {
								echo "<tr>";
									$guild_details = generate_table_fields($guild_details, 20);

									foreach ( $GLOBALS[$module]['header_guild'] as $key => $value ) {
										$item = $guild_details[$value];

										echo "<td class='activator tip-specific-class'>";
											echo "<span class='tip-item'>$item";
												if ( $value == "name" ) { echo "<div class='tip tip-specific-class'>$tooltip_table[$guild_id]</div>"; }
											echo "</span>";
										echo "</td>";							
									}
								
									foreach ( $GLOBALS[$module]['header_standing'] as $key => $value ) {
										$item = $guild_details[$value];

										echo "<td class='standings_tier_data'>$item</td>";
									}	
								echo "</tr>";										
							}
						}
					} else {
						echo "<td colspan='".count($GLOBALS[$module]['header_standing'])."' class='standings_tier_data'>No guild data found.</td>";
					}
				echo "</tbody>";						
			echo "</table>";
		echo "</div>";
	}

	function draw_dungeon_guild($module, $dungeon_details, $guild_array, $type, $region_details, $server_details, $tooltip_table) {
		if ( $type == "server" ) unset($GLOBALS[$module]['header_guild']['Server']);

		$pane_name = "";
		if ( $type == "world" ) $pane_name 	= "";
		if ( $type == "region" ) $pane_name = strtolower($region_details['abbreviation']);
		if ( $type == "server" ) $pane_name = strtolower($server_details['name']);

		$num_of_encounters 			= 0;
		$dungeon_encounter_array 	= array();

		echo "<div class='spreadsheet_content'>";
			echo "<div class='popup_title'>". $dungeon_details['name']. " Standings Spreadsheet View</div>";
			echo "<a class='spreadsheet_popup_close' href='#'>Close</a>";
			echo "<div style=\"position:absolute !important; float:left !important; width:auto !important;\">";
				echo "<table class='table_data standings_tier spreadsheet'\">";
					echo "<thead>";
						echo "<tr>";
							foreach ( $GLOBALS[$module]['header_guild'] as $key => $value ) {
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
								$active_rank = 1;
								$legacy_rank = 1;
								foreach ( $guild_array as $guild_id => $guild_details ) {
									echo "<tr>";
										$guild_details 			= generate_table_fields($guild_details, 20);
										$guild_details['rank'] 	= $legacy_rank;
										//if ( $guild_details['active'] == "Inactive" ) { $guild_details['rank'] 	= $legacy_rank . "<b>|</b>--"; }

										foreach ( $GLOBALS[$module]['header_guild'] as $key => $value ) {
											$item = $guild_details[$value];

											if ( $guild_details['active'] == "Inactive" && $key == "Guild" ) { 
												echo "<td style=\"opacity:.30;\">$item</td>"; 
											} else {
												echo "<td>$item</td>";
											}							
										}

										foreach ( $dungeon_encounter_array as $encounter_id => $encounter_details ) {
											if ( isset($guild_details['direct_encounter_details'][$encounter_id]) ) {
												$hyperlink 	= $GLOBALS['fold_guild_screenshots']."/".$guild_details['direct_encounter_details'][$encounter_id]['screenshot'];
												$text 		= $guild_details['direct_encounter_details'][$encounter_id]['datetime'];

												if ( file_exists($_SERVER['DOCUMENT_ROOT'].$hyperlink) ) { $text = "<a href='$hyperlink' rel='lightbox['kill_shots']'>$text</a>"; }

												echo "<td>$text</td>";
											} else {
												echo "<td>--</td>";
											}
										}
									echo "</tr>";

									$legacy_rank++;
									if ( $guild_details['active'] == "Active" ) { $active_rank++; }
								}
							} else {
								echo "<td colspan='".($num_of_encounters+1)."' class='standings_tier_data'>No guild data found.</td>";
							}		
					echo "</tbody>";
				echo "</table>";
			echo "</div>";
		echo "</div>";

		echo "<div class='clear'></div>";
		echo "<div id='pane_$pane_name'>";
			echo "<table class='table_data standings_tier'>";
				echo "<thead>";
					echo "<tr>";
						foreach ( $GLOBALS[$module]['header_guild'] as $key => $value ) {
							echo "<th>$key</th>";
						}

						foreach ( $GLOBALS[$module]['header_standing'] as $key => $value ) {
							echo "<th>$key</th>";
						}
					echo "</tr>";
				echo "</thead>";
				echo "<tbody>";
					if ( count($guild_array) > 0 ) {
						$active_rank = 1;
						$legacy_rank = 1;
						foreach ( $guild_array as $guild_id => $guild_details ) {
							if ( ($type == "server" && $guild_details['server'] == $server_details['name'] && $guild_details['region'] == $region_details['abbreviation']) 
								|| ($type == "region" && $guild_details['region'] == $region_details['abbreviation']) 
								|| ($type == "world") ) {
								echo "<tr>";
									$guild_details 			= generate_table_fields($guild_details, 20);
									$guild_details['rank'] 	= $legacy_rank;
									//if ( $guild_details['active'] == "Inactive" ) { $guild_details['rank'] 	= $legacy_rank . "<b>|</b>--"; }

									foreach ( $GLOBALS[$module]['header_guild'] as $key => $value ) {
										$item = $guild_details[$value];

										if ( $guild_details['active'] == "Inactive" && $key == "Guild" ) { 
											echo "<td style=\"opacity:.30;\">$item</td>"; 
										} else {
											echo "<td class='activator tip-specific-class'>";
												echo "<span class='tip-item'>$item";
													if ( $value == "name" ) { 
														echo "<div class='tip tip-specific-class'>$tooltip_table[$guild_id]</div>"; 
													}
												echo "</span>";
											echo "</td>";
										}							
									}
								
									foreach ( $GLOBALS[$module]['header_standing'] as $key => $value ) {
										$item = $guild_details[$value];

										echo "<td class='standings_tier_data'>$item</td>";
									}	
								echo "</tr>";

								$legacy_rank++;
								if ( $guild_details['active'] == "Active" ) { $active_rank++; }
							}
						}
					} else {
						echo "<td colspan='".(count($GLOBALS[$module]['header_standing'])+count($GLOBALS[$module]['header_guild']))."' class='standings_tier_data'>No guild data found.</td>";
					}
				echo "</tbody>";						
			echo "</table>";
		echo "</div>";
	}

	function draw_encounter_guild($module, $encounter_details, $guild_array, $type, $region_details, $server_details) {
		if ( $type == "server" ) unset($GLOBALS[$module]['header_guild']['Server']);

		$pane_name = "";
		if ( $type == "world" ) $pane_name 	= "";
		if ( $type == "region" ) $pane_name = strtolower($region_details['abbreviation']);
		if ( $type == "server" ) $pane_name = strtolower($server_details['name']);

		echo "<div class='clear'></div>";
		echo "<div id='pane_$pane_name'>";
			echo "<table class='table_data standings_encounter'>";
				echo "<thead>";
					echo "<tr>";
						foreach ( $GLOBALS['standing']['header_guild'] as $key => $value ) {
							echo "<th>$key</th>";
						}

						foreach ( $GLOBALS['standing']['header_encounter'] as $key => $value ) {
							echo "<th>$key</th>";
						}
					echo "</tr>";
				echo "</thead>";
				echo "<tbody>";
					if ( count($guild_array) > 0 ) {
						$active_rank = 1;
						$legacy_rank = 1;
						foreach ( $guild_array as $guild_id => $guild_details ) {
							if ( ($type == "server" && $guild_details['server'] == $server_details['name'] && $guild_details['region'] == $region_details['abbreviation']) 
								|| ($type == "region" && $guild_details['region'] == $region_details['abbreviation']) 
								|| ($type == "world") ) {
								echo "<tr>";
									$guild_details = generate_table_fields($guild_details, 20);
									$guild_details['rank'] 	= $legacy_rank;
									//if ( $guild_details['active'] == "Inactive" ) { $guild_details['rank'] 	= $legacy_rank . "<b>|</b>--"; }

									foreach ( $GLOBALS[$module]['header_guild'] as $key => $value ) {
										$item = $guild_details[$value];

										//echo "<td><span>$item</span></td>";	

										if ( $guild_details['active'] == "Inactive" && $key == "Guild" ) { 
											echo "<td style=\"opacity:.30;\">$item</td>"; 
										} else {
											echo "<td>$item</td>";
										}											
									}

									foreach ( $GLOBALS['standing']['header_encounter'] as $key => $value ) {
										$item = $guild_details[$value];

										echo "<td class='standings_encounter_data'>$item</td>";
									}
								echo "</tr>";

								$legacy_rank++;
								if ( $guild_details['active'] == "Active" ) { $active_rank++; }
							}
						}
					} else {
						echo "<tr>";
							echo "<td colspan='".(count($GLOBALS[$module]['header_encounter'])+count($GLOBALS[$module]['header_guild']))."' class='standings_encounter_data'>No guild data found.</td>";
						echo "</tr>";
					}
				echo "</tbody>";
			echo "</table>";
		echo "</div>";
	}

	function draw_overall_standing_view($module, $guild_array, $display_type) {
		$tooltip_table = create_progress_overall_table($module, $guild_array);

		if ( $display_type == "world" ) {
			echo "<div class='horizontal_separator'></div>";
			draw_message_banner_region_server("", "", "Standings");
			draw_tier_guild($module, $guild_array, "world", "", "", $tooltip_table);
		}

		if ( $display_type == "region" ) {
			foreach ( $GLOBALS['global_region_array'] as $region => $region_details ) {
				echo "<div class='horizontal_separator'></div>";
				draw_message_banner_region_server($region_details, "", "Standings");
				draw_tier_guild($module, $guild_array, "region", $region_details, "", $tooltip_table);
			}
		}

		if ( $display_type == "server" ) {
			foreach ( $GLOBALS['global_region_array'] as $region => $region_details ) {
				foreach ( $GLOBALS['global_server_array'] as $shard => $server_details ) {
					if ( $server_details['region'] != $region_details['abbreviation'] ) continue;

					echo "<div class='horizontal_separator'></div>";
					draw_message_banner_region_server($region_details, $server_details, "");
					draw_tier_guild($module, $guild_array, "server", $region_details, $server_details, $tooltip_table);
				}
			}
		}
	}

	function draw_overall_size_standing_view($module, $guild_array, $raid_size, $display_type) {
		$tooltip_table = create_progress_overall_size_table($module, $guild_array, $raid_size);

		if ( $display_type == "world" ) {
			echo "<div class='horizontal_separator'></div>";
			draw_message_banner_region_server("", "", "Standings");
			draw_tier_guild($module, $guild_array, "world", "", "", $tooltip_table);
		}

		if ( $display_type == "region" ) {
			foreach ( $GLOBALS['global_region_array'] as $region => $region_details ) {
				echo "<div class='horizontal_separator'></div>";
				draw_message_banner_region_server($region_details, "", "Standings");
				draw_tier_guild($module, $guild_array, "region", $region_details, "", $tooltip_table);
			}
		}

		if ( $display_type == "server" ) {
			foreach ( $GLOBALS['global_region_array'] as $region => $region_details ) {
				foreach ( $GLOBALS['global_server_array'] as $shard => $server_details ) {
					if ( $server_details['region'] != $region_details['abbreviation'] ) continue;

					echo "<div class='horizontal_separator'></div>";
					draw_message_banner_region_server($region_details, $server_details, "");
					draw_tier_guild($module, $guild_array, "server", $region_details, $server_details, $tooltip_table);
				}
			}
		}
	}

	function draw_tier_standing_view($module, $guild_array, $tier_details, $display_type) {
		$tooltip_table = create_progress_tier_table($module, $tier_details['tier'], $guild_array);

		if ( $display_type == "world" ) {
			echo "<div class='horizontal_separator'></div>";
			draw_message_banner_region_server("", "", "Standings");
			draw_tier_guild($module, $guild_array, "world", "", "", $tooltip_table);
		}

		if ( $display_type == "region" ) {
			foreach ( $GLOBALS['global_region_array'] as $region => $region_details ) {
				echo "<div class='horizontal_separator'></div>";
				draw_message_banner_region_server($region_details, "", "Standings");
				draw_tier_guild($module, $guild_array, "region", $region_details, "", $tooltip_table);
			}
		}

		if ( $display_type == "server" ) {
			foreach ( $GLOBALS['global_region_array'] as $region => $region_details ) {
				foreach ( $GLOBALS['global_server_array'] as $shard => $server_details ) {
					if ( $server_details['region'] != $region_details['abbreviation'] ) continue;

					echo "<div class='horizontal_separator'></div>";
					draw_message_banner_region_server($region_details, $server_details, "");
					draw_tier_guild($module, $guild_array, "server", $region_details, $server_details, $tooltip_table);
				}
			}
		}
	}

	function draw_tier_size_standing_view($module, $guild_array, $tier_details, $raid_size, $display_type) {
		$tooltip_table = create_progress_tier_size_table($module, $tier_details['tier'], $raid_size, $guild_array);

		if ( $display_type == "world" ) {
			echo "<div class='horizontal_separator'></div>";
			draw_message_banner_region_server("", "", "Standings");
			draw_tier_guild($module, $guild_array, "world", "", "", $tooltip_table);
		}

		if ( $display_type == "region" ) {
			foreach ( $GLOBALS['global_region_array'] as $region => $region_details ) {
				echo "<div class='horizontal_separator'></div>";
				draw_message_banner_region_server($region_details, "", "Standings");
				draw_tier_guild($module, $guild_array, "region", $region_details, "", $tooltip_table);
			}
		}

		if ( $display_type == "server" ) {
			foreach ( $GLOBALS['global_region_array'] as $region => $region_details ) {
				foreach ( $GLOBALS['global_server_array'] as $shard => $server_details ) {
					if ( $server_details['region'] != $region_details['abbreviation'] ) continue;

					echo "<div class='horizontal_separator'></div>";
					draw_message_banner_region_server($region_details, $server_details, "");
					draw_tier_guild($module, $guild_array, "server", $region_details, $server_details, $tooltip_table);
				}
			}
		}
	}

	function draw_standing_dungeon_view($module, $guild_array, $dungeon_details, $display_type) {
		$tooltip_table = create_progress_dungeon_table($module, $dungeon_details, $guild_array);

		if ( $display_type == "world" ) {
			echo "<div class='horizontal_separator'></div>";
			draw_message_banner_region_server("", "", "Standings");
			draw_dungeon_guild($module, $dungeon_details, $guild_array, "world", "", "", $tooltip_table);
		}

		if ( $display_type == "region" ) {
			foreach ( $GLOBALS['global_region_array'] as $region => $region_details ) {
				echo "<div class='horizontal_separator'></div>";
				draw_message_banner_region_server($region_details, "", "Standings");
				draw_dungeon_guild($module, $dungeon_details, $guild_array, "region", $region_details, "", $tooltip_table);
			}
		}

		if ( $display_type == "server" ) {
			foreach ( $GLOBALS['global_region_array'] as $region => $region_details ) {
				foreach ( $GLOBALS['global_server_array'] as $shard => $server_details ) {
					if ( $server_details['region'] != $region_details['abbreviation'] ) continue;

					echo "<div class='horizontal_separator'></div>";
					draw_message_banner_region_server($region_details, $server_details, "");
					draw_dungeon_guild($module, $dungeon_details, $guild_array, "server", $region_details, $server_details, $tooltip_table);
				}
			}
		}
	}

	function draw_standing_encounter_view($module, $guild_array, $encounter_details, $display_type) {
		if ( $display_type == "world" ) {
			echo "<div class='horizontal_separator'></div>";
			draw_message_banner_region_server("", "", "Standings");
			draw_encounter_guild($module, $encounter_details, $guild_array, "world", "", "Standings" );
		}

		if ( $display_type == "region" ) {
			foreach ( $GLOBALS['global_region_array'] as $region => $region_details ) {
				echo "<div class='horizontal_separator'></div>";
				draw_message_banner_region_server($region_details, "", "Standings");
				draw_encounter_guild($module, $encounter_details, $guild_array, "region", $region_details, "" );
			}
		}

		if ( $display_type == "server" ) {
			foreach ( $GLOBALS['global_region_array'] as $region => $region_details ) {
				foreach ( $GLOBALS['global_server_array'] as $shard => $server_details ) {
					if ( $server_details['region'] != $region_details['abbreviation'] ) continue;

					echo "<div class='horizontal_separator'></div>";
					draw_message_banner_region_server($region_details, $server_details, "");
					draw_encounter_guild($module, $encounter_details, $guild_array, "server", $region_details, $server_details);
				}
			}
		}
	}

	function create_progress_overall_table($module, $guild_array) {
		$table_array = array();

		if ( count($guild_array) > 0 ) {
			foreach ( $guild_array as $guild_id => $guild_details ) {
				$encounter_array 		= array(); 
				$encounter_date_array 	= array(); 
				$dungeon_array 			= array();	
				$country 				= get_image_flag($guild_details['country']);
				$name 					= "$country <span>".generate_hyperlink_guild($guild_details['name'], $guild_details['server'], 0, $guild_details['faction'], "")."</span>";
				$output_table 			= "";

				if ( isset($guild_details['overall_details']) ) {
					foreach ( $guild_details['tier_details'] as $tier => $tier_details ) {
						$specific_tier_details = $GLOBALS['global_tier_array'][$tier];

						if ( $specific_tier_details['tier'] == $tier ) {
							$encounter_date_array[$tier] = $tier_details['recent_time'];
						}
					}
				}

				arsort($encounter_date_array);

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
						if ( count($encounter_date_array) > 0 ) {
							foreach ( $encounter_date_array as $tier => $tier_details ) {
								$output_table .= "<tr>";
									foreach ( $GLOBALS[$module]['tooltip_alltime'] as $key => $value ) {
										$item = $guild_details['tier_details'][$tier][$value];

										$output_table .= "<td>$item</td>";
									}
								$output_table .= "</tr>";	
							}
						} else {
							$output_table .= "<tr>";
								$output_table .= "<td colspan='".count($GLOBALS[$module]['tooltip_alltime'])."'>No raid progression data found.</td>";
							$output_table .= "</tr>";			
						}	
					$output_table .= "</tbody>";
				$output_table .= "</table>";

				$table_array[$guild_id] = $output_table;			
			}	
		}

		return $table_array;
	}

	function create_progress_overall_size_table($module, $guild_array, $raid_size) {
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

						if ( $specific_dungeon_details['dungeon_type'] != 0 ) continue;

						if ( $specific_dungeon_details['players'] == $raid_size ) {
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
										$item = $guild_details['dungeon_details'][$dungeon_id][$value];

										$output_table .= "<td>$item</td>";
									}
								$output_table .= "</tr>";	
							}
						} else {
							$output_table .= "<tr>";
								$output_table .= "<td colspan='".count($GLOBALS[$module]['tooltip_tier'])."'>No raid progression data found.</td>";
							$output_table .= "</tr>";			
						}	
					$output_table .= "</tbody>";
				$output_table .= "</table>";

				$table_array[$guild_id] = $output_table;			
			}	
		}

		return $table_array;
	}

	function create_progress_tier_table($module, $tier, $guild_array) {
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
										$item = $guild_details['dungeon_details'][$dungeon_id][$value];

										$output_table .= "<td>$item</td>";
									}
								$output_table .= "</tr>";	
							}
						} else {
							$output_table .= "<tr>";
								$output_table .= "<td colspan='".count($GLOBALS[$module]['tooltip_tier'])."'>No raid progression data found.</td>";
							$output_table .= "</tr>";			
						}	
					$output_table .= "</tbody>";
				$output_table .= "</table>";

				$table_array[$guild_id] = $output_table;			
			}	
		}

		return $table_array;
	}

	function create_progress_tier_size_table($module, $tier, $raid_size, $guild_array) {
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

						if ( $specific_dungeon_details['dungeon_type'] != 0 ) continue;

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
										$item = $guild_details['dungeon_details'][$dungeon_id][$value];

										$output_table .= "<td>$item</td>";
									}
								$output_table .= "</tr>";		
							}
						} else {
							$output_table .= "<tr>";
								$output_table .= "<td colspan='".count($GLOBALS[$module]['tooltip_tier'])."'>No raid progression data found.</td>";
							$output_table .= "</tr>";			
						}	
					$output_table .= "</tbody>";
				$output_table .= "</table>";

				$table_array[$guild_id] = $output_table;			
			}	
		}

		return $table_array;
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
					if ( $encounter_details['dungeon_id'] == $dungeon_details['dungeon_id'] ) {
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
								$output_table .= "<td colspan='".count($GLOBALS[$module]['tooltip_dungeon'])."'>No raid progression data found.</td>";
							$output_table .= "</tr>";			
						}
					$output_table .= "</tbody>";
				$output_table .= "</table>";
				
				$table_array[$guild_id] = $output_table;			
			}	
		}

		return $table_array;
	}

	function get_overall_standings($unsort_guild_array) {
		$guild_array = $temp_array = array();

		foreach ( $unsort_guild_array as $guild_id => $guild_details ) {			
			$guild_details = get_guild_details($guild_id);

			if ( $_SESSION['active'] == 1 ) {
				if ( $guild_details['type'] == 1 ) { continue; }
				if ( $guild_details['active'] == 0 ) { continue; }
			}	

			foreach ( $guild_details as $key => $value ) { $unsort_guild_array[$guild_id][$key] = $value; }
			foreach ( $guild_details['overall_details'] as $key => $value ) { $unsort_guild_array[$guild_id][$key] = $value; }

			$complete	= $guild_details['overall_details']['complete'];
			$datetime 	= $guild_details['overall_details']['recent_time'];

			$temp_array[$complete][$guild_id] = $guild_details['overall_details']['recent_time'];
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

	function get_overall_size_standings($unsort_guild_array, $raid_size) {
		$guild_array = $temp_array = array();

		foreach ( $unsort_guild_array as $guild_id => $guild_details ) {			
			$guild_details = get_guild_details($guild_id);

			if ( $_SESSION['active'] == 1 ) {
				if ( $guild_details['type'] == 1 ) { continue; }
				if ( $guild_details['active'] == 0 ) { continue; }
			}	

			foreach ( $guild_details as $key => $value ) { $unsort_guild_array[$guild_id][$key] = $value; }
			foreach ( $guild_details['direct_raid_size_details'][$raid_size] as $key => $value ) { $unsort_guild_array[$guild_id][$key] = $value; }

			$complete	= $guild_details['direct_raid_size_details'][$raid_size]['complete'];
			$datetime 	= $guild_details['direct_raid_size_details'][$raid_size]['recent_time'];

			$temp_array[$complete][$guild_id] = $guild_details['direct_raid_size_details'][$raid_size]['recent_time'];
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

	function get_tier_standings($unsort_guild_array, $tier_details) {
		$guild_array = $temp_array = array();
		$tier = $tier_details['tier'];

		foreach ( $unsort_guild_array as $guild_id => $guild_details ) {			
			$guild_details = get_guild_details($guild_id);

			if ( $_SESSION['active'] == 1 ) {
				if ( $guild_details['type'] == 1 ) { continue; }
				if ( $guild_details['active'] == 0 ) { continue; }
			}

			foreach ( $guild_details as $key => $value ) { $unsort_guild_array[$guild_id][$key] = $value; }
			foreach ( $guild_details['tier_details'][$tier] as $key => $value ) { $unsort_guild_array[$guild_id][$key] = $value; }

			$num_name 	= $num_date	= 0;
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

	function get_tier_size_standings($unsort_guild_array, $tier_details, $raid_size) {
		$guild_array = $temp_array = array();
		$tier = $tier_details['tier'];

		foreach ( $unsort_guild_array as $guild_id => $guild_details ) {			
			$guild_details = get_guild_details($guild_id);

			if ( $_SESSION['active'] == 1 ) {
				if ( $guild_details['type'] == 1 ) { continue; }
				if ( $guild_details['active'] == 0 ) { continue; }
			}

			foreach ( $guild_details as $key => $value ) { $unsort_guild_array[$guild_id][$key] = $value; }
			foreach ( $guild_details['raid_size_details'][$tier][$raid_size] as $key => $value ) { $unsort_guild_array[$guild_id][$key] = $value; }

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

	function get_dungeon_standings($unsort_guild_array, $dungeon_details) {
		$guild_array 	= array();
		$temp_array 	= array();
		$dungeon 		= $dungeon_details['dungeon_id'];

		foreach ( $unsort_guild_array as $guild_id => $guild_details ) {			
			$guild_details = get_guild_details($guild_id);

			if ( $_SESSION['active'] == 1 ) {
				if ( $guild_details['type'] == 1 ) { continue; }
				if ( $guild_details['active'] == 0 ) { continue; }
			}

			foreach ( $guild_details as $key => $value ) { $unsort_guild_array[$guild_id][$key] = $value; }
			foreach ( $guild_details['dungeon_details'][$dungeon] as $key => $value ) { $unsort_guild_array[$guild_id][$key] = $value; }

			$num_name 	= $num_date	= 0;
			$name 		= $guild_details['name'];
			$complete	= $guild_details['dungeon_details'][$dungeon]['complete'];
			$datetime 	= $guild_details['dungeon_details'][$dungeon]['recent_time'];

			if ( $complete == 0 ) { continue; }

			$temp_array[$complete][$guild_id] = $guild_details['dungeon_details'][$dungeon]['recent_time'];
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

	function get_encounter_standings($unsort_guild_array, $encounter_details) {
		$guild_array = $data_array = $order_array = array();
		$encounter_id = $encounter_details['encounter_id'];
		
		foreach ( $unsort_guild_array as $guild_id => $guild_details ) {
			$guild_details = get_guild_details($guild_id);

			if ( $_SESSION['active'] == 1 ) {
				if ( $guild_details['type'] == 1 ) { continue; }
				if ( $guild_details['active'] == 0 ) { continue; }
			}	

			if ( isset($guild_details['direct_encounter_details'][$encounter_id]) ) {
				if ( isset($guild_details['direct_encounter_details'][$encounter_id]['screenshot']) && strlen($guild_details['direct_encounter_details'][$encounter_id]['screenshot']) > 0 ) {
					$hyperlink = $GLOBALS['fold_guild_screenshots']."/".$guild_details['direct_encounter_details'][$encounter_id]['screenshot'];
					$text = "--";

					if ( file_exists($_SERVER['DOCUMENT_ROOT'].$hyperlink) ) { $text = "<a href='$hyperlink' rel='lightbox['kill_shots']'>View</a>"; }

					$guild_details['direct_encounter_details'][$encounter_id]['screenshot'] = $text;
				} else {
					$guild_details['direct_encounter_details'][$encounter_id]['screenshot'] = "--";
				}

				if ( isset($guild_details['direct_encounter_details'][$encounter_id]['video']) && strlen($guild_details['direct_encounter_details'][$encounter_id]['video']) > 0 ) {
					$hyperlink = $guild_details['direct_encounter_details'][$encounter_id]['video'];
					$guild_details['direct_encounter_details'][$encounter_id]['video'] = "<a target='_blank' href='$hyperlink'>View</a>";
				} else {
					$guild_details['direct_encounter_details'][$encounter_id]['video'] = "--";
				}

				$guild_details['direct_encounter_details'][$encounter_id]['server_rank'] 	= get_rank_medal($guild_details['direct_encounter_details'][$encounter_id]['server_rank']);
				$guild_details['direct_encounter_details'][$encounter_id]['region_rank'] 	= get_rank_medal($guild_details['direct_encounter_details'][$encounter_id]['region_rank']);
				$guild_details['direct_encounter_details'][$encounter_id]['world_rank'] 	= get_rank_medal($guild_details['direct_encounter_details'][$encounter_id]['world_rank']);

				if ( $guild_details['direct_encounter_details'][$encounter_id]['server_rank'] == "0" ) $guild_details['direct_encounter_details'][$encounter_id]['server_rank'] = "--";
				if ( $guild_details['direct_encounter_details'][$encounter_id]['region_rank'] == "0" ) $guild_details['direct_encounter_details'][$encounter_id]['region_rank'] = "--";
				if ( $guild_details['direct_encounter_details'][$encounter_id]['world_rank'] == "0" ) $guild_details['direct_encounter_details'][$encounter_id]['world_rank'] = "--";

				foreach ( $guild_details as $key => $value ) { $guild_details['direct_encounter_details'][$encounter_id][$key] = $value; }

				$data_array[$guild_id] 	= $guild_details['direct_encounter_details'][$encounter_id];
				$order_array[$guild_id] = $guild_details['direct_encounter_details'][$encounter_id]['strtotime'];
			}	
		}

		asort($order_array);

		foreach ( $order_array as $guild_id => $time ) {
			$guild_array[$guild_id] = $data_array[$guild_id];
		}		

		return $guild_array;
	}

	function get_total_world($tier_details, $guild_array, $order_region_array, $order_server_array) {
		$total_world = 0;
		$guild_count = 0;

		foreach ( $guild_array as $guild_id => $guild_details ) {
			$name 	= $guild_details['name'];
			$region = $guild_details['region'];
			$server = $guild_details['server'];
			$score 	= 0;

			if ( isset($tier_details) && $tier_details == "alltime" ) {
				if ( $guild_details['overall_details']['complete'] == 0 ) continue;
				$score 	= $guild_details['overall_details']['complete'];
			} else {
				if ( $guild_details['tier_details'][$tier_details['tier']]['complete'] == 0 ) continue;
				$score 	= $guild_details['tier_details'][$tier_details['tier']]['complete'];
			}

			$total_world = $total_world + $score;
			$guild_count++;
		}

		if ( count($guild_array) > 0 ) {
			$total_world = number_format(($total_world / $guild_count), 2, ".", ",");
		}

		return $total_world;
	}

	function get_server_strength($tier_details, $order_server_array, $guild_array) {
		$guild_count_array = array();

		foreach ( $GLOBALS['global_server_array'] as $server => $server_details ) {
			$GLOBALS['global_server_array'][$server]['score'] 	= 0;
			$GLOBALS['global_server_array'][$server]['count'] 	= 0;
			$guild_count_array[$server] 				= 0;
		}

		foreach ( $guild_array as $guild_id => $guild_details ) {
			if ( $guild_details['type'] == 1 ) { continue; }
			if ( $guild_details['active'] == 0 ) { continue; }	

			$name 	= $guild_details['name'];
			$region = $guild_details['region'];
			$server = $guild_details['server'];
			$score 	= 0;

			if ( isset($tier_details) && $tier_details == "alltime" ) {
				if ( $guild_details['overall_details']['complete'] == 0 ) continue;
				$score 	= $guild_details['overall_details']['complete'];
			} else {
				if ( $guild_details['tier_details'][$tier_details['tier']]['complete'] == 0 ) continue;
				$score 	= $guild_details['tier_details'][$tier_details['tier']]['complete'];
			}

			if ( isset($GLOBALS['global_server_array'][$server]['score']) && isset($order_server_array[$server]) ) {
				$GLOBALS['global_server_array'][$server]['count'] 	= $GLOBALS['global_server_array'][$server]['count'] + 1;
				$GLOBALS['global_server_array'][$server]['score'] 	= $GLOBALS['global_server_array'][$server]['score'] + $score;
				$order_server_array[$server]				= $order_server_array[$server] + $score;
			} else {
				$GLOBALS['global_server_array'][$server]['count'] 	= 1;
				$GLOBALS['global_server_array'][$server]['score'] 	= $score;
				$order_server_array[$server] 				= $score;
			}

			$guild_count_array[$server]++;
		}

		foreach ( $order_server_array as $server => $score ) {
			$order_server_array[$server] = number_format(($order_server_array[$server] / $guild_count_array[$server]), 2, ".", ",");
			
			if ( $order_server_array[$server] == 0 ) $order_server_array[$server] = 0;
		}

		arsort($order_server_array);

		return $order_server_array;
	}

	function get_region_strength($tier_details, $order_region_array, $guild_array) {
		$guild_count_array = array();

		foreach ( $GLOBALS['global_region_array'] as $region => $region_details ) {
			$GLOBALS['global_region_array'][$region]['score'] 	= 0;
			$GLOBALS['global_region_array'][$region]['count'] 	= 0;
			$guild_count_array[$region] 				= 0;
		}	

		foreach ( $guild_array as $guild_id => $guild_details ) {
			if ( $guild_details['type'] == 1 ) { continue; }
			if ( $guild_details['active'] == 0 ) { continue; }	

			$name 	= $guild_details['name'];
			$region = $guild_details['region'];
			$server = $guild_details['server'];
			$score 	= 0;

			if ( isset($tier_details) && $tier_details == "alltime" ) {
				if ( $guild_details['overall_details']['complete'] == 0 ) continue;
				$score 	= $guild_details['overall_details']['complete'];
			} else {
				if ( $guild_details['tier_details'][$tier_details['tier']]['complete'] == 0 ) continue;
				$score 	= $guild_details['tier_details'][$tier_details['tier']]['complete'];
			}

			if ( isset($GLOBALS['global_region_array'][$region]['score']) && isset($order_region_array[$region]) ) {
				$GLOBALS['global_region_array'][$region]['count'] = $GLOBALS['global_region_array'][$region]['count'] + 1;
				$GLOBALS['global_region_array'][$region]['score'] = $GLOBALS['global_region_array'][$region]['score'] + $score;
				$order_region_array[$region]				= $order_region_array[$region] + $score;
			} else {
				$GLOBALS['global_region_array'][$region]['count'] = 1;
				$GLOBALS['global_region_array'][$region]['score'] = $score;
				$order_region_array[$region] 				= $score;
			}

			$guild_count_array[$region]++;
		}

		foreach ( $order_region_array as $region => $score ) {
			$order_region_array[$region] = number_format(($order_region_array[$region] / $guild_count_array[$region]), 2, ".", ",");

			if ( $order_region_array[$region] == 0 ) $order_region_array[$region] = 0;
		}

		arsort($order_region_array);

		return $order_region_array;
	}
?>