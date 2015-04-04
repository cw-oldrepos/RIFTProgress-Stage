<?php
    // FORMAT
    // input            -> <input or <select
    // type             -> type='text' or type='hidden' etc
    // field_name       -> name='guild_id' or name='country' etc
    // default index    -> When placing pre-selected/entered values. Pulling from an array
    // class            -> class='select_short' etc

    //*****Guild Create*****//
    $GLOBALS['forms']['guild_create']['title']                  = "Create New Guild";
    $GLOBALS['forms']['guild_create']['submit']                 = "Create Guild";
    $GLOBALS['forms']['guild_create']['fields']['Guild Name']   = "input|text|name|name";
    $GLOBALS['forms']['guild_create']['fields']['Faction']      = "select|faction|faction|faction|select_short";
    $GLOBALS['forms']['guild_create']['fields']['Server']       = "select|server|server|server|select_short";
    $GLOBALS['forms']['guild_create']['fields']['Country']      = "select|country|country|country|select_short";
    $GLOBALS['forms']['guild_create']['fields']['Guild Leader'] = "input|text|leader|leader";
    $GLOBALS['forms']['guild_create']['fields']['Website']      = "input|text|website|website";
    $GLOBALS['forms']['guild_create']['fields']['Facebook']     = "input|text|facebook|facebook";
    $GLOBALS['forms']['guild_create']['fields']['Twitter']      = "input|text|twitter|twitter";
    $GLOBALS['forms']['guild_create']['fields']['Google+']      = "input|text|google|google";
    $GLOBALS['forms']['guild_create']['fields']['Guild Logo']   = "input|file|file|file";

    //*****Raid Team Create*****//
    $GLOBALS['forms']['raid_team_add']['title']                     = "Add Raid Team";
    $GLOBALS['forms']['raid_team_add']['submit']                    = "Add Team";
    $GLOBALS['forms']['raid_team_add']['fields']['Guild Name']      = "noinput|text|name|guild_id";
    $GLOBALS['forms']['raid_team_add']['fields']['Team Name']       = "input|text|team|team";
    $GLOBALS['forms']['raid_team_add']['fields']['Faction']         = "select|faction|faction|faction|select_short";
    $GLOBALS['forms']['raid_team_add']['fields']['Server']          = "select|server|server|server|select_short";
    $GLOBALS['forms']['raid_team_add']['fields']['Country']         = "select|country|country|country|select_short";
    $GLOBALS['forms']['raid_team_add']['fields']['Guild Leader']    = "input|text|leader|leader";

    //*****Guild Edit*****//
    $GLOBALS['forms']['guild_edit']['title']                    = "Edit Guild Details";
    $GLOBALS['forms']['guild_edit']['submit']                   = "Save Details";
    $GLOBALS['forms']['guild_edit']['fields']['Guild Name']     = "noinput|text|name|guild_id";
    $GLOBALS['forms']['guild_edit']['fields']['Faction']        = "select|faction|faction|faction|select_short";
    $GLOBALS['forms']['guild_edit']['fields']['Server']         = "select|server|server|server|select_short";
    $GLOBALS['forms']['guild_edit']['fields']['Country']        = "select|country|country|country|select_short";
    $GLOBALS['forms']['guild_edit']['fields']['Guild Leader']   = "input|text|leader|leader";
    $GLOBALS['forms']['guild_edit']['fields']['Website']        = "input|text|website|website";
    $GLOBALS['forms']['guild_edit']['fields']['Facebook']       = "input|text|facebook|facebook";
    $GLOBALS['forms']['guild_edit']['fields']['Twitter']        = "input|text|twitter|twitter";
    $GLOBALS['forms']['guild_edit']['fields']['Google+']        = "input|text|google|google";
    $GLOBALS['forms']['guild_edit']['fields']['Users']          = "textarea|text|creator_id|creator_id";
    $GLOBALS['forms']['guild_edit']['fields']['Guild Logo']     = "input|file|file|file";
    $GLOBALS['forms']['guild_edit']['fields']['Active']         = "select|active|active|active|select_short";

    //*****Login*****//
    $GLOBALS['forms']['login']['title']                 = "Login";
    $GLOBALS['forms']['login']['submit']                = "Submit";
    $GLOBALS['forms']['login']['fields']['Username']    = "input|text|login_username|username";
    $GLOBALS['forms']['login']['fields']['Password']    = "input|password|login_passcode";

    //*****Register*****//
    $GLOBALS['forms']['register']['title']                      = "Registration";
    $GLOBALS['forms']['register']['submit']                     = "Submit";
    $GLOBALS['forms']['register']['fields']['Username']         = "input|text|register_username|register_username";
    $GLOBALS['forms']['register']['fields']['Email']            = "input|text|register_email|register_email";
    $GLOBALS['forms']['register']['fields']['Password']         = "input|password|register_passcode1";
    $GLOBALS['forms']['register']['fields']['Retype Password']  = "input|password|register_passcode2";
    $GLOBALS['forms']['register']['fields']['Guild Name']       = "input|text|name|name";
    $GLOBALS['forms']['register']['fields']['Faction']          = "select|faction|faction|faction|select_short";
    $GLOBALS['forms']['register']['fields']['Server']           = "select|server|server|server|select_short";
    $GLOBALS['forms']['register']['fields']['Country']          = "select|country|country|country|select_short";
    $GLOBALS['forms']['register']['fields']['Guild Leader']     = "input|text|leader|leader";
    $GLOBALS['forms']['register']['fields']['Website']          = "input|text|website|website";
    $GLOBALS['forms']['register']['fields']['Facebook']         = "input|text|facebook|facebook";
    $GLOBALS['forms']['register']['fields']['Twitter']          = "input|text|twitter|twitter";
    $GLOBALS['forms']['register']['fields']['Google+']          = "input|text|google|google";
    $GLOBALS['forms']['register']['fields']['Guild Logo']       = "input|file|file|file";
    $GLOBALS['forms']['register']['fields']['Important Stuff']  = "input|checkbox|agree";

    //*****User Edit*****//
    $GLOBALS['forms']['user_edit']['title']                             = "Edit Account Details";
    $GLOBALS['forms']['user_edit']['submit']                            = "Save Details";
    $GLOBALS['forms']['user_edit']['fields']['Username']                = "noinput|noinput|username|username";
    $GLOBALS['forms']['user_edit']['fields']['Email']                   = "input|text|user_edit_email|email";
    $GLOBALS['forms']['user_edit']['fields']['New Password']            = "input|password|user_edit_pass1";
    $GLOBALS['forms']['user_edit']['fields']['Confirm New Password']    = "input|password|user_edit_pass2";

    //*****Encounter Submit*****//
    $GLOBALS['forms']['encounter_add']['title']                         = "Encounter Kill Submission";
    $GLOBALS['forms']['encounter_add']['submit']                        = "Submit Encounter";
    $GLOBALS['forms']['encounter_add']['fields']['Guild Name']          = "noinput|text|name|guild_id";
    $GLOBALS['forms']['encounter_add']['fields']['Encounter']           = "select|encounter|encounter_id|select_short";
    $GLOBALS['forms']['encounter_add']['fields']['Date (M-D-Y)'][0]     = "group,3";
    $GLOBALS['forms']['encounter_add']['fields']['Date (M-D-Y)'][1]     = "select|month|time_month| |select_short";
    $GLOBALS['forms']['encounter_add']['fields']['Date (M-D-Y)'][2]     = "select|day|time_day| |select_short";
    $GLOBALS['forms']['encounter_add']['fields']['Date (M-D-Y)'][3]     = "select|year|time_year| |select_short";
    $GLOBALS['forms']['encounter_add']['fields']['Time (H:M UTC)'][0]   = "group,3";
    $GLOBALS['forms']['encounter_add']['fields']['Time (H:M UTC)'][1]   = "select|hour|time_hour| |select_short";
    $GLOBALS['forms']['encounter_add']['fields']['Time (H:M UTC)'][2]   = "select|minute|time_minute| |select_short";
    $GLOBALS['forms']['encounter_add']['fields']['Time (H:M UTC)'][3]   = "select|timezone|time_zone| |select_short";
    $GLOBALS['forms']['encounter_add']['fields']['Video Link']          = "input|text|video|video";
    $GLOBALS['forms']['encounter_add']['fields']['Screenshot']          = "input|file|file|file";

    //*****Encounter Popup Submit*****//
    $GLOBALS['forms']['encounter_popup_add']['title']                         = "Encounter Kill Submission";
    $GLOBALS['forms']['encounter_popup_add']['submit']                        = "Submit Encounter";
    $GLOBALS['forms']['encounter_popup_add']['fields']['Guild Name']          = "select|guild|guild_id| |select_short";
    $GLOBALS['forms']['encounter_popup_add']['fields']['Encounter']           = "select|encounter|encounter_id|select_short";
    $GLOBALS['forms']['encounter_popup_add']['fields']['Date (M-D-Y)'][0]     = "group,3";
    $GLOBALS['forms']['encounter_popup_add']['fields']['Date (M-D-Y)'][1]     = "select|month|time_month| |select_short";
    $GLOBALS['forms']['encounter_popup_add']['fields']['Date (M-D-Y)'][2]     = "select|day|time_day| |select_short";
    $GLOBALS['forms']['encounter_popup_add']['fields']['Date (M-D-Y)'][3]     = "select|year|time_year| |select_short";
    $GLOBALS['forms']['encounter_popup_add']['fields']['Time (H:M UTC)'][0]   = "group,3";
    $GLOBALS['forms']['encounter_popup_add']['fields']['Time (H:M UTC)'][1]   = "select|hour|time_hour| |select_short";
    $GLOBALS['forms']['encounter_popup_add']['fields']['Time (H:M UTC)'][2]   = "select|minute|time_minute| |select_short";
    $GLOBALS['forms']['encounter_popup_add']['fields']['Time (H:M UTC)'][3]   = "select|timezone|time_zone| |select_short";
    $GLOBALS['forms']['encounter_popup_add']['fields']['Video Link']          = "input|text|video|video";
    $GLOBALS['forms']['encounter_popup_add']['fields']['Screenshot']          = "input|file|file|file";

    //*****Encounter Edit*****//
    $GLOBALS['forms']['encounter_edit']['title']                         = "Edit Encounter Details";
    $GLOBALS['forms']['encounter_edit']['submit']                        = "Edit Encounter";
    $GLOBALS['forms']['encounter_edit']['fields']['Guild Name']          = "noinput|text|name|guild_id";
    $GLOBALS['forms']['encounter_edit']['fields']['Encounter']           = "noinput|text|encounter_name|encounter_id";
    $GLOBALS['forms']['encounter_edit']['fields']['Date (M-D-Y)'][0]     = "group,3";
    $GLOBALS['forms']['encounter_edit']['fields']['Date (M-D-Y)'][1]     = "select|month|time_month|month|select_short";
    $GLOBALS['forms']['encounter_edit']['fields']['Date (M-D-Y)'][2]     = "select|day|time_day|day|select_short";
    $GLOBALS['forms']['encounter_edit']['fields']['Date (M-D-Y)'][3]     = "select|year|time_year|year|select_short";
    $GLOBALS['forms']['encounter_edit']['fields']['Time (H:M UTC)'][0]   = "group,3";
    $GLOBALS['forms']['encounter_edit']['fields']['Time (H:M UTC)'][1]   = "select|hour|time_hour|hour|select_short";
    $GLOBALS['forms']['encounter_edit']['fields']['Time (H:M UTC)'][2]   = "select|minute|time_minute|minute|select_short";
    $GLOBALS['forms']['encounter_edit']['fields']['Time (H:M UTC)'][3]   = "select|timezone|time_zone|timezone|select_short";
    $GLOBALS['forms']['encounter_edit']['fields']['Video Link']          = "input|text|video|video";
    $GLOBALS['forms']['encounter_edit']['fields']['Screenshot']          = "input|file|file|file";

    //*****Contact Us*****//
    $GLOBALS['forms']['contact']['title']               = "Contact Us";
    $GLOBALS['forms']['contact']['submit']              = "Submit";
    $GLOBALS['forms']['contact']['fields']['Email']     = "input|text|email|email";
    $GLOBALS['forms']['contact']['fields']['Feedback']  = "select|contact|contact|contact";
    $GLOBALS['forms']['contact']['fields']['Message']   = "textarea|text|message|message";

    //*****Forgot Password*****//
    $GLOBALS['forms']['forgot']['title']            = "Forgot Password?";
    $GLOBALS['forms']['forgot']['submit']           = "Retrieve Reset Code";
    $GLOBALS['forms']['forgot']['fields']['Email']  = "input|text|email|email|input_long";

    $GLOBALS['forms']['reset']['title']                     = "Reset Password";
    $GLOBALS['forms']['reset']['submit']                    = "Reset Password";
    $GLOBALS['forms']['reset']['fields']['User ID']         = "noinput|text|user_id|user_id";
    $GLOBALS['forms']['reset']['fields']['Confirmation']    = "noinput|text|confirmcode|confirmcode";
    $GLOBALS['forms']['reset']['fields']['Password']        = "input|password|reset_passcode1";
    $GLOBALS['forms']['reset']['fields']['Retype Password'] = "input|password|reset_passcode2";

    //*****Submit New Guild Details*****//
    $GLOBALS['forms']['update_guild']['title']                          = "Update Guild Details";
    $GLOBALS['forms']['update_guild']['submit']                         = "Submit";
    $GLOBALS['forms']['update_guild']['fields']['Guild Name']           = "select|guild|guild_id| |select_short";
    $GLOBALS['forms']['update_guild']['fields']['Guild Type']           = "select|guild_type|guild_type|guild_type|select_short";
    $GLOBALS['forms']['update_guild']['fields']['Raid Schedule']        = "input|text|schedule|schedule";
    $GLOBALS['forms']['update_guild']['fields']['Website']              = "input|text|website|website";
?>