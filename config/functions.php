<?php
    $GLOBALS['global_days_array']           = get_day_array();
    $GLOBALS['global_months_array']         = get_month_array();
    $GLOBALS['global_years_array']          = get_year_array();
    $GLOBALS['global_hours_array']          = get_hour_array();
    $GLOBALS['global_minutes_array']        = get_minute_array();
    $GLOBALS['global_timezones_array']      = get_timezone_array();
    $GLOBALS['global_point_system_array']   = $GLOBALS['point_system'];

    /******HYPERLINKS*****/
    function generate_hyperlink_guild($text, $server, $target, $class, $text_limit) {
        $hyperlink  = "";
        $guild      = clean_link_guild($text);
        $server     = clean_link($server);
        $url        = "{$GLOBALS['page_guild']}{$guild}-_-{$server}";

        return generate_hyperlink($url, $text, $class, $target, $text_limit);
    }

    function generate_hyperlink_server($text, $view, $target, $class, $text_limit) {
        $hyperlink  = "";
        $server     = clean_link($text);
        $url        = "{$GLOBALS['page_servers']}{$server}";

        return generate_hyperlink($url, $text, $class, $target, $text_limit);
    }

    function generate_hyperlink_standing($text, $type, $view, $details, $sub_details, $target, $class, $text_limit) {
        $url        = "{$GLOBALS['page_standing']}{$view}";
        $hyperlink  = "";
        $tier       = "";
        $raid_size  = "";
        $dungeon    = "";
        $encounter  = "";

        if ( $type == "alltime-size" ) {
            $raid_size = $details;
        }

        if ( $type == "tier" ) {
            $tier = clean_link($details['title']);
        } 

        if ( $type == "tier-size" ) {
            $tier       = clean_link($details['title']);
            $raid_size  = $sub_details;
        }

        if ( $type == "dungeon" ) {
            $tier       = clean_link($sub_details['title']);
            $dungeon    = clean_link($details['name']); 
        }

        if ( $type == "encounter" ) {
            $tier       = clean_link($GLOBALS['global_tier_array'][$sub_details['tier']]['title']);
            $dungeon    = clean_link($sub_details['name']);
            $encounter  = clean_link($details['encounter_name']);  
        }

        if ( $type == "alltime" )       { $url .= "/alltime"; }
        if ( $type == "alltime-size" )  { $url .= "/alltime/{$raid_size}"; }
        if ( $type == "tier" )          { $url .= "/{$tier}"; }
        if ( $type == "tier-size" )     { $url .= "/{$tier}/{$raid_size}"; }
        if ( $type == "dungeon" )       { $url .= "/{$tier}/{$dungeon}"; }
        if ( $type == "encounter" )     { $url .= "/{$tier}/{$dungeon}/{$encounter}"; }

        return generate_hyperlink($url, $text, $class, $target, $text_limit);    
    }

    function generate_hyperlink_ranking($text, $type, $point_system, $view, $details, $sub_details, $target, $class, $text_limit) {
        $url        = "{$GLOBALS['page_ranking']}{$point_system}/{$view}";
        $hyperlink  = "";
        $tier       = "";
        $raid_size  = "";
        $dungeon    = "";

        if ( $type == "alltime-size" ) {
            $raid_size = $details;
        }

        if ( $type == "tier" ) {
            $tier = clean_link($details['title']);
        } 

        if ( $type == "tier-size" ) {
            $tier       = clean_link($details['title']);
            $raid_size  = $sub_details;
        }

        if ( $type == "dungeon" ) {
            $tier       = clean_link($sub_details['title']);
            $dungeon    = clean_link($details['name']); 
        }

        if ( $type == "alltime" )       { $url .= "/alltime"; }
        if ( $type == "alltime-size" )  { $url .= "/alltime/{$raid_size}"; }
        if ( $type == "tier" )          { $url .= "/{$tier}"; }
        if ( $type == "tier-size" )     { $url .= "/{$tier}/{$raid_size}"; }
        if ( $type == "dungeon" )       { $url .= "/{$tier}/{$dungeon}"; }        

        return generate_hyperlink($url, $text, $class, $target, $text_limit);
    }

    function generate_hyperlink($url, $text, $class, $target, $text_limit) {
        $class = strtolower($class);
        $text  = short_name($text, $text_limit);

        if ( $target == 0 ) { $target = "_self"; }
        if ( $target == 1 ) { $target = "_blank"; }

        if ( $url != "" ) {
            $hyperlink = "<a class='$class' href={$url} target='$target'>$text</a>";
        } else {
            $hyperlink = "<a class='$class' target='$target'>$text</a>";
        }

        return $hyperlink;
    }
    
    function generate_external_hyperlink($url, $text, $target, $class) {
        $hyperlink = "";
        $valid_url = parse_url($url);

        if ( !isset($valid_url['scheme']) ) $url = "http://{$url}";

        if ( $url != "" ) {
            if ( $target == 0 ) {
                $hyperlink = "<a class='$class' href='$url' target='_self'>$text</a>";
            } else if ( $target == 1 ) {
                $hyperlink = "<a class='$class' href='$url' target='_blank'>$text</a>";
            }
        } else {
            $hyperlink = "<a class='$class'>$text</a>";
        }

        return $hyperlink;
    }

    /******DATE/TIME ARRAYS*****/
    function get_minute_array() {
        $array = array();

        array_push($array, "00");

        for ( $minute = 1; $minute < 60; $minute++ ) {
            if ( strlen($minute) == 1 ) $minute = "0{$minute}";
            $array[$minute] = $minute;
        }   

        return $array;
    }

    function get_hour_array() {
        $array = array();

        array_push($array, "00");

        for ( $hour = 1; $hour < 24; $hour++ ) {
            if ( strlen($hour) == 1 ) $hour = "0{$hour}";
            $array[$hour] = $hour;
        }

        return $array;
    }

    function get_month_array() {
        $array = array();

        for ( $month = 1; $month < 13; $month++ ) {
            if ( strlen($month) == 1 ) $month = "0{$month}";
            $array[$month] = $month;
        }

        return $array;          
    }

    function get_day_array() {
        $array = array();

        for ( $day = 1; $day < 33; $day++ ) {
            if ( strlen($day) == 1 ) $day = "0{$day}";
            $array[$day] = $day;
        }

        return $array;      
    }

    function get_year_array() {
        $array          = array();
        $current_year   = date("Y");

        for ( $year = $GLOBALS['release_year']; $year <= $current_year; $year++ ) {
            array_push($array, $year);
            $array[$year] = $year;
        }

        return $array;
    }

    function get_timezone_array() {
        $array          = array();
        //$timestamp      = time();

        /*
        foreach(timezone_identifiers_list() as $key => $zone) {
            date_default_timezone_set($zone);

            $timezone = 'UTC '.date('P', $timestamp)." ".date('T');
            $array[$timezone] = $timezone;
        }

        ksort($array);

        date_default_timezone_set($GLOBALS['default_time_zone']);
        */

        //$array['UTC -08:00 SST'] = "Server Time";
        $array['Server Time'] = "Server Time";
        return $array;
    }
    
    /*****GENERATE IMAGES*****/
    function get_header_background_dungeon($dungeon, $url) {
        $dungeon    = strtolower(str_replace(" ", "_", $dungeon));
        $dungeon    = strtolower(str_replace(":", "", $dungeon));
        $image      = "{$GLOBALS['fold_images']}header/header_bg_$dungeon.gif";
        $output     = "<div class='header_dungeon' style='background-image:url('$image');'>$url</div>";

        return $output;
    }

    function get_image_flag($name) {
        $image  = "";
        $name   = strtolower(str_replace(" ","_",$name));
        $image  = "<span class='img_helper'></span><img src='{$GLOBALS['fold_flags']}$name.png' alt='$name' class='img_class'>";

        return $image;
    }

    function get_large_image_flag($name) {
        $image  = "";
        $name   = strtolower(str_replace(" ","_",$name));
        $image  = "<span class='large_img_helper'></span><img src='{$GLOBALS['fold_flags']}large/$name.png' alt='$name' class='large_img_class'>";

        return $image;
    }

    function get_guild_logo($guild_id) {
        $image      = "";
        $path       = "{$GLOBALS['fold_guild_logos']}logo-$guild_id";
        $image      = "<span class='logo_helper'></span><img src='$path' alt='$guild_id' class='logo_class'>";

        return $image; 
    }

    function get_faction_logo($faction) {
        $faction    = strtolower($faction);
        $image      = "";
        $image      = "<span class='faction_helper'></span>{$GLOBALS['images'][$faction]}";

        return $image; 
    }

    function get_rank_medal($rank) {
        if ( $rank == 1 ) { 
            $rank = $GLOBALS['images']['medal_gold'];
        } else if ( $rank == 2 ) {
            $rank = $GLOBALS['images']['medal_silver'];
        } else if ( $rank == 3 ) {
            $rank = $GLOBALS['images']['medal_bronze'];
        }

        return $rank;
    }

    function get_trend_image($trend) {
        $image = "";

        if ( ($trend != "--" || $trend != "NEW") && $trend  > 0 ) $image = $GLOBALS['images']['trend_up'];
        if ( ($trend != "--" || $trend != "NEW") && $trend  < 0 ) $image = $GLOBALS['images']['trend_down'];

        return $image;
    }

    function get_trend_image_large($trend) {
        $image = "";

        if ( ($trend != "--" || $trend != "NEW") && $trend  > 0 ) $image = $GLOBALS['images']['trend_up_large'];
        if ( ($trend != "--" || $trend != "NEW") && $trend  < 0 ) $image = $GLOBALS['images']['trend_down_large'];

        return $image;
    }

    function draw_message_banner($header, $content) {
        echo "<div class='banner'>";
            echo "<div class='banner_header'>$header</div>";
            echo "<div class='banner_content'>$content</div>";
        echo "</div>";
        echo "<div class='clear'></div>";
        echo "<div class='horizontal_separator'></div>";
    }

    function draw_message_banner_region_server($region_details, $server_details, $style) {
        $header_text    = "";
        $tab_name       = "";
        $image_path     = "";

        if ( isset($region_details) && $region_details != "" ) {
            $header_text    = "{$region_details['style']} $style";
            $tab_name       = strtolower($region_details['abbreviation']);
            $image_path     = "{$GLOBALS['fold_images']}site/regions/banner_region_$tab_name.gif";
        } 

        if ( isset($server_details) && $server_details != "" ) {
            $header_text                = "{$server_details['name']} $style";
            $tab_name                   = strtolower($server_details['name']);
            $server_details['region']   = strtolower($server_details['region']);
            $image_path                 = "{$GLOBALS['fold_images']}site/regions/banner_region_{$server_details['region']}.gif";
        }

        if ( $server_details == "" && $region_details == "" ){
            $header_text    = "World $style";
            $tab_name       = "World";
            $image_path     = "{$GLOBALS['fold_images']}site/regions/banner_region_world.gif";
        }

        $tab_name = strtolower(str_replace(" ", "_", $tab_name));

        echo "<div class='banner_region_server standing_shard' style=\"background-image:url('$image_path');\" name='shard_name' id='$tab_name'>";
            echo "<div class='banner_view_type_text'><h2>$header_text</h2></div>";
        echo "</div>";
    }

    function draw_message_banner_progress($tier_details, $type, $style, $dungeon_details, $mob_details) {
        $header_text = $image_path = $tab_name = "";

        if ( isset($mob_details) && count($mob_details) > 0 && $mob_details != "" ) {
            $header_text    = "{$mob_details['encounter_name']} $style";
            $banner_name    = str_replace(" ", "_",  strtolower($dungeon_details['name']));
            $banner_name    = str_replace(":", "",  strtolower($banner_name));
            $banner_name    = str_replace("'", "",  strtolower($banner_name));
            $image_path     = "{$GLOBALS['fold_images']}site/dungeons/banner_$banner_name.png";
        } else if ( isset($dungeon_details) && count($dungeon_details) > 0 && $dungeon_details != "" ) {
            $header_text    = "{$dungeon_details['name']} $style"; 
            $banner_name    = str_replace(" ", "_",  strtolower($dungeon_details['name']));
            $banner_name    = str_replace(":", "",  strtolower($banner_name));
            $banner_name    = str_replace("'", "",  strtolower($banner_name));
            $image_path     = "{$GLOBALS['fold_images']}site/dungeons/banner_$banner_name.png";
        } else if ( isset($tier_details) && $tier_details == "alltime" ) {
            $header_text    = "All-Time $style";
            $image_path     = "{$GLOBALS['fold_images']}site/tiers/banner_tier_default.png";
            $tab_name       = "alltime";
        } else {
            $header_text    = "{$tier_details['title']} $style";
            $image_path     = "{$GLOBALS['fold_images']}site/tiers/banner_tier_{$tier_details['tier']}.png";
            $tab_name       = $tier_details['tier'];
        } 

        echo "<div class='banner_progress tier' style=\"background-image:url('$image_path');\" id='$tab_name'>";
            echo "<div class='banner_progress_text'><h1>$header_text</h1></div>";
        echo "</div>";
    }

    function draw_message_banner_dungeons($dungeon_details) {
        $header_text    = "";
        $tab_name       = "";
        $image_path     = "";


        if ( isset($dungeon_details) ) {
            $header_text    = $dungeon_details['name'];
            $tab_name       = $dungeon_details['name'];
            $tab_name       = strtolower(str_replace(" ", "_", $tab_name));
            $tab_name       = strtolower(str_replace(":", "", $tab_name));
            $tab_name       = strtolower(str_replace("'", "", $tab_name));
            $image_path     = "{$GLOBALS['fold_images']}site/dungeons/banner_small_$tab_name.png";
        } 

        echo "<div class='banner_region_server dungeon' style=\"background-image:url('$image_path');\" name='shard_name' id='$tab_name'>";
            echo "<div class='banner_dungeon_text'>$header_text</div>";
        echo "</div>";
    }

    function get_faction_background($faction) {
        $image      = "";
        $faction    = strtolower($faction);
        $path       = "{$GLOBALS['fold_guild_logos']}tmp/{$faction}_default.png";

        return $path; 
    }

    /*****GENERATE FORMS*****/
    function generate_table_form($form_type, $action, $default_values) {
        $fields_array   = $GLOBALS['forms'][$form_type]['fields'];
        $title          = $GLOBALS['forms'][$form_type]['title'];
        $submit_value   = $GLOBALS['forms'][$form_type]['submit'];
        $submit         = "form_{$form_type}_submit";

        echo "<form action='$action' method='POST' enctype='multipart/form-data'>";
            echo "<table class='table_data form'>";
                echo "<thead>";
                    echo "<tr>";
                        echo "<th colspan='2'>$title</th>";
                    echo "</tr>";
                echo "</thead>";
                echo "<tbody>";
                    foreach ( $fields_array as $field => $input ) {
                        $input = generate_field($input, $default_values);

                        echo "<tr>";
                            echo "<th>$field</th>";
                            echo "<td>$input</td>";
                        echo "</tr>";                   
                    }

                    /*
                    if ( $form_type == 'encounter_add' ) {
                        echo "<tr>";
                            echo "<th>Misc</th>";
                            echo "<td><a href='http://whatismytimezone.com/' target='_blank'>What is My Timezone?</a></td>";
                        echo "</tr>";
                    }
                    */

                    if ( $form_type == 'guild_edit' ) {
                        echo "<tr>";
                            echo "<th>Notice</th>";
                            echo "<td>To add more users to your guild, simply place their email addresses separated by a , (No spaces)</td>";
                        echo "</tr>";
                    }
                echo "</tbody>";
                echo "<tfoot>";
                    echo "<tr>";
                        echo "<td colspan='2'><input name='$submit' type='submit' value='$submit_value' /></td>";
                    echo "</tr>"; 
                echo "</tfoot>";
            echo "</table>";
        echo "</form>";
    }

    function generate_popup_form($form_type, $action, $default_values) {
        $fields_array   = $GLOBALS['forms'][$form_type]['fields'];
        $title          = $GLOBALS['forms'][$form_type]['title'];
        $submit_value   = $GLOBALS['forms'][$form_type]['submit'];
        $submit         = "form_{$form_type}_submit";

        echo "<form action='$action' method='POST' enctype='multipart/form-data'>";
            foreach ( $fields_array as $field => $input ) {
                $input = generate_field($input, $default_values);

                echo "<div class='popup_form_group'>";
                    echo "$field<br>$input";
                echo "</div>";                   
            }

            echo "<div class='popup_form_controls'>";
                echo "<input name='$submit' type='submit' value='$submit_value'>";

                if ( $form_type == 'login' ) {
                    echo "<br><a href='{$GLOBALS['page_forgot']}' class='medium_text'>Password Recovery</a>";
                }

                /*
                if ( $form_type == 'encounter_popup_add' ) {
                    echo "<br><a href='http://whatismytimezone.com/' class='medium_text' target='_blank'>What is My Timezone?</a>";
                }
                */
            echo "</div>";
        echo "</form>";

        echo "<a id='{$form_type}_popup_close' href='#'>Close</a>";
    }

    function generate_field($field, $default_values) {
        if ( is_array($field) ) {
            $num_of_fields  = count($field);
            $field_line     = "";

            for ( $count = 1; $count < count($field); $count++ ) {
                $field_line .= generate_field($field[$count], $default_values);
            }

            return $field_line;
        } else {
            $field_type     = "";
            $input_type     = "";
            $field_name     = "";
            $class          = "";
            $default_index  = "";
            $field_details  = explode("|", trim($field));
            
            if ( isset($field_details[0]) ) $input_type     = $field_details[0];
            if ( isset($field_details[1]) ) $field_type     = $field_details[1];
            if ( isset($field_details[2]) ) $field_name     = $field_details[2];
            if ( isset($field_details[3]) ) $default_index  = $field_details[3];
            if ( isset($field_details[4]) ) $class          = $field_details[4];

            if ( $input_type == "input" && $field_type == "checkbox" ) return generate_checkbox($field_name, $field_type, $class, $default_values, $default_index);
            if ( $input_type == "input" )       return generate_input($field_name, $field_type, $class, $default_values, $default_index);
            if ( $input_type == "hidden" )      return generate_hidden($field_name, $default_values, $default_index);
            if ( $input_type == "noinput" )     return generate_noinput($field_name, $field_type, $class, $default_values, $default_index);
            if ( $input_type == "select" )      return generate_select($field_name, $field_type, $class, $default_values, $default_index);
            if ( $input_type == "textarea" )    return generate_textarea($field_name, $field_type, $class, $default_values, $default_index);             
        }
    }

    function generate_checkbox($name, $field_type, $class, $default_values, $default_index) {
        $input  = "";
        $value  = "";
        $text   = "";
        $id     = "input_{$name}";

        if ( $name ='agree' ) $text = " I agree to the Terms of Service.";

        //$input = "<input name='$name' id='$id' class='$class' type='$field_type' value='$value'/>";
        $input = "<input name='$name' id='$id' class='$class' type='$field_type' value='agree'>$text";

        return $input;
    }

    function generate_hidden($name, $field_type, $class, $default_values, $default_index) {
        $input  = "";
        $value  = "";
        $id     = "input_{$name}";

        if ( isset($default_values[$default_index]) ) $value = $default_values[$default_index];

        $input = "<input name='$name' id='$id' class='$class' type='$field_type' value='$value'/>";

        return $input;
    }

    function generate_input($name, $field_type, $class, $default_values, $default_index) {
        $input  = "";
        $value  = "";
        $id     = "input_{$name}";

        if ( isset($default_values[$default_index]) ) $value = $default_values[$default_index];

        $input = "<input name='$name' id='$id' class='$class' type='$field_type' value='$value'/>";

        return $input;
    }

    function generate_textarea($name, $field_type, $class, $default_values, $default_index) {
        $input  = "";
        $value  = "";
        $id     = "input_{$name}";

        if ( isset($default_values[$default_index]) ) $value = $default_values[$default_index];

        $input = "<textarea name='$name' id='$id' class='$class' />{$value}</textarea>";

        return $input;
    }

    function generate_noinput($name, $field_type, $class, $default_values, $default_index) {
        $input          = "";
        $id             = "noinput_{$name}";
        $value          = $default_values[$default_index];
        $display_value  = $default_values[$name];         $input = "<input name='$default_index' id='$id' class='$class' type='hidden' value='$value'/>$display_value";

        return $input;
    }

    function generate_select($name, $field_type, $class, $default_values, $default_index) {
        $input      = "";
        $id         = "select_{$name}";
        $data_array = array();

        if ( $field_type == "guild" ) {         $data_array = $GLOBALS['global_guild_array']; }
        if ( $field_type == "faction" ) {       $data_array = $GLOBALS['global_faction_array']; }
        if ( $field_type == "server" ) {        $data_array = $GLOBALS['global_server_array']; }
        if ( $field_type == "country" ) {       $data_array = $GLOBALS['global_country_array']; }
        if ( $field_type == "encounter" ) {     $data_array = $GLOBALS['global_encounter_array']; }
        if ( $field_type == "month" ) {         $data_array = $GLOBALS['global_months_array']; }
        if ( $field_type == "day" ) {           $data_array = $GLOBALS['global_days_array']; }
        if ( $field_type == "year" ) {          $data_array = $GLOBALS['global_years_array']; }
        if ( $field_type == "hour" ) {          $data_array = $GLOBALS['global_hours_array']; }
        if ( $field_type == "minute" ) {        $data_array = $GLOBALS['global_minutes_array']; }
        if ( $field_type == "timezone" ) {      $data_array = $GLOBALS['global_timezones_array']; }
        if ( $field_type == "active" ) {        $data_array = $GLOBALS['global_active_array']; }
        if ( $field_type == "contact" ) {       $data_array = $GLOBALS['contact']['type']; }
        if ( $field_type == "site_type" ) {     $data_array = $GLOBALS['site_type']['type']; }
        if ( $field_type == "guild_type" ) {    $data_array = $GLOBALS['guild_type']; }

        $input = "<select name='$name' id='$id' class='$class'>";
            foreach ( $data_array as $value => $value_details ) {
                $form_value     = $value;
                $display_value  = $form_value;

                if ( $field_type == "guild_type" ) {  $display_value = $value_details; $form_value = $value_details; }
                if ( $field_type == "site_type" ) { $display_value = $value_details; }
                if ( $field_type == "active" ) { $display_value = $value_details; }
                if ( $field_type == "contact" ) { $display_value = $value_details; }
                if ( $field_type == "guild" ) { if ( $value_details['active'] == 0 ) { continue; } $display_value = $value_details['name']; }
                if ( $field_type == "encounter" ) { $display_value = $value_details['encounter_name']; }
                if ( $field_type == "month" )  { $display_value = date('F', mktime(0,0,0,$value_details, 1, date('Y')))."-$value_details"; }
                if ( $field_type == "day" )    { $display_value = $value_details; }
                if ( $field_type == "year" )   { $display_value = $value_details; }
                if ( $field_type == "minute" ) { $display_value = $value_details; }
                if ( $field_type == "hour" ) { 
                    $display_value = $value_details;

                    if ( $value > 12 ) {
                        $display_value = ($value-12)."pm";
                        if ( $value > 10 && $value < 22) $display_value = "0".($value-12)."pm";
                    } else if ( $value == 0 ) {
                        $display_value = "12am";
                    } else if ( $value == 12 ) {
                        $display_value = "12pm";
                    } else {
                        $display_value = $value."am";
                    }
                }               

                /****Default Time Selection*****/
                if ( !isset($default_values[$field_type]) ) {
                    if ( $field_type == "hour" && $value == date("H") ) {           $input .= "<option value='$value_details' selected>$display_value</option>"; continue; }
                    if ( $field_type == "minute" && $value == date("i") ) {         $input .= "<option value='$display_value' selected>$display_value</option>"; continue; }
                    if ( $field_type == "year" && $value == date("Y") ) {           $input .= "<option value='$display_value' selected>$display_value</option>"; continue; }
                    if ( $field_type == "day" && $display_value == date("d") ) {    $input .= "<option value='$display_value' selected>$display_value</option>"; continue; }
                    if ( $field_type == "month" && $value_details == date("m") ) {  $input .= "<option value='$value_details' selected>$display_value</option>"; continue; }
                }

                /****Skip Encounters Already Submitted*****/
                if ( $field_type == "encounter" && isset($default_values[$value_details[$name]]) ) { continue; }

                /****Universal Pre-Selected Value Input*****/
                if ( isset($default_values[$field_type]) && $value == $default_values[$field_type] ) { $input .= "<option value='$form_value' selected>$display_value</option>"; continue; }

                /****Pre-Selected Time Values*****/
                if ( ($field_type == "hour" || $field_type == "month") &&
                    isset($default_values[$field_type]) && 
                    $value_details == $default_values[$field_type] ) { 
                    $input .= "<option value='$value_details' selected>$display_value</option>"; continue; 
                }

                if ( ($field_type == "minute" || $field_type == "day" || $field_type == "year") &&
                    isset($default_values[$field_type]) && 
                    $value_details == $default_values[$field_type] ) {
                    $input .= "<option value='$display_value' selected>$display_value</option>"; continue; 
                }

                /*****Filling in Values*****/
                if ( $field_type == "contact" ) { $input .= "<option value='$display_value'>$display_value</option>"; continue; }
                if ( $field_type == "hour" )    { $input .= "<option value='$value_details'>$display_value</option>"; continue; }
                if ( $field_type == "minute" )  { $input .= "<option value='$display_value'>$display_value</option>"; continue; }
                if ( $field_type == "year" )    { $input .= "<option value='$display_value'>$display_value</option>"; continue; }
                if ( $field_type == "day" )     { $input .= "<option value='$display_value'>$display_value</option>"; continue; }
                if ( $field_type == "month" )   { $input .= "<option value='$value_details'>$display_value</option>"; continue; }
                if ( $field_type == "active" )  { $input .= "<option value='$value'>$display_value</option>"; continue; }

                $input .= "<option value='$form_value'>$display_value</option>";
            }
        $input .= "</select>";

        return $input;
    }

    /*****TEXT FORMATTING*****/
    function generate_block_title($title) {
        $word_array = array();
        $new_title  = "";

        $word_array = split(" ", $title);
        $num_of_words = count($word_array);        if ( $num_of_words == 1 ) { $new_title = "<span class='block_title_1'>$word_array[0]</span>"; }

        if ( $num_of_words > 1 ) {
            $new_title = "<span class='block_title_1'>";

            for ( $count = 0; $count < count($word_array); $count++ ) {
                if ( $num_of_words == 2 ) { // Only Two Words
                    if ( $count > 0 && $count % 2 == 1 ) $new_title .= "</span><span class='block_title_2'>";
                    $new_title .= " {$word_array[$count]}"; 
                }

                if ( $num_of_words > 2 && $num_of_words % 2 == 0 ) { // More than two words and even amount
                    if ( $count > 0 && $count % 2 == 0 ) $new_title .= "</span><span class='block_title_2'>";
                    $new_title .= " {$word_array[$count]}"; 
                }

                if ( $num_of_words > 2 && $num_of_words % 2 == 1 ) { // More than two words and odd amount
                    if ( $count > 0 && $count % 2 == 1 ) $new_title .= "</span><span class='block_title_2'>";
                    $new_title .= " {$word_array[$count]}"; 
                }
            }

            echo "</span>";
        }

        return $new_title;
    }

    function short_name($name, $length) {
        if ( !isset($length) || $length == "" ) { return $name; }

        if ( strlen($name) > $length ) { 
            $name = str_replace(" ", "_", $name);
            
            if ( strrpos($name, "_") == ($length - 1) ) $name = substr($name, 0, $length - 1);
            $name = trim(substr(trim($name), 0, $length))."..."; 
            $name = str_replace("_", " ", $name);
        }

        return $name;
    }

    function clean_link_guild($text) {
        $text = strtolower(str_replace(" ", "_", $text));
        
        return $text;       
    }

    function clean_link($text) {
        $text = strtolower(str_replace(" ", "_", $text));
        $text = strtolower(str_replace("'", "", $text));
        
        return $text;
    }

    /*****DATE FORMATTING*****/
    function format_date($date, $format) {
        $str_date = strtotime($date);
        return sprintf(date($format, $str_date)); 
    }

    function convert_date($str_date, $format) {
        return sprintf(date($format, $str_date)); 
    }

    function format_ordinal($number) {
        $abbreviation   = "";
        $ends           = array('th','st','nd','rd','th','th','th','th','th','th');

        if ( $number == "N/A" || $number == "--" ) { return "N/A"; }

        if ( ($number % 100) >= 11 && ($number % 100) <= 13 ) {
           $abbreviation = "{$number}th";
        } else {
           $abbreviation = $number.$ends[$number % 10];
        }

        return $abbreviation;
    }

    function generate_block_fields($point_system, $type, $guild_details, $text_limit) {
        $guild_details['points']    = number_format($guild_details[$point_system]['points'], 2, ".", ",");
        $guild_details['rank']      = $guild_details[$point_system][$type]['rank'];
        $guild_details['trend']     = $guild_details[$point_system][$type]['trend'];
        $guild_details['prev_rank'] = $guild_details[$point_system][$type]['prev_rank'];

        $image = get_trend_image($guild_details['trend']);
    
        $guild_details['trend'] = "{$guild_details['trend']} $image"; 

        return generate_table_fields($guild_details, $text_limit);
    }
    
    function generate_ranking_fields($point_system, $type, $guild_details, $text_limit) {
        $guild_details['points']    = number_format($guild_details[$point_system]['points'], 2, ".", ",");
        $guild_details['rank']      = $guild_details[$point_system][$type]['rank'];
        $guild_details['trend']     = $guild_details[$point_system][$type]['trend'];
        $guild_details['prev_rank'] = "Prev Rank: {$guild_details[$point_system][$type]['prev_rank']}";

        $image = get_trend_image($guild_details['trend']);
    
        if ( $guild_details['trend'] == "NEW" || $guild_details['trend'] == "--" ) {
            $guild_details['trend'] = "<span class='medium_text bold'>{$guild_details['trend']}</span>"; 
        } else {
            $guild_details['trend'] = "<span class='medium_text bold'>{$guild_details['trend']}</span> $image";
        }

        // Legacy
        $guild_details['legacy_rank']      = $guild_details[$point_system][$type]['legacy_rank'];
        $guild_details['legacy_trend']     = $guild_details[$point_system][$type]['legacy_trend'];
        $guild_details['legacy_prev_rank'] = "Prev Rank: {$guild_details[$point_system][$type]['legacy_prev_rank']}";

        $legacy_image = get_trend_image($guild_details['legacy_trend']);
    
        if ( $guild_details['legacy_trend'] == "NEW" || $guild_details['legacy_trend'] == "--" ) {
            $guild_details['legacy_trend'] = "<span class='medium_text bold'>{$guild_details['legacy_trend']}</span>"; 
        } else {
            $guild_details['legacy_trend'] = "<span class='medium_text bold'>{$guild_details['legacy_trend']}</span> $image";
        }

        return generate_table_fields($guild_details, $text_limit);
    }

    function generate_table_fields($guild_details, $text_limit) {
        $server_details     = $GLOBALS['global_server_array'][$guild_details['server']];
        $server_country     = get_image_flag($server_details['region']);
        $country            = get_image_flag($guild_details['country']);

        $guild_details['name']          = "$country <span>".generate_hyperlink_guild($guild_details['name'], $guild_details['server'], 0, $guild_details['faction'], $text_limit)."</span>";
        $guild_details['server']        = "$server_country <span>".generate_hyperlink_server($guild_details['server'], "world", 0, "", "")."</span>";
        $guild_details['country']       = "$country <span>{$guild_details['country']}</span>";

        if ( strlen($guild_details['website']) == 0 )  { $guild_details['website'] = "--"; } else { $guild_details['website'] = generate_external_hyperlink($guild_details['website'], "View", 1, ""); }
        if ( strlen($guild_details['facebook']) == 0 )  { $guild_details['facebook'] = "N/A"; } else { $guild_details['facebook'] = $guild_details['facebook']; }
        if ( strlen($guild_details['twitter']) == 0 )   { $guild_details['twitter'] = "N/A"; } else { $guild_details['twitter'] = $guild_details['twitter']; }
        if ( strlen($guild_details['google']) == 0 )    { $guild_details['google'] = "N/A"; } else { $guild_details['google'] = $guild_details['google']; }

        $guild_details['facebook']      = "<span>{$GLOBALS['images']['facebook_small']} {$guild_details['facebook']}</span>";
        $guild_details['twitter']       = "<span>{$GLOBALS['images']['twitter_small']} {$guild_details['twitter']}</span>";
        $guild_details['google']        = "<span>{$GLOBALS['images']['google_small']} {$guild_details['google']}</span>";

        if ( $guild_details['active'] == "1" ) { $guild_details['active'] = "Active"; }
        if ( $guild_details['active'] == "0" ) { $guild_details['active'] = "Inactive"; }

        return $guild_details;
    }

    /*****DATA VALIDATION*****/
    function validate_email($email) {
        // 0 - Invalid
        // 1 - Valid

        $characters = "^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$^";

        return preg_match($characters, $email);
    }

    function validate_password($pass1, $pass2) {
        // 0 - No Pass Change, 
        // 1 - Valid
        // 2 - Pass Do Not Match
        // 3 - Pass Too Short

        if ( !isset($pass1) && !isset($pass1) ) return 0; // No Password Change
        if ( isset($pass1) && strlen($pass1) > 0 && isset($pass2) && strlen($pass2) > 0 ) { // Passwords were entered
            if ( strlen($pass1) < $GLOBALS['password_minimum'] || strlen($pass2) < $GLOBALS['password_minimum'] ) return 3;
            if ( $pass1 != $pass2 ) return 2;
            if ( $pass1 == $pass2 ) return 1;
        }
    }

    function validate_image($screenshot) {
        $allowed_exts   = $GLOBALS['screenshot_formats'];
        $extension      = strtolower(end(explode(".", $screenshot['file']['name'])));
        $file_type      = "";

        if (((strtolower($screenshot['file']['type']) == "image/gif")
            || (strtolower($screenshot['file']['type']) == "image/jpeg")
            || (strtolower($screenshot['file']['type']) == "image/jpg")
            || (strtolower($screenshot['file']['type']) == "image/png")
            || (strtolower($screenshot['file']['type']) == "image/bmp")
            || (strtolower($screenshot['file']['type']) == "image/pjpeg"))
            && ($screenshot['file']['size'] < 400000000) && (getimagesize($screenshot['file']['tmp_name']))
            && in_array($extension, $allowed_exts)) {

            if ( $screenshot['file']['error'] > 0 ) {
                return 0;
            } else {
                if ( strrpos($screenshot['file']['name'], ".gif") > 0 ) {
                    $file_type = ".gif";
                } else if ( strrpos($screenshot['file']['name'], ".jpg") > 0 ) {
                    $file_type = ".jpg";
                } else if ( strrpos($screenshot['file']['name'], ".jpeg") > 0 ) {
                    $file_type = ".jpeg";
                } else if ( strrpos($screenshot['file']['name'], ".png") > 0 ) {
                    $file_type = ".png";
                } else if ( strrpos($screenshot['file']['name'], ".bmp") > 0 ) {
                    $file_type = ".bmp";
                }

                return 0;
            }
        } else {
            return 1;
        }       
    }

    function validate_submission_time($current_date, $submission_date, $launch_date) {
        $valid = 0;

        if ( $submission_date == "" )           { $valid = 1; }
        if ( $submission_date < $launch_date )  { $valid = 1; }
        if ( $current_date < $submission_date ) { $valid = 1; }

        return $valid;
    }

    function encrypt_password($user, $pass) {
        $encrypt_password = sha1($pass);        for ( $count = 0; $count < 5; $count++ ) {
            $encrypt_password = sha1($encrypt_password.$count);
        }        crypt($encrypt_password);        return $encrypt_password;
    }

    function decrypt_password($user, $pass) {
        $encrypt_password = sha1($pass);
        
        for ( $count = 0; $count < 5; $count++ ) {
            $encrypt_password = sha1($encrypt_password.$count);
        }
        
        crypt($encrypt_password);
                
        return $encrypt_password;
    }

    /*****GLOBAL DATA FETCHING*****/
    function get_tier_details_by_name($tier_name) {
        $tier_name = str_replace("_", " ", $tier_name);

        foreach ( $GLOBALS['global_tier_array'] as $tier => $tier_details ) {
            if ( strcasecmp($tier_name, $tier_details['title']) == 0 ) return $tier_details;
        }
    }

    function get_dungeon_details_by_name($dungeon_name) {
        $dungeon_name = str_replace("_", " ", $dungeon_name);

        if ( $dungeon_name == "tyrants forge" ) { $dungeon_name = "tyrant's forge"; }

        foreach ( $GLOBALS['global_dungeon_array'] as $dungeon_id => $dungeon_details ) {
            if ( strcasecmp($dungeon_name, $dungeon_details['name']) == 0 ) return $dungeon_details;
        }
    }

    function get_encounter_details_by_name($encounter_name) {
        $encounter_name = str_replace("_", " ", $encounter_name);

        foreach ( $GLOBALS['global_encounter_array'] as $encounter_id => $encounter_details ) {
            if ( strcasecmp($encounter_name, $encounter_details['encounter_name']) == 0 ) return $encounter_details;
        }
    }

    function get_server_details_by_name($server_name) {
        $server_name = str_replace("_", " ", $server_name);

        foreach ( $GLOBALS['global_server_array'] as $server => $server_details ) {
            if ( strcasecmp($server_name, $server_details['name']) == 0 ) return $server_details;
        }
    }

    function get_guild_details_by_name($guild_name) {
        $guild_array = array();
        $guild_name = trim(str_replace("_", " ", $guild_name));

        foreach ( $GLOBALS['global_guild_array'] as $guild_id => $guild_details ) {
            if ( strcasecmp($guild_name, $guild_details['name']) == 0 ) { array_push($guild_array, $guild_details); return $guild_array; }
        }
    }

    function get_tier_details($tier) {
        return $GLOBALS['global_tier_array'][$tier];
    }

    function get_dungeon_details($dungeon_id) {
        return $GLOBALS['global_dungeon_array'][$dungeon_id];
    }

    function get_encounter_details($encounter_id) {
        $encounter_details                          = $GLOBALS['global_encounter_array'][$encounter_id];
        $dungeon_details                            = $GLOBALS['global_dungeon_array'][$encounter_details['dungeon_id']];        
        $encounter_details['num_of_kills']          = array();
        $encounter_details['first_kill_date']       = "";
        $encounter_details['first_kill_guild']      = "";
        $encounter_details['first_kill_strtotime']  = "";
        $encounter_details['recent_kill_date']      = "";
        $encounter_details['recent_kill_guild']     = "";
        $encounter_details['recent_kill_strtotime'] = "";
        $first_kill                                 = 0;
        $recent_kill                                = 0;

        $guild_array = $GLOBALS['global_guild_array'];

        foreach ( $guild_array as $guild_id => $guild_details ) {
            $region = $guild_details['region'];

            $encounter_array = explode("~~", $guild_details['progression']);

            for ($count = 0; $count < count($encounter_array); $count++ ) {
                $encounter_info = explode("||", $encounter_array[$count]);

                if ( isset($encounter_info) && count($encounter_info) > 1 ) {
                    if ( $encounter_info[0] == $encounter_id ) {
                        if ( !isset($encounter_details['num_of_kills']['total']) ) $encounter_details['num_of_kills']['total'] = 0;
                        if ( !isset($encounter_details['num_of_kills'][$region]) ) $encounter_details['num_of_kills'][$region] = 0;
                        
                        $encounter_details['num_of_kills']['total']++;
                        $encounter_details['num_of_kills'][$region]++;

                        $encounter_kill_time = strtotime("{$encounter_info[1]} {$encounter_info[2]}");
                        if ( $guild_details['region'] == "EU" ) $encounter_kill_time = $encounter_kill_time - ($dungeon_details['eu_diff']*3600);

                        $encounter_time_format = "{$encounter_info[1]} @ {$encounter_info[2]}";

                        if ( $first_kill > 0 ) {
                            if ( $first_kill > $encounter_kill_time ) {
                                $first_kill = $encounter_kill_time;
                                $encounter_details['first_kill_date']       = $encounter_time_format;
                                $encounter_details['first_kill_guild']      = $guild_id;    
                                $encounter_details['first_kill_strtotime']  = $encounter_kill_time;         
                            } 

                            if ( $recent_kill < $encounter_kill_time ) {
                                $recent_kill = $encounter_kill_time;
                                $encounter_details['recent_kill_date']      = $encounter_time_format;
                                $encounter_details['recent_kill_guild']     = $guild_id;
                                $encounter_details['recent_kill_strtotime'] = $encounter_kill_time;                             
                            }
                        } else {
                            $first_kill = $encounter_kill_time;         
                            $recent_kill = $encounter_kill_time;

                            $encounter_details['first_kill_date']       = $encounter_time_format;
                            $encounter_details['first_kill_guild']      = $guild_id;
                            $encounter_details['first_kill_strtotime']  = $encounter_kill_time; 
                            $encounter_details['recent_kill_date']      = $encounter_time_format;
                            $encounter_details['recent_kill_guild']     = $guild_id;
                            $encounter_details['recent_kill_strtotime'] = $encounter_kill_time;         
                        }

                        break;
                    }
                }
            }
        }   

        return $encounter_details;      
    }

    function get_guild_details_by_name_server($name, $server) {
        $guild_array = array();

        foreach( $GLOBALS['global_guild_array'] as $guild_id => $guild_details ) {
            if ( strcasecmp($name, $guild_details['name']) == 0 && strcasecmp($server, $guild_details['server']) == 0 ) {
                array_push($guild_array, $guild_details);
            }
        }

        return $guild_array;
    }

    function get_guild_details($guild_id) {
        //if ( !isset($GLOBALS['global_guild_array'][$guild_id]) ) $GLOBALS['global_guild_array'][$guild_id] = get_new_guild($guild_id);

        $guild_details                              = $GLOBALS['global_guild_array'][$guild_id];
        $guild_details['encounter_details']         = array();
        $guild_details['direct_encounter_details']  = array();
        $guild_details['dungeon_details']           = array();
        $guild_details['raid_size_details']         = array();
        $guild_details['direct_raid_size_details']  = array();
        $guild_details['tier_details']              = array();
        $guild_details['overall_details']           = array();
        $total_encounters_per_size_array            = $total_encounters_per_tier_size_array = array();

        if ( !isset($guild_details['overall_details']['standing']) )                        $guild_details['overall_details']['standing'] = "0/0";
        if ( !isset($guild_details['overall_details']['pct']) )                             $guild_details['overall_details']['pct'] = "00.00%";
        if ( !isset($guild_details['overall_details']['recent']) )                          $guild_details['overall_details']['recent'] = "--";
        if ( !isset($guild_details['overall_details']['recent_time']) )                     $guild_details['overall_details']['recent_time'] = "";
        if ( !isset($guild_details['overall_details']['complete']) )                        $guild_details['overall_details']['complete'] = 0;
        if ( !isset($guild_details['overall_details']['special_encounter_2']) )             $guild_details['overall_details']['special_encounter_2'] = "0/0";
        if ( !isset($guild_details['overall_details']['special_encounter_1']) )             $guild_details['overall_details']['special_encounter_1'] = "0/0";
        if ( !isset($guild_details['overall_details']['special_encounter_2_complete']) )    $guild_details['overall_details']['special_encounter_2_complete'] = 0;
        if ( !isset($guild_details['overall_details']['special_encounter_1_complete']) )    $guild_details['overall_details']['special_encounter_1_complete'] = 0;
        if ( !isset($guild_details['overall_details']['world_first']) )                     $guild_details['overall_details']['world_first'] = 0;
        if ( !isset($guild_details['overall_details']['region_first']) )                    $guild_details['overall_details']['region_first'] = 0;
        if ( !isset($guild_details['overall_details']['server_first']) )                    $guild_details['overall_details']['server_first'] = 0;

        foreach ( $GLOBALS['point_system'] as $type => $system_name ) {
            if ( !isset($guild_details['overall_details'][$type]['points']) )                   $guild_details['overall_details'][$type]['points'] = 0.00;

            // Doubling up here with Active/Legacy, Legacy will get additional line, active will be standard
            if ( !isset($guild_details['overall_details'][$type]['world']['trend']) )           $guild_details['overall_details'][$type]['world']['trend'] = "--";
            if ( !isset($guild_details['overall_details'][$type]['region']['trend']) )          $guild_details['overall_details'][$type]['region']['trend'] = "--";
            if ( !isset($guild_details['overall_details'][$type]['server']['trend']) )          $guild_details['overall_details'][$type]['server']['trend'] = "--";
            if ( !isset($guild_details['overall_details'][$type]['world']['prev_rank']) )       $guild_details['overall_details'][$type]['world']['prev_rank'] = "--";
            if ( !isset($guild_details['overall_details'][$type]['region']['prev_rank']) )      $guild_details['overall_details'][$type]['region']['prev_rank'] = "--";
            if ( !isset($guild_details['overall_details'][$type]['server']['prev_rank']) )      $guild_details['overall_details'][$type]['server']['prev_rank'] = "--";
            if ( !isset($guild_details['overall_details'][$type]['world']['rank']) )            $guild_details['overall_details'][$type]['world']['rank'] = "N/A";
            if ( !isset($guild_details['overall_details'][$type]['region']['rank']) )           $guild_details['overall_details'][$type]['region']['rank'] = "N/A";
            if ( !isset($guild_details['overall_details'][$type]['server']['rank']) )           $guild_details['overall_details'][$type]['server']['rank'] = "N/A";

            if ( !isset($guild_details['overall_details'][$type]['world']['legacy_trend']) )           $guild_details['overall_details'][$type]['world']['legacy_trend'] = "--";
            if ( !isset($guild_details['overall_details'][$type]['region']['legacy_trend']) )          $guild_details['overall_details'][$type]['region']['legacy_trend'] = "--";
            if ( !isset($guild_details['overall_details'][$type]['server']['legacy_trend']) )          $guild_details['overall_details'][$type]['server']['legacy_trend'] = "--";
            if ( !isset($guild_details['overall_details'][$type]['world']['legacy_prev_rank']) )       $guild_details['overall_details'][$type]['world']['legacy_prev_rank'] = "--";
            if ( !isset($guild_details['overall_details'][$type]['region']['legacy_prev_rank']) )      $guild_details['overall_details'][$type]['region']['legacy_prev_rank'] = "--";
            if ( !isset($guild_details['overall_details'][$type]['server']['legacy_prev_rank']) )      $guild_details['overall_details'][$type]['server']['legacy_prev_rank'] = "--";
            if ( !isset($guild_details['overall_details'][$type]['world']['legacy_rank']) )            $guild_details['overall_details'][$type]['world']['legacy_rank'] = "N/A";
            if ( !isset($guild_details['overall_details'][$type]['region']['legacy_rank']) )           $guild_details['overall_details'][$type]['region']['legacy_rank'] = "N/A";
            if ( !isset($guild_details['overall_details'][$type]['server']['legacy_rank']) )           $guild_details['overall_details'][$type]['server']['legacy_rank'] = "N/A";
        }

        // Setting Default Values For Guild (All Values)
        foreach ( $GLOBALS['global_tier_array'] as $tier => $tier_details ) {
            if ( !isset($guild_details['tier_details'][$tier]['tier_name']) )                       $guild_details['tier_details'][$tier]['tier_name'] = $GLOBALS['global_tier_array'][$tier]['title'];
            if ( !isset($guild_details['tier_details'][$tier]['standing']) )                        $guild_details['tier_details'][$tier]['standing'] = "0/{$GLOBALS['global_tier_array'][$tier]['encounters']}";
            if ( !isset($guild_details['tier_details'][$tier]['pct']) )                             $guild_details['tier_details'][$tier]['pct'] = "00.00%";
            if ( !isset($guild_details['tier_details'][$tier]['recent']) )                          $guild_details['tier_details'][$tier]['recent'] = "--";
            if ( !isset($guild_details['tier_details'][$tier]['recent_time']) )                     $guild_details['tier_details'][$tier]['recent_time'] = "";
            if ( !isset($guild_details['tier_details'][$tier]['complete']) )                        $guild_details['tier_details'][$tier]['complete'] = 0;
            if ( !isset($guild_details['tier_details'][$tier]['special_encounter_2']) )             $guild_details['tier_details'][$tier]['special_encounter_2'] = "0/0";
            if ( !isset($guild_details['tier_details'][$tier]['special_encounter_1']) )             $guild_details['tier_details'][$tier]['special_encounter_1'] = "0/0";
            if ( !isset($guild_details['tier_details'][$tier]['special_encounter_2_complete']) )    $guild_details['tier_details'][$tier]['special_encounter_2_complete'] = 0;
            if ( !isset($guild_details['tier_details'][$tier]['special_encounter_1_complete']) )    $guild_details['tier_details'][$tier]['special_encounter_1_complete'] = 0;
            if ( !isset($guild_details['tier_details'][$tier]['world_first']) )                     $guild_details['tier_details'][$tier]['world_first'] = 0;
            if ( !isset($guild_details['tier_details'][$tier]['region_first']) )                    $guild_details['tier_details'][$tier]['region_first'] = 0;
            if ( !isset($guild_details['tier_details'][$tier]['server_first']) )                    $guild_details['tier_details'][$tier]['server_first'] = 0;

            foreach ( $GLOBALS['point_system'] as $type => $system_name ) {
                if ( !isset($guild_details['tier_details'][$tier][$type]['points']) )               $guild_details['tier_details'][$tier][$type]['points'] = 0.00;

                // Doubling up here with Active/Legacy, Legacy will get additional line, active will be standard
                if ( !isset($guild_details['tier_details'][$tier][$type]['world']['trend']) )       $guild_details['tier_details'][$tier][$type]['world']['trend'] = "--";
                if ( !isset($guild_details['tier_details'][$tier][$type]['region']['trend']) )      $guild_details['tier_details'][$tier][$type]['region']['trend'] = "--";
                if ( !isset($guild_details['tier_details'][$tier][$type]['server']['trend']) )      $guild_details['tier_details'][$tier][$type]['server']['trend'] = "--";
                if ( !isset($guild_details['tier_details'][$tier][$type]['world']['prev_rank']) )   $guild_details['tier_details'][$tier][$type]['world']['prev_rank'] = "--";
                if ( !isset($guild_details['tier_details'][$tier][$type]['region']['prev_rank']) )  $guild_details['tier_details'][$tier][$type]['region']['prev_rank'] = "--";
                if ( !isset($guild_details['tier_details'][$tier][$type]['server']['prev_rank']) )  $guild_details['tier_details'][$tier][$type]['server']['prev_rank'] = "--";
                if ( !isset($guild_details['tier_details'][$tier][$type]['world']['rank']) )        $guild_details['tier_details'][$tier][$type]['world']['rank'] = "N/A";
                if ( !isset($guild_details['tier_details'][$tier][$type]['region']['rank']) )       $guild_details['tier_details'][$tier][$type]['region']['rank'] = "N/A";
                if ( !isset($guild_details['tier_details'][$tier][$type]['server']['rank']) )       $guild_details['tier_details'][$tier][$type]['server']['rank'] = "N/A";

                if ( !isset($guild_details['tier_details'][$tier][$type]['world']['legacy_trend']) )       $guild_details['tier_details'][$tier][$type]['world']['legacy_trend'] = "--";
                if ( !isset($guild_details['tier_details'][$tier][$type]['region']['legacy_trend']) )      $guild_details['tier_details'][$tier][$type]['region']['legacy_trend'] = "--";
                if ( !isset($guild_details['tier_details'][$tier][$type]['server']['legacy_trend']) )      $guild_details['tier_details'][$tier][$type]['server']['legacy_trend'] = "--";
                if ( !isset($guild_details['tier_details'][$tier][$type]['world']['legacy_prev_rank']) )   $guild_details['tier_details'][$tier][$type]['world']['legacy_prev_rank'] = "--";
                if ( !isset($guild_details['tier_details'][$tier][$type]['region']['legacy_prev_rank']) )  $guild_details['tier_details'][$tier][$type]['region']['legacy_prev_rank'] = "--";
                if ( !isset($guild_details['tier_details'][$tier][$type]['server']['legacy_prev_rank']) )  $guild_details['tier_details'][$tier][$type]['server']['legacy_prev_rank'] = "--";
                if ( !isset($guild_details['tier_details'][$tier][$type]['world']['legacy_rank']) )        $guild_details['tier_details'][$tier][$type]['world']['legacy_rank'] = "N/A";
                if ( !isset($guild_details['tier_details'][$tier][$type]['region']['legacy_rank']) )       $guild_details['tier_details'][$tier][$type]['region']['legacy_rank'] = "N/A";
                if ( !isset($guild_details['tier_details'][$tier][$type]['server']['legacy_rank']) )       $guild_details['tier_details'][$tier][$type]['server']['legacy_rank'] = "N/A";
            }

            foreach ( $GLOBALS['raid_size_array'] as $size => $raid_size ) {
                if ( !isset($total_encounters_per_tier_size_array[$tier][$raid_size]['encounters']) ) $total_encounters_per_tier_size_array[$tier][$raid_size]['encounters'] = 0;
                if ( !isset($total_encounters_per_size_array[$raid_size]['encounters']) ) $total_encounters_per_size_array[$raid_size]['encounters'] = 0;
                if ( !isset($total_encounters_per_tier_size_array[$tier][$raid_size]['special_encounters']) ) $total_encounters_per_tier_size_array[$tier][$raid_size]['special_encounters'] = 0;
                if ( !isset($total_encounters_per_size_array[$raid_size]['special_encounters']) ) $total_encounters_per_size_array[$raid_size]['special_encounters'] = 0;

                foreach ( $GLOBALS['global_dungeon_array'] as $dungeon_id => $dungeon_details ) {
                    if ( $dungeon_details['players'] == $raid_size && $dungeon_details['dungeon_type'] == 0 && $dungeon_details['tier'] == $tier ) {
                        $total_encounters_per_tier_size_array[$tier][$raid_size]['encounters'] += $dungeon_details['mobs'];
                        $total_encounters_per_size_array[$raid_size]['encounters'] += $dungeon_details['mobs'];

                        $total_encounters_per_tier_size_array[$tier][$raid_size]['special_encounters'] += $dungeon_details['special_encounters'];
                        $total_encounters_per_size_array[$raid_size]['special_encounters'] += $dungeon_details['special_encounters'];
                    }
                }
            }

            foreach ( $GLOBALS['raid_size_array'] as $size => $raid_size ) {
                if ( !isset($guild_details['raid_size_details'][$tier][$raid_size]['standing']) )                       $guild_details['raid_size_details'][$tier][$raid_size]['standing'] = "0/{$total_encounters_per_tier_size_array[$tier][$raid_size]['encounters']}";
                if ( !isset($guild_details['raid_size_details'][$tier][$raid_size]['pct']) )                            $guild_details['raid_size_details'][$tier][$raid_size]['pct'] = "00.00%";
                if ( !isset($guild_details['raid_size_details'][$tier][$raid_size]['recent']) )                         $guild_details['raid_size_details'][$tier][$raid_size]['recent'] = "--";
                if ( !isset($guild_details['raid_size_details'][$tier][$raid_size]['recent_time']) )                    $guild_details['raid_size_details'][$tier][$raid_size]['recent_time'] = "";
                if ( !isset($guild_details['raid_size_details'][$tier][$raid_size]['complete']) )                       $guild_details['raid_size_details'][$tier][$raid_size]['complete'] = 0;
                if ( !isset($guild_details['raid_size_details'][$tier][$raid_size]['special_encounter_2']) )            $guild_details['raid_size_details'][$tier][$raid_size]['special_encounter_2'] = "0/0";
                if ( !isset($guild_details['raid_size_details'][$tier][$raid_size]['special_encounter_1']) )            $guild_details['raid_size_details'][$tier][$raid_size]['special_encounter_1'] = "0/0";
                if ( !isset($guild_details['raid_size_details'][$tier][$raid_size]['special_encounter_2_complete']) )   $guild_details['raid_size_details'][$tier][$raid_size]['special_encounter_2_complete'] = 0;
                if ( !isset($guild_details['raid_size_details'][$tier][$raid_size]['special_encounter_1_complete']) )   $guild_details['raid_size_details'][$tier][$raid_size]['special_encounter_1_complete'] = 0;
                if ( !isset($guild_details['raid_size_details'][$tier][$raid_size]['world_first']) )                    $guild_details['raid_size_details'][$tier][$raid_size]['world_first'] = 0;
                if ( !isset($guild_details['raid_size_details'][$tier][$raid_size]['region_first']) )                   $guild_details['raid_size_details'][$tier][$raid_size]['region_first'] = 0;
                if ( !isset($guild_details['raid_size_details'][$tier][$raid_size]['server_first']) )                   $guild_details['raid_size_details'][$tier][$raid_size]['server_first'] = 0; 
                

                foreach ( $GLOBALS['point_system'] as $type => $system_name ) {
                    if ( !isset($guild_details['raid_size_details'][$tier][$raid_size][$type]['points']) )              $guild_details['raid_size_details'][$tier][$raid_size][$type]['points'] = 0.00; 
                    if ( !isset($guild_details['raid_size_details'][$tier][$raid_size][$type]['world']['trend']) )      $guild_details['raid_size_details'][$tier][$raid_size][$type]['world']['trend'] = "--";
                    if ( !isset($guild_details['raid_size_details'][$tier][$raid_size][$type]['region']['trend']) )     $guild_details['raid_size_details'][$tier][$raid_size][$type]['region']['trend'] = "--";
                    if ( !isset($guild_details['raid_size_details'][$tier][$raid_size][$type]['server']['trend']) )     $guild_details['raid_size_details'][$tier][$raid_size][$type]['server']['trend'] = "--";
                    if ( !isset($guild_details['raid_size_details'][$tier][$raid_size][$type]['world']['prev_rank']) )  $guild_details['raid_size_details'][$tier][$raid_size][$type]['world']['prev_rank'] = "--";
                    if ( !isset($guild_details['raid_size_details'][$tier][$raid_size][$type]['region']['prev_rank']) ) $guild_details['raid_size_details'][$tier][$raid_size][$type]['region']['prev_rank'] = "--";
                    if ( !isset($guild_details['raid_size_details'][$tier][$raid_size][$type]['server']['prev_rank']) ) $guild_details['raid_size_details'][$tier][$raid_size][$type]['server']['prev_rank'] = "--";
                    if ( !isset($guild_details['raid_size_details'][$tier][$raid_size][$type]['world']['rank']) )       $guild_details['raid_size_details'][$tier][$raid_size][$type]['world']['rank'] = "N/A";
                    if ( !isset($guild_details['raid_size_details'][$tier][$raid_size][$type]['region']['rank']) )      $guild_details['raid_size_details'][$tier][$raid_size][$type]['region']['rank'] = "N/A";
                    if ( !isset($guild_details['raid_size_details'][$tier][$raid_size][$type]['server']['rank']) )      $guild_details['raid_size_details'][$tier][$raid_size][$type]['server']['rank'] = "N/A";
                }

                if ( !isset($guild_details['direct_raid_size_details'][$raid_size]['standing']) )                       $guild_details['direct_raid_size_details'][$raid_size]['standing'] = "0/{$total_encounters_per_size_array[$raid_size]['encounters']}";
                if ( !isset($guild_details['direct_raid_size_details'][$raid_size]['pct']) )                            $guild_details['direct_raid_size_details'][$raid_size]['pct'] = "00.00%";
                if ( !isset($guild_details['direct_raid_size_details'][$raid_size]['recent']) )                         $guild_details['direct_raid_size_details'][$raid_size]['recent'] = "--";
                if ( !isset($guild_details['direct_raid_size_details'][$raid_size]['recent_time']) )                    $guild_details['direct_raid_size_details'][$raid_size]['recent_time'] = "";
                if ( !isset($guild_details['direct_raid_size_details'][$raid_size]['complete']) )                       $guild_details['direct_raid_size_details'][$raid_size]['complete'] = 0;
                if ( !isset($guild_details['direct_raid_size_details'][$raid_size]['special_encounter_2']) )            $guild_details['direct_raid_size_details'][$raid_size]['special_encounter_2'] = "0/0";
                if ( !isset($guild_details['direct_raid_size_details'][$raid_size]['special_encounter_1']) )            $guild_details['direct_raid_size_details'][$raid_size]['special_encounter_1'] = "0/0";
                if ( !isset($guild_details['direct_raid_size_details'][$raid_size]['special_encounter_2_complete']) )   $guild_details['direct_raid_size_details'][$raid_size]['special_encounter_2_complete'] = 0;
                if ( !isset($guild_details['direct_raid_size_details'][$raid_size]['special_encounter_1_complete']) )   $guild_details['direct_raid_size_details'][$raid_size]['special_encounter_1_complete'] = 0;
                if ( !isset($guild_details['direct_raid_size_details'][$raid_size]['world_first']) )                    $guild_details['direct_raid_size_details'][$raid_size]['world_first'] = 0;
                if ( !isset($guild_details['direct_raid_size_details'][$raid_size]['region_first']) )                   $guild_details['direct_raid_size_details'][$raid_size]['region_first'] = 0;
                if ( !isset($guild_details['direct_raid_size_details'][$raid_size]['server_first']) )                   $guild_details['direct_raid_size_details'][$raid_size]['server_first'] = 0;

                foreach ( $GLOBALS['point_system'] as $type => $system_name ) {
                    if ( !isset($guild_details['direct_raid_size_details'][$raid_size][$type]['points']) )              $guild_details['direct_raid_size_details'][$raid_size][$type]['points'] = 0.00;
                    if ( !isset($guild_details['direct_raid_size_details'][$raid_size][$type]['world']['trend']) )      $guild_details['direct_raid_size_details'][$raid_size][$type]['world']['trend'] = "--";
                    if ( !isset($guild_details['direct_raid_size_details'][$raid_size][$type]['region']['trend']) )     $guild_details['direct_raid_size_details'][$raid_size][$type]['region']['trend'] = "--";
                    if ( !isset($guild_details['direct_raid_size_details'][$raid_size][$type]['server']['trend']) )     $guild_details['direct_raid_size_details'][$raid_size][$type]['server']['trend'] = "--";
                    if ( !isset($guild_details['direct_raid_size_details'][$raid_size][$type]['world']['prev_rank']) )  $guild_details['direct_raid_size_details'][$raid_size][$type]['world']['prev_rank'] = "--";
                    if ( !isset($guild_details['direct_raid_size_details'][$raid_size][$type]['region']['prev_rank']) ) $guild_details['direct_raid_size_details'][$raid_size][$type]['region']['prev_rank'] = "--";
                    if ( !isset($guild_details['direct_raid_size_details'][$raid_size][$type]['server']['prev_rank']) ) $guild_details['direct_raid_size_details'][$raid_size][$type]['server']['prev_rank'] = "--";
                    if ( !isset($guild_details['direct_raid_size_details'][$raid_size][$type]['world']['rank']) )       $guild_details['direct_raid_size_details'][$raid_size][$type]['world']['rank'] = "N/A";
                    if ( !isset($guild_details['direct_raid_size_details'][$raid_size][$type]['region']['rank']) )      $guild_details['direct_raid_size_details'][$raid_size][$type]['region']['rank'] = "N/A";
                    if ( !isset($guild_details['direct_raid_size_details'][$raid_size][$type]['server']['rank']) )      $guild_details['direct_raid_size_details'][$raid_size][$type]['server']['rank'] = "N/A";
                }

                foreach ( $GLOBALS['global_dungeon_array'] as $dungeon_id => $dungeon_details ) {
                    if ( !isset($guild_details['dungeon_details'][$dungeon_id]['dungeon_name']) )                   $guild_details['dungeon_details'][$dungeon_id]['dungeon_name'] = $GLOBALS['global_dungeon_array'][$dungeon_id]['name'];
                    if ( !isset($guild_details['dungeon_details'][$dungeon_id]['standing']) )                       $guild_details['dungeon_details'][$dungeon_id]['standing'] = "0/{$GLOBALS['global_dungeon_array'][$dungeon_id]['mobs']}";
                    if ( !isset($guild_details['dungeon_details'][$dungeon_id]['pct']) )                            $guild_details['dungeon_details'][$dungeon_id]['pct'] = "00.00%";
                    if ( !isset($guild_details['dungeon_details'][$dungeon_id]['recent']) )                         $guild_details['dungeon_details'][$dungeon_id]['recent'] = "--";
                    if ( !isset($guild_details['dungeon_details'][$dungeon_id]['recent_time']) )                    $guild_details['dungeon_details'][$dungeon_id]['recent_time'] = "";
                    if ( !isset($guild_details['dungeon_details'][$dungeon_id]['complete']) )                       $guild_details['dungeon_details'][$dungeon_id]['complete'] = 0;
                    if ( !isset($guild_details['dungeon_details'][$dungeon_id]['special_encounter_2']) )            $guild_details['dungeon_details'][$dungeon_id]['special_encounter_2'] = "0/0";
                    if ( !isset($guild_details['dungeon_details'][$dungeon_id]['special_encounter_1']) )            $guild_details['dungeon_details'][$dungeon_id]['special_encounter_1'] = "0/0";
                    if ( !isset($guild_details['dungeon_details'][$dungeon_id]['special_encounter_2_complete']) )   $guild_details['dungeon_details'][$dungeon_id]['special_encounter_2_complete'] = 0;
                    if ( !isset($guild_details['dungeon_details'][$dungeon_id]['special_encounter_1_complete']) )   $guild_details['dungeon_details'][$dungeon_id]['special_encounter_1_complete'] = 0;
                    if ( !isset($guild_details['dungeon_details'][$dungeon_id]['world_first']) )                    $guild_details['dungeon_details'][$dungeon_id]['world_first'] = 0;
                    if ( !isset($guild_details['dungeon_details'][$dungeon_id]['region_first']) )                   $guild_details['dungeon_details'][$dungeon_id]['region_first'] = 0;
                    if ( !isset($guild_details['dungeon_details'][$dungeon_id]['server_first']) )                   $guild_details['dungeon_details'][$dungeon_id]['server_first'] = 0; 

                    foreach ( $GLOBALS['point_system'] as $type => $system_name ) {
                        if ( !isset($guild_details['dungeon_details'][$dungeon_id][$type]['points']) )              $guild_details['dungeon_details'][$dungeon_id][$type]['points'] = 0.00;

                        // Doubling up here with Active/Legacy, Legacy will get additional line, active will be standard
                        if ( !isset($guild_details['dungeon_details'][$dungeon_id][$type]['world']['trend']) )      $guild_details['dungeon_details'][$dungeon_id][$type]['world']['trend'] = "--";
                        if ( !isset($guild_details['dungeon_details'][$dungeon_id][$type]['region']['trend']) )     $guild_details['dungeon_details'][$dungeon_id][$type]['region']['trend'] = "--";
                        if ( !isset($guild_details['dungeon_details'][$dungeon_id][$type]['server']['trend']) )     $guild_details['dungeon_details'][$dungeon_id][$type]['server']['trend'] = "--";
                        if ( !isset($guild_details['dungeon_details'][$dungeon_id][$type]['world']['prev_rank']) )  $guild_details['dungeon_details'][$dungeon_id][$type]['world']['prev_rank'] = "--";
                        if ( !isset($guild_details['dungeon_details'][$dungeon_id][$type]['region']['prev_rank']) ) $guild_details['dungeon_details'][$dungeon_id][$type]['region']['prev_rank'] = "--";
                        if ( !isset($guild_details['dungeon_details'][$dungeon_id][$type]['server']['prev_rank']) ) $guild_details['dungeon_details'][$dungeon_id][$type]['server']['prev_rank'] = "--";
                        if ( !isset($guild_details['dungeon_details'][$dungeon_id][$type]['world']['rank']) )       $guild_details['dungeon_details'][$dungeon_id][$type]['world']['rank'] = "N/A";
                        if ( !isset($guild_details['dungeon_details'][$dungeon_id][$type]['region']['rank']) )      $guild_details['dungeon_details'][$dungeon_id][$type]['region']['rank'] = "N/A";
                        if ( !isset($guild_details['dungeon_details'][$dungeon_id][$type]['server']['rank']) )      $guild_details['dungeon_details'][$dungeon_id][$type]['server']['rank'] = "N/A";

                        if ( !isset($guild_details['dungeon_details'][$dungeon_id][$type]['world']['legacy_trend']) )      $guild_details['dungeon_details'][$dungeon_id][$type]['world']['legacy_trend'] = "--";
                        if ( !isset($guild_details['dungeon_details'][$dungeon_id][$type]['region']['legacy_trend']) )     $guild_details['dungeon_details'][$dungeon_id][$type]['region']['legacy_trend'] = "--";
                        if ( !isset($guild_details['dungeon_details'][$dungeon_id][$type]['server']['legacy_trend']) )     $guild_details['dungeon_details'][$dungeon_id][$type]['server']['legacy_trend'] = "--";
                        if ( !isset($guild_details['dungeon_details'][$dungeon_id][$type]['world']['legacy_prev_rank']) )  $guild_details['dungeon_details'][$dungeon_id][$type]['world']['legacy_prev_rank'] = "--";
                        if ( !isset($guild_details['dungeon_details'][$dungeon_id][$type]['region']['legacy_prev_rank']) ) $guild_details['dungeon_details'][$dungeon_id][$type]['region']['legacy_prev_rank'] = "--";
                        if ( !isset($guild_details['dungeon_details'][$dungeon_id][$type]['server']['legacy_prev_rank']) ) $guild_details['dungeon_details'][$dungeon_id][$type]['server']['legacy_prev_rank'] = "--";
                        if ( !isset($guild_details['dungeon_details'][$dungeon_id][$type]['world']['legacy_rank']) )       $guild_details['dungeon_details'][$dungeon_id][$type]['world']['legacy_rank'] = "N/A";
                        if ( !isset($guild_details['dungeon_details'][$dungeon_id][$type]['region']['legacy_rank']) )      $guild_details['dungeon_details'][$dungeon_id][$type]['region']['legacy_rank'] = "N/A";
                        if ( !isset($guild_details['dungeon_details'][$dungeon_id][$type]['server']['legacy_rank']) )      $guild_details['dungeon_details'][$dungeon_id][$type]['server']['legacy_rank'] = "N/A";
                    }
                }

                if ( !isset($guild_details['tier_details'][$tier]['progression_overall']) ) {
                    foreach ( $GLOBALS['global_dungeon_array'] as $dungeon_id => $dungeon_details ) {
                        if ( $dungeon_details['tier'] != $tier || $dungeon_details['dungeon_type'] == 1 ) continue;

                        if ( isset($guild_details['tier_details'][$tier]['progression_overall']) ) {
                            $guild_details['tier_details'][$tier]['progression_overall'] .= ", {$guild_details['dungeon_details'][$dungeon_id]['standing']} {$dungeon_details['abbreviation']}";
                        } else {
                            $guild_details['tier_details'][$tier]['progression_overall']  = "{$guild_details['dungeon_details'][$dungeon_id]['standing']} {$dungeon_details['abbreviation']}";
                        }
                    }
                }   
            }
        }

        if ( isset($guild_details['progression']) && strlen($guild_details['progression']) > 0 ) {
            $progression_details_array = explode("~~", $guild_details['progression']);
            
            for ( $encounter = 0; $encounter < count($progression_details_array); $encounter++ ) {
                $progression_details = explode("||", $progression_details_array[$encounter]);

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

                $encounter_id       = $progression_details[0];
                $encounter_details  = $GLOBALS['global_encounter_array'][$encounter_id];
                $tier               = $encounter_details['tier'];
                $size               = $encounter_details['players'];
                $dungeon            = $encounter_details['dungeon_id'];
                $dungeon_details    = $GLOBALS['global_dungeon_array'][$dungeon];

                //Providing direct route to encounter details
                $guild_details['direct_encounter_details'][$encounter_id]['guild_id']       = $guild_id;
                $guild_details['direct_encounter_details'][$encounter_id]['encounter_id']   = $encounter_id;
                $guild_details['direct_encounter_details'][$encounter_id]['encounter_name'] = $GLOBALS['global_encounter_array'][$encounter_id]['encounter_name'];
                $guild_details['direct_encounter_details'][$encounter_id]['tier']           = $GLOBALS['global_encounter_array'][$encounter_id]['tier'];
                $guild_details['direct_encounter_details'][$encounter_id]['dungeon_name']   = $GLOBALS['global_encounter_array'][$encounter_id]['dungeon'];
                $guild_details['direct_encounter_details'][$encounter_id]['dungeon_id']     = $GLOBALS['global_encounter_array'][$encounter_id]['dungeon_id'];
                $guild_details['direct_encounter_details'][$encounter_id]['players']        = $GLOBALS['global_encounter_array'][$encounter_id]['players'];
                $guild_details['direct_encounter_details'][$encounter_id]['mob_type']       = $GLOBALS['global_encounter_array'][$encounter_id]['mob_type'];
                $guild_details['direct_encounter_details'][$encounter_id]['date']           = $progression_details[1];
                $guild_details['direct_encounter_details'][$encounter_id]['time']           = $progression_details[2];
                $guild_details['direct_encounter_details'][$encounter_id]['datetime']       = format_date("{$progression_details[1]} {$progression_details[2]}", 'm/d/Y H:i');
                $guild_details['direct_encounter_details'][$encounter_id]['strtotime']      = strtotime("{$progression_details[1]} {$progression_details[2]}");
                //$guild_details['direct_encounter_details'][$encounter_id]['timezone']       = $progression_details[3];
                $guild_details['direct_encounter_details'][$encounter_id]['hour']           = format_date($progression_details[2], "h");
                $guild_details['direct_encounter_details'][$encounter_id]['minute']         = format_date($progression_details[2], "i");
                $guild_details['direct_encounter_details'][$encounter_id]['month']          = format_date($progression_details[1], "m");
                $guild_details['direct_encounter_details'][$encounter_id]['day']            = format_date($progression_details[1], "d");
                $guild_details['direct_encounter_details'][$encounter_id]['year']           = format_date($progression_details[1], "Y");
                $guild_details['direct_encounter_details'][$encounter_id]['screenshot']     = $progression_details[4];
                $guild_details['direct_encounter_details'][$encounter_id]['video']          = $progression_details[5];

                if ( $progression_details[6] == 0 ) $progression_details[6] = "--";
                if ( $progression_details[7] == 0 ) $progression_details[7] = "--";
                if ( $progression_details[8] == 0 ) $progression_details[8] = "--";

                $guild_details['direct_encounter_details'][$encounter_id]['server_rank']    = $progression_details[6];
                $guild_details['direct_encounter_details'][$encounter_id]['region_rank']    = $progression_details[7];
                $guild_details['direct_encounter_details'][$encounter_id]['world_rank']     = $progression_details[8];

                if ( $guild_details['region'] == "EU" ) $guild_details['direct_encounter_details'][$encounter_id]['strtotime'] = $guild_details['direct_encounter_details'][$encounter_id]['strtotime'] - ($dungeon_details['eu_diff']*3600);

                // Placing Recent Encounter First
                if ( $GLOBALS['global_encounter_array'][$encounter_id]['mob_type'] == 0 && $GLOBALS['global_dungeon_array'][$dungeon]['dungeon_type'] == 0 ) {
                    if ( !isset($guild_details['overall_details']['recent_time']) || strlen($guild_details['overall_details']['recent_time']) == 0 ) {
                        $guild_details['overall_details']['recent_time']                        = $guild_details['direct_encounter_details'][$encounter_id]['strtotime'];
                        $guild_details['overall_details']['recent']                             = "{$GLOBALS['global_encounter_array'][$encounter_id]['encounter_name']} @ ".format_date("{$progression_details[1]} {$progression_details[2]}", 'm/d/Y H:i');
                        $guild_details['overall_details']['recent_encounter']                   = $guild_details['direct_encounter_details'][$encounter_id];
                        $guild_details['dungeon_details'][$dungeon]['recent_time']              = $guild_details['direct_encounter_details'][$encounter_id]['strtotime'];
                        $guild_details['dungeon_details'][$dungeon]['recent']                   = "{$GLOBALS['global_encounter_array'][$encounter_id]['encounter_name']} @ ".format_date("{$progression_details[1]} {$progression_details[2]}", 'm/d/Y H:i');
                        $guild_details['dungeon_details'][$dungeon]['recent_encounter']         = $guild_details['direct_encounter_details'][$encounter_id];
                        $guild_details['tier_details'][$tier]['recent_time']                    = $guild_details['direct_encounter_details'][$encounter_id]['strtotime'];
                        $guild_details['tier_details'][$tier]['recent']                         = "{$GLOBALS['global_encounter_array'][$encounter_id]['encounter_name']} @ ".format_date("{$progression_details[1]} {$progression_details[2]}", 'm/d/Y H:i');
                        $guild_details['tier_details'][$tier]['recent_encounter']               = $guild_details['direct_encounter_details'][$encounter_id];
                        $guild_details['raid_size_details'][$tier][$size]['recent_time']        = $guild_details['direct_encounter_details'][$encounter_id]['strtotime'];
                        $guild_details['raid_size_details'][$tier][$size]['recent']             = "{$GLOBALS['global_encounter_array'][$encounter_id]['encounter_name']} @ ".format_date("{$progression_details[1]} {$progression_details[2]}", 'm/d/Y H:i');
                        $guild_details['raid_size_details'][$tier][$size]['recent_encounter']   = $guild_details['direct_encounter_details'][$encounter_id];                
                        $guild_details['direct_raid_size_details'][$size]['recent_time']        = $guild_details['direct_encounter_details'][$encounter_id]['strtotime'];
                        $guild_details['direct_raid_size_details'][$size]['recent']             = "{$GLOBALS['global_encounter_array'][$encounter_id]['encounter_name']} @ ".format_date("{$progression_details[1]} {$progression_details[2]}", 'm/d/Y H:i');
                        $guild_details['direct_raid_size_details'][$size]['recent_encounter']   = $guild_details['direct_encounter_details'][$encounter_id];
                    }

                    // Placing Recent Encounter For Dungeon
                    if ( $guild_details['dungeon_details'][$dungeon]['recent_time'] < $guild_details['direct_encounter_details'][$encounter_id]['strtotime'] ) {
                        $guild_details['dungeon_details'][$dungeon]['recent_time']      = $guild_details['direct_encounter_details'][$encounter_id]['strtotime'];
                        $guild_details['dungeon_details'][$dungeon]['recent']           = "{$GLOBALS['global_encounter_array'][$encounter_id]['encounter_name']} @ ".format_date("{$progression_details[1]} {$progression_details[2]}", 'm/d/Y H:i');
                        $guild_details['dungeon_details'][$dungeon]['recent_encounter'] = $guild_details['direct_encounter_details'][$encounter_id];
                    }

                    // Placing Recent Encounter For Tier
                    if ( $guild_details['tier_details'][$tier]['recent_time'] < $guild_details['direct_encounter_details'][$encounter_id]['strtotime'] ) {
                        $guild_details['tier_details'][$tier]['recent_time']        = $guild_details['direct_encounter_details'][$encounter_id]['strtotime'];
                        $guild_details['tier_details'][$tier]['recent']             = "{$GLOBALS['global_encounter_array'][$encounter_id]['encounter_name']} @ ".format_date("{$progression_details[1]} {$progression_details[2]}", 'm/d/Y H:i');
                        $guild_details['tier_details'][$tier]['recent_encounter']   = $guild_details['direct_encounter_details'][$encounter_id];

                    }

                    // Placing Recent Encounter For Raid Size Per Tier
                    if ( $guild_details['raid_size_details'][$tier][$size]['recent_time'] < $guild_details['direct_encounter_details'][$encounter_id]['strtotime'] ) {
                        $guild_details['raid_size_details'][$tier][$size]['recent_time']        = $guild_details['direct_encounter_details'][$encounter_id]['strtotime'];
                        $guild_details['raid_size_details'][$tier][$size]['recent']             = "{$GLOBALS['global_encounter_array'][$encounter_id]['encounter_name']} @ ".format_date("{$progression_details[1]} {$progression_details[2]}", 'm/d/Y H:i');
                        $guild_details['raid_size_details'][$tier][$size]['recent_encounter']   = $guild_details['direct_encounter_details'][$encounter_id];                    
                    }

                    // Placing Recent Encounter For Raid Size
                    if ( $guild_details['direct_raid_size_details'][$size]['recent_time'] < $guild_details['direct_encounter_details'][$encounter_id]['strtotime'] ) {
                        $guild_details['direct_raid_size_details'][$size]['recent_time']        = $guild_details['direct_encounter_details'][$encounter_id]['strtotime'];
                        $guild_details['direct_raid_size_details'][$size]['recent']             = "{$GLOBALS['global_encounter_array'][$encounter_id]['encounter_name']} @ ".format_date("{$progression_details[1]} {$progression_details[2]}", 'm/d/Y H:i');
                        $guild_details['direct_raid_size_details'][$size]['recent_encounter']   = $guild_details['direct_encounter_details'][$encounter_id];                    
                    }

                    // Placing Recent Encounter For Guild Overall
                    if ( $guild_details['overall_details']['recent_time'] < $guild_details['direct_encounter_details'][$encounter_id]['strtotime'] ) {
                        $guild_details['overall_details']['recent_time']        = $guild_details['direct_encounter_details'][$encounter_id]['strtotime'];
                        $guild_details['overall_details']['recent']             = "{$GLOBALS['global_encounter_array'][$encounter_id]['encounter_name']} @ ".format_date("{$progression_details[1]} {$progression_details[2]}", 'm/d/Y H:i');
                        $guild_details['overall_details']['recent_encounter']   = $guild_details['direct_encounter_details'][$encounter_id];
                    }


                }
                // Placing Default Values for Ranking Attributes: Points/Trend/Previous Rank/Current Rank
                if ( !isset($guild_details['direct_encounter_details'][$encounter_id]['points']) ) $guild_details['direct_encounter_details'][$encounter_id]['points'] = 0;
            }
        }

        // Placing Points Per Encounter/Dungeon/Tier
        if ( isset($guild_details['rank_encounter']) && strlen($guild_details['rank_encounter']) > 0 ) {
            $point_system_array = explode("$$", $guild_details['rank_encounter']);

            for ( $point_system = 0; $point_system < count($point_system_array); $point_system++ ) {
                $progression_details_array = explode("~~", $point_system_array[$point_system]);

                for ( $encounter = 0; $encounter < count($progression_details_array); $encounter++ ) {
                    $progression_details = explode("||", $progression_details_array[$encounter]);

                    $encounter_details = $GLOBALS['global_encounter_array'][$progression_details[0]];

                    $tier           = $encounter_details['tier'];
                    $size           = $encounter_details['players'];
                    $dungeon        = $encounter_details['dungeon_id'];
                    $encounter_id   = $encounter_details['encounter_id'];
                    $rank_details   = explode("&&", $progression_details[2]);

                    $guild_details['direct_encounter_details'][$encounter_id][$point_system]['points']          = $progression_details[1];

                    // Doubling up here with Active/Legacy, Legacy will get additional line, active will be standard
                    $guild_details['direct_encounter_details'][$encounter_id][$point_system]['world']['rank']   = $rank_details[0];
                    $guild_details['direct_encounter_details'][$encounter_id][$point_system]['region']['rank']  = $rank_details[1];
                    $guild_details['direct_encounter_details'][$encounter_id][$point_system]['server']['rank']  = $rank_details[2];

                    if ( isset($rank_details[3]) ) $guild_details['direct_encounter_details'][$encounter_id][$point_system]['world']['legacy_rank']   = $rank_details[3];
                    if ( isset($rank_details[4]) ) $guild_details['direct_encounter_details'][$encounter_id][$point_system]['region']['legacy_rank']  = $rank_details[4];
                    if ( isset($rank_details[5]) ) $guild_details['direct_encounter_details'][$encounter_id][$point_system]['server']['legacy_rank']  = $rank_details[5];
                }
            }           
        }

        // Placing Trend/Previous Rank/Current Rank for Tier
        if ( isset($guild_details['rank_tier']) && strlen($guild_details['rank_tier']) > 0 ) {
            $point_system_array = explode("$$", $guild_details['rank_tier']);

            for ( $point_system = 0; $point_system < count($point_system_array); $point_system++ ) {
                $tier_array = explode("~~", $point_system_array[$point_system]);

                for ( $tier_count = 0; $tier_count < count($tier_array); $tier_count++ ) {
                    $tier_details = explode("||", $tier_array[$tier_count]);

                    $tier           = $tier_details[0];
                    $rank_details   = explode("&&", $tier_details[2]);
                    $prev_details   = explode("&&", $tier_details[3]);
                    $trend_details  = explode("&&", $tier_details[4]);

                    $guild_details['tier_details'][$tier][$point_system]['points']              = $tier_details[1];

                    // Doubling up here with Active/Legacy, Legacy will get additional line, active will be standard
                    $guild_details['tier_details'][$tier][$point_system]['world']['rank']       = $rank_details[0];
                    $guild_details['tier_details'][$tier][$point_system]['region']['rank']      = $rank_details[1];
                    $guild_details['tier_details'][$tier][$point_system]['server']['rank']      = $rank_details[2];
                    $guild_details['tier_details'][$tier][$point_system]['world']['prev_rank']  = $prev_details[0];
                    $guild_details['tier_details'][$tier][$point_system]['region']['prev_rank'] = $prev_details[1];
                    $guild_details['tier_details'][$tier][$point_system]['server']['prev_rank'] = $prev_details[2];
                    $guild_details['tier_details'][$tier][$point_system]['world']['trend']      = $trend_details[0];
                    $guild_details['tier_details'][$tier][$point_system]['region']['trend']     = $trend_details[1];
                    $guild_details['tier_details'][$tier][$point_system]['server']['trend']     = $trend_details[2];

                    if ( isset($rank_details[3]) ) $guild_details['tier_details'][$tier][$point_system]['world']['legacy_rank']       = $rank_details[3];
                    if ( isset($rank_details[4]) ) $guild_details['tier_details'][$tier][$point_system]['region']['legacy_rank']      = $rank_details[4];
                    if ( isset($rank_details[5]) ) $guild_details['tier_details'][$tier][$point_system]['server']['legacy_rank']      = $rank_details[5];
                    if ( isset($prev_details[3]) ) $guild_details['tier_details'][$tier][$point_system]['world']['legacy_prev_rank']  = $prev_details[3];
                    if ( isset($prev_details[4]) ) $guild_details['tier_details'][$tier][$point_system]['region']['legacy_prev_rank'] = $prev_details[4];
                    if ( isset($prev_details[5]) ) $guild_details['tier_details'][$tier][$point_system]['server']['legacy_prev_rank'] = $prev_details[5];
                    if ( isset($trend_details[3]) ) $guild_details['tier_details'][$tier][$point_system]['world']['legacy_trend']      = $trend_details[3];
                    if ( isset($trend_details[4]) ) $guild_details['tier_details'][$tier][$point_system]['region']['legacy_trend']     = $trend_details[4];
                    if ( isset($trend_details[5]) ) $guild_details['tier_details'][$tier][$point_system]['server']['legacy_trend']     = $trend_details[5];
                }   
            }       
        }

        // Placing Trend/Previous Rank/Current Rank for Dungeon
        if ( isset($guild_details['rank_dungeon']) && strlen($guild_details['rank_dungeon']) > 0 ) {
            $point_system_array = explode("$$", $guild_details['rank_dungeon']);

            for ( $point_system = 0; $point_system < count($point_system_array); $point_system++ ) {
                $dungeon_array = explode("~~", $point_system_array[$point_system]);

                for ( $dungeon = 0; $dungeon < count($dungeon_array); $dungeon++ ) {
                    $dungeon_details = explode("||", $dungeon_array[$dungeon]);

                    $dungeon_id     = $dungeon_details[0];
                    $rank_details   = explode("&&", $dungeon_details[2]);
                    $prev_details   = explode("&&", $dungeon_details[3]);
                    $trend_details  = explode("&&", $dungeon_details[4]);

                    $guild_details['dungeon_details'][$dungeon_id][$point_system]['points']                 = $dungeon_details[1];

                    // Doubling up here with Active/Legacy, Legacy will get additional line, active will be standard
                    $guild_details['dungeon_details'][$dungeon_id][$point_system]['world']['rank']          = $rank_details[0];
                    $guild_details['dungeon_details'][$dungeon_id][$point_system]['region']['rank']         = $rank_details[1];
                    $guild_details['dungeon_details'][$dungeon_id][$point_system]['server']['rank']         = $rank_details[2];
                    $guild_details['dungeon_details'][$dungeon_id][$point_system]['world']['prev_rank']     = $prev_details[0];
                    $guild_details['dungeon_details'][$dungeon_id][$point_system]['region']['prev_rank']    = $prev_details[1];
                    $guild_details['dungeon_details'][$dungeon_id][$point_system]['server']['prev_rank']    = $prev_details[2];
                    $guild_details['dungeon_details'][$dungeon_id][$point_system]['world']['trend']         = $trend_details[0];
                    $guild_details['dungeon_details'][$dungeon_id][$point_system]['region']['trend']        = $trend_details[1];
                    $guild_details['dungeon_details'][$dungeon_id][$point_system]['server']['trend']        = $trend_details[2];

                    if ( isset($rank_details[3]) ) $guild_details['dungeon_details'][$dungeon_id][$point_system]['world']['legacy_rank']          = $rank_details[3];
                    if ( isset($rank_details[4]) ) $guild_details['dungeon_details'][$dungeon_id][$point_system]['region']['legacy_rank']         = $rank_details[4];
                    if ( isset($rank_details[5]) ) $guild_details['dungeon_details'][$dungeon_id][$point_system]['server']['legacy_rank']         = $rank_details[5];
                    if ( isset($prev_details[3]) ) $guild_details['dungeon_details'][$dungeon_id][$point_system]['world']['legacy_prev_rank']     = $prev_details[3];
                    if ( isset($prev_details[4]) ) $guild_details['dungeon_details'][$dungeon_id][$point_system]['region']['legacy_prev_rank']    = $prev_details[4];
                    if ( isset($prev_details[5]) ) $guild_details['dungeon_details'][$dungeon_id][$point_system]['server']['legacy_prev_rank']    = $prev_details[5];
                    if ( isset($trend_details[3]) ) $guild_details['dungeon_details'][$dungeon_id][$point_system]['world']['legacy_trend']         = $trend_details[3];
                    if ( isset($trend_details[4]) ) $guild_details['dungeon_details'][$dungeon_id][$point_system]['region']['legacy_trend']        = $trend_details[4];
                    if ( isset($trend_details[5]) ) $guild_details['dungeon_details'][$dungeon_id][$point_system]['server']['legacy_trend']        = $trend_details[5];
                }   
            }       
        }

        // Placing Trend/Previous Rank/Current Rank for Raid_size
        if ( isset($guild_details['rank_size']) && strlen($guild_details['rank_size']) > 0 ) {
            $point_system_array = explode("$$", $guild_details['rank_size']);

            for ( $point_system = 0; $point_system < count($point_system_array); $point_system++ ) {
                $raid_size_array = explode("~~", $point_system_array[$point_system]);

                for ( $size = 0; $size < count($raid_size_array); $size++ ) {
                    $raid_size_details = explode("||", $raid_size_array[$size]);

                    $raid_size      = $raid_size_details[0];
                    $rank_details   = explode("&&", $raid_size_details[2]);
                    $prev_details   = explode("&&", $raid_size_details[3]);
                    $trend_details  = explode("&&", $raid_size_details[4]);

                    $guild_details['direct_raid_size_details'][$raid_size][$point_system]['points']                 = $raid_size_details[1];

                    // Doubling up here with Active/Legacy, Legacy will get additional line, active will be standard
                    $guild_details['direct_raid_size_details'][$raid_size][$point_system]['world']['rank']          = $rank_details[0];
                    $guild_details['direct_raid_size_details'][$raid_size][$point_system]['region']['rank']         = $rank_details[1];
                    $guild_details['direct_raid_size_details'][$raid_size][$point_system]['server']['rank']         = $rank_details[2];
                    $guild_details['direct_raid_size_details'][$raid_size][$point_system]['world']['prev_rank']     = $prev_details[0];
                    $guild_details['direct_raid_size_details'][$raid_size][$point_system]['region']['prev_rank']    = $prev_details[1];
                    $guild_details['direct_raid_size_details'][$raid_size][$point_system]['server']['prev_rank']    = $prev_details[2];
                    $guild_details['direct_raid_size_details'][$raid_size][$point_system]['world']['trend']         = $trend_details[0];
                    $guild_details['direct_raid_size_details'][$raid_size][$point_system]['region']['trend']        = $trend_details[1];
                    $guild_details['direct_raid_size_details'][$raid_size][$point_system]['server']['trend']        = $trend_details[2];

                    if ( isset($rank_details[3]) ) $guild_details['direct_raid_size_details'][$raid_size][$point_system]['world']['legacy_rank']          = $rank_details[3];
                    if ( isset($rank_details[4]) ) $guild_details['direct_raid_size_details'][$raid_size][$point_system]['region']['legacy_rank']         = $rank_details[4];
                    if ( isset($rank_details[5]) ) $guild_details['direct_raid_size_details'][$raid_size][$point_system]['server']['legacy_rank']         = $rank_details[5];
                    if ( isset($prev_details[3]) ) $guild_details['direct_raid_size_details'][$raid_size][$point_system]['world']['legacy_prev_rank']     = $prev_details[3];
                    if ( isset($prev_details[4]) ) $guild_details['direct_raid_size_details'][$raid_size][$point_system]['region']['legacy_prev_rank']    = $prev_details[4];
                    if ( isset($prev_details[5]) ) $guild_details['direct_raid_size_details'][$raid_size][$point_system]['server']['legacy_prev_rank']    = $prev_details[5];
                    if ( isset($trend_details[3]) ) $guild_details['direct_raid_size_details'][$raid_size][$point_system]['world']['legacy_trend']         = $trend_details[3];
                    if ( isset($trend_details[4]) ) $guild_details['direct_raid_size_details'][$raid_size][$point_system]['region']['legacy_trend']        = $trend_details[4];
                    if ( isset($trend_details[5]) ) $guild_details['direct_raid_size_details'][$raid_size][$point_system]['server']['legacy_trend']        = $trend_details[5];
                }   
            }       
        }

        // Placing Trend/Previous Rank/Current Rank for Tier Size
        if ( isset($guild_details['rank_tier_size']) && strlen($guild_details['rank_tier_size']) > 0 ) {
            $point_system_array = explode("$$", $guild_details['rank_tier_size']);

            for ( $point_system = 0; $point_system < count($point_system_array); $point_system++ ) {
                $raid_size_array = explode("~~", $point_system_array[$point_system]);

                for ( $tier_size = 0; $tier_size < count($raid_size_array); $tier_size++ ) {
                    $tier_size_details = explode("||", $raid_size_array[$tier_size]);

                    $tier           = $tier_size_details[0];
                    $raid_size      = $tier_size_details[1];
                    $rank_details   = explode("&&", $tier_size_details[3]);
                    $prev_details   = explode("&&", $tier_size_details[4]);
                    $trend_details  = explode("&&", $tier_size_details[5]);

                    $guild_details['raid_size_details'][$tier][$raid_size][$point_system]['points']                 = $tier_size_details[2];

                    // Doubling up here with Active/Legacy, Legacy will get additional line, active will be standard
                    $guild_details['raid_size_details'][$tier][$raid_size][$point_system]['world']['rank']          = $rank_details[0];
                    $guild_details['raid_size_details'][$tier][$raid_size][$point_system]['region']['rank']         = $rank_details[1];
                    $guild_details['raid_size_details'][$tier][$raid_size][$point_system]['server']['rank']         = $rank_details[2];
                    $guild_details['raid_size_details'][$tier][$raid_size][$point_system]['world']['prev_rank']     = $prev_details[0];
                    $guild_details['raid_size_details'][$tier][$raid_size][$point_system]['region']['prev_rank']    = $prev_details[1];
                    $guild_details['raid_size_details'][$tier][$raid_size][$point_system]['server']['prev_rank']    = $prev_details[2];
                    $guild_details['raid_size_details'][$tier][$raid_size][$point_system]['world']['trend']         = $trend_details[0];
                    $guild_details['raid_size_details'][$tier][$raid_size][$point_system]['region']['trend']        = $trend_details[1];
                    $guild_details['raid_size_details'][$tier][$raid_size][$point_system]['server']['trend']        = $trend_details[2];

                    if ( isset($rank_details[3]) ) $guild_details['raid_size_details'][$tier][$raid_size][$point_system]['world']['legacy_rank']          = $rank_details[3];
                    if ( isset($rank_details[4]) ) $guild_details['raid_size_details'][$tier][$raid_size][$point_system]['region']['legacy_rank']         = $rank_details[4];
                    if ( isset($rank_details[5]) ) $guild_details['raid_size_details'][$tier][$raid_size][$point_system]['server']['legacy_rank']         = $rank_details[5];
                    if ( isset($prev_details[3]) ) $guild_details['raid_size_details'][$tier][$raid_size][$point_system]['world']['legacy_prev_rank']     = $prev_details[3];
                    if ( isset($prev_details[4]) ) $guild_details['raid_size_details'][$tier][$raid_size][$point_system]['region']['legacy_prev_rank']    = $prev_details[4];
                    if ( isset($prev_details[5]) ) $guild_details['raid_size_details'][$tier][$raid_size][$point_system]['server']['legacy_prev_rank']    = $prev_details[5];
                    if ( isset($trend_details[3]) ) $guild_details['raid_size_details'][$tier][$raid_size][$point_system]['world']['legacy_trend']         = $trend_details[3];
                    if ( isset($trend_details[4]) ) $guild_details['raid_size_details'][$tier][$raid_size][$point_system]['region']['legacy_trend']        = $trend_details[4];
                    if ( isset($trend_details[5]) ) $guild_details['raid_size_details'][$tier][$raid_size][$point_system]['server']['legacy_trend']        = $trend_details[5];
                }   
            }       
        }

        //Placing Trend/Previous Rank/Current Rank for Overall
        if ( isset($guild_details['rank_overall']) && strlen($guild_details['rank_overall']) > 0 ) {
            $point_system_array = explode("$$", $guild_details['rank_overall']);

            for ( $point_system = 0; $point_system < count($point_system_array); $point_system++ ) {
                $overall_details = explode("||", $point_system_array[$point_system]);

                $rank_details   = explode("&&", $overall_details[1]);
                $prev_details   = explode("&&", $overall_details[2]);
                $trend_details  = explode("&&", $overall_details[3]);

                $guild_details['overall_details'][$point_system]['points']              = $overall_details[0];

                // Doubling up here with Active/Legacy, Legacy will get additional line, active will be standard
                $guild_details['overall_details'][$point_system]['world']['rank']       = $rank_details[0];
                $guild_details['overall_details'][$point_system]['region']['rank']      = $rank_details[1];
                $guild_details['overall_details'][$point_system]['server']['rank']      = $rank_details[2];
                $guild_details['overall_details'][$point_system]['world']['prev_rank']  = $prev_details[0];
                $guild_details['overall_details'][$point_system]['region']['prev_rank'] = $prev_details[1];
                $guild_details['overall_details'][$point_system]['server']['prev_rank'] = $prev_details[2];
                $guild_details['overall_details'][$point_system]['world']['trend']      = $trend_details[0];
                $guild_details['overall_details'][$point_system]['region']['trend']     = $trend_details[1];
                $guild_details['overall_details'][$point_system]['server']['trend']     = $trend_details[2];

                if ( isset($rank_details[3]) ) $guild_details['overall_details'][$point_system]['world']['legacy_rank']       = $rank_details[3];
                if ( isset($rank_details[4]) ) $guild_details['overall_details'][$point_system]['region']['legacy_rank']      = $rank_details[4];
                if ( isset($rank_details[5]) ) $guild_details['overall_details'][$point_system]['server']['legacy_rank']      = $rank_details[5];
                if ( isset($prev_details[3]) ) $guild_details['overall_details'][$point_system]['world']['legacy_prev_rank']  = $prev_details[3];
                if ( isset($prev_details[4]) ) $guild_details['overall_details'][$point_system]['region']['legacy_prev_rank'] = $prev_details[4];
                if ( isset($prev_details[5]) ) $guild_details['overall_details'][$point_system]['server']['legacy_prev_rank'] = $prev_details[5];
                if ( isset($trend_details[3]) ) $guild_details['overall_details'][$point_system]['world']['legacy_trend']      = $trend_details[3];
                if ( isset($trend_details[4]) ) $guild_details['overall_details'][$point_system]['region']['legacy_trend']     = $trend_details[4];
                if ( isset($trend_details[5]) ) $guild_details['overall_details'][$point_system]['server']['legacy_trend']     = $trend_details[5];
            }       
        }

        foreach ( $GLOBALS['global_tier_array'] as $tier => $tier_details ) {
            if ( isset($guild_details['tier_details'][$tier]) ) {
                foreach ( $GLOBALS['raid_size_array'] as $size => $raid_size ) {
                    if ( !isset($total_encounters_per_size_array[$raid_size]['encounters']) ) $total_encounters_per_size_array[$raid_size]['encounters'] = 0;

                    if ( isset($guild_details['raid_size_details'][$tier][$raid_size]) ) {
                        foreach ( $GLOBALS['global_dungeon_array'] as $dungeon_id => $dungeon_details ) {
                            if ( $dungeon_details['players'] != $raid_size ) continue;
                            if ( isset($guild_details['dungeon_details'][$dungeon_id]) && $dungeon_details['tier'] == $tier ) {
                                foreach ( $GLOBALS['global_encounter_array'] as $encounter_id => $encounter_details ) {
                                    if ( $GLOBALS['global_encounter_array'][$encounter_id]['dungeon_id'] != $dungeon_id || $GLOBALS['global_encounter_array'][$encounter_id]['tier'] != $tier ) continue;

                                    if ( isset($guild_details['direct_encounter_details'][$encounter_id]) && $GLOBALS['global_encounter_array'][$encounter_id]['mob_type'] == 0 ) {
                                        $guild_details['dungeon_details'][$dungeon_id]['complete']++;

                                        if ( $dungeon_details['dungeon_type'] == 0 ) $guild_details['raid_size_details'][$tier][$raid_size]['complete']++;
                                        if ( $dungeon_details['dungeon_type'] == 0 ) $guild_details['direct_raid_size_details'][$raid_size]['complete']++;
                                        if ( $dungeon_details['dungeon_type'] == 0 ) $guild_details['tier_details'][$tier]['complete']++;
                                        if ( $dungeon_details['dungeon_type'] == 0 ) $guild_details['overall_details']['complete']++;       

                                        if ( $guild_details['direct_encounter_details'][$encounter_id]['world_rank'] == 1 ) {
                                            $guild_details['dungeon_details'][$dungeon_id]['world_first']++;
                                            $guild_details['raid_size_details'][$tier][$raid_size]['world_first']++;
                                            $guild_details['direct_raid_size_details'][$raid_size]['world_first']++;
                                            $guild_details['tier_details'][$tier]['world_first']++;
                                            $guild_details['overall_details']['world_first']++;
                                        }

                                        if ( $guild_details['direct_encounter_details'][$encounter_id]['region_rank'] == 1 ) {
                                            $guild_details['dungeon_details'][$dungeon_id]['region_first']++;
                                            $guild_details['raid_size_details'][$tier][$raid_size]['region_first']++;
                                            $guild_details['direct_raid_size_details'][$raid_size]['region_first']++;
                                            $guild_details['tier_details'][$tier]['region_first']++;
                                            $guild_details['overall_details']['region_first']++;
                                        }

                                        if ( $guild_details['direct_encounter_details'][$encounter_id]['server_rank'] == 1 ) {
                                            $guild_details['dungeon_details'][$dungeon_id]['server_first']++;
                                            $guild_details['raid_size_details'][$tier][$raid_size]['server_first']++;
                                            $guild_details['direct_raid_size_details'][$raid_size]['server_first']++;
                                            $guild_details['tier_details'][$tier]['server_first']++;
                                            $guild_details['overall_details']['server_first']++;
                                        }
                                    }

                                    if ( isset($guild_details['direct_encounter_details'][$encounter_id]) && $GLOBALS['global_encounter_array'][$encounter_id]['mob_type'] == 2 ) {
                                        $guild_details['dungeon_details'][$dungeon_id]['special_encounter_2_complete']++;
                                        $guild_details['raid_size_details'][$tier][$raid_size]['special_encounter_2_complete']++;
                                        $guild_details['direct_raid_size_details'][$raid_size]['special_encounter_2_complete']++;
                                        $guild_details['tier_details'][$tier]['special_encounter_2_complete']++;
                                        $guild_details['overall_details']['special_encounter_2_complete']++;
                                    }

                                    if ( isset($guild_details['direct_encounter_details'][$encounter_id]) && $GLOBALS['global_encounter_array'][$encounter_id]['mob_type'] == 1 ) {
                                        $guild_details['dungeon_details'][$dungeon_id]['special_encounter_1_complete']++;
                                        $guild_details['raid_size_details'][$tier][$raid_size]['special_encounter_1_complete']++;
                                        $guild_details['direct_raid_size_details'][$raid_size]['special_encounter_1_complete']++;
                                        $guild_details['tier_details'][$tier]['special_encounter_1_complete']++;
                                        $guild_details['overall_details']['special_encounter_1_complete']++;
                                    }
                                }

                                $dungeon_encounters     = $dungeon_details['mobs'] + $dungeon_details['special_encounters'];
                                $dungeon_total_complete = $guild_details['dungeon_details'][$dungeon_id]['complete'] + $guild_details['dungeon_details'][$dungeon_id]['special_encounter_2_complete'];

                                $guild_details['dungeon_details'][$dungeon_id]['standing']              = "{$guild_details['dungeon_details'][$dungeon_id]['complete']}/{$dungeon_details['mobs']}";
                                $guild_details['dungeon_details'][$dungeon_id]['pct']                   = number_format(($dungeon_total_complete / $dungeon_encounters) * 100, 2, ".",",")."%"; 

                                // Optional standings
                                $guild_details['dungeon_details'][$dungeon_id]['special_encounter_2']   = "{$guild_details['dungeon_details'][$dungeon_id]['special_encounter_2_complete']}/{$dungeon_details['special_encounters']}";
                            }   
                        }           

                        $raid_size_encounters       = $total_encounters_per_tier_size_array[$tier][$raid_size]['encounters'] + $total_encounters_per_tier_size_array[$tier][$raid_size]['special_encounters'];
                        $raid_size_total_complete   = $guild_details['raid_size_details'][$tier][$raid_size]['complete'] + $guild_details['raid_size_details'][$tier][$raid_size]['special_encounter_2_complete'];

                        $guild_details['raid_size_details'][$tier][$raid_size]['standing'] = "{$guild_details['raid_size_details'][$tier][$raid_size]['complete']}/{$total_encounters_per_tier_size_array[$tier][$raid_size]['encounters']}";
                        //$guild_details['raid_size_details'][$tier][$raid_size]['pct'] = number_format(($raid_size_total_complete / $raid_size_encounters) * 100, 2, ".",",")."%";
                    
                        $guild_details['direct_raid_size_details'][$raid_size]['standing'] = "{$guild_details['direct_raid_size_details'][$raid_size]['complete']}/{$total_encounters_per_size_array[$raid_size]['encounters']}";
                        //$guild_details['direct_raid_size_details'][$raid_size]['pct'] = number_format(($guild_details['direct_raid_size_details'][$raid_size]['complete'] / $raid_size_encounters) * 100, 2, ".",",")."%";
                    
                        // Optional standings
                        $guild_details['raid_size_details'][$tier][$raid_size]['special_encounter_2'] = "{$guild_details['raid_size_details'][$tier][$raid_size]['special_encounter_2_complete']}/{$total_encounters_per_tier_size_array[$tier][$raid_size]['special_encounters']}";
                        $guild_details['direct_raid_size_details'][$raid_size]['special_encounter_2'] = "{$guild_details['direct_raid_size_details'][$raid_size]['special_encounter_2_complete']}/{$total_encounters_per_size_array[$raid_size]['special_encounters']}";                      
                    }   
                }
                
                if ( isset($guild_details['tier_details'][$tier]['progression_overall']) && $guild_details['tier_details'][$tier]['complete'] > 0 ) {
                    unset($guild_details['tier_details'][$tier]['progression_overall']);
                
                    foreach ( $GLOBALS['global_dungeon_array'] as $dungeon_id => $dungeon_details ) {
                        if ( $dungeon_details['tier'] != $tier || $dungeon_details['dungeon_type'] == 1 ) continue;
                
                        if ( isset($guild_details['tier_details'][$tier]['progression_overall']) ) {
                            $guild_details['tier_details'][$tier]['progression_overall'] .= ", {$guild_details['dungeon_details'][$dungeon_id]['standing']} {$dungeon_details['abbreviation']}";
                        } else {
                            $guild_details['tier_details'][$tier]['progression_overall']  = "{$guild_details['dungeon_details'][$dungeon_id]['standing']} {$dungeon_details['abbreviation']}";
                        }
                
                        if ( isset($guild_details['tier_details'][$tier]['progression_size'][$dungeon_details['players']]) ) {
                            $guild_details['tier_details'][$tier]['progression_size'][$dungeon_details['players']] .= ", {$guild_details['dungeon_details'][$dungeon_id]['standing']} {$dungeon_details['abbreviation']}";
                        } else {
                            $guild_details['tier_details'][$tier]['progression_size'][$dungeon_details['players']]  = "{$guild_details['dungeon_details'][$dungeon_id]['standing']} {$dungeon_details['abbreviation']}";
                        }
                    }
                }

                $tier_encounters        = $tier_details['encounters'] + $tier_details['special_encounters'];
                $tier_total_complete    = $guild_details['tier_details'][$tier]['complete'] + $guild_details['tier_details'][$tier]['special_encounter_2_complete'];

                $guild_details['tier_details'][$tier]['standing']               = "{$guild_details['tier_details'][$tier]['complete']}/{$tier_details['encounters']}";
                $guild_details['tier_details'][$tier]['pct']                    = number_format(($tier_total_complete / $tier_encounters) * 100, 2, ".",",")."%";
            
                // Optional standings
                $guild_details['tier_details'][$tier]['special_encounter_2']    = "{$guild_details['tier_details'][$tier]['special_encounter_2_complete']}/{$tier_details['special_encounters']}";
            }                   
        }

        $total_encounters_array                         = array();
        $total_encounters_array['encounters']           = 0;
        $total_encounters_array['special_encounters']   = 0;
        $total_encounters_complete                      = $guild_details['overall_details']['complete'] + $guild_details['overall_details']['special_encounter_2_complete'];

        foreach ( $GLOBALS['raid_size_array'] as $size => $raid_size ) {
            $total_encounters_array['encounters']           += $total_encounters_per_size_array[$raid_size]['encounters'];
            $total_encounters_array['special_encounters']   += $total_encounters_per_size_array[$raid_size]['special_encounters'];
        }

        $guild_details['overall_details']['standing'] = "{$guild_details['overall_details']['complete']}/{$total_encounters_array['encounters']}";
        $guild_details['overall_details']['pct'] = number_format(($total_encounters_complete / ($total_encounters_array['encounters'] + $total_encounters_array['special_encounters'])) * 100, 2, ".",",")."%";

        // Optional standings
        $guild_details['overall_details']['special_encounter_2']    = "{$guild_details['overall_details']['special_encounter_2_complete']}/{$total_encounters_array['special_encounters']}";

        return $guild_details;
    }

    function get_guild_count() {
        return count($GLOBALS['global_guild_array']);
    }

    function get_user_count() {
        return count($GLOBALS['global_email_array']);
    }

    function get_parent_raid_teams($guild_details, $guild_id) {
        $query_string = "";

        $guild_id_array = explode("||", $guild_details['child']);        

        for ( $count = 0; $count < count($guild_id_array); $count++ ) {
            if ( $guild_id_array[$count] == $guild_id ) continue;

            if ( strlen($query_string) == 0 ) {
                $query_string = $guild_id_array[$count];
            } else {
                $query_string .= "||{$guild_id_array[$count]}";
            }
        }

        return $query_string;
    }

    /*****DATA SUBMISSION*****/
    function update_kill_details($kill_details, $screenshot, $type) {
        if ( !isset($kill_details['encounter_id']) ) {
            draw_message_banner("Encounter Error", "You may want to select an encounter to do the whole submitting thing! It may help a bit.");
        }

        $encounter_id               = $kill_details['encounter_id'];
        $guild_id                   = $kill_details['guild_id'];
        $encounter_details          = get_encounter_details($encounter_id);
        $guild_details              = get_guild_details($guild_id);

        $kill_details['date']       = "{$kill_details['time_day']}-{$kill_details['time_month']}-{$kill_details['time_year']}";
        $kill_details['db_date']    = "{$kill_details['time_year']}-{$kill_details['time_month']}-{$kill_details['time_day']}";
        $kill_details['time']       = "{$kill_details['time_hour']}:{$kill_details['time_minute']}";

        $current_date_strtotime     = strtotime("now") + 86400; // Padding time to ensure submission can complete
        $full_date                  = "{$kill_details['date']} {$kill_details['time']}";
        $full_date_strtotime        = strtotime($full_date);
        $kill_details['strtotime']  = $full_date_strtotime;
        $launch_date_strtotime      = strtotime($encounter_details['date_launch']);

        $valid_req_kill             = 0;
        $valid_kill                 = 0;
        $valid_kill_date            = 0;
        $valid_screenshot           = 0;
        $req_kill_string            = "";
        $image_path                 = "";       

        if ( isset($guild_details['direct_encounter_details'][$encounter_id]) || $encounter_id == "" || $encounter_id == 0 ) { // Check If Kill Exists 1 - It is already submitted
            draw_message_banner("Submission Error", "Look, we know you're excited about your new shiny kill, telling us once is all that is needed!");
            log_entry(0, "${$guild_details['name']} attempted to upload an encounter that already exists. {$guild_details['direct_encounter_details'][$encounter_id]['encounter_name']}");
            return;
        }
            
        if ( validate_submission_time($current_date_strtotime, $full_date_strtotime, $launch_date_strtotime) == 1 ) { // Check If Date Is Valid.  1 - It is Invalid
            draw_message_banner("Invalid Kill Date", "Please double check your kill time submission. The time you submitted occured before the mob was launched on: ".format_date($encounter_details['date_launch'], 'm-d-Y'). " or maybe you are predicting a future kill on ".format_date($kill_details['date'], 'm-d-Y')." maybe?");
            return;
        }

        /*
        // Checking if Time zone is there!
        if ( !isset($kill_details['time_zone']) || $kill_details['time_zone'] == "" ) {
            draw_message_banner("Invalid Timezone", "Please input a valid timezone! If you need help, try clicking here <a href='http://whatismytimezone.com/' target='_blank'></a>");
            return;            
        }
        */

        // Required Encounter Check
        if ( $GLOBALS['require_encounters'] == 1 ) {
            if ( isset($encounter_details['req_encounter']) && strlen($encounter_details['req_encounter']) > 0 ) {
                $required_encounter_array = explode("~~", $encounter_details['req_encounter']);

                for ( $count = 0; $count < count($required_encounter_array); $count++ ) {
                    $req_encounter_id = $required_encounter_array[$count];

                    if ( !isset($guild_details['direct_encounter_details'][$req_encounter_id]) ) {
                        $valid_req_kill = 1;

                        $req_kill_string .= "<br>{$GLOBALS['global_encounter_array'][$req_encounter_id]['encounter_name']}";
                    }
                }
            }

            if ( $valid_req_kill == 1 ) {
                draw_message_banner("Missing Required Encounters", "If you somehow managed to complete this encounter then you may want to check these encounters first and get them uploaded:<br>$req_kill_string");
                return;
            }
        }
        
        // Required Screenshot Check
        if ( $GLOBALS['require_screenshot'] == 1 ) { // Check If Screenshot is Valid. 1 - It is Invalid
            if ( isset($screenshot['file']['name']) || strlen($screenshot['file']['name']) > 0 ) {
                $valid_screenshot = validate_image($screenshot); 
            } else {
                $valid_screenshot = 1;
            }

            if ( $valid_screenshot == 1 ) {
                draw_message_banner("Screenshot Error", "Your submission could not be completed as you are required a valid screenshot to verify your accomplishment!");
                return;
            }
        }

        // Everything is successful. Upload the kill!
        if ( isset($screenshot['file']) && strlen($screenshot['file']['name']) > 0 ) {
            $image_path = "{$_SERVER['DOCUMENT_ROOT']}{$GLOBALS['fold_guild_screenshots']}{$guild_id}-{$encounter_id}"; 
            
            log_entry(0, "Attempting to upload screenshot to $image_path");

            if ( move_uploaded_file($screenshot['file']['tmp_name'], $image_path) ) {
                log_entry(0, "Image uploaded successfully!");
            } else {
                log_entry(0, "Image upload unsuccessful!");
            }

            $image_path = "{$guild_id}-{$encounter_id}";
        }
        
        $path_string = "{$_SERVER['DOCUMENT_ROOT']}{$GLOBALS['fold_guild_screenshots']}{$guild_id}-{$encounter_id}";
        
        if ( !file_exists($path_string) ) $image_path = "";

        if ( $guild_details['active'] == 1 || $guild_details['active'] == "Active" ) {
            insert_kill_details($guild_details, $encounter_details, $kill_details, $image_path);
            insert_recent_activity($guild_details, $encounter_details, $kill_details);
            
            draw_message_banner("Success", "Your kill shot has been submitted successfully! Your guild's progression has been updated accordingly!"); 
        } else {
            draw_message_banner("Error", "Your guild is currently marked as inactive. Please contact the administrator to restore your active status."); 
        }          
    }

    /*****GLOBAL GLOSSARY BLOCK*****/
    function block_draw_global_glossary($module) {
        $block_title = generate_block_title("Glossary");

        echo "<div class='side_block'>";
            echo "<div class='block_title'>$block_title</div>";
            foreach ( $GLOBALS[$module]['glossary'] as $abbrev => $definition ) {
                echo "<div class='side_block_content'>";
                    echo "<div class='side_block_content_right'>"; 
                        echo "<div class='small'><div class='medium_text'>$definition</div></div>";
                    echo "</div>";
                    echo "<div class='side_block_content_left'>"; 
                        echo "<div class='small'><div class='side_title'>$abbrev</div></div>";
                    echo "</div>";
                    echo "<div class='clear'></div>";
                echo "</div>";
                echo "<div class='clear'></div>";
            }
        echo "</div>";      
    }

    /*****GLOBAL SITE STATS BLOCK*****/
    function block_draw_site_stats() {
        $block_title = generate_block_title("Site Data");

        $tier_array = array();
        $guild_count = $user_count = $kill_count = 0;

        $query = get_all_tiers($GLOBALS['latest_tier']);
        while ($row = mysql_fetch_array($query)) { $tier_array[$row['tier']] = $row; }

        $guild_count    = get_guild_count();
        $user_count     = get_user_count();

        echo "<div class='side_block'>";
            echo "<div class='block_title'>$block_title</div>";

            echo "<div class='side_block_content'>";
                echo "<div class='small_text'>Come help us grow the {$GLOBALS['game_name_1']} raiding community!</div>";
                echo "<div class='clear'></div><br>";
                echo "Content Tiers";
                echo "<div class='clear'></div><br>";

                foreach ( $tier_array as $tier => $tier_details ) {
                    echo "<div class='side_title'>Tier $tier - {$tier_details['title']}</div>";
                    echo "<div class='clear'></div><br>";             
                }

                echo "<div class='side_block_content_left side_title'>Registered Users</div>";
                echo "<div class='side_block_content_right small'>$user_count</div>";
                echo "<div class='clear'></div>";
                echo "<div class='side_block_content_left side_title'>Registered Guilds</div>";
                echo "<div class='side_block_content_right small'>$guild_count</div>";
                echo "<div class='clear'></div>";
            echo "</div>";
        echo "</div>";
    }

    /*****GLOBAL NEWEST GUILDS BLOCK*****/
    function block_draw_newest_guilds() {
        $block_title = generate_block_title("Newest Guilds");

        $guild_array = array();

        $query = get_newest_guilds();
        while ($row = mysql_fetch_array($query)) { $guild_array[$row['guild_id']] = $row; }
                
        echo "<div class='side_block'>";
            echo "<div class='block_title'>$block_title</div>";

            foreach ( $guild_array as $guild_id => $guild_details ) {
                $guild_details = generate_table_fields($guild_details, 20);

                echo "<div class='side_block_content'>";
                    echo "<div class='side_block_content_right'>"; 
                        echo "<div class='small'><div class='small_text'>{$guild_details['server_name']}</div></div>";
                    echo "</div>";
                    echo "<div class='side_block_content_left'>"; 
                        echo "<div class='small'><div class='side_title'>{$guild_details['name']}</div></div>";
                    echo "</div>";
                echo "</div>";
                echo "<div class='clear'></div>";
            }
        echo "</div>";
    }

    /*****GLOBAL AD*****/
    function block_uni_draw_box_ad() {
        echo "<div style='height:250px; width:250px;'>";
            echo "<script async src='//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js'></script>
                <!-- Sidebar - Medium -->
                <ins class='adsbygoogle'
                    style='display:inline-block;width:250px;height:250px'
                    data-ad-client='ca-pub-2757788921600999'
                    data-ad-slot='2147037267'></ins>
                <script>
                (adsbygoogle = window.adsbygoogle || []).push({});
            </script>";
        echo "</div>";
        echo "<div class='clear'></div>";
        echo "<div class='horizontal_separator'></div>";
    }

    function header_uni_draw_box_ad() {
        echo "<div id='banner_ads' style='float:left; width:728px; height:90px; vertical-align:middle; line-height:90px; padding-top:27px; padding-bottom:27px;'>";
            echo "<script async src=\"//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js\"></script>
                <!-- Header_Banner -->
                <ins class=\"adsbygoogle\"
                     style=\"display:inline-block;width:728px;height:90px\"
                     data-ad-client=\"ca-pub-2757788921600999\"
                     data-ad-slot=\"2423588066\"></ins>
                <script>
                (adsbygoogle = window.adsbygoogle || []).push({});
            </script>";
        echo "</div>";
        echo "<div class='clear'></div>";
        echo "<div class='vertical_separator'></div>";        
    }

    /*****SOCIAL NETWORK POSTS*****/
    function create_post_google($news_title, $type) {
    }

    function create_post_facebook($news_title, $type) {
        // 0 - Standard News Article
        // 1 - Weekly Report

        $status_update      = "";
        $hyperlink_title    = strtolower(str_replace(" ","_", $news_title));
        $hyperlink_title    = strtolower(str_replace("#","poundsign", $hyperlink_title)); //%23
        $hyperlink          = "{$GLOBALS['host_name']}{$GLOBALS['page_news']}{$hyperlink_title}";
        $page_id            = "{$GLOBALS['facebook']['page_id']}";

        $facebook = new Facebook(array(
          'appId'  => $GLOBALS['facebook']['app_id'],
          'secret' => $GLOBALS['facebook']['secret']

        ));

        $facebook->setAccessToken($GLOBALS['facebook']['token_page']);

        $response = $facebook->api(
            "/{$page_id}/links",
            "POST",
            array(
                'link' => $hyperlink
            )
        );
    }

    function create_post_twitter($news_title, $type) {
        // 0 - Standard News Article
        // 1 - Weekly Report

        $status_update      = "";
        $hyperlink_title    = strtolower(str_replace(" ","_", $news_title));
        $hyperlink_title    = strtolower(str_replace("#","poundsign", $hyperlink_title)); //%23
        $hyperlink          = "{$GLOBALS['host_name']}{$GLOBALS['page_news']}{$hyperlink_title}";
        $hyperlink_short    = make_bitly_url($hyperlink,'o_6gveb4sdg8','R_8792f649fadb471facf86cb8a4e2feec','json');

        \Codebird\Codebird::setConsumerKey($GLOBALS['twitter']['key'], $GLOBALS['twitter']['secret']);
        $cb = \Codebird\Codebird::getInstance();
        $cb->setToken($GLOBALS['twitter']['token'], $GLOBALS['twitter']['token_secret']);
         
        if ( $type == 0 ) {
            $status_update = "Latest Article: $news_title $hyperlink_short";
        } else if ( $type == 1 ) {
            $status_update = "Check out the latest kills in our weekly raiding report! $hyperlink_short";
        }

        $params = array(
          'status' => $status_update.' #RIFT #mmo #raiding'
        );

        $reply = $cb->statuses_update($params);
    }

    function make_bitly_url($url,$login,$appkey,$format = 'xml',$version = '2.0.1') {
        //create the URL
        $bitly = 'http://api.bit.ly/shorten?version='.$version.'&longUrl='.urlencode($url).'&login='.$login.'&apiKey='.$appkey.'&format='.$format;
        
        //get the url
        //could also use cURL here
        $response = file_get_contents($bitly);
        
        //parse depending on desired format
        if(strtolower($format) == 'json') {
            $json = @json_decode($response,true);
            return $json['results'][$url]['shortUrl'];
        } else {
            $xml = simplexml_load_string($response);
            return 'http://bit.ly/'.$xml->results->nodeKeyVal->hash;
        }
    }

    /*****DATA LOGGING*****/
    function log_entry($severity, $message) {
        if ( $severity == 0 ) { $severity = "INFO"; }
        if ( $severity == 1 ) { $severity = "DEBUG"; }
        if ( $severity == 2 ) { $severity = "WARN"; }
        if ( $severity == 3 ) { $severity = "ERROR"; }

        $year           = date('Y');
        $month          = date('n')."-".date('M');
        $current_date   = date('Y-m-d');
        $log_date       = date('Y-m-d H:i');
        $log_path       = strtolower("{$_SERVER['DOCUMENT_ROOT']}{$GLOBALS['fold_logs']}{$year}/{$month}");
        $log_file       = strtolower("{$log_path}/{$GLOBALS['site_title']}-{$current_date}.txt");

        if ( !file_exists($log_path) ) { mkdir($log_path, 0777, true); }

        $handle = fopen($log_file, 'a+');

        fwrite($handle, "$severity | $log_date | ".session_id()." | $message"."\n");

        fclose($handle);
    }
?>