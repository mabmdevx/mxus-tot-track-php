<?php

# Error Reporting
//error_reporting(E_ALL);
error_reporting(E_ALL ^ E_DEPRECATED);

# Init
session_start();
ob_start();
$config_app = parse_ini_file('../config/app.ini');

# Libs
include_once("libs/lib.db.php");
include_once("libs/lib.app.php");

# Config
## Constants
define("SITE_TITLE", $config_app['SITE_TITLE']);
define("HOME_PAGE", $config_app['HOME_PAGE']);

## Timezone
date_default_timezone_set($config_app['TIMEZONE']);

## Account
define("SYS_USER", $config_app['SYS_USER']);
define("SYS_PASS", $config_app['SYS_PASS']);

## Statcounter
define("STATCOUNTER_PROJECT", $config_app['STATCOUNTER_PROJECT']);
define("STATCOUNTER_SECURITY", $config_app['STATCOUNTER_SECURITY']);

// Login check
include_once("login_check.php");

$page = "";
$pageRtn = false;
if(isset($_GET['pg']) && strlen($_GET['pg'])>0 ){
	$page = htmlentities($_GET['pg']);
} else {
	$page = "dashboard";
}

?>
<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?php echo SITE_TITLE; ?></title>

    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">

    <!-- Core CSS - Include with every page -->
    <link href="assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/font-awesome/css/font-awesome.css" rel="stylesheet">

    <!-- SB Admin CSS - Include with every page -->
    <link href="assets/css/sb-admin.css" rel="stylesheet">
	<link href="assets/css/styles.css" rel="stylesheet">
	
</head>

<body>

    <?php
    if($page === "login"){

        include_once("actions.php");

    } else {
    ?>
    <div id="wrapper">

        <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".sidebar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="<?php echo HOME_PAGE; ?>?pg=dashboard"><?php echo SITE_TITLE; ?></a>
            </div>
            <!-- /.navbar-header -->
        
        
            <ul class="nav navbar-top-links navbar-right">
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        Welcome, <?php echo $_SESSION['username']; ?><i class="fa fa-user fa-fw"></i>  <i class="fa fa-caret-down"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-user">
                        <li>
                            <a href="<?php echo HOME_PAGE; ?>?pg=logout"><i class="fa fa-sign-out fa-fw"></i>&nbsp;Logout</a>
                        </li>
                    </ul>
                    <!-- /.dropdown-user -->
                </li>
                <!-- /.dropdown -->
            </ul>
            <!-- /.navbar-top-links -->

        </nav>
        <!-- /.navbar-static-top -->

        <nav class="navbar-default navbar-static-side" role="navigation">
            <div class="sidebar-collapse">
                <ul class="nav" id="side-menu">             
                    <li>
                        <a href="<?php echo HOME_PAGE; ?>?pg=dashboard"><i class="fa fa-dashboard fa-fw"></i>&nbsp;Dashboard</a>
                    </li>
                    <li>
                        <a href="<?php echo HOME_PAGE; ?>?pg=quickentry"><i class="fa fa-wrench fa-fw"></i>&nbsp;Quick Entry</a>
                    </li> 
                    <li>
                        <a href="<?php echo HOME_PAGE; ?>?pg=manualentry"><i class="fa fa-edit fa-fw"></i>&nbsp;Manual Entry</a>
                    </li>   
                    <li>
                        <a href="<?php echo HOME_PAGE; ?>?pg=events"><i class="fa fa-table fa-fw"></i>&nbsp;Events</a>
                    </li> 
                    <li>
                        <a href="<?php echo HOME_PAGE; ?>?pg=sessions"><i class="fa fa-table fa-fw"></i>&nbsp;Sessions</a>
                    </li>   
                </ul>
                <!-- /#side-menu -->
            </div>
            <!-- /.sidebar-collapse -->
        </nav>
        <!-- /.navbar-static-side -->

        <div id="page-wrapper">
        <?php
            include_once("actions.php");
        ?> 
        </div>   
        <!-- /#page-wrapper -->

        <footer class="navbar-default" style="float:right; margin:20px">
            <div>
                <div class="row">
                    <div class="col-lg-12">TotTrack by <a href="https://webserve.xyz" target="_blank">WebServe</a></div>
                </div>
            </div>
        </footer>

    </div>
    <!-- /#wrapper -->
    <?php
    }
    ?>


    <!-- Core Scripts - Include with every page -->
    <script src="assets/js/jquery-1.10.2.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
    <script src="assets/js/plugins/metisMenu/jquery.metisMenu.js"></script>

    <!-- SB Admin Scripts - Include with every page -->
    <script src="assets/js/sb-admin.js"></script>

    <!-- Default Statcounter code for Tot Track
    https://tottrack.webserve.xyz -->
    <script type="text/javascript">
    var sc_project=<?php echo STATCOUNTER_PROJECT; ?>; 
    var sc_invisible=1; 
    var sc_security="<?php echo STATCOUNTER_SECURITY; ?>"; 
    </script>
    <script type="text/javascript"
    src="https://www.statcounter.com/counter/counter.js"
    async></script>
    <noscript><div class="statcounter"><a title="Web Analytics"
    href="https://statcounter.com/" target="_blank"><img
    class="statcounter"
    src="https://c.statcounter.com/<?php echo STATCOUNTER_PROJECT; ?>/0/<?php echo STATCOUNTER_SECURITY; ?>/1/"
    alt="Web Analytics"
    referrerPolicy="no-referrer-when-downgrade"></a></div></noscript>
    <!-- End of Statcounter Code -->

</body>

</html>
<?php
ob_flush();
?>