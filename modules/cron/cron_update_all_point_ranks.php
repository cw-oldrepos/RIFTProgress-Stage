<?php
	include_once 	"properties.php";
	
	log_entry(0, "Starting Update All Point Ranks...");

	$GLOBALS['global_guild_array'] = array();
	$query = get_all_guilds();
	while ($row = mysql_fetch_array($query)) { $GLOBALS['global_guild_array'][$row['guild_id']] = $row; }

	$guild_array 						= $GLOBALS['global_guild_array'];
	$encounter_array 					= $GLOBALS['global_encounter_array'];
	$dungeon_array 						= $GLOBALS['global_dungeon_array'];
	$tier_array 						= $GLOBALS['global_tier_array'];
	$encounter_time_array 				= array();
	$num_of_guilds_dungeon_array 		= array();
	$num_of_kills_array 				= array();
	$highest_kill_array 				= array();
	$rank_encounter_points_array 		= array();
	$rank_overall_points_array 			= array();
	$rank_tier_points_array 			= array();
	$rank_tier_size_points_array 		= array();
	$rank_size_points_array 			= array();
	$rank_dungeon_points_array 			= array();
	$upload_string_array 				= array();
	$recent_encounter_array				= $recent_size_array = $recent_dungeon_array = $recent_tier_array = array();

	// Find Which Encounters Recently Uploaded Needing Point Values
	log_entry(0, "Gathering encounters in need of point rank updates.");
	$query = mysql_query(sprintf("SELECT *
					FROM %s
					WHERE update_rank = '0'
					GROUP BY encounter_id",
					mysql_real_escape_string($GLOBALS['table_recent_raid'])
					)) or die(log_entry(3, mysql_error()));
	while ($row = mysql_fetch_array($query)) {
		$encounter_id 		= $row['encounter_id'];
		$encounter_details 	= $encounter_array[$encounter_id];
		$dungeon_id 		= $encounter_details['dungeon_id'];
		$tier 				= $encounter_details['tier'];
		$size 				= $encounter_details['players'];

		$recent_tier_array[$tier] 				= $tier;
		$recent_dungeon_array[$dungeon_id] 		= $dungeon_id;
		$recent_size_array[$size] 				= $size;
		$recent_encounter_array[$encounter_id] 	= $encounter_id;
	}

	// Generate Encounter Details. Get First and Recent Kills.
	foreach ( $encounter_array as $encounter_id => $encounter_details ) {
		$encounter_array[$encounter_id] = get_encounter_details($encounter_id);
	}

	// Generate Guild Details. Generate Encounter Array With Encounter ID->Guild ID = Time (Strtotime)
	foreach ( $guild_array as $guild_id => $guild_details ) {
		$guild_details = get_guild_details($guild_id);

		foreach ( $guild_details['direct_encounter_details'] as $encounter_id => $encounter_details ) {
			$encounter_details = $encounter_array[$encounter_id];

			if ( $encounter_details['mob_type'] != 0 || $dungeon_array[$encounter_details['dungeon_id']]['dungeon_type'] != 0 ) continue; // Skip Special Dungeon/Encounters

			$encounter_time_array[$encounter_id][$guild_id] = $guild_details['direct_encounter_details'][$encounter_id]['strtotime'];
			$num_of_guilds_dungeon_array[$encounter_details['dungeon_id']][$guild_id] = $guild_id; // Adding Guild to Num Of Dungeons. Dungeon ID->Guild ID = Guild ID

			if ( !isset($num_of_kills_array[$encounter_details['dungeon_id']][$encounter_id]) ) { // Adding kill to num of kill array Dungeon ID->Encounter ID->NumOfKill
				$num_of_kills_array[$encounter_details['dungeon_id']][$encounter_id] = 1;
			} else {
				$num_of_kills_array[$encounter_details['dungeon_id']][$encounter_id]++;
			}
		}

		$guild_array[$guild_id] = $guild_details;
	}

	// Sorting each kill time by Encounter ID
	foreach ( $encounter_time_array as $encounter_id => $encounter_guild_array ) {
		asort($encounter_guild_array);
		$encounter_time_array[$encounter_id] = $encounter_guild_array;
	}

	foreach ( $dungeon_array as $dungeon_id => $dungeon_details ) {
		if ( $dungeon_details['dungeon_type'] != 0 ) continue;

		$highest_kill = 0;

		if ( isset($num_of_kills_array[$dungeon_id]) ) {
			arsort($num_of_kills_array[$dungeon_id]);

			foreach ( $num_of_kills_array[$dungeon_id] as $encounter_id => $num_of_kills ) { $highest_kill_array[$dungeon_id] = $num_of_kills; break; }
		}
	}

	ksort($encounter_time_array);

	// Encounter / Dungeon Points
	foreach ( $encounter_time_array as $encounter_id => $encounter_guild_array ) {
		if ( !isset($GLOBALS['global_encounter_array'][$encounter_id]) ) { continue; }

		$encounter_details 	= $encounter_array[$encounter_id];
		$dungeon_id 		= $encounter_details['dungeon_id'];
		$tier 				= $encounter_details['tier'];
		$raid_size			= $encounter_details['players'];

		$num_of_guilds = count($num_of_guilds_dungeon_array[$dungeon_id]);

		if ( $num_of_guilds != $highest_kill_array[$dungeon_id] ) $num_of_guilds = $highest_kill_array[$dungeon_id];

		$encounter_details['value_aeyths_start'] 	= number_format(($highest_kill_array[$dungeon_id] / $encounter_details['num_of_kills']['total']) * $GLOBALS['point_base'], 0, "","");
		$encounter_details['value_default_start'] 	= $GLOBALS['point_base'];
		$encounter_details['value_mod_start'] 		= $GLOBALS['point_base_mod'];

		if ( $dungeon_array[$dungeon_id]['final_encounter'] == $encounter_id ) {
			$encounter_details['value_default_start'] = $GLOBALS['point_final_base'] * $dungeon_array[$dungeon_id]['mobs'];		
		}
		
		foreach ( $encounter_guild_array as $guild_id => $strtotime ) {
			$guild_details = $guild_array[$guild_id];

			if ( !isset($guild_array[$guild_id]['direct_encounter_details'][$encounter_id]) ) { continue; }

			$time_diff 		= $encounter_details['first_kill_strtotime']-$strtotime;
			$total_days 	= $time_diff/(60*60*24);
			$exp_value 		= $total_days / 50;

			$aeyths_point 		= ($num_of_guilds / $encounter_details['num_of_kills']['total']) * $encounter_details['value_aeyths_start'] * exp($exp_value);
			$aeyths_mod_point 	= $encounter_details['value_mod_start'] * exp($exp_value);
			$default_point		= $encounter_details['value_default_start'] * exp($exp_value);

			if ( $dungeon_array[$dungeon_id]['final_encounter'] == $encounter_id ) $default_point = $encounter_details['value_default_start'] * exp($exp_value);

			if ( !isset($rank_encounter_points_array[$encounter_id][$guild_id]) ) {
				$rank_encounter_points_array[$encounter_id][$guild_id][0] = $default_point;
				$rank_encounter_points_array[$encounter_id][$guild_id][1] = $aeyths_point;
				$rank_encounter_points_array[$encounter_id][$guild_id][2] = $aeyths_mod_point;
			}
			
			if ( !isset($rank_overall_points_array[$guild_id]) ) {
				$rank_overall_points_array[$guild_id][1] = $aeyths_point;
				$rank_overall_points_array[$guild_id][2] = $aeyths_mod_point;
			} else {
				$rank_overall_points_array[$guild_id][1] += $aeyths_point;
				$rank_overall_points_array[$guild_id][2] += $aeyths_mod_point;
			}

			if ( !isset($rank_tier_points_array[$tier][$guild_id]) ) {
				$rank_tier_points_array[$tier][$guild_id][1] = $aeyths_point;
				$rank_tier_points_array[$tier][$guild_id][2] = $aeyths_mod_point;
			} else {
				$rank_tier_points_array[$tier][$guild_id][1] += $aeyths_point;
				$rank_tier_points_array[$tier][$guild_id][2] += $aeyths_mod_point;
			}

			if ( !isset($rank_size_points_array[$raid_size][$guild_id]) ) {
				$rank_size_points_array[$raid_size][$guild_id][1] = $aeyths_point;
				$rank_size_points_array[$raid_size][$guild_id][2] = $aeyths_mod_point;
			} else {
				$rank_size_points_array[$raid_size][$guild_id][1] += $aeyths_point;
				$rank_size_points_array[$raid_size][$guild_id][2] += $aeyths_mod_point;
			}

			if ( !isset($rank_tier_size_points_array[$tier][$raid_size][$guild_id]) ) {
				$rank_tier_size_points_array[$tier][$raid_size][$guild_id][1] = $aeyths_point;
				$rank_tier_size_points_array[$tier][$raid_size][$guild_id][2] = $aeyths_mod_point;
			} else {
				$rank_tier_size_points_array[$tier][$raid_size][$guild_id][1] += $aeyths_point;
				$rank_tier_size_points_array[$tier][$raid_size][$guild_id][2] += $aeyths_mod_point;
			}
			
			if ( !isset($rank_dungeon_points_array[$dungeon_id][$guild_id]) ) {
				$rank_dungeon_points_array[$dungeon_id][$guild_id][0] = $default_point;
				$rank_dungeon_points_array[$dungeon_id][$guild_id][1] = $aeyths_point;
				$rank_dungeon_points_array[$dungeon_id][$guild_id][2] = $aeyths_mod_point;
			} else {		
				$rank_dungeon_points_array[$dungeon_id][$guild_id][0] += $default_point;
				$rank_dungeon_points_array[$dungeon_id][$guild_id][1] += $aeyths_point;
				$rank_dungeon_points_array[$dungeon_id][$guild_id][2] += $aeyths_mod_point;
			}

			if ( $dungeon_array[$dungeon_id]['final_encounter'] == $encounter_id ) {
				$rank_encounter_points_array[$encounter_id][$guild_id][0] 		= $default_point + ($GLOBALS['point_base'] * $dungeon_array[$dungeon_id]['mobs']);
				$rank_dungeon_points_array[$dungeon_id][$guild_id][0] 			= $default_point + ($GLOBALS['point_base'] * $dungeon_array[$dungeon_id]['mobs']);
			}
		}

		$encounter_array[$encounter_id] = $encounter_details;
	}

	// Tier / Raid Size / Tier Size / Overall Points
	foreach ( $rank_dungeon_points_array as $dungeon_id => $dungeon_guild_array ) {
		$dungeon_details 	= $dungeon_array[$dungeon_id];
		$dungeon_id 		= $dungeon_details['dungeon_id'];
		$tier 				= $dungeon_details['tier'];
		$raid_size			= $dungeon_details['players'];

		foreach ( $dungeon_guild_array as $guild_id => $points[0] ) {
			if ( !isset($rank_overall_points_array[$guild_id][0]) ) {
				$rank_overall_points_array[$guild_id][0] = $rank_dungeon_points_array[$dungeon_id][$guild_id][0];
			} else {
				$rank_overall_points_array[$guild_id][0] += $rank_dungeon_points_array[$dungeon_id][$guild_id][0];
			}

			if ( !isset($rank_tier_points_array[$tier][$guild_id][0]) ) {
				$rank_tier_points_array[$tier][$guild_id][0] = $rank_dungeon_points_array[$dungeon_id][$guild_id][0];
			} else {
				$rank_tier_points_array[$tier][$guild_id][0] += $rank_dungeon_points_array[$dungeon_id][$guild_id][0];
			}

			if ( !isset($rank_size_points_array[$raid_size][$guild_id][0]) ) {
				$rank_size_points_array[$raid_size][$guild_id][0] = $rank_dungeon_points_array[$dungeon_id][$guild_id][0];
			} else {
				$rank_size_points_array[$raid_size][$guild_id][0] += $rank_dungeon_points_array[$dungeon_id][$guild_id][0];
			}

			if ( !isset($rank_tier_size_points_array[$tier][$raid_size][$guild_id][0]) ) {
				$rank_tier_size_points_array[$tier][$raid_size][$guild_id][0] = $rank_dungeon_points_array[$dungeon_id][$guild_id][0];
			} else {
				$rank_tier_size_points_array[$tier][$raid_size][$guild_id][0] += $rank_dungeon_points_array[$dungeon_id][$guild_id][0];
			}
		}
	}
	
	// Database String Format
	// Current Ranking Systems
	// 0 - Default (WildStar Progress similar to WoWProgress)
	// 1 - Aeyths (Exact copy of Rift Progress)
	// 2 - Aeyths Modified (Flat point values)

	// $$ - Delimiter for separating type of ranking system (0,1,2,3,etc)
	// ~~ - Delimiter for separating encounters/dungeons/tiers/raid_sizes
	// || - Delimiter for separating details within an encounter/dungeon/tier/raid_size
	// && - Delimiter for separating world/region/server
	// Tier Rankings
	// Tier||Points||Rank||Prev Rank||Trend
	// Ex: 1||100.00||3&&2&&1||+2&&+1&&--~~2||200.00||3&&2&&1||+2&&+1&&NEW$$

	// Tier-Raid Size Rankings
	// Tier||Size||Points||Rank||Prev Rank||Trend

	// Dungeon Rankings
	// Dungeon||Points||Rank||Prev Rank||Trend

	// Encounter Rankings
	// Encounter||Points||Rank

	// Raid Size Rankings
	// Size||Points||Rank||Prev Rank||Trend

	foreach ( $GLOBALS['global_point_system_array'] as $point_system_type => $point_system_name ) {
		foreach ( $rank_tier_points_array as $tier => $guild_id_array ) { // Creating Upload String for Tier

			$tier_details = $tier_array[$tier];

			$temp_point_array = $temp_array = $world_array = $region_array = $server_array = array();

			foreach ( $guild_id_array as $guild_id => $point_details ) { $temp_array[$guild_id] = $point_details[$point_system_type]; }

			arsort($temp_array);

			foreach ( $temp_array as $guild_id => $points ) {
				$guild_details = $guild_array[$guild_id];
				$region = $guild_details['region'];
				$server = $guild_details['server'];			

				$guild_array[$guild_id]['cron_points'] = number_format($points, 2, ".", "");

				$world_array[$guild_id] 			= $guild_array[$guild_id];
				$region_array[$region][$guild_id] 	= $guild_array[$guild_id];
				$server_array[$server][$guild_id] 	= $guild_array[$guild_id];
			}

			$rank 			= 0;
			$legacy_rank 	= 0;

			foreach ( $world_array as $guild_id => $guild_details ) {
				$guild_array[$guild_id]['cron_world_rank']	= $guild_details['tier_details'][$tier][$point_system_type]['world']['rank'];
				$guild_array[$guild_id]['cron_world_prev'] 	= $guild_details['tier_details'][$tier][$point_system_type]['world']['prev_rank'];
				$guild_array[$guild_id]['cron_world_trend']	= $guild_details['tier_details'][$tier][$point_system_type]['world']['trend'];

				$guild_array[$guild_id]['cron_legacy_world_rank']	= $guild_details['tier_details'][$tier][$point_system_type]['world']['legacy_rank'];
				$guild_array[$guild_id]['cron_legacy_world_prev'] 	= $guild_details['tier_details'][$tier][$point_system_type]['world']['legacy_prev_rank'];
				$guild_array[$guild_id]['cron_legacy_world_trend']	= $guild_details['tier_details'][$tier][$point_system_type]['world']['legacy_trend'];

				if ( !isset($recent_tier_array[$tier]) ) { continue; }

				// Legacy Rankings
				$legacy_trend 		= "--";
				$legacy_prev_rank 	= $guild_details['tier_details'][$tier][$point_system_type]['world']['legacy_rank'];
				$legacy_rank++;
				$legacy_trend 		= cron_get_trend($legacy_trend, $legacy_rank, $legacy_rank);

				$guild_array[$guild_id]['cron_legacy_world_rank']	= $legacy_rank;
				$guild_array[$guild_id]['cron_legacy_world_prev'] 	= $legacy_prev_rank;
				$guild_array[$guild_id]['cron_legacy_world_trend']	= $legacy_trend;

				//echo "Legacy Rank: {$GLOBALS['global_guild_array'][$guild_id]['name']} --- $legacy_rank<br>";

				// Active Rankings
				if ( $guild_details['active'] == 0 || $guild_details['type'] == 1 ) { 
					$guild_array[$guild_id]['cron_world_trend'] = "--";
					continue; 
				}
					
				$trend 		= "--";
				$prev_rank 	= $guild_details['tier_details'][$tier][$point_system_type]['world']['rank'];
				$rank++;
				$trend 		= cron_get_trend($trend, $rank, $prev_rank);

				$guild_array[$guild_id]['cron_world_rank']	= $rank;
				$guild_array[$guild_id]['cron_world_prev'] 	= $prev_rank;
				$guild_array[$guild_id]['cron_world_trend']	= $trend;
			}

			foreach ( $region_array as $region => $region_guild_array ) {
				$rank 			= 0;
				$legacy_rank 	= 0;

				foreach ( $region_guild_array as $guild_id => $guild_details ) {
					$guild_array[$guild_id]['cron_region_rank']		= $guild_details['tier_details'][$tier][$point_system_type]['region']['rank'];
					$guild_array[$guild_id]['cron_region_prev'] 	= $guild_details['tier_details'][$tier][$point_system_type]['region']['prev_rank'];
					$guild_array[$guild_id]['cron_region_trend']	= $guild_details['tier_details'][$tier][$point_system_type]['region']['trend'];

					$guild_array[$guild_id]['cron_legacy_region_rank']	= $guild_details['tier_details'][$tier][$point_system_type]['region']['legacy_rank'];
					$guild_array[$guild_id]['cron_legacy_region_prev'] 	= $guild_details['tier_details'][$tier][$point_system_type]['region']['legacy_prev_rank'];
					$guild_array[$guild_id]['cron_legacy_region_trend']	= $guild_details['tier_details'][$tier][$point_system_type]['region']['legacy_trend'];

					if ( !isset($recent_tier_array[$tier]) ) continue;

					// Legacy Rankings
					$legacy_trend 		= "--";
					$legacy_prev_rank 	= $guild_details['tier_details'][$tier][$point_system_type]['region']['legacy_rank'];
					$legacy_rank++;
					$legacy_trend 		= cron_get_trend($legacy_trend, $legacy_rank, $legacy_rank);

					$guild_array[$guild_id]['cron_legacy_region_rank']	= $legacy_rank;
					$guild_array[$guild_id]['cron_legacy_region_prev'] 	= $legacy_prev_rank;
					$guild_array[$guild_id]['cron_legacy_region_trend']	= $legacy_trend;

					// Active Rankings
					if ( $guild_details['active'] == 0 || $guild_details['type'] == 1 ) { 
						$guild_array[$guild_id]['cron_region_trend'] = "--";
						continue; 
					}
						
					$trend 		= "--";
					$prev_rank 	= $guild_details['tier_details'][$tier][$point_system_type]['region']['rank'];
					$rank++;
					$trend 		= cron_get_trend($trend, $rank, $prev_rank);

					$guild_array[$guild_id]['cron_region_rank']		= $rank;
					$guild_array[$guild_id]['cron_region_prev'] 	= $prev_rank;
					$guild_array[$guild_id]['cron_region_trend']	= $trend;
				}
			}

			foreach ( $server_array as $server => $server_guild_array ) {
				$rank 			= 0;
				$legacy_rank 	= 0;

				foreach ( $server_guild_array as $guild_id => $guild_details ) {
					$guild_array[$guild_id]['cron_server_rank']		= $guild_details['tier_details'][$tier][$point_system_type]['server']['rank'];
					$guild_array[$guild_id]['cron_server_prev'] 	= $guild_details['tier_details'][$tier][$point_system_type]['server']['prev_rank'];
					$guild_array[$guild_id]['cron_server_trend']	= $guild_details['tier_details'][$tier][$point_system_type]['server']['trend'];

					$guild_array[$guild_id]['cron_legacy_server_rank']	= $guild_details['tier_details'][$tier][$point_system_type]['server']['legacy_rank'];
					$guild_array[$guild_id]['cron_legacy_server_prev'] 	= $guild_details['tier_details'][$tier][$point_system_type]['server']['legacy_prev_rank'];
					$guild_array[$guild_id]['cron_legacy_server_trend']	= $guild_details['tier_details'][$tier][$point_system_type]['server']['legacy_trend'];

					if ( !isset($recent_tier_array[$tier]) ) continue;

					// Legacy Rankings
					$legacy_trend 		= "--";
					$legacy_prev_rank 	= $guild_details['tier_details'][$tier][$point_system_type]['server']['legacy_rank'];
					$legacy_rank++;
					$legacy_trend 		= cron_get_trend($legacy_trend, $legacy_rank, $legacy_rank);

					$guild_array[$guild_id]['cron_legacy_server_rank']	= $legacy_rank;
					$guild_array[$guild_id]['cron_legacy_server_prev'] 	= $legacy_prev_rank;
					$guild_array[$guild_id]['cron_legacy_server_trend']	= $legacy_trend;

					// Active Rankings
					if ( $guild_details['active'] == 0 || $guild_details['type'] == 1 ) { 
						$guild_array[$guild_id]['cron_server_trend'] = "--";
						continue; 
					}
						
					$trend 		= "--";
					$prev_rank 	= $guild_details['tier_details'][$tier][$point_system_type]['server']['rank'];
					$rank++;
					$trend 		= cron_get_trend($trend, $rank, $prev_rank);

					$guild_array[$guild_id]['cron_server_rank']	= $rank;
					$guild_array[$guild_id]['cron_server_prev'] 	= $prev_rank;
					$guild_array[$guild_id]['cron_server_trend']	= $trend;
				}
			}

			foreach ( $world_array as $guild_id => $guild_details ) { //Dungeon||Points||Rank||Prev Rank||Trend
				$guild_details = $guild_array[$guild_id];

				echo "Guild: {$GLOBALS['global_guild_array'][$guild_id]['name']} --- {$guild_details['cron_legacy_world_rank']} --- {$guild_details['cron_world_rank']} --- {$guild_details['cron_points']}<br>";

				if ( isset($upload_string_array[$guild_id][$point_system_type]['rank_tier']) ) {
					$upload_string_array[$guild_id][$point_system_type]['rank_tier'] .= 
					"~~".$tier."||".
					$guild_details['cron_points']."||".
					$guild_details['cron_world_rank']."&&".$guild_details['cron_region_rank']."&&".$guild_details['cron_server_rank']."&&".
					$guild_details['cron_legacy_world_rank']."&&".$guild_details['cron_legacy_region_rank']."&&".$guild_details['cron_legacy_server_rank']."||".
					$guild_details['cron_world_prev']."&&".$guild_details['cron_region_prev']."&&".$guild_details['cron_server_prev']."&&".
					$guild_details['cron_legacy_world_prev']."&&".$guild_details['cron_legacy_region_prev']."&&".$guild_details['cron_legacy_server_prev']."||".
					$guild_details['cron_world_trend']."&&".$guild_details['cron_region_trend']."&&".$guild_details['cron_server_trend']."&&".
					$guild_details['cron_legacy_world_trend']."&&".$guild_details['cron_legacy_region_trend']."&&".$guild_details['cron_legacy_server_trend'];				
				} else {
					$upload_string_array[$guild_id][$point_system_type]['rank_tier'] = 
					$tier."||".
					$guild_details['cron_points']."||".
					$guild_details['cron_world_rank']."&&".$guild_details['cron_region_rank']."&&".$guild_details['cron_server_rank']."&&".
					$guild_details['cron_legacy_world_rank']."&&".$guild_details['cron_legacy_region_rank']."&&".$guild_details['cron_legacy_server_rank']."||".
					$guild_details['cron_world_prev']."&&".$guild_details['cron_region_prev']."&&".$guild_details['cron_server_prev']."&&".
					$guild_details['cron_legacy_world_prev']."&&".$guild_details['cron_legacy_region_prev']."&&".$guild_details['cron_legacy_server_prev']."||".
					$guild_details['cron_world_trend']."&&".$guild_details['cron_region_trend']."&&".$guild_details['cron_server_trend']."&&".
					$guild_details['cron_legacy_world_trend']."&&".$guild_details['cron_legacy_region_trend']."&&".$guild_details['cron_legacy_server_trend'];
				}
			}
		}
		
		foreach ( $rank_dungeon_points_array as $dungeon_id => $guild_id_array ) { // Creating Upload String for Dungeon
			$temp_point_array = $temp_array = $world_array = $region_array = $server_array = array();

			$dungeon_details = $dungeon_array[$dungeon_id];

			foreach ( $guild_id_array as $guild_id => $point_details ) { $temp_array[$guild_id] = $point_details[$point_system_type]; }

			arsort($temp_array);
			
			foreach ( $temp_array as $guild_id => $points ) {
				$guild_details = $guild_array[$guild_id];
				$region = $guild_details['region'];
				$server = $guild_details['server'];			

				$guild_array[$guild_id]['cron_points'] = number_format($points, 2, ".", "");

				$world_array[$guild_id] 			= $guild_array[$guild_id];
				$region_array[$region][$guild_id] 	= $guild_array[$guild_id];
				$server_array[$server][$guild_id] 	= $guild_array[$guild_id];
			}

			$rank 			= 0;
			$legacy_rank 	= 0;

			foreach ( $world_array as $guild_id => $guild_details ) {
				$guild_array[$guild_id]['cron_world_rank']	= $guild_details['dungeon_details'][$dungeon_id][$point_system_type]['world']['rank'];
				$guild_array[$guild_id]['cron_world_prev'] 	= $guild_details['dungeon_details'][$dungeon_id][$point_system_type]['world']['prev_rank'];
				$guild_array[$guild_id]['cron_world_trend']	= $guild_details['dungeon_details'][$dungeon_id][$point_system_type]['world']['trend'];

				$guild_array[$guild_id]['cron_legacy_world_rank']	= $guild_details['dungeon_details'][$dungeon_id][$point_system_type]['world']['legacy_rank'];
				$guild_array[$guild_id]['cron_legacy_world_prev'] 	= $guild_details['dungeon_details'][$dungeon_id][$point_system_type]['world']['legacy_prev_rank'];
				$guild_array[$guild_id]['cron_legacy_world_trend']	= $guild_details['dungeon_details'][$dungeon_id][$point_system_type]['world']['legacy_trend'];

				if ( !isset($recent_dungeon_array[$dungeon_id]) ) continue;

				// Legacy Rankings
				$legacy_trend 		= "--";
				$legacy_prev_rank 	= $guild_details['dungeon_details'][$dungeon_id][$point_system_type]['world']['legacy_rank'];
				$legacy_rank++;
				$legacy_trend 		= cron_get_trend($legacy_trend, $legacy_rank, $legacy_rank);

				$guild_array[$guild_id]['cron_legacy_world_rank']	= $legacy_rank;
				$guild_array[$guild_id]['cron_legacy_world_prev'] 	= $legacy_prev_rank;
				$guild_array[$guild_id]['cron_legacy_world_trend']	= $legacy_trend;

				// Active Rankings
				if ( $guild_details['active'] == 0 || $guild_details['type'] == 1 ) { 
					$guild_array[$guild_id]['cron_world_trend'] = "--";
					continue; 
				}
					
				$trend 		= "--";
				$prev_rank 	= $guild_details['dungeon_details'][$dungeon_id][$point_system_type]['world']['rank'];
				$rank++;
				$trend 		= cron_get_trend($trend, $rank, $prev_rank);

				$guild_array[$guild_id]['cron_world_rank']	= $rank;
				$guild_array[$guild_id]['cron_world_prev'] 	= $prev_rank;
				$guild_array[$guild_id]['cron_world_trend']	= $trend;
			}

			foreach ( $region_array as $region => $region_guild_array ) {
				$rank 			= 0;
				$legacy_rank 	= 0;

				foreach ( $region_guild_array as $guild_id => $guild_details ) {
					$guild_array[$guild_id]['cron_region_rank']		= $guild_details['dungeon_details'][$dungeon_id][$point_system_type]['region']['rank'];
					$guild_array[$guild_id]['cron_region_prev'] 	= $guild_details['dungeon_details'][$dungeon_id][$point_system_type]['region']['prev_rank'];
					$guild_array[$guild_id]['cron_region_trend']	= $guild_details['dungeon_details'][$dungeon_id][$point_system_type]['region']['trend'];

					$guild_array[$guild_id]['cron_legacy_region_rank']	= $guild_details['dungeon_details'][$dungeon_id][$point_system_type]['region']['legacy_rank'];
					$guild_array[$guild_id]['cron_legacy_region_prev'] 	= $guild_details['dungeon_details'][$dungeon_id][$point_system_type]['region']['legacy_prev_rank'];
					$guild_array[$guild_id]['cron_legacy_region_trend']	= $guild_details['dungeon_details'][$dungeon_id][$point_system_type]['region']['legacy_trend'];

					if ( !isset($recent_dungeon_array[$dungeon_id]) ) continue;

					// Legacy Rankings
					$legacy_trend 		= "--";
					$legacy_prev_rank 	= $guild_details['dungeon_details'][$dungeon_id][$point_system_type]['region']['legacy_rank'];
					$legacy_rank++;
					$legacy_trend 		= cron_get_trend($legacy_trend, $legacy_rank, $legacy_rank);

					$guild_array[$guild_id]['cron_legacy_region_rank']	= $legacy_rank;
					$guild_array[$guild_id]['cron_legacy_region_prev'] 	= $legacy_prev_rank;
					$guild_array[$guild_id]['cron_legacy_region_trend']	= $legacy_trend;

					// Active Rankings
					if ( $guild_details['active'] == 0 || $guild_details['type'] == 1 ) { 
						$guild_array[$guild_id]['cron_region_trend'] = "--";
						continue; 
					}

					$trend 		= "--";
					$prev_rank 	= $guild_details['dungeon_details'][$dungeon_id][$point_system_type]['region']['rank'];
					$rank++;
					$trend 		= cron_get_trend($trend, $rank, $prev_rank);

					$guild_array[$guild_id]['cron_region_rank']		= $rank;
					$guild_array[$guild_id]['cron_region_prev'] 	= $prev_rank;
					$guild_array[$guild_id]['cron_region_trend']	= $trend;
				}
			}

			foreach ( $server_array as $server => $server_guild_array ) {
				$rank 			= 0;
				$legacy_rank 	= 0;

				foreach ( $server_guild_array as $guild_id => $guild_details ) {
					$guild_array[$guild_id]['cron_server_rank']		= $guild_details['dungeon_details'][$dungeon_id][$point_system_type]['server']['rank'];
					$guild_array[$guild_id]['cron_server_prev'] 	= $guild_details['dungeon_details'][$dungeon_id][$point_system_type]['server']['prev_rank'];
					$guild_array[$guild_id]['cron_server_trend']	= $guild_details['dungeon_details'][$dungeon_id][$point_system_type]['server']['trend'];

					$guild_array[$guild_id]['cron_legacy_server_rank']	= $guild_details['dungeon_details'][$dungeon_id][$point_system_type]['server']['legacy_rank'];
					$guild_array[$guild_id]['cron_legacy_server_prev'] 	= $guild_details['dungeon_details'][$dungeon_id][$point_system_type]['server']['legacy_prev_rank'];
					$guild_array[$guild_id]['cron_legacy_server_trend']	= $guild_details['dungeon_details'][$dungeon_id][$point_system_type]['server']['legacy_trend'];

					if ( !isset($recent_dungeon_array[$dungeon_id]) ) continue;

					// Legacy Rankings
					$legacy_trend 		= "--";
					$legacy_prev_rank 	= $guild_details['dungeon_details'][$dungeon_id][$point_system_type]['server']['legacy_rank'];
					$legacy_rank++;
					$legacy_trend 		= cron_get_trend($legacy_trend, $legacy_rank, $legacy_rank);

					$guild_array[$guild_id]['cron_legacy_server_rank']	= $legacy_rank;
					$guild_array[$guild_id]['cron_legacy_server_prev'] 	= $legacy_prev_rank;
					$guild_array[$guild_id]['cron_legacy_server_trend']	= $legacy_trend;

					// Active Rankings
					if ( $guild_details['active'] == 0 || $guild_details['type'] == 1 ) { 
						$guild_array[$guild_id]['cron_server_trend'] = "--";
						continue; 
					}

					$trend 		= "--";
					$prev_rank 	= $guild_details['dungeon_details'][$dungeon_id][$point_system_type]['server']['rank'];
					$rank++;
					$trend 		= cron_get_trend($trend, $rank, $prev_rank);
					
					$guild_array[$guild_id]['cron_server_rank']		= $rank;
					$guild_array[$guild_id]['cron_server_prev'] 	= $prev_rank;
					$guild_array[$guild_id]['cron_server_trend']	= $trend;
				}
			}

			foreach ( $world_array as $guild_id => $guild_details ) { //Dungeon||Points||Rank||Prev Rank||Trend
				$guild_details = $guild_array[$guild_id];

				if ( isset($upload_string_array[$guild_id][$point_system_type]['rank_dungeon']) ) {
					$upload_string_array[$guild_id][$point_system_type]['rank_dungeon'] .= 
					"~~".$dungeon_id."||".
					$guild_details['cron_points']."||".
					$guild_details['cron_world_rank']."&&".$guild_details['cron_region_rank']."&&".$guild_details['cron_server_rank']."&&".
					$guild_details['cron_legacy_world_rank']."&&".$guild_details['cron_legacy_region_rank']."&&".$guild_details['cron_legacy_server_rank']."||".
					$guild_details['cron_world_prev']."&&".$guild_details['cron_region_prev']."&&".$guild_details['cron_server_prev']."&&".
					$guild_details['cron_legacy_world_prev']."&&".$guild_details['cron_legacy_region_prev']."&&".$guild_details['cron_legacy_server_prev']."||".
					$guild_details['cron_world_trend']."&&".$guild_details['cron_region_trend']."&&".$guild_details['cron_server_trend']."&&".
					$guild_details['cron_legacy_world_trend']."&&".$guild_details['cron_legacy_region_trend']."&&".$guild_details['cron_legacy_server_trend'];				
				} else {
					$upload_string_array[$guild_id][$point_system_type]['rank_dungeon'] = 
					$dungeon_id."||".
					$guild_details['cron_points']."||".
					$guild_details['cron_world_rank']."&&".$guild_details['cron_region_rank']."&&".$guild_details['cron_server_rank']."&&".
					$guild_details['cron_legacy_world_rank']."&&".$guild_details['cron_legacy_region_rank']."&&".$guild_details['cron_legacy_server_rank']."||".
					$guild_details['cron_world_prev']."&&".$guild_details['cron_region_prev']."&&".$guild_details['cron_server_prev']."&&".
					$guild_details['cron_legacy_world_prev']."&&".$guild_details['cron_legacy_region_prev']."&&".$guild_details['cron_legacy_server_prev']."||".
					$guild_details['cron_world_trend']."&&".$guild_details['cron_region_trend']."&&".$guild_details['cron_server_trend']."&&".
					$guild_details['cron_legacy_world_trend']."&&".$guild_details['cron_legacy_region_trend']."&&".$guild_details['cron_legacy_server_trend'];
				}
			}
		}
		
		foreach ( $rank_size_points_array as $raid_size => $guild_id_array ) { // Creating Upload String for Raid Size
			$temp_point_array = $temp_array = $world_array = $region_array = $server_array = array();

			foreach ( $guild_id_array as $guild_id => $point_details ) { $temp_array[$guild_id] = $point_details[$point_system_type]; }

			arsort($temp_array);

			foreach ( $temp_array as $guild_id => $points ) {
				$guild_details = $guild_array[$guild_id];
				$region = $guild_details['region'];
				$server = $guild_details['server'];			

				$guild_array[$guild_id]['cron_points'] = number_format($points, 2, ".", "");

				$world_array[$guild_id] 			= $guild_array[$guild_id];
				$region_array[$region][$guild_id] 	= $guild_array[$guild_id];
				$server_array[$server][$guild_id] 	= $guild_array[$guild_id];
			}

			$rank 			= 0;
			$legacy_rank 	= 0;
			foreach ( $world_array as $guild_id => $guild_details ) {
				$guild_array[$guild_id]['cron_world_rank']	= $guild_details['direct_raid_size_details'][$raid_size][$point_system_type]['world']['rank'];
				$guild_array[$guild_id]['cron_world_prev'] 	= $guild_details['direct_raid_size_details'][$raid_size][$point_system_type]['world']['prev_rank'];
				$guild_array[$guild_id]['cron_world_trend']	= $guild_details['direct_raid_size_details'][$raid_size][$point_system_type]['world']['trend'];

				$guild_array[$guild_id]['cron_legacy_world_rank']	= $guild_details['direct_raid_size_details'][$raid_size][$point_system_type]['world']['legacy_rank'];
				$guild_array[$guild_id]['cron_legacy_world_prev'] 	= $guild_details['direct_raid_size_details'][$raid_size][$point_system_type]['world']['legacy_prev_rank'];
				$guild_array[$guild_id]['cron_legacy_world_trend']	= $guild_details['direct_raid_size_details'][$raid_size][$point_system_type]['world']['legacy_trend'];

				if ( !isset($recent_size_array[$raid_size]) ) continue;

				// Legacy Rankings
				$legacy_trend 		= "--";
				$legacy_prev_rank 	= $guild_details['direct_raid_size_details'][$raid_size][$point_system_type]['world']['legacy_rank'];
				$legacy_rank++;
				$legacy_trend 		= cron_get_trend($legacy_trend, $legacy_rank, $legacy_rank);

				$guild_array[$guild_id]['cron_legacy_world_rank']	= $legacy_rank;
				$guild_array[$guild_id]['cron_legacy_world_prev'] 	= $legacy_prev_rank;
				$guild_array[$guild_id]['cron_legacy_world_trend']	= $legacy_trend;

				// Active Rankings
				if ( $guild_details['active'] == 0 || $guild_details['type'] == 1 ) { 
					$guild_array[$guild_id]['cron_world_trend'] = "--";
					continue; 
				}
					
				$trend 		= "--";
				$prev_rank 	= $guild_details['direct_raid_size_details'][$raid_size][$point_system_type]['world']['rank'];
				$rank++;
				$trend 		= cron_get_trend($trend, $rank, $prev_rank);

				$guild_array[$guild_id]['cron_world_rank']	= $rank;
				$guild_array[$guild_id]['cron_world_prev'] 	= $prev_rank;
				$guild_array[$guild_id]['cron_world_trend']	= $trend;
			}

			foreach ( $region_array as $region => $region_guild_array ) {

				$rank 			= 0;
				$legacy_rank 	= 0;
				foreach ( $region_guild_array as $guild_id => $guild_details ) {
					$guild_array[$guild_id]['cron_region_rank']		= $guild_details['direct_raid_size_details'][$raid_size][$point_system_type]['region']['rank'];
					$guild_array[$guild_id]['cron_region_prev'] 	= $guild_details['direct_raid_size_details'][$raid_size][$point_system_type]['region']['prev_rank'];
					$guild_array[$guild_id]['cron_region_trend']	= $guild_details['direct_raid_size_details'][$raid_size][$point_system_type]['region']['trend'];

					$guild_array[$guild_id]['cron_legacy_region_rank']	= $guild_details['direct_raid_size_details'][$raid_size][$point_system_type]['region']['legacy_rank'];
					$guild_array[$guild_id]['cron_legacy_region_prev'] 	= $guild_details['direct_raid_size_details'][$raid_size][$point_system_type]['region']['legacy_prev_rank'];
					$guild_array[$guild_id]['cron_legacy_region_trend']	= $guild_details['direct_raid_size_details'][$raid_size][$point_system_type]['region']['legacy_trend'];

					if ( !isset($recent_size_array[$raid_size]) ) continue;

					// Legacy Rankings
					$legacy_trend 		= "--";
					$legacy_prev_rank 	= $guild_details['direct_raid_size_details'][$raid_size][$point_system_type]['region']['legacy_rank'];
					$legacy_rank++;
					$legacy_trend 		= cron_get_trend($legacy_trend, $legacy_rank, $legacy_rank);

					$guild_array[$guild_id]['cron_legacy_region_rank']	= $legacy_rank;
					$guild_array[$guild_id]['cron_legacy_region_prev'] 	= $legacy_prev_rank;
					$guild_array[$guild_id]['cron_legacy_region_trend']	= $legacy_trend;

					// Active Rankings
					if ( $guild_details['active'] == 0 || $guild_details['type'] == 1 ) { 
						$guild_array[$guild_id]['cron_region_trend'] = "--";
						continue; 
					}
						
					$trend 		= "--";
					$prev_rank 	= $guild_details['direct_raid_size_details'][$raid_size][$point_system_type]['region']['rank'];
					$rank++;
					$trend 		= cron_get_trend($trend, $rank, $prev_rank);

					$guild_array[$guild_id]['cron_region_rank']		= $rank;
					$guild_array[$guild_id]['cron_region_prev'] 	= $prev_rank;
					$guild_array[$guild_id]['cron_region_trend']	= $trend;
				}
			}

			foreach ( $server_array as $server => $server_guild_array ) {

				$rank 			= 0;
				$legacy_rank 	= 0;
				foreach ( $server_guild_array as $guild_id => $guild_details ) {
					$guild_array[$guild_id]['cron_server_rank']		= $guild_details['direct_raid_size_details'][$raid_size][$point_system_type]['server']['rank'];
					$guild_array[$guild_id]['cron_server_prev'] 	= $guild_details['direct_raid_size_details'][$raid_size][$point_system_type]['server']['prev_rank'];
					$guild_array[$guild_id]['cron_server_trend']	= $guild_details['direct_raid_size_details'][$raid_size][$point_system_type]['server']['trend'];

					$guild_array[$guild_id]['cron_legacy_server_rank']	= $guild_details['direct_raid_size_details'][$raid_size][$point_system_type]['server']['legacy_rank'];
					$guild_array[$guild_id]['cron_legacy_server_prev'] 	= $guild_details['direct_raid_size_details'][$raid_size][$point_system_type]['server']['legacy_prev_rank'];
					$guild_array[$guild_id]['cron_legacy_server_trend']	= $guild_details['direct_raid_size_details'][$raid_size][$point_system_type]['server']['legacy_trend'];

					if ( !isset($recent_size_array[$raid_size]) ) continue;

					// Legacy Rankings
					$legacy_trend 		= "--";
					$legacy_prev_rank 	= $guild_details['direct_raid_size_details'][$raid_size][$point_system_type]['server']['legacy_rank'];
					$legacy_rank++;
					$legacy_trend 		= cron_get_trend($legacy_trend, $legacy_rank, $legacy_rank);

					$guild_array[$guild_id]['cron_legacy_server_rank']	= $legacy_rank;
					$guild_array[$guild_id]['cron_legacy_server_prev'] 	= $legacy_prev_rank;
					$guild_array[$guild_id]['cron_legacy_server_trend']	= $legacy_trend;

					// Active Rankings
					if ( $guild_details['active'] == 0 || $guild_details['type'] == 1 ) { 
						$guild_array[$guild_id]['cron_server_trend'] = "--";
						continue; 
					}
						
					$trend 		= "--";
					$prev_rank 	= $guild_details['direct_raid_size_details'][$raid_size][$point_system_type]['server']['rank'];
					$rank++;
					$trend 		= cron_get_trend($trend, $rank, $prev_rank);

					$guild_array[$guild_id]['cron_server_rank']		= $rank;
					$guild_array[$guild_id]['cron_server_prev'] 	= $prev_rank;
					$guild_array[$guild_id]['cron_server_trend']	= $trend;
				}
			}

			foreach ( $world_array as $guild_id => $guild_details ) { //Dungeon||Points||Rank||Prev Rank||Trend
				$guild_details = $guild_array[$guild_id];

				if ( isset($upload_string_array[$guild_id][$point_system_type]['rank_size']) ) {
					$upload_string_array[$guild_id][$point_system_type]['rank_size'] .= 
					"~~".$raid_size."||".
					$guild_details['cron_points']."||".
					$guild_details['cron_world_rank']."&&".$guild_details['cron_region_rank']."&&".$guild_details['cron_server_rank']."&&".
					$guild_details['cron_legacy_world_rank']."&&".$guild_details['cron_legacy_region_rank']."&&".$guild_details['cron_legacy_server_rank']."||".
					$guild_details['cron_world_prev']."&&".$guild_details['cron_region_prev']."&&".$guild_details['cron_server_prev']."&&".
					$guild_details['cron_legacy_world_prev']."&&".$guild_details['cron_legacy_region_prev']."&&".$guild_details['cron_legacy_server_prev']."||".
					$guild_details['cron_world_trend']."&&".$guild_details['cron_region_trend']."&&".$guild_details['cron_server_trend']."&&".
					$guild_details['cron_legacy_world_trend']."&&".$guild_details['cron_legacy_region_trend']."&&".$guild_details['cron_legacy_server_trend'];					
				} else {
					$upload_string_array[$guild_id][$point_system_type]['rank_size'] = 
					$raid_size."||".
					$guild_details['cron_points']."||".
					$guild_details['cron_world_rank']."&&".$guild_details['cron_region_rank']."&&".$guild_details['cron_server_rank']."&&".
					$guild_details['cron_legacy_world_rank']."&&".$guild_details['cron_legacy_region_rank']."&&".$guild_details['cron_legacy_server_rank']."||".
					$guild_details['cron_world_prev']."&&".$guild_details['cron_region_prev']."&&".$guild_details['cron_server_prev']."&&".
					$guild_details['cron_legacy_world_prev']."&&".$guild_details['cron_legacy_region_prev']."&&".$guild_details['cron_legacy_server_prev']."||".
					$guild_details['cron_world_trend']."&&".$guild_details['cron_region_trend']."&&".$guild_details['cron_server_trend']."&&".
					$guild_details['cron_legacy_world_trend']."&&".$guild_details['cron_legacy_region_trend']."&&".$guild_details['cron_legacy_server_trend'];	
				}
			}
		}
				
		foreach ( $rank_tier_size_points_array as $tier => $raid_size_array ) { // Creating Upload String for Tier Size
			$tier_details = $tier_array[$tier];

			foreach ( $raid_size_array as $raid_size => $guild_id_array ) {
				$temp_point_array = $temp_array = $world_array = $region_array = $server_array = array();

				foreach ( $guild_id_array as $guild_id => $point_details ) { $temp_array[$guild_id] = $point_details[$point_system_type]; }

				arsort($temp_array);

				foreach ( $temp_array as $guild_id => $points ) {
					$guild_details = $guild_array[$guild_id];
					$region = $guild_details['region'];
					$server = $guild_details['server'];			

					$guild_array[$guild_id]['cron_points'] = number_format($points, 2, ".", "");

					$world_array[$guild_id] 			= $guild_array[$guild_id];
					$region_array[$region][$guild_id] 	= $guild_array[$guild_id];
					$server_array[$server][$guild_id] 	= $guild_array[$guild_id];
				}

				$rank 			= 0;
				$legacy_rank 	= 0;
				foreach ( $world_array as $guild_id => $guild_details ) {
					$guild_array[$guild_id]['cron_world_rank']	= $guild_details['raid_size_details'][$tier][$raid_size][$point_system_type]['world']['rank'];
					$guild_array[$guild_id]['cron_world_prev'] 	= $guild_details['raid_size_details'][$tier][$raid_size][$point_system_type]['world']['prev_rank'];
					$guild_array[$guild_id]['cron_world_trend']	= $guild_details['raid_size_details'][$tier][$raid_size][$point_system_type]['world']['trend'];

					$guild_array[$guild_id]['cron_legacy_world_rank']	= $guild_details['raid_size_details'][$tier][$raid_size][$point_system_type]['world']['legacy_rank'];
					$guild_array[$guild_id]['cron_legacy_world_prev'] 	= $guild_details['raid_size_details'][$tier][$raid_size][$point_system_type]['world']['legacy_prev_rank'];
					$guild_array[$guild_id]['cron_legacy_world_trend']	= $guild_details['raid_size_details'][$tier][$raid_size][$point_system_type]['world']['legacy_trend'];

					if ( !isset($recent_tier_array[$tier]) ) continue;
					if ( !isset($recent_size_array[$raid_size]) ) continue;

					// Legacy Rankings
					$legacy_trend 		= "--";
					$legacy_prev_rank 	= $guild_details['raid_size_details'][$tier][$raid_size][$point_system_type]['world']['legacy_rank'];
					$legacy_rank++;
					$legacy_trend 		= cron_get_trend($legacy_trend, $legacy_rank, $legacy_rank);

					$guild_array[$guild_id]['cron_legacy_world_rank']	= $legacy_rank;
					$guild_array[$guild_id]['cron_legacy_world_prev'] 	= $legacy_prev_rank;
					$guild_array[$guild_id]['cron_legacy_world_trend']	= $legacy_trend;

					// Active Rankings
					if ( $guild_details['active'] == 0 || $guild_details['type'] == 1 ) { 
						$guild_array[$guild_id]['cron_world_trend'] = "--";
						continue; 
					}
						
					$trend 		= "--";
					$prev_rank 	= $guild_details['raid_size_details'][$tier][$raid_size][$point_system_type]['world']['rank'];
					$rank++;
					$trend 		= cron_get_trend($trend, $rank, $prev_rank);

					$guild_array[$guild_id]['cron_world_rank']	= $rank;
					$guild_array[$guild_id]['cron_world_prev'] 	= $prev_rank;
					$guild_array[$guild_id]['cron_world_trend']	= $trend;
				}

				foreach ( $region_array as $region => $region_guild_array ) {

					$rank 			= 0;
					$legacy_rank 	= 0;
					foreach ( $region_guild_array as $guild_id => $guild_details ) {
						$guild_array[$guild_id]['cron_region_rank']		= $guild_details['raid_size_details'][$tier][$raid_size][$point_system_type]['region']['rank'];
						$guild_array[$guild_id]['cron_region_prev'] 	= $guild_details['raid_size_details'][$tier][$raid_size][$point_system_type]['region']['prev_rank'];
						$guild_array[$guild_id]['cron_region_trend']	= $guild_details['raid_size_details'][$tier][$raid_size][$point_system_type]['region']['trend'];

						$guild_array[$guild_id]['cron_legacy_region_rank']	= $guild_details['raid_size_details'][$tier][$raid_size][$point_system_type]['region']['legacy_rank'];
						$guild_array[$guild_id]['cron_legacy_region_prev'] 	= $guild_details['raid_size_details'][$tier][$raid_size][$point_system_type]['region']['legacy_prev_rank'];
						$guild_array[$guild_id]['cron_legacy_region_trend']	= $guild_details['raid_size_details'][$tier][$raid_size][$point_system_type]['region']['legacy_trend'];

						if ( !isset($recent_tier_array[$tier]) ) continue;
						if ( !isset($recent_size_array[$raid_size]) ) continue;

						// Legacy Rankings
						$legacy_trend 		= "--";
						$legacy_prev_rank 	= $guild_details['raid_size_details'][$tier][$raid_size][$point_system_type]['region']['legacy_rank'];
						$legacy_rank++;
						$legacy_trend 		= cron_get_trend($legacy_trend, $legacy_rank, $legacy_rank);

						$guild_array[$guild_id]['cron_legacy_region_rank']	= $legacy_rank;
						$guild_array[$guild_id]['cron_legacy_region_prev'] 	= $legacy_prev_rank;
						$guild_array[$guild_id]['cron_legacy_region_trend']	= $legacy_trend;

						// Active Rankings
						if ( $guild_details['active'] == 0 || $guild_details['type'] == 1 ) { 
							$guild_array[$guild_id]['cron_region_trend'] = "--";
							continue; 
						}
							
						$trend 		= "--";
						$prev_rank 	= $guild_details['raid_size_details'][$tier][$raid_size][$point_system_type]['region']['rank'];
						$rank++;
						$trend 		= cron_get_trend($trend, $rank, $prev_rank);

						$guild_array[$guild_id]['cron_region_rank']		= $rank;
						$guild_array[$guild_id]['cron_region_prev'] 	= $prev_rank;
						$guild_array[$guild_id]['cron_region_trend']	= $trend;
					}
				}

				foreach ( $server_array as $server => $server_guild_array ) {

					$rank 			= 0;
					$legacy_rank 	= 0;
					foreach ( $server_guild_array as $guild_id => $guild_details ) {
						$guild_array[$guild_id]['cron_server_rank']		= $guild_details['raid_size_details'][$tier][$raid_size][$point_system_type]['server']['rank'];
						$guild_array[$guild_id]['cron_server_prev'] 	= $guild_details['raid_size_details'][$tier][$raid_size][$point_system_type]['server']['prev_rank'];
						$guild_array[$guild_id]['cron_server_trend']	= $guild_details['raid_size_details'][$tier][$raid_size][$point_system_type]['server']['trend'];

						$guild_array[$guild_id]['cron_legacy_server_rank']	= $guild_details['raid_size_details'][$tier][$raid_size][$point_system_type]['server']['legacy_rank'];
						$guild_array[$guild_id]['cron_legacy_server_prev'] 	= $guild_details['raid_size_details'][$tier][$raid_size][$point_system_type]['server']['legacy_prev_rank'];
						$guild_array[$guild_id]['cron_legacy_server_trend']	= $guild_details['raid_size_details'][$tier][$raid_size][$point_system_type]['server']['legacy_trend'];

						if ( !isset($recent_tier_array[$tier]) ) continue;
						if ( !isset($recent_size_array[$raid_size]) ) continue;

						// Legacy Rankings
						$legacy_trend 		= "--";
						$legacy_prev_rank 	= $guild_details['raid_size_details'][$tier][$raid_size][$point_system_type]['server']['legacy_rank'];
						$legacy_rank++;
						$legacy_trend 		= cron_get_trend($legacy_trend, $legacy_rank, $legacy_rank);

						$guild_array[$guild_id]['cron_legacy_server_rank']	= $legacy_rank;
						$guild_array[$guild_id]['cron_legacy_server_prev'] 	= $legacy_prev_rank;
						$guild_array[$guild_id]['cron_legacy_server_trend']	= $legacy_trend;

						// Active Rankings
						if ( $guild_details['active'] == 0 || $guild_details['type'] == 1 ) { 
							$guild_array[$guild_id]['cron_server_trend'] = "--";
							continue; 
						}
							
						$trend 		= "--";
						$prev_rank 	= $guild_details['raid_size_details'][$tier][$raid_size][$point_system_type]['server']['rank'];
						$rank++;
						$trend 		= cron_get_trend($trend, $rank, $prev_rank);

						$guild_array[$guild_id]['cron_server_rank']	= $rank;
						$guild_array[$guild_id]['cron_server_prev'] 	= $prev_rank;
						$guild_array[$guild_id]['cron_server_trend']	= $trend;
					}
				}

				foreach ( $world_array as $guild_id => $guild_details ) { //Dungeon||Points||Rank||Prev Rank||Trend
					$guild_details = $guild_array[$guild_id];

					if ( isset($upload_string_array[$guild_id][$point_system_type]['rank_tier_size']) ) {
						$upload_string_array[$guild_id][$point_system_type]['rank_tier_size'] .= 
						"~~".$tier."||".$raid_size."||".
						$guild_details['cron_points']."||".
						$guild_details['cron_world_rank']."&&".$guild_details['cron_region_rank']."&&".$guild_details['cron_server_rank']."&&".
						$guild_details['cron_legacy_world_rank']."&&".$guild_details['cron_legacy_region_rank']."&&".$guild_details['cron_legacy_server_rank']."||".
						$guild_details['cron_world_prev']."&&".$guild_details['cron_region_prev']."&&".$guild_details['cron_server_prev']."&&".
						$guild_details['cron_legacy_world_prev']."&&".$guild_details['cron_legacy_region_prev']."&&".$guild_details['cron_legacy_server_prev']."||".
						$guild_details['cron_world_trend']."&&".$guild_details['cron_region_trend']."&&".$guild_details['cron_server_trend']."&&".
						$guild_details['cron_legacy_world_trend']."&&".$guild_details['cron_legacy_region_trend']."&&".$guild_details['cron_legacy_server_trend'];				
					} else {
						$upload_string_array[$guild_id][$point_system_type]['rank_tier_size'] = 
						$tier."||".$raid_size."||".
						$guild_details['cron_points']."||".
						$guild_details['cron_world_rank']."&&".$guild_details['cron_region_rank']."&&".$guild_details['cron_server_rank']."&&".
						$guild_details['cron_legacy_world_rank']."&&".$guild_details['cron_legacy_region_rank']."&&".$guild_details['cron_legacy_server_rank']."||".
						$guild_details['cron_world_prev']."&&".$guild_details['cron_region_prev']."&&".$guild_details['cron_server_prev']."&&".
						$guild_details['cron_legacy_world_prev']."&&".$guild_details['cron_legacy_region_prev']."&&".$guild_details['cron_legacy_server_prev']."||".
						$guild_details['cron_world_trend']."&&".$guild_details['cron_region_trend']."&&".$guild_details['cron_server_trend']."&&".
						$guild_details['cron_legacy_world_trend']."&&".$guild_details['cron_legacy_region_trend']."&&".$guild_details['cron_legacy_server_trend'];	
					}
				}
			}
		}
		
		foreach ( $rank_encounter_points_array as $encounter_id => $guild_id_array ) { // Creating Upload String for Encounter
			$temp_point_array = $temp_array = $world_array = $region_array = $server_array = array();

			foreach ( $guild_id_array as $guild_id => $point_details ) { $temp_array[$guild_id] = $point_details[$point_system_type]; }

			arsort($temp_array);

			foreach ( $temp_array as $guild_id => $points ) {
				$guild_details = $guild_array[$guild_id];

				//echo "{$guild_details['name']} --- {$guild_array[$guild_id]['cron_points']}<br>"; 

				$region = $guild_details['region'];
				$server = $guild_details['server'];			

				$guild_array[$guild_id]['cron_points'] = number_format($points, 2, ".", "");

				$world_array[$guild_id] 			= $guild_array[$guild_id];
				$region_array[$region][$guild_id] 	= $guild_array[$guild_id];
				$server_array[$server][$guild_id] 	= $guild_array[$guild_id];
			}

			$rank 			= 0;
			$legacy_rank 	= 0;
			
			foreach ( $world_array as $guild_id => $guild_details ) {
				$legacy_rank++;
				$guild_array[$guild_id]['cron_legacy_world_rank'] = $legacy_rank;

				if ( $guild_details['active'] == 0 || $guild_details['type'] == 1 ) { 
					$guild_array[$guild_id]['cron_world_trend'] = "--";
					continue; 
				}

				$rank++;
				$guild_array[$guild_id]['cron_world_rank']	= $rank;
			}

			foreach ( $region_array as $region => $region_guild_array ) {
				$rank 			= 0;
				$legacy_rank 	= 0;

				foreach ( $region_guild_array as $guild_id => $guild_details ) {
					$legacy_rank++;
					$guild_array[$guild_id]['cron_legacy_region_rank'] = $legacy_rank;

					if ( $guild_details['active'] == 0 || $guild_details['type'] == 1 ) { 
						$guild_array[$guild_id]['cron_region_trend'] = "--";
						continue; 
					}

					$rank++;
					$guild_array[$guild_id]['cron_region_rank']	= $rank;
				}
			}

			foreach ( $server_array as $server => $server_guild_array ) {
				$rank 			= 0;
				$legacy_rank 	= 0;

				foreach ( $server_guild_array as $guild_id => $guild_details ) {
					$legacy_rank++;
					$guild_array[$guild_id]['cron_legacy_server_rank'] = $legacy_rank;

					if ( $guild_details['active'] == 0 || $guild_details['type'] == 1 ) { 
						$guild_array[$guild_id]['cron_server_trend'] = "--";
						continue; 
					}

					$rank++;
					$guild_array[$guild_id]['cron_server_rank']	= $rank;
				}
			}

			foreach ( $world_array as $guild_id => $guild_details ) { //Dungeon||Points||Rank||Prev Rank||Trend
				$guild_details = $guild_array[$guild_id];

				if ( isset($upload_string_array[$guild_id][$point_system_type]['rank_encounter']) ) {
					$upload_string_array[$guild_id][$point_system_type]['rank_encounter'] .= 
					"~~".$encounter_id."||".
					$guild_details['cron_points']."||".
					$guild_details['cron_world_rank']."&&".$guild_details['cron_region_rank']."&&".$guild_details['cron_server_rank'];			
				} else {
					$upload_string_array[$guild_id][$point_system_type]['rank_encounter'] = 
					$encounter_id."||".
					$guild_details['cron_points']."||".
					$guild_details['cron_world_rank']."&&".$guild_details['cron_region_rank']."&&".$guild_details['cron_server_rank'];
				}
			}
		}
	}
	
	foreach ( $GLOBALS['global_point_system_array'] as $point_system_type => $point_system_type_name ) { // Creating Upload String for Overall
		$temp_point_array = $temp_array = $world_array = $region_array = $server_array = array();

		foreach ( $rank_overall_points_array as $guild_id => $point_details ) { $temp_array[$guild_id] = $point_details[$point_system_type]; }

		arsort($temp_array);
		
		foreach ( $temp_array as $guild_id => $points ) {
			$guild_details = $guild_array[$guild_id];
			$region = $guild_details['region'];
			$server = $guild_details['server'];			

			$guild_array[$guild_id]['cron_points'] = number_format($points, 2, ".", "");

			$world_array[$guild_id] 			= $guild_array[$guild_id];
			$region_array[$region][$guild_id] 	= $guild_array[$guild_id];
			$server_array[$server][$guild_id] 	= $guild_array[$guild_id];
		}

		$rank 			= 0;
		$legacy_rank 	= 0;
		foreach ( $world_array as $guild_id => $guild_details ) {
			$guild_array[$guild_id]['cron_world_rank']	= $guild_details['overall_details'][$point_system_type]['world']['rank'];
			$guild_array[$guild_id]['cron_world_prev'] 	= $guild_details['overall_details'][$point_system_type]['world']['prev_rank'];
			$guild_array[$guild_id]['cron_world_trend']	= $guild_details['overall_details'][$point_system_type]['world']['trend'];

			$guild_array[$guild_id]['cron_legacy_world_rank']	= $guild_details['overall_details'][$point_system_type]['world']['legacy_rank'];
			$guild_array[$guild_id]['cron_legacy_world_prev'] 	= $guild_details['overall_details'][$point_system_type]['world']['legacy_prev_rank'];
			$guild_array[$guild_id]['cron_legacy_world_trend']	= $guild_details['overall_details'][$point_system_type]['world']['legacy_trend'];

			if ( !isset($recent_encounter_array) || count($recent_encounter_array) == 0 ) continue;

			// Legacy Rankings
			$legacy_trend 		= "--";
			$legacy_prev_rank 	= $guild_details['overall_details'][$point_system_type]['world']['legacy_rank'];
			$legacy_rank++;
			$legacy_trend 		= cron_get_trend($legacy_trend, $legacy_rank, $legacy_rank);

			$guild_array[$guild_id]['cron_legacy_world_rank']	= $legacy_rank;
			$guild_array[$guild_id]['cron_legacy_world_prev'] 	= $legacy_prev_rank;
			$guild_array[$guild_id]['cron_legacy_world_trend']	= $legacy_trend;

			// Active Rankings
			if ( $guild_details['active'] == 0 || $guild_details['type'] == 1 ) { 
				$guild_array[$guild_id]['cron_world_trend'] = "--";
				continue; 
			}
				
			$trend 		= "--";
			$prev_rank 	= $guild_details['overall_details'][$point_system_type]['world']['rank'];
			$rank++;
			$trend 		= cron_get_trend($trend, $rank, $prev_rank);

			$guild_array[$guild_id]['cron_world_rank']	= $rank;
			$guild_array[$guild_id]['cron_world_prev'] 	= $prev_rank;
			$guild_array[$guild_id]['cron_world_trend']	= $trend;
		}

		foreach ( $region_array as $region => $region_guild_array ) {
			$rank = 0;

			foreach ( $region_guild_array as $guild_id => $guild_details ) {
				$guild_array[$guild_id]['cron_region_rank']		= $guild_details['overall_details'][$point_system_type]['region']['rank'];
				$guild_array[$guild_id]['cron_region_prev'] 	= $guild_details['overall_details'][$point_system_type]['region']['prev_rank'];
				$guild_array[$guild_id]['cron_region_trend']	= $guild_details['overall_details'][$point_system_type]['region']['trend'];

				$guild_array[$guild_id]['cron_legacy_region_rank']	= $guild_details['overall_details'][$point_system_type]['region']['legacy_rank'];
				$guild_array[$guild_id]['cron_legacy_region_prev'] 	= $guild_details['overall_details'][$point_system_type]['region']['legacy_prev_rank'];
				$guild_array[$guild_id]['cron_legacy_region_trend']	= $guild_details['overall_details'][$point_system_type]['region']['legacy_trend'];

				if ( !isset($recent_encounter_array) || count($recent_encounter_array) == 0 ) continue;

				// Legacy Rankings
				$legacy_trend 		= "--";
				$legacy_prev_rank 	= $guild_details['overall_details'][$point_system_type]['region']['legacy_rank'];
				$legacy_rank++;
				$legacy_trend 		= cron_get_trend($legacy_trend, $legacy_rank, $legacy_rank);

				$guild_array[$guild_id]['cron_legacy_region_rank']	= $legacy_rank;
				$guild_array[$guild_id]['cron_legacy_region_prev'] 	= $legacy_prev_rank;
				$guild_array[$guild_id]['cron_legacy_region_trend']	= $legacy_trend;

				// Active Rankings
				if ( $guild_details['active'] == 0 || $guild_details['type'] == 1 ) { 
					$guild_array[$guild_id]['cron_region_trend'] = "--";
					continue; 
				}
					
				$trend 		= "--";
				$prev_rank 	= $guild_details['overall_details'][$point_system_type]['region']['rank'];
				$rank++;
				$trend 		= cron_get_trend($trend, $rank, $prev_rank);

				$guild_array[$guild_id]['cron_region_rank']		= $rank;
				$guild_array[$guild_id]['cron_region_prev'] 	= $prev_rank;
				$guild_array[$guild_id]['cron_region_trend']	= $trend;
			}
		}

		foreach ( $server_array as $server => $server_guild_array ) {
			$rank = 0;

			foreach ( $server_guild_array as $guild_id => $guild_details ) {
				$guild_array[$guild_id]['cron_server_rank']		= $guild_details['overall_details'][$point_system_type]['server']['rank'];
				$guild_array[$guild_id]['cron_server_prev'] 	= $guild_details['overall_details'][$point_system_type]['server']['prev_rank'];
				$guild_array[$guild_id]['cron_server_trend']	= $guild_details['overall_details'][$point_system_type]['server']['trend'];

				$guild_array[$guild_id]['cron_legacy_server_rank']	= $guild_details['overall_details'][$point_system_type]['server']['legacy_rank'];
				$guild_array[$guild_id]['cron_legacy_server_prev'] 	= $guild_details['overall_details'][$point_system_type]['server']['legacy_prev_rank'];
				$guild_array[$guild_id]['cron_legacy_server_trend']	= $guild_details['overall_details'][$point_system_type]['server']['legacy_trend'];

				if ( !isset($recent_encounter_array) || count($recent_encounter_array) == 0 ) continue;

				// Legacy Rankings
				$legacy_trend 		= "--";
				$legacy_prev_rank 	= $guild_details['overall_details'][$point_system_type]['server']['legacy_rank'];
				$legacy_rank++;
				$legacy_trend 		= cron_get_trend($legacy_trend, $legacy_rank, $legacy_rank);

				$guild_array[$guild_id]['cron_legacy_server_rank']	= $legacy_rank;
				$guild_array[$guild_id]['cron_legacy_server_prev'] 	= $legacy_prev_rank;
				$guild_array[$guild_id]['cron_legacy_server_trend']	= $legacy_trend;

				// Active Rankings
				if ( $guild_details['active'] == 0 || $guild_details['type'] == 1 ) { 
					$guild_array[$guild_id]['cron_server_trend'] = "--";
					continue; 
				}
					
				$trend 		= "--";
				$prev_rank 	= $guild_details['overall_details'][$point_system_type]['server']['rank'];
				$rank++;
				$trend 		= cron_get_trend($trend, $rank, $prev_rank);

				$guild_array[$guild_id]['cron_server_rank']	= $rank;
				$guild_array[$guild_id]['cron_server_prev'] 	= $prev_rank;
				$guild_array[$guild_id]['cron_server_trend']	= $trend;
			}
		}

		foreach ( $world_array as $guild_id => $guild_details ) {
			$guild_details = $guild_array[$guild_id];

			// OVERALL
			if ( isset($upload_string_array[$guild_id][$point_system_type]['rank_overall']) ) {
				$upload_string_array[$guild_id][$point_system_type]['rank_overall'] .= 
				"~~".
				$guild_details['cron_points']."||".
				$guild_details['cron_world_rank']."&&".$guild_details['cron_region_rank']."&&".$guild_details['cron_server_rank']."&&".
				$guild_details['cron_legacy_world_rank']."&&".$guild_details['cron_legacy_region_rank']."&&".$guild_details['cron_legacy_server_rank']."||".
				$guild_details['cron_world_prev']."&&".$guild_details['cron_region_prev']."&&".$guild_details['cron_server_prev']."&&".
				$guild_details['cron_legacy_world_prev']."&&".$guild_details['cron_legacy_region_prev']."&&".$guild_details['cron_legacy_server_prev']."||".
				$guild_details['cron_world_trend']."&&".$guild_details['cron_region_trend']."&&".$guild_details['cron_server_trend']."&&".
				$guild_details['cron_legacy_world_trend']."&&".$guild_details['cron_legacy_region_trend']."&&".$guild_details['cron_legacy_server_trend'];					
			} else {
				$upload_string_array[$guild_id][$point_system_type]['rank_overall'] = 
				$guild_details['cron_points']."||".
				$guild_details['cron_world_rank']."&&".$guild_details['cron_region_rank']."&&".$guild_details['cron_server_rank']."&&".
				$guild_details['cron_legacy_world_rank']."&&".$guild_details['cron_legacy_region_rank']."&&".$guild_details['cron_legacy_server_rank']."||".
				$guild_details['cron_world_prev']."&&".$guild_details['cron_region_prev']."&&".$guild_details['cron_server_prev']."&&".
				$guild_details['cron_legacy_world_prev']."&&".$guild_details['cron_legacy_region_prev']."&&".$guild_details['cron_legacy_server_prev']."||".
				$guild_details['cron_world_trend']."&&".$guild_details['cron_region_trend']."&&".$guild_details['cron_server_trend']."&&".
				$guild_details['cron_legacy_world_trend']."&&".$guild_details['cron_legacy_region_trend']."&&".$guild_details['cron_legacy_server_trend'];	
			}
		}
	}
	
	// Combine All Upload Strings And Submit To Database
	foreach ( $upload_string_array as $guild_id => $ranking_type_array ) {
		$rank_string_array = array();

		foreach ( $ranking_type_array as $type => $ranking_column_array ) {
			foreach ( $ranking_column_array as $column => $string ) {
				
				if ( isset($rank_string_array[$column]) ) {
					$rank_string_array[$column] .= "$$".$string;			
				} else {
					$rank_string_array[$column] = $string;
				}
			}
		}

		if ( count($rank_string_array[$column]) > 0 ) {
			$upload_string = "";

			foreach ( $rank_string_array as $column => $string ) {
				//if ( $column =='rank_overall' ) { continue; }
				//if ( $column =='rank_tier' ) { continue; } 
				//if ( $column =='rank_size' ) { continue; }
				//if ( $column =='rank_tier_size' ) { continue; }

				if ( isset($upload_string) && strlen($upload_string) > 0 ) {
					$upload_string .= ",$column = '".mysql_real_escape_string($string)."'";
				} else {
					$upload_string = "SET $column = '".mysql_real_escape_string($string)."'";
				}
			}

			$query = mysql_query(sprintf("UPDATE %s
								%s
								WHERE guild_id='%s'",
								mysql_real_escape_string($GLOBALS['table_guild']),
								$upload_string,
								mysql_real_escape_string($guild_id)
								)) or die(draw_error_page());
		}
	}

	// Clear All Update_Rank Flags In Recent Raid Table
	log_entry(0, "Setting all recently update encounters to updated.");
	$query = mysql_query(sprintf(
		"UPDATE %s
		 SET update_rank='1'
		 WHERE update_rank='0'",
		 mysql_real_escape_string($GLOBALS['table_recent_raid'])
		 )) or die(log_entry(3, mysql_error()));

	log_entry(0, "Update All Point Ranks Completed!");

	function cron_get_trend($trend, $rank, $prev_rank) {
		if ( $prev_rank == "N/A" ) {
			$trend = "NEW";
		} else if ( $prev_rank != "N/A" ) {
			if ( $rank > $prev_rank ) {
				$trend = "-".($rank - $prev_rank);
			} else if ( $rank < $prev_rank ) {
				$trend = "+".($prev_rank - $rank);
			}
		}

		return $trend;
	}
?>