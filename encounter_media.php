<?php
	include_once 	"configuration.php";

	$values = explode("|", $_GET['value']);
	
	$guild_id 			= $values[0];
	$encounter_id 		= $values[1];
	$guild_details 		= get_guild_details($guild_id);
	$encounter_details 	= $guild_details['direct_encounter_details'][$encounter_id];

	echo "<table class='media_table'>";
		echo "<thead>";
			echo "<tr>";
				echo "<th>Videos</th>";					
				echo "<th>Screenshots</th>";
			echo "</tr>";
		echo "</thead>";
		echo "<tbody>";
			echo "<tr>";
				echo "<td>Cleric Pov</td>";
				echo "<td>Image 1</td>";
			echo "</tr>";
		echo "</tbody>";
	echo "</table>";
?>