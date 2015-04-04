<?php
	include_once 	"properties.php";

	log_entry(0, "Starting Update Encounter Ranks...");

	$guild_array 			= $GLOBALS['global_guild_array'];
	$region_array 			= $GLOBALS['global_region_array'];
	$server_array			= $GLOBALS['global_server_array'];
	$kill_array				= array();


	foreach  ( $guild_array as $guild_id => $value ) {
		$guild_array[$guild_id] = get_guild_details($guild_id);

		foreach ( $guild_array[$guild_id]['direct_encounter_details'] as $encounter_id => $encounter_details ) {
			//if ( $encounter_id == 203 ) echo "{$GLOBALS['global_guild_array'][$guild_id]['name']}<br>{$encounter_details['strtotime']}<br>"; 

			$kill_array[$encounter_id][$guild_id] = $encounter_details['strtotime'];
		}
	}

	foreach  ( $kill_array as $encounter_id => $value ) {
		$encounter_array = $kill_array[$encounter_id];

		asort($encounter_array);

		$kill_array[$encounter_id] = $encounter_array;
	}
	
	foreach  ( $kill_array as $encounter_id => $value ) {
		if ( !isset($GLOBALS['global_encounter_array'][$encounter_id]) || $encounter_id == "" ) { continue; }

		$world_guild_array 	= $region_guild_array = $server_guild_array = array();
		$encounter_details 	= get_encounter_details($encounter_id);
		$encounter_array 	= $kill_array[$encounter_id];

		foreach  ( $encounter_array as $guild_id => $value) {
			$guild_details = $guild_array[$guild_id];
			
			$world_guild_array[$guild_id] 								= $value;
			$region_guild_array[$guild_details['region']][$guild_id] 	= $value;
			$server_guild_array[$guild_details['server']][$guild_id] 	= $value;
		}

		foreach  ( $region_guild_array as $region => $guild_id_array) {
			$region_rank = 1;

			foreach  ( $guild_id_array as $guild_id => $value) {
				$guild_details = $guild_array[$guild_id];

				if ( isset($guild_details['direct_encounter_details'][$encounter_id]) ) {
					$guild_array[$guild_id]['direct_encounter_details'][$encounter_id]['region_rank'] = $region_rank;
				}

				$region_rank++;
			}
		}

		foreach  ( $server_guild_array as $server => $guild_id_array) {
			$server_rank = 1;

			foreach  ( $guild_id_array as $guild_id => $value) {
				$guild_details = $guild_array[$guild_id];

				if ( isset($guild_details['direct_encounter_details'][$encounter_id]) ) {
					$guild_array[$guild_id]['direct_encounter_details'][$encounter_id]['server_rank'] = $server_rank;
				}

				$server_rank++;
			}
		}

		$world_rank = 1;

		foreach  ( $world_guild_array as $guild_id => $value) {
			$guild_details = $guild_array[$guild_id];

			if ( isset($guild_details['direct_encounter_details'][$encounter_id]) ) {
				$guild_details['direct_encounter_details'][$encounter_id]['world_rank'] = $world_rank;
			}

			$world_rank++;

			if ( !isset($guild_details['direct_encounter_details'][$encounter_id]['date']) || 
				$guild_details['direct_encounter_details'][$encounter_id]['date'] == "" ) { unset($guild_array[$guild_id]); continue; }

			if ( !isset($guild_details['direct_encounter_details'][$encounter_id]['time']) || 
				$guild_details['direct_encounter_details'][$encounter_id]['time'] == "" ) { unset($guild_array[$guild_id]); continue; }

			$kill_string 	= $encounter_details['encounter_id']."||".
							$guild_details['direct_encounter_details'][$encounter_id]['date']."||".
							$guild_details['direct_encounter_details'][$encounter_id]['time']."||".
							$guild_details['direct_encounter_details'][$encounter_id]['timezone']."||".
							$guild_details['direct_encounter_details'][$encounter_id]['screenshot']."||".
							$guild_details['direct_encounter_details'][$encounter_id]['video']."||".
							$guild_details['direct_encounter_details'][$encounter_id]['server_rank']."||".
							$guild_details['direct_encounter_details'][$encounter_id]['region_rank']."||".
							$guild_details['direct_encounter_details'][$encounter_id]['world_rank'];

			$guild_array[$guild_id]['mob_array'][$encounter_id] = $kill_string;
		}
	}

	log_entry(0, "Inserting guild encounter ranks into database.");
	foreach  ( $guild_array as $guild_id => $guild_details ) {
		if ( !isset($guild_details['mob_array']) ) continue;

		$progression_string = "";

		foreach ( $guild_details['mob_array'] as $mob_id => $string ) {
			if ( isset($progression_string) && strlen($progression_string) > 0 ) {
				$progression_string = $progression_string."~~".$string;
			} else {
				$progression_string = $string;
			}
		}
		
		$query = mysql_query(sprintf("UPDATE %s
								SET progression = '%s'
								WHERE guild_id='%s'",
								mysql_real_escape_string($GLOBALS['table_guild']),
								mysql_real_escape_string($progression_string),
								mysql_real_escape_string($guild_id)
								)) or die(log_entry(3, mysql_error()));
	}

	log_entry(0, "Update Encounter Ranks Completed!");
?>