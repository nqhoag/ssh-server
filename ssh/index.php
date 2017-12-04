<?php
	error_reporting(0);
    session_start();
    If(!isset($_SESSION['user_id'])){
        header("Location: login.php");
    }
    include_once "header.php";
    require_once("conf.php");
    $mod = "home";
    If(isset($_GET['mod'])){
        $mod = $_GET["mod"];
    }
    switch ($mod){
        case "get":
			include 'getall.php';
			break;
        case "clear":
			include 'clear.php';
			break;
        case "up":
			include 'up.php';
			break;
		case "up2":
			include 'up-auto.php';
			break;
		case "list":
			include "list.php";
			break;
        default:
			include 'home.php';
			break;
    }
    include_once "footer.php";
?>
