<?php

require_once('../../session_setup.php');
error_reporting( E_ALL );
ini_set( 'display_errors', 'On' );
require_once '../absolute_database_config.php';
//include_once('../../utilities/request_result.php');

//$request_result = new Result();

$query = "SELECT * FROM folder WHERE user_id = '".$_SESSION['user_id']."' ORDER BY description";
//$result = $request_result->handleQuery($conn,$query);
$result = mysqli_query($conn, $query);
//$request_result->result = array();
$arr = array();
while ($row = mysqli_fetch_array($result)) {
//	array_push($request_result->result, array("folder_id"=>$row['folder_id'],"description"=>$row['description']));
	$row['description'] != '' ? $description = $row['description'] : $description = 'Anonymous folder';
	array_push($arr, array("folder_id"=>$row['folder_id'],"description"=>$description));
}

echo json_encode($arr);