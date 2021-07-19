<?php

ob_start();
require_once('../../session_setup.php');
require_once '../absolute_database_config.php';
if ( !isset( $_SESSION[ 'user_id' ] ) )
{
	echo 'expired';
	exit;
}
$card_id = $_REQUEST[ 'card_id' ];
$_SESSION[ 'card_view' ][ 'card_id' ] = $card_id;
$sql = "select private, rating from card_contact where user_id = '" . $_SESSION[ 'user_id' ] . "' and card_id = '$card_id';";
$result = mysqli_query( $conn, $sql );
$new_data = array();
while ( $row = mysqli_fetch_array( $result ) )
{
	$new_data = array(
			"private" => $row[ 'private' ],
			"rating" => $row[ 'rating' ] );
}
ob_end_flush();
echo json_encode( $new_data );

