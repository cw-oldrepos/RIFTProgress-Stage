<?php
	include_once 	"properties.php";

	echo "***UPDATING ENCOUNTER REQUIREMENTS***";

	$dungeon_encounter_array = array();
	$encounter_array = $GLOBALS['global_encounter_array'];

	foreach ( $encounter_array as $encounter_id => $encounter_details ) {
		if ( $encounter_details['mob_type'] > 0 ) continue;

		$dungeon_id = $encounter_details['dungeon_id'];

		$dungeon_encounter_array[$dungeon_id][$encounter_id] = $encounter_details['mob_order'];
	}

	foreach ( $dungeon_encounter_array as $dungeon_id => $encounter_id_array ) {
		asort($encounter_id_array);

		$dungeon_encounter_array[$dungeon_id] = $encounter_id_array;
	}

	foreach ( $dungeon_encounter_array as $dungeon_id => $encounter_id_array ) {
		$dungeon_details = $dungeon_array[$dungeon_id];

		$previous_encounter_order = $full_string = $previous_string = $string = "";

		echo "<br>Dungeon: ".$dungeon_details['name']."<br><br>";

		foreach ( $encounter_id_array as $encounter_id => $mob_order ) {
			$encounter_details 	= $encounter_array[$encounter_id];
			$required_encounter_string = "";

			echo "Encounter: ".$encounter_details['encounter_name']." --- ID: $encounter_id --- ".$encounter_details['mob_order']."<br>";

			if ( isset($string) && strlen($string) > 0 ) {
				if ( $previous_encounter_order == $mob_order ) {
					$required_encounter_string = $previous_string;
				} else {
					$required_encounter_string 	= $full_string;
					$previous_string 			= $full_string;
					$string 					.= "~~$encounter_id";	
				}

				$full_string .= "~~$encounter_id";
			} else {
				$required_encounter_string 	= "";
				$string 					= $encounter_id;
				$full_string 				= $encounter_id;
			}

			$previous_encounter_order = $mob_order;

			if ( $previous_string == "" && $previous_encounter_order != $mob_order ) $previous_string = $string;

			echo "Requires: $required_encounter_string<br>";

			echo "<br>";

			$query = mysql_query(sprintf("UPDATE %s
								SET req_encounter='%s'
								WHERE encounter_id='%s'",
								mysql_real_escape_string($GLOBALS['table_encounters']),
								mysql_real_escape_string($required_encounter_string),
								mysql_real_escape_string($encounter_id)
								)) or die(draw_error_page());
		}
	}
?>