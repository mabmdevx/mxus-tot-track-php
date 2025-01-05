<?php

# Last Feed Stats
# {

function check_feed_in_progress($tt_baby_id){

    global $pdo;

    $feed_in_progress = false;

    // Prepare and execute the query
    $stmt = $pdo->prepare("SELECT count(*) as cnt
                            FROM tt_event_sessions 
                            WHERE tt_es_type = 'FEED'
                                AND tt_baby_id = :tt_baby_id 
                                AND tt_es_time_start IS NOT NULL 
                                AND tt_es_time_end IS NULL");
    
    // Bind parameters
    $stmt->bindParam(':tt_baby_id', $tt_baby_id);
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

function get_time_since_last_feed($tt_baby_id){

    global $pdo;

    // Curent Timestamp
    $now_timestamp = date("Y-m-d H:i:s");

    $feed_diff_txt = "";

    // Prepare and execute the query
    $stmt = $pdo->prepare("SELECT TIMESTAMPDIFF(MINUTE, tt_es_time_end, '".$now_timestamp."') as feed_diff
                            FROM tt_event_sessions 
                            WHERE tt_es_id = (SELECT MAX(tt_es_id) 
                                                FROM tt_event_sessions 
                                                WHERE tt_es_type = 'FEED'
                                                AND tt_baby_id = :tt_baby_id)");

    // Bind parameters
    $stmt->bindParam(':tt_baby_id', $tt_baby_id);
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

function get_start_time_of_last_feed($tt_baby_id){

    global $pdo;

    $last_feed_start_time = "";

    // Prepare and execute the query
    $stmt = $pdo->prepare("SELECT tt_es_time_start
                            FROM tt_event_sessions 
                            WHERE tt_es_id = (SELECT MAX(tt_es_id) 
                                                FROM tt_event_sessions 
                                                WHERE tt_es_type = 'FEED'
                                                AND tt_baby_id = :tt_baby_id)");
    
    // Bind parameters
    $stmt->bindParam(':tt_baby_id', $tt_baby_id);
    $stmt->execute();

    // Fetch the result
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    // Get the value
    if(isset($result)){
        $last_feed_start_time = date("H:i", strtotime($result['tt_es_time_start']));
    }

    return $last_feed_start_time;

}

function get_end_time_of_last_feed($tt_baby_id){

    global $pdo;

    $last_feed_end_time = "";

    // Prepare and execute the query
    $stmt = $pdo->prepare("SELECT tt_es_time_end
                            FROM tt_event_sessions 
                            WHERE tt_es_id = (SELECT MAX(tt_es_id) 
                                                FROM tt_event_sessions 
                                                WHERE tt_es_type = 'FEED'
                                                AND tt_baby_id = :tt_baby_id)");
    
    // Bind parameters
    $stmt->bindParam(':tt_baby_id', $tt_baby_id);
    $stmt->execute();

    // Fetch the result
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    // Get the value
    if(isset($result)){
        $last_feed_end_time = date("H:i", strtotime($result['tt_es_time_end']));
    }

    return $last_feed_end_time;
}

function get_recommended_time_of_next_feed($tt_baby_id){

    global $pdo;
    
    $next_feed_recommended_time = "";

    // Prepare and execute the query
    $stmt = $pdo->prepare("SELECT tt_es_time_end
                            FROM tt_event_sessions 
                            WHERE tt_es_id = (SELECT MAX(tt_es_id) 
                                                FROM tt_event_sessions 
                                                WHERE tt_es_type = 'FEED'
                                                AND tt_baby_id = :tt_baby_id)");

    // Bind parameters
    $stmt->bindParam(':tt_baby_id', $tt_baby_id);
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

function get_duration_of_last_feed($tt_baby_id){

    global $pdo;

    $last_feed_duration = 0;

    // Prepare and execute the query
    $stmt = $pdo->prepare("SELECT tt_es_time_duration as last_feed_duration 
                            FROM tt_event_sessions 
                            WHERE tt_es_id = (SELECT MAX(tt_es_id) 
                                                FROM tt_event_sessions 
                                                WHERE tt_es_type = 'FEED'
                                                AND tt_baby_id = :tt_baby_id)");

    // Bind parameters
    $stmt->bindParam(':tt_baby_id', $tt_baby_id);
    $stmt->execute();

    // Fetch the result
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    // Get the value
    if(isset($result)){
        $last_feed_duration = $result['last_feed_duration'];
    }

    return $last_feed_duration;

}

function get_side_of_last_feed($tt_baby_id){

    global $pdo;

    $last_feed_side_txt = "NA";

    // Prepare and execute the query
    $stmt = $pdo->prepare("SELECT tt_es_feed_side as last_feed_side 
                            FROM tt_event_sessions 
                            WHERE tt_es_id = (SELECT MAX(tt_es_id) 
                                                FROM tt_event_sessions 
                                                WHERE tt_es_type = 'FEED'
                                                AND tt_baby_id = :tt_baby_id)");

    // Bind parameters
    $stmt->bindParam(':tt_baby_id', $tt_baby_id);
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
function get_last_diaper_change_stats($tt_baby_id){

    global $pdo;

    // Curent Timestamp
    $now_timestamp = date("Y-m-d H:i:s");

    $diaper_diff_txt = "";
    $diaper_last_dc_type_txt = "NA"; 

    // Prepare and execute the query
    $stmt = $pdo->prepare("SELECT TIMESTAMPDIFF(MINUTE, tt_es_time_start, '".$now_timestamp."') as diaper_diff, tt_es_dc_type
                            FROM tt_event_sessions 
                            WHERE tt_es_id = (SELECT MAX(tt_es_id) 
                                                FROM tt_event_sessions 
                                                WHERE tt_es_type = 'DIAPER_CHANGE'
                                                AND tt_baby_id = :tt_baby_id)");

    // Bind parameters
    $stmt->bindParam(':tt_baby_id', $tt_baby_id);
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


# Last poop stats
# {
 
function get_last_poop_stats($tt_baby_id){

    $last_poop_stats_arr = array();
    $last_poop_stats_arr[0] = "NA";
    $last_poop_stats_arr[1] = "NA";

    global $pdo;

    // Prepare and execute the query
    $stmt = $pdo->prepare("SELECT tt_es_time_start as last_poop_timestamp
                            FROM tt_event_sessions 
                            WHERE tt_es_id = (SELECT MAX(tt_es_id) 
                                                FROM tt_event_sessions 
                                                WHERE tt_es_type = 'DIAPER_CHANGE'
                                                AND (tt_es_dc_type = 2 OR tt_es_dc_type = 3)
                                                AND tt_baby_id = :tt_baby_id)");

    // Bind parameters
    $stmt->bindParam(':tt_baby_id', $tt_baby_id);
    $stmt->execute();

    // Fetch the result
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    // Get the value
    if(isset($result) && $result !== false){

        $last_poop_timestamp = $result['last_poop_timestamp'];

        $last_poop_datetime = new DateTime($last_poop_timestamp);
        $now_datetime = new DateTime();
        $last_poop_interval = $now_datetime->diff($last_poop_datetime);

        $last_poop_days = $last_poop_interval->d;
        $last_poop_hours = $last_poop_interval->h;
        $last_poop_minutes = $last_poop_interval->i;

        $last_poop_diff_txt = "$last_poop_days day(s), $last_poop_hours hour(s), $last_poop_minutes minute(s)";

        $last_poop_stats_arr = array();
        $last_poop_stats_arr[0] = date("Y-m-d H:i:s", strtotime($result['last_poop_timestamp']));
        $last_poop_stats_arr[1] = $last_poop_diff_txt;
        
    }

    return $last_poop_stats_arr;

}

# }

# Stats for the day
# {
function get_count_of_feed_sessions_for_date($tt_baby_id, $date){

    global $pdo;

    $count_feed_sessions_for_date = 0;

    // Prepare and execute the query
    $stmt = $pdo->prepare("SELECT count(*) as cnt 
                            FROM tt_event_sessions 
                            WHERE tt_es_type = 'FEED' 
                                AND tt_baby_id = :tt_baby_id 
                                AND tt_es_date = :tt_date");

    // Bind parameters
    $stmt->bindParam(':tt_baby_id', $tt_baby_id);
    $stmt->bindParam(':tt_date', $date);
    $stmt->execute();
    
    // Fetch the result
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // Get the value
    if(isset($result)){
        $count_feed_sessions_for_date = $result['cnt'];
    }

    return $count_feed_sessions_for_date;

}

function get_total_duration_of_all_feed_sessions_for_date($tt_baby_id, $date){

    global $pdo;

    $total_duration_all_feed_sessions_for_date_txt = "";

    // Prepare and execute the query
    $stmt = $pdo->prepare("SELECT SUM(tt_es_time_duration) as duration 
                            FROM tt_event_sessions 
                            WHERE tt_es_type = 'FEED'
                                AND tt_baby_id = :tt_baby_id
                                AND tt_es_date = :tt_date");

    // Bind parameters
    $stmt->bindParam(':tt_baby_id', $tt_baby_id);
    $stmt->bindParam(':tt_date', $date);
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

function get_total_count_of_diapers_used_for_date($tt_baby_id, $date){

    global $pdo;

    $count_diapers_total_for_date = 0;

    // Prepare and execute the query
    $stmt = $pdo->prepare("SELECT count(*) as cnt 
                            FROM tt_event_sessions 
                            WHERE tt_es_type = 'DIAPER_CHANGE' 
                                AND tt_baby_id = :tt_baby_id 
                                AND tt_es_date = :tt_date");

    // Bind parameters
    $stmt->bindParam(':tt_baby_id', $tt_baby_id);
    $stmt->bindParam(':tt_date', $date);
    $stmt->execute();

    // Fetch the result
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    // Get the value
    if(isset($result)){
        $count_diapers_total_for_date = $result['cnt'];
    }

    return $count_diapers_total_for_date;

}

function get_count_of_diapers_used_type_pee_for_date($tt_baby_id, $date){

    global $pdo;

    $count_diapers_pee_for_date = 0;

    // Prepare and execute the query
    $stmt = $pdo->prepare("SELECT count(*) as cnt 
                            FROM tt_event_sessions 
                            WHERE tt_es_type = 'DIAPER_CHANGE'
                                AND (tt_es_dc_type = 1 OR tt_es_dc_type = 3)
                                AND tt_baby_id = :tt_baby_id 
                                AND tt_es_date = :tt_date");

    // Bind parameters
    $stmt->bindParam(':tt_baby_id', $tt_baby_id);
    $stmt->bindParam(':tt_date', $date);
    $stmt->execute();

    // Fetch the result
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    // Get the value
    if(isset($result)){
        $count_diapers_pee_for_date = $result['cnt'];
    }

    return $count_diapers_pee_for_date;

}

function get_count_of_diapers_used_type_poop_for_date($tt_baby_id, $date){

    global $pdo;

    $count_diapers_poop_for_date = 0;

    // Prepare and execute the query
    $stmt = $pdo->prepare("SELECT count(*) as cnt 
                            FROM tt_event_sessions 
                            WHERE tt_es_type='DIAPER_CHANGE' 
                                AND (tt_es_dc_type = 2 OR tt_es_dc_type = 3) 
                                AND tt_baby_id = :tt_baby_id
                                AND tt_es_date = :tt_date");

    // Bind parameters
    $stmt->bindParam(':tt_baby_id', $tt_baby_id);
    $stmt->bindParam(':tt_date', $date);
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
function get_count_of_total_diapers_used_overall($tt_baby_id){

    global $pdo;

    $count_diapers_total_overall = "0";

    // Prepare and execute the query
    $stmt = $pdo->prepare("SELECT count(*) as count_diapers_total_overall 
                            FROM tt_event_sessions 
                            WHERE tt_es_type = 'DIAPER_CHANGE'
                                AND tt_baby_id = :tt_baby_id");

    // Bind parameters
    $stmt->bindParam(':tt_baby_id', $tt_baby_id);
    $stmt->execute();

    // Fetch the result
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    // Get the value
    if(isset($result)){
        $count_diapers_total_overall = $result['count_diapers_total_overall'];
    }

    return $count_diapers_total_overall;

}

function get_feed_side_percentage($tt_baby_id){

    global $pdo;

    $feed_side_pc_left = 0;
    $feed_side_pc_right = 0;

    // Get Total count
    $count_total_sessions_overall_all = "0";
    // Prepare and execute the query
    $stmt = $pdo->prepare("SELECT count(*) as count_total_sessions_overall_all 
                            FROM tt_event_sessions 
                            WHERE tt_es_type = 'FEED'
                                AND tt_baby_id = :tt_baby_id");

    // Bind parameters
    $stmt->bindParam(':tt_baby_id', $tt_baby_id);
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
    $stmt = $pdo->prepare("SELECT count(*) as count_total_sessions_overall_left 
                            FROM tt_event_sessions 
                            WHERE tt_es_feed_side = 1
                                AND tt_baby_id = :tt_baby_id");

    // Bind parameters
    $stmt->bindParam(':tt_baby_id', $tt_baby_id);
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
    $stmt = $pdo->prepare("SELECT count(*) as count_total_sessions_overall_right 
                            FROM tt_event_sessions 
                            WHERE tt_es_feed_side = 2
                                AND tt_baby_id = :tt_baby_id");

    // Bind parameters
    $stmt->bindParam(':tt_baby_id', $tt_baby_id);
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


function get_count_of_sleep_sessions_for_date($tt_baby_id, $date){

    global $pdo;

    $count_sleep_sessions_for_date = "0";

    // Prepare and execute the query
    $stmt = $pdo->prepare("SELECT count(*) as count_sleep_sessions_for_date 
                            FROM tt_event_sessions 
                            WHERE tt_es_type = 'SLEEP' 
                                AND tt_baby_id = :tt_baby_id 
                                AND tt_es_date = :tt_date");

    // Bind parameters
    $stmt->bindParam(':tt_baby_id', $tt_baby_id);
    $stmt->bindParam(':tt_date', $date);
    $stmt->execute();

    // Fetch the result
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    // Get the value
    if(isset($result)){
        $count_sleep_sessions_for_date = $result['count_sleep_sessions_for_date'];
    }

    return $count_sleep_sessions_for_date;

}


function get_total_sleep_duration_for_date($tt_baby_id, $date){

    global $pdo;

    $total_sleep_duration_for_date_txt = "";

    // Prepare and execute the query
    $stmt = $pdo->prepare("SELECT SUM(tt_es_time_duration) as duration 
                            FROM tt_event_sessions 
                            WHERE tt_es_type = 'SLEEP'
                                AND tt_baby_id = :tt_baby_id
                                AND tt_es_date = :tt_date");
    
    // Bind parameters
    $stmt->bindParam(':tt_baby_id', $tt_baby_id);
    $stmt->bindParam(':tt_date', $date);
    $stmt->execute();

    // Fetch the result
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    // Get the value
    if(isset($result)){
        $total_sleep_duration_for_date_int = $result['duration'];
        if($total_sleep_duration_for_date_int > 59){
            $total_sleep_duration_for_date_hrs = floor($total_sleep_duration_for_date_int/60);
            $total_sleep_duration_for_date_txt = $total_sleep_duration_for_date_hrs. " hour(s) ".($total_sleep_duration_for_date_int-($total_sleep_duration_for_date_hrs*60))." minute(s)";
        } else {
            $total_sleep_duration_for_date_txt = $total_sleep_duration_for_date_int." minute(s)";
        }
    }

    return $total_sleep_duration_for_date_txt;

}

# }


function get_tt_user_uuid(){
    return $_SESSION['tt_user_uuid'];
}


function get_tt_user_id($tt_user_uuid){
    
    global $pdo;

    // Prepare and execute the query
    $stmt = $pdo->prepare("SELECT tt_user_id, tt_user_uuid 
                            FROM tt_users
                            WHERE tt_user_uuid = :tt_user_uuid");

    // Bind parameters
    $stmt->bindParam(':tt_user_uuid', $tt_user_uuid);
    $stmt->execute();

    // Fetch the result
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    // Get the value
    if(isset($result)){
        return $result['tt_user_id'];
    }

    return false;

}


function get_tt_baby_uuid(){
    return $_SESSION['tt_baby_uuid'];
}


function get_tt_baby_id($tt_baby_uuid){
    
    global $pdo;

    // Prepare and execute the query
    $stmt = $pdo->prepare("SELECT tt_baby_id, tt_baby_uuid 
                            FROM tt_babies
                            WHERE tt_baby_uuid = :tt_baby_uuid");

    // Bind parameters
    $stmt->bindParam(':tt_baby_uuid', $tt_baby_uuid);
    $stmt->execute();

    // Fetch the result
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    // Get the value
    if(isset($result)){
        return $result['tt_baby_id'];
    }

    return false;

}


function get_selected_baby_name($tt_baby_id){

    global $pdo;

    // Prepare and execute the query
    $stmt = $pdo->prepare("SELECT tt_baby_id, tt_baby_uuid, tt_baby_name 
                            FROM tt_babies
                            WHERE tt_baby_id = :tt_baby_id");

    // Bind parameters
    $stmt->bindParam(':tt_baby_id', $tt_baby_id);
    $stmt->execute();

    // Fetch the result
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    // Get the value
    if(isset($result)){
        return $result['tt_baby_name'];
    }

    return false;
    
}


function password_encryption($password){
    return password_hash($password, PASSWORD_DEFAULT);
}


function password_verification($password_input, $password_hash){
    return password_verify($password_input, $password_hash);
}


function user_signup($tt_username, $tt_password, $tt_baby_name){

    global $pdo;

    // Curent Timestamp
    $now_timestamp = date("Y-m-d H:i:s");

    // Check if user exists
    //{
    $stmt = $pdo->prepare("SELECT tt_user_id, tt_user_uuid, tt_username 
                            FROM tt_users
                            WHERE tt_username = :tt_username");

    // Bind parameters
    $stmt->bindParam(':tt_username', $tt_username);
    $stmt->execute();

    // Fetch the result
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    // Get the value
    if(isset($result) && strlen($result['tt_user_id']) > 0){
        // Return error - User already exists
        return "ERROR_USER_ALREADY_EXISTS";
    }
    //}

    // Prepare and execute the query
    $stmt = $pdo->prepare("INSERT INTO tt_users (tt_user_uuid, tt_username, tt_password, created_on, updated_on) 
                            VALUES (:tt_user_uuid, :tt_username, :tt_password, :created_on, :updated_on)");

    // Generate User UUID
    $tt_new_user_uuid = generate_uuid();

    // Bind parameters
    $stmt->bindParam(':tt_user_uuid', $tt_new_user_uuid);
    $stmt->bindParam(':tt_username', $tt_username);
    $stmt->bindParam(':tt_password', $tt_password);
    $stmt->bindParam(':created_on', $now_timestamp);
    $stmt->bindParam(':updated_on', $now_timestamp);
    $stmt->execute();

    // Fetch the result - User ID
    $tt_new_user_id = $pdo->lastInsertId();

    // Prepare and execute the query
    $stmt = $pdo->prepare("INSERT INTO tt_babies (tt_baby_uuid, tt_baby_name, tt_user_id, tt_selected_baby_per_user, created_on, updated_on) 
                            VALUES (:tt_baby_uuid, :tt_baby_name, :tt_user_id, :tt_selected_baby_per_user, :created_on, :updated_on)");

    // Generate Baby UUID
    $tt_new_baby_uuid = generate_uuid();

    // Set the selected baby for this user
    $tt_selected_baby_per_user = 1;

    // Bind parameters
    $stmt->bindParam(':tt_baby_uuid', $tt_new_baby_uuid);
    $stmt->bindParam(':tt_baby_name', $tt_baby_name);
    $stmt->bindParam(':tt_user_id', $tt_new_user_id);
    $stmt->bindParam(':tt_selected_baby_per_user', $tt_selected_baby_per_user);
    $stmt->bindParam(':created_on', $now_timestamp);
    $stmt->bindParam(':updated_on', $now_timestamp);
    $stmt->execute();

    return $tt_new_user_uuid;
}


function find_user_by_username($tt_username){

    global $pdo;

    // Prepare and execute the query
    $stmt = $pdo->prepare("SELECT tt_user_id, tt_user_uuid, tt_username, tt_password
                            FROM tt_users
                            WHERE tt_username = :tt_username");

    // Bind parameters
    $stmt->bindParam(':tt_username', $tt_username);
    $stmt->execute();

    // Fetch the result
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    // Get the value
    if(isset($result)){
        return $result;
    }

    return false;
}

# Table List
# {
# Events
function list_events($tt_baby_id, $tt_filter_date, $tt_filter_sort){

    global $pdo;

    $result = "";

    // Prepare and execute the query
    $qry = "SELECT *
                FROM tt_events 
                WHERE tt_baby_id = :tt_baby_id";
    
    if(isset($tt_filter_date) && strlen($tt_filter_date) > 0){
        $qry = $qry." AND tt_event_date = :tt_filter_date";
    }

    $qry = $qry." ORDER BY tt_event_date DESC, tt_event_time";

    if(isset($tt_filter_sort) && strlen($tt_filter_sort) > 0){
        $qry = $qry." ".$tt_filter_sort;
    }
    
    $qry = $qry." LIMIT 300";

    # Commented out - For testing only
    //echo $qry;

    $stmt = $pdo->prepare($qry);
    $stmt->bindParam(':tt_baby_id', $tt_baby_id);
    $stmt->bindParam(':tt_filter_date', $tt_filter_date);
    $stmt->execute();


    // Fetch the result
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Get the value
    if(isset($result)){
        return $result;
    }

    return false;
}
# Event Sessions
function list_event_sessions($tt_baby_id, $tt_filter_date, $tt_filter_sort){

    global $pdo;

    $result = "";

    // Prepare and execute the query
    $qry = "SELECT *
                FROM tt_event_sessions 
                WHERE tt_baby_id = :tt_baby_id";

    if(isset($tt_filter_date) && strlen($tt_filter_date) > 0){
        $qry = $qry." AND tt_es_date = :tt_filter_date";
    }

    $qry = $qry." ORDER BY tt_es_date DESC, tt_es_time_start";

    if(isset($tt_filter_sort) && strlen($tt_filter_sort) > 0){
        $qry = $qry." ".$tt_filter_sort;
    }

    $qry = $qry." LIMIT 300";

    # Commented out - For testing only
    //echo $qry;

    $stmt = $pdo->prepare($qry);
    $stmt->bindParam(':tt_baby_id', $tt_baby_id);
    $stmt->bindParam(':tt_filter_date', $tt_filter_date);
    $stmt->execute();

    // Fetch the result
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Get the value
    if(isset($result)){
        return $result;
    }

    return false;
}


# Babies
function list_babies($tt_user_id){

    global $pdo;

    $result = "";

    // Prepare and execute the query
    $qry = "SELECT *
                FROM tt_babies 
                WHERE tt_user_id = :tt_user_id";

    $qry = $qry." ORDER BY created_on ASC LIMIT 300";

    # Commented out - For testing only
    //echo $qry;

    $stmt = $pdo->prepare($qry);
    $stmt->bindParam(':tt_user_id', $tt_user_id);
    $stmt->execute();

    // Fetch the result
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Get the value
    if(isset($result)){
        return $result;
    }

    return false;
}


function save_selected_baby($tt_selected_baby_per_user, $tt_user_id){

    global $pdo;

    // Curent Timestamp
    $now_timestamp = date("Y-m-d H:i:s");

    // -- Reset all baby selections to zero
    // Prepare the SQL statement
    $stmt1 = $pdo->prepare("UPDATE tt_babies SET 
                                    tt_selected_baby_per_user = 0, 
                                    updated_on = :updated_on 
                            WHERE tt_user_id = :tt_user_id");

    // Bind parameters
    $stmt1->bindParam(':updated_on', $now_timestamp);
    $stmt1->bindParam(':tt_user_id', $tt_user_id);

    // Execute the statement
    $stmt1->execute();

    // -- Set the selected baby
    // Prepare the SQL statement
    $stmt2 = $pdo->prepare("UPDATE tt_babies SET 
                                    tt_selected_baby_per_user = 1, 
                                    updated_on = :updated_on 
                            WHERE tt_baby_uuid = :tt_selected_baby_per_user 
                            AND tt_user_id = :tt_user_id");

    // Bind parameters
    $stmt2->bindParam(':updated_on', $now_timestamp);
    $stmt2->bindParam(':tt_selected_baby_per_user', $tt_selected_baby_per_user);
    $stmt2->bindParam(':tt_user_id', $tt_user_id);

    // Execute the statement
    $stmt2->execute();

    return true;
}


function add_baby($tt_baby_name, $tt_user_id){

    global $pdo;

    // Curent Timestamp
    $now_timestamp = date("Y-m-d H:i:s");

    // Prepare the SQL statement
    $stmt = $pdo->prepare("INSERT INTO tt_babies (tt_baby_name, tt_baby_uuid, tt_user_id, created_on, updated_on)
            VALUES (:tt_baby_name, :tt_baby_uuid, :tt_user_id, :created_on, :updated_on)");

    $tt_baby_uuid = generate_uuid();

    // Bind parameters
    $stmt->bindParam(':tt_baby_name', $tt_baby_name);
    $stmt->bindParam(':tt_baby_uuid', $tt_baby_uuid);
    $stmt->bindParam(':tt_user_id', $tt_user_id);
    $stmt->bindParam(':created_on', $now_timestamp);
    $stmt->bindParam(':updated_on', $now_timestamp);

    // Execute the statement
    $stmt->execute();

    // Get the last inserted ID
    $qry_last_insert_id = $pdo->lastInsertId();   

    return true;
}
# }

function generate_uuid() {

    return sprintf( '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
        mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ),
        mt_rand( 0, 0xffff ),
        mt_rand( 0, 0x0fff ) | 0x4000,
        mt_rand( 0, 0x3fff ) | 0x8000,
        mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ) );

}


function get_selected_baby_by_user_uuid($tt_user_uuid){

    global $pdo;

    // Prepare and execute the query
    $stmt = $pdo->prepare("SELECT tt_baby_id, tt_baby_uuid, tt_selected_baby_per_user 
                            FROM tt_babies 
                            INNER JOIN tt_users ON tt_babies.tt_user_id = tt_users.tt_user_id
                            WHERE tt_babies.tt_selected_baby_per_user = 1
                                AND tt_users.tt_user_uuid = :tt_user_uuid");

    // Bind parameters
    $stmt->bindParam(':tt_user_uuid', $tt_user_uuid);
    $stmt->execute();

    // Fetch the result
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    // Get the value
    if(isset($result)){
        return $result['tt_baby_uuid'];
    }

    return false;
}

function row_color_sessions($val){

    $color = "";

    if($val === "FEED"){
        $color = "color:green";
    } else if($val === "SLEEP"){
        $color = "color:#0D47A1";
    } else if($val === "DIAPER_CHANGE"){
        $color = "color:#6600CC";
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
        $color = "color:#6600CC";
    } else if (str_contains($val, "SP1")) {
        $color = "color:#0099CC";
    } else if (str_contains($val, "SP0")) {
        $color = "color:#0D47A1";
    } else {
        $color = "color:red";
    }

    return $color;
}


?>