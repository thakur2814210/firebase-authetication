<?php

error_reporting(E_ALL);
ini_set('display_errors', 'On');
ob_start();
require_once('../../session_setup.php');
if (!isset($_SESSION['user_id'])) {
	echo json_encode('expired');
	exit;
}
include_once('model.php');
require_once ('../mg_get_card_details.php');
//include_once('../shared/check_card_settings.php');
include_once('../../utilities/request_result.php');
require_once __DIR__ . '/../absolute_database_config.php';
require_once '../../ChromePhp.php';
$user_id = $_SESSION["user_id"];
//$search_terms = str_replace(' ', ',',  $_POST[ "term" ]);
//$folder = $_GET[ "folder" ];
$folder = $_REQUEST["folder"];
//$search_terms = "'".str_replace(' ', "','",  $_GET[ "term" ])."'";
$request_result = new Result();
$request_result->message = $folder;
if ($folder === 'pc') {
	$conditions = "left join card c on c.card_id = cc.card_id "
		. "left join card_data cd on c.card_id = cd.card_id "
		. "left join user u on c.user_id = u.user_id "
//		. " join card_contact ccr on ccr.card_id = cc.card_id "
		. "where cc.user_id = '$user_id' AND c.user_id!='$user_id' AND cc.card_id=c.card_id AND (c.card_type='Personal' OR c.card_type='Corporate')";
//	$conditions = "JOIN card_data cd ON c.card_id = cd.card_id
//        JOIN user u ON c.user_id = u.user_id
//        LEFT JOIN card_contact cc on cc.card_id = c.card_id
//				WHERE c.user_id != '" . $user_id . "' AND (c.card_type='Personal' OR c.card_type='Corporate')";
} elseif ($folder === 'sp') {
//	$conditions = "left join card c on c.card_id = cc.card_id "
//		. "left join card_data cd on c.card_id = cd.card_id "
//		. "left join user u on c.user_id = u.user_id "
////		. "left join card_contact ccr on ccr.card_id = cc.card_id "
//		. "where cc.user_id = '$user_id' AND c.user_id!='$user_id' AND cc.card_id=c.card_id AND (c.card_type='Professional' OR c.card_type='Product' OR c.card_type='Service')";
	
	$conditions = "left join card c on c.card_id = cc.card_id"
		. " left join card_data cd on c.card_id = cd.card_id"
		. " left join user u on c.user_id = u.user_id"
		. " left join card_contact ccr on ccr.card_id = cc.card_id"
		. " where cc.user_id = '$user_id' AND (c.card_type='Professional' OR c.card_type='Product' OR c.card_type='Service')";	
	
//	$conditions = "JOIN card_data cd ON c.card_id = cd.card_id
//        JOIN user u ON c.user_id = u.user_id
//        LEFT JOIN card_contact cc on cc.card_id = c.card_id
//				WHERE c.user_id != '" . $user_id . "' AND (c.card_type='Professional' OR c.card_type='Product' OR c.card_type='Service')";
} else {
	$conditions = "JOIN card c on c.card_id = cc.card_id JOIN card_data cd ON c.card_id = cd.card_id JOIN user u ON c.user_id = u.user_id LEFT JOIN card_contact ccr on ccr.card_id = c.card_id INNER JOIN card_folder cf on cf.card_id = c.card_id AND cf.folder_id='$folder' WHERE cc.user_id = '$user_id'";
}
$allCards = "SELECT c.card_name, c.card_id, c.card_type,c.distributed_brand,c.category,c.sub_category,c.assigned_id,cd.canvas_front,cd.canvas_back,cd.links_front,cd.links_back,cd.landscape,u.first_name,u.last_name,u.profile_image,u.company_name,u.department_name,u.position,u.phone,u.mobile,u.email_address,u.title,u.website_link,cc.rating as your_rating,round(sum(cc.rating) / count(cc.rating), 1) as average_rating FROM card_contact cc $conditions";
$allCards = $allCards . " group by c.card_id order by average_rating desc";

$result = mysqli_query($conn, $allCards);
//$result = $r->handleQuery($conn,$allCards);
if (!$result) {
	$request_result->success = 0;
	$request_result->success = 0;
	$request_result->err = mysqli_error($conn);
} else {
	$request_result->success = 1;
}
$request_result->result = array();

//TODO: Revisit table structure that causes unnecessary complex query, the querying method used now resolves the complexity, but negatively effects performance
while ($row = mysqli_fetch_array($result)) {
	$can_show = GetSetting($row["card_id"], $row["card_type"], "visible_in_search", $conn);

	$visible_in_user_folder = GetSetting($row["card_id"], $row["card_type"], "mutual_contacts", $conn);
	$seen_in_user_folder = false;
	if ($visible_in_user_folder->requested_setting) {
		$seen_in_user_folder = true;
	}
	//check out the gem that is the first comment in this page
	//http://php.net/manual/en/language.types.boolean.php
	if ($can_show->requested_setting) {
		$link_status = "NONE";
		$cardLinkQuery = sprintf("
            SELECT link_status 
            FROM card_link 
            WHERE card_id_requested = '%s'
            AND user_requested_by = '%s' ORDER BY date_accepted DESC LIMIT 1", $row["card_id"], $user_id);
		//echo $cardLinkQuery;
		$link_query_result = mysqli_query($conn, $cardLinkQuery);

		if (!$link_query_result) {
//			$request_result->success = 0;
			$request_result->message = $cardLinkQuery;
			$request_result->err = mysqli_error($conn);
		} else {
//			$request_result->success = 1;
			while ($link_row = mysqli_fetch_array($link_query_result)) {
				$link_status = $link_row["link_status"];
			}
		}
		$card = new SearchedCardInFolder(
			$row["card_id"], $row["card_name"], $row["card_type"], $row["canvas_front"], $row["canvas_back"], $row["links_front"], $row["links_back"], $row["company_name"], $row["department_name"], $row["position"], $row["first_name"], $row["last_name"], $row['profile_image'], $row["distributed_brand"], $row["category"], $row["sub_category"], $row["phone"], $row["mobile"], $row["email_address"], $row["title"], $row["website_link"], $row["landscape"], $link_status, $can_show, $seen_in_user_folder, $row["your_rating"], $row["average_rating"], $row['assigned_id']);
		array_push($request_result->result, $card);
	}
}

echo json_encode($request_result);
ob_end_flush();
