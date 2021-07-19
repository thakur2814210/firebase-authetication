<?php

require_once('../../session_setup.php');
if (!isset($_SESSION['user_id'])) {
	echo json_encode('expired');
	exit;
}
require_once '../database_config.php';
include_once('../../utilities/request_result.php');
$request_result = new Result();
$mysqli_errors = array();
$mysqli_success = array();
$user_id = $_SESSION["user_id"];
$cards = $_REQUEST["cards"];
$folder_id = $_REQUEST['folder_id'];
$card_ids = explode(',', $cards);
//echo "<pre>";
//var_dump($card_ids);
//echo "</pre>";exit;
foreach ($card_ids as $cid) {
	if ($folder_id === 'pc' || $folder_id === 'sp') {
		//=============QUERY=============
		$query = sprintf("DELETE FROM card_contact WHERE user_id = '%s' AND card_id = '%s'", $user_id, $cid);
		$result = mysqli_query($conn, $query);
		if (!$result) {
			array_push($mysqli_errors, array(
				'mysqli_error' => mysqli_error($conn),
				'executed_query' => $query));
		} else {
			array_push($mysqli_success, $query);
		}
		//=============QUERY=============
		$query = sprintf("UPDATE card_link SET link_status='DELETED_BY_HOLDER' WHERE user_requested_by = '%s' AND card_id_requested = '%s'", $user_id, $cid);
		$result = mysqli_query($conn, $query);
		if (!$result) {
			array_push($mysqli_errors, array(
				'mysqli_error' => mysqli_error($conn),
				'executed_query' => $query));
		} else {
			array_push($mysqli_success, $query);
		}
		$query = sprintf("DELETE FROM card_folder WHERE user_id = '%s' AND card_id = '%s'", $user_id, $cid);
		$result = mysqli_query($conn, $query);
		if (!$result) {
			array_push($mysqli_errors, array(
				'mysqli_error' => mysqli_error($conn),
				'executed_query' => $query));
		} else {
			array_push($mysqli_success, $query);
		}
	} else {
		//if delete from pc o sp delete from card_folder in any folder otherwise only in folder where the user is in
		$query = sprintf("DELETE FROM card_folder WHERE user_id = '%s' AND card_id = '%s' AND folder_id = '%s'", $user_id, $cid, $folder_id);

		$result = mysqli_query($conn, $query);
		if (!$result) {
			array_push($mysqli_errors, array(
				'mysqli_error' => mysqli_error($conn),
				'executed_query' => $query));
		} else {
			array_push($mysqli_success, $query);
		}
	}
}

$request_result->errors = $mysqli_errors;
$request_result->successes = $mysqli_success;
if (count($mysqli_errors) > 0) {
	$request_result->success = 0;
} else {
	$request_result->success = 1;
}
echo json_encode($request_result);
