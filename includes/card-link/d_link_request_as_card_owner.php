<?php

require_once('../../session_setup.php');

require_once '../database_config.php';
include_once('models.php');
include_once('../../utilities/request_result.php');
if ( !isset( $_SESSION[ 'user_id' ] ) )
{
	echo json_encode('expired');
	exit;
}

$request_result = new Result();

$mysqli_errors = array();
$mysqli_success = array();

$card_link_id = trim( $_POST[ "card_link_id" ] );

$card_id = "";
$card_type = "";
$user_requested_by = "";

//=============QUERY=============
$query = sprintf( "SELECT c.card_id,c.card_type,cl.user_requested_by FROM card_link cl JOIN card c ON cl.card_id_requested = c.card_id  WHERE cl.card_link = '%s'", $card_link_id );
$result = mysqli_query( $conn, $query );
if ( !$result )
{
	array_push( $mysqli_errors, array(
			'mysqli_error' => mysqli_error( $conn ),
			'executed_query' => $query ) );
}
else
{
	array_push( $mysqli_success, $query );
}
while ( $card_type_row = mysqli_fetch_array( $result ) )
{
	$card_id = $card_type_row[ "card_id" ];
	$card_type = $card_type_row[ "card_type" ];
	$user_requested_by = $card_type_row[ "user_requested_by" ];
}
//=============QUERY=============
//=============QUERY=============
$query = sprintf( "DELETE FROM card_contact WHERE card_id = '%s' AND user_id = '%s'", $card_id, $user_requested_by );
$result = mysqli_query( $conn, $query );
if ( !$result )
{
	array_push( $mysqli_errors, array(
			'mysqli_error' => mysqli_error( $conn ),
			'executed_query' => $query ) );
}
else
{
	array_push( $mysqli_success, $query );
}
//=============QUERY=============
//=============QUERY=============
$query = sprintf( "UPDATE card_link SET link_status = '%s' WHERE card_link = '%s'", "DELETED_BY_OWNER", $card_link_id, $_SESSION[ "user_id" ] );
$result = mysqli_query( $conn, $query );
if ( !$result )
{
	array_push( $mysqli_errors, array(
			'mysqli_error' => mysqli_error( $conn ),
			'executed_query' => $query ) );
}
else
{
	array_push( $mysqli_success, $query );
}
//=============QUERY=============

$request_result->errors = $mysqli_errors;
$request_result->successes = $mysqli_success;

if ( count( $mysqli_errors ) > 0 )
{
	$request_result->success = 0;
}
else
{
	$request_result->success = 1;
}

echo json_encode( $request_result );
