<?php

# Mappings
/*
Feed Side
---------
0 = NA
1 = LEFT
2 = RIGHT

DC Type
-------
0 = NA
1 = PEE
2 = POOP
3 = PEE_AND_POOP
*/


$msgError = ""; // Clear Error Msg
$msgSuccess = ""; // Clear Success Msg

if(isset($_POST['tt_manual_entry_form_postbk']) && ($_POST['tt_manual_entry_form_postbk'] == 1)){

    $tt_val = "";
    if(isset($_POST['tt_val'])){
        $tt_val = htmlentities(strtoupper(trim($_POST['tt_val'])));
    }

    if(strlen($tt_val) === 0){
        $msgError = "Please enter the value before submitting";
    }

    if(strlen($msgError) === 0){ // If no error, proceed

        $tt_notes = "";
        if(isset($_POST['tt_notes'])){
            $tt_notes = htmlentities(trim($_POST['tt_notes']));
        }

        // Parse the input string
        $tt_val_arr = explode(" ", $tt_val);

        $tt_val_param1 = "";
        if(isset($tt_val_arr[0])){
            $tt_val_param1 = trim($tt_val_arr[0]);
        }

        $tt_val_param2 = "";
        if(isset($tt_val_arr[1])){
            $tt_val_param2 = trim($tt_val_arr[1]);
        }

        $tt_val_param3 = "";
        if(isset($tt_val_arr[2])){
            $tt_val_param3 = trim($tt_val_arr[2]);
        }

        # Commented out - For testing only
        //echo $tt_val_param1; echo "<br/>";
        //echo $tt_val_param2; echo "<br/>";
        //echo $tt_val_param3; echo "<br/>";


        // Input value expanded string
        $tt_val_expanded = "";

        // Param1
        $tt_val_type = "";
        $tt_val_feed_side_txt = "NA";
        $tt_val_feed_side_int = 0;

        if ($tt_val_param1 === "FL"){
            $tt_val_type = "FEED_START";
            $tt_val_feed_side_txt = "LEFT";
            $tt_val_feed_side_int = 1;
            $tt_val_expanded = $tt_val_type." | ".$tt_val_feed_side_txt;
        } else if ($tt_val_param1 === "FR"){
            $tt_val_type = "FEED_START";
            $tt_val_feed_side_txt = "RIGHT";
            $tt_val_feed_side_int = 2;
            $tt_val_expanded = $tt_val_type." | ".$tt_val_feed_side_txt;
        } else if ($tt_val_param1 === "SD"){
            $tt_val_type = "FEED_SD";
            $tt_val_expanded = $tt_val_type;
        } else if ($tt_val_param1 === "STOP"){
            $tt_val_type = "FEED_STOP";
            $tt_val_expanded = $tt_val_type;
        } else if ($tt_val_param1 === "DC"){
            $tt_val_type = "DIAPER_CHANGE";
            $tt_val_expanded = $tt_val_type;
        }
        # Commented out - For testing only
        //echo $tt_val_type; echo "<br/>";
        //echo $tt_val_feed_side_txt; echo "<br/>";

        // Param2
        $tt_timestamp = "";
        $tt_time_only = "";

        if (isset($tt_val_param2) && ($tt_val_param2 === "NOW")){
            // Use current timestamp
            $tt_timestamp = date("Y-m-d H:i");
            $tt_time_only = date("H:i");
        } else if (isset($tt_val_param2) && (strlen($tt_val_param2) > 0)){
            // Use user input timestamp
            $tt_timestamp = date("Y-m-d")." ".$tt_val_param2;
            $tt_time_only = $tt_val_param2;
            //$tt_timestamp = "2024-05-11"." ".$tt_val_param2; // For backfill only
        } else {
            // If no time was provided, automatically use current timestamp
            $tt_timestamp = date("Y-m-d H:i");
            $tt_time_only = date("H:i");
        }
        $tt_val_expanded = $tt_val_expanded." | ".$tt_timestamp;
        # Commented out - For testing only
        //echo $tt_timestamp; echo "<br/>";

        // Param3
        $tt_param3_expanded = "NA";

        if (isset($tt_val_param3) && (strlen($tt_val_param3) > 0 )) {
            if ($tt_val_param3 === "1"){
                $tt_param3_expanded = "PEE";
            } else if ($tt_val_param3 === "2"){
                $tt_param3_expanded = "POOP";
            } else if ($tt_val_param3 === "3"){
                $tt_param3_expanded = "PEE_AND_POOP";
            }
            $tt_val_expanded = $tt_val_expanded." | ".$tt_param3_expanded;
        } else {
            $tt_val_param3 = 0;
            if($tt_val_param1 === "DC"){
                $msgError = "Parameter missing";
            }
        }
        # Commented out - For testing only
        //echo $tt_param3_expanded; echo "<br/>";

        $tt_val_sess_type = "";
        if($tt_val_type === "FEED_START" || $tt_val_type === "FEED_STOP" || $tt_val_type === "FEED_SD"){
            $tt_val_sess_type = "FEED";
        } else {
            $tt_val_sess_type = $tt_val_type;
        }


        # DB
        if(strlen($msgError) === 0){

            // Curent Timestamp
            $now_timestamp = date("Y-m-d H:i:s");
            $now_time_only = date("H:i:s");
            $now_date_only = date("Y-m-d");

            # For backfill - temp
            //$now_timestamp = date("Y-m-d H:i:s");
            //$now_time_only = date("H:i:s");
            //$now_date_only = "2024-05-11";

            // Table1 - tt_events
            // {
            // Prepare the SQL statement
            $stmt1 = $pdo->prepare("INSERT INTO tt_events (tt_event_date, tt_event_time, tt_event_val_raw, tt_event_val_parsed, tt_event_notes, created_on, updated_on)
            VALUES (:tt_event_date, :tt_event_time, :tt_event_val_raw, :tt_event_val_parsed, :tt_event_notes, :created_on, :updated_on)");

            // Bind parameters
            $stmt1->bindParam(':tt_event_date', $now_date_only);
            $stmt1->bindParam(':tt_event_time', $tt_time_only);
            $stmt1->bindParam(':tt_event_val_raw', $tt_val);
            $stmt1->bindParam(':tt_event_val_parsed', $tt_val_expanded);
            $stmt1->bindParam(':tt_event_notes', $tt_notes);
            $stmt1->bindParam(':created_on', $now_timestamp);
            $stmt1->bindParam(':updated_on', $now_timestamp);

            // Execute the statement
            $stmt1->execute();

            // Get the last inserted ID
            $qry1_last_insert_id = $pdo->lastInsertId();
            // }

            // For Stop scenario
            if($tt_val_type === "FEED_STOP" || $tt_val_type === "FEED_SD"){

                // Get the last ID where stop info is empty
                // {
                // Prepare and execute the query
                $stmt3 = $pdo->prepare("SELECT MAX(tt_es_id) AS max_id, 
                                            (SELECT tt_es_time_start 
                                            FROM tt_event_sessions 
                                            WHERE tt_es_id = (SELECT MAX(tt_es_id) FROM tt_event_sessions)
                                            ) AS max_tt_es_time_start
                                        FROM tt_event_sessions WHERE tt_es_time_end IS NULL");
                $stmt3->execute();

                // Fetch the result
                $result = $stmt3->fetch(PDO::FETCH_ASSOC);

                // Get the maximum ID
                $max_id = $result['max_id'];

                // Get the start time
                $tt_es_time_start = $result['max_tt_es_time_start'];
                // }

                // Derive time diff for the session
                $time_duration = 0;
                $time1_ut = strtotime($tt_es_time_start);
                $time2_ut = strtotime($tt_timestamp);
                $time_diff = $time2_ut - $time1_ut;
                $time_duration = $time_diff / 60;

                # Commented out - For testing only
                //echo "tt_es_time_start: ".$tt_es_time_start; echo "<br/>";
                //echo "tt_es_time_end: ".$tt_timestamp; echo "<br/>";
                //echo "time_diff: ".$time_diff; echo "<br/>";
                //echo "time_duration: ".$time_duration; echo "<br/>";

                // Self detach boolean flag
                $sd_bool_flag = 0;
                if($tt_val_type === "FEED_SD"){
                    $sd_bool_flag = 1;
                }

                // Update event_sessions table with stop data
                // Table2 - tt_event_sessions
                // {
                // Prepare the SQL statement
                $stmt2b = $pdo->prepare("UPDATE tt_event_sessions
                SET
                    tt_es_event_id_end = :tt_es_event_id_end,
                    tt_es_time_end = :tt_es_time_end,
                    tt_es_time_duration = :tt_es_time_duration,
                    tt_es_feed_self_detach = :tt_es_feed_self_detach,
                    updated_on = :updated_on
                WHERE
                    tt_es_id = :tt_es_id;
                ");

                // Bind parameters
                $stmt2b->bindParam(':tt_es_event_id_end', $qry1_last_insert_id);
                $stmt2b->bindParam(':tt_es_time_end', $tt_timestamp);
                $stmt2b->bindParam(':tt_es_time_duration', $time_duration);
                $stmt2b->bindParam(':tt_es_feed_self_detach', $sd_bool_flag);
                $stmt2b->bindParam(':updated_on', $now_timestamp);
                $stmt2b->bindParam(':tt_es_id', $max_id);

                // Execute the statement
                $stmt2b->execute();
                // }

            } else { // For all scenarios

                // Table2 - tt_event_sessions
                // {
                // Prepare the SQL statement
                $stmt2a = $pdo->prepare("INSERT INTO tt_event_sessions (
                    tt_es_event_id_start, tt_es_date, tt_es_type,
                    tt_es_time_start, tt_es_feed_side,
                    tt_es_dc_type, created_on, updated_on
                )
                VALUES (
                    :tt_es_event_id_start, :tt_es_date, :tt_es_type,
                    :tt_es_time_start, :tt_es_feed_side,
                    :tt_es_dc_type, :created_on, :updated_on
                )");

                // Bind parameters
                $stmt2a->bindParam(':tt_es_event_id_start', $qry1_last_insert_id);
                $stmt2a->bindParam(':tt_es_date', $now_date_only);
                $stmt2a->bindParam(':tt_es_type', $tt_val_sess_type);

                $stmt2a->bindParam(':tt_es_time_start', $tt_timestamp);
                $stmt2a->bindParam(':tt_es_feed_side', $tt_val_feed_side_int);

                $stmt2a->bindParam(':tt_es_dc_type', $tt_val_param3);
                $stmt2a->bindParam(':created_on', $now_timestamp);
                $stmt2a->bindParam(':updated_on', $now_timestamp);

                // Execute the statement
                $stmt2a->execute();
                // }

            }
            
            $msgSuccess = "Saved successfully";
        }
    } // End - If no error
}

?>
<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">Manual Entry</h1>
    </div>
    <!-- /.col-lg-12 -->
</div>
<!-- /.row -->
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                Enter the values
            </div>
            <div class="panel-body">
                <div class="row">
                	<div id="msgdiv" style="height:30px;">
                    <p>
                    <?php if(isset($msgError) && strlen($msgError) > 0 ) { ?>
                    <div align="center" class="msgError"><strong><?php echo $msgError; ?></strong></div>
                    <?php } else if(isset($msgSuccess) && strlen($msgSuccess) > 0 ) { ?>
                    <div align="center" class="msgSuccess"><strong><?php echo $msgSuccess; ?></strong></div>
                    <?php } ?>
                    </p>
                    </div>
					<div class="col-lg-4"></div>
                    <div class="col-lg-4">
						
						<form role="form" class="form-inline" id="manual_entry_form" name="manual_entry_form" method="POST" >	
    
                            <div class="form-group">
                                <label>Event Value :</label>
                                <input id="tt_val" name="tt_val" class="form-control" type="text" placeholder="<keyword> <time> <ext1>" value="">
                            </div>
                            <br/>
                            <div class="form-group">
                                <label>Notes :</label>
                                <input id="tt_notes" name="tt_notes" class="form-control" type="text" value="">
                            </div>
                            <br/><br/>
                            <div class="form-group">
                                <input id="tt_manual_entry_form_postbk" name="tt_manual_entry_form_postbk" type="hidden" value="1" />
                                <button type="submit" class="btn btn-default">Save</button>
                            </div>

						</form>
						
                        <?php if(isset($_POST['tt_manual_entry_form_postbk']) && ($_POST['tt_manual_entry_form_postbk'] == 1) && (strlen($msgError) === 0)){ ?>
                        <br/>
                        <div>
                            <strong>Submitted Value:</strong>
                            <ul>
                                <li>Event Value: <?php echo htmlentities($_POST['tt_val']); ?></li>
                                <li>Notes: <?php if(strlen(htmlentities($_POST['tt_notes'])) > 0) { echo htmlentities($_POST['tt_notes']); } else { echo "-"; } ?></li>
                                <li>Expanded Value: <br/><?php echo htmlentities($tt_val_expanded); ?></li>
                            </ul>
                        </div>
                        <?php } ?>
        
                        <br/>
                        <hr/>
                        <div>
                            <strong>Possible values:</strong>
                            <ul>
                                <li>Param 1: { FL / FR / SD / STOP / DC }</li>
                                <li>Param 2: { NOW / time }</li>
                                <li>Param 3: { 1 / 2 / 3 }</li>
                            </ul>
                            <strong>Examples:</strong>
                            <ul>
                                <li>FL NOW</li>
                                <li>FL 14:05</li>
                                <li>FR 14:10</li>
                                <li>SD 14:25</li>
                                <li>STOP 14:25</li>
                                <li>DC NOW 1</li>
                                <li>DC 14:30 1</li>
                                <li>DC 14:30 2</li>
                                <li>DC 14:30 3</li>
                            </ul>
                        </div>

                    </div><!-- /.col-lg-4 (nested) -->
                    <div class="col-lg-4"></div>
                    <!-- /.col-lg-4 (nested) -->
					<br/>
                </div>
                <!-- /.row (nested) -->
            </div>
            <!-- /.panel-body -->
        </div>
        <!-- /.panel -->
    </div>
    <!-- /.col-lg-12 -->
</div>
