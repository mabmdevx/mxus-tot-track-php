<?php

$msg_error = ""; // Clear Error Msg
$msg_success = ""; // Clear Success Msg

// Get User ID of logged in user
$tt_user_uuid = get_tt_user_uuid();
$tt_user_id = get_tt_user_id($tt_user_uuid);

// Save Selected Baby
if(isset($_POST['tt_form_save_selected_baby_postbk']) && ($_POST['tt_form_save_selected_baby_postbk']  == 1)){

    // Get the form values
    $tt_selected_baby_uuid_per_user = ""; // Note: This is Baby UUID
    if(isset($_POST['tt_selected_baby_uuid_per_user'])){
        $tt_selected_baby_uuid_per_user = htmlentities(trim($_POST['tt_selected_baby_uuid_per_user']));
    }

     // Validations
    if(strlen($tt_selected_baby_uuid_per_user) === 0){
        $msg_error = "Please select a baby before submitting";
    }
    
    if(strlen($msg_error) === 0){
        save_selected_baby($tt_selected_baby_uuid_per_user, $tt_user_id);
        $msg_success = "Selected baby saved successfully";
    }
}

// Submit Add Baby
if(isset($_POST['tt_form_add_baby_postbk']) && ($_POST['tt_form_add_baby_postbk']  == 1)){

    // Get the form values
    $tt_add_baby_name = "";
    if(isset($_POST['tt_add_baby_name'])){
        $tt_add_baby_name = htmlentities(trim($_POST['tt_add_baby_name']));
    }

     // Validations
    if(strlen($tt_add_baby_name) === 0){
        $msg_error = "Please enter the Baby Name before submitting";
    }
    
    $baby_add_success = false;
    if(strlen($msg_error) === 0){
        add_baby($tt_add_baby_name, $tt_user_id);
        $baby_add_success = true;
        $msg_success = "Baby added successfully";
    }
}

// Get Baby ID of selected baby
$tt_baby_uuid = get_selected_baby_by_user_uuid($tt_user_uuid);
$tt_baby_id = get_tt_baby_id($tt_baby_uuid);

// Get the list of babies
$babies_list = list_babies($tt_user_id);

?>
<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">Babies</h1>
    </div>
    <!-- /.col-lg-12 -->
</div>
<!-- /.row -->
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                List of babies
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
                        <form role="form" class="form-inline" id="tt_form_save_selected_baby" name="tt_form_save_selected_baby" method="POST">
                            <table class="table table-striped table-bordered table-hover" style="margin-bottom:0px;">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Baby Name</th>
                                        <!--<th>Baby ID</th>-->
                                        <th style="text-align:center">Selected Baby</th>          
                                    </tr>
                                </thead>
                                <tbody>
                                <?php
                                if(isset($babies_list) && $babies_list!=false && count($babies_list)>0)
                                {
                                    for($k=0;$k<count($babies_list);$k++)
                                    {
                                ?>
                                    <tr>
                                        <td><?php echo $k+1; ?></td>
                                        <td><?php echo htmlentities($babies_list[$k]['tt_baby_name']); ?></td>
                                        <!--<td><?php echo htmlentities($babies_list[$k]['tt_baby_uuid']); ?></td>-->
                                        <td style="text-align:center">
                                            <input type="radio" id="tt_selected_baby_uuid_per_user<?php echo $k+1; ?>" name="tt_selected_baby_uuid_per_user" 
                                                value="<?php echo htmlentities($babies_list[$k]['tt_baby_uuid']); ?>" 
                                                <?php if($babies_list[$k]['tt_selected_baby_per_user'] == 1) { ?> checked="checked" <?php } ?>>
                                        </td>
                                    </tr>
                                <?php
                                    }
                                }
                                else
                                {
                                ?>
                                <tr>
                                    <td colspan="4" style="text-align:center">No Data Available</td>                                
                                    </tr>
                                <?php
                                }
                                ?>
                                </tbody>
                            </table>

                            <br/><br/>

                            <input id="tt_form_save_selected_baby_postbk" name="tt_form_save_selected_baby_postbk" type="hidden" value="1" />
                        
                            <div style="text-align:right">
                                <button type="submit" class="btn btn-success">Save Selected Baby</button>
                                &nbsp;&nbsp;
                                <button type="submit" class="btn btn-info" onclick="show_add_baby_form(); return false;">Add Baby</button>
                            </div>
                        </form>

                        <br/><br/>



                        <form role="form" class="form-inline" id="tt_form_add_baby" name="tt_form_add_baby" method="POST" style="display:none">	
                            <fieldset id="tt_filters" class="fieldset_custom">
                                <legend class="fieldset_custom">Add Baby</legend>
                                <br/>
                                <div class="form-group">
                                    <label>Baby Name: </label>
                                    <input id="tt_add_baby_name" name="tt_add_baby_name" class="form-control" type="text" placeholder="<Baby Name>" value="">
                                </div>
                                <br/><br/>
                                <div class="form-group">
                                    <input id="tt_form_add_baby_postbk" name="tt_form_add_baby_postbk" type="hidden" value="1" />
                                    <button type="submit" class="btn btn-success">Save</button>
                                    <button type="button" class="btn btn-default" onclick="hide_add_baby_form(); return false;">Close</button>
                                </div>
                                <br/><br/>
                            </fieldset>
                            <br/><br/>
                        </form>

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
<script type="text/javascript">
function show_add_baby_form() {
    document.getElementById("tt_form_add_baby").style.display = "block";
}
function hide_add_baby_form() {
    document.getElementById("tt_form_add_baby").style.display = "none";
}
</script>
<?php
// Show/Hide Add Baby Form
if(isset($_POST['tt_form_add_baby_postbk']) && ($_POST['tt_form_add_baby_postbk']  == 1)){
    if($baby_add_success == false){
        echo '<script type="text/javascript">show_add_baby_form();</script>';
    } else{
        echo '<script type="text/javascript">hide_add_baby_form();</script>';
    }
}
?>