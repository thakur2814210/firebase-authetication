<?php

require_once('../../session_setup.php');
if ( !isset( $_SESSION[ 'user_id' ] ) )
{
	echo 'expired';
	exit;
}
$card_id = trim( $_REQUEST[ 'card_id' ] );
if (strpos($card_id, ',') !== false)
{
	$card_ids = explode(',', $card_id);
}
$folder_ids = trim( $_REQUEST[ 'folder_ids' ] );
$folder_id_array = explode( ",", $folder_ids );
//cadd to db
require_once '../database_config.php';
if (is_array($card_ids))
{
	foreach ($card_ids as $c_id)
	{
		foreach ( $folder_id_array as $folder_id )
		{
			$query = "delete from card_folder where user_id = '" . $_SESSION[ 'user_id' ] . "' and card_id = '$c_id' AND folder_id = '$folder_id';";
			$result = mysqli_query( $conn, $query );
			$sql = "insert into card_folder (user_id, card_id, folder_id) values ('" . $_SESSION[ 'user_id' ] . "', '" . $c_id . "', '" . $folder_id . "')";
			$result = mysqli_query( $conn, $sql );
			print_r( $sql );
		}
	}
}
else
{
	$query = "delete from card_folder where user_id = '" . $_SESSION[ 'user_id' ] . "' and card_id = '" . $card_id . "';";
	$result = mysqli_query( $conn, $query );
	foreach ( $folder_id_array as $folder_id )
	{
		$sql = "insert into card_folder (user_id, card_id, folder_id) values ('" . $_SESSION[ 'user_id' ] . "', '" . $card_id . "', '" . $folder_id . "')";
		$result = mysqli_query( $conn, $sql );
		print_r( $sql );
	}
}
//print_r( $query );
