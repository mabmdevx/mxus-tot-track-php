<?php

if(!isset($_GET['pg']))
{
	header("Location: ".HOME_PAGE."?pg=login");
}


if(isset($_GET['pg']))
{
	if( !isset($_SESSION['username']) && isset($_GET['pg']) && ($_GET['pg']!="login") )
	{
		header("Location: ".HOME_PAGE."?pg=login");
		exit;
	}
	else if( isset($_SESSION['username']) && isset($_GET['pg']) && ($_GET['pg']=="login") )
	{
		header("Location: ".HOME_PAGE."?pg=dashboard");
		exit;
	}
}

?>
