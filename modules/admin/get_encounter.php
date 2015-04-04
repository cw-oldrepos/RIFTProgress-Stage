<?php
	$ROOT 			= dirname(dirname(dirname(__FILE__)));
	include_once 	"{$ROOT}/configuration.php";

	$guild_id 					= $_GET['gid'];
	$guild_details 				= array();
	$encounter_array 			= array();
	$encounter_details_array 	= array();
	$days_array 				= get_day_array();
	$months_array 				= get_month_array();
	$years_array 				= get_year_array();
	$hours_array 				= get_hour_array();
	$minutes_array 				= get_minute_array();

	/*$query = mysql_query(sprintf("SELECT *
      	FROM %s
      	WHERE guild_id = %s",
      	mysql_real_escape_string($GLOBALS['table_guild']),
      	mysql_real_escape_string($guild_id)
      	)) or die(draw_page_error());

	$guild_details = mysql_fetch_array($query);*/

	$guild_details = get_guild_details($guild_id);

	/*if ( isset($guild_details['progression']) && strlen($guild_details['progression']) > 0 ) {
		$progression_details_array  = explode("~~", $guild_details['progression']);

		for ( $count = 0; $count < count($progression_details_array); $count++ ) {
			$progression_details   = explode("||", $progression_details_array[$count]);

			$encounter_array[$progression_details[0]] 			= $progression_details[1];
			$encounter_details_array[$progression_details[0]] 	= $progression_details;
		}
	}*/

	if ( $_GET['type'] == 0 ) {
		echo "<select class='dropbox_default' id='encounter_id' name='encounter_id' onChange='update_encounter_details(this)'>";
			echo "<option value=''></option>";

			foreach ($guild_details['direct_encounter_details'] as $encounter_id => $encounter_details) {
				echo "<option value='$encounter_id'>{$encounter_details['encounter_name']}</option>"; 
			}
		echo "</select>";
	} else if ( $_GET['type'] == 1 ) {
		$encounter_details = $guild_details['direct_encounter_details'][$_GET['eid']];

		/*$hour_array = $minute_array = $year_array = $month_array = $day_array = $encounter_date_array = $encounter_time_array = array();

		$encounter_date_array = explode("-", $encounter_details[6]);
		$encounter_time_array = explode(":", $encounter_details[7]);

		for ( $hour = 1; $hour < 25; $hour++ ) {
			array_push($hour_array, $hour);
		}

		for ( $minute = 1; $minute < 60; $minute++ ){
			array_push($minute_array, $minute);
		}

		$current_year 	= date("Y");
		for ( $year = 2011; $year <= $current_year; $year++ ){
			array_push($year_array, $year);
		}
		
		for ( $month = 1; $month < 13; $month++ ){
			array_push($month_array, $month);
		}

		for ( $day = 1; $day < 32; $day++ ){
			array_push($day_array, $day);
		}*/		

		echo "<input type='hidden' name='guild_id' value='{$guild_id}'/>";
		echo "<input type='hidden' name='encounter_id' value='{$encounter_details['encounter_id']}'/>";
		echo "<table class='table_data form' style='width:auto !important;'>";
		echo "<tr>";
			echo "<th>Tier</th>";
			echo "<td>{$encounter_details['tier']}</td>";
		echo "</tr>";		
		echo "<tr>";
			echo "<th>Dungeon ID</th>";
			echo "<td>{$encounter_details['dungeon_id']}</td>";
		echo "</tr>";
		echo "<tr>";
			echo "<th>Dungeon Name</th>";
			echo "<td>{$encounter_details['dungeon_name']}</td>";
		echo "</tr>";
		echo "<tr>";
			echo "<th>Encounter ID</th>";
			echo "<td>{$encounter_details['encounter_id']}</td>";
		echo "</tr>";
		echo "<tr>";
			echo "<th>Raid Size</th>";
			echo "<td>{$encounter_details['players']}</td>";
		echo "</tr>";
		echo "<tr>";
			echo "<th>World Rank: </th>";
			echo "<td>{$encounter_details['world_rank']}</td>";
		echo "</tr>";
		echo "<tr>";
			echo "<th>Region Rank: </th>";
			echo "<td>{$encounter_details['region_rank']}</td>";
		echo "</tr>";
		echo "<tr>";
			echo "<th>Server Rank: </th>";
			echo "<td>{$encounter_details['server_rank']}</td>";
		echo "</tr>";
		echo "<tr>";
			echo "<th>Date</th>";
			echo "<td>";
				echo "<select class='select_short' name='month_select' id='month_select'>";
					for ( $count = 0; $count < count($month_array); $count++) {
						$output = $month_array[$count];
						$output_month = "";

						if ($output < 10) {
						$output_month = "0".$output;
						} else {
						$output_month = $output;
						}

						if ($output == $encounter_date_array[1]) {
							echo "<option value='$output_month' selected>$output_month</option>";
						} else {
							echo "<option value='$output_month'>$output_month</option>";
						}
					}
				echo "</select>";

				echo "<select class='select_short' name='day_select' id='day_select'>";
					for ( $count = 0; $count < count($day_array); $count++) {
						$output = $day_array[$count];
						$output_day = "";

						if ($output < 10) {
							$output_day = "0".$output;
						} else {
							$output_day = $output;
						}

						if ( $output == $encounter_date_array[0] || $output == $encounter_date_array[2] ){
							echo "<option value='$output_day' selected>$output_day</option>";							
						} else {
						
							echo "<option value='$output_day'>$output_day</option>";
						}
					}
				echo "</select>";

				echo "<select class='select_short' name='year_select' id='year_select'>";
					for ( $count = 0; $count < count($year_array); $count++) {
						$output_year = $year_array[$count];

						if ( $output_year == $encounter_date_array[2] || $output_year == $encounter_date_array[0] ) {
							echo "<option value='$output_year' selected>$output_year</option>";
						} else {
							echo "<option value='$output_year'>$output_year</option>";
						}
					}
				echo "</select>";
			echo "</td>";
		echo "</tr>";
		echo "<tr>";
			echo "<th>Time: </th>";
			echo "<td>";
				echo "<select class='select_short' name='hour_select' id='hour_select'>";
					for ( $count = 0; $count < count($hour_array); $count++) {
						$output 		= $hour_array[$count];
						$output_hour 	= "";

						if ($output > 12) {
							$output_hour = ($output - 12) . "pm";
						} else {
							$output_hour = $output . "am";
						}

						if ($output < 10) {
							$output = "0". $output;
						} else {
							$output = $output;
						}

						if ($output == $encounter_time_array[0]) {
							echo "<option value='$output' selected>$output_hour</option>";
						} else {
							echo "<option value='$output'>$output_hour</option>";
						}
					}
				echo "</select>";

				echo "<select class='select_short' name='minute_select' id='minute_select'>";
					for ( $count = 0; $count < count($minute_array); $count++) {
						$output = $minute_array[$count];

						$output_minute = "";
						if ($output < 10) {
							$output_minute = "0". $output;
						} else {
							$output_minute = $output;
						}

						if ($output == $encounter_time_array[1]) {
							echo "<option value='$output_minute' selected>$output_minute</option>";
						} else {
							echo "<option value='$output_minute'>$output_minute</option>";
						}
					}
				echo "</select>";
			echo "</td>";
		echo "</tr>";
		echo "<tr>";
			echo "<th>Screenshot</th>";
			echo "<td>";
				echo "<input style='width:225px;' type='text' class='textbox_default' name='screenshot_input' id='screenshot_input' value='$encounter_details[8]'/>";
			echo "</td>";
		echo "</tr>";
		echo "<tr>";
			echo "<th>Video: </th>";
			echo "<td>";
				echo "<input style='width:225px;' type='text' class='textbox_default' name='video_input' id='video_input' value='$encounter_details[9]'/>";
			echo "</td>";
		echo "</tr>";		
		echo "<tr>";
			echo "<th colspan='2' style='text-align:center;'>";
				echo "<input type='submit' class='data_button' name='form_guild_encounter_edit' id='edit_button' value='Edit'></input>";
				echo "<input type='submit' class='data_button' name='form_guild_encounter_delete' id='submit_button' value='Delete'></input>";
			echo "</th>";
		echo "</tr>";										
		echo "</table>";

	}
?>