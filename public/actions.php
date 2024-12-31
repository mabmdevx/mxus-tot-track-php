<?php

switch($page)
{
    case "signup":
    $pageRtn=include_once("pages/signup.php");
    break;

    case "login":
    $pageRtn=include_once("pages/login.php");
    break;

    case "logout":
    $pageRtn=include_once("pages/logout.php");
    break;

    case "dashboard":
    $pageRtn=include_once("pages/dashboard.php");
    break;

    case "quickentry":
    $pageRtn=include_once("pages/quick_entry.php");
    break;

    case "manualentry":
    $pageRtn=include_once("pages/manual_entry.php");
    break;

    case "events":
    $pageRtn=include_once("pages/events.php");
    break;

    case "sessions":
    $pageRtn=include_once("pages/sessions.php");
    break;

    case "babies":
    $pageRtn=include_once("pages/babies.php");
    break;

    default:
    $pageRtn=include_once("pages/404page.php");
    break;
}

if($pageRtn == false)
{
    include_once("pages/404page.php");
}


?>
