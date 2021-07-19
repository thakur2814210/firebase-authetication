<?php

ob_start();
require_once('../session_setup.php');
error_reporting(E_ALL);
ini_set('display_errors', 'On');
require_once '../ChromePhp.php';
//cadd to db
require_once 'absolute_database_config.php';
include_once('card-contacts/model.php');
//require_once (__DIR__.'/../mg_get_card_details.php');
include_once('shared_functions.php');
$card_id = $_REQUEST['card_id'];
$user_id = $_SESSION["user_id"];

/*
 * get card_type
 */
$q_card_type = sprintf("select card_type from card where card_id='%s'", $card_id);
$r_card_type = mysqli_query($conn, $q_card_type);
$row_card_type = mysqli_fetch_row($r_card_type);
$card_type = $row_card_type[0];

/*
 * get mutul contacts
 */
$query = sprintf("
			select distinct cc.card_id, u2.user_id
			from card_contact cc
			inner join user u2 on cc.user_id = u2.user_id
			inner join card c on c.card_id = '$card_id'
			inner join subscription s on s.card_id = cc.card_id
			and cc.card_id = '$card_id'
			and s.plan_id in (3, 6, 11, 14)
			and u2.user_id in (
				select ccc.user_id from card_contact ccc 
				where ccc.card_id = '%s'
				and ccc.user_id != '%s'
				and ccc.private = 0)
		", $card_id, $user_id);
$q_downloaded_cards = "select card_id from card_contact where user_id='$user_id'";
$r_downloaded_cards = mysqli_query($conn, $q_downloaded_cards);
$downloaded_cards = array();
while ($row = mysqli_fetch_assoc($r_downloaded_cards)) {
	array_push($downloaded_cards, $row['card_id']);
}
$cards = implode(',', $downloaded_cards);
$q_my_held = "SELECT user_id FROM card WHERE card_id IN('" . implode("','", $downloaded_cards) . "')";
$r_my_held = mysqli_query($conn, $q_my_held);
$myheld = array();
while ($row = mysqli_fetch_assoc($r_my_held)) {
	array_push($myheld, $row['user_id']);
}
$held = implode(',', $myheld);
$query = sprintf("select distinct"
	. " c.card_type"
	. ", cc.user_id"
	. ", u2.first_name"
	. ", u2.last_name"
	. ", u2.profile_image"
	. ", u2.user_id"
	. ", u2.phone"
	. ", u2.mobile"
	. ", u2.email_address"
	. ", u2.website_link"
	. ", u2.company_name"
	. " from card_contact cc"
	. " inner join user u2 on cc.user_id = u2.user_id"
	. " inner join card c on c.card_id = '$card_id'"
	. " and cc.card_id = '$card_id' WHERE cc.user_id IN('" . implode("','", $myheld) . "')");

//Chromephp::log('query is ' . $query);
$result = mysqli_query($conn, $query);
//Chromephp::log('error: ' . mysqli_error($conn));
$mutual_ids = array();
while ($row = mysqli_fetch_assoc($result)) {
	$mutual_ids[] = $row['user_id'];
//	Chromephp::log('mututal_ids: ' . $row['user_id']);
}

/*
 * third party comment are only for professional card
 */
if (strtolower($card_type) == 'personal' || strtolower($card_type) == 'corporate') {
	$q_comments = "select user_id, comment_id, comment, timestamp from card_comment where card_id = '$card_id' ORDER BY timestamp DESC";
} else {
	/*
	 * third party comments only allowed for card whose plan is in 3, 6, 11, 14
	 */
	$allowed_plan = array(
		"3",
		"6",
		"11",
		"14");
	$q_plan = "SELECT plan_id FROM subscription WHERE card_id='$card_id'";
//	Chromephp::log('query is ' . $q_plan);
	$r_plan = mysqli_query($conn, $q_plan);
//	Chromephp::log('error: ' . mysqli_error($conn));
	if ($r_plan) {
		$row_plan = mysqli_fetch_row($result);
//			$plan_id = $row2[0]; //this if we want activate paid plans
		$plan_id = "3";
		if (in_array($plan_id, $allowed_plan)) {
			$q_comments = "select user_id, comment_id, comment, timestamp from card_comment where card_id = '$card_id' ORDER BY timestamp DESC";
		} else {
			$q_comments = "select user_id, comment_id, comment, timestamp from card_comment where card_id = '$card_id' AND user_id='" . $_SESSION['user_id'] . "' ORDER BY timestamp DESC";
		}
	} else {
		$q_comments = "select user_id, comment_id, comment, timestamp from card_comment where card_id = '$card_id' AND user_id='" . $_SESSION['user_id'] . "' ORDER BY timestamp DESC";
	}
}


$r_comments = mysqli_query($conn, $q_comments);
$comments = array();
if ($r_comments) {
//	Chromephp::log('result exists');
	while ($row_comments = mysqli_fetch_assoc($r_comments)) {
		$q_user_name = "select first_name, last_name from user where user_id='" . $row_comments['user_id'] . "'";
//		Chromephp::log($query2);
		$r_user_name = mysqli_query($conn, $q_user_name);
		if ($r_user_name) {
			$row_user_name = mysqli_fetch_row($r_user_name);
			$full_name = $row_user_name[0] . " " . $row_user_name[1];
		} else {
			$full_name = 'Anonymous';
		}
		if (in_array($row_comments['user_id'], $mutual_ids) || $row_comments['user_id'] === $user_id) {
			//user can delete comment
			if ($row_comments['user_id'] === $user_id) {
				$comments[] = array(
					"user_id" => $row_comments['user_id'],
					"full_name" => $full_name,
					"comment_id" => $row_comments['comment_id'] . '_no_del',
					"comment" => stripslashes($row_comments['comment']),
					"timestamp" => $row_comments['timestamp']);
			} else {
				//user can't delete comment
				$comments[] = array(
					"user_id" => $row_comments['user_id'],
					"full_name" => $full_name,
					"comment_id" => $row_comments['comment_id'],
					"comment" => stripslashes($row_comments['comment']),
					"timestamp" => $row_comments['timestamp']);
			}
		}
	}
	echo json_encode($comments);
} else {
	echo "false";
}
ob_end_flush();
