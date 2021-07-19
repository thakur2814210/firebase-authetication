<?php

error_reporting( E_ALL );
ini_set( 'display_errors', 'On' );
require_once('../../session_setup.php');
//cadd to db
require_once '../database_config.php';
if ( !isset( $_SESSION[ 'user_id' ] ) )
{
	echo 'expired';
	exit;
}
$comment_id = uniqid();
$comment = addslashes( trim( $_REQUEST[ 'comment' ] ) );
if ( empty( $comment ) )
{
	echo "empty";
	exit;
}
$query = "insert into card_comment (comment_id, user_id, card_id, comment) values ('" . $comment_id . "', '" . $_SESSION[ 'user_id' ] . "', '" . $_SESSION[ 'card_view' ][ 'card_id' ] . "', '" . $comment . "')";
print_r( $query );
mysqli_query( $conn, $query );
echo mysqli_error( $conn );
