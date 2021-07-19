<?php

require_once('../../session_setup.php');
if (!isset($_SESSION['user_id'])) {
	echo json_encode('expired');
	exit;
}
include_once('../card-contacts/model.php');
//include_once('../../utilities/request_result.php');
require_once ('../shared_functions.php');
require_once '../absolute_database_config.php';
include_once('../../utilities/request_result.php');
//$user_id = $_SESSION[ "user_id" ];
//$folder = strtolower($_REQUEST[ "folder" ]);
$user_id = $_SESSION['user_id'];
$request_result = new Result();
$request_result->result = array();

/* CARD FOR PERSONAL FOLDER */
//	$conditions = "join card c on c.card_id = cc.card_id
//        join card_data cd on c.card_id = cd.card_id
//        join user u on c.user_id = u.user_id
//        join card_contact ccr on ccr.card_id = cc.card_id
//        where cc.user_id = '$user_id' AND (c.card_type='Personal' OR c.card_type='Corporate')";
//$query = "SELECT c.card_name, c.card_id, c.card_type, c.distributed_brand, c.category, c.sub_category,	c.assigned_id,  cd.canvas_front, cd.canvas_back, cd.landscape, u.first_name, u.last_name,	u.profile_image, u.company_name, u.department_name, u.position, u.phone, u.mobile, u.email_address, u.title, u.website_link,	cc.rating as your_rating, round(sum(cc.rating) / count(cc.rating), 1) as average_rating FROM card_contact cc $conditions";
//$query .= " group by c.card_id";
//$conditions = "JOIN card_data cd ON c.card_id = cd.card_id
//			JOIN user u ON c.user_id = u.user_id
//			LEFT JOIN card_contact cc on cc.card_id = c.card_id
//			WHERE c.user_id != '$user_id' AND (c.card_type='Personal' OR c.card_type='Corporate')";
//$query = "SELECT c.card_id, c.card_type FROM card c $conditions";

$conditions = "LEFT JOIN card_folder cf ON cf.user_id='$user_id' INNER JOIN card c on c.card_id = cc.card_id WHERE c.user_id != '$user_id' AND cc.user_id = '$user_id' AND (c.card_type='Personal' OR c.card_type='Corporate')";
$query = "SELECT cc.card_id, cc.user_id, c.card_type FROM card_contact cc $conditions";

