<?php

// Curent Timestamp
$now_timestamp = date("Y-m-d H:i:s");
$now_date_only = date("Y-m-d");
// Yesterday
$yesterday_date_only = date('Y-m-d', strtotime('-1 day'));


# Last Feed Stats
# {
$feed_in_progress = check_feed_in_progress();

# Time since last feed
$feed_diff_txt = get_time_since_last_feed();

# Duration of last feed
$last_feed_duration = get_duration_of_last_feed();

# Side of last feed
$last_feed_side_txt = get_side_of_last_feed();

# Start time of last feed
$last_feed_start_time = get_start_time_of_last_feed();

# End time of last feed
$last_feed_end_time = get_end_time_of_last_feed();

# Recommended time of next feed
$next_feed_recommended_time = get_recommended_time_of_next_feed();
# }


# Last Diaper Change Stats
# {
$last_diaper_change_stats_arr = get_last_diaper_change_stats();

# Time since last diaper change
$diaper_diff_txt = $last_diaper_change_stats_arr[0];

# Last diaper change type
$diaper_last_dc_type_txt = $last_diaper_change_stats_arr[1];
# }


# Stats for the day - Today
# {
# No. of feed sessions today
$count_feed_sessions_today = get_count_of_feed_sessions_for_date($now_date_only);

# Total duration of all feed sessions today
$total_duration_all_feed_sessions_today_txt = get_total_duration_of_all_feed_sessions_for_date($now_date_only);

# Total count of diapers used today
$count_diapers_total_today = get_total_count_of_diapers_used_for_date($now_date_only);

# Count of diapers used today - Pee
$count_diapers_pee_today = get_count_of_diapers_used_type_pee_for_date($now_date_only);

# Count of diapers used today - Poop
$count_diapers_poop_today = get_count_of_diapers_used_type_poop_for_date($now_date_only);
# }


# Stats for the day - Yesterday
# {
# No. of feed sessions yesterday
$count_feed_sessions_yesterday = get_count_of_feed_sessions_for_date($yesterday_date_only);

# Total duration of all feed sessions yesterday
$total_duration_all_feed_sessions_yesterday_txt = get_total_duration_of_all_feed_sessions_for_date($yesterday_date_only);

# Total count of diapers used yesterday
$count_diapers_total_yesterday = get_total_count_of_diapers_used_for_date($yesterday_date_only);

# Count of diapers used yesterday - Pee
$count_diapers_pee_yesterday = get_count_of_diapers_used_type_pee_for_date($yesterday_date_only);

# Count of diapers used yesterday - Poop
$count_diapers_poop_yesterday = get_count_of_diapers_used_type_poop_for_date($yesterday_date_only);
# }


# Overall Stats
# {
# Count of total diapers used overall
$count_diapers_total_overall = get_count_of_total_diapers_used_overall();

# Feed side percentage
$feed_side_pc_arr = get_feed_side_percentage();
$feed_side_pc_left = $feed_side_pc_arr[0];
$feed_side_pc_right = $feed_side_pc_arr[1];
# }

?>
<meta http-equiv="refresh" content="15">
<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">Dashboard</h1>
    </div>
    <!-- /.col-lg-12 -->
</div>
<!-- /.row -->
<div class="row">
    <div class="col-lg-12">
        <span style="font-size:18px">Welcome to <?php echo SITE_TITLE; ?></span>
        <br/><br/>
  
        <div class="panel panel-default">
            <div class="panel-heading">
                Stats
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-lg-12">
                        <fieldset class="fieldset_custom">
                            <legend class="fieldset_custom">Last feed</legend>
                            <?php //if($feed_in_progress === false) { ?>
                            <ul style="margin-left:-10px">
                                <li>Time elapsed since last feed: <b><?php echo $feed_diff_txt; ?></b></li>
                                <li>Duration of last feed: <b><?php echo $last_feed_duration. " minutes"; ?></b></li>
                                <li>Side of last feed: <b><?php echo $last_feed_side_txt; ?></b></li>
                                <li>Start time of last feed: <b><?php echo $last_feed_start_time; ?></b></li>
                                <li>End time of last feed: <b><?php echo $last_feed_end_time; ?></b></li>
                                <li>Recommended time of next feed: <b><?php echo $next_feed_recommended_time; ?></b></li>
                            </ul>
                            <?php //} else { ?>
                                <!--
                                <ul>
                                    <li><b>Feed currently in progress</b></li>
                                </ul>
                                -->
                            <?php //} ?>
                        </fieldset>
                        <br/>
                        <fieldset class="fieldset_custom">
                            <legend class="fieldset_custom">Last diaper change</legend>
                            <ul style="margin-left:-10px">
                                <li>Time since last diaper change: <b><?php echo $diaper_diff_txt; ?></b></li>
                                <li>Last diaper change type: <b><?php echo $diaper_last_dc_type_txt; ?></b></li>
                            </ul>
                        </fieldset>
                        <br/>
                        <fieldset class="fieldset_custom">
                            <legend class="fieldset_custom">Today</legend>
                            <ul style="margin-left:-10px">
                                <li>Count of feed sessions: <b><?php echo $count_feed_sessions_today; ?></b></li>
                                <li>Total duration of all feed sessions: <b><?php echo $total_duration_all_feed_sessions_today_txt; ?></b></li>
                                <li>Total count of diapers used: <b><?php echo $count_diapers_total_today; ?></b></li>
                                <li>Count of diapers used - Pee: <b><?php echo $count_diapers_pee_today; ?></b></li>
                                <li>Count of diapers used - Poop: <b><?php echo $count_diapers_poop_today; ?></b></li>
                            </ul>
                        </fieldset>
                        <br/>
                        <fieldset class="fieldset_custom">
                            <legend class="fieldset_custom">Yesterday</legend>
                            <ul style="margin-left:-10px">
                                <li>Count of feed sessions: <b><?php echo $count_feed_sessions_yesterday; ?></b></li>
                                <li>Total duration of all feed sessions: <b><?php echo $total_duration_all_feed_sessions_yesterday_txt; ?></b></li>
                                <li>Total count of diapers used: <b><?php echo $count_diapers_total_yesterday; ?></b></li>
                                <li>Count of diapers used - Pee: <b><?php echo $count_diapers_pee_yesterday; ?></b></li>
                                <li>Count of diapers used - Poop: <b><?php echo $count_diapers_poop_yesterday; ?></b></li>
                            </ul>
                        </fieldset>
                        <br/>
                        <fieldset class="fieldset_custom">
                            <legend class="fieldset_custom">Overall Summary</legend>
                            <ul style="margin-left:-10px">
                                <li>Total diapers used: <b><?php echo $count_diapers_total_overall; ?></b></li>
                                <!--<li>Total count of diapers used in last 30 days: <b><?php // echo $count_diapers_total_thirty_days; ?></b></li>-->
                                <!--<li>Total duration of all feed sessions: <b><?php // echo $total_duration_all_feed_sessions_yesterday_txt; ?></b></li>-->
                                <li>Feed Side %: <b>LEFT: <?php echo $feed_side_pc_left."%"; ?></b>&nbsp;|&nbsp;<b>RIGHT: <?php echo $feed_side_pc_right."%"; ?></b></li>
                            </ul>
                        </fieldset>
                    </div><!-- /.col-lg-4 (nested) -->

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
<!-- /.row -->
