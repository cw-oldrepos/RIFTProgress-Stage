<?php
	$ROOT 			= dirname(dirname(dirname(__FILE__)));
	include_once 	"{$ROOT}/configuration.php";

	$module = "server_rankings";
	if ( !isset($GLOBALS[$module]['set']) || $GLOBALS[$module]['set'] == 0 ) { draw_disabled_module(); exit; } 	

	//***************DECLARING VARIABLES**************
	$guild_array 	= array();
	$server_array 	= array();
	$region_total 	= array();
	//***************DECLARING VARIABLES**************

	$guild_array 			= get_overall_standings($GLOBALS['global_guild_array']);
	$server_strength_array 	= get_server_strength($guild_array);
	$server_active_array 	= get_server_activity($guild_array);
	$total_world 			= get_total_world($guild_array);	

	$rank = 0;
	foreach( $guild_array as $guild_id => $guild_details ) {
		$rank++;

		$guild_array[$guild_id]['rank'] = $rank;
	}

	foreach( $GLOBALS['global_server_array'] as $server => $server_details ) {
		list($server_details, $region_total) = get_server_ranking_details($server_details, $guild_array, $region_total);
		$server_array[$server] = generate_server_fields($server_details, "");
	}

	function block_draw_strength($module, $order_server_array, $total_world) {
		$block_title 	= generate_block_title("Server Strength Index");
		$server_count 	= array();

		foreach ( $GLOBALS['global_region_array'] as $region => $server_details ) {
			foreach ( $order_server_array as $server => $server_average ) {
				$server_details = $GLOBALS['global_server_array'][$server];

				if ( $server_details['region'] != $region ) { continue; }

				if ( isset($server_count[$region]) ) { $server_count[$region]++; } else { $server_count[$region] = 1; }
			}
		}

		echo "<div class='side_block'>";
			echo "<div class='block_title'>$block_title</div>";

			foreach ( $GLOBALS['global_region_array'] as $region => $region_details ) {
				echo "<div class='block_subtitle'>{$region_details['full']}</div>";

				foreach ( $order_server_array as $server => $server_average ) {
					$server_details = $GLOBALS['global_server_array'][$server];

					if ( $server_details['region'] != $region ) { continue; }

					$country 	= $server_details['country'];
					$region 	= $server_details['region'];

					if ( !isset($GLOBALS['global_region_array'][$region]['count']) || $GLOBALS['global_region_array'][$region]['count'] == 0 ) $GLOBALS['global_region_array'][$region]['count'] = 1;

					$server_average 	= number_format(($server_average/$total_world[$region])*100, 2, ".", ",");
					$server_diff	 	= number_format(($server_average - ($server_count[$region]/1.5)), 2, ".",",");
					$country 			= get_image_flag($region);
					$server 			= "$country <span>".generate_hyperlink_server($server, "world", 0, "", "")."</span>";

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
			}
			echo "<div class='clear'></div>";
		echo "</div>";		
	}

	function block_draw_activity($module, $server_active_array, $total_world) {
		$block_title 	= generate_block_title("Server Active Index");
		$server_count 	= array();

		foreach ( $GLOBALS['global_region_array'] as $region => $server_details ) {
			foreach ( $server_active_array as $server => $server_average ) {
				$server_details = $GLOBALS['global_server_array'][$server];

				if ( $server_details['region'] != $region ) { continue; }

				if ( isset($server_count[$region]) ) { $server_count[$region]++; } else { $server_count[$region] = 1; }
			}
		}

		echo "<div class='side_block'>";
			echo "<div class='block_title'>$block_title</div>";

			foreach ( $GLOBALS['global_region_array'] as $region => $region_details ) {
				echo "<div class='block_subtitle'>{$region_details['full']}</div>";

				foreach ( $server_active_array as $server => $server_average ) {
					$server_details = $GLOBALS['global_server_array'][$server];

					if ( $server_details['region'] != $region ) { continue; }

					$country 	= $server_details['country'];
					$region 	= $server_details['region'];

					if ( !isset($GLOBALS['global_region_array'][$region]['count']) || $GLOBALS['global_region_array'][$region]['count'] == 0 ) $GLOBALS['global_region_array'][$region]['count'] = 1;

					$server_average 	= number_format(($server_average/$total_world[$region])*100, 2, ".", ",");
					$server_diff	 	= number_format(($server_average - ($server_count[$region]/1.5)), 2, ".",",");
					$country 			= get_image_flag($region);
					$server 			= "$country <span>".generate_hyperlink_server($server, "world", 0, "", "")."</span>";

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
			}
			echo "<div class='clear'></div>";
		echo "</div>";		
	}

	function draw_server_statistics($module, $guild_array, $server_array, $region_total) {
		foreach ( $GLOBALS['global_region_array'] as $region => $region_details ) {
			echo "<div class='horizontal_separator'></div>";

			draw_message_banner_region_server($region_details, "", "Servers");
			echo "<table class='table_data server_ranking'>";
				echo "<thead>";
					echo "<tr>";
						foreach ( $GLOBALS[$module]['header_stats'] as $header => $value ) {
							echo "<th>$header</th>";	
						}
					echo "</tr>";
				echo "</thead>";
				echo "<tbody>";
					foreach( $GLOBALS['global_server_array'] as $server => $server_details ) {
						if ( $region_details['abbreviation'] != $server_details['region'] ) { continue; }

						$server_details = $server_array[$server];

						echo "<tr>";
							foreach ( $GLOBALS[$module]['header_stats'] as $header => $value ) {
								$item = $server_details[$value];
								
								echo "<td>$item</td>";
							}
						echo "</tr>";
					}
				echo "</tbody>";
				echo "<tfoot>";
					echo "<tr>";
						foreach ( $GLOBALS[$module]['header_stats'] as $header => $value ) {
							$item = $region_total[$region][$value];

							echo "<th>$item</th>";
						}
					echo "</tr>";
				echo "</tfoot>";
			echo "</table>";
		}
	}

    function generate_server_fields($server_details, $text_limit) {
        $region_name    = $GLOBALS['global_region_array'][$server_details['region']]['full'];
        $country        = get_image_flag($server_details['country']);
        $region         = get_image_flag($server_details['region']);

        $server_details['server_name']  = $server_details['name'];
        $server_details['name']         = "$region <span>".generate_hyperlink_server($server_details['name'], "world", 0, $server_details['faction'], "")."</span>";
        $server_details['country']      = "$country <span>{$server_details['country']}</span>";
        $server_details['region']       = "$region <span>$region_name</span>";

        return $server_details;
    }

	function get_server_ranking_details($server_details, $guild_array, $region_total) {
		$num_of_guilds 	= 0;
		$num_of_rf 		= 0;
		$num_of_wf 		= 0;
		$num_of_kills 	= 0;
		$num_of_guard 	= 0;
		$num_of_defiant	= 0;
		$num_of_top_10 	= 0;
		$num_of_top_20 	= 0;
		$region 		= $server_details['region'];

		foreach( $guild_array as $guild_id => $guild_details ) {
			if ( $guild_details['server'] == $server_details['name'] ) {
				if ( $guild_details['faction'] == "Guardian" ) { $num_of_guard++; }
				if ( $guild_details['faction'] == "Defiant" ) { $num_of_defiant++; }
				if ( $guild_details['rank'] <= 10 ) { $num_of_top_10++; }
				if ( $guild_details['rank'] <= 20 ) { $num_of_top_20++; }
	
				$num_of_rf += $guild_details['overall_details']['region_first'];
				$num_of_wf += $guild_details['overall_details']['world_first'];
				$num_of_kills += $guild_details['overall_details']['complete'];
				$num_of_guilds++;
			}
		}

		// Region Details
		if ( isset($region_total[$region]['num_of_guilds']) ) { $region_total[$region]['num_of_guilds'] += $num_of_guilds; } else { $region_total[$region]['num_of_guilds'] = $num_of_guilds; }
		if ( isset($region_total[$region]['num_of_kills']) ) { $region_total[$region]['num_of_kills'] += $num_of_kills; } else { $region_total[$region]['num_of_kills'] = $num_of_kills; }
		if ( isset($region_total[$region]['num_of_rf']) ) { $region_total[$region]['num_of_rf'] += $num_of_rf; } else { $region_total[$region]['num_of_rf'] = $num_of_rf; }
		if ( isset($region_total[$region]['num_of_wf']) ) { $region_total[$region]['num_of_wf'] += $num_of_wf; } else { $region_total[$region]['num_of_wf'] = $num_of_wf; }
		if ( isset($region_total[$region]['num_of_guard']) ) { $region_total[$region]['num_of_guard'] += $num_of_guard; } else { $region_total[$region]['num_of_guard'] = $num_of_guard; }
		if ( isset($region_total[$region]['num_of_defiant']) ) { $region_total[$region]['num_of_defiant'] += $num_of_defiant; } else { $region_total[$region]['num_of_defiant'] = $num_of_defiant; }
		if ( isset($region_total[$region]['top_10']) ) { $region_total[$region]['top_10'] += $num_of_top_10; } else { $region_total[$region]['top_10'] = $num_of_top_10; }
		if ( isset($region_total[$region]['top_20']) ) { $region_total[$region]['top_20'] += $num_of_top_20; } else { $region_total[$region]['top_20'] = $num_of_top_20; } 

		if ( $region_total[$region]['num_of_guilds'] > 0 ) {
			$region_total[$region]['num_of_guard_perc'] 	= number_format(($region_total[$region]['num_of_guard']/$region_total[$region]['num_of_guilds'])*100, 0, ".", ",")."%";
			$region_total[$region]['num_of_defiant_perc'] = number_format(($region_total[$region]['num_of_defiant']/$region_total[$region]['num_of_guilds'])*100, 0, ".", ",")."%";
		} else {
			$region_total[$region]['num_of_guard_perc'] 	= "0%";
			$region_total[$region]['num_of_defiant_perc'] 	= "0%";			
		}

		// Server Details
		if ( $num_of_guard > $num_of_defiant ) { $server_details['faction'] = "Guardian"; }
		if ( $num_of_defiant > $num_of_guard ) { $server_details['faction'] = "Defiant"; }
		if ( $num_of_guilds == 0 || $num_of_defiant == $num_of_guard ) { $server_details['faction'] = "Neutral"; } 

		if ( $num_of_guilds > 0 ) {
			$num_of_guard 	= number_format(($num_of_guard/$num_of_guilds)*100, 0, ".", ",")."%";
			$num_of_defiant 	= number_format(($num_of_defiant/$num_of_guilds)*100, 0, ".", ",")."%";
		} else {
			$num_of_guard 	= "0%";
			$num_of_defiant = "0%";			
		}

		$server_details['guilds'] 			= "{$num_of_guilds} (<a class='guardian'>{$num_of_guard}</a>/<a class='defiant'>{$num_of_defiant}</a>)";
		$server_details['num_of_guilds'] 	= $num_of_guilds;
		$server_details['num_of_kills'] 	= $num_of_kills;
		$server_details['num_of_rf'] 		= $num_of_rf;
		$server_details['num_of_wf'] 		= $num_of_wf;
		$server_details['top_10'] 			= $num_of_top_10;
		$server_details['top_20'] 			= $num_of_top_20;

		$region_total[$region]['guilds'] 	= "{$region_total[$region]['num_of_guilds']} (<a class='guardian'>{$region_total[$region]['num_of_guard_perc']}</a>/<a class='defiant'>{$region_total[$region]['num_of_defiant_perc']}</a>)";
		$region_total[$region]['name'] 		= "Totals";

		return array($server_details, $region_total);
	}

	function get_overall_standings($unsort_guild_array) {
		$guild_array = $temp_array = array();

		foreach ( $unsort_guild_array as $guild_id => $guild_details ) {			
			$guild_details = get_guild_details($guild_id);

			if ( $guild_details['type'] == 1 ) { continue; }
			if ( $guild_details['active'] == 0 ) { continue; }	

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

	function get_total_world($guild_array) {
		$total_world = array();
		$guild_count = array();

		foreach ( $GLOBALS['global_region_array'] as $region => $region_details ) {
			$total_world[$region] = 0;
			$guild_count[$region] = 0;
		}

		foreach ( $guild_array as $guild_id => $guild_details ) {
			$name 	= $guild_details['name'];
			$region = $guild_details['region'];
			$server = $guild_details['server'];
			$score 	= 0;

			if ( $guild_details['overall_details']['complete'] == 0 ) continue;

			$score 	= $guild_details['overall_details']['complete'];

			$total_world[$region] += $score;
			$guild_count[$region]++;
		}

		foreach ( $GLOBALS['global_region_array'] as $region => $region_details ) {
			//$total_world[$region] = number_format(($total_world[$region] / $guild_count[$region]), 2, ".", ",");
			//$total_world[$region] = number_format(($guild_count[$region] / $total_world[$region] ), 2, ".", ",");
			$total_world[$region] = number_format($total_world[$region], 2, ".", ",");
		}

		return $total_world;
	}

	function get_server_activity($guild_array) {
		$order_server_array = array();

		$value_kills 		= 2;
		$value_guilds 		= 1;

		foreach ( $guild_array as $guild_id => $guild_details ) {
			if ( $guild_details['type'] == 1 ) { continue; }
			if ( $guild_details['active'] == 0 ) { continue; }	

			$name 	= $guild_details['name'];
			$region = $guild_details['region'];
			$server = $guild_details['server'];
			$score 	= 0;

			if ( $guild_details['overall_details']['complete'] == 0 ) continue;


			$score += $guild_details['overall_details']['complete'] * $value_kills;
			$score += $value_guilds;

			$order_server_array[$server] +=	$score;
		}

		foreach ( $order_server_array as $server => $score ) {
			$order_server_array[$server] = number_format($order_server_array[$server], 2, ".", ",");

			if ( $order_server_array[$server] == 0 ) $order_server_array[$server] = 0.00;
		}

		arsort($order_server_array);

		return $order_server_array;
	}

	function get_server_strength($guild_array) {
		$order_server_array = array();

		$value_wf 			= 2;
		$value_rf 			= 1;
		$value_top_25 		= 4;
		$value_top_50 		= 2;

		foreach ( $guild_array as $guild_id => $guild_details ) {
			if ( $guild_details['type'] == 1 ) { continue; }
			if ( $guild_details['active'] == 0 ) { continue; }	

			$name 	= $guild_details['name'];
			$region = $guild_details['region'];
			$server = $guild_details['server'];
			$score 	= 0;

			if ( $guild_details['overall_details']['complete'] == 0 ) continue;

			$score += $guild_details['overall_details']['world_first'] * $value_wf;
			$score += $guild_details['overall_details']['region_first'] * $value_wf;
			if ( $guild_details['rank'] <= 25 ) { $score += $value_top_25; }
			if ( $guild_details['rank'] <= 50 ) { $score += $value_top_50; }

			$order_server_array[$server] +=	$score;
		}

		foreach ( $order_server_array as $server => $score ) {
			$order_server_array[$server] = number_format($order_server_array[$server], 2, ".", ",");

			if ( $order_server_array[$server] == 0 ) $order_server_array[$server] = 0.00;
		}

		arsort($order_server_array);

		return $order_server_array;
	}
?>