<?php
	date_default_timezone_set('America/Los_Angeles');

	$server 								= basename(dirname(__FILE__));

	$GLOBALS['site_title'] 					= "RIFT Progress";
	$GLOBALS['site_title_short'] 			= "RIFT Progress";
	$GLOBALS['game_name_1'] 				= "Rift";
	$GLOBALS['game_name_2'] 				= "Storm Legion";
	$GLOBALS['game_name_3'] 				= "Nightmare Tide";
	$GLOBALS['game_url'] 					= "http://www.riftgame.com";
	$GLOBALS['root_path']					= "";
	$GLOBALS['host_name']					= "http://www.topofrift.com";

	if ( !isset($_SERVER['DOCUMENT_ROOT']) ) { $_SERVER['DOCUMENT_ROOT'] = dirname(__FILE__); }
	if ( strpos($server, "stage") > 0 ) { $GLOBALS['root_path'] = "/".basename(dirname(__FILE__)); }
	if ( strpos($server, "stage") > 0 ) { $GLOBALS['host_name'] = "http://www.trinityguild.org/site-rift-stage"; }

	$GLOBALS['meta_author'] 				= "Terry 'Raive' Fowler";
	$GLOBALS['meta_keywords'] 				= "rift, rift raiding, rift progress, progress, raiding, progression, tracker, tracking, rank, ranking, top 25, guild";
	$GLOBALS['meta_description']			= "Rift's #1 Resource for raid progression tracking.";
	$GLOBALS['latest_tier']					= 7;
	$GLOBALS['eu_time_diff']				= 28800; // 8 *3600 = 28800
	$GLOBALS['na_patch_time']				= "N/A (Inconsistant)"; //"UTC -8 Hours (PST)";
	$GLOBALS['eu_patch_time'] 				= "N/A (Inconsistant)"; //"UTC +0 Hours (GMT)";
	$GLOBALS['freeze_kill_count'] 			= 0;
	$GLOBALS['freeze_kill_date'] 			= 0;
	$GLOBALS['base_point_value'] 			= 1000;
	$GLOBALS['update_freq'] 				= 10;
	$GLOBALS['current_dir'] 				= dirname(__FILE__);
	$GLOBALS['release_year']				= 2011;
	$GLOBALS['require_screenshot']			= 1;
	$GLOBALS['require_encounters']			= 0;
	$GLOBALS['screenshot_formats']			= array("jpg", "jpeg", "gif", "png", "bmp");
	$GLOBALS['default_time_zone']			= "America/Los_Angeles";
	$GLOBALS['enable_ads']['header']		= 1;
	$GLOBALS['enable_ads']['block']			= 0;
	$GLOBALS['live'] 						= 0;

	// Go to Graph API Explorer
	// Select Application (Rift Progress)
	// Click Get Access Token
	// Place Access Token in this url
	// https://graph.facebook.com/oauth/access_token?client_id=816307315051965&client_secret=97e6d3e3e7f6decc497428ec53b08cdd&grant_type=fb_exchange_token&fb_exchange_token=***ACCESS TOKEN HERE***

	$GLOBALS['facebook']['app_id']			= "816307315051965";
	$GLOBALS['facebook']['page_id']			= "1404349913116107";
	$GLOBALS['facebook']['secret']			= "97e6d3e3e7f6decc497428ec53b08cdd";
	$GLOBALS['facebook']['token_user']		= "CAALmbVuKjb0BAJNY3d7CtwvsksIZC9nRPLXUitvLyv5QZCf4pGz2eFl5NDKmRCh6XOdyo3ntNqhnr2rzYaaI0XSzpggbYBRPJOHslbt7TdapQDiAibkNOlZATK6vJRJEupUlMs4VwMrZAR86ZBu9QexlM8Wf5RVDZBOSR92JwXhugCcNcVIHhLUuZBtraNQ3UsZD";
	$GLOBALS['facebook']['token_page']		= "CAALmbVuKjb0BAJNY3d7CtwvsksIZC9nRPLXUitvLyv5QZCf4pGz2eFl5NDKmRCh6XOdyo3ntNqhnr2rzYaaI0XSzpggbYBRPJOHslbt7TdapQDiAibkNOlZATK6vJRJEupUlMs4VwMrZAR86ZBu9QexlM8Wf5RVDZBOSR92JwXhugCcNcVIHhLUuZBtraNQ3UsZD";

	$GLOBALS['twitter']['key']				= "LbFIFyK8qx0GY72rlu52Q";
	$GLOBALS['twitter']['secret']			= "VMgEPXl9BEusyJDkCuMJvvug0SyxfqieiKcxcKKHFg";
	$GLOBALS['twitter']['token']			= "1697399862-fB2jnBQqAjgK0vf8hfiCldGchzNWznno6gDq6ya";
	$GLOBALS['twitter']['token_secret']		= "W1gA0kWjrpgM3hals2PjfcuqcqSguX2K8KRDUVP75qUbm";

	$GLOBALS['faction'][0] 					= "defiant";
	$GLOBALS['faction'][1]					= "guardian";

	$GLOBALS['raid_size'][20]				= 20;
	$GLOBALS['raid_size'][10]				= 10;
	
	$GLOBALS['guild_type'][0]				= "Casual Raiding";
	$GLOBALS['guild_type'][1]				= "Semi-Core Raiding";
	$GLOBALS['guild_type'][2]				= "Hardcore Raiding";

	$GLOBALS['raid_size_array'][0]			= 20;
	$GLOBALS['raid_size_array'][1]			= 10;

	$GLOBALS['global_active_array'][0]		= "Inactive";
	$GLOBALS['global_active_array'][1]		= "Active";

	$GLOBALS['global_raid_size_array']  	= array();
	$GLOBALS['global_raid_size_array'][20]	= 20;
	$GLOBALS['global_raid_size_array'][10]	= 10;	

	$GLOBALS['ranking']['type']['prad']		= "Quality Progression";
	$GLOBALS['ranking']['type']['pra']		= "Aethys Point";
	$GLOBALS['ranking']['type']['pram']		= "Aethys Point Flat";

	$GLOBALS['point_system'][0]				= "Quality Progression Ranking";
	$GLOBALS['point_system'][1]				= "Aethys Point Ranking";
	$GLOBALS['point_system'][2]				= "Aethys Point Flat Ranking";

	$GLOBALS['point_system_abbrev'][0]		= "QP";
	$GLOBALS['point_system_abbrev'][1]		= "AP";
	$GLOBALS['point_system_abbrev'][2]		= "APF";

	$GLOBALS['point_system_default'] 		= 0;
	$GLOBALS['password_minimum'] 			= 3;

	$GLOBALS['point_base']					= 1000;
	$GLOBALS['point_final_base']			= 5000;
	$GLOBALS['point_base_mod']				= 2500;

	$GLOBALS['view_type'][0] 				= "server";
	$GLOBALS['view_type'][1] 				= "region";
	$GLOBALS['view_type'][2] 				= "world";

	$GLOBALS['private_key'] 				= "6LcT1-USAAAAAK8a1X30ENTeL48zTeij8utLPFJk";
	$GLOBALS['public_key']					= "6LcT1-USAAAAAD-UphWZwbo72rOsW7u1js2xKbyx";
	$GLOBALS['reg_questions'][0] 			= "What are the two factions? (Defiant & ?????)";
	$GLOBALS['reg_questions'][1] 			= "Which company developed RIFT? (***** World)";
	$GLOBALS['reg_answers'][0] 				= "guardian";
	$GLOBALS['reg_answers'][1] 				= "trion";
	$GLOBALS['email_admin'] 				= "administrator@topofrift.com";
	$GLOBALS['company_1']					= "Trion Worlds";
	$GLOBALS['link_company_1']				= "http://www.trionworlds.com";
	$GLOBALS['link_game_1'] 				= "http://www.riftgame.com";
	$GLOBALS['link_facebook'] 				= "http://www.facebook.com/RiftProgress";
	$GLOBALS['link_twitter'] 				= "http://twitter.com/RiftProgress";
	$GLOBALS['link_google']					= "http://plus.google.com/+Rift-progress/";
	$GLOBALS['copyright'] 					= "&copy; {$GLOBALS['release_year']} {$GLOBALS['site_title']} - All Rights Reserved.";
	
	include_once $GLOBALS['current_dir']."/config/dbinfo.php";
	include_once $GLOBALS['current_dir']."/config/querys.php";
	include_once $GLOBALS['current_dir']."/config/modules.php";
	include_once $GLOBALS['current_dir']."/config/forms.php";
	include_once $GLOBALS['current_dir']."/config/functions.php";
	include_once $GLOBALS['current_dir']."/config/scripts.php";
	include_once $GLOBALS['current_dir']."/config/files.php";
	include_once $GLOBALS['current_dir']."/config/header.php";
	include_once $GLOBALS['current_dir']."/facebook/src/facebook.php";
	include_once $GLOBALS['current_dir']."/twitter/codebird-php-master/src/codebird.php";
	
	ob_start("ob_gzhandler");

	$sid = session_id();

	if ( !isset($sid) || $sid == "" ) {
		session_start();
		$sid = session_id();

		if ( !isset($_SESSION['active']) ) { $_SESSION['active'] = 0; }
	}
?>