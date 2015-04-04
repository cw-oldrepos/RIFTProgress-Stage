<?php
	$ROOT 			= dirname(dirname(dirname(__FILE__)));
	include_once 	"{$ROOT}/configuration.php";

	$module = "user";
	if ( !isset($GLOBALS[$module]['set']) || $GLOBALS[$module]['set'] == 0 ) { echo draw_disabled_module(); exit; } 
	if ( !isset($_SESSION['user']) ) { header("Location: {$GLOBALS['page_login']}"); exit; } 

	//***************DECLARING VARIABLES**************
	$user_details 		= array();
	$guild_array 		= array();
	$display_type 		= 0;
	//***************DECLARING VARIABLES**************

	if ( isset($_POST) && count($_POST) > 0 ) {
		if ( isset($_POST['form_user_edit']) )					{ $display_type = 1; }
		if ( isset($_POST['form_user_edit_submit']) ) 			{ $display_type = 2; }

		if ( isset($_POST['form_encounter_add']) )				{ $display_type = 3; } // Display Add Kills, Kill Progression
		if ( isset($_POST['form_encounter_add_submit']) )		{ $display_type = 4; } // Display Add Kill Confirmation
		if ( isset($_POST['form_encounter_edit']) ) 			{ $display_type = 5; }
		if ( isset($_POST['form_encounter_edit_submit']) )		{ $display_type = 6; }
		if ( isset($_POST['form_encounter_delete']) ) 			{ $display_type = 7; }
		if ( isset($_POST['form_encounter_delete_confirm']) )	{ $display_type = 8; }
		
		if ( isset($_POST['form_guild_create']) )				{ $display_type = 9; }
		if ( isset($_POST['form_guild_create_submit']) )		{ $display_type = 10; }
		if ( isset($_POST['form_guild_edit']) )					{ $display_type = 11; }
		if ( isset($_POST['form_guild_edit_submit']) )			{ $display_type = 12; }	
		if ( isset($_POST['form_guild_delete']) )				{ $display_type = 13; }
		if ( isset($_POST['form_guild_delete_submit']) )		{ $display_type = 14; }

		if ( isset($_POST['form_raid_team_add']) )				{ $display_type = 15; }
		if ( isset($_POST['form_raid_team_add_submit']) )		{ $display_type = 16; }
	}

	$user_details = get_user_details($_SESSION['user_id'], $_SESSION['user']);

	function block_draw_account_table($module, $user_details) {
		$block_title = generate_block_title($GLOBALS[$module]['block_title'][0]);

		$user_details = get_user_details($_SESSION['user_id'], $_SESSION['user']);

		echo "<div class='side_block'>";
			echo "<div class='block_title'>$block_title</div>";
				foreach  ($GLOBALS[$module]['account_details'] as $header => $value ) {
					$item = isset($user_details[$value]) ? $user_details[$value] : "N/A";

					if ( $value == "date_joined" ) {$item = format_date($item, 'm-d-Y');}

					echo "<div class='side_block_content'>";
						echo "<div class='side_block_content_right'>"; 
							echo "<div class='small'><div class='small_text'>$item</div></div>";
						echo "</div>";
						echo "<div class='side_block_content_left'>"; 
							echo "<div class='small'><div class='side_title'>$header</div></div>";
						echo "</div>";
						echo "<div class='clear'></div>";
					echo "</div>";
				}

				echo "<div class='side_block_content'>";
					echo "<form action='{$GLOBALS['page_user']}' method='POST'>";
						echo "<input name='form_user_edit' type='submit' value='Edit Account Details'>";
					echo "</form>";
				echo "</div>";
		echo "</div>";
	}

	function block_draw_guild_table($module, $user_details) {
		$block_title = generate_block_title($GLOBALS[$module]['block_title'][1]);
		$guild_array = get_user_guilds($user_details['user_id']);

		echo "<div class='side_block'>";
			echo "<div class='block_title'>$block_title</div>";

			echo "<div class='side_block_content'>";
				echo "<div class='small_text'>{$GLOBALS[$module]['message']['new_guild']}</div>";

				echo "<form action='{$GLOBALS['page_user']}' method='POST'>";
					echo "<input name='form_guild_create' class='data_button' type='submit' value='Create New Guild'>";
				echo "</form>";
				echo "<div class='clear'></div>";
			echo "</div>";	

			if ( count($guild_array) > 0 ) {
				foreach ( $guild_array as $guild_id => $guild_details ) {
					$guild_details = get_guild_details($guild_id);

					foreach ( $guild_details['overall_details'] as $key => $value ) { $guild_details[$key] = $value; }

					$guild_details['points'] = number_format($guild_details['overall_details'][0]['points'], 2, ".", ",");

					if ( $guild_details['overall_details'][0]['world']['rank'] == "N/A" ) {
						$guild_details['rank'] = "N/A";
					} else {
						$guild_details['rank'] = format_ordinal($guild_details['overall_details'][0]['world']['rank']). " Overall";
					}

					$guild_details['country'] 		= get_image_flag($guild_details['country'])." <span>{$guild_details['country']}</span>";
					$guild_details['server'] 		= get_image_flag($guild_details['region'])." <span>{$guild_details['server']}</span>";
					$guild_details['date_created'] 	= format_date($guild_details['date_created'], 'm-d-Y'); 

					echo "<div class='block_subtitle'>{$guild_details['name']}</div>";
					echo "<div class='clear'></div>";

					foreach ( $GLOBALS[$module]['guild_details'] as $header => $value ) {
						$item = isset($guild_details[$value]) && strlen($guild_details[$value]) > 0 ? $guild_details[$value] : "N/A";

						echo "<div class='side_block_content'>";
							echo "<div class='side_block_content_right'>"; 
								echo "<div class='small'><div class='small_text'>$item</div></div>";
							echo "</div>";
							echo "<div class='side_block_content_left'>"; 
								echo "<div class='small'><div class='side_title'>$header</div></div>";
							echo "</div>";
							echo "<div class='clear'></div>";
						echo "</div>";
					}

					echo "<div class='side_block_content'>";
						echo "<form action='{$GLOBALS['page_user']}' method='POST'>";
							echo "<input name='guild_id' type='hidden' value='{$guild_details['guild_id']}'>";
							echo "<input name='form_guild_edit' type='submit' value='Edit Guild'>";
							echo "<input name='form_guild_delete' type='submit' value='Disband Guild'>";
							echo "<div class='clear'></div>";
							if ( $guild_details['type'] == 0 ) echo "<input name='form_raid_team_add' type='submit' value='Add Raid Team'>";
							echo "<input name='form_encounter_add' type='submit' value='Add Kills'>";
							echo "<div class='clear'></div>";
						echo "</form>";
					echo "</div>";
				}
			}
		echo "</div>";
	}

	function draw_user_edit($module, $user_details) {
		$user_details = get_user_details($_SESSION['user_id'], $_SESSION['user']);

		echo "<div class='form_wrapper'>";
			generate_table_form('user_edit', $GLOBALS['page_user'], $user_details);
		echo "</div>";
	}

	function draw_progression_submission($module, $guild_details) {
		$guild_details['direct_encounter_details']['guild_id'] 	= $guild_details['guild_id'];
		$guild_details['direct_encounter_details']['name'] 		= $guild_details['name'];

		echo "<div class='form_wrapper'>";
			generate_table_form('encounter_add', $GLOBALS['page_user'], $guild_details['direct_encounter_details']);
		echo "</div>";
	}

	function draw_progression_edit($module, $guild_details) {
		krsort($GLOBALS['global_tier_array']);

		foreach ( $GLOBALS['global_tier_array'] as $tier => $tier_details ) {
			if ( $tier_details['encounters'] > 0 ) {

				//draw_message_banner_progress($tier_details, "", "", "", "", 0);
				
				echo "<div id='pane_$tier' class='tier'>";

					foreach ( $GLOBALS['global_dungeon_array'] as $dungeon_id => $dungeon_details ) {
						if ( $dungeon_details['tier'] == $tier ) {
							
							echo "<div class='horizontal_separator'></div>";   

							draw_message_banner_dungeons($dungeon_details);

							$dungeon_array[$dungeon_id]['valid'] = 0;

							$pane_name = $dungeon_details['name'];
							$pane_name = strtolower(str_replace(" ", "_", $pane_name));
							$pane_name = strtolower(str_replace(":", "", $pane_name));

							echo "<div id='pane_$pane_name' class='dungeon'>";
								echo "<table class='table_data'>";
									echo "<thead>";
										echo "<tr>";
											echo "<th>Encounter</th>";
											foreach ( $GLOBALS[$module]['header_progression'] as $header => $key ) {
												echo "<th>$header</th>";	
											}

											echo "<th>Options</th>";
										echo "</tr>";
									echo "</thead>";
									echo "<tbody>";
										if ( $guild_details['dungeon_details'][$dungeon_id]['complete'] > 0 ) {
											foreach ( $GLOBALS['global_encounter_array'] as $encounter_id => $encounter_details ) {
												if ( $encounter_details['dungeon_id'] == $dungeon_id && isset($guild_details['direct_encounter_details'][$encounter_id]) ) {
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
																if ( $key == "screenshot" ) $value = "<a href='{$GLOBALS['fold_guild_screenshots']}$value' data-lightbox='{$dungeon_id}'>View";
																if ( $key == "video" ) $value = "<a href='{$value}'>View";
															}
															
															echo "<td>$value</td>";
														}

														echo "<td>";
															echo "<form action='{$GLOBALS['page_user']}' method='POST'>";
																echo "<input name='encounter_id' type='hidden' value='$encounter_id'>";
																echo "<input name='guild_id' type='hidden' value='{$guild_details['guild_id']}'>";
																echo "<input name='form_encounter_edit' class='data_button' type='submit' value='Edit Details'>";
																echo " | ";
																echo "<input name='form_encounter_delete' class='data_button' type='submit' value='Delete'>";
															echo "</form>";
														echo "</td>";
													echo "</tr>";
												}
											}
										} else {
											echo "<tr>";
												echo "<td colspan='".(count($GLOBALS[$module]['header_progression'])+2)."' style='text-align:center;'>";
													echo "Currently no progression data available.";
												echo "</td>";
											echo "</tr>";
										}
									echo "</tbody>";
								echo "</table>";
							echo "</div>";
							echo "<div class='clear'></div>";
						}
					}
				echo "</div>";
			}
		}
		
		echo "<div class='clear'></div>";
	}

	function draw_submission_edit_encounter($module, $guild_details, $encounter_id) {
		$guild_details['direct_encounter_details'][$encounter_id]['guild_id'] 	= $guild_details['guild_id'];
		$guild_details['direct_encounter_details'][$encounter_id]['name'] 		= $guild_details['name'];

		echo "<div class='form_wrapper'>";
			generate_table_form('encounter_edit', $GLOBALS['page_user'], $guild_details['direct_encounter_details'][$encounter_id]);
		echo "</div>";
	}

	function draw_submission_delete_encounter($guild_details, $encounter_id) {
		$encounter_details = $guild_details['direct_encounter_details'][$encounter_id];

		draw_message_banner("Confirmation", "Are you sure you want to remove the achievement: <br>{$encounter_details['encounter_name']} on {$encounter_details['datetime']}");

		echo "<form action='{$GLOBALS['page_user']}' method='POST'>";
			echo "<input name='guild_id' type='hidden' value='{$guild_details['guild_id']}'>";
			echo "<input name='encounter_id'type='hidden' value='$encounter_id'>";
			echo "<div style='text-align:center;'>";
				echo "<input name='form_encounter_delete_confirm' type='submit' value='Confirm'>";
			echo "</div>";
		echo "</form>";
	}

	function draw_raid_team_create($module, $guild_details) {
		$name 		= "";
		$country 	= "";
		$faction 	= "";
		$server 	= "";
		$website 	= "";
		$leader 	= "";

		if ( isset($guild_details) && count($guild_details) > 0 ) {
			$name 		= isset($guild_details['name']) && strlen($guild_details['name']) > 0 ? $guild_details['name'] : "";
			$country 	= isset($guild_details['country']) && strlen($guild_details['country']) > 0 ? $guild_details['country'] : "";
			$faction 	= isset($guild_details['faction']) && strlen($guild_details['faction']) > 0 ? $guild_details['faction'] : "";
			$server 	= isset($guild_details['server']) && strlen($guild_details['server']) > 0 ? $guild_details['server'] : "";
			$website 	= isset($guild_details['website']) && strlen($guild_details['website']) > 0 ? $guild_details['website'] : "";
			$leader 	= isset($guild_details['leader']) && strlen($guild_details['leader']) > 0 ? $guild_details['leader'] : "";
		}

		echo "<div class='form_wrapper'>";
			generate_table_form('raid_team_add', $GLOBALS['page_user'], $guild_details);
		echo "</div>";
	}

	function draw_guild_create($module, $guild_details) {
		$name 		= "";
		$country 	= "";
		$faction 	= "";
		$server 	= "";
		$website 	= "";
		$leader 	= "";

		if ( isset($guild_details) && count($guild_details) > 0 ) {
			$name 		= isset($guild_details['name']) && strlen($guild_details['name']) > 0 ? $guild_details['name'] : "";
			$country 	= isset($guild_details['country']) && strlen($guild_details['country']) > 0 ? $guild_details['country'] : "";
			$faction 	= isset($guild_details['faction']) && strlen($guild_details['faction']) > 0 ? $guild_details['faction'] : "";
			$server 	= isset($guild_details['server']) && strlen($guild_details['server']) > 0 ? $guild_details['server'] : "";
			$website 	= isset($guild_details['website']) && strlen($guild_details['website']) > 0 ? $guild_details['website'] : "";
			$leader 	= isset($guild_details['leader']) && strlen($guild_details['leader']) > 0 ? $guild_details['leader'] : "";
		}

		echo "<div class='form_wrapper'>";
			generate_table_form('guild_create', $GLOBALS['page_user'], $guild_details);
		echo "</div>";
	}

	function draw_guild_edit($module, $guild_details) {
		$guild_details  = get_updated_guild_details($guild_details['guild_id']);
		$guild_logo		= get_guild_logo($guild_details['guild_id']);
		$faction_logo	= get_faction_logo($guild_details['faction']);
		
		$guild_details['creator_id'] = get_creator_emails($guild_details['creator_id']);

		echo "<div class='pane_guild_edit'>";
			generate_table_form('guild_edit', $GLOBALS['page_user'], $guild_details);
			echo "<div>";
				echo $guild_logo;
				echo $faction_logo;
			echo "</div>";
			echo "<div class=\"clear\"></div>";
		echo "</div>";
	}

	function draw_guild_delete($module, $guild_details) {
		draw_message_banner("Disbanding?", "{$GLOBALS[$module]['message']['disband_confirm']}<br>< {$guild_details['name']} >");

		echo "<form action='{$GLOBALS['page_user']}' method='POST'>";
			echo "<div style='text-align:center;'>";
				echo "<input name='guild_id' type='hidden' value='{$guild_details['guild_id']}'>";
				echo "<input name='form_guild_delete_submit' class='data_button' type='submit' value='Confirm Delete'>";
			echo "</div>";
		echo "</form>";
	}

	function get_user_guilds($user_id) {
		$guild_array = array();

		foreach ( $GLOBALS['global_guild_array'] as $guild_id => $guild_details ) {
			$user_array = explode("||", $guild_details['creator_id']);

			if ( in_array($user_id, $user_array) ) { $guild_array[$guild_id] = $guild_details; }
		}

		return $guild_array;
	}

	function refresh_guild_list() {
		$GLOBALS['global_guild_array'] = array();

		$query = get_all_guilds();
		while ($row = mysql_fetch_array($query)) { $GLOBALS['global_guild_array'][$row['guild_id']] = $row; }
	}
?>