<?php
require_once('../../session_setup.php');
if ( !isset( $_SESSION[ 'user_id' ] ) )
{
	echo 'expired';
	exit;
}else{
	$user_id = $_SESSION[ 'user_id' ];
}
$folder_id = trim($_REQUEST['folder_id']);
$folder_description = trim($_REQUEST['folder_description']);
//cadd to db
require_once '../database_config.php';
$query = "SELECT folder_id FROM folder WHERE description='$folder_description' AND user_id='$user_id'";
$result = mysqli_query($conn, $query);
$data = mysqli_fetch_row($result);
if ($data){
	echo 'exists';
	exit;
}
mysqli_query($conn, "update folder set description = '".$folder_description."' where folder_id = '".$folder_id."'");
echo 'ok';
//$res = array($user_id,$folder_id,$folder_description);
//echo json_encode($res);