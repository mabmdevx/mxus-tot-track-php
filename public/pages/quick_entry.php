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

if(isset($_POST['tt_quick_entry_form_postbk']) && ($_POST['tt_quick_entry_form_postbk'] == 1)){

    $tt_val = "";
    
    // Generate the input string
    $tt_val_rd_param1 = "";
    if(isset($_POST['tt_val_rd_param1'])){
        $tt_val_rd_param1 = htmlentities(trim($_POST['tt_val_rd_param1']));
    }

    $tt_val_txt_param2a = "";
    if(isset($_POST['tt_val_txt_param2a'])){
        $tt_val_txt_param2a = htmlentities(trim($_POST['tt_val_txt_param2a']));
    }

    $tt_val_rd_param2b = "";
    if(isset($_POST['tt_val_rd_param2b'])){
        $tt_val_rd_param2b = htmlentities(trim($_POST['tt_val_rd_param2b']));
    }

    $tt_val_rd_param2_hour = "";
    if(isset($_POST['tt_val_rd_param2_hour'])){
        $tt_val_rd_param2_hour = htmlentities(trim($_POST['tt_val_rd_param2_hour']));
    }

    $tt_val_rd_param2_min = "";
    if(isset($_POST['tt_val_rd_param2_min'])){
        $tt_val_rd_param2_min = htmlentities(trim($_POST['tt_val_rd_param2_min']));
    }

    $tt_val_rd_param3 = "";
    if(isset($_POST['tt_val_rd_param3'])){
        $tt_val_rd_param3 = htmlentities(trim($_POST['tt_val_rd_param3']));
    }

    if(strlen($tt_val_rd_param1) === 0){
        $msgError = "Please enter the Operation value before submitting";
    }
    if(strlen($tt_val_txt_param2a) === 0){
        $msgError = "Please enter the Date value before submitting";
    }
    if(strlen($tt_val_rd_param2b) === 0){
        $msgError = "Please enter the Time value before submitting";
    }
    if(($tt_val_rd_param1 === "DC") && strlen($tt_val_rd_param3) === 0){
        $msgError = "Please enter the DC value before submitting";
    }

    $tt_notes = "";
    if(isset($_POST['tt_notes'])){
        $tt_notes = htmlentities(trim($_POST['tt_notes']));
    }

    # Commented out - For testing only
    //echo "tt_val_rd_param1: ".$tt_val_rd_param1; echo "<br/>";
    //echo "tt_val_txt_param2a: ".$tt_val_txt_param2a; echo "<br/>";
    //echo "tt_val_rd_param2b: ".$tt_val_rd_param2b; echo "<br/>";
    //echo "tt_val_rd_param2_hour: ".$tt_val_rd_param2_hour; echo "<br/>";
    //echo "tt_val_rd_param2_min: ".$tt_val_rd_param2_min; echo "<br/>";
    //echo "tt_val_rd_param3: ".$tt_val_rd_param3; echo "<br/>";
    //echo "tt_notes: ".$tt_notes; echo "<br/>";

    // Map radio button entry to text entry
    $tt_val_param1 = $tt_val_rd_param1;
    $tt_val_param2 = $tt_val_txt_param2a." ".$tt_val_rd_param2b;
    $tt_val_param3 = $tt_val_rd_param3;

    if($tt_val_rd_param2b === "CUSTOM_TIME"){
        $tt_val_param2 = $tt_val_txt_param2a." ".$tt_val_rd_param2_hour.":".$tt_val_rd_param2_min;
    }
    $tt_val = $tt_val_param1." ".$tt_val_param2." ".$tt_val_param3;
    # Commented out - For testing only
    //echo "tt_val: ".$tt_val; echo "<br/>"; 

    if(strlen($msgError) === 0){ // If no error, proceed

        // -- Parse the input string --

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

        if (isset($tt_val_rd_param2b) && ($tt_val_rd_param2b === "NOW")){
            // Use current timestamp
            $tt_timestamp = date("Y-m-d H:i");
            $tt_time_only = date("H:i");
        } else if (isset($tt_val_rd_param2b) && (strlen($tt_val_rd_param2b) > 0)){
            // Use user input timestamp
            $tt_timestamp = $tt_val_param2;
            $tt_time_only = $tt_val_rd_param2_hour.":".$tt_val_rd_param2_min;
            //$tt_timestamp = "2024-05-11"." ".$tt_val_param2; // For backfill only
        } else {
            // If no time was provided, automatically use current timestamp
            $tt_timestamp = date("Y-m-d H:i");
            $tt_time_only = date("H:i");
        }
        $tt_val_expanded = $tt_val_expanded." | ".$tt_timestamp;
        # Commented out - For testing only
        //echo "tt_timestamp: ".$tt_timestamp; echo "<br/>";
        //echo "tt_time_only: ".$tt_time_only; echo "<br/>";


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
            $stmt1->bindParam(':tt_event_date', $tt_val_txt_param2a);
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
                $stmt2a->bindParam(':tt_es_date', $tt_val_txt_param2a);
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
        <h1 class="page-header">Quick Entry</h1>
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
						
						<form role="form" class="form-inline" id="quick_entry_form" name="quick_entry_form" method="POST" >	
    
                            <div class="form-group">
                                <label>Event Value :</label>
                                <fieldset id="tt_val_fs_param1" class="fieldset_custom">
                                    <legend class="fieldset_custom">Select the operation</legend>
                                    <input type="radio" id="tt_val_rd_param1_opt1" name="tt_val_rd_param1" value="FL" checked="checked">
                                    <label for="FL">FEED START - LEFT (FL)</label>
                                    <br/>
                                    <input type="radio" id="tt_val_rd_param1_opt2" name="tt_val_rd_param1" value="FR">
                                    <label for="FR">FEED START - RIGHT (FR)</label>
                                    <br/>
                                    <input type="radio" id="tt_val_rd_param1_opt3" name="tt_val_rd_param1" value="SD">
                                    <label for="SD">FEED STOP - SELF DETACH (SD)</label>
                                    <br/>
                                    <input type="radio" id="tt_val_rd_param1_opt4" name="tt_val_rd_param1" value="STOP">
                                    <label for="STOP">FEED STOP - STOP (STOP)</label>
                                    <br/>
                                    <input type="radio" id="tt_val_rd_param1_opt5" name="tt_val_rd_param1" value="DC">
                                    <label for="DC">DIAPER CHANGE (DC)</label>
                                </fieldset>
                                <br/>
                                <fieldset id="tt_val_fs_param2a" class="fieldset_custom">
                                    <legend class="fieldset_custom">Select the date</legend>
                                    <input type="text" class="form-control" id="tt_val_txt_param2a" name="tt_val_txt_param2a" value="<?php echo date("Y-m-d"); ?>">
                                </fieldset>
                                <br/>
                                <fieldset id="tt_val_fs_param2b" class="fieldset_custom">
                                    <legend class="fieldset_custom">Select the time</legend>
                                    <input type="radio" id="tt_val_rd_param2b_opt1" name="tt_val_rd_param2b" value="NOW" checked="checked">
                                    <label for="NOW">NOW</label>
                                    <br/>
                                    <input type="radio" id="tt_val_rd_param2b_opt2" name="tt_val_rd_param2b" value="CUSTOM_TIME">
                                    <label for="CUSTOM_TIME" style="display:inline">Custom Time: <br/>
                                        <label for="hours">Select hour:</label>
                                        <select id="tt_val_rd_param2_hour" name="tt_val_rd_param2_hour">
                                            <!-- Loop through hours 00 to 23 and create options -->
                                            <?php
                                            for ($hour = 0; $hour < 24; $hour++) {
                                                // Format hour with leading zero if less than 10
                                                $formatted_hour = sprintf("%02d", $hour);
                                                // Output option element
                                                echo "<option value='$formatted_hour'>$formatted_hour</option>";
                                            }
                                            ?>
                                        </select>
                                        <label for="minutes">Select minute:</label>
                                        <select id="tt_val_rd_param2_min" name="tt_val_rd_param2_min">
                                            <!-- Loop through minutes 00 to 59 and create options -->
                                            <?php
                                            for ($minute = 0; $minute < 60; $minute++) {
                                                // Format minute with leading zero if less than 10
                                                $formatted_minute = sprintf("%02d", $minute);
                                                // Output option element
                                                echo "<option value='$formatted_minute'>$formatted_minute</option>";
                                            }
                                            ?>
                                        </select>
                                    </label>
                                </fieldset>
                                <br/>
                                <fieldset id="tt_val_fs_param3" class="fieldset_custom">
                                    <legend class="fieldset_custom">Select the DC Type</legend>
                                    <input type="radio" id="tt_val_rd_param3_opt1" name="tt_val_rd_param3" value="1">
                                    <label for="1">1</label>
                                    &nbsp;
                                    <input type="radio" id="tt_val_rd_param3_opt2" name="tt_val_rd_param3" value="2">
                                    <label for="2">2</label>
                                    &nbsp;
                                    <input type="radio" id="tt_val_rd_param3_opt3" name="tt_val_rd_param3" value="3">
                                    <label for="3">3</label>
                                </fieldset>
                                <br/>
                            </div>
                            <div class="form-group">
                                <label>Notes :</label>
                                <input id="tt_notes" name="tt_notes" class="form-control" type="text" value="">
                            </div>
                            <br/><br/>
                            <div class="form-group">
                                <input id="tt_quick_entry_form_postbk" name="tt_quick_entry_form_postbk" type="hidden" value="1" />
                                <button type="submit" class="btn btn-default">Save</button>
                            </div>

						</form>
						
                        <?php if(isset($_POST['tt_quick_entry_form_postbk']) && ($_POST['tt_quick_entry_form_postbk'] == 1) && (strlen($msgError) === 0)){ ?>
                        <br/>
                        <div>
                            <strong>Submitted Value:</strong>
                            <ul>
                                <li>Event Value: <?php echo htmlentities($tt_val); ?></li>
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
<script type="text/javascript">
    // Get references to the hour and minute dropdown menus and the custom time radio button
    var hourSelect = document.getElementById("tt_val_rd_param2_hour");
    var minuteSelect = document.getElementById("tt_val_rd_param2_min");
    var customTimeRadioButton = document.getElementById("tt_val_rd_param2b_opt2");

    // Function to handle the change event of the hour and minute dropdown menus
    function updateTimeSelection() {
        // Check if either the hour or minute dropdown menu is selected
        if (hourSelect.value !== "" || minuteSelect.value !== "") {
            // If either dropdown menu is selected, select the "CUSTOM_TIME" radio button
            customTimeRadioButton.checked = true;
        }
    }

    // Attach change event listeners to the hour and minute dropdown menus
    hourSelect.addEventListener("change", updateTimeSelection);
    minuteSelect.addEventListener("change", updateTimeSelection);
</script>