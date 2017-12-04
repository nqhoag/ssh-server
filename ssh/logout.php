<?php
	session_start();
	session_unset();
        If(!isset($_SESSION['user_id']))
			header("Location: index.php?mod=login");
	exit($_SESSION["user_id"]);
?>