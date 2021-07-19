<?php

error_reporting(E_ALL);
ini_set('display_errors', 'On');
require_once 'db.php';

$urls = array();
//$url = "http://www.cardition.com/tests/print_ot_get_card_details_urls.php";
$user_ids = array();
$card_ids = array();
$ghost_user_ids = array();
$ghost_card_ids = array();

$q_users = "select user_id from user";
$r_user = mysqli_query($conn, $q_users);
while ($row_user = mysqli_fetch_assoc($r_user)) {
	array_push($user_ids, $row_user['user_id']);
}

$q_cards = "select card_id from card";
$r_cards = mysqli_query($conn, $q_cards);
while ($row_cards = mysqli_fetch_assoc($r_cards)) {
	array_push($card_ids, $row_cards['card_id']);
}
$q_users2 = "select user_id from user";
$r_user2 = mysqli_query($conn, $q_users2);
while ($row_user2 = mysqli_fetch_assoc($r_user2)) {
	$user_id = $row_user2['user_id'];
	$q_cards2 = "select card_id, user_id from card where user_id != '$user_id' ";
	$r_cards2 = mysqli_query($conn, $q_cards2);
//	echo mysqli_error($conn);
	while ($row_cards2 = mysqli_fetch_assoc($r_cards2)) {
		$card_id = $row_cards2['card_id'];
//		$assigned_id = $row_cards2['assigned_id'];
//		echo $row_cards2['user_id']."<br>";
		if (!in_array($row_cards2['user_id'], $user_ids)) {
			if (!in_array($row_cards2['user_id'], $ghost_user_ids)) {
				array_push($ghost_user_ids, $row_cards2['user_id']);
			}
				$url = "http://www.cardition.com/cardition_api/ot_get_card_details.php?app_user_id=$user_id&card_id=$card_id";
				$link = "<a href='$url' target='_blank'>$url</a>";
//			if (!in_array($link, $ghost_card_ids)) {
			if (!array_key_exists($card_id, $ghost_card_ids)) {
//				array_push($ghost_card_ids, $card_id.' - '. $assigned_id);
//				array_push($ghost_card_ids, $card_id);
				$ghost_card_ids[$card_id] = $link;
//				array_push($ghost_card_ids, $link);
			}
		} else {
			$url = "http://www.cardition.com/cardition_api/ot_get_card_details.php?app_user_id=$user_id&card_id=$card_id";
			$link = "<a href='$url' target='_blank'>$url</a>";
			array_push($urls, $link);
		}
	}
}
echo microtime().'<br>';
$mt = microtime();
$a = explode('.', $mt);
echo str_replace(' ', '', end($a));
echo "<pre>";
echo "These user ids are registered as card owners but are not registred as users (" . count($ghost_user_ids) . "):<br>";
var_dump($ghost_user_ids);
echo "<br>These are the cards whose owner is missing in user table (" . count($ghost_card_ids) . "):<br>";
var_dump($ghost_card_ids);
echo "<br>Possible urls for ot_get_card_details (" . count($urls) . "):<br>";
var_dump($urls);
echo "</pre>";
