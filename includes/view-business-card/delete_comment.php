<?php
require_once('../../session_setup.php');
if ( !isset( $_SESSION[ 'user_id' ] ) )
{
	echo 'expired';
	exit;
}
//cadd to db
require_once '../database_config.php';
$comment_id = trim($_REQUEST['comment_id']);
$query = "delete from card_comment where comment_id = '".$comment_id."';";
//print_r($query);
mysqli_query($conn, $query);
