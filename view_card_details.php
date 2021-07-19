<?php

error_reporting(E_ALL);
ini_set('display_errors', 'On');
require_once('session_setup.php');
$bcn = $_REQUEST['card_id'];
create_cookie("viewCardDetails", $bcn);
if (!isset($_SESSION['user_id'])) {
//	if (!isset($_COOKIE["viewCardDetails"])){
//	}
	header("Location: index.php");
	exit;
} else {
	header("Location: dashboard.php");
}

