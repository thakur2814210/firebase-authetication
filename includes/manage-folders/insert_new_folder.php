<?php
error_reporting( E_ALL );
ini_set( 'display_errors', 'On' );
require_once('../../session_setup.php');
if ( !isset( $_SESSION[ 'user_id' ] ) )
{
	echo 'expired';
	exit;
}
$folder_description = trim( $_REQUEST[ 'folder_description' ] );
//cadd to db
require_once '../database_config.php';
$query = "SELECT folder_id FROM folder WHERE description='$folder_description' AND user_id='" . $_SESSION[ 'user_id' ] . "'";
$result = mysqli_query($conn, $query);
$data = mysqli_fetch_row($result);
if ($data){
	echo 'exists';
	exit;
}
$folder_id = uniqid();
if ( mysqli_query( $conn, "insert into folder (folder_id, user_id, description) values ('" . $folder_id . "', '" . $_SESSION[ 'user_id' ] . "', '" . $folder_description . "')" ) )
{
	$temp .= "<li><input id='cbx-$folder_id' type='checkbox' class='css-checkbox mg-checkbox' value='$folder_id' />";
	$temp .= "<label for='cbx-$folder_id' class='checkbox css-label mg-checkbox'>$folder_description</label></li>";
	echo $temp;
}
else
{
	echo "false";
}