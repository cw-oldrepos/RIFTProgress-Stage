<?php
	$ROOT 			= dirname(dirname(dirname(__FILE__)));
	include_once 	"{$ROOT}/configuration.php";

	$module = "how";
	if ( !isset($GLOBALS[$module]['set']) || $GLOBALS[$module]['set'] == 0 ) { draw_disabled_module(); exit; } 

	//***************DECLARING VARIABLES**************
	//***************DECLARING VARIABLES**************

	function block_draw_ranking_tidbits($module) {
		if ( $GLOBALS['freeze_kill_count'] == 0 ) 	$GLOBALS['freeze_kill_count'] = "N/A";
		if ( $GLOBALS['freeze_kill_date'] == 0 ) 	$GLOBALS['freeze_kill_date'] = "N/A";

		$block_title = generate_block_title($GLOBALS[$module]['block_title'][0]);

		echo "<div class='side_block'>";
			echo "<div class='block_title'>$block_title</div>";

			foreach ( $GLOBALS[$module]['block_tidbits'] as $header => $value ) {
				echo "<div class='side_block_content'>";
					echo "<div class='side_block_content_left'>"; 
						echo "<div class='small'><div class='side_title'>$header</div></div>";
					echo "</div>";
					echo "<div class='side_block_content_right'>"; 
						echo "<div class='small'><div class='small_text'>$value</div></div>";
					echo "</div>";
					echo "<div class='clear'></div>";
				echo "</div>";				
			}
		echo "</div>";
	}

	function block_draw_glossary($module) {
		$block_title = generate_block_title($GLOBALS[$module]['block_title'][1]);

		echo "<div class='side_block'>";
			echo "<div class='block_title'>$block_title</div>";

			foreach ( $GLOBALS[$module]['glossary'] as $header => $value ) {
				echo "<div class='side_block_content'>";
					echo "<div class='side_block_content_left'>"; 
						echo "<div class='small'><div class='side_title'>$header</div></div>";
					echo "</div>";
					echo "<div class='clear'></div>";
					echo "<div class='side_block_content_left'>"; 
						echo "<div class='small'><div class='small_text'>$value</div></div>";
					echo "</div>";
					echo "<div class='clear'></div>";
				echo "</div>";				
			}
		echo "</div>";	
	}

	function draw_how_it_works() {
		echo "<div class='primary_content_block'>";
			echo "<article class='content_article'>";
				echo "<div class='content_article_content'>";
					echo "<h1>{$GLOBALS['game_name_1']} Progress 'How Does it Work?'</h1>";
					echo "<span>Last Updated: 07/29/2014</span>";
					echo "<hr><div class='clear'></div>";
					echo "<p>";
						echo "<table class='table_data text'>";
							echo "<thead>";
								echo "<tr>";
									echo "<th></th>";
									echo "<th>Quality Progression (Default)</th>";
									echo "<th>Aeyths Point</th>";
									echo "<th>Aeyths Flat Point</th>";
								echo "</tr>";
							echo "</thead>";
							echo "<tbody>";
								echo "<tr>";
									echo "<th>Description</th>";
									echo "<td>'All encounters start with a base point value unmodified. Final dungeon encounter is modified to be the overall ranking regardless of previous encounters.'</td>";
									echo "<td>'All encounters start with a base point value and is modified by # of guilds active in a dungeon.'</td>";
									echo "<td>'All encounters use the same point base scale. Standard decay rate with no point modifications.'</td>";
								echo "</tr>";
								echo "<tr>";
									echo "<th>Base Encounter Point Value</th>";
									echo "<td>".$GLOBALS['point_base']."</td>";
									echo "<td>".$GLOBALS['point_base']."</td>";
									echo "<td>".$GLOBALS['point_base_mod']."</td>";
								echo "</tr>";
								echo "<tr>";
									echo "<th>Final Encounter Point Value</th>";
									echo "<td>(".$GLOBALS['point_final_base']." * #ofMobs) + (BEPV * #ofMobs)</td>";
									echo "<td>--</td>";
									echo "<td>--</td>";
								echo "</tr>";
								echo "<tr>";
									echo "<th>Rate of Decay</th>";
									echo "<td>-exp(NbT/50)</td>";
									echo "<td>-exp(NbT/50)</td>";
									echo "<td>-exp(NbT/50)</td>";
								echo "</tr>";
								echo "<tr>";
									echo "<th>Starting Point Formula</th>";
									echo "<td>BEPV</td>";
									echo "<td>(NbHK/NKE) * BEPV</td>";
									echo "<td>BEPV</td>";
								echo "</tr>";
								echo "<tr>";
									echo "<th>Kill Value Formula</th>";
									echo "<td>BEPV * Decay</td>";
									echo "<td>(NbG/NbGD) * StartValue  * Decay</td>";
									echo "<td>BEPV * Decay</td>";
								echo "</tr>";
							echo "</tbody>";
						echo "</table>";
						echo "<br><br>";
						echo "<h1>Example Data</h1>";
						echo "<hr><div class='clear'></div>";
						echo "<table class='table_data text'>";
							echo "<thead>";
								echo "<tr>";
									echo "<th>Guild</th>";
									echo "<th>Date</th>";
									echo "<th>Quality Progression</th>";
									echo "<th>Aeyths Point</th>";
									echo "<th>Aeyths Flat Point</th>";
								echo "</tr>";
							echo "</thead>";
							echo "<tbody>";
								echo "<tr>";
									echo "<th colspan='5'>Encounter A (# of Kills: 63)</th>";
								echo "</tr>";
								echo "<tr>";
									echo "<th>Guild A</th>";
									echo "<td>09-18-2013 11:15:00</td>";
									echo "<td>1,000.00</td>";
									echo "<td>1,000.00</td>";
									echo "<td>2,500.00</td>";
								echo "</tr>";
								echo "<tr>";
									echo "<th>Guild B</th>";
									echo "<td>09-18-2013 11:42:00</td>";
									echo "<td>999.63</td>";
									echo "<td>999.63</td>";
									echo "<td>2,499.06</td>";
								echo "</tr>";
								echo "<tr>";
									echo "<th>Guild C</th>";
									echo "<td>09-23-2013 01:12:00</td>";
									echo "<td>912.45</td>";
									echo "<td>912.45</td>";
									echo "<td>2,281.12</td>";
								echo "</tr>";
								echo "<tr>";
									echo "<th colspan='5'>Encounter B (# of Kills: 52)</th>";
								echo "</tr>";
								echo "<tr>";
									echo "<th>Guild B</th>";
									echo "<td>09-18-2013 13:43:00</td>";
									echo "<td>1,000.00</td>";
									echo "<td>1,468.38</td>";
									echo "<td>2,500.00</td>";
								echo "</tr>";
								echo "<tr>";
									echo "<th>Guild A</th>";
									echo "<td>09-18-2013 17:00:00</td>";
									echo "<td>997.27</td>";
									echo "<td>1,464.37</td>";
									echo "<td>2,493.17</td>";
								echo "</tr>";
								echo "<tr>";
									echo "<th>Guild C</th>";
									echo "<td>09-24-2013 02:11:00</td>";
									echo "<td>895.49</td>";
									echo "<td>1,314.92</td>";
									echo "<td>2,238.71</td>";
								echo "</tr>";
								echo "<tr>";
									echo "<th colspan='5'>Encounter C (Final Encounter) (# of Kills: 40)</th>";
								echo "</tr>";
								echo "<tr>";
									echo "<th>Guild B</th>";
									echo "<td>09-20-2013 21:08:00</td>";
									echo "<td>18,000.00</td>";
									echo "<td>2,480.63</td>";
									echo "<td>2,500.00</td>";
								echo "</tr>";
								echo "<tr>";
									echo "<th>Guild A</th>";
									echo "<td>09-18-2013 17:00:00</td>";
									echo "<td>15,085.02</td>";
									echo "<td>1,998.56</td>";
									echo "<td>2,014.17</td>";
								echo "</tr>";
								echo "<tr>";
									echo "<th>Guild C</th>";
									echo "<td>--</td>";
									echo "<td>-</td>";
									echo "<td>-</td>";
									echo "<td>-</td>";
								echo "</tr>";
								echo "<tr>";
									echo "<th colspan='5'>Dungeon Score (Highest Killed Encounter: 63)</th>";
								echo "</tr>";
								echo "<tr>";
									echo "<th>Guild B</th>";
									echo "<td>--</td>";
									echo "<td>18,000.00</td>";
									echo "<td>4,948.63</td>";
									echo "<td>7,499.06</td>";
								echo "</tr>";
								echo "<tr>";
									echo "<th>Guild A</th>";
									echo "<td>--</td>";
									echo "<td>15,085.02</td>";
									echo "<td>4,462.93</td>";
									echo "<td>7,007.34</td>";
								echo "</tr>";
								echo "<tr>";
									echo "<th>Guild C</th>";
									echo "<td>--</td>";
									echo "<td>1,807.94</td>";
									echo "<td>2,227.36</td>";
									echo "<td>4,519.83</td>";
								echo "</tr>";
							echo "</tbody>";
						echo "</table>";
					echo "</p>";
				echo "</div>";
			echo "</article>";
		echo "</div>";
	}
?>