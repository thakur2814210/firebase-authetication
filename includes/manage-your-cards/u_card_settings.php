<?php

class Result
{
	var $code;
	var $card_id;
	var $message;
//		var $folder_name;
	var $result;
	var $query;
	var $err;
}
require_once('../../session_setup.php');
if ( !isset( $_SESSION[ 'user_id' ] ) )
{
	echo json_encode( 'expired' );
	exit;
}
$card_id = trim( $_GET[ 'card_id' ] );
$card_type = trim( $_GET[ 'card_type' ] );
require_once '../database_config.php';

$update_query = "";

if ( isset( $_POST[ 'settings' ] ) )
{
	$settings = $_POST[ 'settings' ];
}
else
{
	$settings = array();
}

require 'sets.php';

# Create some sets. Parameters are label and universe (both optional).
$set1 = new Set( "No Settings", "Settings" );
if ( $card_type == "Personal" || $card_type == "Corporate" )
{
	//$set1->add(array("global_search", "share_among_users", "seen_in_user_folder", "requires_reciprocity", "need_approval"));
	$set1->add( array(
			"global_search",
			"seen_in_user_folder",
			"requires_reciprocity",
			"need_approval" ) );
}
else if ( $card_type == "Professional" || $card_type == "Product" )
{
	//$set1->add(array("visible_pp_search", "share_among_users", "allow_rating"));
	$set1->add( array(
			"visible_pp_search",
			"allow_rating" ) );
}

$set3 = new Set( "Client Settings Checked", "Settings" );
$set3->add( $settings );

# Perform set operations. All set operations return a 'result' set except isSubset() and isSuperset().

$resultSet = $set1->symDiff( $set3 );

$update_set_query = "";
foreach ( $settings as $key => $value )
{
	$update_set_query.= $value . " = 1,";
}
foreach ( $resultSet->get() as $key => $value )
{
	$update_set_query.= $value . " = 0,";
}
//remove trailing comma
$update_set_query = substr( $update_set_query, 0, -1 );

if ( $card_type == "Personal" || $card_type == "Corporate" )
{
	$update_query = "UPDATE personal_card_setting SET " . $update_set_query . " WHERE card_id = '" . $card_id . "'";
}
else if ( $card_type == "Professional" || $card_type == "Product" )
{
	$update_query = "UPDATE professional_card_setting SET " . $update_set_query . " WHERE card_id = '" . $card_id . "'";
}
else
{
	//TODO: return error (unsure about best way atm)
}

$result = mysqli_query( $conn, $update_query );

$newResult = new Result();
$newResult->card_id = $card_id;
$newResult->result = $result;
$newResult->query = $update_query;

if ( !$result )
{
	$newResult->code = 0;
	$newResult->message = "The card settings were unsuccessfully updated";
	$newResult->err = mysqli_error( $conn );
}
else
{
	$newResult->code = 1;
	$newResult->message = "The card settings were successfully updated";
}

// $count = mysqli_affected_rows($conn);
// if($count>0){
// 	$newResult->code = 1;
// 	$newResult->message = "The card settings were successfully updated";
// }
// else{
// 	$newResult->code = 0;
// 	$newResult->message = "The card settings were unsuccessfully updated";
// }

echo json_encode( $newResult );
