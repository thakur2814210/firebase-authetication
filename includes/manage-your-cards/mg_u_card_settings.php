<?php

error_reporting( E_ALL );
ini_set( 'display_errors', 'On' );
require_once('../../session_setup.php');
if ( !isset( $_SESSION[ 'user_id' ] ) )
{
	echo json_encode( array(
			'status' => 'expired' ) );
	exit;
}
$card_id = trim( $_GET[ 'card_id' ] );
$card_type = trim( $_GET[ 'card_type' ] );

require_once '../database_config.php';
if ( isset( $_POST[ 'settings' ] ) )
{
	$settings = $_POST[ 'settings' ];
}
else
{
	$settings = array();
}
if ( strtolower( $card_type ) == 'personal' || strtolower( $card_type ) == 'corporate' )
{
	$table_name = 'personal_card_setting';
	$settings_options = array(
			"global_search",
			"seen_in_user_folder",
			"share_among_users",
			"need_approval",
			"requires_reciprocity" );
	$query = "SELECT global_search, seen_in_user_folder, share_among_users, need_approval, requires_reciprocity from $table_name WHERE card_id='$card_id'";
	$result = mysqli_query( $conn, $query );
//	echo mysqli_error($conn);
	$set_settings = array();
	$row = mysqli_fetch_row( $result );
	if ( $row[ 0 ] == 1 )
	{
		$set_settings[] = 'global_search';
	}
	if ( $row[ 1 ] == 1 )
	{
		$set_settings[] = 'seen_in_user_folder';
	}
	if ( $row[ 2 ] == 1 )
	{
		$set_settings[] = 'share_among_users';
	}
	if ( $row[ 3 ] == 1 )
	{
		$set_settings[] = 'need_approval';
	}
	if ( $row[ 4 ] == 1 )
	{
		$set_settings[] = 'requires_reciprocity';
	}
	$unset_settings = array_diff( $settings_options, $settings );
	if (count($set_settings) > count($settings))
	{
		$changed_settings = array_diff( $set_settings, $settings );
	}
	else /*if (count($settings) > count($set_settings))*/
	{
		$changed_settings = array_diff( $settings, $set_settings );
	}

	if ( count( $changed_settings ) == 0 )
	{
		echo "unchanged";
		exit;
	}
	$query_update = "";
	foreach ( $settings as $v )
	{
		$query_update .= "$v='1',";
	}
	foreach ( $unset_settings as $v )
	{
		$query_update .= "$v='0',";
	}
	$query_update = rtrim( $query_update, ',' );
	$sql = "UPDATE $table_name SET $query_update WHERE card_id = '$card_id'";
//echo $sql;exit;
	if ( mysqli_query( $conn, $sql ) )
	{
		$result = 'success';
	}
	else
	{
		$result = 'error';
	}
//echo "$sql<br>";
//echo mysqli_error( $conn ) . "<br>";
	echo $result;
}
else
{
	$table_name = 'professional_card_setting';
	$settings_options = array(
			"visible_pp_search",
			"allow_rating" );
	$query = "SELECT visible_pp_search, allow_rating from $table_name WHERE card_id='$card_id'";
	$result = mysqli_query( $conn, $query );
	$set_settings = array();
	$row = mysqli_fetch_row( $result );
	if ( $row[ 0 ] == 1 )
	{
		$set_settings[] = 'visible_pp_search';
	}
	if ( $row[ 1 ] == 1 )
	{
		$set_settings[] = 'allow_rating';
	}
	$unset_settings = array_diff( $set_settings, $settings );
	$added_settings = array_diff( $settings, $set_settings );
	if ( count( $added_settings ) == 0 && count( $unset_settings ) == 0 )
	{
		echo json_encode( array(
				"status" => "unchanged" ) );
	}
	else
	{
		$result = array(
				"status" => "changed" );
		$result[ 'wanted_settings' ] = implode( ',', $settings );
//		if ( count( $added_settings ) > 0 )
//		{
//			if ( count( $unset_settings ) == 0 )
//			{
//				$result[ 'wanted_settings' ] = implode( ',', $settings );
//			}
//			else
//			{
//
//			}
//		}
//		else
//		{
//			if ( count( $unset_settings ) > count( $set_settings ) )
//			{
//				$wanted_settings = array_diff( $unset_settings, $set_settings );
//				$result[ 'wanted_settings' ] = implode( ',', $wanted_settings );
//			}
//			else if ( count( $unset_settings ) < count( $set_settings ) )
//			{
//				$wanted_settings = array_diff( $set_settings, $unset_settings );
//				$result[ 'wanted_settings' ] = implode( ',', $wanted_settings );
//			}
//			else
//			{
//				$wanted_settings = array();
//				$result[ 'wanted_settings' ] = implode( ',', $wanted_settings );
//			}
		}
		echo json_encode( $result );
//	}
//	else
//	{
//		echo implode( ',', $added_settings );
//	}
}
