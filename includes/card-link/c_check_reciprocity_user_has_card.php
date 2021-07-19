<?php

require_once('../../session_setup.php');
require_once '../database_config.php';
include_once('../../utilities/request_result.php');
if ( !isset( $_SESSION[ 'user_id' ] ) )
{
	echo json_encode('expired');
	exit;
}
$r = new Result();
$user_id = $_SESSION[ 'user_id' ];
$query = sprintf( "SELECT count(1) AS total FROM card WHERE user_id = '%s'", $user_id );
$result = $r->handleQuery( $conn, $query );
while ( $row = mysqli_fetch_array( $result ) )
{
	$total = $row[ "total" ];
}
$r->result = $total;
echo json_encode( $r );
