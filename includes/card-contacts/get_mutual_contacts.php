<?php

ob_start();
require_once('../../session_setup.php');
if (!isset($_SESSION['user_id'])) {
	echo 'expired';
}
require_once '../database_config.php';
include_once('model.php');
//require_once (__DIR__.'/../mg_get_card_details.php');
include_once(__DIR__ . '/../shared_functions.php');
//include_once('../shared/check_card_settings.php');
include_once('../../utilities/request_result.php');
require_once '../../ChromePhp.php';
$user_id = $_SESSION["user_id"];
$card_id = $_REQUEST["card_id"];
$card_type = '';
$r = new Result();
$query0 = sprintf("select card_type from card c where card_id = '%s'", $card_id);
$result0 = $r->handleQuery($conn, $query0);
$r->data = array();
while ($row = mysqli_fetch_array($result0)) {
	$card_type = $row['card_type'];
}
$r = new Result();
/*
 * 15/11/2015 Marco Gasi
 * modified query to get the rating awarded by each user to the current bc
 * also added the query result for rating to the $a array at line 67
 * other files interested by this change:
 * shared.js: added re-initialization of the star-rating plugin at line 41
 * card-contacts/mutual_contacts.hbs: added line 19 to display rating
 */

$query1 = sprintf("select plan_id from subscription where card_id='$card_id'");
$result1 = mysqli_query($conn, $query1);
if ($result1) {
	$row = mysqli_fetch_row($result1);
	$plan_id = isset($row[0]) ? $row[0] : 1;
	ChromePhp::log("PLAN_ID = " . $plan_id);
} else {
	ChromePhp::log("PLAN_ID = not found");
}

/*
 * FOLLOWING CODE GET ALL USER IDS WHOSE CARDS ARE IN MY FOLDERS
 */
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

//if (in_array($plan_id, array(3, 6, 11, 14)))
$s = false;
if ($s) {
	/*
	 * removed line	and ccc.user_id != '%s'
	 * after where ccc.card_id = '%s'
	 * to get all
	 */
	$query = sprintf("select distinct"
		. " c.card_type"
		. ", cc.rating"
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
		. " inner join subscription s on s.card_id = cc.card_id and cc.card_id = '$card_id'"
		. " and s.plan_id in (3, 6, 11, 14)"
		. " WHERE cc.user_id IN('" . implode("','", $myheld) . "')");
//		. " and u2.user_id in "
//		. "( select ccc.user_id from card_contact ccc "
//		. " where ccc.card_id = '%s' and ccc.user_id != '%s' and ccc.private = 0)"
//		. " where cc.user_id='$user_id' ", $card_id, $user_id);
	ChromePhp::log($query);
	$result = $r->handlequery($conn, $query);
	$r->data = array();
//	while ( $row = mysqli_fetch_array( $result ) )
	while ($row = mysqli_fetch_assoc($result)) {
		$can_show = GetSetting($card_id, $card_type, "mutual_contacts", $conn);
		//	$private = $row[ 'private' ];
		//  if($can_show->requested_setting && !$private) {
		if ($can_show->requested_setting) {
			//	if ( !$private ) {
			$a = array(
				'contact_user_id' => $row['user_id'],
				'contact_first_name' => $row['first_name'],
				'contact_last_name' => $row['last_name'],
				'contact_phone' => $row['phone'],
				'contact_mobile' => $row['mobile'],
				'contact_email_address' => $row['email_address'],
				'contact_website_link' => $row['website_link'],
				'contact_company_name' => $row['company_name'],
				'contact_profile_image' => $row['profile_image'],
				'mutual_user_id' => $row['user_id'],
				'card_type' => $row['card_type'],
				'contact_rating' => $row['rating']
			);
			ChromePhp::log('mututal contact id: ' . $row['user_id']);
			$o = (object) $a;
			array_push($r->data, $o);
		}
	}
} else {
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
//		. " where cc.user_id in ' " . implode("','", $downloaded_cards) . " ' ";
//		. " (select ccc.user_id"
//		. " from card_contact ccc"
//		. " where ccc.card_id in ( select mycard.card_id from card mycard where mycard.user_id='$user_id') and ccc.user_id != '%s' and ccc.private = 0)", $card_id, $user_id)
//		. " and ccc.user_id != '%s' and ccc.private = 0)", $user_id);

	ChromePhp::log('query mutual is ' . $query);
	$result = $r->handlequery($conn, $query);
	$r->data = array();
//	while ( $row = mysqli_fetch_array( $result ) )
	while ($row = mysqli_fetch_assoc($result)) {
		$can_show = GetSetting($card_id, $card_type, "mutual_contacts", $conn);
		//	$private = $row[ 'private' ];
		//  if($can_show->requested_setting && !$private) {
		if ($can_show->requested_setting) {
			//	if ( !$private ) {
			$a = array(
				'contact_user_id' => $row['user_id'],
				'contact_first_name' => $row['first_name'],
				'contact_last_name' => $row['last_name'],
				'contact_phone' => $row['phone'],
				'contact_mobile' => $row['mobile'],
				'contact_email_address' => $row['email_address'],
				'contact_website_link' => $row['website_link'],
				'contact_company_name' => $row['company_name'],
				'contact_profile_image' => $row['profile_image'],
				'mutual_user_id' => $row['user_id'],
				'card_type' => $row['card_type']
			);
			ChromePhp::log('mututal contact no plan id: ' . $row['user_id']);
			$o = (object) $a;
			array_push($r->data, $o);
		}
	}

//	$query_users_with_my_cards = "select cc.user_id";
}
/*
 * This is the original query (06/08/2015)
  $query = sprintf( "
  select distinct cc.private, u2.first_name, u2.last_name, u2.profile_image, u2.user_id
  from card_contact cc
  inner join user u on cc.user_id = u.user_id
  inner join card c on c.card_id = cc.card_id
  inner join user u2 on c.user_id = u2.user_id
  and cc.user_id = '%s'
  and u2.user_id in (
  select u.user_id from card_contact cc
  inner join user u on cc.user_id = u.user_id
  where cc.card_id = '%s'
  and cc.user_id != '%s'
  and cc.private = 0)
  ", $user_id, $card_id, $user_id );
 * */
echo json_encode($r);
ob_end_flush();
