<?php

// Get User ID of logged in user
$tt_user_uuid = get_tt_user_uuid();
$tt_user_id = get_tt_user_id($tt_user_uuid);

// Get Baby ID of selected baby
$tt_baby_uuid = get_selected_baby_by_user_uuid($tt_user_uuid);
$tt_baby_id = get_tt_baby_id($tt_baby_uuid);


// Filters
$tt_filter_date = date("Y-m-d");
if(isset($_POST['tt_filter_date'])){
    $tt_filter_date = htmlentities(trim($_POST['tt_filter_date']));
}

$tt_filter_sort = "DESC";
if(isset($_POST['tt_filter_sort'])){
    $tt_filter_sort = htmlentities(trim($_POST['tt_filter_sort']));
}

$sessions_list = list_event_sessions($tt_baby_id, $tt_filter_date, $tt_filter_sort);

?>
<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">Sessions</h1>
    </div>
    <!-- /.col-lg-12 -->
</div>
<!-- /.row -->
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                List of sessions
            </div>
            <div class="panel-body">
                <div class="row">

                	<div id="msgdiv" style="height:30px;">
                    <p>
                    <?php if(isset($msg_error) && strlen($msg_error) > 0 ) { ?>
                    <div align="center" class="msg_error"><strong><?php echo $msg_error; ?></strong></div>
                    <?php } else if(isset($msg_success) && strlen($msg_success) > 0 ) { ?>
                    <div align="center" class="msg_success"><strong><?php echo $msg_success; ?></strong></div>
                    <?php } ?>
                    </p>
                    </div>

                    <div style="margin-left:15px; margin-bottom:5px"><label>Selected Baby : </label>&nbsp;<?php echo get_selected_baby_name($tt_baby_id); ?></div>
					
                    <div class="col-lg-12">

                        <form role="form" class="form-inline" id="tt_filter_events_form" name="tt_filter_events_form" method="POST" >	
                            <fieldset id="tt_filters" class="fieldset_custom">
                                <legend class="fieldset_custom">Filters</legend>
                                <br/>
                                <div class="form-group">
                                    <label>Date: </label>
                                    <input id="tt_filter_date" name="tt_filter_date" class="form-control" type="text" placeholder="<date>" value="<?php echo $tt_filter_date; ?>">
                                </div>
                                &nbsp;&nbsp;
                                <div class="form-group">
                                        <label>Sort: </label>
                                        <input type="radio" id="tt_filter_sort_asc" name="tt_filter_sort" value="ASC" checked="checked">
                                        Ascending
                                        &nbsp;
                                        <input type="radio" id="tt_filter_sort_desc" name="tt_filter_sort" value="DESC">
                                        Descending
                                </div>
                                <br/><br/>
                                <div class="form-group">
                                    <input id="filter_submit_postbk" name="filter_submit_postbk" type="hidden" value="1" />
                                    <button type="submit" class="btn btn-default">Filter</button>
                                </div>
                                <br/><br/>
                            </fieldset>
						</form>

                        <br/><br/>

                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover" style="margin-bottom:0px;">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Session ID</th>
                                        <th>Start Event ID</th>
                                        <th>Stop Event ID</th>
                                        <th>Date</th>
                                        <th>Type</th> 
                                        <th>Time Start</th> 
                                        <th>Time End</th> 
                                        <th>Duration</th> 
                                        <th>Feed Side</th> 
                                        <th>Self Detach</th> 
                                        <th>DC Type</th>
                                        <th>Created On</th>
                                        <th>Updated On</th>                        
                                    </tr>
                                </thead>
                                <tbody>
                                <?php
                                if(isset($sessions_list) && $sessions_list!=false && count($sessions_list)>0)
                                {
                                    for($k=0;$k<count($sessions_list);$k++)
                                    {
                                ?>
                                    <tr style="<?php echo row_color_sessions(htmlentities($sessions_list[$k]['tt_es_type'])); ?>">
                                        <td><?php echo $k+1; ?></td>
                                        <td><?php echo htmlentities($sessions_list[$k]['tt_es_id']); ?></td>
                                        <td><?php echo htmlentities($sessions_list[$k]['tt_es_event_id_start']); ?></td>
                                        <td><?php echo htmlentities($sessions_list[$k]['tt_es_event_id_end']); ?></td>
                                        <td><?php echo htmlentities($sessions_list[$k]['tt_es_date']); ?></td>
                                        <td><?php echo htmlentities($sessions_list[$k]['tt_es_type']); ?></td>
                                        <td><?php echo htmlentities($sessions_list[$k]['tt_es_time_start']); ?></td>
                                        <td><?php echo htmlentities($sessions_list[$k]['tt_es_time_end']); ?></td>
                                        <td><?php echo htmlentities($sessions_list[$k]['tt_es_time_duration']); ?></td>
                                        <td><?php echo htmlentities($sessions_list[$k]['tt_es_feed_side']); ?></td>
                                        <td><?php echo htmlentities($sessions_list[$k]['tt_es_feed_self_detach']); ?></td>
                                        <td><?php echo htmlentities($sessions_list[$k]['tt_es_dc_type']); ?></td>
                                        <td><?php echo htmlentities($sessions_list[$k]['created_on']); ?></td>
                                        <td><?php echo htmlentities($sessions_list[$k]['updated_on']); ?></td>
                                    </tr>
                                <?php
                                    }
                                }
                                else
                                {
                                ?>
                                <tr>
                                    <td colspan="14" style="text-align:center">No Data Available</td>                                
                                    </tr>
                                <?php
                                }
                                ?>
                                </tbody>
                            </table>
                        </div>
                        <br/><br/>
                    </div>
                    <!-- /.col-lg-12 (nested) -->

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
