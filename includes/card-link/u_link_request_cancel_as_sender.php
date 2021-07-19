<?php

require_once('../../session_setup.php');

require_once '../database_config.php';
include_once('../../utilities/request_result.php');
if ( !isset( $_SESSION[ 'user_id' ] ) )
{
	echo 'expired';
	exit;
}

$request_result = new Result();

$mysqli_errors = array();
$mysqli_success = array();

$query = sprintf( "UPDATE card_link SET link_status='CANCELED_BY_SENDER' WHERE user_requested_by = '%s' AND card_link = '%s' ORDER BY date_accepted DESC LIMIT 1", $_SESSION[ "user_id" ], $_REQUEST[ "card_id" ] );

//=============QUERY=============
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
?>