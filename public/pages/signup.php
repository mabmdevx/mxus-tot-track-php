<?php

$msg_error = "";

if(isset($_POST['tt_postbk_signup']) && $_POST['tt_postbk_signup'] == 1) {

    $tt_username = htmlentities(trim($_POST['tt_username']));
    $tt_password = password_encryption(htmlentities(trim($_POST['tt_password'])));
    $tt_baby_name = htmlentities(trim($_POST['tt_baby_name']));

    // Validations
    if( strlen($tt_username)==0 || strlen($tt_password)==0 || strlen($tt_baby_name)==0 ){
        $msg_error .="Please Provide Valid Username, Password and Baby Name";
    }

    if(strlen($msg_error) === 0){

        $signup_response = user_signup($tt_username, $tt_password, $tt_baby_name);

        if($signup_response == "ERROR_USER_ALREADY_EXISTS"){
            $msg_error = "ERROR: User already exists.";
        } else {
            $tt_user_uuid = $signup_response;
        }

        if(strlen($msg_error) === 0){

            if($tt_user_uuid > 0){

                $login_check_flag = true;
                $_SESSION['tt_username'] = $tt_username;
                $_SESSION['tt_user_uuid'] = $tt_user_uuid;
                $_SESSION['tt_baby_uuid'] = get_selected_baby_by_user_uuid($_SESSION['tt_user_uuid']);

                header("Location: ".HOME_PAGE."?pg=dashboard");
                exit;

            }
            else{
                $msg_error = "Something went wrong. Please try again.";
            }
        
        }
    }
}

?>
<div class="row">
    <div class="col-md-4 col-md-offset-4">
    	<h3 style="font-size:18px;text-align:center;padding-top:30px;"><strong><?php echo SITE_TITLE; ?></strong></h3>
        <div class="login-panel panel panel-default" style="margin-top:10% !important">
            <div class="panel-heading">
                <h3 class="panel-title">Signup</h3>
            </div>
            <div class="panel-body">
                <form role="form" method="post">
                    <fieldset>
                        <div class="form-group">
                            <div align="center" style="color:#FF3333"><?php echo $msg_error; ?></div>
                        </div>
                        <div class="form-group">
                            <label>Username</label><input id="tt_username" name="tt_username" class="form-control" placeholder="Enter your Username" type="text" autofocus>
                        </div>
                        <div class="form-group">
                            <label>Password</label><input id="tt_password" name="tt_password" class="form-control" placeholder="Enter your Password" type="password">
                        </div>
                        <div class="form-group">
                            <label>Baby Name</label><input id="tt_baby_name" name="tt_baby_name" class="form-control" placeholder="Enter the Baby Name" type="text">
                        </div>
                        <br/>
                        <button type="submit" class="btn btn-lg btn-info btn-block">Signup</button>
                        <br/>
                    </fieldset>
                    <input  id="tt_postbk_signup" name="tt_postbk_signup" type="hidden" value="1" />
                </form>
            </div>
        </div>
        <footer class="navbar-default" style="margin-top:50px">
            <div>
                <div class="row">
                    <div style="margin:0 auto; text-align:center">TotTrack by <a href="https://webserve.xyz" target="_blank">WebServe</a></div>
                </div>
            </div>
        </footer>
    </div>
</div>