<?php
    $GLOBALS['news']['title']                                   = "News";
    $GLOBALS['news']['page_title']                              = "{$GLOBALS['game_name_1']}'s Raid Progression Tracker";
    $GLOBALS['news']['description']                             = "{$GLOBALS['game_name_1']}'s #1 Resource for raid progression tracking.";
    $GLOBALS['news']['set']                                     = 1;

    $GLOBALS['standing']['title']                               = "Standings";
    $GLOBALS['standing']['set']                                 = 1;

    $GLOBALS['ranking']['title']                                = "Rankings";
    $GLOBALS['ranking']['set']                                  = 1;

    $GLOBALS['servers']['title']                                = "Servers";
    $GLOBALS['servers']['set']                                  = 1;

    $GLOBALS['server_rankings']['title']                        = "Server Rankings";
    $GLOBALS['server_rankings']['set']                          = 1;

    $GLOBALS['guild_list']['title']                             = "Guilds";
    $GLOBALS['guild_list']['set']                               = 1;

    $GLOBALS['search']['title']                                 = "Guild Search";
    $GLOBALS['search']['set']                                   = 1;

    $GLOBALS['how']['title']                                    = "How-To";
    $GLOBALS['how']['set']                                      = 1;

    $GLOBALS['tos']['title']                                    = "Terms of Service";
    $GLOBALS['tos']['set']                                      = 1;

    $GLOBALS['privacy']['title']                                = "Privacy Policy";
    $GLOBALS['privacy']['set']                                  = 1;

    $GLOBALS['contact']['title']                                = "Contact Us";
    $GLOBALS['contact']['set']                                  = 1;

    $GLOBALS['quick']['title']                                  = "Quick Kill Submission";
    $GLOBALS['quick']['set']                                    = 1;

    $GLOBALS['social']['title']                                 = "Social";
    $GLOBALS['social']['set']                                   = 1;

    $GLOBALS['register']['title']                               = "Register";
    $GLOBALS['register']['set']                                 = 1;

    $GLOBALS['forgot']['title']                                 = "Forgot Password?";
    $GLOBALS['forgot']['set']                                   = 1;

    $GLOBALS['user']['title']                                   = "Control Panel";
    $GLOBALS['user']['set']                                     = 1;
    
    $GLOBALS['guild']['title']                                  = "Guild Details";
    $GLOBALS['guild']['set']                                    = 1;

    $GLOBALS['login']['title']                                  = "Login";
    $GLOBALS['login']['set']                                    = 1;

    $GLOBALS['logout']['title']                                 = "Logout";
    $GLOBALS['logout']['set']                                   = 1;

    $GLOBALS['news']['block_title'][0]                          = "Rift Progress Top 10";
    $GLOBALS['news']['block_title'][1]                          = "Recent Kills";
    $GLOBALS['news']['news_type'][0]                            = "News";
    $GLOBALS['news']['news_type'][1]                            = "Patch Notes"; 
    $GLOBALS['news']['limit_news']                              = 10;
    $GLOBALS['news']['limit_recent']                            = 50;
    $GLOBALS['news']['limit_ranks']                             = 10;
    $GLOBALS['news']['limit_standing']                          = 20;
    $GLOBALS['news']['display_standing']                        = 4;

    $GLOBALS['news']['header_standing']['Rank']                 = "rank";
    $GLOBALS['news']['header_standing']['Guild']                = "name";
    $GLOBALS['news']['header_standing']['Server']               = "server";
    $GLOBALS['news']['header_standing']['Progress']             = "standing";

    $GLOBALS['standing']['block_title'][0]                      = "Encounter Details";
    $GLOBALS['standing']['block_title'][1]                      = "Server Strength Index";
    $GLOBALS['standing']['block_title'][2]                      = "Standing Views";
    $GLOBALS['standing']['block_subtitle'][0]                   = "Servers";
    $GLOBALS['standing']['block_subtitle'][1]                   = "Regions";
    $GLOBALS['standing']['limit_ranks']                         = 10;

    $GLOBALS['standing']['header_guild']['Rank']                = "rank";
    $GLOBALS['standing']['header_guild']['Guild']               = "name";
    $GLOBALS['standing']['header_guild']['Server']              = "server";

    $GLOBALS['standing']['header_standing']['Progress']         = "standing";
    $GLOBALS['standing']['header_standing']['HM']               = "special_encounter_2";
    $GLOBALS['standing']['header_standing']['WF']               = "world_first";
    $GLOBALS['standing']['header_standing']['RF']               = "region_first";
    $GLOBALS['standing']['header_standing']['SF']               = "server_first";
    $GLOBALS['standing']['header_standing']['Recent Activity']  = "recent";

    $GLOBALS['standing']['header_encounter']['Date']            = "datetime";
    $GLOBALS['standing']['header_encounter']['WR']              = "world_rank";
    $GLOBALS['standing']['header_encounter']['RR']              = "region_rank";
    $GLOBALS['standing']['header_encounter']['SR']              = "server_rank";
    $GLOBALS['standing']['header_encounter']['Video']           = "video";
    $GLOBALS['standing']['header_encounter']['Screenshot']      = "screenshot";

    $GLOBALS['standing']['tooltip_alltime']['Tier']             = "tier_name";
    $GLOBALS['standing']['tooltip_alltime']['Progress']         = "standing";
    $GLOBALS['standing']['tooltip_alltime']['Hard Modes']       = "special_encounter_2";
    $GLOBALS['standing']['tooltip_alltime']['WF']               = "world_first";
    $GLOBALS['standing']['tooltip_alltime']['RF']               = "region_first";
    $GLOBALS['standing']['tooltip_alltime']['SF']               = "server_first";

    $GLOBALS['standing']['tooltip_tier']['Dungeon']             = "dungeon_name";
    $GLOBALS['standing']['tooltip_tier']['Progress']            = "standing";
    $GLOBALS['standing']['tooltip_tier']['Hard Modes']          = "special_encounter_2";
    $GLOBALS['standing']['tooltip_tier']['WF']                  = "world_first";
    $GLOBALS['standing']['tooltip_tier']['RF']                  = "region_first";
    $GLOBALS['standing']['tooltip_tier']['SF']                  = "server_first";

    $GLOBALS['standing']['tooltip_dungeon']['Encounter']        = "encounter_name";
    $GLOBALS['standing']['tooltip_dungeon']['Date']             = "datetime";
    $GLOBALS['standing']['tooltip_dungeon']['WR']               = "world_rank";
    $GLOBALS['standing']['tooltip_dungeon']['RR']               = "region_rank";
    $GLOBALS['standing']['tooltip_dungeon']['SR']               = "server_rank";

    $GLOBALS['standing']['glossary']["WF"]                      = "World Firsts";
    $GLOBALS['standing']['glossary']["RF"]                      = "Region Firsts";
    $GLOBALS['standing']['glossary']["SF"]                      = "Server Firsts";
    $GLOBALS['standing']['glossary']["WR"]                      = "World Rank";
    $GLOBALS['standing']['glossary']["RR"]                      = "Region Rank";
    $GLOBALS['standing']['glossary']["SR"]                      = "Server Rank";

    $GLOBALS['standing']['block_encounter']["Encounter"]        = "encounter_name";
    $GLOBALS['standing']['block_encounter']["Dungeon"]          = "dungeon";
    $GLOBALS['standing']['block_encounter']["Tier"]             = "tier";
    $GLOBALS['standing']['block_encounter']["Raid Size"]        = "players";
    $GLOBALS['standing']['block_encounter']["# Of Kills"]       = "num_of_kills";
    $GLOBALS['standing']['block_encounter']["First Kill"]       = "first_kill_guild";
    $GLOBALS['standing']['block_encounter']["Most Recent Kill"] = "recent_kill_guild";

    $GLOBALS['ranking']['block_title'][0]                       = "What's Trending";
    $GLOBALS['ranking']['block_title'][1]                       = "Point Systems";
    $GLOBALS['ranking']['block_title'][2]                       = "Ranking Views";
    $GLOBALS['ranking']['block_subtitle'][0]                    = "Biggest Gains";
    $GLOBALS['ranking']['block_subtitle'][1]                    = "Biggest Losses";
    $GLOBALS['ranking']['block_subtitle'][2]                    = "Newest Guilds";
    $GLOBALS['ranking']['limit_unrank']                         = 10;
    $GLOBALS['ranking']['limit_rank']                           = 10;
    $GLOBALS['ranking']['limit_trend']                          = 10;
    $GLOBALS['ranking']['limit_total_trend']                    = 15;

    $GLOBALS['ranking']['header_guild']['Rank']                 = "rank";
    $GLOBALS['ranking']['header_guild']['Guild']                = "name";
    $GLOBALS['ranking']['header_guild']['Server']               = "server";
    
    $GLOBALS['ranking']['header_ranking']['Progress']           = "standing";
    $GLOBALS['ranking']['header_ranking']['WF']                 = "world_first";
    $GLOBALS['ranking']['header_ranking']['RF']                 = "region_first";
    $GLOBALS['ranking']['header_ranking']['SF']                 = "server_first";
    $GLOBALS['ranking']['header_ranking']['Points']             = "points";
    $GLOBALS['ranking']['header_ranking']['Trending']           = "trend";

    $GLOBALS['ranking']['header_dungeon_ranking']['Progress']   = "standing";
    $GLOBALS['ranking']['header_dungeon_ranking']['RF']         = "region_first";
    $GLOBALS['ranking']['header_dungeon_ranking']['WF']         = "world_first";
    $GLOBALS['ranking']['header_dungeon_ranking']['Date']       = "datetime";

    $GLOBALS['ranking']['tooltip_alltime']['Tier']              = "dungeon_name";
    $GLOBALS['ranking']['tooltip_alltime']['Progress']          = "standing";
    $GLOBALS['ranking']['tooltip_alltime']['WF']                = "world_first";
    $GLOBALS['ranking']['tooltip_alltime']['RF']                = "region_first";
    $GLOBALS['ranking']['tooltip_alltime']['SF']                = "server_first";
    $GLOBALS['ranking']['tooltip_alltime']['Points']            = "points";

    $GLOBALS['ranking']['tooltip_tier']['Dungeon']              = "dungeon_name";
    $GLOBALS['ranking']['tooltip_tier']['Progress']             = "standing";
    $GLOBALS['ranking']['tooltip_tier']['Recent Activity']      = "recent";
    $GLOBALS['ranking']['tooltip_tier']['Points']               = "points";

    $GLOBALS['ranking']['tooltip_dungeon']['Encounter']         = "encounter_name";
    $GLOBALS['ranking']['tooltip_dungeon']['Dungeon']           = "dungeon_name";
    $GLOBALS['ranking']['tooltip_dungeon']['Date']              = "datetime";
    $GLOBALS['ranking']['tooltip_dungeon']['Points']            = "points";

    $GLOBALS['ranking']['glossary']["RF"]                       = "Region Firsts";
    $GLOBALS['ranking']['glossary']["WF"]                       = "World Firsts";
    $GLOBALS['ranking']['glossary']["SF"]                       = "Server Firsts";
    $GLOBALS['ranking']['glossary']["RR"]                       = "Region Rank";
    $GLOBALS['ranking']['glossary']["WR"]                       = "World Rank";
    $GLOBALS['ranking']['glossary']["SR"]                       = "Server Rank";
    
    $GLOBALS['server_rankings']['header_stats']["Server"]           = "name";
    $GLOBALS['server_rankings']['header_stats']["Guilds"]           = "guilds";
    $GLOBALS['server_rankings']['header_stats']["Top 10 WW"]        = "top_10";
    $GLOBALS['server_rankings']['header_stats']["Top 20 WW"]        = "top_20";
    $GLOBALS['server_rankings']['header_stats']["Region Firsts"]    = "num_of_rf";
    $GLOBALS['server_rankings']['header_stats']["World Firsts"]     = "num_of_wf";
    $GLOBALS['server_rankings']['header_stats']["Total Kills"]      = "num_of_kills";

    $GLOBALS['servers']['header_guild']['Guild']                = "name";

    $GLOBALS['servers']['limit_progress']                       = 10;
    $GLOBALS['servers']['block_title'][0]                       = "Server Details";
    $GLOBALS['servers']['block_title'][1]                       = "Top {$GLOBALS['servers']['limit_progress']} Server Progression";
    $GLOBALS['servers']['block_title'][2]                       = "Tier Selection";

    $GLOBALS['servers']['block_details']['Server Name']         = "full";
    $GLOBALS['servers']['block_details']['Region']              = "region";
    $GLOBALS['servers']['block_details']['# of Guilds']         = "num_of_guilds";
    $GLOBALS['servers']['block_details']['# of Region Firsts']  = "num_of_rf";
    $GLOBALS['servers']['block_details']['# of World Firsts']   = "num_of_wf";

    $GLOBALS['servers']['tooltip_dungeon']['Encounter']         = "encounter_name";
    $GLOBALS['servers']['tooltip_dungeon']['Date']              = "datetime";
    $GLOBALS['servers']['tooltip_dungeon']['WR']                = "world_rank";
    $GLOBALS['servers']['tooltip_dungeon']['RR']                = "region_rank";
    $GLOBALS['servers']['tooltip_dungeon']['SR']                = "server_rank";

    $GLOBALS['how']['block_title'][0]                           = "Ranking Tidbits";
    $GLOBALS['how']['block_title'][1]                           = "Glossary";

    $GLOBALS['how']['block_tidbits']["Update Frequency"]        = "Every 30 Minutes";
    $GLOBALS['how']['block_tidbits']["NA Patch Time"]           = "N/A (Inconsistent)";
    $GLOBALS['how']['block_tidbits']["EU Patch Time"]           = "N/A (Inconsistent)";
    $GLOBALS['how']['block_tidbits']["Freeze Kill Counter"]     = "N/A";
    $GLOBALS['how']['block_tidbits']["Freeze Kill Date"]        = "N/A";
    $GLOBALS['how']['block_tidbits']["Base Point Value"]        = "1,000 Points";

    $GLOBALS['how']['glossary']["NbG"]                          = "Number of Guilds Evaluated";
    $GLOBALS['how']['glossary']["NbGD"]                         = "Number of Guilds Downed Encounter";
    $GLOBALS['how']['glossary']["NbT"]                          = "Time after World First Encounter Clear";
    $GLOBALS['how']['glossary']["NbHK"]                         = "Number of Most Killed Encounter";
    $GLOBALS['how']['glossary']["NKE"]                          = "Number of Kills for Specific Encounter";
    $GLOBALS['how']['glossary']["BEPV"]                         = "Base Encounter Point Value";

    $GLOBALS['guild_list']['block_title'][0]                    = "Guild Listing";
    $GLOBALS['guild_list']['block_title'][1]                    = "Update Your Guild!";

    $GLOBALS['guild_list']['list_header']['Active Guilds']      = "guild_count";
    $GLOBALS['guild_list']['list_header']['NA Guilds']          = "na_guild_count";
    $GLOBALS['guild_list']['list_header']['EU Guilds']          = "eu_guild_count";    

    $GLOBALS['guild_list']['guild_details']['Guild']            = "name";
    $GLOBALS['guild_list']['guild_details']['Server']           = "server";
    $GLOBALS['guild_list']['guild_details']['Type']             = "guild_type";  
    $GLOBALS['guild_list']['guild_details']['Raid Schedule']    = "schedule";
    $GLOBALS['guild_list']['guild_details']['Website']          = "website";

    $GLOBALS['guild']['guild_details']['Date Created']          = "date_created";
    $GLOBALS['guild']['guild_details']['Server']                = "server";
    $GLOBALS['guild']['guild_details']['Country']               = "country";
    $GLOBALS['guild']['guild_details']['Faction']               = "faction";
    $GLOBALS['guild']['guild_details']['Guild Leader']          = "leader";
    $GLOBALS['guild']['guild_details']['Website']               = "website";
    $GLOBALS['guild']['guild_details']['Facebook']              = "facebook";
    $GLOBALS['guild']['guild_details']['Twitter']               = "twitter";
    $GLOBALS['guild']['guild_details']['Google+']               = "google";
    $GLOBALS['guild']['guild_details']['Active']                = "active";

    $GLOBALS['guild']['navigation']['Profile']                  = "profile";
    //$GLOBALS['guild']['navigation']['Rankings']                 = "rankings";
    //$GLOBALS['guild']['navigation']['Recent Activity']          = "recent";
    $GLOBALS['guild']['navigation']['Progression']              = "progression";
    $GLOBALS['guild']['navigation']['Activity Timeline']        = "timeline";
    $GLOBALS['guild']['navigation']['Widget']                   = "widget";
    //$GLOBALS['guild']['navigation']['Twitch Channels']          = "streamers";

    $GLOBALS['guild']['header_progression']['Date Completed']   = "datetime";
    $GLOBALS['guild']['header_progression']['WR']               = "world_rank";
    $GLOBALS['guild']['header_progression']['RR']               = "region_rank";
    $GLOBALS['guild']['header_progression']['SR']               = "server_rank";
    $GLOBALS['guild']['header_progression']['QP']               = "0";
    $GLOBALS['guild']['header_progression']['AP']               = "0";
    $GLOBALS['guild']['header_progression']['APF']              = "0";
    $GLOBALS['guild']['header_progression']['Video']       = "video";
    $GLOBALS['guild']['header_progression']['Screenshot']       = "screenshot";

    $GLOBALS['guild']['header_history']['Encounter']            = "encounter_name";
    $GLOBALS['guild']['header_history']['Dungeon']              = "dungeon_name";
    $GLOBALS['guild']['header_history']['Tier']                 = "tier";
    $GLOBALS['guild']['header_history']['Date Completed']       = "datetime";
    $GLOBALS['guild']['header_history']['Span']                 = "span";
    $GLOBALS['guild']['header_history']['World Rank']           = "world_rank";

    $GLOBALS['guild']['block_title'][0]                         = "Guild Activity";
    $GLOBALS['guild']['block_title'][1]                         = "Ranking Details";
    $GLOBALS['guild']['block_title'][2]                         = "Progression Breakdown";
    $GLOBALS['guild']['limit_activity']                         = 15;

    $GLOBALS['user']['guild_details']['Date Created']           = "date_created";
    $GLOBALS['user']['guild_details']['Server']                 = "server";
    $GLOBALS['user']['guild_details']['Country']                = "country";
    $GLOBALS['user']['guild_details']['Faction']                = "faction";

    $GLOBALS['user']['account_details']['Username']             = "username";
    $GLOBALS['user']['account_details']['Email']                = "email";
    $GLOBALS['user']['account_details']['Date Created']         = "date_joined";

    $GLOBALS['user']['header_progression']['Date Completed']    = "datetime";
    $GLOBALS['user']['header_progression']['WR']                = "world_rank";
    $GLOBALS['user']['header_progression']['RR']                = "region_rank";
    $GLOBALS['user']['header_progression']['SR']                = "server_rank";
    $GLOBALS['user']['header_progression']['Video']        = "video";
    $GLOBALS['user']['header_progression']['Screenshot']        = "screenshot";

    $GLOBALS['user']['message']['new_guild']                    = "To create a new guild, click on the 'Create New Guild' button to begin the process!";
    $GLOBALS['user']['message']['enroll_default']               = "Your guild is currently not enrolled in our guild raid progression system. Enrolling will allow your guild to be tracked and ranked along side fellow guilds.";
    $GLOBALS['user']['message']['enroll_success']               = "Congratulations! Your guild has successfully been enrolled into our raid progression tracker! Use the 'Submit Kills' button to start listing your kills!";
    $GLOBALS['user']['message']['disband_confirm']              = "Are you sure you want to disband your guild?";

    $GLOBALS['user']['block_title'][0]                          = "Account Information";
    $GLOBALS['user']['block_title'][1]                          = "Guild Management";

    $GLOBALS['contact']['type'][0]                              = "General Feedback";
    $GLOBALS['contact']['type'][1]                              = "Guild Details Request";
    $GLOBALS['contact']['type'][2]                              = "UI Layout";
    $GLOBALS['contact']['type'][3]                              = "Account Problem";
    $GLOBALS['contact']['type'][4]                              = "Submission Errors";
    $GLOBALS['contact']['type'][5]                              = "Feature Request";
    $GLOBALS['contact']['type'][6]                              = "Specific Problems";   
?>