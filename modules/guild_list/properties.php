<?php
	$ROOT 			= dirname(dirname(dirname(__FILE__)));
	include_once 	"{$ROOT}/configuration.php";
	
	$module = "guild_list";
	if ( !isset($GLOBALS[$module]['set']) || $GLOBALS[$module]['set'] == 0 ) { draw_disabled_module(); exit; } 



	$guild_stats = array();

	foreach ( $GLOBALS['global_guild_array'] as $guild_id => $guild_details ) {
		$region = strtolower($guild_details['region']);

		if ( $guild_details['active'] == "Inactive" ) { continue; }

		if ( !isset($guild_stats['guild_count']) ) { $guild_stats['guild_count'] = 0; }

		if ( !isset($guild_stats[$region.'_guild_count']) ) { $guild_stats[$region.'_guild_count'] = 0; }

		$guild_stats['guild_count']++;
		$guild_stats[$region.'_guild_count']++;
	}

	function send_update_request($form_details) {
		$guild_id 		= $form_details['guild_id'];
		$guild_details 	= $GLOBALS['global_guild_array'][$guild_id];
		
		$email_headers 	= "From: {$GLOBALS['email_admin']}\r\n";
		$email_headers .= "Reply-To: \r\n";
		$email_headers .= "Return-Path: \r\n";
		$email_headers .= "CC: \r\n";
		$email_headers .= "BCC: \r\n";
		$email_subject 	= "{$GLOBALS['site_title_short']}-{$guild_details} Guild Details Update Request";
		$email_address 	= $GLOBALS['email_admin'];
		$email_message 	= "Dear Site Administrator,\n\n{$guild_details['name']} is requesting an update to guild details!\n\n";
		$email_message .= "Guild Type: {$guild_details['guild_type']} -->> {$form_details['guild_type']}\n";
		$email_message .= "Raid Schedule: {$guild_details['schedule']} -->> {$form_details['schedule']}\n";
		$email_message .= "Website: {$guild_details['website']} -->> {$form_details['website']}\n";
		mail($email_address, $email_subject, $email_message, $email_headers);	
	}

	function draw_submit_popup() { generate_popup_form('update_guild', $GLOBALS['page_guild_list'], ""); }

	function block_draw_details($module, $guild_details) {
		$block_title = generate_block_title($GLOBALS[$module]['block_title'][0]);

		echo "<div class='side_block'>";
			echo "<div class='block_title'>$block_title</div>";

			foreach ( $GLOBALS[$module]['list_header'] as $header => $value ) {
				$item = $guild_details[$value];

				echo "<div class='side_block_content'>";
					echo "<div class='side_block_content_left'>"; 
						echo "<div class='small'><div class='side_title'>$header</div></div>";
					echo "</div>";
					echo "<div class='side_block_content_right'>"; 
						echo "<div class='small'><div class='small_text'>$item</div></div>";
					echo "</div>";
				echo "</div>";
				echo "<div class='clear'></div>";
			}
		echo "</div>";
	}

	function block_draw_update($module) {
		$block_title = generate_block_title($GLOBALS[$module]['block_title'][1]);

		echo "<div class='side_block'>";
			echo "<div class='block_title'>$block_title</div>";
			echo "<div class='side_block_content'>";
				echo "<input id='update_guild_popup' class='data_button' type='submit' value='Update Now'>";
			echo "</div>";
		echo "</div>";
	}

	function draw_guild_listing($module) {
		foreach ( $GLOBALS['global_region_array'] as $region_id => $region_details ) {
			draw_message_banner_region_server($region_details, "", "Active Guild List");

			$pane_name = strtolower($region_details['abbreviation']);

			echo "<div class='clear'></div>";
			echo "<div id='pane_$pane_name'>";
				echo "<table class='table_data guild_list'>";
					echo "<thead>";
						echo "<tr>";
							foreach ( $GLOBALS[$module]['guild_details'] as $key => $value ) {
								echo "<th>$key</th>";
							}
						echo "</tr>";
					echo "</thead>";
					echo "<tbody>";
						foreach ( $GLOBALS['global_guild_array'] as $guild_id => $guild_details ) {
							$guild_details = get_guild_details($guild_id);
							$guild_details = generate_table_fields($guild_details, 20);

							if ( $guild_details['region'] != $region_details['abbreviation'] ) { continue; }
							if ( $guild_details['active'] == "Inactive" ) { continue; }

							echo "<tr>";
								foreach ( $GLOBALS[$module]['guild_details'] as $key => $value ) {
									if ( !isset($guild_details[$value]) || strlen($guild_details[$value]) == 0 ) { $guild_details[$value] = "N/A"; }
									$item = $guild_details[$value];
									
									echo "<td>$item</td>";
								}
							echo "</tr>";
						}			
					echo "</tbody>";
				echo "</table>";
			echo "</div>";
		}
	}
?>