$query .= " group by cc.card_id";
$result = mysqli_query($conn, $query);
//ChromePhp::log( 'mysql query: ' . $query );
//ChromePhp::log( 'mysql error: ' . mysqli_error( $conn ) );
$cards_number = array();
if ($result) {
	while ($row = mysqli_fetch_array($result)) {
		$can_show = GetSetting($row["card_id"], $row["card_type"], "visible_in_search", $conn);
		//check out the gem that is the first comment in this page
		//http://php.net/manual/en/language.types.boolean.php
		if ($can_show->requested_setting) {
			$link_status = "NONE";
			$cardLinkQuery = sprintf("
            SELECT link_status
            FROM card_link
            WHERE card_id_requested = '%s'
            AND user_requested_by = '%s'", $row["card_id"], $user_id);
			//echo $cardLinkQuery;
			$link_query_result = mysqli_query($conn, $cardLinkQuery);
			if (!$link_query_result) {
//			$request_result->success = 0;
//			$request_result->message = $cardLinkQuery;
//			$request_result->err = mysqli_error( $conn );
			} else {
//			$request_result->success = 1;
				while ($link_row = mysqli_fetch_array($link_query_result)) {
					$link_status = $link_row["link_status"];
				}
			}
			if ($link_status === 'ACCEPTED') {
				array_push($cards_number, $row["card_id"]);
			}
		}
	}
}
array_push($request_result->result, array(
	'folder_id' => 'pc',
	'description' => 'Personal / Corporate',
	'cards_amount' => count($cards_number)));

/* CARDS FOR PRODUCT FOLDER */
//	$conditions = "join card c on c.card_id = cc.card_id
//        join card_data cd on c.card_id = cd.card_id
//        join user u on c.user_id = u.user_id
//        join card_contact ccr on ccr.card_id = cc.card_id
//        where cc.user_id = '$user_id' AND (c.card_type='Personal' OR c.card_type='Corporate')";
//$query = "SELECT c.card_name, c.card_id, c.card_type, c.distributed_brand, c.category, c.sub_category,	c.assigned_id,  cd.canvas_front, cd.canvas_back, cd.landscape, u.first_name, u.last_name,	u.profile_image, u.company_name, u.department_name, u.position, u.phone, u.mobile, u.email_address, u.title, u.website_link,	cc.rating as your_rating, round(sum(cc.rating) / count(cc.rating), 1) as average_rating FROM card_contact cc $conditions";
//$query .= " group by c.card_id order by average_rating desc";
//$conditions = "JOIN card_data cd ON c.card_id = cd.card_id
//        JOIN user u ON c.user_id = u.user_id
//        LEFT JOIN card_contact cc on cc.card_id = c.card_id
//				WHERE c.user_id != '$user_id' AND (c.card_type='Professional' OR c.card_type='Product' OR c.card_type='Service')";
//
//$query = "SELECT c.card_id, c.card_type FROM card c $conditions";
//$query .= " group by c.card_id";

//$conditions = "INNER JOIN card c on c.card_id = cc.card_id WHERE c.user_id != '$user_id' AND cc.user_id = '$user_id' AND (c.card_type='Personal' OR c.card_type='Corporate')";
//$query = "SELECT cc.card_id, cc.user_id, c.card_type FROM card_contact cc $conditions";

$conditions = "LEFT JOIN card_folder cf ON cf.user_id='$user_id' INNER JOIN card c on c.card_id = cc.card_id WHERE c.user_id != '$user_id' AND cc.user_id = '$user_id' AND (c.card_type='Professional' OR c.card_type='Product' OR c.card_type='Service')";
$query = "SELECT cc.card_id, cc.user_id, c.card_type FROM card_contact cc $conditions";
$query .= " group by c.card_id";
$result = mysqli_query($conn, $query);
$cards_number = array();
if ($result) {
	while ($row = mysqli_fetch_array($result)) {
		$cards_ids[] = $row["card_id"];
		$can_show = GetSetting($row["card_id"], $row["card_type"], "visible_in_search", $conn);
		//check out the gem that is the first comment in this page
		//http://php.net/manual/en/language.types.boolean.php
		if ($can_show->requested_setting) {
			$link_status = "NONE";
			$cardLinkQuery = sprintf("
            SELECT link_status
            FROM card_link
            WHERE card_id_requested = '%s'
            AND user_requested_by = '%s'", $row["card_id"], $user_id);
			//echo $cardLinkQuery;
			$link_query_result = mysqli_query($conn, $cardLinkQuery);
			if (!$link_query_result) {
//			$request_result->success = 0;
//			$request_result->message = $cardLinkQuery;
//			$request_result->err = mysqli_error( $conn );
			} else {
//			$request_result->success = 1;
				while ($link_row = mysqli_fetch_array($link_query_result)) {
					$link_status = $link_row["link_status"];
				}
			}
			if ($link_status === 'ACCEPTED') {
				array_push($cards_number, $row["card_id"]);
			}
		}
	}
}
array_push($request_result->result, array(
	'cards' => implode(' - ', $cards_number),
	'folder_id' => 'sp',
	'description' => 'Service / Product',
	'cards_amount' => count($cards_number)));


/* CARDS FOR OTHER FOLDERS */
$query = "SELECT f.folder_id, f.description, f.user_id, COUNT(cf.folder_id) AS card_amount "
	. "FROM folder f "
	. "LEFT JOIN card_folder cf ON f.folder_id=cf.folder_id AND f.user_id=cf.user_id "
	. "WHERE f.user_id = '$user_id' "
	. "GROUP BY f.folder_id ";
$result = $request_result->handleQuery($conn, $query);
//$result = mysqli_query($conn, $query);
//$folders = array();
if ($result) {
	$request_result->success = 1;
} else {
	$request_result->success = 0;
	echo json_encode($request_result);
}
while ($row = mysqli_fetch_array($result)) {
	$row['description'] != '' ? $description = $row['description'] : $description = 'Anonymous folder';
//	ChromePhp::log( 'mg_get_folders  card_amount for custom folder ' + $description + ' is ' + $row[ 'card_amount' ] );
	array_push($request_result->result, array(
		"folder_id" => $row['folder_id'],
		"description" => $description,
		"cards_amount" => $row['card_amount']));
//	array_push($folders, array("folder_id"=>$row['folder_id'],"description"=>$description));
}

//echo $query . "<br>";
//$result = $r->handleQuery( $conn, $query );
echo json_encode($request_result);
