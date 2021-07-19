<?php

//ob_start();
require_once(__DIR__ . '/../../session_setup.php');
if (!isset($_SESSION['user_id'])) {
	echo json_encode('expired');
	exit;
}
require_once __DIR__ . '/../database_config.php';
include_once('models.php');
include_once(__DIR__ . '/../../utilities/request_result.php');
include_once(__DIR__ . '/../../utilities/mail.class.php');
$r = new Result();
$card_id = trim($_REQUEST['card_id']);
$user_requested_by = $_SESSION['user_id'];
$date_string = $_POST['date_string'];
date_default_timezone_set('Europe/Istanbul');
//$card_link = new CardLink( NULL, $user_requested_by, $card_id, "ACCEPTED", new DateTime( 'NOW' ), NULL );
$card_link = new CardLink(NULL, $user_requested_by, $card_id, "ACCEPTED", $date_string, NULL);
//Create relevant card_link entry for accounting purposes
//=============QUERY=============
//TODO: String interpolation should use full obj card_link, not separate properties as params
$query = "SELECT requested_date FROM card_link WHERE user_requested_by='$user_requested_by' AND card_id_requested='$card_id'";
$result = mysqli_query($conn, $query);
if ($result) {
	if (mysqli_num_rows($result) > 0) {
		$msg = 'Failed inserting card_contact2';
		echo json_encode($msg);
		exit;
	} else {
		$query = sprintf("INSERT INTO card_link (user_requested_by,card_id_requested,link_status,date_requested,date_accepted,request_origin) VALUES ('%s','%s','%s','$date_string','$date_string','add_contact')", $card_link->user_requested_by, $card_link->card_id_requested, $card_link->link_status);
		$result = $r->handleQuery($conn, $query);
//=============QUERY=============
//Create the card_contact link for the user requesting the link
//=============QUERY=============
		$query = sprintf("INSERT INTO card_contact (user_id,card_id,private) values ('%s','%s', 0)", $user_requested_by, $card_id);
		$result = $r->handleQuery($conn, $query);
//return to ajax quickly
		echo json_encode($r);
	}
} else {
	$query = sprintf("INSERT INTO card_link (user_requested_by,card_id_requested,link_status,date_requested,date_accepted,request_origin) VALUES ('%s','%s','%s','$date_string','$date_string','add_contact')", $card_link->user_requested_by, $card_link->card_id_requested, $card_link->link_status);
	$result = $r->handleQuery($conn, $query);
//=============QUERY=============
//Create the card_contact link for the user requesting the link
//=============QUERY=============
	$query = sprintf("INSERT INTO card_contact (user_id,card_id,private) values ('%s','%s', 0)", $user_requested_by, $card_id);
	$result = $r->handleQuery($conn, $query);
//return to ajax quickly
	echo json_encode($r);
}
