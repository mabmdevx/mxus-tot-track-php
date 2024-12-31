<?php

$msgError = "";
$tt_remember_me_token_val = "tt-rm-7f3f04a9-dddb-4f54-8383-357f09f63a64";


if(isset($_POST['checkloginpostbk']) && ($_POST['checkloginpostbk']==1) )
{

	$login_check_flag = false;

	if(isset($msgError) && strlen($msgError)==0){ // If no error, proceed, else display error to user

        // If "Remember Me" cookie is set (On subsequent page loads), login using the "Remember Me" cookie info
        if (isset($_COOKIE['tt_rm_token']) && !isset($_SESSION['username'])) {

            $tt_rm_token = $_COOKIE['tt_rm_token'];
            $tt_rm_username = $_COOKIE['tt_rm_username'];
            $tt_rm_user_uuid = $_COOKIE['tt_rm_user_uuid'];
            $tt_rm_baby_uuid = $_COOKIE['tt_rm_baby_uuid'];

            // Look up the user with the token from the database
            //$known_user_rm_token = find_user_by_token($token); - for later
            $known_user_rm_token = $tt_remember_me_token_val;

            if ($known_user_rm_token === $tt_remember_me_token_val) {

                // Log in the user using the cookie
                $login_check_flag = true;
                $_SESSION['username'] = $tt_rm_username;

            } else {
                // If token is not valid, delete the cookie
                setcookie('tt_rm_token', '', time() - 3600);
                setcookie('tt_rm_username', '', time() - 3600);
                setcookie('tt_rm_user_uuid', '', time() - 3600);
                setcookie('tt_rm_baby_uuid', '', time() - 3600);
            }

        } else {
        // If "Remember Me" cookie is not set, login using username and password

            // Login using username and password
            $input_username = htmlentities(trim($_POST['username']));
            $input_password = htmlentities(trim($_POST['password']));

            $input_remember_me = "";
            if(isset($_POST['remember_me'])){
                $input_remember_me = htmlentities(trim($_POST['remember_me']));
            }

            // Validations
            if( strlen($input_username)==0 || strlen($input_password)==0 ){
                $msgError = "Please provide valid Username and Password";
            }

            if(isset($msgError) && strlen($msgError)==0){ // If no error, proceed, else display error to user

                // Look up the user with the username from the database
                $known_user = find_user_by_username($input_username);

                if ($known_user) {

                    $known_user_password_hash = $known_user['tt_password'];

                    if(password_verification($input_password, $known_user_password_hash)){
                    
                        $login_check_flag = true;
                        $_SESSION['username'] = $input_username;
                        $_SESSION['tt_user_uuid'] = $known_user['tt_user_uuid'];
                        $_SESSION['tt_baby_uuid'] = get_selected_baby_by_user_uuid($_SESSION['tt_user_uuid']);


                        // When user logs in and checks "Remember Me"
                        if ($input_remember_me === "1") {

                            //$tt_remember_me_token = generate_random_token(); // Generate a random token - for later
                            $tt_remember_me_token = $tt_remember_me_token_val;

                            setcookie('tt_rm_token', $tt_remember_me_token, time() + (30 * 24 * 3600)); // Set cookie to expire in 30 days
                            setcookie('tt_rm_username', $input_username, time() + (30 * 24 * 3600)); // Set cookie to expire in 30 days

                            // Save User UUID and Baby UUID in the cookie for Remember Me
                            setcookie('tt_rm_user_uuid', $_SESSION['tt_user_uuid'], time() + (30 * 24 * 3600)); // Set cookie to expire in 30 days
                            setcookie('tt_rm_baby_uuid', $_SESSION['tt_baby_uuid'], time() + (30 * 24 * 3600)); // Set cookie to expire in 30 days

                            // Save $rm_token in the database linked to the user's account - for later
                        }

                    } else {
                        $msgError = "Invalid Password. Please try again.";
                    }
                
                } else {
                    $msgError = "Please provide valid Username and Password.";
                }
            
            }

        }
		

		if(isset($login_check_flag) && ($login_check_flag === true))
		{
			header("Location: ".HOME_PAGE."?pg=dashboard");	
			exit;

		}
	
	}
}

?>
<div class="row">
    <div class="col-md-4 col-md-offset-4">
    	<h3 style="font-size:18px;text-align:center;padding-top:30px;"><strong><?php echo SITE_TITLE; ?></strong></h3>
        <div class="login-panel panel panel-default" style="margin-top:10% !important">
            <div class="panel-heading">
                <h3 class="panel-title">Please Sign In</h3>
            </div>
            <div class="panel-body">
                <form role="form" method="post">
                    <fieldset>
                   		<div class="form-group">
                            <div align="center" style="color:#FF3333"><?php echo $msgError; ?></div>
                        </div>
                        <div class="form-group">
                            <input id="username" name="username" class="form-control" placeholder="Username" type="text" autofocus>
                        </div>
                        <div class="form-group">
                            <input id="password" name="password" class="form-control" placeholder="Password" type="password">
                        </div>
                        <div class="checkbox">
                            <label>
                                <input id="remember_me" name="remember_me" type="checkbox" value="1">Remember Me
                            </label>
                        </div>
                        <button type="submit" class="btn btn-lg btn-success btn-block">Login</button>
                        
                    </fieldset>
                    <input name="checkloginpostbk" type="hidden" id="checkloginpostbk" value="1" />
                </form>
            </div>
            <div class="panel-body">
                <a href="?pg=signup" class="btn btn-lg btn-info btn-block">Signup</a>
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