<?php

# Last Feed Stats
# {

function check_feed_in_progress(){

    global $pdo;

    $feed_in_progress = false;

    // Prepare and execute the query
    $stmt = $pdo->prepare("SELECT count(*) as cnt
                            FROM tt_event_sessions WHERE tt_es_type='FEED' AND tt_es_time_start IS NOT NULL AND tt_es_time_end IS NULL");
    $stmt->execute();

    // Fetch the result
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    // Get the value
    if(isset($result)){
        $cnt = $result['cnt'];

        if($cnt > 0){
            $feed_in_progress = true;
        }
    }

    return $feed_in_progress;
}

function get_time_since_last_feed(){

    global $pdo;

    // Curent Timestamp
    $now_timestamp = date("Y-m-d H:i:s");

    $feed_diff_txt = "";

    // Prepare and execute the query
    $stmt = $pdo->prepare("SELECT TIMESTAMPDIFF(MINUTE, tt_es_time_end, '".$now_timestamp."') as feed_diff
    from tt_event_sessions where tt_es_id = (SELECT MAX(tt_es_id) FROM tt_event_sessions WHERE tt_es_type='FEED')");
    $stmt->execute();

    // Fetch the result
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    // Get the value
    if(isset($result)){
        $feed_diff = $result['feed_diff'];

        if($feed_diff > 59){
            $feed_diff_hrs = floor($feed_diff/60);
            $feed_diff_txt = $feed_diff_hrs. " hour(s) ".($feed_diff-($feed_diff_hrs*60))." minute(s)";
        } else {
            $feed_diff_txt = $feed_diff." minute(s)";
        }
    }

    return $feed_diff_txt;
}

function get_start_time_of_last_feed(){

    global $pdo;

    $last_feed_start_time = "";

    // Prepare and execute the query
    $stmt = $pdo->prepare("SELECT tt_es_time_start
    from tt_event_sessions where tt_es_id = (SELECT MAX(tt_es_id) FROM tt_event_sessions WHERE tt_es_type='FEED')");
    $stmt->execute();

    // Fetch the result
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    // Get the value
    if(isset($result)){
        $last_feed_start_time = date("H:i", strtotime($result['tt_es_time_start']));
    }

    return $last_feed_start_time;

}

function get_end_time_of_last_feed(){

    global $pdo;

    $last_feed_end_time = "";

    // Prepare and execute the query
    $stmt = $pdo->prepare("SELECT tt_es_time_end
    from tt_event_sessions where tt_es_id = (SELECT MAX(tt_es_id) FROM tt_event_sessions WHERE tt_es_type='FEED')");
    $stmt->execute();

    // Fetch the result
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    // Get the value
    if(isset($result)){
        $last_feed_end_time = date("H:i", strtotime($result['tt_es_time_end']));
    }

    return $last_feed_end_time;
}

function get_recommended_time_of_next_feed(){

    global $pdo;
    
    $next_feed_recommended_time = "";

    // Prepare and execute the query
    $stmt = $pdo->prepare("SELECT tt_es_time_end
    from tt_event_sessions where tt_es_id = (SELECT MAX(tt_es_id) FROM tt_event_sessions WHERE tt_es_type='FEED')");
    $stmt->execute();

    // Fetch the result
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    // Get the value
    if(isset($result)){
        $next_feed_interval = "2 hours";
        $last_feed_plus_interval = strtotime('+'.$next_feed_interval, strtotime($result['tt_es_time_end']));
        $next_feed_recommended_time = date('H:i', $last_feed_plus_interval);
    }

    return $next_feed_recommended_time;
}

function get_duration_of_last_feed(){

    global $pdo;

    $last_feed_duration = 0;

    // Prepare and execute the query
    $stmt = $pdo->prepare("SELECT tt_es_time_duration as last_feed_duration from tt_event_sessions where tt_es_id = (SELECT MAX(tt_es_id) FROM tt_event_sessions WHERE tt_es_type='FEED')");
    $stmt->execute();

    // Fetch the result
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    // Get the value
    if(isset($result)){
        $last_feed_duration = $result['last_feed_duration'];
    }

    return $last_feed_duration;

}

function get_side_of_last_feed(){

    global $pdo;

    $last_feed_side_txt = "NA";

    // Prepare and execute the query
    $stmt = $pdo->prepare("SELECT tt_es_feed_side as last_feed_side from tt_event_sessions where tt_es_id = (SELECT MAX(tt_es_id) FROM tt_event_sessions WHERE tt_es_type='FEED')");
    $stmt->execute();
    
    // Fetch the result
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // Get the value
    if(isset($result)){
        $last_feed_side_int = $result['last_feed_side'];
        if($last_feed_side_int === 1){
            $last_feed_side_txt = "LEFT";
        } else if($last_feed_side_int === 2){
            $last_feed_side_txt = "RIGHT";
        } else {
            $last_feed_side_txt = "NA";
        }
    }

    return $last_feed_side_txt;

}
# }


# Last diaper change stats
# {
function get_last_diaper_change_stats(){

    global $pdo;

    // Curent Timestamp
    $now_timestamp = date("Y-m-d H:i:s");

    $diaper_diff_txt = "";
    $diaper_last_dc_type_txt = "NA"; 

    // Prepare and execute the query
    $stmt = $pdo->prepare("SELECT TIMESTAMPDIFF(MINUTE, tt_es_time_start, '".$now_timestamp."') as diaper_diff, tt_es_dc_type
    from tt_event_sessions where tt_es_id = (SELECT MAX(tt_es_id) FROM tt_event_sessions WHERE tt_es_type='DIAPER_CHANGE')");
    $stmt->execute();

    // Fetch the result
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    // Get the value
    if(isset($result)){
        $diaper_diff = $result['diaper_diff'];

        if($diaper_diff > 59){
            $diaper_diff_hrs = floor($diaper_diff/60);
            $diaper_diff_txt = $diaper_diff_hrs. " hour(s) ".($diaper_diff-($diaper_diff_hrs*60))." minute(s)";
        } else {
            $diaper_diff_txt = $diaper_diff." minute(s)";
        }

        $diaper_last_dc_type = $result['tt_es_dc_type'];
        if($diaper_last_dc_type === 1){
            $diaper_last_dc_type_txt = "PEE";
        } else if($diaper_last_dc_type === 2){
            $diaper_last_dc_type_txt = "POOP";
        } else if($diaper_last_dc_type === 3){
            $diaper_last_dc_type_txt = "PEE and POOP";
        } 
    }

    $diaper_change_stats_arr = array();
    $diaper_change_stats_arr[0] = $diaper_diff_txt;
    $diaper_change_stats_arr[1] = $diaper_last_dc_type_txt;

    return $diaper_change_stats_arr;

}
# }


# Stats for the day
# {
function get_count_of_feed_sessions_for_date($date){

    global $pdo;

    $count_feed_sessions_for_date = 0;

    // Prepare and execute the query
    $stmt = $pdo->prepare("SELECT count(*) as cnt FROM tt_event_sessions WHERE tt_es_type='FEED' AND tt_es_date='".$date."'");
    $stmt->execute();
    
    // Fetch the result
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // Get the value
    if(isset($result)){
        $count_feed_sessions_for_date = $result['cnt'];
    }

    return $count_feed_sessions_for_date;

}

function get_total_duration_of_all_feed_sessions_for_date($date){

    global $pdo;

    $total_duration_all_feed_sessions_for_date_txt = "";

    // Prepare and execute the query
    $stmt = $pdo->prepare("SELECT SUM(tt_es_time_duration) as duration FROM tt_event_sessions WHERE tt_es_type='FEED' AND tt_es_date='".$date."'");
    $stmt->execute();
    
    // Fetch the result
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // Get the value
    if(isset($result)){
        $total_duration_all_feed_sessions_for_date = $result['duration'];
        if($total_duration_all_feed_sessions_for_date > 59){
            $total_duration_all_feed_sessions_for_date_hrs = floor($total_duration_all_feed_sessions_for_date/60);
            $total_duration_all_feed_sessions_for_date_txt = $total_duration_all_feed_sessions_for_date_hrs. " hour(s) ".($total_duration_all_feed_sessions_for_date-($total_duration_all_feed_sessions_for_date_hrs*60))." minute(s)";
        } else {
            $total_duration_all_feed_sessions_for_date_txt = $total_duration_all_feed_sessions_for_date." minute(s)";
        }
    }

    return $total_duration_all_feed_sessions_for_date_txt;

}

function get_total_count_of_diapers_used_for_date($date){

    global $pdo;

    $count_diapers_total_for_date = 0;

    // Prepare and execute the query
    $stmt = $pdo->prepare("SELECT count(*) as cnt FROM tt_event_sessions WHERE tt_es_type='DIAPER_CHANGE' AND tt_es_date='".$date."'");
    $stmt->execute();

    // Fetch the result
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    // Get the value
    if(isset($result)){
        $count_diapers_total_for_date = $result['cnt'];
    }

    return $count_diapers_total_for_date;

}

function get_count_of_diapers_used_type_pee_for_date($date){

    global $pdo;

    $count_diapers_pee_for_date = 0;

    // Prepare and execute the query
    $stmt = $pdo->prepare("SELECT count(*) as cnt FROM tt_event_sessions WHERE tt_es_type='DIAPER_CHANGE' AND (tt_es_dc_type = 1 OR tt_es_dc_type = 3) AND tt_es_date='".$date."'");
    $stmt->execute();

    // Fetch the result
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    // Get the value
    if(isset($result)){
        $count_diapers_pee_for_date = $result['cnt'];
    }

    return $count_diapers_pee_for_date;

}

function get_count_of_diapers_used_type_poop_for_date($date){

    global $pdo;

    $count_diapers_poop_for_date = 0;

    // Prepare and execute the query
    $stmt = $pdo->prepare("SELECT count(*) as cnt FROM tt_event_sessions WHERE tt_es_type='DIAPER_CHANGE' AND (tt_es_dc_type = 2 OR tt_es_dc_type = 3) AND tt_es_date='".$date."'");
    $stmt->execute();

    // Fetch the result
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    // Get the value
    if(isset($result)){
        $count_diapers_poop_for_date = $result['cnt'];
    }

    return $count_diapers_poop_for_date;

}
# }


# Overall Stats
# {
function get_count_of_total_diapers_used_overall(){

    global $pdo;

    $count_diapers_total_overall = "0";

    // Prepare and execute the query
    $stmt = $pdo->prepare("SELECT count(*) as count_diapers_total_overall FROM tt_event_sessions WHERE tt_es_type='DIAPER_CHANGE'");
    $stmt->execute();

    // Fetch the result
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    // Get the value
    if(isset($result)){
        $count_diapers_total_overall = $result['count_diapers_total_overall'];
    }

    return $count_diapers_total_overall;

}

function get_feed_side_percentage(){

    global $pdo;

    $feed_side_pc_left = 0;
    $feed_side_pc_right = 0;

    // Get Total count
    $count_total_sessions_overall_all = "0";
    // Prepare and execute the query
    $stmt = $pdo->prepare("SELECT count(*) as count_total_sessions_overall_all FROM tt_event_sessions WHERE tt_es_type='FEED'");
    $stmt->execute();

    // Fetch the result
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    // Get the value
    if(isset($result)){
        $count_total_sessions_overall_all = $result['count_total_sessions_overall_all'];
    }

    // Get Left count
    $count_total_sessions_overall_left = "0";
    // Prepare and execute the query
    $stmt = $pdo->prepare("SELECT count(*) as count_total_sessions_overall_left FROM tt_event_sessions WHERE tt_es_feed_side=1");
    $stmt->execute();

    // Fetch the result
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    // Get the value
    if(isset($result)){
        $count_total_sessions_overall_left = $result['count_total_sessions_overall_left'];
    }

    // Get Right count
    $count_total_sessions_overall_right = 0;
    // Prepare and execute the query
    $stmt = $pdo->prepare("SELECT count(*) as count_total_sessions_overall_right FROM tt_event_sessions WHERE tt_es_feed_side=2");
    $stmt->execute();

    // Fetch the result
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    // Get the value
    if(isset($result)){
        $count_total_sessions_overall_right = $result['count_total_sessions_overall_right'];
    }

    $feed_side_pc_left = round( ($count_total_sessions_overall_left / $count_total_sessions_overall_all) * 100);
    $feed_side_pc_right = round( ($count_total_sessions_overall_right / $count_total_sessions_overall_all) * 100);

    $feed_side_pc_arr = array();
    $feed_side_pc_arr[0] = $feed_side_pc_left;
    $feed_side_pc_arr[1] = $feed_side_pc_right;

    return $feed_side_pc_arr;
}

# }

# Table List
# {
# Events
function list_events($tt_filter_date, $tt_filter_sort){

    global $pdo;

    $result = "";

    // Prepare and execute the query
    $qry = "SELECT *
                FROM tt_events WHERE 1=1";
    
    if(isset($tt_filter_date) && strlen($tt_filter_date) > 0){
        $qry = $qry." AND tt_event_date = '".$tt_filter_date."'";
    }

    $qry = $qry." ORDER BY tt_event_date DESC, tt_event_time";

    if(isset($tt_filter_sort) && strlen($tt_filter_sort) > 0){
        $qry = $qry." ".$tt_filter_sort;
    }
    
    $qry = $qry." LIMIT 300";

    # Commented out - For testing only
    //echo $qry;

    $stmt = $pdo->prepare($qry);
    $stmt->execute();

    // Fetch the result
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Get the value
    if(isset($result)){
        return $result;
    }

    return $result;
}
# Event Sessions
function list_event_sessions($tt_filter_date, $tt_filter_sort){

    global $pdo;

    $result = "";

    // Prepare and execute the query
    $qry = "SELECT *
                FROM tt_event_sessions WHERE 1=1";

    if(isset($tt_filter_date) && strlen($tt_filter_date) > 0){
        $qry = $qry." AND tt_es_date = '".$tt_filter_date."'";
    }

    $qry = $qry." ORDER BY tt_es_date DESC, tt_es_time_start";

    if(isset($tt_filter_sort) && strlen($tt_filter_sort) > 0){
        $qry = $qry." ".$tt_filter_sort;
    }

    $qry = $qry." LIMIT 300";

    # Commented out - For testing only
    //echo $qry;

    $stmt = $pdo->prepare($qry);
    $stmt->execute();

    // Fetch the result
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Get the value
    if(isset($result)){
        return $result;
    }

    return $result;
}
# }

function row_color_sessions($val){

    $color = "";

    if($val === "FEED"){
        $color = "color:green";
    } else if($val === "DIAPER_CHANGE"){
        $color = "color:blue";
    }

    return $color;
}

function row_color_events($val){

    $color = "";

    # Note: str_contains() is PHP 8 syntax

    if (str_contains($val, "FL") || str_contains($val, "FR")) {
        $color = "color:green";
    } else if (str_contains($val, "SD") || str_contains($val, "STOP")) {
        $color = "color:brown";
    } else if (str_contains($val, "DC")) {
        $color = "color:blue";
    } else {
        $color = "color:red";
    }

    return $color;
}


?>