<?php
	$ROOT 			= dirname(dirname(dirname(__FILE__)));
	include_once 	"{$ROOT}/configuration.php";

	$module = "admin";

	$admin_set = 0;
	
	if ( isset($_POST['form_new_guild']) ) {
		create_new_guild($_POST);
	} else if ( isset($_POST['form_new_tier']) ) {
		create_new_tier($_POST, $_FILES);
	} else if ( isset($_POST['form_new_dungeon']) ) {
		create_new_dungeon($_POST, $_FILES);
	} else if ( isset($_POST['form_new_encounter']) ) {
		create_new_encounter($_POST);
	} else if ( isset($_POST['form_kills_submit']) ) {
		update_kill_details($_POST, $_FILES, "new");
	} else if ( isset($_POST['form_news_article']) ) {
		create_news_article($_POST);
	} else if ( isset($_POST['form_guild_encounter_edit']) ) {
		edit_guild_encounter($_POST);
	} else if ( isset($_POST['form_guild_encounter_delete']) ) {
		delete_guild_encounter($_POST);
	} else if ( isset($_POST['admin_login']) ){
		$user_details = login_admin($_POST);

		if ( isset($user_details['user_id']) && $user_details['admin'] == 1 ) {
			$_SESSION['user_id']			= $user_details['user_id'];
			$_SESSION['user']			= $user_details['username'];
			$_SESSION['logged']			= true;
			$_SESSION['logging']		= false;
			$_SESSION['admin']			= "1";
		} else {
			$admin_set = 2;
		}
	}

	if ( isset($_SESSION['admin']) && $_SESSION['admin'] == 1 ) {
		$admin_set = 1;
	}
	
	function draw_forms() {
		$guild_array 		= $GLOBALS['global_guild_array'];
		$encounter_array 	= $GLOBALS['global_encounter_array'];
		$country_array 		= $GLOBALS['global_country_array'];
		$tier_array 		= $GLOBALS['global_tier_array'];
		$faction_array 		= $GLOBALS['global_faction_array'];
		$dungeon_array 		= $GLOBALS['global_dungeon_array'];

		$days_array 	= get_day_array();
		$months_array 	= get_month_array();
		$years_array 	= get_year_array();
		$hours_array 	= get_hour_array();
		$minutes_array 	= get_minute_array();	

		echo "<div style='width:100%;'>";
			echo "<form id='form_new_tier' action='{$GLOBALS['page_admin']}' method='POST' enctype='multipart/form-data' runat='server'>";
				echo "<table class='table_data form' style='float:left; margin-bottom:1%; width:auto !important;'>"; //height:240px; 
					echo "<thead>";
						echo "<th colspan='2'>Add New Tier</th>";
					echo "</thead>";
					echo "<tbody>";
						echo "<tr>";
							echo "<th>Tier</th>";
							echo "<td>";
								echo "<select class='select_short' name='tier'>";
									for ($count = $GLOBALS['latest_tier'] + 1; $count < $GLOBALS['latest_tier'] + 3; $count++) {
										$value 	= $count;
										echo "<option value='$value'>$value</option>";
									}
								echo "</select>";
							echo "</td>";
						echo "</tr>";
						echo "<tr>";
							echo "<th>Alt Tier</th>";
							echo "<td><input style='width:225px;' id='textbox_username' type='text' name='alt_tier' value=''></td>";
						echo "</tr>";
						echo "<tr>";
							echo "<th>Title</th>";
							echo "<td><input style='width:225px;' id='textbox_username' type='text' name='title' value=''></td>";
						echo "</tr>";
						echo "<tr>";
							echo "<th>Alt Title</th>";
							echo "<td><input style='width:225px;' id='textbox_username' type='text' name='alt_title' value=''></td>";
						echo "</tr>";
						echo "<tr>";
							echo "<th>Starting Date</th>";
							echo "<td>";
								echo "";
								echo "<select class='select_short' id ='time_month' name='time_month'>";
									for ($count = 0; $count < count($months_array); $count++) {
										$value 	= $months_array[$count];
										echo "<option value='$value'>$value</option>";
									}
								echo "</select>";
								echo "   ";
								echo "<select class='select_short' id='time_day' name='time_day'>";
									for ($count = 0; $count < count($days_array); $count++) {
										$value 	= $days_array[$count];
										echo "<option value='$value'>$value</option>";
									}
								echo "</select>";
								echo "  ";
								echo "<select class='select_short' id='time_year' name='time_year'>";
									for ($count = 0; $count < count($years_array); $count++) {
										$value 	= $years_array[$count];
										echo "<option value='$value'>$value</option>";
									}
								echo "</select>";
							echo "</td>";
						echo "</tr>";
						echo "<tr>";
							echo "<th colspan='2' style='text-align:center;'>Tier Banner</th>";
						echo "</tr>";
						echo "<tr>";
							echo "<th colspan='2' style='text-align:center;'><input class='dropbox_file' type='file' name='file'></th>";
						echo "</tr>";
						echo "<tr>";
							echo "<th colspan='2' style='text-align:center;'>";
								echo "<input name='form_new_tier' type='submit' value='Create Tier!'>";
							echo "</th>";
						echo "</tr>";
					echo "</tbody>";
				echo "</table>";
			echo "</form>";		
			echo "<form id='form_quick_submit' action='{$GLOBALS['page_admin']}' method='POST' enctype='multipart/form-data' runat='server'>";
				echo "<table class='table_data form' style='float:right; margin-bottom:1%; width:auto !important;'>";
					echo "<thead>";
						echo "<th colspan='2'>Add Guild Kill</th>";
					echo "</thead>";
					echo "<tbody>";
						echo "<tr>";
							echo "<th>Guild</th>";
							echo "<td>";
								echo "<select class='select_short' id='guild_id' name='guild_id'>";
									foreach ( $guild_array as $guild_id => $guild_details ) {
										$guild_name 	= $guild_details['name'];

										echo "<option value='$guild_id'>$guild_name</option>";
									}
								echo "</select>";
							echo "</td>";
						echo "</tr>";
						echo "<tr>";
							echo "<th>Encounter</th>";
							echo "<td>";
								echo "<select class='select_short' id='encounter' name='encounter'>";
									foreach ( $encounter_array as $encounter_id => $encounter_details ) {
										$encounter_tier 	= $encounter_details['tier'];
										$encounter_name 	= $encounter_details['encounter_name'];

										echo "<option value='$encounter_id"."-$encounter_tier'>$encounter_name</option>";
									}
								echo "</select>";
							echo "</td>";
						echo "</tr>";
						echo "<tr>";
							echo "<th>Kill Date (M-D-Y)</th>";
							echo "<td>";
								echo "";
								echo "<select class='select_short' id ='time_month' name='time_month'>";
									for ($count = 0; $count < count($months_array); $count++) {
										$value 	= $months_array[$count];
										echo "<option value='$value'>$value</option>";
									}
								echo "</select>";
								echo "   ";
								echo "<select class='select_short' id='time_day' name='time_day'>";
									for ($count = 0; $count < count($days_array); $count++) {
										$value 	= $days_array[$count];
										echo "<option value='$value'>$value</option>";
									}
								echo "</select>";
								echo "  ";
								echo "<select class='select_short' id='time_year' name='time_year'>";
									for ($count = 0; $count < count($years_array); $count++) {
										$value 	= $years_array[$count];
										echo "<option value='$value'>$value</option>";
									}
								echo "</select>";
							echo "</td>";
						echo "</tr>";
						echo "<tr>";
							echo "<th>Kill Time (H:M)</th>";
							echo "<td>";
								echo "<select class='select_short' id ='time_hours' name='time_hours'>";
									for ($count = 0; $count < count($hours_array); $count++) {
										$value 	= $hours_array[$count];
										$actual_value = "";
										if ( $value > 12 ) {
											$actual_value = ($value-12)."pm";
										} else {
											$actual_value = $value."am";
										}
										echo "<option value='$value'>$actual_value</option>";
									}
								echo "</select>";
								echo "   ";
								echo "<select class='select_short' id='time_minutes' name='time_minutes'>";
									for ($count = 0; $count < count($minutes_array); $count++) {
										$value 	= $minutes_array[$count];
										echo "<option value='$value'>$value</option>";
									}
								echo "</select>";
							echo "</td>";
						echo "</tr>";
						echo "<tr>";
							echo "<th>Video Link</td>";
							echo "<td><input style='width:225px;' type='text' id ='video' name='video'/></td>";
						echo "</tr>";
						echo "<tr>";
							echo "<th colspan='2' style='text-align:center;'>Kill Screenshot</th>";
						echo "</tr>";
						echo "<tr>";
							echo "<th colspan='2' style='text-align:center;'><input class='dropbox_file' type='file' name='file'></th>";
						echo "</tr>";
						echo "<tr>";
							echo "<th colspan='2' style='text-align:center;'>";
								echo "<input name='form_kills_submit' type='submit' value='Submit'>";
							echo "</th>";
						echo "</tr>";
					echo "</tbody>";
				echo "</table>";
			echo "</form>";
		echo "</div>";
		echo "<div class='clear'></div>";

		echo "<div style='width:100%;'>";
			echo "<form id='form_new_dungeon' action='{$GLOBALS['page_admin']}' method='POST' enctype='multipart/form-data' runat='server'>";
				echo "<table class='table_data form' style='float:left; margin-bottom:1%; width:auto !important;'>";
					echo "<thead>";
						echo "<th colspan='2'>Add New Dungeon</th>";
					echo "</thead>";
					echo "<tbody>";
						echo "<tr>";
							echo "<th>Dungeon Name</th>";
							echo "<td><input style='width:225px;' id='textbox_username' type='text' name='dungeon' value=''></td>";
						echo "</tr>";
						echo "<tr>";
							echo "<th>Abbreviation</th>";
							echo "<td><input style='width:225px;' id='textbox_username' type='text' name='abbreviation' value=''></td>";
						echo "</tr>";
						echo "<tr>";
							echo "<th>Tier</th>";
							echo "<td>";
								echo "<select class='select_short' id='tier' name='tier'>";
									foreach ( $tier_array as $tier => $tier_details ) {
										echo "<option value='$tier'>$tier</option>";
									}
								echo "</select>";
							echo "</td>";
						echo "</tr>";
						echo "<tr>";
							echo "<th>Players</th>";
							echo "<td><input style='width:225px;' id='textbox_email1' type='text' name='players'  value=''></td>";
						echo "</tr>";
						echo "<tr>";
							echo "<th>Starting Date (M-D-Y)</th>";
							echo "<td>";
								echo "";
								echo "<select class='select_short' id ='time_month' name='time_month'>";
									for ($count = 0; $count < count($months_array); $count++) {
										$value 	= $months_array[$count];
										echo "<option value='$value'>$value</option>";
									}
								echo "</select>";
								echo "   ";
								echo "<select class='select_short' id='time_day' name='time_day'>";
									for ($count = 0; $count < count($days_array); $count++) {
										$value 	= $days_array[$count];
										echo "<option value='$value'>$value</option>";
									}
								echo "</select>";
								echo "  ";
								echo "<select class='select_short' id='time_year' name='time_year'>";
									for ($count = 0; $count < count($years_array); $count++) {
										$value 	= $years_array[$count];
										echo "<option value='$value'>$value</option>";
									}
								echo "</select>";
							echo "</td>";
						echo "</tr>";
						echo "<tr>";
							echo "<th colspan='2' style='text-align:center;'>Dungeon Banner</th>";
						echo "</tr>";
						echo "<tr>";
							echo "<th colspan='2' style='text-align:center;'><input class='dropbox_file' type='file' name='file'></th>";
						echo "</tr>";
						echo "<tr>";
							echo "<th colspan='2' style='text-align:center;'>Dungeon Small Banner</th>";
						echo "</tr>";
						echo "<tr>";
							echo "<th colspan='2' style='text-align:center;'><input class='dropbox_file' type='file' name='file2'></th>";
						echo "</tr>";
						echo "<tr>";
							echo "<th colspan='2' style='text-align:center;'>Dungeon Header Banner</th>";
						echo "</tr>";
						echo "<tr>";
							echo "<th colspan='2' style='text-align:center;'><input class='dropbox_file' type='file' name='file3'></th>";
						echo "</tr>";
						echo "<tr>";
							echo "<th colspan='2' style='text-align:center;'>Dungeon Color</th>";
						echo "</tr>";
						echo "<tr>";
							echo "<th colspan='2' style='text-align:center;'><input class='color' name='color'></th>";
						echo "<tr>";
							echo "<th colspan='2' style='text-align:center;'>";
								echo "<input name='form_new_dungeon' type='submit' value='Create Dungeon!'>";
							echo "</th>";
						echo "</tr>";
					echo "</tbody>";
				echo "</table>";
			echo "</form>";
			echo "<form id='form_new_encounter' action='{$GLOBALS['page_admin']}' method='POST' enctype='multipart/form-data' runat='server'>";
				echo "<table class='table_data form' style='float:right; margin-bottom:1%; width:auto !important;'>";
					echo "<thead>";
						echo "<th colspan='2'>Add New Encounter</th>";
					echo "</thead>";
					echo "<tbody>";
						echo "<tr>";
							echo "<th>Name</th>";
							echo "<td><input style='width:225px;' id='textbox_username' type='text' name='name' value=''></td>";
						echo "</tr>";
						echo "<tr>";
							echo "<th>Encounter Name</th>";
							echo "<td><input style='width:225px;' id='textbox_username' type='text' name='encounter_name' value=''></td>";
						echo "</tr>";
						echo "<tr>";
							echo "<th>Short Name</th>";
							echo "<td><input style='width:225px;' id='textbox_username' type='text' name='encounter_short_name' value=''></td>";
						echo "</tr>";
						echo "<tr>";
							echo "<th>Dungeon</th>";
							echo "<td>";
								echo "<select class='select_short' id='dungeon' name='dungeon'>";
									foreach ( $dungeon_array as $dungeon_id => $dungeon_details ) {
										echo "<option value='$dungeon_id'>".$dungeon_details['name']."</option>";
									}
								echo "</select>";
							echo "</td>";
						echo "</tr>";
						echo "<tr>";
							echo "<th>Starting Date</th>";
							echo "<td>";
								echo "";
								echo "<select class='select_short' id ='time_month' name='time_month'>";
									for ($count = 0; $count < count($months_array); $count++) {
										$value 	= $months_array[$count];
										echo "<option value='$value'>$value</option>";
									}
								echo "</select>";
								echo "   ";
								echo "<select class='select_short' id='time_day' name='time_day'>";
									for ($count = 0; $count < count($days_array); $count++) {
										$value 	= $days_array[$count];
										echo "<option value='$value'>$value</option>";
									}
								echo "</select>";
								echo "  ";
								echo "<select class='select_short' id='time_year' name='time_year'>";
									for ($count = 0; $count < count($years_array); $count++) {
										$value 	= $years_array[$count];
										echo "<option value='$value'>$value</option>";
									}
								echo "</select>";
							echo "</td>";
						echo "</tr>";
						echo "<tr>";
							echo "<th colspan='2' style='text-align:center;'>";
								echo "<input name='form_new_encounter' class='data_button' type='submit' value='Create Encounter!'>";
							echo "</th>";
						echo "</tr>";
					echo "</tbody>";
				echo "</table>";
			echo "</form>";
		echo "</div>";

		echo "<div class='clear'></div>";
		echo "<div style='width:100%;'>";
			echo "<form id='form_new_guild' action='{$GLOBALS['page_admin']}' method='POST' enctype='multipart/form-data' runat='server'>";
				echo "<table class='table_data form' style='float:left; margin-bottom:1%; width:auto !important;'>";
					echo "<thead>";
						echo "<th colspan='2'>Add New Guild</th>";
					echo "</thead>";
					echo "<tbody>";
						echo "<tr>";
							echo "<th>Guild Name</th>";
							echo "<td><input style='width:225px;' id='textbox_username' type='text' name='name' value=''></td>";
						echo "</tr>";
						echo "<tr>";
							echo "<th>Guild Leader</th>";
							echo "<td><input style='width:225px;' id='textbox_email1' type='text' name='leader' value=''></td>";
						echo "</tr>";
						echo "<tr>";
							echo "<th>Faction</th>";
							echo "<td>";
								echo "<select class='select_short' id ='faction' name='faction'>";
									for ($count = 0; $count < count($faction_array); $count++) {
										echo "<option value='".ucfirst($faction_array[$count])."'>".ucfirst($faction_array[$count])."</option>";
									}
								echo "</select>";
							echo "</td>";
						echo "</tr>";
						echo "<tr>";
							echo "<th>Country</th>";
							echo "<td>";
								echo "<select class='select_short' id='country' name='country'>";
									foreach ( $country_array as $country => $country_details ) {
										echo "<option value='".strtolower($country)."'>$country</option>";
									}
								echo "</select>";
							echo "</td>";
						echo "</tr>";
						echo "<tr>";
							echo "<th>Server</th>";
							echo "<td>";
								echo "<select class='select_short' id ='server' name='server'>";
									foreach ( $shard_array as $shard => $shard_details ) {
										echo "<option value='$shard'>$shard</option>";
									}
								echo "</select>";
							echo "</td>";
						echo "</tr>";
						echo "<tr>";
							echo "<th>Website</th>";
							echo "<td><input style='width:225px;' type='text' id ='website' name='website'/></td>";
						echo "</tr>";
						echo "<tr>";
							echo "<th colspan='2' style='text-align:center;'>";
								echo "<input name='form_new_guild' class='data_button' type='submit' value='Create Guild!'>";
							echo "</th>";
						echo "</tr>";
					echo "</tbody>";
				echo "</table>";
			echo "</form>";
			echo "<form id='form_guild_encounter' action='{$GLOBALS['page_admin']}' method='POST' enctype='multipart/form-data' runat='server'>";
				echo "<table class='table_data form' style='float:right; margin-bottom:1%; width:auto !important;'>";
					echo "<thead>";
						echo "<th colspan='2'>Edit Guild Kills</th>";
					echo "</thead>";
					echo "<tbody>";
						echo "<tr>";
							echo "<th>Guild</th>";
							echo "<td>";
								echo "<select class='select_short' id='guild_select' name='guild_select' onChange='update_encounter_list(this)'>";
									foreach ( $guild_array as $guild_id => $guild_details ) {
										$guild_name 	= $guild_details['name'];

										echo "<option value='$guild_id'>$guild_name</option>";
									}
								echo "</select>";
							echo "</td>";
						echo "</tr>";
						echo "<tr>";
							echo "<th>Encounter</th>";
							echo "<td>";
								echo "<div id='encounter_selectarea'></div>";
							echo "</td>";
						echo "</tr>";
				echo "</table>";
			echo "</form>";

			echo "<div id='encounter_detail' style='float:right;'></div>";
		echo "</div>";

		echo "<div class='clear'></div><br>";
		echo "<div style='width:100%;'>";
			echo "<form method='post' action='{$GLOBALS['page_admin']}' enctype='multipart/form-data' runat='server'>";
				echo "<table class='table_data form' style='width:100% !important; float:left;'>";
					echo "<thead>";
						echo "<th colspan='2'>Add News Article</th>";
					echo "</thead>";
					echo "<tbody>";
					echo "<tr>";
						echo "<th>Editor</th>";
						echo "<td>";
							echo $_SESSION['user'];
							echo "<input type='hidden' name='editor' value='".$_SESSION['user']."'>";
						echo "</td>";
					echo "</tr>";
					echo "<tr>";
						echo "<th>Title</th>";
						echo "<td><input type='text' name='title' value=''></td>";
					echo "</tr>";
					echo "<tr>";
						echo "<th>Type</th>";
						echo "<td>";
							echo "<select class='select_short' name='type'>";
								echo "<option value='0'>News</option>";
								echo "<option value='1'>Patch</option>";
							echo "</select>";
						echo "</td>";
					echo "</tr>";
					echo "<tr>";
						echo "<td colspan='2'>";
							echo "<textarea id='tiny' name='content'></textarea>";
						echo "</td>";
							echo "<tr>";
							echo "<td colspan='2' style='text-align:center;'>";
								echo "<input name='form_news_article' class='data_button' type='submit' value='Submit'>";
							echo "</td>";
						echo "</tr>";
					echo "</tbody>";
				echo "</table>";
			echo "</form>";
		echo "</div>";
	}

	function get_all_tiers_admin() {
		$query = mysql_query(sprintf("SELECT *
						FROM %s
						ORDER BY tier DESC",
						mysql_real_escape_string($GLOBALS['table_tier'])
						)) or die(draw_error_page());
		return $query;
	}
 	
 	function create_new_guild($guild_details) {
		$server = $guild_details['server'];

		$query = mysql_query(sprintf("SELECT *
										FROM %s
										WHERE name='%s'",
										mysql_real_escape_string($GLOBALS['table_servers']),
										mysql_real_escape_string($guild_details['server'])
										)) or die(draw_error_page());
		$region = mysql_fetch_array($query);

		$guild_details['region'] = $GLOBALS['global_server_array'][$server]['region'];

		$query = mysql_query(sprintf("INSERT INTO %s
						(name, date_created, guild_leader, website, faction, region, country, server, creator_id, guild_description)
						values('%s','%s','%s','%s','%s','%s','%s','%s','%s','%s')",
						mysql_real_escape_string($GLOBALS['table_guild']),
						mysql_real_escape_string($guild_details['name']),
						mysql_real_escape_string(""),
						mysql_real_escape_string($guild_details['leader']),
						mysql_real_escape_string($guild_details['website']),
						mysql_real_escape_string($guild_details['faction']),
						mysql_real_escape_string($guild_details['region']),
						mysql_real_escape_string($guild_details['country']),
						mysql_real_escape_string($guild_details['server']),
						mysql_real_escape_string("1"),
						mysql_real_escape_string($guild_details['description'])
						)) or die(draw_error_page());
	}

 	function create_new_tier($tier_details, $screenshot) {
		if ( !isset($GLOBALS['global_tier_array'][$tier_details['tier']]) ) {
			$start_date	= $tier_details['time_year']."-".$tier_details['time_month']."-".$tier_details['time_day'];

			$query = mysql_query(sprintf("INSERT INTO %s
							(tier, alt_tier, date_start, date_end, title, alt_title, encounters)
							values('%s','%s','%s','%s','%s','%s','%s')",
							mysql_real_escape_string($GLOBALS['table_tier']),
							mysql_real_escape_string($tier_details['tier']),
							mysql_real_escape_string($tier_details['alt_tier']),
							mysql_real_escape_string($start_date),
							mysql_real_escape_string("0000-00-00"),
							mysql_real_escape_string($tier_details['title']),
							mysql_real_escape_string($tier_details['alt_title']),
							mysql_real_escape_string("0")
							)) or die(draw_error_page());

			$strtotime_start_date 	= strtotime($start_date);
			$end_date 				= strtotime("yesterday", $strtotime_start_date);
			$date 					= date('Y-m-d', $end_date);
			$previous_tier 			= $tier_details['tier'] - 1;

			$query = mysql_query(sprintf("UPDATE %s
							SET date_end ='%s'
							WHERE tier='%s'",
				mysql_real_escape_string($GLOBALS['table_tier']),
				mysql_real_escape_string($date),
				mysql_real_escape_string($previous_tier)
				)) or die(draw_error_page());

			if ( isset($screenshot['file']['name']) && strlen($screenshot['file']['name']) > 0 ) {
				$allowedExts 	= array("jpg", "jpeg", "gif", "png");
				$extension 		= strtolower(end(explode(".", $screenshot['file']['name'])));
				$fileType		= "";

				if (((strtolower($screenshot['file']['type']) == "image/gif")
					|| (strtolower($screenshot['file']['type']) == "image/jpeg")
					|| (strtolower($screenshot['file']['type']) == "image/jpg")
					|| (strtolower($screenshot['file']['type']) == "image/png")
					|| (strtolower($screenshot['file']['type']) == "image/pjpeg"))
					&& ($screenshot['file']['size'] < 4000000) && (getimagesize($screenshot['file']['tmp_name']))
					&& in_array($extension, $allowedExts)) {

					if ( $screenshot['file']['error'] > 0 ) {
						draw_message_banner("Error", "Your screenshot had the current error on upload: ".$screenshot['file']['error']);
					} else {
						if ( strrpos($screenshot['file']['name'], ".gif") > 0 ) {
							$fileType = ".gif";
						} else if ( strrpos($screenshot['file']['name'], ".jpg") > 0 ) {
							$fileType = ".jpg";
						} else if ( strrpos($screenshot['file']['name'], ".jpeg") > 0 ) {
							$fileType = ".jpeg";
						} else if ( strrpos($screenshot['file']['name'], ".png") > 0 ) {
							$fileType = ".png";
						}

						$image_path 		= $_SERVER['DOCUMENT_ROOT'].$GLOBALS['fold_images']."site/tiers/banner_tier_".$tier_details['tier'].".png";
					   	move_uploaded_file($screenshot['file']['tmp_name'], "$image_path");
					}
				}
		  	} else {
		  		$default_path 	= $_SERVER['DOCUMENT_ROOT'].$GLOBALS['fold_images']."site/tiers/banner_tier_default.png";
				$image_path 	= $_SERVER['DOCUMENT_ROOT'].$GLOBALS['fold_images']."site/tiers/banner_tier_".$tier_details['tier'].".png";
			   	copy($default_path, $image_path);
		  	}
		} else {
			draw_message_banner("Error", "The tier you are attempting to add already exists!");
		}
	}

 	function create_new_dungeon($dungeon_details, $screenshot) {
		$start_date		= $dungeon_details['time_year']."-".$dungeon_details['time_month']."-".$dungeon_details['time_day'];
		$color 			= "#".$dungeon_details['color'];
		$dungeon_name 	= strtolower(str_replace(" ", "_", $dungeon_details['dungeon']));

		$query = mysql_query(sprintf("INSERT INTO %s
						(name, abbreviation, tier, players, mobs, date_launch, color)
						values('%s','%s','%s','%s','%s','%s','%s')",
						mysql_real_escape_string($GLOBALS['table_dungeon']),
						mysql_real_escape_string($dungeon_details['dungeon']),
						mysql_real_escape_string($dungeon_details['abbreviation']),
						mysql_real_escape_string($dungeon_details['tier']),
						mysql_real_escape_string($dungeon_details['players']),
						mysql_real_escape_string("0"),
						mysql_real_escape_string($start_date),
						mysql_real_escape_string($color)
						)) or die(draw_error_page());

		if ( isset($screenshot['file']['name']) && strlen($screenshot['file']['name']) > 0 ) {
			$allowedExts 	= array("jpg", "jpeg", "gif", "png");
			$extension 		= strtolower(end(explode(".", $screenshot['file']['name'])));
			$fileType		= "";

			if (((strtolower($screenshot['file']['type']) == "image/gif")
				|| (strtolower($screenshot['file']['type']) == "image/jpeg")
				|| (strtolower($screenshot['file']['type']) == "image/jpg")
				|| (strtolower($screenshot['file']['type']) == "image/png")
				|| (strtolower($screenshot['file']['type']) == "image/pjpeg"))
				&& ($screenshot['file']['size'] < 4000000) && (getimagesize($screenshot['file']['tmp_name']))
				&& in_array($extension, $allowedExts)) {

				if ( $screenshot['file']['error'] > 0 ) {
					draw_message_banner("Error", "Your screenshot had the current error on upload: ".$screenshot['file']['error']);
				} else {
					if ( strrpos($screenshot['file']['name'], ".gif") > 0 ) {
						$fileType = ".gif";
					} else if ( strrpos($screenshot['file']['name'], ".jpg") > 0 ) {
						$fileType = ".jpg";
					} else if ( strrpos($screenshot['file']['name'], ".jpeg") > 0 ) {
						$fileType = ".jpeg";
					} else if ( strrpos($screenshot['file']['name'], ".png") > 0 ) {
						$fileType = ".png";
					}

					$image_path 		= $_SERVER['DOCUMENT_ROOT'].$GLOBALS['fold_images']."site/dungeon/banner_".$dungeon_name.".png";
				   	move_uploaded_file($screenshot['file']['tmp_name'], "$image_path");
				}
			}
	  	} else {
	  		$default_path 	= $_SERVER['DOCUMENT_ROOT'].$GLOBALS['fold_images']."site/dungeons/banner_default.png";
			$image_path 	= $_SERVER['DOCUMENT_ROOT'].$GLOBALS['fold_images']."site/dungeons/banner_".$dungeon_name.".png";
		   	copy($default_path, $image_path);
	  	}

		if ( isset($screenshot['file2']['name']) && strlen($screenshot['file2']['name']) > 0 ) {
			$allowedExts 	= array("jpg", "jpeg", "gif", "png");
			$extension 		= strtolower(end(explode(".", $screenshot['file2']['name'])));
			$fileType		= "";

			if (((strtolower($screenshot['file2']['type']) == "image/gif")
				|| (strtolower($screenshot['file2']['type']) == "image/jpeg")
				|| (strtolower($screenshot['file2']['type']) == "image/jpg")
				|| (strtolower($screenshot['file2']['type']) == "image/png")
				|| (strtolower($screenshot['file2']['type']) == "image/pjpeg"))
				&& ($screenshot['file2']['size'] < 4000000) && (getimagesize($screenshot['file2']['tmp_name']))
				&& in_array($extension, $allowedExts)) {

				if ( $screenshot['file2']['error'] > 0 ) {
					draw_message_banner("Error", "Your screenshot had the current error on upload: ".$screenshot['file2']['error']);
				} else {
					if ( strrpos($screenshot['file2']['name'], ".gif") > 0 ) {
						$fileType = ".gif";
					} else if ( strrpos($screenshot['file2']['name'], ".jpg") > 0 ) {
						$fileType = ".jpg";
					} else if ( strrpos($screenshot['file2']['name'], ".jpeg") > 0 ) {
						$fileType = ".jpeg";
					} else if ( strrpos($screenshot['file2']['name'], ".png") > 0 ) {
						$fileType = ".png";
					}

					$image_path 		= $_SERVER['DOCUMENT_ROOT'].$GLOBALS['fold_images']."site/dungeon/banner_small_".$dungeon_name.".png";
				   	move_uploaded_file2($screenshot['file2']['tmp_name'], "$image_path");
				}
			}
	  	} else {
	  		$default_path 	= $_SERVER['DOCUMENT_ROOT'].$GLOBALS['fold_images']."site/dungeons/banner_small_default.png";
			$image_path 	= $_SERVER['DOCUMENT_ROOT'].$GLOBALS['fold_images']."site/dungeons/banner_small_".$dungeon_name.".png";
		   	copy($default_path, $image_path);
	  	}

		if ( isset($screenshot['file3']['name']) && strlen($screenshot['file3']['name']) > 0 ) {
			$allowedExts 	= array("jpg", "jpeg", "gif", "png");
			$extension 		= strtolower(end(explode(".", $screenshot['file3']['name'])));
			$fileType		= "";

			if (((strtolower($screenshot['file3']['type']) == "image/gif")
				|| (strtolower($screenshot['file3']['type']) == "image/jpeg")
				|| (strtolower($screenshot['file3']['type']) == "image/jpg")
				|| (strtolower($screenshot['file3']['type']) == "image/png")
				|| (strtolower($screenshot['file3']['type']) == "image/pjpeg"))
				&& ($screenshot['file3']['size'] < 4000000) && (getimagesize($screenshot['file3']['tmp_name']))
				&& in_array($extension, $allowedExts)) {

				if ( $screenshot['file3']['error'] > 0 ) {
					draw_message_banner("Error", "Your screenshot had the current error on upload: ".$screenshot['file3']['error']);
				} else {
					if ( strrpos($screenshot['file3']['name'], ".gif") > 0 ) {
						$fileType = ".gif";
					} else if ( strrpos($screenshot['file3']['name'], ".jpg") > 0 ) {
						$fileType = ".jpg";
					} else if ( strrpos($screenshot['file3']['name'], ".jpeg") > 0 ) {
						$fileType = ".jpeg";
					} else if ( strrpos($screenshot['file3']['name'], ".png") > 0 ) {
						$fileType = ".png";
					}

					$image_path 		= $_SERVER['DOCUMENT_ROOT'].$GLOBALS['fold_images']."header/header_bg_".$dungeon_name.".gif";
				   	move_uploaded_file3($screenshot['file3']['tmp_name'], "$image_path");
				}
			}
	  	} else {
	  		$default_path 	= $_SERVER['DOCUMENT_ROOT'].$GLOBALS['fold_images']."header/header_bg_default.gif";
			$image_path 	= $_SERVER['DOCUMENT_ROOT'].$GLOBALS['fold_images']."header/header_bg_".$dungeon_name.".gif";
		   	copy($default_path, $image_path);
	  	}
	}

 	function create_new_encounter($encounter_details) {
		$start_date		= $encounter_details['time_year']."-".$encounter_details['time_month']."-".$encounter_details['time_day'];

		$dungeon_details = $GLOBALS['global_dungeon_array'][$encounter_details['dungeon']];	

		$query = mysql_query(sprintf("INSERT INTO %s
						(name, dungeon, dungeon_id, players, tier, mob_type, encounter_name, encounter_short_name, date_launch, mob_order)
						values('%s','%s','%s','%s','%s','%s','%s','%s','%s','%s')",
						mysql_real_escape_string($GLOBALS['table_encounters']),
						mysql_real_escape_string($encounter_details['name']),
						mysql_real_escape_string($dungeon_details['name']),
						mysql_real_escape_string($dungeon_details['dungeon_id']),
						mysql_real_escape_string($dungeon_details['players']),
						mysql_real_escape_string($dungeon_details['tier']),
						mysql_real_escape_string("1"),
						mysql_real_escape_string($encounter_details['encounter_name']),
						mysql_real_escape_string($encounter_details['encounter_short_name']),
						mysql_real_escape_string($start_date),
						mysql_real_escape_string("0")
						)) or die(draw_error_page());

		$num_of_encounters = $dungeon_details['mobs'] + 1;

		$query = mysql_query(sprintf("UPDATE %s
						SET mobs ='%s'
						WHERE dungeon_id='%s'",
			mysql_real_escape_string($GLOBALS['table_dungeon']),
			mysql_real_escape_string($num_of_encounters),
			mysql_real_escape_string($dungeon_details['dungeon_id'])
			)) or die(draw_error_page());

		$num_of_encounters = $GLOBALS['global_tier_array'][$dungeon_details['tier']]['encounters'] + 1;

		$query = mysql_query(sprintf("UPDATE %s
						SET encounters ='%s'
						WHERE tier='%s'",
			mysql_real_escape_string($GLOBALS['table_tier']),
			mysql_real_escape_string($num_of_encounters),
			mysql_real_escape_string($dungeon_details['tier'])
			)) or die(draw_error_page());
	}

 	function create_news_article($news_details) {
		$query = mysql_query(sprintf("INSERT INTO %s
						(title, content, added_by, published, type)
						values('%s','%s','%s','%s','%s')",
						mysql_real_escape_string($GLOBALS['table_news']),
						mysql_real_escape_string($news_details['title']),
						mysql_real_escape_string($news_details['content']),
						mysql_real_escape_string($news_details['editor']),
						mysql_real_escape_string("1"),
						mysql_real_escape_string($news_details['type'])
						)) or die(draw_error_page());

		create_post_google($news_details['title'], 0);
		create_post_twitter($news_details['title'], 0);
		create_post_facebook($news_details['title'], 0);
	}

	function edit_guild_encounter($encounter_details) {
		$guild_details = $current_encounter_details = $encounter_array = array();
		$guild_details = get_guild_details($encounter_details['guild_id']);

		if ( isset($guild_details['progression']) && strlen($guild_details['progression']) > 0 ) {
			$progression_details_array  = explode("~~", $guild_details['progression']);

			for ( $count = 0; $count < count($progression_details_array); $count++ ) {
				$progression_details   = explode("||", $progression_details_array[$count]);

				if ($progression_details[0] == $encounter_details['encounter_id']) {
					$current_encounter_details = $progression_details;
				} else {
					array_push($encounter_array, $progression_details_array[$count]);
				}
			}

			$new_date = $encounter_details['day_select'] . "-" . $encounter_details['month_select'] . "-" . $encounter_details['year_select'];
			$new_time = $encounter_details['hour_select'] . ":" . $encounter_details['minute_select'];

			$current_encounter_details[6] = $new_date;
			$current_encounter_details[7] = $new_time;
			$current_encounter_details[8] = $encounter_details['screenshot_input'];
			$current_encounter_details[9] = $encounter_details['video_input'];

			array_push($encounter_array, implode("||", $current_encounter_details));

			$progression_string = implode("~~", $encounter_array);

			$query = mysql_query(sprintf("UPDATE %s
							SET progression ='%s'
							WHERE guild_id='%s'",
				mysql_real_escape_string($GLOBALS['table_guild']),
				mysql_real_escape_string($progression_string),
				mysql_real_escape_string($encounter_details['guild_id'])
				)) or die(draw_error_page());

			$query = mysql_query(sprintf("UPDATE %s
							SET date ='%s', time='%s', update_rank=0
							WHERE guild_id='%s'
							AND encounter_id='%s'",
				mysql_real_escape_string($GLOBALS['table_recent_raid']),
				mysql_real_escape_string($new_date),
				mysql_real_escape_string($new_time),
				mysql_real_escape_string($encounter_details['guild_id']),
				mysql_real_escape_string($encounter_details['encounter_id'])
				)) or die(draw_error_page());
		}
	}

	function delete_guild_encounter($encounter_details) {
		$guild_details = $current_encounter_details = $encounter_array = array();
		$guild_details = get_guild_details($encounter_details['guild_id']);

		if ( isset($guild_details['progression']) && strlen($guild_details['progression']) > 0 ) {
			$progression_details_array  = explode("~~", $guild_details['progression']);

			for ( $count = 0; $count < count($progression_details_array); $count++ ) {
				$progression_details   = explode("||", $progression_details_array[$count]);

				if ($progression_details[0] != $encounter_details['encounter_id']) {
					array_push($encounter_array, $progression_details_array[$count]);
				}
			}

			$progression_string = implode("~~", $encounter_array);

			$query = mysql_query(sprintf("UPDATE %s
							SET progression ='%s'
							WHERE guild_id='%s'",
				mysql_real_escape_string($GLOBALS['table_guild']),
				mysql_real_escape_string($progression_string),
				mysql_real_escape_string($encounter_details['guild_id'])
				)) or die(draw_error_page());	

			$query = mysql_query(sprintf("DELETE FROM %s
							WHERE guild_id='%s'
							AND encounter_id='%s'",
				mysql_real_escape_string($GLOBALS['table_recent_raid']),
				mysql_real_escape_string($encounter_details['guild_id']),
				mysql_real_escape_string($encounter_details['encounter_id'])
				)) or die(draw_error_page());

			$query = mysql_query(sprintf("UPDATE %s
							SET update_rank='0'
							WHERE encounter_id='%s'",
				mysql_real_escape_string($GLOBALS['table_recent_raid']),
				mysql_real_escape_string($encounter_details['encounter_id'])
				)) or die(draw_error_page());			
		}
	}

	function login_admin($login_details) {
		$username = $login_details['admin_name'];
		$passcode = decrypt_password($username, $login_details['admin_pass']);

		$query = mysql_query(sprintf("SELECT *
				FROM %s
				WHERE username = '%s' AND passcode = '%s'",
				mysql_real_escape_string($GLOBALS['table_users']),
				mysql_real_escape_string($username),
				mysql_real_escape_string($passcode)
				)) or die(draw_error_page());

		return mysql_fetch_array($query);
	}

	/*
  	function decrypt_password($user, $pass) {
  		$encrypt_password = sha1($pass);
  		
   		for ( $count = 0; $count < 5; $count++ ) {
   			$encrypt_password = sha1($encrypt_password.$count);
   		}
   		
   		crypt($encrypt_password);
   		   		
 		return $encrypt_password;
 	}
 	*/
?>