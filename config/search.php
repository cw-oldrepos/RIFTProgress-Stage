<?php
	$ROOT 			= dirname(dirname(__FILE__));
	include_once 	"{$ROOT}/configuration.php";
	
	if ( isset($_POST) && isset($_POST['guild_search']) && strlen($_POST['guild_search']) > 0 ) {
		$guild_array 	= array();
		$guild_name 	= strtolower($_POST['guild_search']);
		
		foreach( $GLOBALS['global_guild_array'] as $guild_id => $guild_details ) {
			if ( strcasecmp($guild_name, $guild_details['name']) == 0 ) {
				$guild_array = array();
				array_push($guild_array, $guild_details);
				break;
			} else if ( stripos($guild_details['name'], $guild_name) > -1 ) {			
				array_push($guild_array, $guild_details);
			}
		}
		
		if ( count($guild_array) == 1 ) {
			$name 	= $guild_array[0]['name'];
			$server = $guild_array[0]['server'];

			$hyperlink = $GLOBALS['page_guild'].strtolower(str_replace(" ", "_", $name)."-_-".$server);

			header("Location: {$hyperlink}");			
		} else if ( count($guild_array) > 0 ) {
			$name 	= $guild_array[0]['name'];
			$server = $guild_array[0]['server'];
			
			$hyperlink = $GLOBALS['page_guild'].strtolower(str_replace(" ", "_", $name)."-_-".$server);
			header("Location: {$hyperlink}");		
		} else if ( count($guild_array) == 0 ) {
			header("Location: {$GLOBALS['page_index']}");
		}
	}

	function draw_guild_select($guild_array) {
		echo "<div class='banner'>";
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
							$guild_details 	= $guild_array[$count];
							$guild_details 	= generate_table_fields($guild_details);
							$name 			= generate_hyperlink_guild($guild_details['name'], $guild_details['server'], 0, $guild_details['faction']);

							echo "<tr>";
								echo "<td>$name</td>";
								echo "<td>{$guiild_details['server']}</td>";
							echo "</tr>";
						}
					echo "</tbody>";
				echo "</table>";
			echo "</div>";
		echo "</div><br>";		
	}

	mysql_close($dblink);
?>