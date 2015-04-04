<?php
	function draw_header($module, $page_title, $page_description) {
		if ($module == "logout") { 
			session_destroy();
			if ( isset($_SESSION) ) { unset($_SESSION['user']); unset($_SESSION['userID']); }
		}
		
		//***************DECLARING VARIABLES**************
		//***************DECLARING VARIABLES**************
		
		echo "<!DOCTYPE HTML PUBLIC '-//W3C//DTD HTML 4.01 Transitional//EN''http://www.w3.org/TR/html4/loose.dtd'>";
		echo "<html>";
			echo "<head>";
				load_scripts($module, $page_description);
				echo "<meta property='og:title' content='$page_title' />";
				echo "<meta property='og:description' content='$page_description' />";
				echo "<title>$page_title | ".$GLOBALS['site_title']."</title>";
			echo "</head>";

			if ( isset($_SESSION['user'])) flush();

			echo "<body onLoad='pageLoad()'>";
				echo "<div id='page_wrapper'>";
					echo "<div id='fade' class='black_overlay'></div>";
					echo "<div id='popup_wrapper'>";
						echo "<div id='login_content' class='popup_content'>";
							echo "<div class='popup_title'>{$GLOBALS['login']['title']}</div>";
							echo "<div class='popup_form'>";
								draw_login_popup();
							echo "</div>";
						echo "</div>";
						echo "<div id='quick_content' class='popup_content'>";
							echo "<div class='popup_title'>{$GLOBALS['quick']['title']}</div>";
							echo "<div class='popup_form'>";
								draw_quick_popup();
							echo "</div>";
						echo "</div>";
						echo "<div id='contact_content' class='popup_content'>";
							echo "<div class='popup_title'>{$GLOBALS['contact']['title']}</div>";
							echo "<div class='popup_form'>";
								draw_contact_popup();
							echo "</div>";
						echo "</div>";
						echo "<div id='logout_content' class='popup_content'>";
							echo "<div class='popup_title'>{$GLOBALS['logout']['title']}</div>";
							echo "<div class='popup_form'>";
								draw_logout_popup();
							echo "</div>";
						echo "</div>";
						echo "<div id='active_content' class='popup_content'>";
							echo "<div class='popup_title'>Switching Status</div>";
							echo "<div id='host' style='display:none;'>{$GLOBALS['host_name']}</div>";
							echo "<div id='div_active'></div>";
							echo "<div class='popup_form'>";
								draw_active_popup();
							echo "</div>";
						echo "</div>";
					echo "</div>";
					echo "<div class='clear'></div>";
					echo "<div id='header_wrapper'>";
						echo "<div id='header_content'>";
							echo "<div class='vertical_separator'></div>";
							echo "<div id='banner_main' style='float:left; width:284px; height:150px;'>";
								echo $GLOBALS['images']['banner_header'];
								echo "<div class='horizontal_separator'></div>";
								echo "<div id='banner_social'>";
									echo "<span class='img_helper'></span>";
									echo generate_hyperlink($GLOBALS['link_facebook'], $GLOBALS['images']['facebook'], "", 1, "");
									echo generate_hyperlink($GLOBALS['link_twitter'], $GLOBALS['images']['twitter'], "", 1, "");
								echo "</div>";
							echo "</div>";
							echo "<div class='vertical_separator'></div>";
							
							if ( $GLOBALS['enable_ads']['header'] == 1 ) { header_uni_draw_box_ad(); }
						echo "</div>";
						echo "<div class='clear'></div>";
					echo "</div>";
					echo "<div id='nav_wrapper'>";
						echo "<div id='nav_content'>";
							echo "<nav>";
								echo "<ul id='nav_right'>";
									echo "<li id='nav_search'>";
										echo "<form action='{$GLOBALS['page_search']}' method=\"POST\">";
											echo "<input id='textbox_search' type='text' name='guild_search' placeholder='Enter Guild Name'>";
										echo "</form>";
										echo "<div class='clear'></div>";
									echo "</li>";
									
									if ( isset($_SESSION['user']) && isset($_SESSION['user_id']) ) {
										echo "<li class='dropdown'>".generate_hyperlink($GLOBALS['page_user'], $GLOBALS['user']['title'], "", 0, "")."<div class='backdrop'></div></li>";
										echo "<li id='logout_popup' class='dropdown'>{$GLOBALS['logout']['title']}<div class='backdrop'></div></li>";
									} else {
										echo "<li class='dropdown'>".generate_hyperlink($GLOBALS['page_register'], $GLOBALS['register']['title'], "", 0, "")."<div class='backdrop'></div></li>";
										echo "<li id='login_popup' class='dropdown'>{$GLOBALS['login']['title']}<div class='backdrop'></div></li>";
									}
									
									echo "<li id='contact_popup' class='dropdown'>{$GLOBALS['contact']['title']}<div class='backdrop'></div></li>";
									/*echo "<li id='active_popup' class='dropdown'>";
										if ( isset($_SESSION['active']) && $_SESSION['active'] == 1 ) { 
											echo "<a id='active_text' class='defiant'>Active</a>"; 
										} else { 
											echo "<a id='active_text' class='guardian'>Legacy</a>"; 
										}

										echo "<div class='backdrop'></div>";
									echo "</li>";*/
								echo "</ul>";
								echo "<ul id='nav_left'>";
									echo "<li class='dropdown'>".generate_hyperlink($GLOBALS['page_news'], $GLOBALS['news']['title'], "", 0, "")."<div class='backdrop'></div></li>";
									
									if ( $GLOBALS['standing']['set'] == 1) { generate_menu_standing(); }
									
									if ( $GLOBALS['ranking']['set'] == 1 ) { generate_menu_ranking(); }

									if ( $GLOBALS['servers']['set'] == 1 ) { generate_menu_server(); }

									if ( $GLOBALS['how']['set'] == 1 ) {
										echo "<li class='dropdown'>".generate_hyperlink($GLOBALS['page_how'], $GLOBALS['how']['title'], "", 0, "")."<div class='backdrop'></div></li>";	
									}

									if ( $GLOBALS['guild_list']['set'] == 1 ) {
										echo "<li class='dropdown'>".generate_hyperlink($GLOBALS['page_guild_list'], $GLOBALS['guild_list']['title'], "", 0, "")."<div class='backdrop'></div></li>";	
									}
									
									if ( $GLOBALS['quick']['set'] == 1 ) {
										echo "<li id='quick_popup' class='dropdown'>{$GLOBALS['quick']['title']}<div class='backdrop'></div></li>";
									}
								echo "</ul>";
							echo "</nav>";
						echo "</div>";
					echo "</div>";
	}

	function draw_footer() {
		$logo_game_1 	= generate_hyperlink($GLOBALS['link_game_1'], $GLOBALS['images']['logo_game_1'], "", 1, "");
		$logo_company_1 = generate_hyperlink($GLOBALS['link_company_1'], $GLOBALS['images']['logo_company_1'], "", 1, "");
		$logo_game_2 	= generate_hyperlink($GLOBALS['link_game_1'], $GLOBALS['images']['logo_game_2'], "", 1, "");
		$logo_game_3 	= generate_hyperlink($GLOBALS['link_game_1'], $GLOBALS['images']['logo_game_3'], "", 1, "");

		echo "<div id='footer_wrapper'>";
			echo "<div id='footer_content'>";
				echo "<div class='horizontal_separator'></div>";
				echo "<div class='vertical_separator'></div>";				
				echo "<div class='footer_container'>";
					echo "<span class='img_helper'></span>$logo_game_2";
				echo"</div>";
				echo "<div class='vertical_separator'></div>";
				echo "<div class='footer_container'>";
					echo "<span class='img_helper'></span>$logo_company_1";
				echo "</div>";
				echo "<div class='vertical_separator'></div>";
				echo "<div class='footer_container'>";
					echo "<span class='img_helper'></span>$logo_game_3";
				echo "</div>";
				echo "<div class='vertical_separator'></div>";
				echo "<div class='clear'></div>";
			echo "</div>";
		echo "</div>";
	}

	function draw_bottom() {
		$terms_of_service 	= generate_hyperlink($GLOBALS['page_tos'], $GLOBALS['tos']['title'], "", 0, "");
		$pricacy_policy		= generate_hyperlink($GLOBALS['page_privacy'], $GLOBALS['privacy']['title'], "", 0, "");
					echo "<div id='bottom_wrapper'>";
						echo "<div id='bottom_content'>";
							echo "<div class='horizontal_separator'></div>";
							echo "<div class='vertical_separator'></div>";
							echo "<div id='copyright_content'>";
								echo " {$GLOBALS['copyright']} ";
								echo "$terms_of_service | $pricacy_policy";
							echo "</div>";
							echo "<div class='vertical_separator'></div>";
							echo "<div class='clear'></div>";
							echo "<div class='horizontal_separator'></div>";
						echo "</div>";
					echo "</div>";
					echo "<div class='clear'></div>";
				echo "</div>";
			echo "</body>";
		echo "</html>";
	}

	function draw_login_popup() { generate_popup_form('login', $GLOBALS['page_login'], ""); }

	function draw_quick_popup() { generate_popup_form('encounter_popup_add', $GLOBALS['page_quick'], ""); }

	function draw_contact_popup() { generate_popup_form('contact', $GLOBALS['page_contact'], ""); }

	function draw_logout_popup() {
		echo "<div>";
			echo "<form action='{$GLOBALS['page_logout']}' method='POST'>";
				echo "Are you sure you want to log out?<br>";
				echo "<input type='submit' value='Log Out'>";
			echo "</form>";
		echo "</div>";
		echo "<a id='logout_popup_close' href='#'>Close</a>";
	}

	function draw_active_popup() {
		echo "<div>";
			echo $GLOBALS['images']['loading'];
		echo "</div>";
	}

	function generate_menu_ranking() {
		echo "<li class='dropdown'>{$GLOBALS['ranking']['title']}";
			echo "<ul>";
				echo "<li>";
					echo "<div class='nav_block_title'>";
						echo generate_hyperlink_ranking("All-Time Rankings", "alltime", "prad", "world", "", "", 0, "", "");

						foreach ( $GLOBALS['raid_size_array'] as $size => $raid_size ) {
							echo generate_hyperlink_ranking("{$raid_size}-Mans", "alltime-size", "prad", "world", $raid_size, "", 0, "", "");
						}
					echo "</div>";
					
					echo "<div>";
						krsort($GLOBALS['global_tier_array']);

						foreach ( $GLOBALS['global_tier_array'] as $tier => $tier_details ) {
							$num_of_encounters = $tier_details['encounters'];

							if ( $num_of_encounters > 0 ) {
								$tier_number 	= $tier_details['tier'];
								$tier_title 	= $tier_details['title'];

								echo "<div class='nav_block'>";
									echo "<div class='nav_block_subtitle'>Tier $tier_number ({$tier_title})</div>";
									echo "<hr>";

									$type_array = array();

									foreach ( $GLOBALS['global_dungeon_array'] as $dungeon_id => $dungeon_details ) {
										if ( $dungeon_details['tier'] == $tier ) {
											$type_array[$dungeon_details['players']] = $dungeon_details['players'];
										}
									}
									 
									echo "<div class='nav_block_subtitle'>";
										echo generate_hyperlink_ranking("All Content", "tier", "prad", "world", $tier_details, "", 0, "", "");

										foreach ( $type_array as $type => $raid_size ) {
											echo generate_hyperlink_ranking("{$raid_size}-Mans", "tier-size", "prad", "world", $tier_details, $raid_size, 0, "", "");
										}
									echo "</div>";
									echo "<div class='clear'></div>";

									$first_dungeon = 0;

									foreach ( $GLOBALS['global_dungeon_array'] as $dungeon_id => $dungeon_details ) {
										$dungeon_name 		= $dungeon_details['name'];
										$dungeon_tier 		= $dungeon_details['tier'];
										$dungeon_mobs 		= $dungeon_details['mobs'];
								
										if ( $dungeon_tier == $tier_number && $dungeon_mobs > 0 ) {
											if ( $first_dungeon == 0 ) {
												echo generate_hyperlink_ranking($dungeon_name, "dungeon", "prad", "world", $dungeon_details, $tier_details, 0, "header_dungeon_link", "");
												$first_dungeon++;
											} else {
												echo generate_hyperlink_ranking($dungeon_name, "dungeon", "prad", "world", $dungeon_details, $tier_details, 0, "header_dungeon_link", "");
											}
										}

										echo "<div class='clear'></div>";
									}
								echo "</div>";
							}
						}
					echo "</div>";
				echo "</li>";
			echo "</ul>";
		echo "<div class='backdrop'></div></li>";
	}
	
	function generate_menu_standing() {
		echo "<li class='dropdown'>{$GLOBALS['standing']['title']}";
			echo "<ul>";
				echo "<li>";
					//echo "<div class='nav_block_title'>";
					//	echo generate_hyperlink_standing("All-Time Standings", "alltime", "world", "", "", 0, "", "");

					//	foreach ( $GLOBALS['raid_size_array'] as $size => $raid_size ) {
					//		echo generate_hyperlink_standing("{$raid_size}-Mans", "alltime-size", "world", $raid_size, "", 0, "", "");
					//	}
					//echo "</div>";

					echo "<div>";
						krsort($GLOBALS['global_tier_array']);
						
						foreach ( $GLOBALS['global_tier_array'] as $tier => $tier_details ) {
							$num_of_encounters = $tier_details['encounters'];

							if ( $num_of_encounters > 0 ) {
								$tier_number 	= $tier_details['tier'];
								$tier_title 	= $tier_details['title'];

								echo "<div class='nav_block'>";
									echo "<div class='nav_block_subtitle'>Tier $tier_number ({$tier_title})</div>";
									//echo "<hr>";

									$type_array = array();

									foreach ( $GLOBALS['global_dungeon_array'] as $dungeon_id => $dungeon_details ) {
										if ( $dungeon_details['tier'] == $tier ) {
											$type_array[$dungeon_details['players']] = $dungeon_details['players'];
										}
									}
									 
									//echo "<div class='nav_block_subtitle'>";
										//echo generate_hyperlink_standing("All Content", "tier", "world", $tier_details, "", 0, "", "");

									//	foreach ( $type_array as $type => $raid_size ) {
											//echo generate_hyperlink_standing("{$raid_size}-Mans", "tier-size", "world", $tier_details, $raid_size, 0, "", "");
									//	}
									//echo "</div>";
									echo "<div class='clear'></div>";

									$first_dungeon = 0;

									foreach ( $GLOBALS['global_dungeon_array'] as $dungeon_id => $dungeon_details ) {
										$dungeon_name 		= $dungeon_details['name'];
										$dungeon_tier 		= $dungeon_details['tier'];
										$dungeon_mobs 		= $dungeon_details['mobs'];

										if ( $dungeon_tier == $tier_number && $dungeon_mobs > 0 ) {
											echo generate_hyperlink_standing($dungeon_name, "dungeon", "world", $dungeon_details, $tier_details, 0, "header_dungeon_link", "");
											echo "<hr>";

											$first_mob = 0;

											foreach ( $GLOBALS['global_encounter_array'] as $encounter_id => $encounter_details ) {
												//if ( $encounter_details['mob_type'] == 0 ) {
													$encounter_name = $encounter_details['encounter_name'];
													$hyperlink_mob 	= generate_hyperlink_standing($encounter_name, "encounter", "world", $encounter_details, $dungeon_details, 0, "header_link", "");
													
													if ( $dungeon_id == $encounter_details['dungeon_id'] ) {
														if ( $first_mob == 0 ) {
															echo "<div class='nav_block_content_line'>$hyperlink_mob</div>";
															$first_mob++;
														} else {
															echo "<div class='nav_block_content_line'>$hyperlink_mob</div>";
														}
													}
												//}
											}
										}

										echo "<div class='clear'></div>";
									}
								echo "</div>";

							}
						}
					echo "</div>";
				echo "</li>";
			echo "</ul>";
		echo "<div class='backdrop'></div></li>";
	}
	
	function generate_menu_server() {
		echo "<li class='dropdown'>{$GLOBALS['servers']['title']}";
			echo "<ul>";
				echo "<li>";
					echo "<div>";
						echo "<div class='nav_block_title'>".generate_hyperlink($GLOBALS['page_server_rankings'], $GLOBALS['server_rankings']['title'], "", 0, "")."</div>";

						$region_count = count($GLOBALS['global_region_array']);

						echo "<div>";
							foreach ( $GLOBALS['global_region_array'] as $region => $region_details ) {
								$region_name 	= $region_details['full'];
								$region_abbrev 	= $region_details['abbreviation'];

								echo "<div class='nav_block'>";
									echo "<div class='nav_block_subtitle'>{$region_name}</div>";
									echo "<div class='clear'></div>";
									echo "<hr>";

									$first_server = 0;

									foreach ( $GLOBALS['global_server_array'] as $server => $server_details ) {
										$server_name 			= $server_details['name'];
										$server_region 			= $server_details['region'];
										$flag 					= get_image_flag($server_region);
										$current_server 		= "$flag <span>".generate_hyperlink_server($server_name, "world", 0, "", "")."</span>";
								
										if ( $region_abbrev == $server_region ) {															
											if ( $first_server == 0 ) {
												echo "<div class='nav_block_content_line'>$current_server</div>";
												$first_server++;
											} else {
												echo "<div class='nav_block_content_line'>$current_server</div>";
											}
										}	
									}
								echo "</div>";
							}
						echo "</div>";
					echo "</div>";
				echo "</li>";
			echo "</ul>";
		echo "<div class='backdrop'></div></li>";
	}
?>