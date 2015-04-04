<?php
	$GLOBALS['table_dungeon'] 			= "dungeon_table";
	$GLOBALS['table_encounters'] 		= "encounterlist_table";
	$GLOBALS['table_faq'] 				= "faq_table";
	$GLOBALS['table_factions'] 			= "faction_table";
	$GLOBALS['table_tier'] 				= "tier_table";
	$GLOBALS['table_log'] 				= "log_table";
	$GLOBALS['table_servers'] 			= "server_table";
	$GLOBALS['table_region'] 			= "region_table";
	$GLOBALS['table_users'] 			= "users_table";
	$GLOBALS['table_guild'] 			= "guild_table";
	$GLOBALS['table_news'] 				= "news_table";
	$GLOBALS['table_country'] 			= "country_table";
	$GLOBALS['table_message'] 			= "message_table";
	$GLOBALS['table_recent_raid'] 		= "recent_raid_table";
	$GLOBALS['table_document'] 			= "document_table";

	$GLOBALS['global_guild_array'] 		= array();
	$GLOBALS['global_region_array'] 	= array();
	$GLOBALS['global_server_array'] 	= array();
	$GLOBALS['global_tier_array'] 		= array();
	$GLOBALS['global_dungeon_array'] 	= array();
	$GLOBALS['global_encounter_array'] 	= array();
	$GLOBALS['global_country_array'] 	= array();
	$GLOBALS['global_faction_array'] 	= array();
	$GLOBALS['global_email_array'] 		= array();

	$query = get_all_guilds();
	while ($row = mysql_fetch_array($query)) { $GLOBALS['global_guild_array'][$row['guild_id']] = $row; }

	$query = get_all_regions();
	while ($row = mysql_fetch_array($query)) { $GLOBALS['global_region_array'][$row['abbreviation']] = $row; }

	$query = get_all_servers();
	while ($row = mysql_fetch_array($query)) { $row['name'] = utf8_encode($row['name']); $GLOBALS['global_server_array'][$row['name']] = $row; }

	$query = get_all_tiers();
	while ($row = mysql_fetch_array($query)) { $GLOBALS['global_tier_array'][$row['tier']] = $row; }

	$query = get_all_dungeons();
	while ($row = mysql_fetch_array($query)) { $GLOBALS['global_dungeon_array'][$row['dungeon_id']] = $row; }

	$query = get_all_encounters();
	while ($row = mysql_fetch_array($query)) { $GLOBALS['global_encounter_array'][$row['encounter_id']] = $row; }

	$query = get_all_countries();
	while ($row = mysql_fetch_array($query)) { $GLOBALS['global_country_array'][$row['name']] = $row; }

	$query = get_all_factions();
	while ($row = mysql_fetch_array($query)) { $GLOBALS['global_faction_array'][$row['name']] = $row; }

	$query = get_all_emails();
	while ($row = mysql_fetch_array($query)) { $GLOBALS['global_email_array'][$row['email']] = $row; }

	/*****DEFAULT QUERYS*****/
	function get_all_servers() {
		$query = mysql_query(sprintf(
			"SELECT *
			 FROM %s
			 ORDER BY name ASC",
			mysql_real_escape_string($GLOBALS['table_servers'])
			)) or die(draw_error_page());
		return $query;
	}

	function get_all_regions() {
		$query = mysql_query(sprintf(
			"SELECT *
			 FROM %s
			 ORDER BY abbreviation DESC",
			mysql_real_escape_string($GLOBALS['table_region'])
			)) or die(draw_error_page());
		return $query;
	}

	function get_all_tiers() {
		$query = mysql_query(sprintf(
			"SELECT *
			 FROM %s
			 ORDER BY tier DESC",
			mysql_real_escape_string($GLOBALS['table_tier'])
			)) or die(draw_error_page());
		return $query;
	}

	function get_all_dungeons() {
		$query = mysql_query(sprintf(
			"SELECT *
			 FROM %s
			 ORDER BY players DESC, dungeon_id DESC",
			mysql_real_escape_string($GLOBALS['table_dungeon'])
			)) or die(draw_error_page());
		return $query;
	}

	function get_all_encounters() {
		$query  = mysql_query(sprintf(
			"SELECT *
			 FROM %s
			 ORDER BY tier DESC, dungeon DESC, mob_order ASC",
			mysql_real_escape_string($GLOBALS['table_encounters'])
			)) or die(draw_error_page());
		return $query;
	}

	function get_all_countries() {
		$query = mysql_query(sprintf(
			"SELECT *
			 FROM %s
			 ORDER BY name ASC",
			mysql_real_escape_string($GLOBALS['table_country'])
			)) or die(draw_error_page());
		return $query;
	}

	function get_all_factions() {
		$query  = mysql_query(sprintf(
			"SELECT *
			 FROM %s
			 ORDER BY faction_id DESC",
			mysql_real_escape_string($GLOBALS['table_factions'])
			)) or die(draw_error_page());
		return $query;
	}

	function get_all_guilds() {
		$query = mysql_query(sprintf(
			"SELECT *
			 FROM %s
			 ORDER BY name ASC",
			mysql_real_escape_string($GLOBALS['table_guild'])
			)) or die(draw_error_page());
		return $query;
	}

	function get_all_emails() {
		$query = mysql_query(sprintf(
			"SELECT username, email
			 FROM %s
			 ORDER BY email ASC",
			mysql_real_escape_string($GLOBALS['table_users'])
			)) or die(draw_error_page());
		return $query;
	}

    function insert_recent_activity($guild_details, $encounter_details, $kill_details) {
    	log_entry(0, "Starting Insert Recent Raid Activity: {$guild_details['name']}'s' {$encounter_details['encounter_name']} recent raid activity into database.");

        $query = mysql_query(sprintf(
            "INSERT INTO %s
             (guild_id, encounter_id, strtotime)
             values('%s','%s','%s')",
             mysql_real_escape_string($GLOBALS['table_recent_raid']),
             mysql_real_escape_string($guild_details['guild_id']),
             mysql_real_escape_string($encounter_details['encounter_id']),
             mysql_real_escape_string($kill_details['strtotime'])
             )) or die(log_entry(3, "Error Inserting into recent raid activity database. Guild: {$guild_details['guild_id']} - Encounter: {$encounter_details['encounter_id']} - ".mysql_error()));

        log_entry(0, "Insert Recent Raid Activity Completed!");
    }

    function insert_kill_details($guild_details, $encounter_details, $kill_details, $image_path) {
    	log_entry(0, "Starting Insert Kill Details: {$guild_details['name']} Encounter: {$encounter_details['encounter_name']}");

    	// 0 - Encounter ID
    	// 1 - Date
    	// 2 - Time
    	// 3 - Time Zone
    	// 4 - Image Path
    	// 5 - Video Link
    	// 6 - Server Rank
    	// 7 - Region Rank
    	// 8 - World Rank
    	// 9 - Server

        $guild_progress_string  = $guild_details['progression'];
        $progression_string     = $encounter_details['encounter_id']."||".
                                $kill_details['db_date']."||".
                                $kill_details['time']."|| ||".
                                $image_path."||".
                                $kill_details['video']."||".
                                "0||0||0||";

        if ( isset($guild_progress_string) && strlen($guild_progress_string) > 0 ) {
            $guild_progress_string = $guild_progress_string."~~".$progression_string;
        } else {
            $guild_progress_string = $progression_string;
        }

        log_entry(0, "Update {$guild_details['name']}'s' kill progression.");

        $query = mysql_query(sprintf(
        	"UPDATE %s
             SET progression ='%s'
             WHERE guild_id='%s'",
             mysql_real_escape_string($GLOBALS['table_guild']),
             mysql_real_escape_string($guild_progress_string),
             mysql_real_escape_string($guild_details['guild_id'])
             )) or die(log_entry(3, "Error Inserting into recent raid activity database. Guild: {$guild_details['guild_id']} - Encounter: {$encounter_details['encounter_id']} - ".mysql_error()));

        log_entry(0, "Insert Kill Details Completed!");
    }

	function remove_kill_details($kill_details) {
		$guild_id 			= $kill_details['guild_id'];
		$encounter_id 		= $kill_details['encounter_id'];
		$guild_details 		= $GLOBALS['global_guild_array'][$guild_id];
		$encounter_array 	= explode("~~", $guild_details['progression']);
		$points_array 		= explode("~~", $guild_details['rank_encounter']);

		log_entry(0, "Starting Guild Kill Removal: {$guild_details['name']} for encounter: {$encounter_id}.");

		for ( $count = 0; $count < count($encounter_array); $count++ ) {
			if ( isset($encounter_array[$count]) && strlen($encounter_array[$count]) > 0 ) {
				$encounter_details = explode("||", $encounter_array[$count]);

				if ( $encounter_details[0] == $encounter_id ) {
					unset($encounter_array[$count]);
					break;
				}
			}
		}

		for ( $count = 0; $count < count($points_array); $count++ ) {
			if ( isset($points_array[$count]) && strlen($points_array[$count]) > 0 ) {
				$points_details = explode("||", $points_array[$count]);

				if ( $points_details[0] == $encounter_id ) {
					unset($points_array[$count]);
					break;
				}
			}
		}

		$progression_string = implode("~~", $encounter_array);
		$points_string 		= implode("~~", $points_array);

		log_entry(0, "Removing Kill Details from Guild Table.");
		$query = mysql_query(sprintf(
			"UPDATE %s
			 SET progression ='%s', rank_encounter ='', rank_dungeon ='', rank_tier='', rank_size='', rank_overall='', rank_tier_size=''
			 WHERE guild_id='%s'",
			 mysql_real_escape_string($GLOBALS['table_guild']),
			 mysql_real_escape_string($progression_string),
			 mysql_real_escape_string($guild_id)
			 )) or die(draw_error_page());

		log_entry(0, "Removing Kill Details from Recent Raid Table.");
		$query = mysql_query(sprintf(
			"DELETE
			 FROM %s
			 WHERE guild_id='%s'
			 AND encounter_id='%s'",
			 mysql_real_escape_string($GLOBALS['table_recent_raid']),
			 mysql_real_escape_string($guild_id),
			 mysql_real_escape_string($encounter_id)
			 )) or die(draw_error_page());

		$query = mysql_query(sprintf(
			"UPDATE %s
			 SET update_rank='0'
			 WHERE encounter_id='%s'",
			 mysql_real_escape_string($GLOBALS['table_recent_raid']),
			 mysql_real_escape_string($encounter_id)
			 )) or die(draw_error_page());
	}

	function insert_new_guild($guild_details, $logo) {
		log_entry(0, "Starting Insert New Guild: {$guild_details['name']}...");

		$image_path 	= "";
		$default_path 	= strtolower("{$_SERVER['DOCUMENT_ROOT']}{$GLOBALS['fold_guild_logos']}tmp/default.png");
		$valid_logo 	= 1;

		$guild_details['region'] = $GLOBALS['global_server_array'][$guild_details['server']]['region'];
		
		if ( strlen($guild_details['leader']) == 0 ) $guild_details['leader'] = "N/A"; 

		log_entry(0, "Inserting New Guild Into Database.");
		$query = mysql_query(sprintf(
			"INSERT INTO %s
			 SET name = '%s', 
			 country = '%s',
			 server = '%s',
			 region = '%s',
			 faction = '%s',
			 website = '%s',
			 facebook = '%s',
			 twitter = '%s',
			 google = '%s',
			 leader = '%s',
			 active = 1,
			 creator_id = '%s'",
			 mysql_real_escape_string($GLOBALS['table_guild']),
			 mysql_real_escape_string($guild_details['name']),
			 mysql_real_escape_string($guild_details['country']),
			 mysql_real_escape_string($guild_details['server']),
			 mysql_real_escape_string($guild_details['region']),
			 mysql_real_escape_string($guild_details['faction']),
			 mysql_real_escape_string($guild_details['website']),
			 mysql_real_escape_string($guild_details['facebook']),
			 mysql_real_escape_string($guild_details['twitter']),
			 mysql_real_escape_string($guild_details['google']),
			 mysql_real_escape_string($guild_details['leader']),
			 mysql_real_escape_string($_SESSION['user_id'])
			 )) or die(mysql_error());

		$query = mysql_query(sprintf(
			"SELECT *
			 FROM %s
			 WHERE name = '%s' 
			 AND country = '%s'
			 AND server = '%s'
			 AND region = '%s'
			 AND faction = '%s'
			 AND website = '%s'
			 AND facebook = '%s'
			 AND twitter = '%s'
			 AND google = '%s'
			 AND leader = '%s'
			 AND active = 1
			 AND creator_id = '%s'",
			 mysql_real_escape_string($GLOBALS['table_guild']),
			 mysql_real_escape_string($guild_details['name']),
			 mysql_real_escape_string($guild_details['country']),
			 mysql_real_escape_string($guild_details['server']),
			 mysql_real_escape_string($guild_details['region']),
			 mysql_real_escape_string($guild_details['faction']),
			 mysql_real_escape_string($guild_details['website']),
			 mysql_real_escape_string($guild_details['facebook']),
			 mysql_real_escape_string($guild_details['twitter']),
			 mysql_real_escape_string($guild_details['google']),
			 mysql_real_escape_string($guild_details['leader']),
			 mysql_real_escape_string($_SESSION['user_id'])
			 )) or die(mysql_error());

		$new_guild_details = mysql_fetch_array($query);

		$image_path = strtolower("{$_SERVER['DOCUMENT_ROOT']}{$GLOBALS['fold_guild_logos']}logo-{$new_guild_details['guild_id']}");

		log_entry(0, "Validating Guild Logo.");
		if ( validate_image($logo) == 0 ) {
			log_entry(0, "Setting Guild Logo as Uploaded File.");
			move_uploaded_file($logo['file']['tmp_name'], $image_path);
	  	} else {
	  		log_entry(0, "Setting Default Logo as Guild Logo.");
	  		copy($default_path, $image_path);
	  	}

	  	$_SESSION['guild_id'] = $new_guild_details['guild_id'];
	}

	function insert_new_raid_team($team_details, $guild_details) {
		log_entry(0, "Starting Insert New Raid Team: {$team_details['name']}...");

		$image_path 	= "";

		$team_details['region'] = $GLOBALS['global_server_array'][$team_details['server']]['region'];
		
		if ( strlen($guild_details['leader']) == 0 ) $guild_details['leader'] = "N/A"; 

		log_entry(0, "Inserting Team Details into Database.");
		$query = mysql_query(sprintf(
			"INSERT INTO %s
			 SET name = '%s', 
			 country = '%s',
			 server = '%s',
			 region = '%s',
			 faction = '%s',
			 website = '%s',
			 facebook = '%s',
			 twitter = '%s',
			 google = '%s',
			 leader = '%s',
			 type = '%s',
			 parent = '%s',
			 active = 1,
			 creator_id = '%s'",
			 mysql_real_escape_string($GLOBALS['table_guild']),
			 mysql_real_escape_string($team_details['team']),
			 mysql_real_escape_string($team_details['country']),
			 mysql_real_escape_string($team_details['server']),
			 mysql_real_escape_string($team_details['region']),
			 mysql_real_escape_string($team_details['faction']),
			 mysql_real_escape_string($guild_details['website']),
			 mysql_real_escape_string($guild_details['facebook']),
			 mysql_real_escape_string($guild_details['twitter']),
			 mysql_real_escape_string($guild_details['google']),
			 mysql_real_escape_string($team_details['leader']),
			 mysql_real_escape_string(1),
			 mysql_real_escape_string($guild_details['guild_id']),
			 mysql_real_escape_string($_SESSION['user_id'])
			 )) or die(mysql_error());

		$query = mysql_query(sprintf(
			"SELECT *
			 FROM %s
			 WHERE name = '%s' 
			 AND country = '%s'
			 AND server = '%s'
			 AND region = '%s'
			 AND faction = '%s'
			 AND website = '%s'
			 AND facebook = '%s'
			 AND twitter = '%s'
			 AND google = '%s'
			 AND leader = '%s'
			 AND type = '%s'
			 AND parent = '%s'
			 AND active = 1
			 AND creator_id = '%s'",
			 mysql_real_escape_string($GLOBALS['table_guild']),
			 mysql_real_escape_string($team_details['team']),
			 mysql_real_escape_string($team_details['country']),
			 mysql_real_escape_string($team_details['server']),
			 mysql_real_escape_string($team_details['region']),
			 mysql_real_escape_string($team_details['faction']),
			 mysql_real_escape_string($guild_details['website']),
			 mysql_real_escape_string($guild_details['facebook']),
			 mysql_real_escape_string($guild_details['twitter']),
			 mysql_real_escape_string($guild_details['google']),
			 mysql_real_escape_string($team_details['leader']),
			 mysql_real_escape_string(1),
			 mysql_real_escape_string($guild_details['guild_id']),
			 mysql_real_escape_string($_SESSION['user_id'])
			 )) or die(mysql_error());

		$new_guild_details = mysql_fetch_array($query);

		$image_path_parent 	= strtolower("{$_SERVER['DOCUMENT_ROOT']}{$GLOBALS['fold_guild_logos']}logo-{$guild_details['guild_id']}");
		$image_path_child	= strtolower("{$_SERVER['DOCUMENT_ROOT']}{$GLOBALS['fold_guild_logos']}logo-{$new_guild_details['guild_id']}");

		log_entry(0, "Copying Parent Guild Logo to Child.");
		copy($image_path_parent, $image_path_child);

		$raid_team_string = get_parent_raid_teams($guild_details, "");
		
		if ( strlen($raid_team_string) == 0 ) {
			$raid_team_string = $new_guild_details['guild_id'];
		} else {
			$raid_team_string .= "||{$new_guild_details['guild_id']}";
		}

		log_entry(0, "Updating Parent Guild Details with Child.");
        $query = mysql_query(sprintf(
        	"UPDATE %s
             SET child ='%s'
             WHERE guild_id='%s'",
             mysql_real_escape_string($GLOBALS['table_guild']),
             mysql_real_escape_string($raid_team_string),
             mysql_real_escape_string($guild_details['guild_id'])
             )) or die(mysql_error());

	  	$_SESSION['guild_id'] = $new_guild_details['guild_id'];

	  	log_entry(0, "Insert New Raid Team Completed!");
	}

	function update_guild_details($guild_details, $image_details) {
		log_entry(0, "Starting Update Guild Details: {$guild_details['name']}...");

		$image_path = "";
		$logo_path 	= "";

		$guild_details['region'] = $GLOBALS['global_server_array'][$guild_details['server']]['region'];
		
		if ( strlen($guild_details['leader']) == 0 ) $guild_details['leader'] = "N/A";

		if ( isset($image_details['file']['tmp']) ) {
			$image_path = strtolower($_SERVER['DOCUMENT_ROOT']."{$GLOBALS['fold_guild_logos']}logo-{$guild_details['guild_id']}"); 

			log_entry(0, "Validating New Guild Logo.");
			if ( validate_image($image_details) == 0 ) move_uploaded_file($image_details['file']['tmp_name'], $image_path);
		}

		if ( stripos($guild_details['creator_id'], $_SESSION['email']) === FALSE ) {
			log_entry(2, "Attempted to unmark self-user.");
			draw_message_banner("Error", "Unable to update guild details as you can not unmark yourself as a guild user.");
			return;
		}

		$guild_details['creator_id'] = get_creator_id_by_email($guild_details['creator_id']);

		log_entry(0, "Updating Guild Details in Database.");
		$query = mysql_query(sprintf(
			"UPDATE %s
			 SET country = '%s',
			 server = '%s',
			 region = '%s',
			 faction = '%s',
			 website = '%s',
			 facebook = '%s',
			 twitter = '%s',
			 google = '%s',
			 leader = '%s',
			 creator_id = '%s',
			 active = '%s'
			 WHERE guild_id = '%s'",
			 mysql_real_escape_string($GLOBALS['table_guild']),
			 mysql_real_escape_string($guild_details['country']),
			 mysql_real_escape_string($guild_details['server']),
			 mysql_real_escape_string($guild_details['region']),
			 mysql_real_escape_string($guild_details['faction']),
			 mysql_real_escape_string($guild_details['website']),
			 mysql_real_escape_string($guild_details['facebook']),
			 mysql_real_escape_string($guild_details['twitter']),
			 mysql_real_escape_string($guild_details['google']),
			 mysql_real_escape_string($guild_details['leader']),
			 mysql_real_escape_string($guild_details['creator_id']),
			 mysql_real_escape_string($guild_details['active']),
			 mysql_real_escape_string($guild_details['guild_id'])
			 )) or die(mysql_error());

		draw_message_banner("Success", "Your guild profile has been updated!");

		log_entry(0, "Update Guild Details Completed!");
	}

	function remove_guild_details($guild_details) {
		log_entry(0, "Starting Guild Removal: {$guild_details['name']}...");

		$image_path = "";

		log_entry(0, "Removing Guild Details from Guild Table.");
		$query = mysql_query(sprintf(
			"DELETE
			 FROM %s
			 WHERE guild_id='%s'",
			 mysql_real_escape_string($GLOBALS['table_guild']),
			 mysql_real_escape_string($guild_details['guild_id'])
			 )) or die(draw_error_page());

		log_entry(0, "Removing Guild Details from Recent Raid Table.");
		$query = mysql_query(sprintf(
			"DELETE
			 FROM %s
			 WHERE guild_id='%s'",
			 mysql_real_escape_string($GLOBALS['table_recent_raid']),
			 mysql_real_escape_string($guild_details['guild_id'])
			 )) or die(draw_error_page());				

		$image_path = strtolower("{$_SERVER['DOCUMENT_ROOT']}{$GLOBALS['fold_guild_logos']}/logo-{$guild_details['guild_id']}"); 

		log_entry(0, "Removing Guild Logo Image.");
		if ( file_exists($image_path) ) unlink($image_path);

		$guild_details 	= $GLOBALS['global_guild_array'][$guild_details['guild_id']];
		$parent_details = $GLOBALS['global_guild_array'][$guild_details['parent']];

		if ( $guild_details['type'] == 1 ) {
			$raid_team_string = get_parent_raid_teams($parent_details, $guild_details['guild_id']);
		}

		log_entry(0, "Updating Parent Raid Guild (If they exist)");
        $query = mysql_query(sprintf(
        	"UPDATE %s
             SET child ='%s'
             WHERE guild_id='%s'",
             mysql_real_escape_string($GLOBALS['table_guild']),
             mysql_real_escape_string($raid_team_string),
             mysql_real_escape_string($parent_details['guild_id'])
             )) or die(mysql_error());

       	log_entry(0, "Guild Removal Completed!");
	}

	/*****MODULE-News*****/
	function get_news_articles($limit) {
		$query = mysql_query(sprintf(
			"SELECT *
			 FROM %s
			 WHERE published = 1
			 ORDER BY date_added DESC
			 LIMIT $limit",
			 mysql_real_escape_string($GLOBALS['table_news'])
			 )) or die(draw_page_error());
		return $query;	
	}

	function get_specific_news_article($article_title) {
		$query = mysql_query(sprintf(
			"SELECT *
			 FROM %s
			 WHERE published = 1
			 AND title LIKE LOWER('%s')
			 LIMIT 1",
			 mysql_real_escape_string($GLOBALS['table_news']),
			 $article_title
			 )) or die(draw_page_error());
		return $query;	
	}

	/*****MODULE-Login*****/
	function validate_user_pass_details($username, $password) {
		$password = decrypt_password($username, $password);
	
		$query	= mysql_query(sprintf(
			"SELECT *
			 FROM %s
			 WHERE username='%s'
			 AND passcode='%s'",
			 mysql_real_escape_string($GLOBALS['table_users']),
			 mysql_real_escape_string($username),
			 mysql_real_escape_string($password)
			 )) or die(draw_error_page());		
		return mysql_fetch_array($query);
	}

 	function get_num_of_users($username, $password) {
		$password = decrypt_password($username, $password);
		
		$query	= mysql_query(sprintf(
			"SELECT *
			 FROM %s
			 WHERE username='%s'
			 AND passcode='%s'",
			 mysql_real_escape_string($GLOBALS['table_users']),
			 mysql_real_escape_string($username),
			 mysql_real_escape_string($password)
			 )) or die(draw_error_page());
		$num_of_users = mysql_num_rows($query);
		
		return $num_of_users;
	}

	/*****MODULE-User*****/
	function get_creator_emails($creator_id) {
		$user_array 	= explode("||", $creator_id);
		$query_string 	= "";
		$user_string 	= "";

		for ( $count = 0; $count < count($user_array); $count++ ) {
			if ( $query_string == "" ) {
				$query_string = "user_id='{$user_array[$count]}'";
			} else {
				$query_string .= " OR user_id='{$user_array[$count]}'";
			}
		}

		$query	= mysql_query(sprintf(
			"SELECT *
			 FROM %s
			 WHERE %s",
			 mysql_real_escape_string($GLOBALS['table_users']),
			 $query_string
			 )) or die(draw_error_page());

		while ($row = mysql_fetch_array($query)) {
			if ( $user_string == "" ) {
				$user_string = "{$row['email']}";
			} else {
				$user_string .= ",{$row['email']}";
			}
		}

		return $user_string;
	}

	function get_creator_id_by_email($creator_email) {
		$user_array 	= explode(",", $creator_email);
		$query_string 	= "";
		$user_string 	= "";

		for ( $count = 0; $count < count($user_array); $count++ ) {
			if ( $query_string == "" ) {
				$query_string = "email='{$user_array[$count]}'";
			} else {
				$query_string .= " OR email='{$user_array[$count]}'";
			}
		}

		$query	= mysql_query(sprintf(
			"SELECT *
			 FROM %s
			 WHERE %s",
			 mysql_real_escape_string($GLOBALS['table_users']),
			 $query_string
			 )) or die(draw_error_page());

		while ($row = mysql_fetch_array($query)) {
			if ( $user_string == "" ) {
				$user_string = "{$row['user_id']}";
			} else {
				$user_string .= "||{$row['user_id']}";
			}
		}

		return $user_string;		
	}

	function get_user_details($user_id) {
		$query	= mysql_query(sprintf(
			"SELECT *
			 FROM %s
			 WHERE user_id='%s'",
			 mysql_real_escape_string($GLOBALS['table_users']),
			 mysql_real_escape_string($user_id)
			 )) or die(draw_error_page());		
		return mysql_fetch_array($query);
	}

	function get_updated_guild_details($guild_id) {	
		$query	= mysql_query(sprintf(
			"SELECT *
			 FROM %s
			 WHERE guild_id='%s'",
			 mysql_real_escape_string($GLOBALS['table_guild']),
			 mysql_real_escape_string($guild_id)
			 )) or die(draw_error_page());

		$GLOBALS['global_guild_array'][$guild_id] = mysql_fetch_array($query);

		return get_guild_details($guild_id);
	}

	function update_user_details($user_details, $form_details) {
		log_entry(0, "Starting user details update: {$user_details['user_id']}...");

		$encrypt_password = "";

		log_entry(0, "Validating Password...");
		$valid_pass 	= validate_password($form_details['user_edit_pass1'], $form_details['user_edit_pass2']);

		log_entry(0, "Validating Email...");
		$valid_email 	= validate_email($form_details['user_edit_email']);

		if ( $valid_pass == 2 ) draw_message_banner("Error", "New passwords must match!");
		if ( $valid_pass == 3 ) draw_message_banner("Error", "Passwords must be a minimum of {$GLOBALS['password_minimum']} characters!");

		if ( $valid_pass == 1 ) {
			$encrypt_password = encrypt_password($user_details['username'], $form_details['user_edit_pass1']);

			log_entry(0, "Updating User Password: {$user_details['user_id']}");

			$query = mysql_query(sprintf(
				"UPDATE %s
				 SET email = '%s', passcode = '%s'
				 WHERE user_id = '%s'",
				 mysql_real_escape_string($GLOBALS['table_users']),
				 mysql_real_escape_string($form_details['user_edit_email']),
				 mysql_real_escape_string($encrypt_password),
				 mysql_real_escape_string($user_details['user_id'])
				 )) or die(draw_error_page());

			draw_message_banner("Success", "Your account information changes have been updated succcessfully!");
		} else if ( $valid_pass == 0 ) {
			log_entry(0, "Updating Email Address: {$user_details['user_id']}");

			$query = mysql_query(sprintf(
				"UPDATE %s
				 SET email = '%s'
				 WHERE user_id = '%s'",
				 mysql_real_escape_string($GLOBALS['table_users']),
				 mysql_real_escape_string($form_details['user_edit_email']),
				 mysql_real_escape_string($_SESSION['user_id'])
				 )) or die(draw_error_page());

			draw_message_banner("Success", "Your account information changes have been updated succcessfully!");			
		}

		$_SESSION['email'] = $form_details['user_edit_email'];
	}

	/*****MODULE-Register*****/
	function get_newest_guilds() {
		$query = mysql_query(sprintf(
			"SELECT * 
			 FROM %s
			 WHERE type = 0
			 AND progression != ''
			 ORDER BY date_created DESC
			 LIMIT 10",
			 mysql_real_escape_string($GLOBALS['table_guild'])
			 )) or die(draw_error_page());
		return $query;
	}

 	function register_user($username, $email, $passcode) {
 		log_entry(0, "Attempting to register Username: {$username} and Email Address: {$email}...");

		$passcode = encrypt_password($username, $passcode);

		$query = mysql_query(sprintf(
			"INSERT INTO %s
			 (username, passcode, email, active)
			 values('%s','%s','%s', 1)",
			 mysql_real_escape_string($GLOBALS['table_users']),
			 mysql_real_escape_string($username),
			 mysql_real_escape_string($passcode),
			 mysql_real_escape_string($email)
			 )) or die(draw_error_page());

		log_entry(0, "User Registration Completed!");	
	}

 	function validate_user($user) {
 		log_entry(0, "Validating User Details: {$user}...");

 		$check = 0;

 		if ( strlen(trim($user)) >= 4 ) {
	 		$query	= mysql_query(sprintf(
	 			"SELECT *
				 FROM %s
				 WHERE username='%s'",
				 mysql_real_escape_string($GLOBALS['table_users']),
				 mysql_real_escape_string($user)
				 )) or die(draw_error_page());	
	 						
	 		$check = mysql_num_rows($query);
	 	} else {
	 		$check = 1;
	 	}
 		
	 	log_entry(0, "Completed user validation: $check");

		return $check;
 	}

 	/*****MODULE-Forgot*****/
 	function search_for_user_by_email($email) {
		$query = mysql_query(sprintf(
			"SELECT *
			 FROM %s
			 WHERE email = '%s'",
			 mysql_real_escape_string($GLOBALS['table_users']),
			 mysql_real_escape_string($email)
			 )) or die(draw_error_page());
		return mysql_num_rows($query);
	}

	function search_for_user_confirm($user_id, $confirm_code) {
		$query = mysql_query(sprintf(
			"SELECT *
			 FROM %s
			 WHERE user_id = '%s'
			 AND confirmcode = '%s'",
			 mysql_real_escape_string($GLOBALS['table_users']),
			 mysql_real_escape_string($user_id),
			 mysql_real_escape_string($confirm_code)
			 )) or die(draw_error_page());
		return mysql_num_rows($query);
	}

	function get_user_details_by_email($email) {
		$query	= mysql_query(sprintf(
			"SELECT *
			 FROM %s
			 WHERE email='%s'",
			 mysql_real_escape_string($GLOBALS['table_users']),
			 mysql_real_escape_string($email)
			 )) or die(draw_error_page());		
		return mysql_fetch_array($query);
	}

	function set_confirm_code($user_id, $confirm_code) {
		log_entry(0, "Setting user $user_id confirmation code...");

		$query = mysql_query(sprintf(
			"UPDATE %s
			 SET confirmcode='%s'
			 WHERE user_id='%s'",
			 mysql_real_escape_string($GLOBALS['table_users']),
			 mysql_real_escape_string($confirm_code),
			 mysql_real_escape_string($user_id)
			 )) or die(draw_error_page());

		log_entry(0, "User confirmation code completed!");
	}

	function reset_password($user_id, $passcode) {
		$user_details 	= array();
		$success 		= 0;

		log_entry(0, "Attempting to reset user {$user_id} password...");

		$query = mysql_query(sprintf(
			"SELECT *
			 FROM %s
			 WHERE user_id = '%s'",
			 mysql_real_escape_string($GLOBALS['table_users']),
			 mysql_real_escape_string($user_id)
			 )) or die(draw_error_page());

		$user_details 		= mysql_fetch_array($query);
		$encrypt_password 	= encrypt_password($user_details["username"], $passcode);

		if ( isset($user_details['user_id']) ) {
			$query = mysql_query(sprintf(
				"UPDATE %s
				 SET passcode='%s', confirmcode=''
				 WHERE user_id='%s'",
				 mysql_real_escape_string($GLOBALS['table_users']),
				 mysql_real_escape_string($encrypt_password),
				 mysql_real_escape_string($user_details["user_id"])
				 )) or die(draw_error_page());

			$success = 1;		
		}

		log_entry(0, "User password reset complete!: $success");

		return $success;
	}
?>