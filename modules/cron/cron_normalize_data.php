<?php
    include_once    "properties.php";

    log_entry(0, "Starting data normalize process...");

    $guild_array = $GLOBALS['global_guild_array'];

    // NEW FORMAT
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

    // OLD FORMAT
    // 0 - Encounter ID
    // 1 - Dungeon Name
    // 2 - Dungeon ID
    // 3 - Tier
    // 4 - Raid Size
    // 5 - Time
    // 6 - Date
    // 7 - Image Path
    // 8 - Video Link
    // 9 - Server Rank
    // 10 - Region Rank
    // 11 - World Rank
    // 12 - Server

    log_entry(0, "Creating new normalized data per guild.");
    foreach( $guild_array as $guild_id => $guild_details ) {
        $new_progression_string = "";
        $encounter_array        = explode("~~", $guild_details['progression']);
        echo "/******{$guild_details['name']}*****/<br>";
        for ( $count = 0; $count < count($encounter_array); $count++ ) {
            $encounter_details      = explode("||", $encounter_array[$count]);
            $new_encounter_details  = array();

            if ( count($encounter_details) == 10 ) { echo "NEW<br>";}
            if ( count($encounter_details) > 10 ) { 
                echo "OLD<br>";

                $new_encounter_details[0]   = $encounter_details[0];
                $new_encounter_details[1]   = $encounter_details[6];
                $new_encounter_details[2]   = $encounter_details[7];
                $new_encounter_details[3]   = "";
                $new_encounter_details[4]   = $encounter_details[8];
                $new_encounter_details[5]   = $encounter_details[9];
                $new_encounter_details[6]   = $encounter_details[10];
                $new_encounter_details[7]   = $encounter_details[11];
                $new_encounter_details[8]   = $encounter_details[12];

                $progression_string = implode("||", $new_encounter_details);

                if ( isset($new_progression_string) && strlen($new_progression_string) > 0 ) {
                    $new_progression_string = $new_progression_string."~~".$progression_string;
                } else {
                    $new_progression_string = $progression_string;
                }
            }
        }

        if ( strlen($new_progression_string) > 0 ) {
            $query = mysql_query(sprintf(
                "UPDATE %s
                 SET progression ='%s'
                 WHERE guild_id='%s'",
                 mysql_real_escape_string($GLOBALS['table_guild']),
                 mysql_real_escape_string($new_progression_string),
                 mysql_real_escape_string($guild_details['guild_id'])
                 )) or die(mysql_error());
            echo "New String: $new_progression_string<br>";
        }
    }

    log_entry(0, "Data normalization completed!");
?>